<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface PaymentTransactionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy tất cả giao dịch của một đơn hàng.
     */
    public function getByOrder(int $orderId): Collection;
}
