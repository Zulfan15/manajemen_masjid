

<?php $__env->startSection('title', 'Inventaris Masjid'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-boxes text-green-700 mr-2"></i>Inventaris Masjid
                </h1>
                <p class="text-gray-600 mt-2">Ringkasan kondisi aset dan aktivitas inventaris masjid.</p>
            </div>

            <?php if(!auth()->user()->isSuperAdmin()): ?>
                <div class="flex gap-3">
                    <a href="#"
                       class="inline-flex items-center bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i> Tambah Aset
                    </a>
                    <a href="#"
                       class="inline-flex items-center bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-user-friends mr-2"></i> Data Petugas
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if(auth()->user()->isSuperAdmin()): ?>
            <div class="mt-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
                <p class="font-semibold"><i class="fas fa-info-circle mr-2"></i>Mode View Only</p>
                <p class="text-sm mt-1">
                    Sebagai <strong>Super Administrator</strong> Anda hanya dapat melihat data inventaris.
                    Aksi tambah/ubah/hapus hanya dapat dilakukan oleh petugas modul inventaris.
                </p>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500 mb-1">Total Aset</p>
            <p class="text-3xl font-bold text-gray-800"><?php echo e(number_format($totalAset ?? 0)); ?></p>
            <p class="text-xs text-green-600 mt-1">+1.2% bulan ini (dummy)</p>
        </div>

        
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500 mb-1">Jadwal Perawatan</p>
            <p class="text-3xl font-bold text-gray-800"><?php echo e(number_format($totalJadwalPerawatan ?? 0)); ?></p>
            <p class="text-xs text-green-600 mt-1">+<?php echo e($totalJadwalPerawatan ?? 0); ?> minggu ini (dummy)</p>
        </div>

        
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500 mb-1">Kondisi Barang</p>
            <p class="text-3xl font-bold text-gray-800"><?php echo e(number_format($totalPerluPerbaikan ?? 0)); ?></p>
            <p class="text-xs text-red-600 mt-1">Perlu perbaikan</p>
        </div>

        
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500 mb-1">Transaksi</p>
            <p class="text-3xl font-bold text-gray-800"><?php echo e(number_format($totalTransaksiBulanIni ?? 0)); ?></p>
            <p class="text-xs text-red-600 mt-1">-2% bulan ini (dummy)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Jumlah Aset per Kategori</h2>

            <?php
                $maxKategori = max(($asetPerKategori ?? collect())->pluck('total')->toArray() ?: [1]);
            ?>

            <?php $__empty_1 = true; $__currentLoopData = ($asetPerKategori ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center mb-3">
                    <span class="w-24 text-sm text-gray-700 capitalize"><?php echo e($row->kategori ?? 'Lainnya'); ?></span>
                    <div class="flex-1 bg-gray-100 h-2 rounded">
                        <div class="h-2 rounded bg-green-600"
                             style="width: <?php echo e(($row->total / $maxKategori) * 100); ?>%"></div>
                    </div>
                    <span class="ml-2 text-sm font-semibold text-gray-800"><?php echo e($row->total); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-sm">Belum ada data aset per kategori.</p>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>

            <?php $__empty_1 = true; $__currentLoopData = ($aktivitasTerbaru ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-start mb-4">
                    <div class="mt-1 mr-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-green-100 text-green-700">
                            <i class="fas fa-plus text-xs"></i>
                        </span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">
                            Aset
                            <span class="font-semibold">
                                <?php echo e(optional($log->aset)->nama_aset ?? '-'); ?>

                            </span>
                            (<?php echo e(ucfirst($log->tipe_transaksi ?? 'transaksi')); ?>)
                        </p>
                        <p class="text-xs text-gray-500">
                            <?php echo e(optional($log->petugas)->name ?? 'Petugas tidak diketahui'); ?> ·
                            <?php echo e(optional($log->created_at)->diffForHumans() ?? '-'); ?>

                        </p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-sm">Belum ada aktivitas transaksi aset.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Aset Terbaru</h2>
                <a href="<?php echo e(route('inventaris.aset.index')); ?>" class="text-success small">Lihat semua</a>
            </div>

            <div class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = ($asetTerbaru ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-800"><?php echo e($aset->nama_aset ?? '-'); ?></p>
                            <p class="text-xs text-gray-500">
                                <?php echo e(ucfirst($aset->kategori ?? 'Lainnya')); ?> ·
                                Kode: <?php echo e($aset->kode_aset ?? '-'); ?>

                            </p>
                        </div>
                        <span class="text-xs text-gray-500">
                            <?php echo e(optional($aset->created_at)->diffForHumans() ?? '-'); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-500 text-sm">Belum ada aset yang tercatat.</p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center h-full min-h-[150px]
                        <?php if(auth()->user()->isSuperAdmin()): ?> opacity-60 cursor-not-allowed <?php endif; ?>">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-3">
                    <i class="fas fa-plus text-green-700"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Tambah Aset</h3>
                <p class="text-xs text-gray-500 mb-3 text-center">
                    Tambahkan aset baru ke dalam sistem inventaris masjid.
                </p>
                <?php if(!auth()->user()->isSuperAdmin()): ?>
                    <a href="#"
                       class="text-xs px-3 py-1 rounded bg-green-700 text-white hover:bg-green-800 transition">
                        Mulai
                    </a>
                <?php else: ?>
                    <span class="text-[10px] text-gray-400">Hanya petugas inventaris yang dapat menambah aset.</span>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center h-full min-h-[150px]">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                    <i class="fas fa-user-friends text-gray-700"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Data Petugas</h3>
                <p class="text-xs text-gray-500 mb-3 text-center">
                    Lihat daftar petugas yang bertanggung jawab atas inventaris masjid.
                </p>
                <a href="<?php echo e(route('inventaris.petugas.index')); ?>"
                    class="inline-flex items-center px-3 py-1.5 text-xs rounded bg-gray-800 text-white hover:bg-gray-900">
                        Lihat
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\Downloads\Manpro Masjid\resources\views/modules/inventaris/index.blade.php ENDPATH**/ ?>