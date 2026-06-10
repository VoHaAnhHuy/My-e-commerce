<?php

namespace App\Repositories\Eloquent;

use App\Models\Coupon;
use App\Repositories\Interfaces\CouponRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CouponRepository extends BaseRepository implements CouponRepositoryInterface
{
    public function __construct(Coupon $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code): ?Model
    {
        return $this->model->where('code', $code)->first();
    }

    public function incrementUsedCount(int $id): void
    {
        $this->model->where('id', $id)->increment('used_count');
    }
}
