

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto space-y-6">

    <!-- PROFIL JAMAAH -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-2">
            <?php echo e($jamaah->nama_lengkap); ?>

        </h2>

        <p class="text-gray-600">No HP: <?php echo e($jamaah->no_hp ?? '-'); ?></p>
        <p class="text-gray-600">Alamat: <?php echo e($jamaah->alamat ?? '-'); ?></p>

        <div class="mt-3">
            <?php $__currentLoopData = $jamaah->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">
                    <?php echo e($cat->nama); ?>

                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- GRID DONASI & KEGIATAN -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- RIWAYAT DONASI -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3">Riwayat Donasi</h3>

            <?php if($jamaah->donations->isEmpty()): ?>
                <p class="text-gray-500 text-sm">
                    Belum ada donasi
                </p>
            <?php else: ?>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $jamaah->donations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b">
                            <td><?php echo e($donasi->tanggal); ?></td>
                            <td><?php echo e(ucfirst($donasi->jenis_donasi)); ?></td>
                            <td>Rp <?php echo e(number_format($donasi->jumlah)); ?></td>
                            <td>
                                <span class="px-2 py-1 rounded text-xs
                                    <?php echo e($donasi->status == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'); ?>">
                                    <?php echo e($donasi->status); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- RIWAYAT KEGIATAN -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3">Riwayat Kegiatan</h3>

            <?php if($jamaah->participations->isEmpty()): ?>
                <p class="text-gray-500 text-sm">
                    Belum mengikuti kegiatan
                </p>
            <?php else: ?>
                <ul class="space-y-2">
                    <?php $__currentLoopData = $jamaah->participations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="border-b pb-2">
                        <p class="font-medium">
                            <?php echo e($p->activity->nama_kegiatan); ?>

                        </p>
                        <p class="text-sm text-gray-500">
                            <?php echo e($p->activity->tanggal); ?>

                        </p>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fadhi\Documents\KULIah\SEMESTER 7\Manajemen Proyek\New folder\manajemen_masjid\resources\views/modules/jamaah/show.blade.php ENDPATH**/ ?>