<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\StoreAttributeRequest;
use App\Http\Requests\Attribute\UpdateAttributeRequest;
use App\Services\AttributeService;
use Illuminate\Http\JsonResponse;

class AttributeController extends Controller
{
    public function __construct(
        protected AttributeService $attributeService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->attributeService->getAll()]);
    }

    public function show(int $attribute): JsonResponse
    {
        $result = $this->attributeService->getById($attribute);

        if (!$result) {
            return response()->json(['message' => 'Thuộc tính không tồn tại.'], 404);
        }

        return response()->json(['data' => $result->load('values')]);
    }

    public function store(StoreAttributeRequest $request): JsonResponse
    {
        $attribute = $this->attributeService->create($request->validated());

        return response()->json([
            'message' => 'Tạo thuộc tính thành công.',
            'data'    => $attribute,
        ], 201);
    }

    public function update(UpdateAttributeRequest $request, int $attribute): JsonResponse
    {
        $result = $this->attributeService->update($attribute, $request->validated());

        if (!$result) {
            return response()->json(['message' => 'Thuộc tính không tồn tại.'], 404);
        }

        return response()->json([
            'message' => 'Cập nhật thuộc tính thành công.',
            'data'    => $result,
        ]);
    }

    public function destroy(int $attribute): JsonResponse
    {
        if (!$this->attributeService->delete($attribute)) {
            return response()->json(['message' => 'Thuộc tính không tồn tại.'], 404);
        }

        return response()->json(['message' => 'Xóa thuộc tính thành công.']);
    }
}
