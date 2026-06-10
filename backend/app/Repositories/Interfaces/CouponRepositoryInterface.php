<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CouponRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Tìm coupon theo mã code.
     */
    public function findByCode(string $code): ?Model;

    /**
     * Tăng used_count.
     */
    public function incrementUsedCount(int $id): void;
}
