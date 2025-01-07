<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Lưu URL hiện tại vào session
            session()->put('url.intended', url()->current());

            if ($request->is('customer/*')) { // Nếu URL thuộc về khách hàng
                return route('customer.auth.login'); // Redirect đến trang đăng nhập của khách hàng
            }
            return route('admin.auth.login'); // Redirect mặc định
        }
    }

    /**
     * Xử lý middleware kiểm tra xác thực và phân quyền
     *
     * @param Request $request Đối tượng request hiện tại
     * @param Closure $next Middleware tiếp theo trong chuỗi
     * @param array $guards Các guard được chỉ định
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Kiểm tra xác thực cho customer
        if (in_array('customer', $guards)) {
            session()->put('url.intended', url()->current());

            // Nếu không đăng nhập với guard customer, chuyển hướng đến trang đăng nhập
            if (!Auth::guard('customer')->check()) {
                return redirect()->route('customer.auth.login');
            }
            return $next($request);
        }

        // Kiểm tra xác thực cho admin/user
        if (!Auth::check()) {
            session()->put('url.intended', url()->current());

            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập admin
            return redirect()->route('admin.auth.login');
        }
        
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Cho phép user có trường admin là 1 vào tất cả các trang
        if ($user->admin == 1) {
            return $next($request);
        }

        // Lấy tên route hiện tại
        $route = $request->route()->getName();

        // Kiểm tra quyền truy cập của người dùng
        if ($user->cant($route)) {
            // Nếu muốn xử lý lỗi 403 (Forbidden), có thể bỏ comment dòng dưới
            return redirect()->route('error', ['code' => 403]);
        }

        // Cho phép request tiếp tục
        return $next($request);
    }
}
