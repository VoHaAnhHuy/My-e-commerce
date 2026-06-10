<?php

namespace App\Repositories\Interfaces;

interface AuditLogRepositoryInterface extends BaseRepositoryInterface
{
    public function getByEntity(string $entityType, int $entityId);
}
