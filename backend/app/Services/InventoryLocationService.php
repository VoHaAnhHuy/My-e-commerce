<?php

namespace App\Services;

use App\Repositories\Interfaces\InventoryLocationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class InventoryLocationService
{
    public function __construct(
        protected InventoryLocationRepositoryInterface $locationRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->locationRepo->getAll();
    }

    public function getById(int $id): ?Model
    {
        return $this->locationRepo->getById($id)?->load('stocks');
    }

    public function create(array $data): Model
    {
        return $this->locationRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->locationRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->locationRepo->delete($id);
    }
}
