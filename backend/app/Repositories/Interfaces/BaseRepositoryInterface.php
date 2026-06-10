<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * Lấy tất cả records.
     */
    public function getAll(): Collection;

    /**
     * Lấy record theo ID.
     */
    public function getById(int $id): ?Model;

    /**
     * Tạo record mới.
     */
    public function create(array $data): Model;

    /**
     * Cập nhật record.
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Xóa record.
     */
    public function delete(int $id): bool;
}
