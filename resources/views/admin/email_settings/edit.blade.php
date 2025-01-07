@extends('layouts.app')

@section('page-title', "Email Settings")

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        @if (session('success'))
        <div class="col-md-7 alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="col-md-7 alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">Email Configuration Settings</h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('email.settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Username Settings -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label fw-semibold">Username</label>
                                    <input type="text" 
                                           class="form-control @error('username') is-invalid @enderror" 
                                           id="username" 
                                           name="username" 
                                           value="{{ $settings->username ?? '' }}"
                                           placeholder="your@email.com">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Settings -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">App Password</label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           value="{{ $settings->password ?? '' }}"
                                           placeholder="Enter password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- From Address Settings -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="from_address" class="form-label fw-semibold">From Address</label>
                                    <input type="email" 
                                           class="form-control @error('from_address') is-invalid @enderror" 
                                           id="from_address" 
                                           name="from_address" 
                                           value="{{ $settings->from_address ?? '' }}"
                                           placeholder="noreply@example.com">
                                    @error('from_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- From Name Settings -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="from_name" class="form-label fw-semibold">From Name</label>
                                    <input type="text" 
                                           class="form-control @error('from_name') is-invalid @enderror" 
                                           id="from_name" 
                                           name="from_name" 
                                           value="{{ $settings->from_name ?? '' }}"
                                           placeholder="Company Name">
                                    @error('from_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">
            <form action="{{ route('email.settings.sendTestEmail') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary">
                <i class="bi bi-envelope me-2"></i>Send Test Email
            </button>
            </form>
        </div>
    </div>
</div>
@endsection
