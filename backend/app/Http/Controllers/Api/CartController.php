<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    /**
     * GET /api/cart
     */
    public function index(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCart(
            $request->user()?->id,
            $request->session()->getId()
        );

        return response()->json(['data' => $cart]);
    }

    /**
     * POST /api/cart/items
     */
    public function addItem(AddCartItemRequest $request): JsonResponse
    {
        $cart = $this->cartService->addItem(
            $request->user()?->id,
            $request->session()->getId(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
            'data'    => $cart,
        ]);
    }

    /**
     * PUT /api/cart/items/{cartItem}
     */
    public function updateItem(UpdateCartItemRequest $request, int $cartItem): JsonResponse
    {
        $item = $this->cartService->updateItem($cartItem, $request->validated()['quantity']);

        if (!$item) {
            return response()->json(['message' => 'Sản phẩm không tồn tại trong giỏ hàng.'], 404);
        }

        return response()->json([
            'message' => 'Cập nhật số lượng thành công.',
            'data'    => $item,
        ]);
    }

    /**
     * DELETE /api/cart/items/{cartItem}
     */
    public function removeItem(int $cartItem): JsonResponse
    {
        if (!$this->cartService->removeItem($cartItem)) {
            return response()->json(['message' => 'Sản phẩm không tồn tại trong giỏ hàng.'], 404);
        }

        return response()->json(['message' => 'Đã xóa sản phẩm khỏi giỏ hàng.']);
    }

    /**
     * DELETE /api/cart
     */
    public function clear(Request $request): JsonResponse
    {
        $this->cartService->clearCart(
            $request->user()?->id,
            $request->session()->getId()
        );

        return response()->json(['message' => 'Đã xóa toàn bộ giỏ hàng.']);
    }
}
