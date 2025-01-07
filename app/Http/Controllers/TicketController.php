<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\Customer;
use App\Models\Department;
use App\Models\TicketLog;
use App\Models\User;
use App\Mail\TicketUpdatedEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getTicketsDataTable($request);
        }

        return $this->getTicketsView();
    }

    private function getTicketsDataTable(Request $request)
    {
        $query = $this->buildTicketsQuery($request);

        return DataTables::of($query)
            ->addColumn('customer', fn($row) => $row->customer_name ?? 'N/A')
            ->addColumn('department', fn($row) => $row->department_name ?? 'N/A')
            ->addColumn('ticket_type', fn($row) => $row->ticket_type_name ?? 'N/A')
            ->addColumn('priority', fn($row) => $this->getPriorityBadge($row->priority))
            ->addColumn('status', fn($row) => $this->getStatusBadge($row->status))
            ->addColumn('created_at', fn($row) => $row->created_at ? Carbon::parse($row->created_at)->format('d M Y') : '')
            ->addColumn('closed_at', fn($row) => $row->closed_at ? Carbon::parse($row->closed_at)->format('d M Y') : 'Not closed')
            ->addColumn('action', fn($row) => view('components.action-buttons', [
                'row' => $row,
                'editRoute' => 'admin.tickets.edit',
                'deleteRoute' => 'admin.tickets.destroy'
            ])->render())
            ->rawColumns(['action', 'priority', 'status'])
            ->make(true);
    }

    private function buildTicketsQuery(Request $request)
    {
        $query = Ticket::query()
            ->select('tickets.*', 
                'customers.name as customer_name',
                'departments.name as department_name',
                'ticket_types.name as ticket_type_name'
            )
            ->leftJoin('customers', 'tickets.customer_id', '=', 'customers.id')
            ->leftJoin('departments', 'tickets.department_id', '=', 'departments.id')
            ->leftJoin('ticket_types', 'tickets.ticket_type_id', '=', 'ticket_types.id');

        $this->applyFilters($query, $request);

        return $query;
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('ticket_type_id')) {
            $query->where('tickets.ticket_type_id', $request->ticket_type_id);
        }

        if ($request->filled('department_id')) {
            $query->where('tickets.department_id', $request->department_id);
        } else {
            if (auth()->user()->admin != 1) {
                $query->where('tickets.department_id', auth()->user()->department_id);
            }
        }

        if ($request->filled('customer_id')) {
            $query->where('tickets.customer_id', $request->customer_id);
        }

        if ($request->filled('priority')) {
            $query->where('tickets.priority', $request->priority);
        }

        if ($request->filled('status')) {
            $query->where('tickets.status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tickets.created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tickets.created_at', '<=', $request->end_date);
        }
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
        $customers = Customer::all();
        $statuses = ['new', 'inprogress', 'resolved'];
        $priorities = ['low', 'medium', 'high'];

        return view('admin.tickets.index', compact('ticketTypes', 'departments', 'customers', 'statuses', 'priorities'));
    }

    public function create()
    {
        $ticketTypes = TicketType::all()->pluck('name', 'id'); 
        $customers = Customer::all()->pluck('name', 'id');
        $departments = Department::all()->pluck('name', 'id');
        return view('admin.tickets.create', compact('ticketTypes', 'customers', 'departments'));
    }

    public function store(Request $request)
    {
        $this->validateTicket($request);

        $data = $request->all();
        $data['status'] = 'new'; // Set status to 'new' automatically

        Ticket::create($data);

        return redirect()->route('admin.tickets.index')
                         ->with('success', 'Ticket created successfully.');
    }

    private function validateTicket(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'ticket_type_id' => 'required',
            'customer_id' => 'required',
            'department_id' => 'required',
            'priority' => 'required',
        ]);
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);
        $logs = TicketLog::where('ticket_id', $id)->get();
        $attachments = $ticket->attachments;
        return view('admin.tickets.show', compact('ticket', 'logs', 'attachments'));
    }

    public function edit($id)
    {
        $ticketTypes = TicketType::all()->pluck('name', 'id'); 
        $departments = Department::all()->pluck('name', 'id');
        $ticket = Ticket::find($id);
        $logs = $ticket->logs();
        $messages = $ticket->messages;
        return view('admin.tickets.edit', compact('ticket', 'ticketTypes', 'departments', 'messages'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'ticket_type_id' => 'required',
            'department_id' => 'required',
            'priority' => 'required',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,doc,docx|max:10240',
        ]);

        $ticket = Ticket::find($id);

        $emailSubject = $this->getEmailSubject($ticket, $request->status);
        $this->updateTicketStatus($ticket, $request->status);

        $ticket->logAction(auth()->user()->id, $ticket->__toString());
        $ticket->saveAttachments($request->file('attachments'));
        $ticket->update($request->all());

        $this->sendTicketUpdateEmail($ticket, $request, $emailSubject);

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }

    private function getEmailSubject($ticket, $status)
    {
        if ($ticket->status != $status) {
            if ($status == 'inprogress') {
                return 'Ticket is now in progress';
            } elseif ($status == 'resolved') {
                return 'Ticket has been resolved';
            }
        }
        return 'Ticket updated';
    }

    private function updateTicketStatus($ticket, $status)
    {
        if ($ticket->status != $status && $status == 'resolved') {
            $ticket->closed_at = Carbon::now();
        }
    }

    private function sendTicketUpdateEmail($ticket, $request, $emailSubject)
    {
        $emailData = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'customer' => $ticket->customer->name,
            'priority' => $request->priority,
            'ticket_type' => $ticket->ticketType->name,
            'department' => $ticket->department->name,
            'closed_at' => $ticket->closed_at ? Carbon::parse($ticket->closed_at)->format('d M Y H:i:s') : 'Not closed',
            'updated_at' =>$ticket->updated_at ? Carbon::parse($ticket->updated_at)->format('d M Y H:i:s') : ''
        ];

        Mail::to($ticket->customer->email)->queue(new TicketUpdatedEmail($emailData, $emailSubject));
    }

    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        $ticket->delete();

        return redirect()->route('admin.tickets.index')
                         ->with('success', 'Ticket deleted successfully.');
    }
}
