<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemLog;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Check if user is logged in
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập.');
        }

        $role = session('user_role');

        // 2. Chấp nhận Role 1 (Admin) và Role 3 (Staff)
        if (!in_array($role, [1, 3])) {
            // Log security event
            SystemLog::ghi('security', 'unauthorized_admin_access', 'Cố gắng truy cập trái phép vào trang quản trị', 'warning');
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        // 3. (Optional) Check specific permissions based on Role 3 later
        // ...

        return $next($request);
    }
}
