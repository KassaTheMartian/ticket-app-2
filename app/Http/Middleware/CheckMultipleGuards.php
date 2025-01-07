<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckMultipleGuards
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem user đã đăng nhập với bất kỳ guard nào chưa
        if (Auth::guard('web')->check() || Auth::guard('customer')->check()) {
            return $next($request);
        }

        // Nếu chưa đăng nhập, redirect về trang login
        return redirect()->route('error');
    }
}
