<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Manajemen Masjid'); ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
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

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<<<<<<<< Updated upstream:storage/framework/views/326ebe09038c0fd1e9cccdb73ece19c2.php
<?php /**PATH E:\Bayu\Kuliah\Semester 7\Menpro\Kodingan\manajemen_masjid\resources\views/layouts/app.blade.php ENDPATH**/ ?>
========
<?php /**PATH C:\Users\fadhi\Documents\KULIah\SEMESTER 7\Manajemen Proyek\New folder\manajemen_masjid\resources\views/layouts/app.blade.php ENDPATH**/ ?>
>>>>>>>> Stashed changes:storage/framework/views/8d445ccb736045c4d009b0f2959dec99.php
