<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class CheckTicketDepartment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

        // Nếu user là admin, cho phép tiếp tục
        if ($user->admin == 1) {
            return $next($request);
        }
        
        $ticket = $request->route('ticket'); // Lấy ticket từ route

        if (is_string($ticket)) {
            $ticket = Ticket::findOrFail($ticket);
        }

        // Kiểm tra phòng ban của user và ticket
        if ($ticket->department_id !== $user->department_id) {
            // Nếu không khớp, trả về lỗi hoặc chuyển hướng
            abort(404, 'Ticket not found.');
        }

        return $next($request); // Cho phép nếu khớp
    }
}
