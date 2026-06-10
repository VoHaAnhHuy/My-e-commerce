<?php

namespace App\Services;

use App\Repositories\Interfaces\PaymentTransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PaymentTransactionService
{
    public function __construct(
        protected PaymentTransactionRepositoryInterface $transactionRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->transactionRepo->getAll();
    }

    public function getByOrder(int $orderId): Collection
    {
        return $this->transactionRepo->getByOrder($orderId);
    }

    public function getById(int $id): ?Model
    {
        return $this->transactionRepo->getById($id);
    }

    public function create(array $data): Model
    {
        return $this->transactionRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->transactionRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->transactionRepo->delete($id);
    }
}
