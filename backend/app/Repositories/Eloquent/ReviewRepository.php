<?php

namespace App\Repositories\Eloquent;

use App\Models\Review;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    public function getByProduct(int $productId): Collection
    {
        return $this->model->where('product_id', $productId)->with('user')->orderByDesc('created_at')->get();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->with('product')->orderByDesc('created_at')->get();
    }
}
