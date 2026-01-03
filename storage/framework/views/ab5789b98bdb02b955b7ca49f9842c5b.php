<div class="bg-white shadow rounded-lg">
    <table class="min-w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3">Judul</th>
                <th class="px-4 py-3">Jenis</th>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3"><?php echo e($item->title); ?></td>
                    <td class="px-4 py-3 capitalize"><?php echo e($item->type); ?></td>
                    <td class="px-4 py-3"><?php echo e($item->created_at->format('d M Y')); ?></td>

                    <td class="px-4 py-3 text-center">
                        <a href="<?php echo e(route('informasi.pengumuman.edit', $item->id)); ?>"
                           class="text-blue-600 mx-1"><i class="fas fa-edit"></i></a>

                        <form action="<?php echo e(route('informasi.pengumuman.destroy',$item->id)); ?>"
                              method="POST" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button onclick="return confirm('Hapus?')" 
                                    class="text-red-600 mx-1">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="py-4 text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH C:\Users\ACER\Downloads\Manpro Masjid\resources\views/modules/informasi/partials/table_pengumuman.blade.php ENDPATH**/ ?>