<?php

namespace App\Services;

use App\Repositories\Interfaces\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ReviewService
{
    public function __construct(
        protected ReviewRepositoryInterface $reviewRepo,
    ) {}

    public function getByProduct(int $productId): Collection
    {
        return $this->reviewRepo->getByProduct($productId);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->reviewRepo->getByUser($userId);
    }

    public function getById(int $id): ?Model
    {
        return $this->reviewRepo->getById($id);
    }

    public function create(array $data): Model
    {
        return $this->reviewRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->reviewRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->reviewRepo->delete($id);
    }
}
