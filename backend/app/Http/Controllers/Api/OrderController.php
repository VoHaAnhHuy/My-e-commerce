<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {}

    /**
     * GET /api/orders — Đơn hàng của user hiện tại.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getByUser($request->user()->id);

        return response()->json(['data' => $orders]);
    }

    /**
     * GET /api/admin/orders — Tất cả đơn hàng (admin).
     */
    public function adminIndex(): JsonResponse
    {
        return response()->json(['data' => $this->orderService->getAll()]);
    }

    /**
     * GET /api/orders/{order}
     */
    public function show(int $order): JsonResponse
    {
        $result = $this->orderService->getById($order);

        if (!$result) {
            return response()->json(['message' => 'Đơn hàng không tồn tại.'], 404);
        }

        return response()->json(['data' => $result]);
    }

    /**
     * POST /api/orders
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->create(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Đặt hàng thành công.',
            'data'    => $order,
        ], 201);
    }

    /**
     * PATCH /api/orders/{order}/cancel
     */
    public function cancel(Request $request, int $order): JsonResponse
    {
        $result = $this->orderService->cancel($order, $request->user()->id);

        $status = $result['success'] ? 200 : 422;

        return response()->json(['message' => $result['message']], $status);
    }

    /**
     * PATCH /api/admin/orders/{order}/status
     */
    public function updateStatus(UpdateOrderStatusRequest $request, int $order): JsonResponse
    {
        $this->orderService->updateStatus($order, $request->validated()['status']);

        return response()->json(['message' => 'Cập nhật trạng thái đơn hàng thành công.']);
    }
}
