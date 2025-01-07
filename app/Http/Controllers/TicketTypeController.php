<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketType;
use Yajra\DataTables\Facades\DataTables;

class TicketTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TicketType::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return view('components.action-buttons', [
                        'row' => $row,
                        'editRoute' => 'admin.ticket_types.edit',
                        'deleteRoute' => 'admin.ticket_types.destroy'
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $ticketTypes = TicketType::all();
        return view('admin.ticket_types.index', compact('ticketTypes'));
    }

    public function create()
    {
        return view('admin.ticket_types.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:ticket_types,name',
            'description' => 'nullable|string',
        ]);

        $ticketType = TicketType::create($validatedData);
        return redirect()->route('admin.ticket_types.index')->with('success', 'Ticket Type created successfully.');
    }

    public function show($id)
    {
        $ticketType = TicketType::findOrFail($id);
        return view('admin.ticket_types.show', compact('ticketType'));
    }

    public function edit($id)
    {
        $ticketType = TicketType::findOrFail($id);
        return view('admin.ticket_types.edit', compact('ticketType'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:ticket_types,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $ticketType = TicketType::findOrFail($id);
        $ticketType->update($validatedData);
        return redirect()->back()->with('success', 'Ticket Type updated successfully.');
    }

    public function destroy($id)
    {
        $ticketType = TicketType::findOrFail($id);
        $ticketType->delete();
        return redirect()->route('admin.ticket_types.index')->with('success', 'Ticket Type deleted successfully.');
    }
}
