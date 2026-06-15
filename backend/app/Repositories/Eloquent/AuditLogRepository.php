<?php

namespace App\Repositories\Eloquent;

use App\Models\AuditLog;
use App\Repositories\Interfaces\AuditLogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AuditLogRepository extends BaseRepository implements AuditLogRepositoryInterface
{
    public function __construct(AuditLog $model)
    {
        parent::__construct($model);
    }

    public function getByEntity(string $entityType, int $entityId): Collection
    {
        return $this->model
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->with('actor')
            ->orderByDesc('created_at')
            ->get();
    }
}
