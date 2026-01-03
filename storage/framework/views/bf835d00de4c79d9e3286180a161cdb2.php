

<?php $__env->startSection('title', 'Daftar Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Daftar Aset</h1>
            <p class="text-sm text-gray-500">
                Kelola data aset inventaris masjid.
            </p>
        </div>

        <a href="<?php echo e(route('inventaris.aset.create')); ?>"
           class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium shadow-sm hover:bg-emerald-700">
            <i class="fa-solid fa-plus mr-2 text-xs"></i>
            Tambah Aset Baru
        </a>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        
        <div class="px-4 py-3 border-b border-gray-100">
            <form method="GET"
                  action="<?php echo e(route('inventaris.aset.index')); ?>"
                  class="flex flex-col md:flex-row gap-3 md:items-center">

                
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="<?php echo e(request('search')); ?>"
                        placeholder="Cari nama barang..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    >
                </div>

                
                <select name="kategori"
                        class="w-full md:w-44 text-sm border border-gray-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Kategori</option>
                    <?php if(isset($kategoriOptions)): ?>
                        <?php $__currentLoopData = $kategoriOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kategori); ?>" <?php if(request('kategori') == $kategori): echo 'selected'; endif; ?>>
                                <?php echo e($kategori); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>

                
                <select name="jenis_aset"
                        class="w-full md:w-44 text-sm border border-gray-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Jenis Aset</option>
                    
                </select>

                
                <select name="kondisi"
                        class="w-full md:w-44 text-sm border border-gray-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Kondisi</option>
                    <option value="baik" <?php if(request('kondisi') == 'baik'): echo 'selected'; endif; ?>>Layak</option>
                    <option value="perlu_perbaikan" <?php if(request('kondisi') == 'perlu_perbaikan'): echo 'selected'; endif; ?>>Perbaikan</option>
                    <option value="rusak" <?php if(request('kondisi') == 'rusak'): echo 'selected'; endif; ?>>Rusak</option>
                </select>
            </form>
        </div>

        
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold text-gray-500">
                        <th class="px-4 py-3 text-left">Foto Barang</th>
                        <th class="px-4 py-3 text-left">Nama Barang</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Jenis Aset</th>
                        <th class="px-4 py-3 text-left">Lokasi</th>
                        <th class="px-4 py-3 text-left">Kondisi</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Umur Barang</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">QR Code</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // Hitung umur dari tanggal_perolehan (kalau ada)
                            $umurTahun = !empty($asset->tanggal_perolehan)
                                ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->diffInYears(now())
                                : null;

                            // Ambil kondisi (kalau kamu nanti sudah join dengan kondisi_barang,
                            // ganti ke $asset->kondisi_terbaru misalnya)
                            $kondisi = strtolower($asset->kondisi ?? $asset->status ?? '-');

                            $badgeClass = match($kondisi) {
                                'baik', 'layak' => 'bg-emerald-100 text-emerald-700',
                                'perlu_perbaikan', 'perbaikan' => 'bg-amber-100 text-amber-700',
                                'rusak' => 'bg-rose-100 text-rose-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        ?>
                        <tr class="hover:bg-gray-50">
                            
                            <td class="px-4 py-3">
                                <div class="h-10 w-16 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] text-gray-400">
                                    Foto
                                </div>
                            </td>

                            
                            <td class="px-4 py-3 text-gray-800 font-medium">
                                <?php echo e($asset->nama_aset); ?>

                            </td>

                            
                            <td class="px-4 py-3 text-gray-700">
                                <?php echo e($asset->kategori ?? '-'); ?>

                            </td>

                            
                            <td class="px-4 py-3 text-gray-700">
                                <?php echo e($asset->jenis_aset ?? '-'); ?>

                            </td>

                            
                            <td class="px-4 py-3 text-gray-700">
                                <?php echo e($asset->lokasi ?? '-'); ?>

                            </td>

                            
                            <td class="px-4 py-3">
                                <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold <?php echo e($badgeClass); ?>">
                                    <?php echo e($kondisi !== '-' ? ucfirst(str_replace('_', ' ', $kondisi)) : '-'); ?>

                                </span>
                            </td>

                            
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                <?php if(!is_null($umurTahun)): ?>
                                    <?php echo e($umurTahun); ?> Tahun
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-center">
                                
                                <a href="#"
                                   class="inline-flex items-center justify-center h-7 w-7 rounded-full border border-gray-200 text-gray-500 hover:bg-gray-100">
                                    <i class="fa-solid fa-qrcode text-xs"></i>
                                </a>
                            </td>

                            
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    <a href="<?php echo e(route('inventaris.aset.show', $asset->aset_id ?? $asset->kode_aset)); ?>"
                                       class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200"
                                       title="Detail">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </a>

                                    
                                    <button type="button"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-amber-50 text-amber-600 cursor-not-allowed"
                                            title="Edit (coming soon)">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>

                                    
                                    <button type="button"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-rose-50 text-rose-600 cursor-not-allowed"
                                            title="Hapus (coming soon)">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada data aset.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="px-4 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-xs text-gray-500">
            <div>
                <?php if($assets->total() > 0): ?>
                    Showing
                    <span class="font-semibold"><?php echo e($assets->firstItem()); ?></span>
                    to
                    <span class="font-semibold"><?php echo e($assets->lastItem()); ?></span>
                    of
                    <span class="font-semibold"><?php echo e($assets->total()); ?></span>
                    entries
                <?php else: ?>
                    Showing 0 entries
                <?php endif; ?>
            </div>
            <div>
                <?php echo e($assets->onEachSide(1)->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Backup\tahun ajaran 4-1 smester 7\ManPro\Manajemen Masjid\manajemen_masjid\resources\views/modules/inventaris/aset/index.blade.php ENDPATH**/ ?>