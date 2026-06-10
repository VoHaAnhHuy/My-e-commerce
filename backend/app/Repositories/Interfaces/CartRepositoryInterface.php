<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy hoặc tạo cart cho user đã đăng nhập.
     */
    public function getOrCreateByUser(int $userId): Model;

    /**
     * Lấy hoặc tạo cart cho guest (session).
     */
    public function getOrCreateBySession(string $sessionId): Model;
}
