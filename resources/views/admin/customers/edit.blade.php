@extends('layouts.app')

@section('page-title', "Edit Customer")

@section('content')
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $customer->profile_picture)}}" 
                                        alt="Profile Picture" 
                                        class="img-fluid rounded-circle mb-3 shadow" 
                                        style="width: 200px; height: 200px; object-fit: cover;"
                                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=0D8ABC&color=fff';">
                                    <div class="custom-file mt-2">
                                        <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                                        <label class="form-label text-muted mt-2">Change Profile Picture</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="" {{ $customer->gender == '' ? 'selected' : '' }}>Select Gender</option>
                                            <option value="male" {{ $customer->gender == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ $customer->gender == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ $customer->gender == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $customer->date_of_birth }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" value="{{ $customer->address }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="software" class="form-label">Software</label>
                                        <input type="text" class="form-control" id="software" name="software" value="{{ $customer->software }}">
                                        @error('software')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" class="form-control" id="website" name="website" value="{{ $customer->website }}">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="tax_number" class="form-label">Tax Number</label>
                                        <input type="text" class="form-control @error('tax_number') is-invalid @enderror" id="tax_number" name="tax_number" value="{{ $customer->tax_number }}">
                                        @error('tax_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="verify_at" class="form-label">Verify At</label>
                                        <input class="form-control" id="verify_at" value="{{ $customer->email_verified_at ? \Carbon\Carbon::parse($customer->email_verified_at)->format('d/m/Y h:i A') : '' }}" disabled>
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" {{ $customer->email_verified_at ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_verified">
                                                Verified
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-lg">Back</a>
                            <button type="submit" class="btn btn-primary btn-lg">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4 mt-4 px-0">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Tickets List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: 15px;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Type</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Closed At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td class="text-center">{{ $ticket->id }}</td>
                                <td>{{ $ticket->title }}</td>
                                <td>{{ $ticket->department->name ?? '' }}</td>
                                <td>{{ $ticket->ticketType->name ?? '' }}</td>
                                <td>
                                    @if($ticket->priority == 'low')
                                        <p hidden>1</p>
                                        <span class="badge bg-primary text-light">{{ ucfirst($ticket->priority) }}</span>
                                    @elseif($ticket->priority == 'medium')
                                        <p hidden>2</p>
                                        <span class="badge bg-warning text-dark">{{ ucfirst($ticket->priority) }}</span>
                                    @elseif($ticket->priority == 'high')
                                        <p hidden>3</p>
                                        <span class="badge bg-danger text-light">{{ ucfirst($ticket->priority) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->status == 'new')
                                        <p hidden>1</p>
                                        <span class="badge bg-primary text-light">New</span>
                                    @elseif($ticket->status == 'inprogress')
                                        <p hidden>2</p>
                                        <span class="badge bg-warning text-dark">In Progress</span>
                                    @elseif($ticket->status == 'resolved')
                                        <p hidden>3</p>
                                        <span class="badge bg-success text-light">Resolved</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                <td>{{ $ticket->closed_at ? \Carbon\Carbon::parse($ticket->closed_at)->format('d M Y') : '' }}</td>
                                <td class="text-center">
                                    <div>
                                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-outline-warning btn-sm rounded-2 mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button data-form="deleteForm{{ $ticket->id }}" class="btn btn-outline-danger btn-sm btn-delete rounded-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="deleteForm{{ $ticket->id }}" action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" class="d-none">
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
            searchPlaceholder: "Search tickets...",
            lengthMenu: "Show _MENU_ entries"
        },
        order: [[6, 'desc']], // Sắp xếp cột thứ 8 (zero-indexed là 7) theo thứ tự tăng dần
        // columnDefs: [
        //     { 
        //         targets: [0, 6], 
        //         orderable: false 
        //     }
        // ]
    });
});
</script>
@endsection