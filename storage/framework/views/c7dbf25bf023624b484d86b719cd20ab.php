<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Manajemen Masjid'); ?></title>
    
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
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-100">
    <?php if(auth()->guard()->check()): ?>
        <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <div class="flex">
            <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <main class="flex-1 p-6 md:ml-64 mt-16">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    <?php else: ?>
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    <?php endif; ?>

    <!-- Bootstrap JS - WAJIB UNTUK MODAL (HARUS SEBELUM Alpine.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\xampp\htdocs\manajemen_masjid\manajemen_masjid\manajemen_masjid-main\resources\views/layouts/app.blade.php ENDPATH**/ ?>