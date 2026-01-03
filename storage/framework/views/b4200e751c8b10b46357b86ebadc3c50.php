
<?php $__env->startSection('title', 'Tambah Aset Baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 lg:p-10 bg-[#f6f8f7]">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-wrap gap-2 mb-4 text-sm">
            <a class="text-emerald-700 font-medium" href="<?php echo e(route('inventaris.aset.index')); ?>">Daftar Aset</a>
            <span class="text-gray-400 font-medium">/</span>
            <span class="text-gray-800 font-medium">Tambah Aset Baru</span>
        </div>

        
        <div class="flex flex-wrap justify-between gap-3 mb-6">
            <div>
                <h1 class="text-gray-900 text-3xl font-bold tracking-tight">Tambah Aset Baru</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Isi formulir di bawah ini untuk menambahkan aset baru ke dalam inventaris.
                </p>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 bg-white rounded-xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <form action="<?php echo e(route('inventaris.aset.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        <label class="flex flex-col">
                            <span class="text-gray-800 text-sm font-medium pb-2">Nama Barang</span>
                            <input name="nama_aset" placeholder="Contoh: Karpet Sholat"
                                   class="h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 text-sm text-gray-900
                                          focus:outline-0 focus:ring-2 focus:ring-emerald-700/30"/>
                        </label>

                        
                        <label class="flex flex-col">
                            <span class="text-gray-800 text-sm font-medium pb-2">Kategori</span>
                            <select name="kategori"
                                    class="h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 text-sm text-gray-900
                                           focus:outline-0 focus:ring-2 focus:ring-emerald-700/30">
                                <option value="">Pilih Kategori</option>
                                <?php $__currentLoopData = $kategoriOptions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k); ?>"><?php echo e($k); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </label>

                        
                        <div class="flex flex-col">
                            <span class="text-gray-800 text-sm font-medium pb-2">Jenis Aset</span>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4">
                                    <input type="radio" name="jenis_aset" value="bergerak" class="text-emerald-700">
                                    <span class="text-sm text-gray-700">Bergerak</span>
                                </label>
                                <label class="flex items-center gap-2 h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4">
                                    <input type="radio" name="jenis_aset" value="tidak_bergerak" class="text-emerald-700">
                                    <span class="text-sm text-gray-700">Tidak Bergerak</span>
                                </label>
                            </div>
                        </div>

                        
                        <label class="flex flex-col">
                            <span class="text-gray-800 text-sm font-medium pb-2">Kondisi Awal</span>
                            <select name="status"
                                    class="h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 text-sm text-gray-900
                                           focus:outline-0 focus:ring-2 focus:ring-emerald-700/30">
                                <option value="">Pilih Status</option>
                                <option value="aktif">Layak / Aktif</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                                <option value="dibuang">Dibuang</option>
                            </select>
                        </label>

                        
                        <label class="flex flex-col md:col-span-2">
                            <span class="text-gray-800 text-sm font-medium pb-2">Tanggal Pembelian</span>
                            <input type="date" name="tanggal_perolehan"
                                   class="h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 text-sm text-gray-900
                                          focus:outline-0 focus:ring-2 focus:ring-emerald-700/30"/>
                        </label>

                        
                        <label class="flex flex-col md:col-span-2">
                            <span class="text-gray-800 text-sm font-medium pb-2">Lokasi Barang</span>
                            <textarea name="lokasi" rows="3" placeholder="Contoh: Gudang utama, Lantai 2"
                                      class="rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 py-3 text-sm text-gray-900
                                             focus:outline-0 focus:ring-2 focus:ring-emerald-700/30"></textarea>
                        </label>

                        
                        <div class="flex flex-col md:col-span-2">
                            <span class="text-gray-800 text-sm font-medium pb-2">Upload Foto Barang</span>

                            <label class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-gray-300 rounded-lg
                                          cursor-pointer bg-[#f6f8f7] hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                    <div class="h-10 w-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <p class="mt-3 text-sm text-gray-500">
                                        <span class="font-semibold">Klik untuk upload</span> atau seret dan lepas
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">SVG, PNG, JPG atau GIF (MAX. 800×400px)</p>
                                </div>
                                <input type="file" name="foto" class="hidden">
                            </label>


                        </div>
                    </div>

                    
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit"
                                class="h-12 rounded-lg bg-emerald-800 text-white px-6 text-sm font-bold flex items-center gap-2
                                    hover:bg-emerald-800/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-800">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 h-fit">
                <h2 class="text-gray-900 text-xl font-semibold">Kode QR Aset</h2>

                <div class="mt-4 rounded-xl bg-rose-50 border border-rose-100 p-6 flex items-center justify-center">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-center">
                        <i class="fa-solid fa-qrcode text-3xl text-gray-400"></i>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-3">
                    QR code akan dibuat secara otomatis setelah aset disimpan.
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Backup\tahun ajaran 4-1 smester 7\ManPro\Manajemen Masjid\manajemen_masjid\resources\views/modules/inventaris/aset/create.blade.php ENDPATH**/ ?>