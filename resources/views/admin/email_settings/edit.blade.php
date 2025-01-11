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

        <!-- User Guide -->
        <div class="col-md-8 mt-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Setup Guide
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Part 1: Gmail Configuration -->
                    <div class="mb-4">
                        <h5 class="fw-bold">1. Configure Gmail for the application</h5>
                        <div class="ms-4">
                            <p class="fw-bold mb-2">Step 1: Enable 2-Factor Authentication (2FA)</p>
                            <ul class="list-unstyled ms-3">
                                <li><i class="bi bi-dot"></i>Go to <a href="https://myaccount.google.com" target="_blank">Google Account Settings</a></li>
                                <li><i class="bi bi-dot"></i>Go to "Security"</li>
                                <li><i class="bi bi-dot"></i>Enable "2-Step Verification"</li>
                            </ul>

                            <p class="fw-bold mb-2 mt-3">Step 2: Create App Password</p>
                            <ul class="list-unstyled ms-3">
                                <li><i class="bi bi-dot"></i>After enabling 2FA, go to "App Passwords"</li>
                                <li><i class="bi bi-dot"></i>Select "Other" from the app list</li>
                                <li><i class="bi bi-dot"></i>Name the app (e.g., "Mail System")</li>
                                <li><i class="bi bi-dot"></i>Copy the generated password to use in the settings</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Part 2: Fill in the information -->
                    <div class="mb-4">
                        <h5 class="fw-bold">2. Fill in the configuration information</h5>
                        <div class="ms-4">
                            <ul class="list-unstyled">
                                <li><i class="bi bi-dot"></i><span class="fw-semibold">Username:</span> Enter your Gmail address</li>
                                <li><i class="bi bi-dot"></i><span class="fw-semibold">App Password:</span> Enter the app password created in the previous step</li>
                                <li><i class="bi bi-dot"></i><span class="fw-semibold">From Address:</span> The email address displayed when sending (should be the same as Username)</li>
                                <li><i class="bi bi-dot"></i><span class="fw-semibold">From Name:</span> The name displayed in the recipient's email</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Part 3: Check -->
                    <div class="mb-4">
                        <h5 class="fw-bold">3. Check the configuration</h5>
                        <div class="ms-4">
                            <ul class="list-unstyled">
                                <li><i class="bi bi-dot"></i>Click "Save Settings" to save the configuration</li>
                                <li><i class="bi bi-dot"></i>Use the "Send Test Email" button to send a test email</li>
                                <li><i class="bi bi-dot"></i>Check your inbox to confirm the email was sent successfully</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Part 4: Important Notes -->
                    <div class="mb-4">
                        <h5 class="fw-bold">Important Notes</h5>
                        <div class="alert alert-info">
                            <ul class="list-unstyled mb-0">
                                <li><i class="bi bi-exclamation-circle me-2"></i>Do not share the app password with others</li>
                                <li><i class="bi bi-exclamation-circle me-2"></i>Ensure the sending email address is valid to avoid being marked as spam</li>
                                <li><i class="bi bi-exclamation-circle me-2"></i>If you change your Gmail password, you need to create a new app password</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Part 5: Support -->
                    <div>
                        <h5 class="fw-bold">Support</h5>
                        <div class="alert alert-secondary">
                            <p class="mb-2">If you encounter difficulties during the setup process, please contact:</p>
                            <ul class="list-unstyled mb-0">
                                <li><i class="bi bi-envelope-fill me-2"></i>Email: nhokkho18@gmail.com</li>
                                <li><i class="bi bi-telephone-fill me-2"></i>Hotline: 0819530009</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection