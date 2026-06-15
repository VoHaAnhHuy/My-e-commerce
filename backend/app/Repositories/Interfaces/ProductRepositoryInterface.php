<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy sản phẩm đang active (hiển thị cho khách).
     */
    public function getActive(): Collection;

    /**
     * Tìm sản phẩm theo slug.
     */
    public function findBySlug(string $slug): ?Model;

    /**
     * FR-CAT-002: Lấy tất cả sản phẩm cho admin.
     */
    public function getAllForAdmin(): Collection;

    /**
     * FR-CAT-002: Lấy sản phẩm có thể tìm kiếm (loại trừ archived).
     */
    public function getSearchable(): Collection;
}
