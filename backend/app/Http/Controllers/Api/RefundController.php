<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Refund\StoreRefundRequest;
use App\Http\Requests\Refund\UpdateRefundRequest;
use App\Services\RefundService;
use Illuminate\Http\JsonResponse;

class RefundController extends Controller
{
    public function __construct(protected RefundService $refundService) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->refundService->getAll()]);
    }

    public function byOrder(int $order): JsonResponse
    {
        return response()->json(['data' => $this->refundService->getByOrder($order)]);
    }

    public function store(StoreRefundRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Tạo yêu cầu hoàn tiền thành công.',
            'data'    => $this->refundService->create($request->validated()),
        ], 201);
    }

    public function show(int $refund): JsonResponse
    {
        $result = $this->refundService->getById($refund);
        if (!$result) return response()->json(['message' => 'Yêu cầu hoàn tiền không tồn tại.'], 404);
        return response()->json(['data' => $result]);
    }

    public function update(UpdateRefundRequest $request, int $refund): JsonResponse
    {
        $result = $this->refundService->update($refund, $request->validated());
        if (!$result) return response()->json(['message' => 'Yêu cầu hoàn tiền không tồn tại.'], 404);
        return response()->json(['message' => 'Cập nhật hoàn tiền thành công.', 'data' => $result]);
    }

    public function destroy(int $refund): JsonResponse
    {
        $this->refundService->delete($refund);
        return response()->json(['message' => 'Xóa yêu cầu hoàn tiền thành công.']);
    }
}
