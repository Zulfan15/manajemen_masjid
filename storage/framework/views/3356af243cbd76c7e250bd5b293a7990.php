<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masjid Al-Ikhlas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow fixed w-full z-50 top-0">
        <div class="container mx-auto px-4 h-16 flex justify-between items-center">
            <div class="flex items-center gap-2 text-green-700 font-bold text-xl">
                <i class="fas fa-mosque"></i> Masjid Al-Ikhlas
            </div>
            <div>
                <a href="#jadwal" class="text-gray-600 hover:text-green-700 mx-3">Jadwal</a>
                <a href="#berita" class="text-gray-600 hover:text-green-700 mx-3">Berita</a>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 ml-3">Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-green-700 font-semibold ml-3">Login Pengurus</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Header & Jadwal -->
    <div class="mt-16 bg-green-800 text-white py-20 text-center">
        <h1 class="text-4xl font-bold mb-4">Selamat Datang di Portal Masjid</h1>
        <p class="text-green-100 mb-10">Pusat informasi dan kegiatan jamaah.</p>
        
        <div id="jadwal" class="container mx-auto px-4">
            <div class="bg-white text-gray-800 rounded-lg shadow-xl p-6 max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-5 gap-4">
                <?php $__currentLoopData = $jadwalSholat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sholat => $waktu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center p-2 rounded <?php echo e($sholat == 'Maghrib' ? 'bg-green-100 border border-green-300' : 'bg-gray-50'); ?>">
                    <div class="text-xs text-gray-500 uppercase"><?php echo e($sholat); ?></div>
                    <div class="text-2xl font-bold"><?php echo e($waktu); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Berita -->
    <div id="berita" class="container mx-auto px-4 py-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-8 border-l-4 border-green-600 pl-4">Informasi Terbaru</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                <div class="h-40 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
                <div class="p-6">
                    <span class="text-xs font-bold text-green-600 uppercase"><?php echo e($post->category); ?></span>
                    <h3 class="text-lg font-bold mt-2 mb-2 text-gray-800 hover:text-green-700">
                        <a href="<?php echo e(route('public.info.show', $post->slug)); ?>"><?php echo e($post->title); ?></a>
                    </h3>
                    <p class="text-gray-600 text-sm line-clamp-3"><?php echo e(\Illuminate\Support\Str::limit(strip_tags($post->content), 100)); ?></p>
                    <a href="<?php echo e(route('public.info.show', $post->slug)); ?>" class="inline-block mt-4 text-green-700 font-semibold text-sm">Baca Selengkapnya &rarr;</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 text-center py-10 bg-white rounded shadow">
                <p class="text-gray-500">Belum ada berita.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\ASUS\Music\Semester 7\menpro\manajemen_masjid\resources\views/modules/informasi/public_landing.blade.php ENDPATH**/ ?>