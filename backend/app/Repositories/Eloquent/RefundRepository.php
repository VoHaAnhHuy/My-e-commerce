<?php

namespace App\Repositories\Eloquent;

use App\Models\Refund;
use App\Repositories\Interfaces\RefundRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RefundRepository extends BaseRepository implements RefundRepositoryInterface
{
    public function __construct(Refund $model)
    {
        parent::__construct($model);
    }

    public function getByOrder(int $orderId): Collection
    {
        return $this->model->where('order_id', $orderId)->with('paymentTransaction')->orderByDesc('created_at')->get();
    }
}
