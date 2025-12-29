<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HotPop Admin - @yield('title', 'Dashboard')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
            color: white;
        }
        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 1.1rem;
            color: #d1d1d1;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            color: #f1f1f1;
            background-color: #495057;
        }
        .sidebar .brand {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.5rem;
            color: #FF6600; /* HotPop Color */
        }
        .active-link {
            background-color: #FF6600;
            color: white !important;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .topbar {
            background-color: white;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 30px;
        }
        .logout-btn {
            background: none;
            border: none;
            color: #dc3545;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">HotPop Admin</div>
        <a href="{{ route('manage.dashboard.index') }}" class="{{ request()->routeIs('manage.dashboard.*') ? 'active-link' : '' }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
        <a href="{{ route('manage.users.index') }}" class="{{ request()->routeIs('manage.users.*') ? 'active-link' : '' }}">
            <i class="fas fa-users me-2"></i> Users
        </a>
         {{-- Add more links here --}}
         <a href="{{ route('manage.subs-plans.index') }}" class="{{ request()->routeIs('manage.subs-plans.*') ? 'active-link' : '' }}">
            <i class="fas fa-credit-card me-2"></i> Plans
        </a>
         <a href="{{ route('manage.reports.index') }}" class="{{ request()->routeIs('manage.reports.*') ? 'active-link' : '' }}">
            <i class="fas fa-flag me-2"></i> Reports
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            {{-- <span class="me-3">Welcome, {{ auth()->user()->name }}</span> --}}
             <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-1"></i> Admin
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        @include('partials.flash')
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
