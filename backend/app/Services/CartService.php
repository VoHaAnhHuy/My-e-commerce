<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\InventoryStock;
use App\Repositories\Interfaces\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepo,
    ) {
    }

    /**
     * Resolve cart hiện tại: ưu tiên user_id, fallback cart_token.
     */
    protected function resolveCart(?int $userId, ?string $cartToken): Model
    {
        if ($userId) {
            return $this->cartRepo->getOrCreateByUser($userId);
        }

        if (!$cartToken) {
            throw ValidationException::withMessages([
                'cart_token' => ['Vui lòng cung cấp header X-Cart-Token để sử dụng giỏ hàng.'],
            ]);
        }

        return $this->cartRepo->getOrCreateByToken($cartToken);
    }

    /**
     * Kiểm tra cart item có thuộc cart hiện tại không.
     */
    protected function authorizeCartItem(CartItem $item, ?int $userId, ?string $cartToken): bool
    {
        $cart = $item->cart;

        if ($userId && $cart->user_id === $userId) {
            return true;
        }

        if ($cartToken && $cart->cart_token === $cartToken) {
            return true;
        }

        return false;
    }

    /**
     * Kiểm tra tồn kho có đủ không.
     *
     * available = SUM(quantity_on_hand) - SUM(quantity_reserved)
     */
    protected function checkStock(int $variantId, int $requestedQty): void
    {
        $stock = InventoryStock::where('variant_id', $variantId)
            ->selectRaw('COALESCE(SUM(quantity_on_hand), 0) - COALESCE(SUM(quantity_reserved), 0) as available')
            ->value('available');

        if ($stock !== null && $stock < $requestedQty) {
            throw ValidationException::withMessages([
                'quantity' => ["Sản phẩm chỉ còn {$stock} trong kho."],
            ]);
        }
    }

    /**
     * Lấy cart hiện tại (kèm items + product info).
     */
    public function getCart(?int $userId, ?string $cartToken): Model
    {
        $cart = $this->resolveCart($userId, $cartToken);

        return $cart->load('items.productVariant.product', 'items.productVariant.images');
    }

    /**
     * Thêm item vào cart. Nếu variant đã có → tăng quantity.
     * Kiểm tra tồn kho trước khi thêm.
     */
    public function addItem(?int $userId, ?string $cartToken, array $data): Model
    {
        $cart = $this->resolveCart($userId, $cartToken);

        $variantId = $data['product_variant_id'];
        $requestedQty = $data['quantity'];

        // Tính tổng quantity sau khi thêm
        $existingItem = $cart->items()
            ->where('product_variant_id', $variantId)
            ->first();

        $totalQty = $existingItem
            ? $existingItem->quantity + $requestedQty
            : $requestedQty;

        // Kiểm tra tồn kho
        $this->checkStock($variantId, $totalQty);

        if ($existingItem) {
            $existingItem->increment('quantity', $requestedQty);
        } else {
            $cart->items()->create($data);
        }

        return $cart->load('items.productVariant.product', 'items.productVariant.images');
    }

    /**
     * Cập nhật quantity của item.
     * Kiểm tra authorization + tồn kho.
     */
    public function updateItem(int $cartItemId, int $quantity, ?int $userId, ?string $cartToken): ?CartItem
    {
        $item = CartItem::with('cart')->find($cartItemId);

        if (!$item) {
            return null;
        }

        // Authorization: item phải thuộc cart của user/guest hiện tại
        if (!$this->authorizeCartItem($item, $userId, $cartToken)) {
            abort(403, 'Bạn không có quyền cập nhật sản phẩm này.');
        }

        // Kiểm tra tồn kho
        $this->checkStock($item->product_variant_id, $quantity);

        $item->update(['quantity' => $quantity]);

        return $item->fresh();
    }

    /**
     * Xóa item khỏi cart.
     * Kiểm tra authorization.
     */
    public function removeItem(int $cartItemId, ?int $userId, ?string $cartToken): bool
    {
        $item = CartItem::with('cart')->find($cartItemId);

        if (!$item) {
            return false;
        }

        // Authorization
        if (!$this->authorizeCartItem($item, $userId, $cartToken)) {
            abort(403, 'Bạn không có quyền xóa sản phẩm này.');
        }

        return $item->delete();
    }

    /**
     * Xóa toàn bộ items trong cart.
     */
    public function clearCart(?int $userId, ?string $cartToken): bool
    {
        $cart = $this->resolveCart($userId, $cartToken);

        return (bool) $cart->items()->delete();
    }

    /**
     * Merge guest cart vào user cart khi đăng nhập.
     */
    public function mergeCart(string $cartToken, int $userId): Model
    {
        return $this->cartRepo->mergeGuestCart($cartToken, $userId);
    }
}
