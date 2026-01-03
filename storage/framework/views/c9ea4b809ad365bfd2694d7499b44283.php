
<?php $__env->startSection('title', 'Detail Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    
    <div class="text-sm text-gray-500 mb-2">
        <span class="text-emerald-700">Aset</span> <span class="mx-1">/</span> <span>Detail Aset</span>
    </div>

    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <h1 class="text-3xl font-semibold text-gray-900">Detail Aset</h1>

        <div class="flex gap-3">
            <button type="button"
                class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50">
                <i class="fa-solid fa-print mr-2 text-xs"></i>
                Cetak QR Code
            </button>

            
            <button type="button"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                <i class="fa-solid fa-pen mr-2 text-xs"></i>
                Edit Aset
            </button>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="aspect-[4/3] rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                
                Foto Barang
            </div>
        </div>

        
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Nama Barang</div>
                <div class="text-lg font-semibold text-gray-900"><?php echo e($asset->nama_aset); ?></div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Kategori</div>
                <div class="text-lg font-semibold text-gray-900"><?php echo e($asset->kategori ?? '-'); ?></div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Jenis Aset</div>
                <div class="text-lg font-semibold text-gray-900">-</div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Tanggal Pembelian</div>
                <div class="text-lg font-semibold text-gray-900">
                    <?php echo e($asset->tanggal_perolehan ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->translatedFormat('d F Y') : '-'); ?>

                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Lokasi</div>
                <div class="text-lg font-semibold text-gray-900"><?php echo e($asset->lokasi ?? '-'); ?></div>
            </div>

            
            <?php
                $kondisi = strtolower($kondisiTerbaru->kondisi ?? '-');
                $badge = match($kondisi) {
                    'baik','layak' => 'bg-emerald-100 text-emerald-700',
                    'perlu_perbaikan','perbaikan' => 'bg-amber-100 text-amber-700',
                    'rusak' => 'bg-rose-100 text-rose-700',
                    default => 'bg-gray-100 text-gray-600',
                };
            ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-2">Kondisi</div>
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold <?php echo e($badge); ?>">
                    <?php echo e($kondisi !== '-' ? ucfirst(str_replace('_',' ',$kondisi)) : '-'); ?>

                </span>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Umur Aset</div>
                <div class="text-lg font-semibold text-gray-900"><?php echo e($umurText); ?></div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col items-center justify-center">
                <div class="h-28 w-28 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-qrcode text-2xl"></i>
                </div>
                <div class="mt-3 text-sm font-medium text-gray-700"><?php echo e($qrCodeText); ?></div>
            </div>
        </div>
    </div>

    
    <div class="mt-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Riwayat Perawatan</h2>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">TANGGAL</th>
                        <th class="px-4 py-3 text-left">KETERANGAN</th>
                        <th class="px-4 py-3 text-left">BIAYA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $riwayatPerawatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <?php echo e($row->tanggal_jadwal ? \Carbon\Carbon::parse($row->tanggal_jadwal)->translatedFormat('d F Y') : '-'); ?>

                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                <?php echo e($row->note ?? $row->jenis_perawatan ?? '-'); ?>

                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                -
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                Belum ada riwayat perawatan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="mt-6">
            <button type="button"
                class="inline-flex items-center px-4 py-2 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 text-sm font-medium cursor-not-allowed">
                <i class="fa-regular fa-trash-can mr-2 text-xs"></i>
                Hapus Aset
            </button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Backup\tahun ajaran 4-1 smester 7\ManPro\Manajemen Masjid\manajemen_masjid\resources\views/modules/inventaris/aset/show.blade.php ENDPATH**/ ?>