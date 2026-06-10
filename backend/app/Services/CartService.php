<?php

namespace App\Services;

use App\Models\CartItem;
use App\Repositories\Interfaces\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CartService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepo,
    ) {}

    /**
     * Lấy cart hiện tại (kèm items).
     */
    public function getCart(?int $userId, ?string $sessionId): Model
    {
        $cart = $userId
            ? $this->cartRepo->getOrCreateByUser($userId)
            : $this->cartRepo->getOrCreateBySession($sessionId);

        return $cart->load('items.productVariant');
    }

    /**
     * Thêm item vào cart. Nếu variant đã có → tăng quantity.
     */
    public function addItem(?int $userId, ?string $sessionId, array $data): Model
    {
        $cart = $userId
            ? $this->cartRepo->getOrCreateByUser($userId)
            : $this->cartRepo->getOrCreateBySession($sessionId);

        $existingItem = $cart->items()
            ->where('product_variant_id', $data['product_variant_id'])
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $data['quantity']);
        } else {
            $cart->items()->create($data);
        }

        return $cart->load('items.productVariant');
    }

    /**
     * Cập nhật quantity của item.
     */
    public function updateItem(int $cartItemId, int $quantity): ?CartItem
    {
        $item = CartItem::find($cartItemId);

        if (!$item) {
            return null;
        }

        $item->update(['quantity' => $quantity]);

        return $item->fresh();
    }

    /**
     * Xóa item khỏi cart.
     */
    public function removeItem(int $cartItemId): bool
    {
        $item = CartItem::find($cartItemId);

        return $item ? $item->delete() : false;
    }

    /**
     * Xóa toàn bộ items trong cart.
     */
    public function clearCart(?int $userId, ?string $sessionId): bool
    {
        $cart = $userId
            ? $this->cartRepo->getOrCreateByUser($userId)
            : $this->cartRepo->getOrCreateBySession($sessionId);

        return (bool) $cart->items()->delete();
    }
}
