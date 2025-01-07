<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickies</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/api/placeholder/32/32">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-head.tinymce-config/>
    
    {!! ReCaptcha::htmlScriptTagJsApi() !!}

    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-bg: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }
        
        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .nav-link {
            color: var(--dark-bg) !important;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .footer {
            background-color: var(--dark-bg);
            color: white;
            padding: 20px 0;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .social-icons a {
        font-size: 1.2rem;
        transition: color 0.3s ease;
        }
        .social-icons a:hover {
            color: #007bff !important;
        }
    </style>

    @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-ticket-alt me-2"></i>
                Tickies
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if(Auth::guard('customer')->check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.dashboard') }}">
                                <i class="fas fa-home me-1"></i>Home
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.tickets.create') }}">
                                <i class="fas fa-plus-circle me-1"></i>Create New Ticket
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.tickets.index') }}">
                                <i class="fas fa-list-alt me-1"></i>My Tickets
                            </a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav">
                    @if(Auth::guard('customer')->check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
                                {{ Auth::guard('customer')->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="{{ route('customer.profile') }}">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.auth.logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                                <form id="logout-form" action="{{ route('customer.auth.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('customer.auth.login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1" style="min-height: 100vh;">
        @yield('content')
    </main>

    <footer class="bg-light text-dark py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="d-flex align-items-center mb-3">
                        {{-- <img src="/api/placeholder/50/50" alt="Tickies Logo" class="me-3 rounded-circle"> --}}
                        <h4 class="mb-0 fw-bold">Tickies</h4>
                    </div>
                    <p class="text-muted">Welcome to Tickies, your number one source for all things tickets. We're dedicated to providing you the best ticket booking experience with focus on dependability, customer service, and uniqueness.</p>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            Nha Trang, Khanh Hoa, Vietnam
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone-alt me-2 text-primary"></i>
                            0819530009
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            nhokkho18@gmail.com
                        </li>
                    </ul>
                    {{-- <div class="social-icons mt-3">
                        <a href="#" class="text-dark me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-dark me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-dark me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-dark"><i class="fab fa-linkedin-in"></i></a>
                    </div> --}}
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>
                    &copy; {{ date('Y') }} Tickies
                    <span class="mx-2">•</span> 
                    All Rights Reserved
                </p>
                <small class="text-muted">Powered by Nhom 5</small>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.min.js"></script>
    @yield('scripts')
</body>
</html>