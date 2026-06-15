<?php

namespace App\Repositories\Interfaces;

interface ShipmentRepositoryInterface extends BaseRepositoryInterface
{
    public function getByOrder(int $orderId);
}
