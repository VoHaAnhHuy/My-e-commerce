<?php

namespace App\Services;

use App\Repositories\Interfaces\RefundRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RefundService
{
    public function __construct(
        protected RefundRepositoryInterface $refundRepo,
    ) {}

    public function getByOrder(int $orderId): Collection
    {
        return $this->refundRepo->getByOrder($orderId);
    }

    public function getAll(): Collection
    {
        return $this->refundRepo->getAll();
    }

    public function getById(int $id): ?Model
    {
        return $this->refundRepo->getById($id)?->load('order', 'paymentTransaction');
    }

    public function create(array $data): Model
    {
        return $this->refundRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->refundRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->refundRepo->delete($id);
    }
}
