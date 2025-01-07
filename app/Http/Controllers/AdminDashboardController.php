<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $data = [
            'totalUsers' => $this->getTotalUsers($startDate, $endDate),
            'totalCustomers' => $this->getTotalCustomers($startDate, $endDate),
            'totalTickets' => $this->getTotalTickets($startDate, $endDate),
            'totalNewTickets' => $this->getTotalTicketsByStatus($startDate, $endDate, 'new'),
            'totalInProgressTickets' => $this->getTotalTicketsByStatus($startDate, $endDate, 'inprogress'),
            'totalResolvedTickets' => $this->getTotalTicketsByStatus($startDate, $endDate, 'resolved'),
            'dailyTickets' => $this->getDailyTickets($startDate, $endDate),
            'ticketsByDepartment' => $this->getTicketsByDepartment($startDate, $endDate),
            'ticketsByType' => $this->getTicketsByType($startDate, $endDate),
            'topCustomers' => $this->getTopCustomers($startDate, $endDate),
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('admin.dashboard', $data);
    }

    private function getTotalUsers($startDate, $endDate)
    {
        return User::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    private function getTotalCustomers($startDate, $endDate)
    {
        return Customer::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    private function getTotalTickets($startDate, $endDate)
    {
        return Ticket::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    private function getTotalTicketsByStatus($startDate, $endDate, $status)
    {
        return Ticket::where('status', $status)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    private function getDailyTickets($startDate, $endDate)
    {
        return Ticket::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_tickets')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getTicketsByDepartment($startDate, $endDate)
    {
        return Ticket::select(
            'department_id',
            DB::raw('COUNT(*) as total_tickets'),
            DB::raw('departments.name as department_name')
        )
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->whereBetween('tickets.created_at', [$startDate, $endDate])
            ->groupBy('department_id', 'departments.name')
            ->get();
    }

    private function getTicketsByType($startDate, $endDate)
    {
        return Ticket::select(
            'ticket_type_id',
            DB::raw('COUNT(*) as total_tickets'),
            DB::raw('ticket_types.name as type_name')
        )
            ->join('ticket_types', 'tickets.ticket_type_id', '=', 'ticket_types.id')
            ->whereBetween('tickets.created_at', [$startDate, $endDate])
            ->groupBy('ticket_type_id', 'ticket_types.name')
            ->get();
    }

    private function getTopCustomers($startDate, $endDate)
    {
        return Customer::select(
            'customers.id',
            'customers.name',
            DB::raw('COUNT(tickets.id) as total_tickets')
        )
            ->join('tickets', 'customers.id', '=', 'tickets.customer_id')
            ->whereBetween('tickets.created_at', [$startDate, $endDate])
            ->groupBy('customers.id', 'customers.name')
            ->orderBy('total_tickets', 'desc')
            ->limit(10)
            ->get();
    }
}
