<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Đăng ký tài khoản mới
     *
     * POST /api/register
     *
     * @param  RegisterRequest  $request  — Validate: name, email (unique), password (min 8, confirmed)
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // RegisterRequest đã tự động validate name, email, password
        // Nếu validation fail → tự động trả về 422 với thông báo lỗi tiếng Việt
        $validated = $request->validated();

        $user = User::create($validated);

        // Gửi email xác nhận
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để xác nhận tài khoản.',
            'user' => $user,
        ], 201);
    }

    /**
     * Đăng nhập
     *
     * POST /api/login
     *
     * @param  LoginRequest  $request  — Validate: email, password
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // LoginRequest đã tự động validate email & password
        // Nếu validation fail → tự động trả về 422 với thông báo lỗi tiếng Việt
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        // Kiểm tra user tồn tại + password đúng
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng.',
            ], 401);
        }

        // Kiểm tra email đã xác nhận chưa
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email chưa được xác nhận. Vui lòng kiểm tra email.',
            ], 403);
        }

        // Tạo Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Đăng xuất
     *
     * POST /api/logout
     * Header: Authorization: Bearer {token}
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công.',
        ]);
    }
}
