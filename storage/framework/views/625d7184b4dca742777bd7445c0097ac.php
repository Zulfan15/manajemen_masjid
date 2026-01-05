
<?php $__env->startSection('title', 'Keuangan Masjid'); ?>
<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-money-bill-wave text-green-700 mr-2"></i>Keuangan Masjid
                </h1>
                <p class="text-gray-600 mt-2">Kelola keuangan dan transaksi masjid</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Total Pengeluaran</p>
                        <h3 class="text-2xl font-bold mt-2">Rp <?php echo e(number_format(\App\Models\Pengeluaran::sum('jumlah'), 0, ',', '.')); ?></h3>
                    </div>
                    <i class="fas fa-arrow-down text-4xl text-green-300"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Transaksi Bulan Ini</p>
                        <h3 class="text-2xl font-bold mt-2"><?php echo e(\App\Models\Pengeluaran::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count()); ?></h3>
                    </div>
                    <i class="fas fa-receipt text-4xl text-blue-300"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Kategori</p>
                        <h3 class="text-2xl font-bold mt-2"><?php echo e(\App\Models\KategoriPengeluaran::count()); ?></h3>
                    </div>
                    <i class="fas fa-tags text-4xl text-purple-300"></i>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <a href="<?php echo e(route('keuangan.pengeluaran.index')); ?>" class="block bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-green-500 hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-4 mr-4">
                        <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Transaksi Pengeluaran</h3>
                        <p class="text-gray-600 text-sm">Kelola semua pengeluaran masjid</p>
                    </div>
                </div>
            </a>
            
            <a href="<?php echo e(route('keuangan.kategori-pengeluaran.index')); ?>" class="block bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-4 mr-4">
                        <i class="fas fa-tags text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Kategori Pengeluaran</h3>
                        <p class="text-gray-600 text-sm">Kelola kategori pengeluaran</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-history mr-2"></i>Transaksi Terbaru
                </h2>
                <a href="<?php echo e(route('keuangan.pengeluaran.index')); ?>" class="text-green-600 hover:text-green-700 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = \App\Models\Pengeluaran::with('kategori')->latest('tanggal')->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-medium"><?php echo e($item->judul_pengeluaran); ?></div>
                                <div class="text-gray-500 text-xs"><?php echo e(\Illuminate\Support\Str::limit($item->deskripsi, 50)); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    <?php echo e($item->kategori->nama_kategori ?? '-'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-red-600">
                                Rp <?php echo e(number_format($item->jumlah, 0, ',', '.')); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada transaksi</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Backup\tahun ajaran 4-1 smester 7\ManPro\Manajemen Masjid\manajemen_masjid\resources\views/modules/keuangan/index.blade.php ENDPATH**/ ?>