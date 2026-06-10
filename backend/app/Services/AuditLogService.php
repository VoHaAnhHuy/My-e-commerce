<?php

namespace App\Services;

use App\Repositories\Interfaces\AuditLogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    public function __construct(
        protected AuditLogRepositoryInterface $auditLogRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->auditLogRepo->getAll();
    }

    public function getByEntity(string $entityType, int $entityId): Collection
    {
        return $this->auditLogRepo->getByEntity($entityType, $entityId);
    }

    public function getById(int $id): ?Model
    {
        return $this->auditLogRepo->getById($id)?->load('actor');
    }

    public function log(?int $actorUserId, string $action, string $entityType, int $entityId, ?array $before = null, ?array $after = null): Model
    {
        return $this->auditLogRepo->create([
            'actor_user_id' => $actorUserId,
            'action'        => $action,
            'entity_type'   => $entityType,
            'entity_id'     => $entityId,
            'before_data'   => $before,
            'after_data'    => $after,
        ]);
    }
}
