<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    /**
     * Lấy cart_token từ header X-Cart-Token.
     */
    protected function getCartToken(Request $request): ?string
    {
        return $request->header('X-Cart-Token');
    }

    /**
     * GET /api/cart
     */
    public function index(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCart(
            $request->user()?->id,
            $this->getCartToken($request)
        );

        return response()->json(['data' => new CartResource($cart)]);
    }

    /**
     * POST /api/cart/items
     */
    public function addItem(AddCartItemRequest $request): JsonResponse
    {
        $cart = $this->cartService->addItem(
            $request->user()?->id,
            $this->getCartToken($request),
            $request->validated()
        );

        return response()->json([
            'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
            'data'    => new CartResource($cart),
        ]);
    }

    /**
     * PUT /api/cart/items/{cartItem}
     */
    public function updateItem(UpdateCartItemRequest $request, int $cartItem): JsonResponse
    {
        $item = $this->cartService->updateItem(
            $cartItem,
            $request->validated()['quantity'],
            $request->user()?->id,
            $this->getCartToken($request)
        );

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
    public function removeItem(Request $request, int $cartItem): JsonResponse
    {
        if (!$this->cartService->removeItem($cartItem, $request->user()?->id, $this->getCartToken($request))) {
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
            $this->getCartToken($request)
        );

        return response()->json(['message' => 'Đã xóa toàn bộ giỏ hàng.']);
    }

    /**
     * POST /api/cart/merge
     * Merge guest cart vào user cart sau khi đăng nhập.
     * Requires: auth:sanctum + header X-Cart-Token
     */
    public function merge(Request $request): JsonResponse
    {
        $cartToken = $this->getCartToken($request);

        if (!$cartToken) {
            return response()->json(['message' => 'Thiếu header X-Cart-Token.'], 422);
        }

        $cart = $this->cartService->mergeCart(
            $cartToken,
            $request->user()->id
        );

        return response()->json([
            'message' => 'Đã merge giỏ hàng thành công.',
            'data'    => new CartResource($cart),
        ]);
    }
}
