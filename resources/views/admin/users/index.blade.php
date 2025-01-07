@extends('layouts.app')

@section('page-title', 'Users Management')

@section('content')
<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Users List</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-500">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add User</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="usersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
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
                text: "Are you sure you want to delete this user?",
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

    let table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.users.index') }}",
        columns: [
            {data: 'id', name: 'id', className: 'text-center'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
        ],
        responsive: true,
        language: {
            searchPlaceholder: "Search users...",
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