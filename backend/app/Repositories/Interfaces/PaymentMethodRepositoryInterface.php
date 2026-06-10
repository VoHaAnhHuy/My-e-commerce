<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface PaymentMethodRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy danh sách phương thức thanh toán đang hoạt động.
     */
    public function getActive(): Collection;
}
