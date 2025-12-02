

<?php $__env->startSection('title', 'Manajemen Informasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-bullhorn text-green-700 mr-2"></i>Kelola Informasi</h1>
            <div class="flex gap-2">
                <a href="<?php echo e(route('public.home')); ?>" target="_blank" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Lihat Web</a>
                <?php if(!auth()->user()->isSuperAdmin()): ?>
                <a href="<?php echo e(route('informasi.create')); ?>" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800"><i class="fas fa-plus mr-1"></i> Tambah</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-3 border-b">Judul</th>
                        <th class="p-3 border-b">Kategori</th>
                        <th class="p-3 border-b">Tanggal</th>
                        <th class="p-3 border-b text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-3 font-medium"><?php echo e($post->title); ?></td>
                        <td class="p-3"><span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded"><?php echo e(ucfirst($post->category)); ?></span></td>
                        <td class="p-3 text-sm text-gray-500"><?php echo e($post->created_at->format('d M Y')); ?></td>
                        <td class="p-3 text-right">
                            <form action="<?php echo e(route('informasi.broadcast', $post->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Kirim email ke jamaah?')">
                                <?php echo csrf_field(); ?> <button class="text-yellow-600 mr-2" title="Broadcast"><i class="fas fa-envelope"></i></button>
                            </form>
                            <?php if(!auth()->user()->isSuperAdmin()): ?>
                            <a href="<?php echo e(route('informasi.edit', $post->id)); ?>" class="text-blue-600 mr-2"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo e(route('informasi.destroy', $post->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?> <button class="text-red-600"><i class="fas fa-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="p-5 text-center text-gray-500">Belum ada data.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Music\Semester 7\menpro\manajemen_masjid\resources\views/modules/informasi/index.blade.php ENDPATH**/ ?>