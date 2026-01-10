

<?php $__env->startSection('content'); ?>
<div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">
        Ubah Kategori Jamaah
    </h2>

    <p class="text-gray-600 mb-4">
        <?php echo e($jamaah->nama_lengkap); ?>

    </p>

    <form method="POST" action="<?php echo e(route('jamaah.role.update', $jamaah->id)); ?>">
        <?php echo csrf_field(); ?>

        <!-- ROLE UTAMA -->
        <div class="mb-4">
            <label class="font-semibold block mb-2">Peran Utama</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2">
                    <input type="radio" name="main_role" value="umum" required
                        <?php echo e($jamaah->categories->contains('nama','umum') ? 'checked' : ''); ?>>
                    Umum
                </label>

                <label class="flex items-center gap-2">
                    <input type="radio" name="main_role" value="pengurus"
                        <?php echo e($jamaah->categories->contains('nama','pengurus') ? 'checked' : ''); ?>>
                    Pengurus
                </label>
            </div>
        </div>

        <!-- DONATUR -->
        <div class="mb-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="donatur" value="1"
                    <?php echo e($jamaah->categories->contains('nama','Donatur') ? 'checked' : ''); ?>>
                Aktif sebagai Donatur
            </label>
        </div>

        <!-- ACTION -->
        <div class="flex justify-end gap-3">
            <a href="<?php echo e(route('jamaah.index')); ?>"
               class="px-4 py-2 border rounded">
               Batal
            </a>

            <button class="bg-indigo-600 text-white px-4 py-2 rounded">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fadhi\Documents\KULIah\SEMESTER 7\Manajemen Proyek\New folder\manajemen_masjid\resources\views/modules/jamaah/edit-role.blade.php ENDPATH**/ ?>