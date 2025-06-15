<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DentalCare</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #7367f0;
            --primary-light: rgba(115, 103, 240, 0.12);
            --secondary-color: #82868b;
            --success-color: #28c76f;
            --warning-color: #ff9f43;
            --danger-color: #ea5455;
            --info-color: #00cfe8;
            --dark-color: #4b4b4b;
            --light-color: #f8f8f8;
            --sidebar-bg: #2b2c33;
            --sidebar-active: rgba(115, 103, 240, 0.2);
            --card-bg: #ffffff;
            --body-bg: #f5f7fa;
            --text-primary: #5e5873;
            --text-secondary: #6e6b7b;
            --border-color: rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            color: var(--text-primary);
        }
        
        /* Sidebar - Modern Design */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 260px;
            padding: 1.5rem 0;
            background-color: var(--sidebar-bg);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 1.5rem 1.5rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand h4 {
            font-weight: 600;
            margin-bottom: 0;
            color: white;
        }
        
        .sidebar-nav {
            padding: 0 1rem;
        }
        
        .sidebar-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 0;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .sidebar-link.active {
            color: white;
            background-color: var(--sidebar-active);
            font-weight: 600;
        }
        
        .sidebar-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        /* Top Navbar - Modern Design */
        .top-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 260px;
            height: 70px;
            background-color: var(--card-bg);
            box-shadow: 0 1px 15px rgba(0, 0, 0, 0.04);
            z-index: 999;
            transition: all 0.3s ease;
        }
        
        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 2rem;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 12px;
        }
        
        .user-name {
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .logout-btn {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Main Content Area */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            margin-top: 70px;
            transition: all 0.3s ease;
        }
        
        /* Modern Alerts */
        .alert-modern {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .alert-success {
            background-color: rgba(40, 199, 111, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(234, 84, 85, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        /* Modern Cards */
        .modern-card {
            background: var(--card-bg);
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .card-header-modern {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }
        
        .card-title-modern {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .top-navbar, .main-content {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="navbar-content">
            <div></div> <!-- Empty div for spacing -->
            
            <div class="user-profile">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span class="user-name">{{ auth()->user()->name }}</span>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="fas fa-tooth me-2"></i> DentalCare</h4>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.dentists') }}" class="sidebar-link {{ request()->routeIs('admin.dentists*') ? 'active' : '' }}">
                <i class="fas fa-user-md"></i> Manage Dentists
            </a>
            <a href="{{ route('admin.schedule') }}" class="sidebar-link {{ request()->routeIs('admin.schedule*') ? 'active' : '' }}">
                <i class="fas fa-clock"></i> Working Hours
            </a>
            <a href="{{ route('admin.appointments') }}" class="sidebar-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Appointments
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-modern alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-modern alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Mobile sidebar toggle functionality
        $(document).ready(function() {
            $('.navbar-toggler').on('click', function() {
                $('.sidebar').toggleClass('active');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>