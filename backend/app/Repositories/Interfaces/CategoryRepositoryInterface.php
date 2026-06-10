<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy danh mục gốc (không có parent).
     */
    public function getRootCategories(): Collection;

    /**
     * Lấy tất cả kèm children (tree).
     */
    public function getWithChildren(): Collection;

    /**
     * Tìm theo slug.
     */
    public function findBySlug(string $slug): ?Model;
}
