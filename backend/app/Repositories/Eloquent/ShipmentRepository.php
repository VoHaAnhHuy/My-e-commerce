<?php

namespace App\Repositories\Eloquent;

use App\Models\Shipment;
use App\Repositories\Interfaces\ShipmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ShipmentRepository extends BaseRepository implements ShipmentRepositoryInterface
{
    public function __construct(Shipment $model)
    {
        parent::__construct($model);
    }

    public function getByOrder(int $orderId): Collection
    {
        return $this->model->where('order_id', $orderId)->with('orderItems')->orderByDesc('created_at')->get();
    }
}
