<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Klinik Welly | @yield('title')</title>

    {{-- Instant Theme Initialization (Zero FOUC) persis seperti Bundling2 --}}
    <script>
        (function() {
            var mode = localStorage.getItem('theme_mode') || localStorage.getItem('theme') || 'light';
            var color = localStorage.getItem('theme_color') || 'ocean';
            if (mode === 'dark') {
                document.documentElement.classList.add('dark-mode');
            } else {
                document.documentElement.classList.remove('dark-mode');
            }
            document.documentElement.setAttribute('data-theme-color', color);
        })();
    </script>

    <link rel="icon" href="{{ asset('img/klinik.jpeg') }}" type="image/jpeg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    
    <!-- AdminLTE & Plugins dari Bundling2 -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Remodal popup -->
    <link href="{{ asset('css/remodal.css') }}" rel="stylesheet">
    <link href="{{ asset('css/remodal-default-theme.css') }}" rel="stylesheet">

    <!-- jQuery First -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <style>
        /* ================= THEME COLOR PALETTES (BUNDLING2 STYLE) ================= */
        :root,
        html[data-theme-color="ocean"], body[data-theme-color="ocean"] {
            --theme-primary: #0284c7;
            --theme-primary-hover: #0369a1;
            --theme-accent: #38bdf8;
            --theme-sidebar-bg: linear-gradient(180deg, #0284c7 0%, #0f172a 100%);
            --theme-gradient: linear-gradient(135deg, #0284c7, #0369a1);
        }

        html[data-theme-color="emerald"], body[data-theme-color="emerald"] {
            --theme-primary: #1d7969;
            --theme-primary-hover: #166255;
            --theme-accent: #eb6e25;
            --theme-sidebar-bg: #1d7969;
            --theme-gradient: linear-gradient(135deg, #1d7969, #166255);
        }

        html[data-theme-color="blue"], body[data-theme-color="blue"] {
            --theme-primary: #1d4ed8;
            --theme-primary-hover: #1e40af;
            --theme-accent: #0284c7;
            --theme-sidebar-bg: #1d4ed8;
            --theme-gradient: linear-gradient(135deg, #1d4ed8, #1e40af);
        }

        html[data-theme-color="purple"], body[data-theme-color="purple"] {
            --theme-primary: #6d28d9;
            --theme-primary-hover: #5b21b6;
            --theme-accent: #a855f7;
            --theme-sidebar-bg: #6d28d9;
            --theme-gradient: linear-gradient(135deg, #6d28d9, #5b21b6);
        }

        html[data-theme-color="crimson"], body[data-theme-color="crimson"] {
            --theme-primary: #991b1b;
            --theme-primary-hover: #7f1d1d;
            --theme-accent: #f43f5e;
            --theme-sidebar-bg: #991b1b;
            --theme-gradient: linear-gradient(135deg, #991b1b, #7f1d1d);
        }

        html[data-theme-color="teal"], body[data-theme-color="teal"] {
            --theme-primary: #0f766e;
            --theme-primary-hover: #115e59;
            --theme-accent: #14b8a6;
            --theme-sidebar-bg: #0f766e;
            --theme-gradient: linear-gradient(135deg, #0f766e, #115e59);
        }

        html[data-theme-color="midnight"], body[data-theme-color="midnight"] {
            --theme-primary: #1e293b;
            --theme-primary-hover: #0f172a;
            --theme-accent: #38bdf8;
            --theme-sidebar-bg: #1e293b;
            --theme-gradient: linear-gradient(135deg, #1e293b, #0f172a);
        }

        /* Accent on buttons & primary elements */
        .btn-primary {
            background-color: var(--theme-primary) !important;
            border-color: var(--theme-primary) !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--theme-primary-hover) !important;
            border-color: var(--theme-primary-hover) !important;
        }
        .text-primary {
            color: var(--theme-primary) !important;
        }
        .badge-primary, .bg-primary {
            background-color: var(--theme-primary) !important;
        }

        /* ================= LIGHT MODE BASE ================= */
        body:not(.dark-mode) .content-wrapper,
        html:not(.dark-mode) .content-wrapper {
            background-color: #f5f8fc !important;
        }

        /* ================= WATERMARK LOGO KLINIK TRANSPARAN (SEMUA MENU KECUALI DASHBOARD) ================= */
        .content-wrapper.with-klinik-watermark {
            position: relative;
        }
        .content-wrapper.with-klinik-watermark::before {
            content: "";
            position: fixed;
            top: 50%;
            left: calc(50% + 125px); /* Tengah area konten (memperhitungkan lebar sidebar 250px) */
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            max-width: 80vw;
            max-height: 80vh;
            background-image: url("{{ asset('img/klinik.jpeg') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;
            opacity: 0.075;
            pointer-events: none;
            z-index: 0;
            filter: grayscale(10%);
        }
        .sidebar-collapse .content-wrapper.with-klinik-watermark::before {
            left: 50%;
        }
        .content-wrapper.with-klinik-watermark > * {
            position: relative;
            z-index: 1;
        }

        body:not(.dark-mode) .main-header,
        html:not(.dark-mode) .main-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04) !important;
        }

        /* ================= SIDEBAR (LIGHT MODE) ================= */
        body:not(.dark-mode) .main-sidebar,
        html:not(.dark-mode) .main-sidebar {
            background: var(--theme-sidebar-bg) !important;
            transition: background 0.3s ease;
        }

        body:not(.dark-mode) .brand-link,
        html:not(.dark-mode) .brand-link {
            background: rgba(15, 23, 42, 0.25) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
            transition: background 0.3s ease;
        }

        body:not(.dark-mode) .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35) !important;
            border-radius: 8px !important;
        }

        /* ================= DARK MODE CORE ================= */
        html.dark-mode,
        body.dark-mode {
            background-color: #18181b !important;
            color: #e4e4e7 !important;
        }

        /* Content Wrapper */
        body.dark-mode .content-wrapper,
        html.dark-mode .content-wrapper {
            background-color: #18181b !important;
        }

        /* Navbar */
        body.dark-mode .main-header,
        html.dark-mode .main-header {
            background-color: #1f1f23 !important;
            border-bottom: 1px solid #27272a !important;
        }

        body.dark-mode .main-header .nav-link,
        body.dark-mode .main-header i {
            color: #e4e4e7 !important;
        }

        /* Sidebar Dark */
        body.dark-mode .main-sidebar,
        html.dark-mode .main-sidebar {
            background-color: #18181b !important;
            border-right: 1px solid #27272a !important;
        }

        body.dark-mode .brand-link,
        html.dark-mode .brand-link {
            background-color: #1f1f23 !important;
            border-bottom: 1px solid #27272a !important;
            color: #e4e4e7 !important;
        }

        body.dark-mode .brand-link img {
            filter: brightness(0.95);
        }

        body.dark-mode .nav-sidebar .nav-link {
            color: #a1a1aa !important;
        }

        body.dark-mode .nav-sidebar .nav-link:hover {
            background-color: #27272a !important;
            color: #ffffff !important;
        }

        body.dark-mode .nav-sidebar .nav-link.active,
        body.dark-mode .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #27272a !important;
            color: var(--theme-accent) !important;
            border-left: 3px solid var(--theme-accent) !important;
        }

        /* Cards */
        body.dark-mode .card,
        html.dark-mode .card {
            background-color: #202024 !important;
            color: #e4e4e7 !important;
            border: 1px solid #2e2e33 !important;
            box-shadow: 0 4px 14px rgba(0,0,0,0.3) !important;
        }

        body.dark-mode .card-header,
        html.dark-mode .card-header {
            background-color: #202024 !important;
            border-bottom: 1px solid #2e2e33 !important;
        }

        body.dark-mode .card-header.bg-white,
        body.dark-mode .bg-white {
            background-color: #202024 !important;
            color: #e4e4e7 !important;
        }

        body.dark-mode .text-dark,
        html.dark-mode .text-dark {
            color: #f4f4f5 !important;
        }

        body.dark-mode .text-muted,
        html.dark-mode .text-muted {
            color: #a1a1aa !important;
        }

        /* Tables */
        body.dark-mode .table,
        html.dark-mode .table {
            background-color: #202024 !important;
            color: #e4e4e7 !important;
        }

        body.dark-mode .table thead th,
        html.dark-mode .table thead th {
            background-color: #27272a !important;
            color: #f4f4f5 !important;
            border-color: #3f3f46 !important;
        }

        body.dark-mode .table td,
        body.dark-mode .table th,
        html.dark-mode .table td,
        html.dark-mode .table th {
            border-color: #2e2e33 !important;
        }

        body.dark-mode .table tbody tr:hover,
        html.dark-mode .table tbody tr:hover {
            background-color: #2a2a30 !important;
        }

        /* Forms */
        body.dark-mode input.form-control,
        body.dark-mode select.form-control,
        body.dark-mode textarea.form-control,
        body.dark-mode input:not([type="checkbox"]):not([type="radio"]),
        body.dark-mode select,
        body.dark-mode textarea {
            background-color: #27272a !important;
            color: #f4f4f5 !important;
            border: 1px solid #3f3f46 !important;
        }

        /* Theme Swatches Grid */
        .theme-dropdown-menu {
            transition: all 0.25s ease;
        }
        .theme-swatches-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            margin-top: 6px;
        }

        .theme-swatch {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            outline: none;
            padding: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .theme-swatch:hover {
            transform: scale(1.15);
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .theme-swatch.active {
            border-color: #ffffff;
            box-shadow: 0 0 0 2px var(--theme-primary), 0 4px 10px rgba(0,0,0,0.35);
            transform: scale(1.1);
        }

        .theme-swatch .swatch-check {
            color: #ffffff;
            font-size: 11px;
            display: none;
        }

        .theme-swatch.active .swatch-check {
            display: block;
        }

        .mode-btn.active {
            background-color: var(--theme-primary) !important;
            color: #ffffff !important;
            border-color: var(--theme-primary) !important;
        }

        .rotate-icon {
            font-size: 1.15rem;
            transition: transform .4s ease, color .3s ease;
        }
        body.dark-mode .rotate-icon {
            transform: rotate(180deg);
            color: #facc15;
        }
        body:not(.dark-mode) .rotate-icon {
            transform: rotate(0deg);
            color: #60a5fa;
        }

        /* Dropdown positioning fix inside responsive table */
        .table-responsive {
            overflow-x: auto;
            overflow-y: visible;
        }
        .table-responsive .dropdown-menu {
            position: absolute !important;
        }

        /* Fix Modal Bootstrap Backdrop agar tidak menutupi modal (Z-Index Fix) */
        .modal-backdrop {
            z-index: 1040 !important;
        }
        .modal {
            z-index: 1050 !important;
        }
            z-index: 1050 !important;
        }

        /* Custom Perbaikan Tampilan Bundling2 & Bootstrap 4 Compat */
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1rem;
            border-radius: .25rem;
        }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: .75rem 1.25rem;
            position: relative;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }
        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1.25rem;
        }
        .gap-1 { gap: 0.25rem !important; }
        .gap-2 { gap: 0.5rem !important; }
        .gap-3 { gap: 1rem !important; }

        /* Icon logo header */
        .clinic-header-icon {
            width: 38px;
            height: 38px;
            background: rgba(2, 132, 199, 0.1);
            color: #0284c7;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* Badge Rekam Medis Elegan */
        .badge-rm {
            background: #e0f2fe;
            color: #0369a1;
            font-family: monospace, 'Courier New', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 3px 7px;
            border-radius: 5px;
            border: 1px solid #bae6fd;
            display: inline-block;
        }
    </style>

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar (Bundling2 Style) -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/') }}" class="nav-link font-weight-bold">Dashboard</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('kunjungan.index') }}" class="nav-link font-weight-bold">Registrasi & Kunjungan</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('masterPasien.index') }}" class="nav-link font-weight-bold">Master Pasien</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto align-items-center">

                <!-- Status Klinik -->
                <li class="nav-item d-none d-md-flex align-items-center mr-2">
                    <span class="badge badge-success px-2 py-1" style="border-radius: 12px; font-size: 11px;">
                        <i class="fas fa-check-circle mr-1"></i> Pelayanan Aktif
                    </span>
                </li>

                <!-- 🎨 DROPDOWN PENGATURAN TEMA & WARNA (BUNDLING2) -->
                <li class="nav-item dropdown mr-1">
                    <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" title="Ganti Tema & Warna Aplikasi" id="themeDropdownBtn" role="button" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
                        <i class="fas fa-palette text-warning" style="font-size: 1.15rem;"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow-lg border-0 theme-dropdown-menu" style="width: 290px; border-radius: 12px; z-index: 1050;">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <h6 class="mb-0 font-weight-bold" style="font-size: 13px;">
                                <i class="fas fa-paint-brush mr-1 text-primary"></i> Pengaturan Tema
                            </h6>
                            <button type="button" class="btn btn-xs btn-outline-secondary" onclick="resetTheme()" title="Reset ke Default" style="font-size: 10px; border-radius: 6px;">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </button>
                        </div>

                        {{-- Mode Tampilan: Terang vs Gelap --}}
                        <div class="mb-3">
                            <label class="text-muted text-uppercase mb-1 font-weight-bold d-block" style="font-size: 10px; letter-spacing: 0.5px;">Mode Tampilan</label>
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button type="button" class="btn btn-outline-secondary font-weight-bold mode-btn" id="btnModeLight" onclick="setThemeMode('light')">
                                    <i class="fas fa-sun text-warning mr-1"></i> Terang
                                </button>
                                <button type="button" class="btn btn-outline-secondary font-weight-bold mode-btn" id="btnModeDark" onclick="setThemeMode('dark')">
                                    <i class="fas fa-moon text-info mr-1"></i> Gelap
                                </button>
                            </div>
                        </div>

                        {{-- Pilihan Warna Aksen --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="text-muted text-uppercase mb-0 font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">Warna Aksen</label>
                                <small id="currentThemeLabel" class="font-weight-bold text-primary" style="font-size: 11px;">Biru Samudra</small>
                            </div>
                            <div class="theme-swatches-grid pt-1">
                                <button type="button" class="theme-swatch" data-theme="ocean" style="background: linear-gradient(135deg, #0284c7, #0f172a);" onclick="setThemeColor('ocean', 'Biru Samudra')" title="Biru Samudra">
                                    <i class="fas fa-check swatch-check"></i>
                                </button>
                                <button type="button" class="theme-swatch" data-theme="emerald" style="background: #1d7969;" onclick="setThemeColor('emerald', 'Hijau Klinik')" title="Hijau Klinik">
                                    <i class="fas fa-check swatch-check"></i>
                                </button>
                                <button type="button" class="theme-swatch" data-theme="blue" style="background: #1d4ed8;" onclick="setThemeColor('blue', 'Biru Bahari')" title="Biru Bahari">
                                    <i class="fas fa-check swatch-check"></i>
                                </button>
                                <button type="button" class="theme-swatch" data-theme="purple" style="background: #6d28d9;" onclick="setThemeColor('purple', 'Ungu Modern')" title="Ungu Modern">
                                    <i class="fas fa-check swatch-check"></i>
                                </button>
                                <button type="button" class="theme-swatch" data-theme="crimson" style="background: #991b1b;" onclick="setThemeColor('crimson', 'Merah Marun')" title="Merah Marun">
                                    <i class="fas fa-check swatch-check"></i>
                                </button>
                                <button type="button" class="theme-swatch" data-theme="teal" style="background: #0f766e;" onclick="setThemeColor('teal', 'Teal Tropis')" title="Teal Tropis">
                                    <i class="fas fa-check swatch-check"></i>
                                </button>
                                <button type="button" class="theme-swatch" data-theme="midnight" style="background: #1e293b;" onclick="setThemeColor('midnight', 'Midnight Slate')" title="Midnight Slate">
                                    <i class="fas fa-check swatch-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Quick Toggle Dark Mode -->
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="toggleTheme(event)" title="Klik Cepat: Mode Terang / Gelap">
                        <i id="themeIcon" class="fas fa-sun rotate-icon"></i>
                    </a>
                </li>

                <!-- User Dropdown (Bundling2 Style) -->
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-primary font-weight-bold" style="width:32px; height:32px;">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <span class="ml-2 d-none d-md-inline font-weight-bold small text-dark">
                            {{ auth()->user()->name ?? 'Petugas' }}
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                        <div class="px-3 py-2 border-bottom">
                            <div class="font-weight-bold text-dark">{{ auth()->user()->name ?? 'Petugas' }}</div>
                            <small class="text-muted">{{ auth()->user()->email ?? '-' }}</small>
                            <span class="badge badge-info ml-1 text-uppercase" style="font-size: 10px;">{{ auth()->user()->role ?? 'admin' }}</span>
                        </div>
                        <a class="dropdown-item py-2" href="{{ route('formPasien') }}">
                            <i class="fas fa-user-plus text-primary mr-2"></i> Pendaftaran Pasien
                        </a>
                        @can('role', ['admin'])
                            <a class="dropdown-item py-2" href="{{ route('addUser') }}">
                                <i class="fas fa-users-cog text-info mr-2"></i> Manajemen Petugas
                            </a>
                        @endcan
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('mesinLogout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger">
                                <i class="fas fa-sign-out-alt mr-2"></i> Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </li>

            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container (Bundling2 Style) -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="brand-link d-flex align-items-center">
                <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo Klinik"
                    class="brand-image img-circle elevation-3" style="width: 34px; height: 34px; object-fit: cover;" />
                <span class="brand-text font-weight-bold ml-2" style="letter-spacing: 0.5px; font-size: 15px;">KLINIK WELLY</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
                    <div class="image">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center elevation-2" style="width:34px; height:34px;">
                            <i class="fas fa-user-nurse"></i>
                        </div>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block font-weight-bold text-truncate" style="max-width: 160px;">
                            {{ auth()->user()->name ?? 'Petugas Klinik' }}
                        </a>
                        <small class="text-success font-weight-bold">● Online</small>
                    </div>
                </div>

                <!-- Sidebar Menu (Khanza / Bundling2 Style) -->
                <nav class="mt-2 sidebar-nav">
                    <ul id="sidebarnav" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        {{-- ================= MENU HOME / DASHBOARD ================= --}}
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard Utama</p>
                            </a>
                        </li>

                        {{-- ================= KELOMPOK 1: MAIN / MASTER DATA ================= --}}
                        <li class="nav-header text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">Main & Data Master</li>
                        <li class="nav-item has-treeview {{ request()->is('master/*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('master/*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cubes text-primary"></i>
                                <p>
                                    Master Data
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('master.dokter.index') }}" class="nav-link {{ request()->is('master/dokter*') ? 'active' : '' }}">
                                        <i class="fas fa-user-md nav-icon text-primary"></i>
                                        <p>Master Dokter (DPJP)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('master.poli.index') }}" class="nav-link {{ request()->is('master/poli*') ? 'active' : '' }}">
                                        <i class="fas fa-clinic-medical nav-icon text-success"></i>
                                        <p>Master Poliklinik / Unit</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('master.jadwal.index') }}" class="nav-link {{ request()->is('master/jadwal*') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-alt nav-icon text-info"></i>
                                        <p>Jadwal Dokter</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('master.penjab.index') }}" class="nav-link {{ request()->is('master/penjab*') ? 'active' : '' }}">
                                        <i class="fas fa-file-contract nav-icon text-warning"></i>
                                        <p>Master Jenis Bayar / PJ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('master.obat.index') }}" class="nav-link {{ request()->is('master/obat*') ? 'active' : '' }}">
                                        <i class="fas fa-pills nav-icon text-danger"></i>
                                        <p>Master Obat & Alkes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('master.tindakan.index') }}" class="nav-link {{ request()->is('master/tindakan*') ? 'active' : '' }}">
                                        <i class="fas fa-hand-holding-medical nav-icon text-primary"></i>
                                        <p>Master Tarif & Tindakan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- ================= KELOMPOK 2: LAYANAN MEDIS ================= --}}
                        <li class="nav-header text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">Layanan Pasien</li>
                        <li class="nav-item has-treeview {{ request()->is('rm-baru*') || request()->is('masterPasien*') || request()->is('rawat-jalan*') || request()->is('kunjungan*') || request()->is('cariTanggal*') || request()->is('registrasi-pasien*') || request()->is('formPasien*') || request()->is('detailPasien*') || request()->is('modul/layanan*') || request()->is('laboratorium*') || request()->is('soap*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('rm-baru*') || request()->is('masterPasien*') || request()->is('rawat-jalan*') || request()->is('kunjungan*') || request()->is('cariTanggal*') || request()->is('registrasi-pasien*') || request()->is('formPasien*') || request()->is('detailPasien*') || request()->is('modul/layanan*') || request()->is('laboratorium*') || request()->is('soap*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hospital-user text-info"></i>
                                <p>
                                    Layanan Klinis
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('masterPasien.create') }}" class="nav-link {{ request()->is('rm-baru*') || request()->is('masterPasien*') ? 'active' : '' }}">
                                        <i class="fas fa-user-plus nav-icon text-primary"></i>
                                        <p>Pendaftaran Pasien (RM)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('rawatjalan.index') }}" class="nav-link {{ request()->is('rawat-jalan*') || request()->is('kunjungan*') || request()->is('cariTanggal*') ? 'active' : '' }}">
                                        <i class="fas fa-wheelchair nav-icon text-info"></i>
                                        <p>Rawat Jalan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('registrasi.pasien') }}" class="nav-link {{ request()->is('registrasi-pasien*') || request()->is('formPasien*') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-check nav-icon text-success"></i>
                                        <p>Registrasi Periksa</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0)" onclick="bukaModalPilihPasien('soap')" class="nav-link {{ request()->is('soap*') ? 'active' : '' }}">
                                        <i class="fas fa-user-md nav-icon text-danger"></i>
                                        <p>Dokter Ralan (SOAP)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0)" onclick="bukaModalPilihPasien('resep')" class="nav-link {{ request()->is('pelayanan/resep*') ? 'active' : '' }}">
                                        <i class="fas fa-prescription nav-icon text-success"></i>
                                        <p>E-Resep Dokter (R/)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0)" onclick="bukaModalPilihPasien('tindakan')" class="nav-link {{ request()->is('pelayanan/tindakan*') ? 'active' : '' }}">
                                        <i class="fas fa-procedures nav-icon text-info"></i>
                                        <p>Tindakan & Rawat Luka</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'layanan', 'modul' => 'igd']) }}" class="nav-link {{ request()->is('modul/layanan/igd*') ? 'active' : '' }}">
                                        <i class="fas fa-ambulance nav-icon text-danger"></i>
                                        <p>IGD</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'layanan', 'modul' => 'dokter-igd']) }}" class="nav-link {{ request()->is('modul/layanan/dokter-igd*') ? 'active' : '' }}">
                                        <i class="fas fa-stethoscope nav-icon text-danger"></i>
                                        <p>Dokter IGD</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'layanan', 'modul' => 'rawat-inap']) }}" class="nav-link {{ request()->is('modul/layanan/rawat-inap*') ? 'active' : '' }}">
                                        <i class="fas fa-hotel nav-icon text-primary"></i>
                                        <p>Rawat Inap</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'layanan', 'modul' => 'dokter-ranap']) }}" class="nav-link {{ request()->is('modul/layanan/dokter-ranap*') ? 'active' : '' }}">
                                        <i class="fas fa-user-md nav-icon text-primary"></i>
                                        <p>Dokter Ranap</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0)" onclick="bukaModalPilihPasien('hasil_lab')" class="nav-link {{ request()->is('laboratorium*') ? 'active' : '' }}">
                                        <i class="fas fa-flask nav-icon text-teal"></i>
                                        <p>Laboratorium</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'layanan', 'modul' => 'radiologi']) }}" class="nav-link {{ request()->is('modul/layanan/radiologi*') ? 'active' : '' }}">
                                        <i class="fas fa-film nav-icon text-info"></i>
                                        <p>Radiologi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'layanan', 'modul' => 'operasi']) }}" class="nav-link {{ request()->is('modul/layanan/operasi*') ? 'active' : '' }}">
                                        <i class="fas fa-procedures nav-icon text-warning"></i>
                                        <p>Operasi (OK / VK)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'layanan', 'modul' => 'oral-diagnostic']) }}" class="nav-link {{ request()->is('modul/layanan/oral-diagnostic*') ? 'active' : '' }}">
                                        <i class="fas fa-tooth nav-icon text-success"></i>
                                        <p>Oral Diagnostic</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'layanan', 'modul' => 'utd']) }}" class="nav-link {{ request()->is('modul/layanan/utd*') ? 'active' : '' }}">
                                        <i class="fas fa-heart nav-icon text-danger"></i>
                                        <p>UTD (Transfusi Darah)</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- ================= KELOMPOK 3: FARMASI & APOTEK ================= --}}
                        <li class="nav-header text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">Farmasi & Obat</li>
                        <li class="nav-item has-treeview {{ request()->is('farmasi*') || request()->is('modul/farmasi*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('farmasi*') || request()->is('modul/farmasi*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-medkit text-success"></i>
                                <p>
                                    Farmasi
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="javascript:void(0)" onclick="bukaModalPilihPasien('farmasi')" class="nav-link {{ request()->is('farmasi/pembelian*') ? 'active' : '' }}">
                                        <i class="fas fa-shopping-cart nav-icon text-success"></i>
                                        <p>Apotek Ralan (Resep)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'farmasi', 'modul' => 'apotek-ranap']) }}" class="nav-link {{ request()->is('modul/farmasi/apotek-ranap*') ? 'active' : '' }}">
                                        <i class="fas fa-capsules nav-icon text-warning"></i>
                                        <p>Apotek Ranap</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('penjualan.bebas.index') }}" class="nav-link {{ request()->is('modul/farmasi/penjualan-bebas*') ? 'active' : '' }}">
                                        <i class="fas fa-cash-register nav-icon text-primary"></i>
                                        <p>Penjualan Obat Bebas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('farmasi.penerimaan.index') }}" class="nav-link {{ request()->is('modul/farmasi/penerimaan*') ? 'active' : '' }}">
                                        <i class="fas fa-truck-loading nav-icon text-warning"></i>
                                        <p>Penerimaan Obat (Faktur)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('farmasi.mutasi.index') }}" class="nav-link {{ request()->is('modul/farmasi/mutasi*') ? 'active' : '' }}">
                                        <i class="fas fa-dolly-flatbed nav-icon text-teal"></i>
                                        <p>Mutasi Obat & BHP</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('master.obat.index') }}" class="nav-link {{ request()->is('master/obat*') ? 'active' : '' }}">
                                        <i class="fas fa-boxes nav-icon text-info"></i>
                                        <p>Data Barang / Stok</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- ================= KELOMPOK 4: KASIR & KEUANGAN ================= --}}
                        <li class="nav-header text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">Kasir & Keuangan</li>
                        <li class="nav-item has-treeview {{ request()->is('pelayanan/billing*') || request()->is('laporan/kasir*') || request()->is('modul/keuangan*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('pelayanan/billing*') || request()->is('laporan/kasir*') || request()->is('modul/keuangan*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-money-bill-wave text-warning"></i>
                                <p>
                                    Keuangan & Kasir
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="javascript:void(0)" onclick="bukaModalPilihPasien('billing')" class="nav-link {{ request()->is('pelayanan/billing*') ? 'active' : '' }}">
                                        <i class="fas fa-file-invoice-dollar nav-icon text-success"></i>
                                        <p>Kasir Rawat Jalan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('laporan.kasir.index') }}" class="nav-link {{ request()->is('laporan/kasir*') ? 'active' : '' }}">
                                        <i class="fas fa-chart-pie nav-icon text-warning"></i>
                                        <p>Rekap Kasir & Pendapatan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'keuangan', 'modul' => 'kasir-ranap']) }}" class="nav-link {{ request()->is('modul/keuangan/kasir-ranap*') ? 'active' : '' }}">
                                        <i class="fas fa-file-invoice nav-icon text-warning"></i>
                                        <p>Kasir Rawat Inap</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'keuangan', 'modul' => 'jasa-medis']) }}" class="nav-link {{ request()->is('modul/keuangan/jasa-medis*') ? 'active' : '' }}">
                                        <i class="fas fa-hand-holding-usd nav-icon text-primary"></i>
                                        <p>Jasa Medis & Perawat</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'keuangan', 'modul' => 'buku-kas']) }}" class="nav-link {{ request()->is('modul/keuangan/buku-kas*') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line nav-icon text-info"></i>
                                        <p>Keuangan & Pembukuan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- ================= KELOMPOK 5: REKAM MEDIK & LAPORAN ================= --}}
                        <li class="nav-header text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">Rekam Medik</li>
                        <li class="nav-item has-treeview {{ request()->is('pelayanan/diagnosa*') || request()->is('pelayanan/resume*') || request()->is('modul/rekammedik*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('pelayanan/diagnosa*') || request()->is('pelayanan/resume*') || request()->is('modul/rekammedik*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-book-medical text-teal"></i>
                                <p>
                                    Rekam Medik
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="javascript:void(0)" onclick="bukaModalPilihPasien('diagnosa')" class="nav-link {{ request()->is('pelayanan/diagnosa*') ? 'active' : '' }}">
                                        <i class="fas fa-diagnoses nav-icon text-danger"></i>
                                        <p>Diagnosa Pasien (ICD-10)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0)" onclick="bukaModalPilihPasien('resume')" class="nav-link {{ request()->is('pelayanan/resume*') ? 'active' : '' }}">
                                        <i class="fas fa-notes-medical nav-icon text-primary"></i>
                                        <p>Resume Medis Ralan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'rekammedik', 'modul' => 'clinical-pathway']) }}" class="nav-link {{ request()->is('modul/rekammedik/clinical-pathway*') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-check nav-icon text-info"></i>
                                        <p>Clinical Pathway</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'rekammedik', 'modul' => 'laporan-rm']) }}" class="nav-link {{ request()->is('modul/rekammedik/laporan-rm*') ? 'active' : '' }}">
                                        <i class="fas fa-chart-pie nav-icon text-warning"></i>
                                        <p>Laporan Kemenkes (RL)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'rekammedik', 'modul' => 'mini-pacs']) }}" class="nav-link {{ request()->is('modul/rekammedik/mini-pacs*') ? 'active' : '' }}">
                                        <i class="fas fa-photo-video nav-icon text-secondary"></i>
                                        <p>Mini PACS (Foto Luka/Rad)</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- ================= KELOMPOK 6: PERSURATAN & MANAJEMEN ================= --}}
                        <li class="nav-header text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">Manajemen & Persuratan</li>
                        <li class="nav-item has-treeview {{ request()->is('surat*') || request()->is('modul/manajemen*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('surat*') || request()->is('modul/manajemen*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope-open-text text-danger"></i>
                                <p>
                                    Surat & Manajemen
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('surat.index') }}" class="nav-link {{ request()->is('surat') ? 'active' : '' }}">
                                        <i class="fas fa-folder-open nav-icon text-primary"></i>
                                        <p>Dashboard Persuratan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('surat.rujukan.index') }}" class="nav-link {{ request()->is('surat/rujukan*') ? 'active' : '' }}">
                                        <i class="fas fa-paper-plane nav-icon text-danger"></i>
                                        <p>Surat Rujukan Pasien</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('surat.askep.index') }}" class="nav-link {{ request()->is('surat/askep*') ? 'active' : '' }}">
                                        <i class="fas fa-file-medical-alt nav-icon text-teal"></i>
                                        <p>Formulir Askep</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('surat.observasi.index') }}" class="nav-link {{ request()->is('surat/observasi*') ? 'active' : '' }}">
                                        <i class="fas fa-notes-medical nav-icon text-info"></i>
                                        <p>Lembar Observasi Khusus</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('surat.bukumerja.index') }}" class="nav-link {{ request()->is('surat/buku-merja*') ? 'active' : '' }}">
                                        <i class="fas fa-book nav-icon text-primary"></i>
                                        <p>Buku Merja Klien</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('surat.puskesmas.index') }}" class="nav-link {{ request()->is('surat/laporan-puskesmas*') ? 'active' : '' }}">
                                        <i class="fas fa-hospital nav-icon text-success"></i>
                                        <p>Laporan ke Puskesmas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('surat.pelimpahan.index') }}" class="nav-link {{ request()->is('surat/pelimpahan*') ? 'active' : '' }}">
                                        <i class="fas fa-file-signature nav-icon text-warning"></i>
                                        <p>Pelimpahan Wewenang</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('surat.ekspedisi.index') }}" class="nav-link {{ request()->is('surat/ekspedisi*') ? 'active' : '' }}">
                                        <i class="fas fa-book-reader nav-icon text-success"></i>
                                        <p>Buku Ekspedisi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'manajemen', 'modul' => 'kepegawaian']) }}" class="nav-link {{ request()->is('modul/manajemen/kepegawaian*') ? 'active' : '' }}">
                                        <i class="fas fa-id-badge nav-icon text-info"></i>
                                        <p>Kepegawaian (SDM)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'manajemen', 'modul' => 'presensi']) }}" class="nav-link {{ request()->is('modul/manajemen/presensi*') ? 'active' : '' }}">
                                        <i class="fas fa-user-clock nav-icon text-warning"></i>
                                        <p>Presensi Pegawai</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'manajemen', 'modul' => 'inventaris']) }}" class="nav-link {{ request()->is('modul/manajemen/inventaris*') ? 'active' : '' }}">
                                        <i class="fas fa-cogs nav-icon text-secondary"></i>
                                        <p>Inventaris & Alat Medis</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'manajemen', 'modul' => 'e-signature']) }}" class="nav-link {{ request()->is('modul/manajemen/e-signature*') ? 'active' : '' }}">
                                        <i class="fas fa-signature nav-icon text-primary"></i>
                                        <p>E-Signature (TTE)</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- ================= KELOMPOK 7: BRIDGING & ANJUNGAN ================= --}}
                        <li class="nav-header text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">Bridging & Antrean</li>
                        <li class="nav-item has-treeview {{ request()->is('modul/bridging*') || request()->is('modul/anjungan*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('modul/bridging*') || request()->is('modul/anjungan*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-network-wired text-primary"></i>
                                <p>
                                    Bridging & Antrian
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'bridging', 'modul' => 'satu-sehat']) }}" class="nav-link {{ request()->is('modul/bridging/satu-sehat*') ? 'active' : '' }}">
                                        <i class="fas fa-heartbeat nav-icon text-danger"></i>
                                        <p>Satu Sehat (Kemenkes)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'bridging', 'modul' => 'bpjs-pcare']) }}" class="nav-link {{ request()->is('modul/bridging/bpjs-pcare*') ? 'active' : '' }}">
                                        <i class="fas fa-shield-alt nav-icon text-success"></i>
                                        <p>BPJS PCare / Antrean</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'bridging', 'modul' => 'bpjs-vclaim']) }}" class="nav-link {{ request()->is('modul/bridging/bpjs-vclaim*') ? 'active' : '' }}">
                                        <i class="fas fa-id-card nav-icon text-info"></i>
                                        <p>BPJS VClaim / E-MR</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'bridging', 'modul' => 'mobile-jkn']) }}" class="nav-link {{ request()->is('modul/bridging/mobile-jkn*') ? 'active' : '' }}">
                                        <i class="fas fa-mobile-alt nav-icon text-primary"></i>
                                        <p>Mobile JKN</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'bridging', 'modul' => 'wa-gateway']) }}" class="nav-link {{ request()->is('modul/bridging/wa-gateway*') ? 'active' : '' }}">
                                        <i class="fab fa-whatsapp nav-icon text-success"></i>
                                        <p>WA Gateway Notifikasi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modul.placeholder', ['kategori' => 'anjungan', 'modul' => 'anjungan-antrian']) }}" class="nav-link {{ request()->is('modul/anjungan/anjungan-antrian*') ? 'active' : '' }}">
                                        <i class="fas fa-tv nav-icon text-warning"></i>
                                        <p>Display TV Antrian</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- ================= MENU LAYANAN KHUSUS ================= --}}
                        <li class="nav-header text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">Program Khusus</li>
                        <li class="nav-item has-treeview {{ request()->is('formPadepokan*') || request()->is('editPeserta*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('formPadepokan*') || request()->is('editPeserta*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clinic-medical text-warning"></i>
                                <p>
                                    Layanan Khusus
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('formPadepokan') }}" class="nav-link {{ request()->is('formPadepokan*') || request()->is('editPeserta*') ? 'active' : '' }}">
                                        <i class="fas fa-graduation-cap nav-icon text-warning"></i>
                                        <p>Padepokan Cettol</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- ================= MENU PENGATURAN & KEAMANAN ================= --}}
                        @can('role', ['admin'])
                            <li class="nav-header text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">Sistem & Keamanan</li>
                            <li class="nav-item has-treeview {{ request()->is('addUser*') || request()->is('editPetugas*') || request()->is('user/permissions*') || request()->is('tracker*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('addUser*') || request()->is('editPetugas*') || request()->is('user/permissions*') || request()->is('tracker*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-shield-alt text-dark"></i>
                                    <p>
                                        Keamanan & Admin
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('addUser') }}" class="nav-link {{ request()->is('addUser*') || request()->is('editPetugas*') || request()->is('user/permissions*') ? 'active' : '' }}">
                                            <i class="fas fa-users-cog nav-icon text-success"></i>
                                            <p>Manajemen Petugas & Akses</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('tracker.index') }}" class="nav-link {{ request()->is('tracker*') ? 'active' : '' }}">
                                            <i class="fas fa-history nav-icon text-warning"></i>
                                            <p>Audit Trail / Tracker</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper {{ request()->routeIs('dashboard') ? '' : 'with-klinik-watermark' }}">
            @hasSection('hide_content_header')
            @else
                <!-- Content Header (Page header) -->
                <div class="content-header py-2">
                    <div class="container-fluid">
                        <div class="row mb-1 align-items-center">
                            <div class="col-sm-6">
                                <h4 class="m-0 font-weight-bold text-dark" style="font-size: 1.25rem;">@yield('title')</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right mb-0" style="background: transparent; font-size: 12px;">
                                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.content-header -->
            @endif

            <!-- Main content -->
            <section class="content pt-2">
                <div class="container-fluid">
                    @yield('konten')
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer (Bundling2 Style) -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                <b>Praktik Mandiri Perawat Luka Welly</b> (Welly Prestikasari, S.Kep., Ners., M.Kep.)
            </div>
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">Klinik Perawat Luka Welly</a>.</strong> All rights reserved.
        </footer>

    </div>
    <!-- ./wrapper -->

    <!-- AdminLTE & Bootstrap JS dari Bundling2 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/sidebarmenu.js') }}" defer></script>

    <!-- DataTables & Remodal -->
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/remodal.js') }}"></script>

    {{-- ================= SYSTEM PENGATURAN TEMA & WARNA (BUNDLING2) ================= --}}
    <script>
    const THEME_NAMES = {
        ocean: 'Biru Samudra (Default)',
        emerald: 'Hijau Klinik (RSBW)',
        blue: 'Biru Bahari',
        purple: 'Ungu Modern',
        crimson: 'Merah Marun',
        teal: 'Teal Tropis',
        midnight: 'Midnight Slate'
    };

    function applyTheme(mode, color) {
        mode = mode || localStorage.getItem('theme_mode') || localStorage.getItem('theme') || 'light';
        color = color || localStorage.getItem('theme_color') || 'ocean';

        const isDark = (mode === 'dark');

        if (isDark) {
            document.documentElement.classList.add('dark-mode');
            if (document.body) document.body.classList.add('dark-mode');
        } else {
            document.documentElement.classList.remove('dark-mode');
            if (document.body) document.body.classList.remove('dark-mode');
        }

        document.documentElement.setAttribute('data-theme-color', color);
        if (document.body) document.body.setAttribute('data-theme-color', color);

        // Update Quick Icon
        const icon = document.getElementById('themeIcon');
        if (icon) {
            icon.classList.remove('fa-sun', 'fa-moon');
            icon.classList.add(isDark ? 'fa-moon' : 'fa-sun');
        }

        // Update Mode Buttons in dropdown
        const btnLight = document.getElementById('btnModeLight');
        const btnDark = document.getElementById('btnModeDark');
        if (btnLight && btnDark) {
            btnLight.classList.toggle('active', !isDark);
            btnDark.classList.toggle('active', isDark);
        }

        // Update Swatches in dropdown
        document.querySelectorAll('.theme-swatch').forEach(el => {
            const swatchTheme = el.getAttribute('data-theme');
            el.classList.toggle('active', swatchTheme === color);
        });

        // Update Color Label
        const label = document.getElementById('currentThemeLabel');
        if (label) {
            label.textContent = THEME_NAMES[color] || 'Hijau Klinik';
        }
    }

    function toggleTheme(e) {
        if (e) e.preventDefault();
        const currentMode = localStorage.getItem('theme_mode') || localStorage.getItem('theme') || 'light';
        const newMode = (currentMode === 'dark') ? 'light' : 'dark';
        setThemeMode(newMode);
    }

    function setThemeMode(mode) {
        localStorage.setItem('theme_mode', mode);
        localStorage.setItem('theme', mode);
        applyTheme(mode, null);
    }

    function setThemeColor(color, labelText) {
        localStorage.setItem('theme_color', color);
        localStorage.setItem('theme_color_customized', 'true');
        applyTheme(null, color);
    }

    function resetTheme() {
        localStorage.setItem('theme_mode', 'light');
        localStorage.setItem('theme', 'light');
        localStorage.setItem('theme_color', 'emerald');
        localStorage.removeItem('theme_color_customized');
        applyTheme('light', 'emerald');
    }

    document.addEventListener('DOMContentLoaded', () => {
        applyTheme();
    });
    </script>

    @yield('scripts')
    @stack('scripts')

    <script>
        $(document).ready(function() {
            if ($.fn.DataTable && $('#zero_config').length) {
                $('#zero_config').DataTable({
                    "language": {
                        "search": "Cari Cepat:",
                        "lengthMenu": "Tampilkan _MENU_ data",
                        "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                        "paginate": {
                            "previous": "Sebelumnya",
                            "next": "Selanjutnya"
                        }
                    }
                });
            }

            // Fix dropdown terpotong di dalam table-responsive
            $('.table-responsive').on('show.bs.dropdown', function () {
                $('.table-responsive').css("overflow", "inherit");
            });

            $('.table-responsive').on('hide.bs.dropdown', function () {
                $('.table-responsive').css("overflow", "auto");
            });
        });

        // Fitur intip password (show/hide password)
        function togglePasswordVisibility(inputId, iconId) {
            var field = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            if (!field) return;

            if (field.type === "password") {
                field.type = "text";
                if (icon) {
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                }
            } else {
                field.type = "password";
                if (icon) {
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        }

        function getCurrentDateTime() {
            var currentDateTime = new Date();
            var year = currentDateTime.getFullYear();
            var month = (currentDateTime.getMonth() + 1).toString().padStart(2, "0");
            var day = currentDateTime.getDate().toString().padStart(2, "0");
            var hours = currentDateTime.getHours().toString().padStart(2, "0");
            var minutes = currentDateTime.getMinutes().toString().padStart(2, "0");
            var dateString = year + "-" + month + "-" + day;
            var timeString = hours + ":" + minutes;
            var dateEl = document.getElementById("date");
            var timeEl = document.getElementById("time");
            if (dateEl) dateEl.value = dateString;
            if (timeEl) timeEl.value = timeString;
        }
        window.onload = getCurrentDateTime;

        // Modal Pilih Pasien untuk Layanan Medis, Lab, Farmasi & Kasir Billing
        var targetAksiPelayanan = 'soap';

        function bukaModalPilihPasien(aksi) {
            targetAksiPelayanan = aksi;
            var judul = 'Pilih Pasien';
            var icon = 'fa-user-check';

            if (aksi === 'soap') {
                judul = 'Pilih Pasien - Input Catatan SOAP';
                icon = 'fa-stethoscope text-danger';
            } else if (aksi === 'resep') {
                judul = 'Pilih Pasien - E-Resep & Terapi Obat';
                icon = 'fa-prescription text-success';
            } else if (aksi === 'tindakan') {
                judul = 'Pilih Pasien - Input Tindakan & Rawat Luka';
                icon = 'fa-procedures text-info';
            } else if (aksi === 'permintaan_lab') {
                judul = 'Pilih Pasien - Form Permintaan Lab';
                icon = 'fa-vial text-warning';
            } else if (aksi === 'hasil_lab') {
                judul = 'Pilih Pasien - Input Hasil Pemeriksaan Lab';
                icon = 'fa-file-medical-alt text-teal';
            } else if (aksi === 'farmasi') {
                judul = 'Pilih Pasien - Pembelian Obat & Balutan';
                icon = 'fa-pills text-success';
            } else if (aksi === 'billing') {
                judul = 'Pilih Pasien - Kasir & Billing Pasien';
                icon = 'fa-cash-register text-success';
            } else if (aksi === 'diagnosa') {
                judul = 'Pilih Pasien - Diagnosa Pasien (ICD-10)';
                icon = 'fa-diagnoses text-danger';
            } else if (aksi === 'resume') {
                judul = 'Pilih Pasien - Resume Medis Rawat Jalan';
                icon = 'fa-notes-medical text-primary';
            }

            $('#modalPilihPasienTitle').html('<i class="fas ' + icon + ' mr-2"></i>' + judul);
            $('#searchModalPasien').val('');
            $('#modalPilihPasien').modal('show');
            loadDaftarPasienPelayanan('');
        }

        function loadDaftarPasienPelayanan(keyword) {
            $('#tbodyModalPasien').html('<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat daftar pasien...</td></tr>');
            
            $.getJSON("{{ route('api.pelayanan.cari-pasien') }}", { q: keyword }, function(data) {
                if (!data || data.length === 0) {
                    $('#tbodyModalPasien').html('<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-info-circle mr-1"></i> Tidak ada data pasien yang cocok.</td></tr>');
                    return;
                }

                var html = '';
                $.each(data, function(i, item) {
                    html += '<tr style="cursor: pointer;" onclick="pilihPasienPelayanan(\'' + item.hashid + '\')">' +
                        '<td class="font-monospace text-primary fw-bold text-nowrap">' + item.no_rawat + '</td>' +
                        '<td class="font-monospace text-success fw-bold text-nowrap">' + item.no_rkm_medis + '</td>' +
                        '<td class="fw-bold">' + item.nama_lengkap + '</td>' +
                        '<td><span class="badge badge-light border">' + item.unit_poli + '</span></td>' +
                        '<td><small class="text-muted">' + item.dokter_dituju + '</small></td>' +
                        '<td class="text-center">' +
                            '<button type="button" class="btn btn-xs btn-primary font-weight-bold px-2 py-1">' +
                                '<i class="fas fa-check-circle mr-1"></i> Pilih' +
                            '</button>' +
                        '</td>' +
                    '</tr>';
                });
                $('#tbodyModalPasien').html(html);
            }).fail(function() {
                $('#tbodyModalPasien').html('<tr><td colspan="6" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Gagal memuat data pasien. Silakan coba lagi.</td></tr>');
            });
        }

        var debounceTimerPasien;
        $('#searchModalPasien').on('keyup input', function() {
            clearTimeout(debounceTimerPasien);
            var val = $(this).val();
            debounceTimerPasien = setTimeout(function() {
                loadDaftarPasienPelayanan(val);
            }, 300);
        });

        function pilihPasienPelayanan(hashid) {
            var url = '';
            if (targetAksiPelayanan === 'soap') {
                url = "{{ url('/soap') }}/" + hashid;
            } else if (targetAksiPelayanan === 'resep') {
                url = "{{ url('/pelayanan/resep') }}/" + hashid;
            } else if (targetAksiPelayanan === 'tindakan') {
                url = "{{ url('/pelayanan/tindakan') }}/" + hashid;
            } else if (targetAksiPelayanan === 'permintaan_lab') {
                url = "{{ url('/laboratorium/permintaan') }}/" + hashid;
            } else if (targetAksiPelayanan === 'hasil_lab') {
                url = "{{ url('/laboratorium/periksa') }}/" + hashid;
            } else if (targetAksiPelayanan === 'farmasi') {
                url = "{{ url('/farmasi/pembelian') }}/" + hashid;
            } else if (targetAksiPelayanan === 'billing') {
                url = "{{ url('/pelayanan/billing') }}/" + hashid;
            } else if (targetAksiPelayanan === 'diagnosa') {
                url = "{{ url('/pelayanan/diagnosa') }}/" + hashid;
            } else if (targetAksiPelayanan === 'resume') {
                url = "{{ url('/pelayanan/resume') }}/" + hashid;
            }

            if (url) {
                $('#modalPilihPasien').modal('hide');
                window.location.href = url;
            }
        }
    </script>

    <!-- MODAL POPUP PILIH PASIEN UNTUK MEDIS, LAB & FARMASI -->
    <div class="modal fade" id="modalPilihPasien" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 99999;">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow border-0" style="border-radius: 8px;">
                <div class="modal-header bg-dark text-white py-2 px-3 align-items-center">
                    <h6 class="modal-title font-weight-bold" id="modalPilihPasienTitle">
                        <i class="fas fa-user-check mr-2"></i> Pilih Pasien
                    </h6>
                    <button type="button" class="close text-white opacity-75" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" id="searchModalPasien" class="form-control border-left-0" placeholder="Ketik No. Rawat, No. RM, atau Nama Pasien untuk mencari..." autocomplete="off">
                        </div>
                    </div>
                    <div class="table-responsive border rounded" style="max-height: 380px; overflow-y: auto;">
                        <table class="table table-sm table-hover table-striped mb-0 text-nowrap" style="font-size: 12px;">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th>No. Rawat</th>
                                    <th>No. RM</th>
                                    <th>Nama Pasien</th>
                                    <th>Poliklinik</th>
                                    <th>Dokter</th>
                                    <th class="text-center" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyModalPasien">
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> Memuat data pasien...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Klik baris pasien atau tombol <strong>Pilih</strong> untuk langsung membuka formulir.</small>
                    <button type="button" class="btn btn-sm btn-secondary font-weight-bold px-3" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
