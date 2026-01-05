
<?php $__env->startSection('title', 'Kegiatan & Acara'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container mx-auto">
        <!-- Alert Messages -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Error!</p>
                <p><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-calendar-alt text-green-700 mr-2"></i>Kegiatan & Acara
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola kegiatan dan acara masjid</p>
                </div>
                <?php if(!auth()->user()->isSuperAdmin()): ?>
                    <a href="<?php echo e(route('kegiatan.create')); ?>"
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Kegiatan
                    </a>
                <?php endif; ?>
            </div>

            <?php if(auth()->user()->isSuperAdmin()): ?>
                <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong></p>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Kegiatan</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['total']); ?></h3>
                        </div>
                        <i class="fas fa-calendar-check text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Mendatang</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['mendatang']); ?></h3>
                        </div>
                        <i class="fas fa-clock text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Berlangsung</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['berlangsung']); ?></h3>
                        </div>
                        <i class="fas fa-play-circle text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-500 to-gray-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Selesai</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['selesai']); ?></h3>
                        </div>
                        <i class="fas fa-check-circle text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <form method="GET" action="<?php echo e(route('kegiatan.index')); ?>" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari kegiatan..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <select name="jenis"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Jenis</option>
                            <option value="rutin" <?php echo e(request('jenis') == 'rutin' ? 'selected' : ''); ?>>Rutin</option>
                            <option value="insidental" <?php echo e(request('jenis') == 'insidental' ? 'selected' : ''); ?>>Insidental
                            </option>
                            <option value="event_khusus" <?php echo e(request('jenis') == 'event_khusus' ? 'selected' : ''); ?>>Event
                                Khusus</option>
                        </select>
                    </div>
                    <div>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="direncanakan" <?php echo e(request('status') == 'direncanakan' ? 'selected' : ''); ?>>
                                Direncanakan</option>
                            <option value="berlangsung" <?php echo e(request('status') == 'berlangsung' ? 'selected' : ''); ?>>
                                Berlangsung</option>
                            <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                            <option value="dibatalkan" <?php echo e(request('status') == 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan
                            </option>
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Kegiatan List -->
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $kegiatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="px-2 py-1 <?php echo e($kegiatan->getStatusBadgeClass()); ?> text-xs rounded">
                                        <?php echo e(ucfirst($kegiatan->status)); ?>

                                    </span>
                                    <span class="px-2 py-1 <?php echo e($kegiatan->getJenisBadgeClass()); ?> text-xs rounded">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $kegiatan->jenis_kegiatan))); ?>

                                    </span>
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i><?php echo e($kegiatan->tanggal_mulai->format('d M Y')); ?>

                                    </span>
                                    <span class="text-sm text-gray-500">
                                        <i
                                            class="fas fa-clock mr-1"></i><?php echo e(date('H:i', strtotime($kegiatan->waktu_mulai))); ?>

                                    </span>
                                    <?php if($kegiatan->kuota_peserta): ?>
                                        <span class="text-sm text-gray-500">
                                            <i
                                                class="fas fa-users mr-1"></i><?php echo e($kegiatan->jumlah_peserta); ?>/<?php echo e($kegiatan->kuota_peserta); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(route('kegiatan.show', $kegiatan->id)); ?>"
                                    class="text-lg font-semibold text-gray-800 hover:text-green-700">
                                    <i class="fas <?php echo e($kegiatan->getKategoriIcon()); ?> text-green-700 mr-1"></i>
                                    <?php echo e($kegiatan->nama_kegiatan); ?>

                                </a>
                                <p class="text-gray-600 mt-1 line-clamp-2"><?php echo e($kegiatan->deskripsi); ?></p>
                                <div class="flex items-center gap-4 text-sm text-gray-500 mt-2">
                                    <span><i class="fas fa-map-marker-alt mr-1"></i><?php echo e($kegiatan->lokasi); ?></span>
                                    <?php if($kegiatan->pic): ?>
                                        <span><i class="fas fa-user mr-1"></i><?php echo e($kegiatan->pic); ?></span>
                                    <?php endif; ?>
                                    <?php if($kegiatan->creator): ?>
                                        <span><i class="fas fa-user-edit mr-1"></i><?php echo e($kegiatan->creator->name); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <a href="<?php echo e(route('kegiatan.show', $kegiatan->id)); ?>"
                                    class="text-blue-600 hover:text-blue-800 p-2" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if(!auth()->user()->isSuperAdmin()): ?>
                                    <a href="<?php echo e(route('kegiatan.edit', $kegiatan->id)); ?>"
                                        class="text-green-600 hover:text-green-800 p-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('kegiatan.destroy', $kegiatan->id)); ?>" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-2" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-16 text-gray-500">
                        <i class="fas fa-calendar-alt text-6xl mb-4 text-gray-300"></i>
                        <h3 class="text-xl font-semibold mb-2">Belum Ada Kegiatan</h3>
                        <p class="mb-4">Mulai tambah kegiatan untuk masjid</p>
                        <?php if(!auth()->user()->isSuperAdmin()): ?>
                            <a href="<?php echo e(route('kegiatan.create')); ?>"
                                class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition inline-block">
                                <i class="fas fa-plus mr-2"></i>Tambah Kegiatan
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if($kegiatans->hasPages()): ?>
                <div class="mt-6">
                    <?php echo e($kegiatans->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Backup\tahun ajaran 4-1 smester 7\ManPro\Manajemen Masjid\manajemen_masjid\resources\views/modules/kegiatan/index.blade.php ENDPATH**/ ?>