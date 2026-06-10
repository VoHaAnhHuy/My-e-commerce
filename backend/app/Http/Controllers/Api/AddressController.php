<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(
        protected AddressService $addressService,
    ) {}

    /**
     * GET /api/addresses
     */
    public function index(Request $request): JsonResponse
    {
        $addresses = $this->addressService->getByUser($request->user()->id);

        return response()->json(['data' => $addresses]);
    }

    /**
     * POST /api/addresses
     */
    public function store(StoreAddressRequest $request): JsonResponse
    {
        $address = $this->addressService->create(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Tạo địa chỉ thành công.',
            'data'    => $address,
        ], 201);
    }

    /**
     * PUT /api/addresses/{address}
     */
    public function update(UpdateAddressRequest $request, int $address): JsonResponse
    {
        $result = $this->addressService->update($address, $request->validated());

        if (!$result) {
            return response()->json(['message' => 'Địa chỉ không tồn tại.'], 404);
        }

        return response()->json([
            'message' => 'Cập nhật địa chỉ thành công.',
            'data'    => $result,
        ]);
    }

    /**
     * DELETE /api/addresses/{address}
     */
    public function destroy(int $address): JsonResponse
    {
        if (!$this->addressService->delete($address)) {
            return response()->json(['message' => 'Địa chỉ không tồn tại.'], 404);
        }

        return response()->json(['message' => 'Xóa địa chỉ thành công.']);
    }

    /**
     * PATCH /api/addresses/{address}/default
     */
    public function setDefault(Request $request, int $address): JsonResponse
    {
        $this->addressService->setDefault($address, $request->user()->id);

        return response()->json(['message' => 'Đã đặt làm địa chỉ mặc định.']);
    }
}
