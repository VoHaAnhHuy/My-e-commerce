<?php

namespace App\Repositories\Interfaces;

interface ReviewRepositoryInterface extends BaseRepositoryInterface
{
    public function getByProduct(int $productId);
    public function getByUser(int $userId);
}
