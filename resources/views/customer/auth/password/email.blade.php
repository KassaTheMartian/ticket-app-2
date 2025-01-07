<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    {!! ReCaptcha::htmlScriptTagJsApi() !!}
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header" style="background-color: #4A90E2; color: white; text-align: center;">
                        <h3>Forgot Password</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('customer.auth.password.email.post') }}">
                            @csrf
                            <div class="mb-3 text-center">
                                <p class="text-muted">
                                    Enter your registered email address. We will send you a password reset link.
                                </p>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input 
                                        type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        placeholder="Enter your email address" 
                                        required 
                                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-muted">
                                    Example: user@example.com
                                </div>
                            </div>
                            {!! ReCaptcha::htmlFormSnippet() !!}
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="text-danger">Authentication Failed</span>
                            @endif
                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-primary" style="background-color: #4A90E2; border-color: #4A90E2;">
                                    Send Password Reset Request
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-footer text-center">
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('customer.auth.login') }}" class="text-primary text-decoration-none">
                                    Back to Login
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('customer.auth.register') }}" class="text-primary text-decoration-none">
                                    Register New Account
                                </a>
                            </div>
                        </div>
                        @if (session('status'))
                        <div class="alert alert-success mt-4" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>