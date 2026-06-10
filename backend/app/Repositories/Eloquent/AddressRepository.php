<?php

namespace App\Repositories\Eloquent;

use App\Models\Address;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AddressRepository extends BaseRepository implements AddressRepositoryInterface
{
    public function __construct(Address $model)
    {
        parent::__construct($model);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->orderByDesc('is_default')->get();
    }

    public function setDefault(int $id, int $userId): bool
    {
        // Bỏ default tất cả địa chỉ của user
        $this->model->where('user_id', $userId)->update(['is_default' => false]);

        // Set default cho địa chỉ được chọn
        return (bool) $this->model->where('id', $id)->where('user_id', $userId)->update(['is_default' => true]);
    }
}
