<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
     * Block unauthenticated users from accessing protected routes.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session('user_id')) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        return $next($request);
    }
}
