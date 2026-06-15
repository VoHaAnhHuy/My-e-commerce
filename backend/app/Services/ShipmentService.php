<?php

namespace App\Services;

use App\Repositories\Interfaces\ShipmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ShipmentService
{
    public function __construct(
        protected ShipmentRepositoryInterface $shipmentRepo,
    ) {}

    public function getByOrder(int $orderId): Collection
    {
        return $this->shipmentRepo->getByOrder($orderId);
    }

    public function getAll(): Collection
    {
        return $this->shipmentRepo->getAll();
    }

    public function getById(int $id): ?Model
    {
        return $this->shipmentRepo->getById($id)?->load('orderItems');
    }

    public function create(array $data): Model
    {
        return $this->shipmentRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->shipmentRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->shipmentRepo->delete($id);
    }
}
