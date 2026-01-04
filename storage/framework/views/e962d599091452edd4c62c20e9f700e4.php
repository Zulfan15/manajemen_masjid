
<?php $__env->startSection('title', 'Manajemen Takmir/Pengurus'); ?>
<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-user-tie text-green-600 mr-2"></i>Manajemen Takmir/Pengurus
                </h1>
                <p class="text-gray-600 mt-2">Kelola data takmir dan pengurus masjid</p>
            </div>
            <div class="flex gap-2">
                <?php
                    $pemilihanAktif = \App\Models\Pemilihan::where('status', 'aktif')
                        ->where('tanggal_mulai', '<=', now())
                        ->where('tanggal_selesai', '>=', now())
                        ->first();
                ?>
                <?php if($pemilihanAktif): ?>
                    <a href="<?php echo e(route('takmir.pemilihan.vote', $pemilihanAktif->id)); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center animate-pulse">
                        <i class="fas fa-vote-yea mr-2"></i>Pemilihan Aktif!
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('takmir.create')): ?>
                    <a href="<?php echo e(route('takmir.create')); ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Pengurus
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p><?php echo e(session('success')); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <p><?php echo e(session('error')); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filter & Search -->
        <div class="mb-6">
            <form method="GET" action="<?php echo e(route('takmir.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                        <option value="nonaktif" <?php echo e(request('status') == 'nonaktif' ? 'selected' : ''); ?>>Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <select name="jabatan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Jabatan</option>
                        <option value="Ketua (DKM)" <?php echo e(request('jabatan') == 'Ketua (DKM)' ? 'selected' : ''); ?>>Ketua (DKM)</option>
                        <option value="Wakil Ketua" <?php echo e(request('jabatan') == 'Wakil Ketua' ? 'selected' : ''); ?>>Wakil Ketua</option>
                        <option value="Sekretaris" <?php echo e(request('jabatan') == 'Sekretaris' ? 'selected' : ''); ?>>Sekretaris</option>
                        <option value="Bendahara" <?php echo e(request('jabatan') == 'Bendahara' ? 'selected' : ''); ?>>Bendahara</option>
                        <option value="Pengurus" <?php echo e(request('jabatan') == 'Pengurus' ? 'selected' : ''); ?>>Pengurus</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nama, email, atau telepon..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="<?php echo e(route('takmir.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                    <a href="<?php echo e(route('takmir.export')); ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-file-excel mr-2"></i>Export Excel
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 border-b text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $takmir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($takmir->firstItem() + $index); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="<?php echo e($item->foto_url); ?>" alt="<?php echo e($item->nama); ?>" class="h-12 w-12 rounded-full object-cover">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($item->nama); ?></div>
                                <?php if($item->isVerifiedJamaah()): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                        <i class="fas fa-check-circle mr-1"></i>Jamaah Terverifikasi
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?php echo e($item->jabatan); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php if($item->email): ?>
                                    <div><i class="fas fa-envelope text-gray-400 mr-1"></i><?php echo e($item->email); ?></div>
                                <?php endif; ?>
                                <?php if($item->phone): ?>
                                    <div><i class="fas fa-phone text-gray-400 mr-1"></i><?php echo e($item->phone); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($item->periode_mulai->format('d/m/Y')); ?> - <?php echo e($item->periode_akhir->format('d/m/Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($item->status == 'aktif'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="<?php echo e(route('takmir.show', $item->id)); ?>" class="text-blue-600 hover:text-blue-900" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('takmir.update')): ?>
                                        <a href="<?php echo e(route('takmir.edit', $item->id)); ?>" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('takmir.delete')): ?>
                                        <form action="<?php echo e(route('takmir.destroy', $item->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                                <p class="text-lg">Tidak ada data takmir/pengurus</p>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('takmir.create')): ?>
                                    <a href="<?php echo e(route('takmir.create')); ?>" class="text-green-600 hover:text-green-700 mt-2 inline-block">
                                        <i class="fas fa-plus mr-1"></i>Tambah pengurus pertama
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($takmir->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($takmir->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\!KULIAH\!SEM7\menpro\manajemen_masjid\resources\views/modules/takmir/index.blade.php ENDPATH**/ ?>