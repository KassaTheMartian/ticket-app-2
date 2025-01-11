<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\Department;
use App\Mail\SendTicketEmail;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Mail\NewTicketAssignedEmail;

class CustomerTicketController extends Controller
{
    public function dashboard()
    {
        return view('customer.home', ['articles' => Article::all()]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getTicketsDataTable($request);
        }

        return $this->getTicketsView();
    }

    private function buildTicketsQuery(Request $request)
    {
        $query = auth()->guard('customer')->user()->tickets()
            ->select(
                'tickets.*', 
                'departments.name as department_name',
                'ticket_types.name as ticket_type_name',
                'customers.name as customer_name' // Thêm thông tin customer
            )
            ->leftJoin('departments', 'tickets.department_id', '=', 'departments.id')
            ->leftJoin('ticket_types', 'tickets.ticket_type_id', '=', 'ticket_types.id')
            ->leftJoin('customers', 'tickets.customer_id', '=', 'customers.id');
    
        return $query;
    }
    
    private function getTicketsDataTable(Request $request)
    {
        $query = $this->buildTicketsQuery($request);
    
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('title', fn($row) => $row->title)
            ->addColumn('customer', fn($row) => $row->customer_name ?? 'N/A')
            ->addColumn('department', fn($row) => $row->department_name ?? 'N/A')
            ->addColumn('ticket_type', fn($row) => $row->ticket_type_name ?? 'N/A')
            ->addColumn('priority', fn($row) => $this->getPriorityBadge($row->priority))
            ->addColumn('status', fn($row) => $this->getStatusBadge($row->status))
            ->addColumn('created_at', fn($row) => $row->created_at ? Carbon::parse($row->created_at)->format('d M Y') : '')
            ->addColumn('closed_at', fn($row) => $row->closed_at ? Carbon::parse($row->closed_at)->format('d M Y') : 'Not closed')
            ->addColumn('action', fn($row) => '<a href="' . route('customer.tickets.show', $row->id) . '" 
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-eye"></i>
                </a>')
            ->rawColumns(['action', 'priority', 'status'])
            ->make(true);
    }
    private function getPriorityBadge($priority)
    {
        $badges = [
            'low' => '<span class="badge bg-primary text-light">Low</span>',
            'medium' => '<span class="badge bg-warning text-dark">Medium</span>',
            'high' => '<span class="badge bg-danger text-light">High</span>'
        ];
        return $badges[$priority] ?? '';
    }

    private function getStatusBadge($status)
    {
        $badges = [
            'new' => '<span class="badge bg-primary text-light">New</span>',
            'inprogress' => '<span class="badge bg-warning text-dark">In Progress</span>',
            'resolved' => '<span class="badge bg-success text-light">Resolved</span>'
        ];
        return $badges[$status] ?? '';
    }
    private function getTicketsView()
    {
        $ticketTypes = TicketType::all();
        $departments = Department::all();
        $statuses = ['new', 'inprogress', 'resolved'];
        $priorities = ['low', 'medium', 'high'];

        return view('customer.ticket_index', compact('ticketTypes', 'departments', 'statuses', 'priorities'));
    }
    public function show($id)
    {
        $ticket = Ticket::with('ticketType', 'department', 'customer')->findOrFail($id);
        return view('customer.ticket_detail', [
            'ticket' => $ticket,
            'logs' => $ticket->logs(),
            'attachments' => $ticket->attachments
        ]);
    }

    public function create()
    {
        return view('customer.send_ticket', [
            'ticketTypes' => TicketType::all()->pluck('name', 'id'),
            'departments' => Department::all()->pluck('name', 'id')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTicket($request);
        $validated['customer_id'] = auth()->guard('customer')->id();
        $validated['status'] = 'new';
        $validated['priority'] = 'medium';

        $ticket = Ticket::create($validated);
        $ticket->logAction(null, $ticket->__toString());
        $ticket->saveAttachments($request->file('attachments'));
        $ticket->load('ticketType', 'department', 'customer');

        $this->sendTicketEmail($ticket);

        return redirect()->route('customer.tickets.index')->with('success', 'Ticket booked successfully.');
    }

    private function validateTicket(Request $request)
    {
        return $request->validate([
            'title' => 'required',
            'description' => 'required',
            'ticket_type_id' => 'required',
            'department_id' => 'required',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,doc,docx,pdf|max:10240',
            'g-recaptcha-response' => 'recaptcha',
        ]);
    }

    private function sendTicketEmail(Ticket $ticket)
    {
        $ticketData = [
            'title' => $ticket->title,
            'description' => $ticket->description,
            'ticket_type' => $ticket->ticketType->name,
            'department' => $ticket->department->name,
            'customer' => $ticket->customer->name,
            'priority' => $ticket->priority,
            'status' => $ticket->status,
            'created_at' =>$ticket->created_at ? Carbon::parse($ticket->created_at)->format('d M Y H:i:s') : ''
        ];
        Mail::to(auth()->guard('customer')->user()->email)->queue(new SendTicketEmail($ticketData));

        $departmentEmails = $ticket->department->users->pluck('email')->toArray();
        foreach ($departmentEmails as $email) {
            $ticketData['staff_name'] = $ticket->department->users->where('email', $email)->first()->name;
            $ticketData['ticket_url'] = route('admin.tickets.edit', $ticket->id);
            Mail::to($email)->queue(new NewTicketAssignedEmail($ticketData));
        }
    }
}
