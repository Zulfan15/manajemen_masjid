
<?php $__env->startSection('title', 'Manajemen Kurban'); ?>
<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-sheep text-green-700 mr-2"></i>Manajemen Kurban
                </h1>
                <p class="text-gray-600 mt-2">Kelola data kurban</p>
            </div>
            <?php if(!auth()->user()->isSuperAdmin()): ?>
                <button class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Data Kurban
                </button>
            <?php endif; ?>
        </div>
        <?php if(auth()->user()->isSuperAdmin()): ?>
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong></p>
            </div>
        <?php endif; ?>
        <div class="text-center py-16 text-gray-500">
            <i class="fas fa-sheep text-6xl mb-4 text-gray-300"></i>
            <h3 class="text-xl font-semibold mb-2">Halaman Navigasi Modul</h3>
            <p>Konten detail modul ini akan dikembangkan oleh tim lain.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Backup\tahun ajaran 4-1 smester 7\ManPro\Manajemen Masjid\manajemen_masjid\resources\views/modules/kurban/index.blade.php ENDPATH**/ ?>