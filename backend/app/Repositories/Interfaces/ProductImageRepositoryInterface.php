<?php

namespace App\Repositories\Interfaces;

interface ProductImageRepositoryInterface extends BaseRepositoryInterface
{
    public function getByProduct(int $productId);
}
