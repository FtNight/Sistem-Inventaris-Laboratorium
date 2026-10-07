<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Inventaris Laboratorium - Manajemen aset, barang masuk, dan laporan kerusakan laboratorium secara digital.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Sistem Inventaris Laboratorium</title>

    {{-- Bootstrap 5 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:       #4f46e5;
            --primary-dark:  #3730a3;
            --primary-light: #818cf8;
            --secondary:     #06b6d4;
            --success:       #10b981;
            --warning:       #f59e0b;
            --danger:        #ef4444;
            --bg-dark:       #0f172a;
            --bg-card:       #1e293b;
            --bg-surface:    #263148;
            --text-muted:    #94a3b8;
            --border-color:  #334155;
            --sidebar-w:     260px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: #e2e8f0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ═══════════════════ SIDEBAR ═══════════════════ */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, #1a1f3a 0%, #0f172a 100%);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-text {
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.3;
            color: #f1f5f9;
        }

        .sidebar-brand .brand-sub {
            font-size: 0.72rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.75rem;
        }

        .sidebar-section-title {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            padding: 0.5rem 0.5rem 0.25rem;
            margin-top: 0.5rem;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .nav-link-item:hover {
            background-color: rgba(79, 70, 229, 0.12);
            color: var(--primary-light);
        }

        .nav-link-item.active {
            background: linear-gradient(135deg, rgba(79,70,229,0.25), rgba(6,182,212,0.1));
            color: #fff;
            border: 1px solid rgba(79,70,229,0.3);
        }

        .nav-link-item .nav-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            background-color: rgba(255,255,255,0.05);
            transition: background 0.2s;
        }

        .nav-link-item.active .nav-icon {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
        }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid var(--border-color);
        }

        .user-info-card {
            background: var(--bg-surface);
            border-radius: 12px;
            padding: 0.75rem;
        }

        .user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: #fff;
            flex-shrink: 0;
        }

        /* ═══════════════════ MAIN CONTENT ═══════════════════ */
        #main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ═══════════════════ TOPBAR ═══════════════════ */
        .topbar {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .topbar-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #f1f5f9;
        }

        .topbar-sub {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        /* ═══════════════════ PAGE CONTENT ═══════════════════ */
        .page-content {
            padding: 1.75rem;
            flex: 1;
        }

        /* ═══════════════════ CARDS ═══════════════════ */
        .card-dark {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
        }

        .card-dark .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
        }

        /* ═══════════════════ STAT CARDS ═══════════════════ */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.3);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 16px 16px 0 0;
        }

        .stat-card.blue::before   { background: linear-gradient(90deg, var(--primary), var(--secondary)); }
        .stat-card.green::before  { background: linear-gradient(90deg, var(--success), #34d399); }
        .stat-card.red::before    { background: linear-gradient(90deg, var(--danger), #f97316); }
        .stat-card.yellow::before { background: linear-gradient(90deg, var(--warning), #fcd34d); }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.blue   { background: rgba(79,70,229,0.15); color: var(--primary-light); }
        .stat-icon.green  { background: rgba(16,185,129,0.15); color: #34d399; }
        .stat-icon.red    { background: rgba(239,68,68,0.15); color: #f87171; }
        .stat-icon.yellow { background: rgba(245,158,11,0.15); color: #fbbf24; }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            color: #f1f5f9;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* ═══════════════════ TABLES ═══════════════════ */
        .table-dark-custom {
            color: #e2e8f0;
        }

        .table-dark-custom thead th {
            background: var(--bg-surface);
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-color: var(--border-color);
            padding: 0.85rem 1rem;
        }

        .table-dark-custom tbody td {
            border-color: var(--border-color);
            padding: 0.85rem 1rem;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .table-dark-custom tbody tr {
            transition: background 0.15s;
        }

        .table-dark-custom tbody tr:hover {
            background: rgba(79,70,229,0.06);
        }

        /* ═══════════════════ BADGES ═══════════════════ */
        .badge-soft-success { background: rgba(16,185,129,0.15); color: #34d399; }
        .badge-soft-danger  { background: rgba(239,68,68,0.15);  color: #f87171; }
        .badge-soft-warning { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .badge-soft-info    { background: rgba(6,182,212,0.15);  color: #22d3ee; }
        .badge-soft-primary { background: rgba(79,70,229,0.15);  color: #818cf8; }

        /* ═══════════════════ FORMS ═══════════════════ */
        .form-control-dark,
        .form-select-dark {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            color: #e2e8f0;
            border-radius: 10px;
        }

        .form-control-dark:focus,
        .form-select-dark:focus {
            background: var(--bg-surface);
            border-color: var(--primary);
            color: #e2e8f0;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.2);
        }

        .form-control-dark::placeholder { color: #64748b; }

        .form-label { font-size: 0.85rem; font-weight: 500; color: #cbd5e1; }

        /* ═══════════════════ BUTTONS ═══════════════════ */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: #fff;
            border-radius: 10px;
            padding: 0.55rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--primary-dark), #2e27a0);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79,70,229,0.35);
            color: #fff;
        }

        /* ═══════════════════ ALERTS ═══════════════════ */
        .alert-dark-success {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.3);
            color: #34d399;
            border-radius: 10px;
        }

        .alert-dark-danger {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            color: #f87171;
            border-radius: 10px;
        }

        /* ═══════════════════ STOCK BADGES ═══════════════════ */
        .stock-good-high   { color: #34d399; font-weight: 600; }
        .stock-good-mid    { color: #fbbf24; font-weight: 600; }
        .stock-good-low    { color: #f87171; font-weight: 600; }
        .stock-damaged     { color: #f87171; }

        /* ═══════════════════ MOBILE TOGGLE ═══════════════════ */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #e2e8f0;
            font-size: 1.25rem;
            padding: 0.25rem;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1039;
        }

        /* ═══════════════════ PAGINATION ═══════════════════ */
        .pagination .page-link {
            background: var(--bg-card);
            border-color: var(--border-color);
            color: #94a3b8;
            border-radius: 8px !important;
            margin: 0 2px;
        }

        .pagination .page-link:hover {
            background: var(--bg-surface);
            color: var(--primary-light);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        /* ═══════════════════ SCROLLBAR ═══════════════════ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* ═══════════════════ RESPONSIVE ═══════════════════ */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0;
            }
            .sidebar-toggle { display: block; }
            .sidebar-overlay.show { display: block; }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ═══════════════════ SIDEBAR ═══════════════════ --}}
    <aside id="sidebar">
        <div class="sidebar-brand d-flex align-items-center gap-3">
            <div class="brand-icon">
                <i class="bi bi-flask"></i>
            </div>
            <div>
                <div class="brand-text">Inventaris Lab</div>
                <div class="brand-sub">Sistem Manajemen Aset</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Menu Utama</div>

            <a href="{{ route('dashboard') }}"
               class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-title">Manajemen</div>

            <a href="{{ route('assets.index') }}"
               class="nav-link-item {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-box-seam"></i></span>
                <span>Master Aset</span>
            </a>

            <a href="{{ route('incoming.index') }}"
               class="nav-link-item {{ request()->routeIs('incoming.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-arrow-down-circle"></i></span>
                <span>Barang Masuk</span>
            </a>

            <a href="{{ route('damage.index') }}"
               class="nav-link-item {{ request()->routeIs('damage.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-exclamation-triangle"></i></span>
                <span>Laporan Rusak/Hilang</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div style="min-width: 0;">
                        <div class="fw-600" style="font-size: 0.8rem; color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ auth()->user()->name ?? 'User' }}
                        </div>
                        <div style="font-size: 0.7rem; color: var(--text-muted);">
                            {{ auth()->user()->role === 'kepala_lab' ? 'Kepala Lab' : 'Laboran' }}
                        </div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm w-100 d-flex align-items-center justify-content-center gap-2"
                            style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #f87171; border-radius: 8px; font-size: 0.78rem; padding: 0.4rem;">
                        <i class="bi bi-box-arrow-left"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Overlay untuk mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- ═══════════════════ MAIN CONTENT ═══════════════════ --}}
    <div id="main-content">

        {{-- Topbar --}}
        <div class="topbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                    <div class="topbar-sub">@yield('page-sub', date('l, d F Y'))</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge" style="background: rgba(16,185,129,0.15); color: #34d399; border-radius: 8px; padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Online
                </span>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="mx-3 mt-3">
                <div class="alert-dark-success alert d-flex align-items-center gap-2 py-2 px-3" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-3 mt-3">
                <div class="alert-dark-danger alert d-flex align-items-center gap-2 py-2 px-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                </div>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="page-content">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="text-center py-3" style="border-top: 1px solid var(--border-color); font-size: 0.78rem; color: var(--text-muted);">
            &copy; {{ date('Y') }} Sistem Inventaris Laboratorium &mdash; Dibangun dengan Laravel &amp; Bootstrap 5
        </footer>
    </div>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
