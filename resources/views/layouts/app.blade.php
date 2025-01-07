<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Admin Panel') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    {{-- html editer --}}
    <x-head.tinymce-config/>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --sidebar-width: 250px;
        }

        body {
            background-color: #f4f6f9;
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
        }

        /* Improved Responsive Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: #2c3034;
            transition: transform 0.3s ease-in-out;
            z-index: 1040;
            overflow-y: auto;
        }

        /* Mobile Styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 300px;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;
                margin-left: 0;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1030;
            }

            .overlay.show {
                display: block;
            }
        }

        /* Desktop Styles */
        @media (min-width: 993px) {
            .main-content {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }
        }

        .sidebar-brand {
            padding: 0.8rem;
            text-align: center;
            background-color: #212529;
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            transition: text-shadow 0.3s ease;
        }

        .sidebar-brand:hover {
            text-shadow: 0 0 20px rgba(255, 255, 255, 1);
        }

        .sidebar-menu .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background-color: rgba(185, 177, 177, 0.3);
            color: #ffffff;
        }

        .topbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .content-wrapper {
            padding: 20px;
        }

        /* Improved Responsive Handling */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Prevent input zoom on mobile */
        @media (max-width: 576px) {
            input, select, textarea {
                font-size: 16px;
            }
        }

        /* User Dropdown */
        .user-actions .dropdown-toggle {
            display: flex;
            align-items: center;
        }

        /* Smooth Transitions */
        * {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Overlay for mobile sidebar -->
    <div class="overlay"></div>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <nav class="col sidebar" id="sidebar">
                <div class="sidebar-brand">
                    Admin Panel
                </div>
                <div class="sidebar-menu mx-2">
                    <ul class="nav flex-column">
                        @php
                            $menus = config('menu');
                            $currentUser = Auth::user();
                            $currentRoute = Route::currentRouteName();
                        @endphp
                        @auth
                            @foreach ($menus as $menu)  
                                @if($currentUser->admin == 1 || $currentUser->can($menu['route']))
                                    <li class="nav-item my-1">
                                        <a class="nav-link rounded {{ $currentRoute == $menu['route'] ? 'active' : '' }}" 
                                           href="{{ route($menu['route']) }}">
                                            <i class="{{ $menu['icon'] ?? 'fas fa-circle' }} me-2"></i>
                                            {{ $menu['label'] }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endauth
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="main-content">
                <!-- Top Bar -->
                <div class="topbar d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-outline-secondary me-3 d-md-none toggle-sidebar" type="button">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <h1 class="h5 m-0 p-2" style="background-color: #4A90E2; color: white; border-radius: 0.25rem;">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="user-actions">
                        @auth
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle text-decoration-none" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user me-2"></i>
                                    <span>{{ $currentUser->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}">
                                        <i class="fas fa-user me-2"></i> Profile
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('admin.auth.logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Page Content -->
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('admin.auth.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.overlay');
            const toggleButtons = document.querySelectorAll('.toggle-sidebar');

            // Sidebar Toggle
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
            });

            // Close sidebar when clicking overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });

            // Close sidebar on nav link click (mobile)
            const navLinks = document.querySelectorAll('.sidebar-menu .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 993) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            });

            // Prevent zoom on input focus (mobile)
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (window.innerWidth < 576) {
                        document.querySelector('meta[name=viewport]').setAttribute(
                            'content', 
                            'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'
                        );
                    }
                });
                input.addEventListener('blur', function() {
                    document.querySelector('meta[name=viewport]').setAttribute(
                        'content', 
                        'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'
                    );
                });
            });
        });
    </script>
    @yield('script')
</body>
</html>