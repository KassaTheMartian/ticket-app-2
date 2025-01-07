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
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $customer->date_of_birth }}">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" value="{{ $customer->address }}">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="software" class="form-label">Software</label>
                                        <input type="text" class="form-control" id="software" name="software" value="{{ $customer->software }}">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" class="form-control" id="website" name="website" value="{{ $customer->website }}">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="tax_number" class="form-label">Tax Number</label>
                                        <input type="text" class="form-control @error('tax_number') is-invalid @enderror" id="tax_number" name="tax_number" value="{{ $customer->tax_number }}">
                                        @error('tax_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="verify_at" class="form-label">Verify At</label>
                                        <input class="form-control" id="verify_at" value="{{ $customer->email_verified_at }}" disabled>
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
</div>
@endsection