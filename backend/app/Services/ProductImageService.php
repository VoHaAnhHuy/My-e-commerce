<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductImageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductImageService
{
    public function __construct(
        protected ProductImageRepositoryInterface $imageRepo,
    ) {}

    public function getByProduct(int $productId): Collection
    {
        return $this->imageRepo->getByProduct($productId);
    }

    public function getById(int $id): ?Model
    {
        return $this->imageRepo->getById($id);
    }

    public function create(array $data): Model
    {
        return $this->imageRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->imageRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->imageRepo->delete($id);
    }
}
