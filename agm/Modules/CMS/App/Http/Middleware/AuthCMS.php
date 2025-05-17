<?php

namespace Modules\CMS\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCMS
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem user đã đăng nhập chưa
        if (!Auth::guard('web')->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            return redirect()->route('cms.login');
        }
        return $next($request);
    }
}
