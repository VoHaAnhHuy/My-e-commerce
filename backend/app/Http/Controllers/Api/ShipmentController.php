<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipment\StoreShipmentRequest;
use App\Http\Requests\Shipment\UpdateShipmentRequest;
use App\Services\ShipmentService;
use Illuminate\Http\JsonResponse;

class ShipmentController extends Controller
{
    public function __construct(protected ShipmentService $shipmentService) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->shipmentService->getAll()]);
    }

    public function byOrder(int $order): JsonResponse
    {
        return response()->json(['data' => $this->shipmentService->getByOrder($order)]);
    }

    public function store(StoreShipmentRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Tạo vận đơn thành công.',
            'data'    => $this->shipmentService->create($request->validated()),
        ], 201);
    }

    public function show(int $shipment): JsonResponse
    {
        $result = $this->shipmentService->getById($shipment);
        if (!$result) return response()->json(['message' => 'Vận đơn không tồn tại.'], 404);
        return response()->json(['data' => $result]);
    }

    public function update(UpdateShipmentRequest $request, int $shipment): JsonResponse
    {
        $result = $this->shipmentService->update($shipment, $request->validated());
        if (!$result) return response()->json(['message' => 'Vận đơn không tồn tại.'], 404);
        return response()->json(['message' => 'Cập nhật vận đơn thành công.', 'data' => $result]);
    }

    public function destroy(int $shipment): JsonResponse
    {
        $this->shipmentService->delete($shipment);
        return response()->json(['message' => 'Xóa vận đơn thành công.']);
    }
}
