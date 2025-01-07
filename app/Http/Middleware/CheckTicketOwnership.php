<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTicketOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ticketId = $request->route('id');
        $ticket = auth()->guard('customer')->user()->tickets()->find($ticketId);
        
        if(!$ticket) {
            abort(404, 'Ticket not found.');
        }

        return $next($request);
    }
}
