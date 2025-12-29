

<?php $__env->startSection('title', 'Sertifikat Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-certificate text-green-700 mr-2"></i>Sertifikat Saya
                </h1>
                <p class="text-gray-600 mt-2">Sertifikat dari kegiatan yang telah Anda ikuti</p>
            </div>
        </div>

        <!-- Sertifikat List -->
        <?php if($sertifikats->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $sertifikats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sertifikat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-2 border-yellow-300 rounded-lg overflow-hidden hover:shadow-lg transition duration-300 bg-gradient-to-br from-yellow-50 to-white">
                        <!-- Certificate Header -->
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4 text-white">
                            <div class="flex items-center justify-between">
                                <i class="fas fa-award text-3xl opacity-75"></i>
                                <span class="text-xs font-semibold bg-white bg-opacity-20 px-3 py-1 rounded-full">
                                    Sertifikat
                                </span>
                            </div>
                        </div>

                        <!-- Certificate Content -->
                        <div class="p-5">
                            <!-- Kegiatan Name -->
                            <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2">
                                <?php echo e($sertifikat->nama_kegiatan); ?>

                            </h3>

                            <!-- Info -->
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar w-5 text-yellow-600"></i>
                                    <span><?php echo e($sertifikat->tanggal_kegiatan->format('d M Y')); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-5 text-yellow-600"></i>
                                    <span class="line-clamp-1"><?php echo e($sertifikat->tempat_kegiatan); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-certificate w-5 text-yellow-600"></i>
                                    <span class="text-xs"><?php echo e($sertifikat->nomor_sertifikat); ?></span>
                                </div>
                            </div>

                            <!-- Badges -->
                            <div class="flex gap-2 mb-4">
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                                <span class="px-3 py-1 <?php echo e($sertifikat->getTemplateBadgeClass()); ?> text-xs rounded-full">
                                    <?php echo e(ucfirst($sertifikat->template)); ?>

                                </span>
                            </div>

                            <!-- Download Button -->
                            <a href="<?php echo e(route('jamaah.sertifikat.download', $sertifikat->id)); ?>" 
                               class="block w-full px-4 py-2.5 bg-yellow-600 text-white text-center rounded-lg hover:bg-yellow-700 transition font-medium">
                                <i class="fas fa-download mr-2"></i>Download Sertifikat
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-certificate text-6xl mb-4 text-gray-300"></i>
                <h3 class="text-xl font-semibold mb-2">Belum ada sertifikat</h3>
                <p class="text-sm mb-4">Sertifikat akan muncul di sini setelah Anda mengikuti kegiatan yang menyediakan sertifikat</p>
                <a href="<?php echo e(route('jamaah.kegiatan.index')); ?>" 
                   class="inline-block px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                    <i class="fas fa-calendar-alt mr-2"></i>Lihat Kegiatan
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Ney\Kuliah\Semester 7\MANAJEMEN PROYEK\manajemen_masjid\resources\views/modules/jamaah/sertifikat/index.blade.php ENDPATH**/ ?>