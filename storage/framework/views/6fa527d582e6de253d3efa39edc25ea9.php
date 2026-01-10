

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto bg-white p-8 shadow rounded-lg">

    <h2 class="text-2xl font-bold mb-6">
        <?php echo e($data ? "Edit " : "Tambah "); ?> <?php echo e(ucfirst($type)); ?>

    </h2>

    <form 
        action="<?php echo e($data ? $route_update : $route_store); ?>" 
        method="POST" enctype="multipart/form-data">

        <?php echo csrf_field(); ?>
        <?php if($data): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

        
        <div class="mb-4">
            <label class="font-semibold">Judul</label>
            <input type="text" name="title"
                   value="<?php echo e(old('title', $data->title ?? '')); ?>"
                   class="mt-1 w-full p-3 border rounded-lg">
        </div>

        
        <?php if($type == 'artikel'): ?>
        <div class="mb-4">
            <label class="font-semibold">Kategori</label>
            <select name="category_id" id="category_select"
                    class="mt-1 w-full p-3 border rounded-lg">
                <option value="">-- Pilih Kategori --</option>

                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>"
                        <?php echo e(old('category_id', $data->category_id ?? '') == $cat->id ? 'selected':''); ?>>
                        <?php echo e($cat->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <option value="new">+ Tambah Kategori Baru</option>
            </select>

            
            <input type="text" id="new_category_input" name="new_category"
                   placeholder="Nama kategori baru..."
                   class="mt-3 w-full p-3 border rounded-lg hidden">
        </div>
        <?php endif; ?>

        
        <?php if($type == 'pengumuman'): ?>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="font-semibold">Tanggal Mulai</label>
                <input type="date" name="start_date"
                       value="<?php echo e(old('start_date', $data->start_date ?? '')); ?>"
                       class="mt-1 w-full p-3 border rounded-lg">
            </div>
            <div>
                <label class="font-semibold">Tanggal Berakhir</label>
                <input type="date" name="end_date"
                       value="<?php echo e(old('end_date', $data->end_date ?? '')); ?>"
                       class="mt-1 w-full p-3 border rounded-lg">
            </div>
        </div>
        <?php endif; ?>

        
        <div class="mb-4">
            <label class="font-semibold">Konten</label>
            <textarea name="content" rows="7"
                      class="mt-1 w-full p-3 border rounded-lg"><?php echo e(old('content', $data->content ?? '')); ?></textarea>
        </div>

        
        <?php if($type !== 'pengumuman'): ?>
        <div class="mb-4">
            <label class="font-semibold">Thumbnail (opsional)</label>
            <input type="file" name="thumbnail" class="mt-2">

            <?php if($data && $data->thumbnail): ?>
                <img src="<?php echo e(asset('storage/'.$data->thumbnail)); ?>"
                     class="h-32 mt-2 rounded border">
            <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <div class="mb-6">
            <label class="font-semibold">Kirim Notifikasi?</label>
            <select name="send_notification" class="mt-1 w-full p-3 border rounded-lg">
                <option value="no">Tidak</option>
                <option value="yes">Ya, kirim notifikasi</option>
            </select>
        </div>

        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                Simpan
            </button>
        </div>
    </form>
</div>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const select = document.getElementById("category_select");
    const newInput = document.getElementById("new_category_input");

    if (!select) return;

    select.addEventListener("change", () => {
        if (select.value === "new") {
            newInput.classList.remove("hidden");
            newInput.required = true;
        } else {
            newInput.classList.add("hidden");
            newInput.required = false;
            newInput.value = "";
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\manpro\2\Manajemen_masjid\resources\views/modules/informasi/form.blade.php ENDPATH**/ ?>