<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Echoes Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }

        .admin-sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1a1a2e;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand a {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand a:hover { color: #a78bfa; }

        .sidebar-nav { padding: 12px 0; }

        .nav-section {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            padding: 16px 20px 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            color: #d1d5db;
            text-decoration: none;
            font-size: 0.875rem;
            transition: background 0.15s, color 0.15s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
            text-decoration: none;
        }

        .sidebar-link.active {
            background: rgba(124,58,237,0.2);
            color: #a78bfa;
            border-left-color: #7c3aed;
        }

        .sidebar-link i { width: 16px; text-align: center; font-size: 13px; }

        .admin-topbar {
            height: 56px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-weight: 600;
            font-size: 1rem;
            color: #111827;
        }

        .admin-content { padding: 24px; }

        /* Active state helper */
        .sidebar-link[data-route="{{ request()->route()->getName() ?? '' }}"] {
            background: rgba(124,58,237,0.2);
            color: #a78bfa;
            border-left-color: #7c3aed;
        }
    </style>

    @yield('styles')
</head>

<body>
<div class="d-flex">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-music"></i>
                Echoes Admin
            </a>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-section">Tổng quan</div>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            <div class="nav-section">Quản lý nội dung</div>
            <a href="{{ route('admin.concerts.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.concerts.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Concert
            </a>
            <a href="{{ route('admin.music.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.music.*') ? 'active' : '' }}">
                <i class="fas fa-music"></i> Nhạc sống
            </a>
            <a href="{{ route('admin.merchandise.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.merchandise.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> Merchandise
            </a>
            <a href="{{ route('admin.news.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> Tin tức
            </a>

        </nav>

    </aside>

    <!-- MAIN -->
    <div class="flex-grow-1 d-flex flex-column" style="min-width:0">

        <!-- TOPBAR -->
        <div class="admin-topbar">
            <span class="topbar-title">@yield('title', 'Dashboard')</span>
            <div class="d-flex align-items: center; gap: 12px">
                <a href="{{ route('home') }}" target="_blank"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-external-link-alt"></i> Xem website
                </a>
            </div>
        </div>

        <!-- CONTENT -->
        <main class="admin-content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
