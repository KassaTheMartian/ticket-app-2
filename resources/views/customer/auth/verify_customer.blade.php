<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .verification-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .verification-box {
            max-width: 400px;
            width: 100%;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }
        .verification-icon {
            font-size: 3rem;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container verification-container">
        <div class="verification-box">
            <div class="login-header">
                <h2>Email Verification</h2>
            </div>
            @if (session('success'))
                <div class="verification-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="alert alert-success mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @elseif (session('message'))
                <div class="verification-icon">
                    <i class="bi bi-x-circle-fill" style="color: #dc3545;"></i>
                </div>
                <div class="alert alert-danger mb-4" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <a href="{{ route('customer.auth.login') }}" class="btn btn-primary btn-lg w-100">
                Back to Login Page
            </a>
        </div>
    </div>
</body>

    <!-- Bootstrap JS and dependencies (optional, but recommended) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>