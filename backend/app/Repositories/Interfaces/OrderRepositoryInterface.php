<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy tất cả đơn hàng của user.
     */
    public function getByUser(int $userId): Collection;

    /**
     * Cập nhật trạng thái đơn hàng.
     */
    public function updateStatus(int $id, string $status): bool;
}
