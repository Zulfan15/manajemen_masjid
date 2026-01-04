

<?php $__env->startSection('title', 'Dashboard - Manajemen Masjid'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Selamat Datang, <?php echo e($user->name); ?>!
                </h1>
                <p class="text-gray-600 mt-2">Sistem Manajemen Masjid Terpadu</p>
            </div>
            <div>
                <i class="fas fa-mosque text-green-700 text-6xl"></i>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                    <i class="fas fa-shield-alt mr-1"></i>
                    <?php echo e(ucfirst(str_replace('_', ' ', $role->name))); ?>

                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Modul Akses</p>
                    <p class="text-3xl font-bold text-green-700"><?php echo e(count($accessibleModules)); ?></p>
                </div>
                <i class="fas fa-th-large text-4xl text-green-200"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Status Akun</p>
                    <p class="text-lg font-bold text-green-600">
                        <i class="fas fa-check-circle"></i> Aktif
                    </p>
                </div>
                <i class="fas fa-user-check text-4xl text-green-200"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Login Terakhir</p>
                    <p class="text-sm font-semibold text-gray-700">
                        <?php if($user->last_login_at): ?>
                            <?php echo e(\Carbon\Carbon::parse($user->last_login_at)->diffForHumans()); ?>

                        <?php else: ?>
                            Pertama kali
                        <?php endif; ?>
                    </p>
                </div>
                <i class="fas fa-clock text-4xl text-green-200"></i>
            </div>
        </div>
    </div>

    <!-- Module Navigation -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-th mr-2"></i>Modul yang Dapat Diakses
        </h2>

        <?php if(count($navigation) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php $__currentLoopData = $navigation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($item['url']); ?>" 
                       class="block bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-lg p-6 transition shadow-sm hover:shadow-md">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-800"><?php echo e($item['label']); ?></h3>
                            <?php if($item['can_edit']): ?>
                                <span class="text-green-600">
                                    <i class="fas fa-edit text-xl"></i>
                                </span>
                            <?php else: ?>
                                <span class="text-blue-600">
                                    <i class="fas fa-eye text-xl"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-600">
                            <?php if($item['can_edit']): ?>
                                Anda memiliki akses penuh untuk mengelola modul ini
                            <?php else: ?>
                                Anda dapat melihat data pada modul ini
                            <?php endif; ?>
                        </p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-inbox text-6xl mb-4"></i>
                <p>Anda belum memiliki akses ke modul apapun</p>
                <p class="text-sm mt-2">Hubungi administrator untuk mendapatkan akses</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <?php if($user->isSuperAdmin()): ?>
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
                <h3 class="text-lg font-bold text-blue-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Super Admin
                </h3>
                <p class="text-blue-700 text-sm">
                    Sebagai Super Admin, Anda memiliki akses VIEW (baca) ke semua modul. 
                    Anda TIDAK dapat mengubah atau menghapus data, hanya dapat melihat dan memantau aktivitas sistem.
                </p>
                <a href="<?php echo e(route('activity-logs.index')); ?>" 
                   class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    <i class="fas fa-history mr-2"></i>Lihat Log Aktivitas
                </a>
            </div>
        <?php endif; ?>

        <?php $__currentLoopData = ['jamaah', 'keuangan', 'kegiatan', 'zis', 'kurban', 'inventaris', 'takmir', 'informasi', 'laporan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($user->hasRole("admin_{$module}")): ?>
                <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-green-800 mb-2">
                        <i class="fas fa-user-shield mr-2"></i>Admin <?php echo e(ucfirst($module)); ?>

                    </h3>
                    <p class="text-green-700 text-sm mb-4">
                        Anda adalah administrator modul <?php echo e($module); ?>. Anda dapat mengelola data dan mempromosikan jamaah menjadi pengurus.
                    </p>
                    <a href="<?php echo e(route('users.promote.show', $module)); ?>" 
                       class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        <i class="fas fa-user-plus mr-2"></i>Kelola Pengurus
                    </a>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\!KULIAH\!SEM7\menpro\manajemen_masjid\resources\views/dashboard/index.blade.php ENDPATH**/ ?>