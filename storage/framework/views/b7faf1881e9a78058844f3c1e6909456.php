

<?php $__env->startSection('title', 'Manajemen Kurban'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-sheep text-green-700 mr-2"></i>Manajemen Kurban
                </h1>
                <p class="text-gray-600 mt-2">Kelola data kurban masjid</p>
            </div>
            <?php if(auth()->user()->hasPermission('kurban.create')): ?>
                <a href="<?php echo e(route('kurban.create')); ?>" class="bg-green-700 text-white px-4 py-3 rounded-lg hover:bg-green-800 transition flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Kurban Baru</span>
                </a>
            <?php endif; ?>
        </div>

        <?php if(auth()->user()->isSuperAdmin()): ?>
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mt-4">
                <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong> - Anda hanya dapat melihat data, tidak dapat mengedit.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="<?php echo e(route('kurban.index')); ?>" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">-- Semua Status --</option>
                    <option value="disiapkan" <?php echo e(request('status') === 'disiapkan' ? 'selected' : ''); ?>>Disiapkan</option>
                    <option value="siap_sembelih" <?php echo e(request('status') === 'siap_sembelih' ? 'selected' : ''); ?>>Siap Disembelih</option>
                    <option value="disembelih" <?php echo e(request('status') === 'disembelih' ? 'selected' : ''); ?>>Disembelih</option>
                    <option value="didistribusi" <?php echo e(request('status') === 'didistribusi' ? 'selected' : ''); ?>>Didistribusi</option>
                    <option value="selesai" <?php echo e(request('status') === 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Hewan</label>
                <select name="jenis_hewan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">-- Semua Jenis --</option>
                    <option value="sapi" <?php echo e(request('jenis_hewan') === 'sapi' ? 'selected' : ''); ?>>Sapi</option>
                    <option value="kambing" <?php echo e(request('jenis_hewan') === 'kambing' ? 'selected' : ''); ?>>Kambing</option>
                    <option value="domba" <?php echo e(request('jenis_hewan') === 'domba' ? 'selected' : ''); ?>>Domba</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Hingga Tanggal</label>
                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">&nbsp;</label>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-green-700 text-white px-3 py-2 rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-search mr-1"></i>Filter
                    </button>
                    <a href="<?php echo e(route('kurban.index')); ?>" class="flex-1 bg-gray-400 text-white px-3 py-2 rounded-lg hover:bg-gray-500 transition text-center">
                        <i class="fas fa-redo mr-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Alert Messages -->
    <?php if($message = session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span><?php echo e($message); ?></span>
        </div>
    <?php endif; ?>

    <!-- Data Table -->
    <?php if($kurbans->count() > 0): ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nomor Kurban</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jenis Hewan</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Berat (kg)</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Biaya</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php $__currentLoopData = $kurbans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kurban): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800"><?php echo e($kurban->nomor_kurban); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    <?php echo e(ucfirst($kurban->jenis_hewan)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4"><?php echo e(number_format($kurban->berat_badan, 2)); ?> kg</td>
                            <td class="px-6 py-4">Rp <?php echo e(number_format($kurban->total_biaya, 0, ',', '.')); ?></td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusColors = [
                                        'disiapkan' => 'bg-blue-100 text-blue-800',
                                        'siap_sembelih' => 'bg-yellow-100 text-yellow-800',
                                        'disembelih' => 'bg-purple-100 text-purple-800',
                                        'didistribusi' => 'bg-orange-100 text-orange-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                    ];
                                    $statusLabel = [
                                        'disiapkan' => 'Disiapkan',
                                        'siap_sembelih' => 'Siap Disembelih',
                                        'disembelih' => 'Disembelih',
                                        'didistribusi' => 'Didistribusi',
                                        'selesai' => 'Selesai',
                                    ];
                                ?>
                                <span class="px-3 py-1 <?php echo e($statusColors[$kurban->status] ?? 'bg-gray-100 text-gray-800'); ?> rounded-full text-sm font-medium">
                                    <?php echo e($statusLabel[$kurban->status] ?? $kurban->status); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($kurban->tanggal_persiapan->format('d M Y')); ?></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="<?php echo e(route('kurban.show', $kurban)); ?>" class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if(auth()->user()->hasPermission('kurban.update') && !auth()->user()->isSuperAdmin()): ?>
                                        <a href="<?php echo e(route('kurban.edit', $kurban)); ?>" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->hasPermission('kurban.delete') && !auth()->user()->isSuperAdmin()): ?>
                                        <form method="POST" action="<?php echo e(route('kurban.destroy', $kurban)); ?>" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="bg-white px-6 py-4 border-t">
                <?php echo e($kurbans->links()); ?>

            </div>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-sheep text-6xl mb-4 text-gray-300"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Data Kurban</h3>
            <p class="text-gray-600 mb-4">Tidak ada data kurban yang sesuai dengan filter Anda.</p>
            <?php if(auth()->user()->hasPermission('kurban.create')): ?>
                <a href="<?php echo e(route('kurban.create')); ?>" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 inline-flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Kurban Pertama</span>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\Downloads\Manpro Masjid\resources\views/modules/kurban/index.blade.php ENDPATH**/ ?>