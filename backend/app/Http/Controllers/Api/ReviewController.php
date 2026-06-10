<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviewService) {}

    public function index(int $product): JsonResponse
    {
        return response()->json(['data' => $this->reviewService->getByProduct($product)]);
    }

    public function myReviews(Request $request): JsonResponse
    {
        return response()->json(['data' => $this->reviewService->getByUser($request->user()->id)]);
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'pending';

        return response()->json([
            'message' => 'Đánh giá đã được gửi.',
            'data'    => $this->reviewService->create($data),
        ], 201);
    }

    public function show(int $review): JsonResponse
    {
        $result = $this->reviewService->getById($review);
        if (!$result) return response()->json(['message' => 'Đánh giá không tồn tại.'], 404);
        return response()->json(['data' => $result]);
    }

    public function update(UpdateReviewRequest $request, int $review): JsonResponse
    {
        $result = $this->reviewService->update($review, $request->validated());
        if (!$result) return response()->json(['message' => 'Đánh giá không tồn tại.'], 404);
        return response()->json(['message' => 'Cập nhật đánh giá thành công.', 'data' => $result]);
    }

    public function destroy(int $review): JsonResponse
    {
        $this->reviewService->delete($review);
        return response()->json(['message' => 'Xóa đánh giá thành công.']);
    }
}
