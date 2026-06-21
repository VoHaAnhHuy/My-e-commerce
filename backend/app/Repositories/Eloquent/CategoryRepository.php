<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getRootCategories(): Collection
    {
        return $this->model
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();
    }

    public function getWithChildren(): Collection
    {
        return $this->model
            ->with('children')
            ->orderBy('sort_order')
            ->get();
    }

    public function findBySlug(string $slug): ?Model
    {
        return $this->model
            ->where('slug', $slug)
            ->with('children', 'products')
            ->first();
    }
}
