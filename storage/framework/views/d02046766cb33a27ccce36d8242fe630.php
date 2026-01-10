<div class="bg-white shadow rounded-lg">
    <table class="min-w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3">Judul</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Penulis</th>
                <th class="px-4 py-3">Dipublish</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $artikel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3"><?php echo e($item->title); ?></td>
                    <td class="px-4 py-3"><?php echo e($item->category->name); ?></td>
                    <td class="px-4 py-3"><?php echo e($item->author_name); ?></td>
                    <td class="px-4 py-3"><?php echo e($item->published_at->format('d M Y')); ?></td>

                    <td class="px-4 py-3 text-center">
                        <a href="<?php echo e(route('informasi.artikel.edit', $item->id)); ?>"
                           class="text-blue-600 mx-1"><i class="fas fa-edit"></i></a>

                        <form action="<?php echo e(route('informasi.artikel.destroy',$item->id)); ?>"
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
                <tr><td colspan="5" class="py-4 text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH D:\manpro\2\Manajemen_masjid\resources\views/modules/informasi/partials/table_artikel.blade.php ENDPATH**/ ?>