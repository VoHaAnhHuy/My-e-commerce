<?php

namespace App\Services;

use App\Repositories\Interfaces\PaymentMethodRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodService
{
    public function __construct(
        protected PaymentMethodRepositoryInterface $paymentMethodRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->paymentMethodRepo->getAll();
    }

    public function getActive(): Collection
    {
        return $this->paymentMethodRepo->getActive();
    }

    public function getById(int $id): ?Model
    {
        return $this->paymentMethodRepo->getById($id);
    }

    public function create(array $data): Model
    {
        return $this->paymentMethodRepo->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->paymentMethodRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->paymentMethodRepo->delete($id);
    }
}
