<?php

namespace App\Services;

use App\Repositories\Interfaces\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AddressService
{
    public function __construct(
        protected AddressRepositoryInterface $addressRepo,
    ) {}

    public function getByUser(int $userId): Collection
    {
        return $this->addressRepo->getByUser($userId);
    }

    public function create(int $userId, array $data): Model
    {
        $data['user_id'] = $userId;

        $address = $this->addressRepo->create($data);

        // Nếu set default hoặc là địa chỉ đầu tiên → set default
        if (!empty($data['is_default']) || $this->addressRepo->getByUser($userId)->count() === 1) {
            $this->addressRepo->setDefault($address->id, $userId);
            $address->refresh();
        }

        return $address;
    }

    public function update(int $id, array $data): ?Model
    {
        return $this->addressRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->addressRepo->delete($id);
    }

    public function setDefault(int $id, int $userId): bool
    {
        return $this->addressRepo->setDefault($id, $userId);
    }
}
