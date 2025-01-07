@extends('layouts.app')
@section('page-title', 'Tickets Management')
@section('content')
<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Tickets
            </h6>
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterForm" aria-expanded="false" aria-controls="filterForm">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
            <div class="collapse" id="filterForm">
                <div class="card-body">
                <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-2">        
                <div class="col-md-3">
                <label for="ticket_type_select" class="form-label">Ticket Type</label>
                <select name="ticket_type_id" id="ticket_type_select" class="selectpicker w-100 border rounded">
                    <option value="">All Ticket Types</option>
                    @foreach($ticketTypes as $type)
                        <option value="{{ $type->id }}" 
                            {{ request('ticket_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(auth()->user()->admin == 1)
                <div class="col-md-3">
                    <label for="department_select" class="form-label">Department</label>
                    <select name="department_id" id="department_select" class="selectpicker w-100 border rounded">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" 
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            
            <div class="col-md-3">
                <label for="customer_select" class="form-label">Customer</label>
                <select name="customer_id" id="customer_select" class="selectpicker w-100 border rounded" data-live-search="true">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" 
                            {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <div class="col-md-3">
                <label for="priority_select" class="form-label">Priority</label>
                <select name="priority" id="priority_select" class="selectpicker w-100 border rounded">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" 
                            {{ request('priority') == $priority ? 'selected' : '' }}>
                            {{ ucfirst($priority) }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <div class="col-md-3">
                <label for="status_select" class="form-label">Status</label>
                <select name="status" id="status_select" class="selectpicker w-100 border rounded">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" 
                            {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" 
                    value="{{ request('start_date') }}" class="form-control">
            </div>
        
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" 
                    value="{{ request('end_date') }}" class="form-control">
            </div>
        
            <div class="col-md-3 d-flex align-items-end justify-content-between">
                <button type="submit" class="btn btn-primary me-2 px-5">Filter</button>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary px-4">Reset</a>
            </div>
        </form>
        </div>
    </div>
</div>    
<div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Tickets List</h5>
            {{-- <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-500">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Ticket</span>
            </a> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="ticketsTable" width="100%" cellspacing="0" style="font-size: 15px;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Title</th>
                            <th>Customer</th>         
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

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function() {
        $(document).on('click', '.btn-delete', function() {
            let formId = $(this).data('form');
            Swal.fire({
                title: "Confirm Deletion",
                text: "Are you sure you want to delete this ticket?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                cancelButtonClass: "btn btn-secondary mr-2",
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#${formId}`).submit();
                }
            });
        });
    });

    let table = $('#ticketsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "{{ route('admin.tickets.index') }}",
        data: function(d) {
            d.ticket_type_id = $('#ticket_type_select').val();
            d.department_id = $('#department_select').val();
            d.customer_id = $('#customer_select').val();
            d.priority = $('#priority_select').val();
            d.status = $('#status_select').val();
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
        }
    },
    columns: [
        {data: 'id', name: 'id', className: 'text-center'},
        {data: 'title', name: 'tickets.title'},
        {data: 'customer', name: 'customers.name', orderable: true},
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
        pageLength: 10,
        deferRender: true,
        orderCellsTop: true,
        fixedHeader: true,
        createdRow: function(row, data, dataIndex) {
        // Thêm class cho mỗi row
        $(row).find('td').addClass('align-middle');
        },
    });

    // Xử lý sự kiện khi form filter được submit
    $('form').on('submit', function(e) {
        e.preventDefault();
        table.draw();
    });



</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function () {
        $('.selectpicker').selectpicker();
    });
</script>
@endsection