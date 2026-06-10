<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy sản phẩm đang active.
     */
    public function getActive(): Collection;

    /**
     * Tìm sản phẩm theo slug.
     */
    public function findBySlug(string $slug): ?Model;
}
