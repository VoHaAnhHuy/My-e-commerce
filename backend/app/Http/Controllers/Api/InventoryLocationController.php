<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryLocation\StoreInventoryLocationRequest;
use App\Http\Requests\InventoryLocation\UpdateInventoryLocationRequest;
use App\Services\InventoryLocationService;
use Illuminate\Http\JsonResponse;

class InventoryLocationController extends Controller
{
    public function __construct(protected InventoryLocationService $locationService) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->locationService->getAll()]);
    }

    public function store(StoreInventoryLocationRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Tạo kho thành công.',
            'data'    => $this->locationService->create($request->validated()),
        ], 201);
    }

    public function show(int $inventoryLocation): JsonResponse
    {
        $result = $this->locationService->getById($inventoryLocation);
        if (!$result) return response()->json(['message' => 'Kho không tồn tại.'], 404);
        return response()->json(['data' => $result]);
    }

    public function update(UpdateInventoryLocationRequest $request, int $inventoryLocation): JsonResponse
    {
        $result = $this->locationService->update($inventoryLocation, $request->validated());
        if (!$result) return response()->json(['message' => 'Kho không tồn tại.'], 404);
        return response()->json(['message' => 'Cập nhật kho thành công.', 'data' => $result]);
    }

    public function destroy(int $inventoryLocation): JsonResponse
    {
        $this->locationService->delete($inventoryLocation);
        return response()->json(['message' => 'Xóa kho thành công.']);
    }
}
