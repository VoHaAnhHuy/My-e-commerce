<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductImage;
use App\Repositories\Interfaces\ProductImageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductImageRepository extends BaseRepository implements ProductImageRepositoryInterface
{
    public function __construct(ProductImage $model)
    {
        parent::__construct($model);
    }

    public function getByProduct(int $productId): Collection
    {
        return $this->model->where('product_id', $productId)->orderBy('sort_order')->get();
    }
}
