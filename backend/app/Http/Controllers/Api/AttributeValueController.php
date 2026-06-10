<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeValue\StoreAttributeValueRequest;
use App\Http\Requests\AttributeValue\UpdateAttributeValueRequest;
use App\Services\AttributeValueService;
use Illuminate\Http\JsonResponse;

class AttributeValueController extends Controller
{
    public function __construct(
        protected AttributeValueService $attrValueService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->attrValueService->getAll()]);
    }

    public function show(int $attributeValue): JsonResponse
    {
        $result = $this->attrValueService->getById($attributeValue);

        if (!$result) {
            return response()->json(['message' => 'Giá trị thuộc tính không tồn tại.'], 404);
        }

        return response()->json(['data' => $result]);
    }

    public function store(StoreAttributeValueRequest $request): JsonResponse
    {
        $value = $this->attrValueService->create($request->validated());

        return response()->json([
            'message' => 'Tạo giá trị thuộc tính thành công.',
            'data'    => $value,
        ], 201);
    }

    public function update(UpdateAttributeValueRequest $request, int $attributeValue): JsonResponse
    {
        $result = $this->attrValueService->update($attributeValue, $request->validated());

        if (!$result) {
            return response()->json(['message' => 'Giá trị thuộc tính không tồn tại.'], 404);
        }

        return response()->json([
            'message' => 'Cập nhật giá trị thuộc tính thành công.',
            'data'    => $result,
        ]);
    }

    public function destroy(int $attributeValue): JsonResponse
    {
        if (!$this->attrValueService->delete($attributeValue)) {
            return response()->json(['message' => 'Giá trị thuộc tính không tồn tại.'], 404);
        }

        return response()->json(['message' => 'Xóa giá trị thuộc tính thành công.']);
    }
}
