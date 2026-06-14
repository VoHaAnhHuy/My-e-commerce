<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy hoặc tạo cart cho user đã đăng nhập.
     */
    public function getOrCreateByUser(int $userId): Model;

    /**
     * Lấy hoặc tạo cart cho guest (cart_token).
     */
    public function getOrCreateByToken(string $cartToken): Model;

    /**
     * Lấy cart active theo cart_token (không tạo mới).
     */
    public function findActiveByToken(string $cartToken): ?Model;

    /**
     * Merge guest cart vào user cart.
     * Trả về user cart đã merge.
     */
    public function mergeGuestCart(string $cartToken, int $userId): Model;
}
