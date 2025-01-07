<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {!! ReCaptcha::htmlScriptTagJsApi() !!}

    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #34495e;
            --background-color: #f7f9fc;
        }

        body {
            background-color: var(--background-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
        }

        .register-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(50,50,93,.1), 0 5px 15px rgba(0,0,0,.07);
            padding: 2rem 2.5rem ;
            width: 100%;
            max-width: 750px;
            transition: transform 0.3s ease;
        }

        .register-container:hover {
            transform: translateY(-1px);
        }

        .register-header {
            text-align: center;
            margin-bottom: 0.5rem; /* Reduced margin */
        }

        .register-header h2 {
            color: var(--secondary-color);
            font-weight: 700;
            letter-spacing: -0.5px;
            font-size: 1.75rem; /* Slightly smaller heading */
        }

        .form-control {
            border-radius: 10px;
            padding: 0.55rem 1rem; /* Reduced padding */
            border-color: rgba(50,50,93,.1);
            transition: all 0.3s ease;
            font-size: 0.9rem;

        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(74,144,226,0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem; /* Reduced padding */
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3a7bd5;
            transform: translateY(-3px);
            box-shadow: 0 7px 14px rgba(50,50,93,.1), 0 3px 6px rgba(0,0,0,.08);
        }

        .login-link {
            color: #6c757d;
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="register-container">
                <div class="register-header">
                    <h2>Create Your Account</h2>
                    <p class="text-muted">Enter your details to get started</p>
                </div>
                <form action="{{ route('customer.auth.register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" value="{{ old('name') }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" value="{{ old('phone') }}" required>
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Create a strong password" required>
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" required>
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    {!! ReCaptcha::htmlFormSnippet() !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                    @endif
                    <button type="submit" class="btn btn-primary w-100 mt-3">Create Account</button>
                </form>
                
                <div class="login-link text-center mt-3">
                    Already have an account? <a href="{{ route('customer.auth.login') }}" class="text-primary">Log in</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>