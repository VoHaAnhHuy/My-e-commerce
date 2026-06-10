<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;

class AuditLogController extends Controller
{
    public function __construct(protected AuditLogService $auditLogService) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->auditLogService->getAll()]);
    }

    public function show(int $auditLog): JsonResponse
    {
        $result = $this->auditLogService->getById($auditLog);
        if (!$result) return response()->json(['message' => 'Audit log không tồn tại.'], 404);
        return response()->json(['data' => $result]);
    }

    public function byEntity(string $entityType, int $entityId): JsonResponse
    {
        return response()->json([
            'data' => $this->auditLogService->getByEntity($entityType, $entityId),
        ]);
    }
}
