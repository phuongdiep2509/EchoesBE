<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Echoes Admin</title>

    {{-- Google Fonts: Cal Sans (same as client) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- Client base CSS (color variables + Cal Sans) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">

    <style>
        /* ── Design tokens (mirror client) ── */
        :root {
            --echoes-green:  #46462a;
            --echoes-red:    #74070d;
            --echoes-beige:  #e1cfac;
            --echoes-cream:  #f0efeb;
            --echoes-olive:  #525233;
            --font:          "Cal Sans", sans-serif;

            /* sidebar dimensions */
            --sidebar-w: 250px;
            --topbar-h:  58px;
        }

        /* ── Reset for admin context ── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: var(--font) !important;
            background: var(--echoes-cream);
            color: #222;
            margin: 0;
            overflow-x: hidden;
        }

        a { text-decoration: none !important; }

        /* ════════════════════════════════════════
           SIDEBAR
        ════════════════════════════════════════ */
        .admin-sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--echoes-green);   /* same green as client headers */
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        /* Brand / Logo */
        .sidebar-brand {
            padding: 20px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .sidebar-brand a {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: 0.5px;
        }

        .sidebar-brand a:hover { color: var(--echoes-beige); }

        .sidebar-brand .brand-logo {
            width: 38px;
            height: 38px;
            background: var(--echoes-red);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Section label */
        .nav-section {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.45);
            padding: 18px 20px 6px;
        }

        /* Nav links */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.75);
            font-size: 0.9rem;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-left-color: var(--echoes-beige);
        }

        .sidebar-link.active {
            background: rgba(116,7,13,0.25);   /* red tint */
            color: #fff;
            border-left-color: var(--echoes-red);
            font-weight: 600;
        }

        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: 13px;
            opacity: 0.85;
        }

        /* Sidebar footer: logout */
        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.12);
            padding: 12px 0;
        }

        /* ════════════════════════════════════════
           TOPBAR
        ════════════════════════════════════════ */
        .admin-topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 2px solid var(--echoes-beige);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--echoes-green);
            letter-spacing: 0.3px;
        }

        .topbar-actions { display: flex; align-items: center; gap: 10px; }

        /* ════════════════════════════════════════
           CONTENT AREA
        ════════════════════════════════════════ */
        .admin-content { padding: 28px; }

        /* ════════════════════════════════════════
           OVERRIDE BOOTSTRAP to match client theme
        ════════════════════════════════════════ */

        /* Tables */
        .table thead th {
            background: var(--echoes-green);
            color: #fff;
            font-size: 0.82rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: none;
            padding: 12px 16px;
            font-family: var(--font);
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px 16px;
            font-size: 0.9rem;
            color: #333;
            border-color: rgba(0,0,0,0.06);
            font-family: var(--font);
        }

        .table tbody tr:hover { background: rgba(70,70,42,0.04); }

        /* Cards */
        .card {
            border: 1px solid var(--echoes-beige);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }

        .card-header {
            background: var(--echoes-cream);
            border-bottom: 1px solid var(--echoes-beige);
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--echoes-green);
            padding: 14px 20px;
            font-family: var(--font);
        }

        /* Buttons — primary = client green */
        .btn-primary {
            background: var(--echoes-green) !important;
            border-color: var(--echoes-green) !important;
            color: #fff !important;
            font-family: var(--font);
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .btn-primary:hover {
            background: #5a5a35 !important;
            border-color: #5a5a35 !important;
        }

        .btn-danger {
            background: var(--echoes-red) !important;
            border-color: var(--echoes-red) !important;
            color: #fff !important;
            font-family: var(--font);
        }

        .btn-danger:hover { opacity: 0.88 !important; }

        .btn-outline-primary {
            color: var(--echoes-green) !important;
            border-color: var(--echoes-green) !important;
            font-family: var(--font);
        }

        .btn-outline-primary:hover {
            background: var(--echoes-green) !important;
            color: #fff !important;
        }

        .btn-outline-danger {
            color: var(--echoes-red) !important;
            border-color: var(--echoes-red) !important;
            font-family: var(--font);
        }

        .btn-outline-danger:hover {
            background: var(--echoes-red) !important;
            color: #fff !important;
        }

        .btn-secondary, .btn-outline-secondary {
            font-family: var(--font);
        }

        /* Form controls */
        .form-control, .form-select {
            font-family: var(--font);
            font-size: 0.9rem;
            border-color: var(--echoes-beige);
            color: #333;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--echoes-green);
            box-shadow: 0 0 0 0.2rem rgba(70,70,42,0.15);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--echoes-green);
            font-family: var(--font);
        }

        /* Badges */
        .badge { font-family: var(--font); letter-spacing: 0.3px; }

        /* Alerts */
        .alert-success {
            background: rgba(70,70,42,0.08);
            border-color: var(--echoes-green);
            color: var(--echoes-green);
            font-family: var(--font);
        }

        .alert-danger {
            background: rgba(116,7,13,0.08);
            border-color: var(--echoes-red);
            color: var(--echoes-red);
            font-family: var(--font);
        }

        /* Headings in content */
        .admin-content h1,
        .admin-content h2,
        .admin-content h3,
        .admin-content h4 {
            font-family: var(--font);
            color: var(--echoes-green);
        }

        /* Page header bar (breadcrumb-style) */
        .admin-page-header {
            background: var(--echoes-green);
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .admin-page-header h2 {
            font-size: 1.1rem;
            margin: 0;
            color: white;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Stat cards on dashboard */
        .echoes-stat-card {
            background: #fff;
            border: 1px solid var(--echoes-beige);
            border-radius: 12px;
            padding: 20px 24px;
            border-left: 5px solid var(--echoes-green);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .echoes-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(70,70,42,0.12);
        }

        .echoes-stat-card.red  { border-left-color: var(--echoes-red); }
        .echoes-stat-card.beige{ border-left-color: var(--echoes-beige); }
        .echoes-stat-card.olive{ border-left-color: var(--echoes-olive); }

        .stat-number {
            font-size: 2rem;
            font-weight: 900;
            color: var(--echoes-green);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-number.red { color: var(--echoes-red); }

        .stat-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
        }

        .stat-icon {
            font-size: 1.5rem;
            opacity: 0.25;
        }

        /* Dropdown menu */
        .dropdown-item { font-family: var(--font); font-size: 0.875rem; }
        .dropdown-item:hover { background: var(--echoes-cream); color: var(--echoes-green); }
        .dropdown-item.text-danger:hover { background: rgba(116,7,13,0.08); }

        /* User avatar pill in topbar */
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--echoes-cream);
            border: 1px solid var(--echoes-beige);
            border-radius: 999px;
            padding: 5px 14px 5px 8px;
            cursor: pointer;
            transition: background 0.15s;
        }

        .topbar-user:hover { background: var(--echoes-beige); }

        .topbar-user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--echoes-red);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .topbar-user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--echoes-green);
        }

        .topbar-user-role {
            font-size: 0.7rem;
            color: #999;
            display: block;
            line-height: 1;
        }

        /* Role badge */
        .role-badge {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            padding: 3px 8px;
            border-radius: 999px;
            text-transform: uppercase;
        }

        .role-badge.admin    { background: var(--echoes-red);   color: white; }
        .role-badge.staff    { background: var(--echoes-green); color: white; }
        .role-badge.khachhang{ background: var(--echoes-beige); color: var(--echoes-green); }
    </style>

    @yield('styles')
</head>

<body>
<div class="d-flex">

    {{-- ═══════════════════════════
         SIDEBAR
    ═══════════════════════════ --}}
    <aside class="admin-sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <div class="brand-logo">
                    <img src="{{ asset('assets/images/index/logo.png') }}"
                         alt="Echoes"
                         style="width:36px;height:36px;object-fit:contain"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                    <i class="fas fa-music" style="display:none"></i>
                </div>
                Quản lý Echoes
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            <div class="nav-section">Tổng quan</div>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            @if(auth()->user()?->isAdmin())
            <div class="nav-section">Tài khoản</div>
            <a href="{{ route('admin.tai-khoan.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.tai-khoan.*') ? 'active' : '' }}">
                <i class="fas fa-key"></i> Tài khoản
            </a>
            <a href="{{ route('admin.nhan-vien.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.nhan-vien.*') ? 'active' : '' }}">
                <i class="fas fa-id-badge"></i> Nhân viên
            </a>
            @endif

            <div class="nav-section">Khách hàng</div>
            <a href="{{ route('admin.khach-hang.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.khach-hang.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Khách hàng
            </a>

            <div class="nav-section">Nội dung</div>
            <a href="{{ route('admin.concerts.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.concerts.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Concert
            </a>
            <a href="{{ route('admin.loai-su-kien.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.loai-su-kien.*') ? 'active' : '' }}">
                <i class="fas fa-list-alt"></i> Danh mục sự kiện
            </a>
            <a href="{{ route('admin.hang-ve.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.hang-ve.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> Hạng vé
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

            <div class="nav-section">Đặt vé</div>
            <a href="{{ route('admin.orders.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i> Đơn hàng
            </a>

        </nav>

        {{-- Sidebar footer --}}
        <div class="sidebar-footer">
            @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="sidebar-link w-100 border-0 bg-transparent text-start"
                        style="cursor:pointer">
                    <i class="fas fa-sign-out-alt"></i>
                    Đăng xuất
                </button>
            </form>
            @endauth
        </div>

    </aside>

    {{-- ═══════════════════════════
         MAIN
    ═══════════════════════════ --}}
    <div class="flex-grow-1 d-flex flex-column" style="min-width:0">

        {{-- Topbar --}}
        <div class="admin-topbar">
            <span class="topbar-title">@yield('title', 'Dashboard')</span>

            <div class="topbar-actions">
                {{-- Link to public site --}}
                <a href="{{ route('home') }}" target="_blank"
                   class="btn btn-sm btn-outline-secondary"
                   style="font-family:var(--font);font-size:0.8rem">
                    <i class="fas fa-external-link-alt me-1"></i> Xem website
                </a>

                {{-- User dropdown --}}
                @auth
                <div class="dropdown">
                    <button class="topbar-user border-0" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="topbar-user-avatar">
                            {{ mb_strtoupper(mb_substr(auth()->user()->HoTen, 0, 1)) }}
                        </div>
                        <div>
                            <span class="topbar-user-name">{{ auth()->user()->HoTen }}</span>
                            <span class="topbar-user-role">
                                {{ auth()->user()->isAdmin() ? 'Admin' : 'Nhân viên' }}
                            </span>
                        </div>
                        <i class="fas fa-chevron-down ms-1" style="font-size:10px;color:#aaa"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                        style="border-color:var(--echoes-beige);min-width:180px;font-family:var(--font)">
                        <li>
                            <a class="dropdown-item" href="#"
                               onclick="openProfilePopup('view'); return false;">
                                <i class="fas fa-user me-2" style="color:var(--echoes-green)"></i>
                                Hồ sơ cá nhân
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item"
                                        style="color:var(--echoes-red)">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>

        {{-- Content --}}
        <main class="admin-content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('partials.profile-popup')
@yield('scripts')
</body>
</html>
