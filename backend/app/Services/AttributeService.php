<?php

namespace App\Services;

use App\Repositories\Interfaces\AttributeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AttributeService
{
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->attributeRepo->getAll();
    }

    public function getById(int $id): ?Model
    {
        return $this->attributeRepo->getById($id);
    }

    public function create(array $data): Model
    {
        return $this->attributeRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->attributeRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->attributeRepo->delete($id);
    }
}
