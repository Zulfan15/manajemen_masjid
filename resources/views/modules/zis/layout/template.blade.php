<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen ZIS - @yield('title')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #059669;
            --primary-light: #d1fae5;
            --dark-text: #1e293b;
            --muted-text: #64748b;
            --bg-body: #f8fafc;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--dark-text);
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 101vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            height: 80px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            background: linear-gradient(135deg, #059669 0%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .nav-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: 700;
            padding: 24px 24px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            color: var(--muted-text);
            padding: 12px 24px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-size: 0.95rem;
        }

        .nav-link i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 12px;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background-color: #f0fdf4;
        }

        .nav-link.active {
            color: var(--primary-color);
            background-color: #ecfdf5;
            border-left-color: var(--primary-color);
            font-weight: 600;
        }
        
        .nav-link.active i {
            transform: scale(1.1);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: 80px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .page-content {
            padding: 32px;
            flex: 1;
        }

        .card {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02); /* Soft shadow */
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 20px 24px;
            font-weight: 600;
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 16px;
        }
        
        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        
        .btn-primary, .btn-success {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover, .btn-success:hover {
            background-color: #047857;
            border-color: #047857;
            transform: translateY(-1px);
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        @media (max-width: 992px) {
            .sidebar { margin-left: -280px; }
            .sidebar.active { margin-left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.1); }
            .main-content { margin-left: 0; }
            .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); z-index: 999; }
            .overlay.active { display: block; }
        }
    </style>
</head>
<body>

    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-hand-holding-heart me-3"></i>Manajemen ZIS
        </div>

        <div class="flex-grow-1 overflow-auto py-2">
            
            <div class="nav-section-title">Menu Utama</div>
            <a class="nav-link {{ request()->is('zis/dashboard') ? 'active' : '' }}" href="{{ route('zis.dashboard') }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <div class="nav-section-title">Master Data</div>
            <a class="nav-link {{ request()->is('zis/muzakki*') ? 'active' : '' }}" href="{{ route('zis.muzakki.index') }}">
                <i class="fas fa-user-check"></i> Data Muzakki
            </a>
            <a class="nav-link {{ request()->is('zis/mustahiq*') ? 'active' : '' }}" href="{{ route('zis.mustahiq.index') }}">
                <i class="fas fa-user-check"></i> Data Mustahiq
            </a>

            <div class="nav-section-title">Keuangan</div>
            <a class="nav-link {{ request()->is('zis/transaksi*') ? 'active' : '' }}" href="{{ route('zis.transaksi.index') }}">
                <i class="fas fa-arrow-down"></i> Pemasukan ZIS
            </a>
            <a class="nav-link {{ request()->is('zis/penyaluran*') ? 'active' : '' }}" href="{{ route('zis.penyaluran.index') }}">
                <i class="fas fa-paper-plane"></i> Penyaluran Dana
            </a>
            <a class="nav-link {{ request()->is('zis/laporan*') ? 'active' : '' }}" href="{{ route('zis.laporan.index') }}">
                <i class="fas fa-file-invoice-dollar"></i> Laporan
            </a>

            @if(auth('zis')->check() && auth('zis')->user()->role === 'admin')
            <div class="nav-section-title">Admin Area</div>
            <a class="nav-link {{ request()->is('zis/user*') ? 'active' : '' }}" href="{{ route('zis.user.index') }}">
                <i class="fas fa-users-cog"></i> Kelola Pengguna
            </a>
            @endif

        </div>

        <div class="p-3 border-top">
            <form action="{{ route('zis.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light text-danger w-100 fw-bold d-flex align-items-center justify-content-center">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-block text-end">
                    <div class="fw-bold small text-dark">{{ auth('zis')->user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ strtoupper(auth('zis')->user()->role) }}</div>
                </div>
                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px; background: linear-gradient(135deg, #059669, #34d399);">
                    {{ substr(auth('zis')->user()->name, 0, 1) }}
                </div>
            </div>
        </div>

        <!-- CONTENT BODY -->
        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>
</body>
</html>

