<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->categoryRepo->getRootCategories();
    }

    public function getById(int $id): ?Model
    {
        return $this->categoryRepo->getById($id)?->load('children', 'products');
    }

    public function findBySlug(string $slug): ?Model
    {
        return $this->categoryRepo->findBySlug($slug);
    }

    public function create(array $data): Model
    {
        return $this->categoryRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->categoryRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->categoryRepo->delete($id);
    }
}
