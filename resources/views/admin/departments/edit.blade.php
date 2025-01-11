@extends('layouts.app')

@section('page-title', "Edit Department")

@section('content')
<div class="card shadow">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Oops! Something went wrong.</strong>
                <ul class="mt-2 mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="name" class="form-label">Department Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $department->name }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="6">{{ $department->description }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <i class="fas fa-save me-2"></i>Update Department
                </button>
            </div>
        </form>
    </div>
</div>
<div class="card shadow mb-4 mt-4 px-0">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Staff List</h5>
    </div>
    <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="text-center">{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td class="text-center">
                                    <div>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-warning btn-sm rounded-2 mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button data-form="deleteForm{{ $user->id }}" class="btn btn-outline-danger btn-delete btn-sm rounded-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="deleteForm{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-none">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
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
    
$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: "Search users...",
            lengthMenu: "Show _MENU_ entries"
        },
        // columnDefs: [
        //     { 
        //         targets: [0, 3], 
        //         orderable: false 
        //     }
        // ]
    });
});
</script>
@endsection