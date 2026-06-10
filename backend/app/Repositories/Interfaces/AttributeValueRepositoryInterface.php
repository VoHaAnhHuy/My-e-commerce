<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface AttributeValueRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy tất cả values của một attribute.
     */
    public function getByAttribute(int $attributeId): Collection;
}
