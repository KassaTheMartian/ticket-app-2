<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
                        <h3>Reset Password</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customer.auth.password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ request()->segment(6) }}" placeholder="Enter your email address" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <div class="text-danger mt-2">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm your password" required>
                                </div>
                            </div>

                            {!! ReCaptcha::htmlFormSnippet() !!}
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="text-danger">Authentication Failed</span>
                            @endif

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary" style="background-color: #4A90E2;">Reset Password</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small class="text-muted">Back to <a href="#" class="text-primary">Login</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
