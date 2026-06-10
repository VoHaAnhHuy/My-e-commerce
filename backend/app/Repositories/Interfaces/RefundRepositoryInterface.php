<?php

namespace App\Repositories\Interfaces;

interface RefundRepositoryInterface extends BaseRepositoryInterface
{
    public function getByOrder(int $orderId);
}
