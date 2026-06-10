<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{

    public function verify(Request $request, $id, $hash): JsonResponse
    {
        $user = User::findOrFail($id);

        // Kiểm tra hash có khớp với email của user không
        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->json([
                'message' => 'Link xác nhận không hợp lệ.',
            ], 403);
        }

        // Nếu email đã verify rồi
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email đã được xác nhận trước đó.',
            ]);
        }

        // Đánh dấu email đã verify
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'message' => 'Email đã được xác nhận thành công.',
        ]);
    }


    public function resend(ResendVerificationRequest $request): JsonResponse
    {
        // ResendVerificationRequest đã tự động validate email
        // Nếu validation fail → tự động trả về 422 với thông báo lỗi tiếng Việt
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email không tồn tại trong hệ thống.',
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email đã được xác nhận.',
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Email xác nhận đã được gửi lại.',
        ]);
    }
}

