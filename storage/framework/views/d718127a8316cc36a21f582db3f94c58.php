

<?php $__env->startSection('title', isset($post) ? 'Edit Info' : 'Tambah Info'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800"><?php echo e(isset($post) ? 'Edit Informasi' : 'Tulis Informasi Baru'); ?></h2>
        
        <form action="<?php echo e(isset($post) ? route('informasi.update', $post->id) : route('informasi.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if(isset($post)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Judul</label>
                <input type="text" name="title" value="<?php echo e(old('title', $post->title ?? '')); ?>" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                <select name="category" class="w-full border rounded p-2">
                    <?php $__currentLoopData = ['berita', 'pengumuman', 'artikel', 'dakwah']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e((old('category', $post->category ?? '') == $cat) ? 'selected' : ''); ?>>
                            <?php echo e(ucfirst($cat)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Isi Konten</label>
                <textarea name="content" rows="10" class="w-full border rounded p-2" required><?php echo e(old('content', $post->content ?? '')); ?></textarea>
            </div>

            <div class="flex justify-between">
                <a href="<?php echo e(route('informasi.index')); ?>" class="text-gray-500 hover:text-gray-700 px-4 py-2">Batal</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded hover:bg-green-800">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Music\Semester 7\menpro\manajemen_masjid\resources\views/modules/informasi/create.blade.php ENDPATH**/ ?>