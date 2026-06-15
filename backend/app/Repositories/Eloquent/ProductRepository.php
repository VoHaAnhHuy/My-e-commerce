<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getActive(): Collection
    {
        return $this->model
            ->active()
            ->with('categories', 'variants')
            ->get();
    }

    public function findBySlug(string $slug): ?Model
    {
        return $this->model
            ->where('slug', $slug)
            ->with('categories', 'variants.attributeValues.attribute', 'images')
            ->first();
    }

    /**
     * FR-CAT-002: Lấy tất cả sản phẩm cho admin (bao gồm mọi trạng thái).
     */
    public function getAllForAdmin(): Collection
    {
        return $this->model
            ->with('categories', 'variants')
            ->get();
    }

    /**
     * FR-CAT-002: Lấy sản phẩm có thể tìm kiếm (loại trừ archived).
     */
    public function getSearchable(): Collection
    {
        return $this->model
            ->searchable()
            ->with('categories', 'variants')
            ->get();
    }
}
