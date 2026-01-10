

<?php $__env->startSection('content'); ?>


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    
    <div class="bg-blue-50 p-4 rounded-xl shadow-sm">
        <p class="text-sm text-blue-600">👥 Total Jamaah Terdaftar</p>
        <p class="text-3xl font-bold text-blue-800">
            <?php echo e($totalJamaah); ?>

        </p>
    </div>

    
    <div class="bg-green-50 p-4 rounded-xl shadow-sm">
        <p class="text-sm text-green-600">🆕 Jamaah Baru Bulan Ini</p>
        <p class="text-3xl font-bold text-green-800">
            <?php echo e($jamaahBaruBulanIni); ?>

        </p>
    </div>

    
    <div class="bg-purple-50 p-4 rounded-xl shadow-sm">
        <p class="text-sm text-purple-600">🤲 Relawan / Pengurus Aktif</p>
        <p class="text-3xl font-bold text-purple-800">
            <?php echo e($totalRelawan); ?>

        </p>
    </div>

    
    <div class="bg-yellow-50 p-4 rounded-xl shadow-sm">
        <p class="text-sm text-yellow-600">📊 Tingkat Partisipasi Jamaah</p>
        <p class="text-3xl font-bold text-yellow-800">
            <?php echo e($tingkatPartisipasi); ?>%
        </p>

        <div class="w-full bg-yellow-200 rounded-full h-2 mt-3">
            <div
                class="bg-yellow-500 h-2 rounded-full transition-all duration-500"
                style="width: <?php echo e($tingkatPartisipasi); ?>%">
            </div>
        </div>
    </div>

</div>

<div class="bg-white shadow rounded p-4">
<table class="w-full">
    <thead class="border-b">
        <tr class="text-left text-gray-500">
            <th class="py-2">Nama</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $jamaahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2 font-medium">
                <a href="<?php echo e(route('jamaah.show', $j->id)); ?>"
                class="text-blue-600 hover:underline">
                    <?php echo e($j->nama_lengkap); ?>

                </a>
            </td>
            <td>
                <?php $__currentLoopData = $j->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">
                        <?php echo e($c->nama); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </td>
            <td>
                <a href="<?php echo e(route('jamaah.role.edit', $j->id)); ?>"
                    class="text-indigo-600 hover:underline">
                    Ubah Role
                </a>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fadhi\Documents\KULIah\SEMESTER 7\Manajemen Proyek\New folder\manajemen_masjid\resources\views/modules/jamaah/index.blade.php ENDPATH**/ ?>