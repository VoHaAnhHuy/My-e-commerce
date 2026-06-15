<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware thử authenticate bằng Sanctum nhưng KHÔNG throw 401 nếu thất bại.
 *
 * Dùng cho các endpoint cart để:
 * - Nếu có Bearer token hợp lệ → $request->user() trả về User
 * - Nếu không có token hoặc token sai → $request->user() trả về null (guest)
 */
class OptionalAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Thử authenticate với Sanctum guard, bỏ qua nếu thất bại
        try {
            Auth::guard('sanctum')->check();
            if (Auth::guard('sanctum')->check()) {
                Auth::shouldUse('sanctum');
            }
        } catch (\Exception $e) {
            // Bỏ qua — request tiếp tục với user() = null
        }

        return $next($request);
    }
}
