
<?php $__env->startSection('title', 'Laporan Kegiatan'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-file-alt text-green-700 mr-2"></i>Laporan Kegiatan
                    </h1>
                    <p class="text-gray-600 mt-2">Dokumentasi dan laporan kegiatan masjid</p>
                </div>
                <?php if(auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.create')): ?>
                    <a href="<?php echo e(route('kegiatan.laporan.create')); ?>"
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i>Buat Laporan
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
                            <p class="text-sm opacity-90">Total Laporan</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['total']); ?></h3>
                        </div>
                        <i class="fas fa-file-alt text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Bulan Ini</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['bulan_ini']); ?></h3>
                        </div>
                        <i class="fas fa-calendar-check text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Peserta Total</p>
                            <h3 class="text-2xl font-bold"><?php echo e(number_format($stats['total_peserta'])); ?></h3>
                        </div>
                        <i class="fas fa-users text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Kegiatan Aktif</p>
                            <h3 class="text-2xl font-bold"><?php echo e($stats['kegiatan_aktif']); ?></h3>
                        </div>
                        <i class="fas fa-tasks text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <form method="GET" class="mb-6 flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari laporan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <select name="jenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="this.form.submit()">
                    <option value="">Semua Jenis</option>
                    <option value="kajian" <?php echo e(request('jenis') == 'kajian' ? 'selected' : ''); ?>>Kajian</option>
                    <option value="sosial" <?php echo e(request('jenis') == 'sosial' ? 'selected' : ''); ?>>Sosial</option>
                    <option value="pendidikan" <?php echo e(request('jenis') == 'pendidikan' ? 'selected' : ''); ?>>Pendidikan</option>
                    <option value="perayaan" <?php echo e(request('jenis') == 'perayaan' ? 'selected' : ''); ?>>Perayaan</option>
                    <option value="lainnya" <?php echo e(request('jenis') == 'lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                </select>
                <input type="month" name="bulan" value="<?php echo e(request('bulan')); ?>"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="this.form.submit()">
            </form>

            <!-- Laporan List -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kegiatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $laporans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-800"><?php echo e($laporan->nama_kegiatan); ?></p>
                                        <p class="text-sm text-gray-500">
                                            <span class="<?php echo e($laporan->getJenisBadgeClass()); ?> text-xs px-2 py-1 rounded">
                                                <?php echo e(ucfirst($laporan->jenis_kegiatan)); ?>

                                            </span>
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo e($laporan->tanggal_pelaksanaan->format('d M Y')); ?>

                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <i
                                        class="fas fa-users mr-1"></i><?php echo e($laporan->jumlah_hadir); ?>/<?php echo e($laporan->jumlah_peserta); ?>

                                    (<?php echo e(number_format($laporan->getPersentaseKehadiran(), 1)); ?>%)
                                </td>
                                <td class="px-6 py-4">
                                    <span class="<?php echo e($laporan->getStatusBadgeClass()); ?> text-xs px-2 py-1 rounded">
                                        <?php echo e(ucfirst($laporan->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="<?php echo e(route('kegiatan.laporan.show', $laporan)); ?>"
                                            class="text-blue-600 hover:text-blue-800" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('kegiatan.update')): ?>
                                            <a href="<?php echo e(route('kegiatan.laporan.edit', $laporan)); ?>"
                                                class="text-green-600 hover:text-green-800" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?php echo e(route('kegiatan.laporan.download', $laporan)); ?>"
                                                class="text-purple-600 hover:text-purple-800" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('kegiatan.delete')): ?>
                                            <form action="<?php echo e(route('kegiatan.laporan.destroy', $laporan)); ?>" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fas fa-file-alt text-6xl mb-4 text-gray-300"></i>
                                    <h3 class="text-xl font-semibold mb-2">Belum Ada Laporan</h3>
                                    <p class="mb-4">Mulai buat laporan untuk kegiatan yang telah selesai</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($laporans->hasPages()): ?>
                <div class="mt-6">
                    <?php echo e($laporans->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Aji Katab\KULIAH\SEMESTER 7\MANAJEMEN PROYEK\manajemen_masjid\resources\views/modules/kegiatan/laporan/index.blade.php ENDPATH**/ ?>