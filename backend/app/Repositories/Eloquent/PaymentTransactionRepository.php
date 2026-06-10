<?php

namespace App\Repositories\Eloquent;

use App\Models\PaymentTransaction;
use App\Repositories\Interfaces\PaymentTransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PaymentTransactionRepository extends BaseRepository implements PaymentTransactionRepositoryInterface
{
    public function __construct(PaymentTransaction $model)
    {
        parent::__construct($model);
    }

    public function getByOrder(int $orderId): Collection
    {
        return $this->model->where('order_id', $orderId)->orderByDesc('created_at')->get();
    }
}
