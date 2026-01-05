<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Manajemen Masjid')</title>
    
    <!-- Bootstrap CSS - WAJIB UNTUK MODAL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Prevent Bootstrap-Tailwind conflict */
        .modal { 
            z-index: 9999 !important; 
        }
        .modal-backdrop { 
            z-index: 9998 !important; 
        }
        
        /* Fix button styles if needed */
        .btn-action {
            border: none !important;
            outline: none !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    @auth
        @include('layouts.navbar')
        
        <div class="flex">
            @include('layouts.sidebar')
            
            <main class="flex-1 p-6 md:ml-64 mt-16">
                @yield('content')
            </main>
        </div>
    @else
        <main>
            @yield('content')
        </main>
    @endauth

    <!-- Bootstrap JS - WAJIB UNTUK MODAL (HARUS SEBELUM Alpine.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
</body>
</html>