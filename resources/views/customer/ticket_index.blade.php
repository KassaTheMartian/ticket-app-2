@extends('layouts.customer_layout')

@section('content')
<div class="container py-4">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="border-left: 5px solid #28a745;">
        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem; color: #28a745;"></i>
        <div>
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="card shadow-sm border-2">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-ticket-alt me-2"></i>My Support Tickets
            </h4>
            <a href="{{ route('customer.tickets.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus-circle me-1"></i>Send New Ticket
            </a>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="ticketsTable" width="100%" cellspacing="0" style="font-size: 15px;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Ticket Type</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Closed At</th>                   
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    let table = $('#ticketsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customer.tickets.index') }}", // Corrected route
        columns: [
            {data: 'id', name: 'id', className: 'text-center'},
            {data: 'title', name: 'tickets.title'},
            {data: 'department', name: 'departments.name', orderable: true},
            {data: 'ticket_type', name: 'ticket_types.name', orderable: true}, 
            {data: 'priority', name: 'tickets.priority', orderable: true},
            {data: 'status', name: 'tickets.status', orderable: true},
            {data: 'created_at', name: 'tickets.created_at', orderable: true},
            {data: 'closed_at', name: 'tickets.closed_at', orderable: true},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
        ],
        order: [[7, 'desc']],
        responsive: true,
        language: {
            searchPlaceholder: "Search tickets...", 
            lengthMenu: "Show _MENU_ entries",
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'
        },
        createdRow: function(row, data, dataIndex) {
        // Thêm class cho mỗi row
        $(row).find('td').addClass('align-middle');
        },
    });
</script>
@endsection