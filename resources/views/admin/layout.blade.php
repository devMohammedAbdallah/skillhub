<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة التحكم') — {{ config('app.name', 'SkillHub') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --tc-primary: #4caf50;
            --tc-primary-dark: #2e7d32;
            --tc-sidebar: #1b4728;
            --tc-sidebar-hover: #2e7d32;
            --tc-bg: #f0f4f8;
        }

        body {
            background-color: var(--tc-bg);
            min-height: 100vh;
        }

        a {
            color: var(--tc-primary-dark);
        }

        .btn-primary {
            --bs-btn-bg: #4caf50;
            --bs-btn-border-color: #4caf50;
            --bs-btn-hover-bg: #43a047;
            --bs-btn-hover-border-color: #43a047;
            --bs-btn-active-bg: #388e3c;
            --bs-btn-active-border-color: #388e3c;
        }

        .btn-outline-primary {
            --bs-btn-color: #2e7d32;
            --bs-btn-border-color: #2e7d32;
            --bs-btn-hover-bg: #4caf50;
            --bs-btn-hover-border-color: #4caf50;
            --bs-btn-hover-color: #fff;
        }

        .btn-outline-danger {
            --bs-btn-color: #dc3545;
            --bs-btn-border-color: #dc3545;
            --bs-btn-hover-bg: #dc3545;
            --bs-btn-hover-border-color: #dc3545;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4caf50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.15);
        }

        /* Layout */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: var(--tc-sidebar);
            color: #fff;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .admin-sidebar .brand {
            display: block;
            padding: 1.1rem 1.25rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            background-color: rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .admin-sidebar .brand:hover {
            color: #fff;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.7rem 1.25rem;
            border-left: 3px solid transparent;
            font-size: 0.95rem;
        }

        .admin-sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.08);
            border-left-color: var(--tc-primary);
        }

        .admin-sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(76, 175, 80, 0.25);
            border-left-color: var(--tc-primary);
            font-weight: 600;
        }

        .admin-sidebar .nav-link i {
            width: 1.25rem;
            margin-inline-end: 0.4rem;
        }

        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .admin-topbar {
            background-color: #fff;
            border-bottom: 1px solid #e3e8ef;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .admin-content {
            padding: 1.5rem;
            flex: 1;
        }

        .admin-topbar .user-name {
            font-weight: 600;
            color: var(--tc-primary-dark);
        }

        .page-title {
            color: var(--tc-primary-dark);
        }

        .stat-card {
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.75rem rgba(27, 71, 40, 0.08);
            overflow: hidden;
        }

        .stat-card .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            background-color: rgba(76, 175, 80, 0.15);
            color: var(--tc-primary-dark);
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--tc-sidebar);
            line-height: 1;
        }

        .stat-card .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .table thead th {
            background-color: var(--tc-primary-dark);
            color: #fff;
            border: none;
            font-weight: 600;
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .card {
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.75rem rgba(27, 71, 40, 0.08);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 2px solid var(--tc-primary);
            font-weight: 700;
            color: var(--tc-primary-dark);
        }

        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: fixed;
                z-index: 1040;
                left: -250px;
                transition: left 0.25s ease;
            }

            .admin-sidebar.show {
                left: 0;
            }

            .sidebar-toggle {
                display: inline-flex;
            }

            .sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                z-index: 1030;
            }

            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar" id="adminSidebar">
            <a href="{{ route('admin.dashboard') }}" class="brand">
                <i class="bi bi-mortarboard-fill me-1"></i> SkillHub
            </a>
            <nav class="nav flex-column mt-2">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                   href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-folder2"></i> Categories
                </a>
                <a class="nav-link {{ request()->routeIs('admin.instructors.*') ? 'active' : '' }}"
                   href="{{ route('admin.instructors.index') }}">
                    <i class="bi bi-person-badge"></i> Instructors
                </a>
                <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}"
                   href="{{ route('admin.courses.index') }}">
                    <i class="bi bi-book"></i> Courses
                </a>
                <a class="nav-link {{ request()->routeIs('admin.enrollments.*') ? 'active' : '' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-journal-check"></i> Enrollments
                </a>
                <a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}"
                   href="{{ route('admin.messages.index') }}">
                    <i class="bi bi-envelope-paper"></i> Messages
                </a>
            </nav>
        </aside>

        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <div class="admin-main">
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm sidebar-toggle"
                            id="sidebarToggle" aria-label="القائمة">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="user-name d-none d-sm-inline">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->name }}
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-globe me-1"></i> عرض الموقع
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i> تسجيل الخروج
                        </button>
                    </form>
                </div>
            </header>

            <main class="admin-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var sidebar = document.getElementById('adminSidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            var toggle = document.getElementById('sidebarToggle');

            function closeSidebar() {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
