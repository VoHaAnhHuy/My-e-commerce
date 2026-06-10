<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Gửi link reset password qua email
     *
     * POST /api/forgot-password
     *
     * @param  ForgotPasswordRequest  $request  — Validate: email
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        // ForgotPasswordRequest đã tự động validate email
        // Nếu validation fail → tự động trả về 422 với thông báo lỗi tiếng Việt
        $validated = $request->validated();

        $status = Password::sendResetLink($validated);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Link reset mật khẩu đã được gửi đến email của bạn. Link sẽ hết hạn sau 5 phút.',
            ]);
        }

        return response()->json([
            'message' => 'Không thể gửi link reset. Vui lòng kiểm tra lại email.',
        ], 400);
    }

    /**
     * Reset password bằng token
     *
     * POST /api/reset-password
     *
     * @param  ResetPasswordRequest  $request  — Validate: token, email, password (min 8, confirmed)
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        // ResetPasswordRequest đã tự động validate token, email, password
        // Nếu validation fail → tự động trả về 422 với thông báo lỗi tiếng Việt
        $validated = $request->validated();

        $status = Password::reset(
            $validated,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Mật khẩu đã được đổi thành công.',
            ]);
        }

        return response()->json([
            'message' => 'Không thể reset mật khẩu. Token không hợp lệ hoặc đã hết hạn.',
        ], 400);
    }
}

