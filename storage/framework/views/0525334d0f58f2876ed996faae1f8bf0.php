
<?php $__env->startSection('title', 'Pengumuman Kegiatan'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-bullhorn text-green-700 mr-2"></i>Pengumuman Kegiatan
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola pengumuman untuk kegiatan dan acara masjid</p>
                </div>
                <?php if(auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.create')): ?>
                    <a href="<?php echo e(route('kegiatan.pengumuman.create')); ?>"
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Pengumuman
                    </a>
                <?php endif; ?>
            </div>

            <?php if(session('success')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6">
                    <p class="text-green-700"><i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Pengumuman</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['total']); ?></h3>
                        </div>
                        <i class="fas fa-bullhorn text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Aktif</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['aktif']); ?></h3>
                        </div>
                        <i class="fas fa-check-circle text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Views</p>
                            <h3 class="text-2xl font-bold"><?php echo e(number_format($stats['total_views'])); ?></h3>
                        </div>
                        <i class="fas fa-eye text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Bulan Ini</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['bulan_ini']); ?></h3>
                        </div>
                        <i class="fas fa-calendar-alt text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <form method="GET" class="mb-6 flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari pengumuman..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <select name="kategori"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="kajian" <?php echo e(request('kategori') == 'kajian' ? 'selected' : ''); ?>>Kajian</option>
                    <option value="kegiatan" <?php echo e(request('kategori') == 'kegiatan' ? 'selected' : ''); ?>>Kegiatan</option>
                    <option value="event" <?php echo e(request('kategori') == 'event' ? 'selected' : ''); ?>>Event</option>
                    <option value="umum" <?php echo e(request('kategori') == 'umum' ? 'selected' : ''); ?>>Umum</option>
                </select>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="nonaktif" <?php echo e(request('status') == 'nonaktif' ? 'selected' : ''); ?>>Tidak Aktif</option>
                </select>
            </form>

            <!-- Pengumuman List -->
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $pengumumans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengumuman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="<?php echo e($pengumuman->getStatusBadgeClass()); ?> text-xs px-2 py-1 rounded">
                                        <?php echo e(ucfirst($pengumuman->status)); ?>

                                    </span>
                                    <span class="<?php echo e($pengumuman->getPrioritasBadgeClass()); ?> text-xs px-2 py-1 rounded">
                                        <?php echo $pengumuman->getKategoriIcon(); ?> <?php echo e(ucfirst($pengumuman->kategori)); ?>

                                    </span>
                                    <?php if($pengumuman->prioritas != 'normal'): ?>
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <?php echo e(ucfirst($pengumuman->prioritas)); ?>

                                        </span>
                                    <?php endif; ?>
                                    <span class="text-sm text-gray-500">
                                        <i
                                            class="fas fa-calendar mr-1"></i><?php echo e($pengumuman->tanggal_mulai->format('d M Y')); ?>

                                    </span>
                                </div>
                                <a href="<?php echo e(route('kegiatan.pengumuman.show', $pengumuman)); ?>"
                                    class="text-lg font-semibold text-gray-800 hover:text-green-700 mb-2 block">
                                    <?php echo e($pengumuman->judul); ?>

                                </a>
                                <p class="text-gray-600 mb-3"><?php echo e($pengumuman->getExcerpt()); ?></p>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span><i class="fas fa-eye mr-1"></i><?php echo e($pengumuman->views); ?> views</span>
                                    <span><i class="fas fa-user mr-1"></i><?php echo e($pengumuman->creator->name ?? 'N/A'); ?></span>
                                    <?php if($pengumuman->kegiatan): ?>
                                        <span><i
                                                class="fas fa-link mr-1"></i><?php echo e($pengumuman->kegiatan->nama_kegiatan); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <a href="<?php echo e(route('kegiatan.pengumuman.show', $pengumuman)); ?>"
                                    class="text-green-600 hover:text-green-800 p-2" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if(auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.update')): ?>
                                    <a href="<?php echo e(route('kegiatan.pengumuman.edit', $pengumuman)); ?>"
                                        class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if(auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.delete')): ?>
                                    <form action="<?php echo e(route('kegiatan.pengumuman.destroy', $pengumuman)); ?>" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
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
                        <i class="fas fa-bullhorn text-6xl mb-4 text-gray-300"></i>
                        <h3 class="text-xl font-semibold mb-2">Belum Ada Pengumuman</h3>
                        <p class="mb-4">Mulai buat pengumuman untuk kegiatan masjid</p>
                        <?php if(auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.create')): ?>
                            <a href="<?php echo e(route('kegiatan.pengumuman.create')); ?>"
                                class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition inline-block">
                                <i class="fas fa-plus mr-2"></i>Tambah Pengumuman
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if($pengumumans->hasPages()): ?>
                <div class="mt-6">
                    <?php echo e($pengumumans->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Aji Katab\KULIAH\SEMESTER 7\MANAJEMEN PROYEK\manajemen_masjid\resources\views/modules/kegiatan/pengumuman/index.blade.php ENDPATH**/ ?>