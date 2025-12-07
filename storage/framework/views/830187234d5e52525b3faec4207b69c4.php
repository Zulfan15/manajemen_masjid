
<?php $__env->startSection('title', 'Detail Pengumuman'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <a href="<?php echo e(route('kegiatan.pengumuman.index')); ?>" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-bullhorn text-green-700 mr-2"></i><?php echo e($pengumuman->judul); ?>

                    </h1>
                    <div class="flex items-center gap-3 mt-2 text-sm text-gray-600">
                        <span><i class="fas fa-user mr-1"></i><?php echo e($pengumuman->creator->name); ?></span>
                        <span><i class="fas fa-calendar mr-1"></i><?php echo e($pengumuman->created_at->format('d M Y H:i')); ?></span>
                        <span><i class="fas fa-eye mr-1"></i><?php echo e($pengumuman->views); ?> views</span>
                    </div>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('kegiatan.update')): ?>
                    <a href="<?php echo e(route('kegiatan.pengumuman.edit', $pengumuman)); ?>"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                <?php endif; ?>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <!-- Badges -->
                <div class="flex items-center gap-2 mb-6">
                    <span class="<?php echo e($pengumuman->getStatusBadgeClass()); ?> px-3 py-1 rounded-full text-sm">
                        <?php echo e($pengumuman->isAktif() ? 'Aktif' : 'Tidak Aktif'); ?>

                    </span>
                    <span class="<?php echo e($pengumuman->getPrioritasBadgeClass()); ?> px-3 py-1 rounded-full text-sm">
                        <i class="fas fa-flag mr-1"></i><?php echo e(ucfirst($pengumuman->prioritas)); ?>

                    </span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                        <i class="fas <?php echo e($pengumuman->getKategoriIcon()); ?> mr-1"></i><?php echo e(ucfirst($pengumuman->kategori)); ?>

                    </span>
                </div>

                <!-- Info Box -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-600 mb-1">Tanggal Mulai</p>
                        <p class="font-semibold text-blue-900"><?php echo e($pengumuman->tanggal_mulai->format('d F Y')); ?></p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-600 mb-1">Tanggal Berakhir</p>
                        <p class="font-semibold text-blue-900">
                            <?php echo e($pengumuman->tanggal_berakhir ? $pengumuman->tanggal_berakhir->format('d F Y') : 'Tidak ditentukan'); ?>

                        </p>
                    </div>
                    <?php if($pengumuman->kegiatan): ?>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-600 mb-1">Terkait Kegiatan</p>
                            <a href="<?php echo e(route('kegiatan.show', $pengumuman->kegiatan_id)); ?>"
                                class="font-semibold text-green-900 hover:underline">
                                <?php echo e($pengumuman->kegiatan->nama); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <div class="prose max-w-none">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Isi Pengumuman</h3>
                    <div class="text-gray-700 whitespace-pre-line"><?php echo e($pengumuman->konten); ?></div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <div>
                            <p>Dibuat oleh: <strong><?php echo e($pengumuman->creator->name); ?></strong></p>
                            <p><?php echo e($pengumuman->created_at->format('d F Y, H:i')); ?> WIB</p>
                        </div>
                        <?php if($pengumuman->updated_at != $pengumuman->created_at): ?>
                            <div class="text-right">
                                <p>Terakhir diupdate: <strong><?php echo e($pengumuman->updater?->name ?? 'System'); ?></strong></p>
                                <p><?php echo e($pengumuman->updated_at->format('d F Y, H:i')); ?> WIB</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Aji Katab\KULIAH\SEMESTER 7\MANAJEMEN PROYEK\manajemen_masjid\resources\views/modules/kegiatan/pengumuman/show.blade.php ENDPATH**/ ?>