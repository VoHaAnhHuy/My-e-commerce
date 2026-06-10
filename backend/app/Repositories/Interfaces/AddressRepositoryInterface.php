<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface AddressRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy tất cả địa chỉ của user.
     */
    public function getByUser(int $userId): Collection;

    /**
     * Set địa chỉ mặc định (unset các default khác).
     */
    public function setDefault(int $id, int $userId): bool;
}
