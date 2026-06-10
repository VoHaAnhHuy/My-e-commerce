<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ProductVariantRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy tất cả variants của product.
     */
    public function getByProduct(int $productId): Collection;
}
