<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Repositories\Interfaces\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }

    /**
     * Lấy hoặc tạo cart active cho user đã đăng nhập.
     */
    public function getOrCreateByUser(int $userId): Model
    {
        return $this->model->firstOrCreate(
            ['user_id' => $userId, 'status' => 'active'],
            ['user_id' => $userId]
        );
    }

    /**
     * Lấy hoặc tạo cart active cho guest (cart_token).
     * Tự động set expires_at = 30 ngày.
     */
    public function getOrCreateByToken(string $cartToken): Model
    {
        return $this->model->firstOrCreate(
            ['cart_token' => $cartToken, 'status' => 'active'],
            [
                'cart_token'  => $cartToken,
                'expires_at'  => now()->addDays(30),
            ]
        );
    }

    /**
     * Lấy cart active theo cart_token (không tạo mới).
     */
    public function findActiveByToken(string $cartToken): ?Model
    {
        return $this->model
            ->where('cart_token', $cartToken)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Merge guest cart (theo cart_token) vào user cart.
     *
     * Logic:
     * 1. Lấy guest cart + user cart
     * 2. Với mỗi item trong guest cart:
     *    - Nếu user cart đã có cùng variant → cộng dồn quantity
     *    - Nếu chưa có → chuyển item sang user cart
     * 3. Đánh dấu guest cart là 'converted'
     * 4. Trả về user cart đã merge
     */
    public function mergeGuestCart(string $cartToken, int $userId): Model
    {
        return DB::transaction(function () use ($cartToken, $userId) {
            $userCart  = $this->getOrCreateByUser($userId);
            $guestCart = $this->findActiveByToken($cartToken);

            if (!$guestCart || $guestCart->id === $userCart->id) {
                return $userCart->load('items.productVariant');
            }

            foreach ($guestCart->items as $guestItem) {
                $existingItem = $userCart->items()
                    ->where('product_variant_id', $guestItem->product_variant_id)
                    ->first();

                if ($existingItem) {
                    // Cộng dồn quantity
                    $existingItem->increment('quantity', $guestItem->quantity);
                } else {
                    // Chuyển item sang user cart
                    $guestItem->update(['cart_id' => $userCart->id]);
                }
            }

            // Đánh dấu guest cart là converted
            $guestCart->update(['status' => 'converted']);

            return $userCart->fresh()->load('items.productVariant');
        });
    }
}
