<?php

namespace App\Services;

use App\Repositories\Interfaces\AttributeValueRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AttributeValueService
{
    public function __construct(
        protected AttributeValueRepositoryInterface $attrValueRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->attrValueRepo->getAll();
    }

    public function getByAttribute(int $attributeId): Collection
    {
        return $this->attrValueRepo->getByAttribute($attributeId);
    }

    public function getById(int $id): ?Model
    {
        return $this->attrValueRepo->getById($id);
    }

    public function create(array $data): Model
    {
        return $this->attrValueRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->attrValueRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->attrValueRepo->delete($id);
    }
}
