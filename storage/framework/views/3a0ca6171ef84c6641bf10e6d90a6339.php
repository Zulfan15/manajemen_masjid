

<?php $__env->startSection('title', 'Edit Aktivitas Harian'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Aktivitas Harian</h1>
            <p class="text-gray-600 mt-1">Ubah data aktivitas harian pengurus</p>
        </div>
        <a href="<?php echo e(route('takmir.aktivitas.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Warning untuk pengurus (24 jam rule) -->
    <?php if(auth()->user()->hasRole('pengurus_takmir')): ?>
    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
            <div>
                <p class="text-sm font-medium text-yellow-800">Perhatian!</p>
                <p class="text-sm text-yellow-700">Aktivitas hanya dapat diedit dalam 24 jam setelah dibuat.</p>
                <p class="text-sm text-yellow-600 mt-1">
                    Dibuat: <?php echo e($aktivita->created_at->format('d/m/Y H:i')); ?> 
                    (<?php echo e($aktivita->created_at->diffForHumans()); ?>)
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?php echo e(route('takmir.aktivitas.update', $aktivita->id)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Pilih Pengurus (Only for Admin) -->
            <?php if(auth()->user()->hasRole('admin_takmir') && $pengurusList): ?>
            <div class="mb-6">
                <label for="takmir_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pengurus <span class="text-red-500">*</span>
                </label>
                <select name="takmir_id" id="takmir_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['takmir_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Pilih Pengurus</option>
                    <?php $__currentLoopData = $pengurusList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengurus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($pengurus->id); ?>" 
                                <?php echo e((old('takmir_id') ?? $aktivita->takmir_id) == $pengurus->id ? 'selected' : ''); ?>>
                            <?php echo e($pengurus->nama); ?> - <?php echo e($pengurus->jabatan); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['takmir_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <?php else: ?>
            <!-- Info untuk pengurus -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Aktivitas ini tercatat atas nama:</p>
                        <p class="text-sm text-blue-700"><?php echo e($aktivita->takmir->nama); ?> - <?php echo e($aktivita->takmir->jabatan); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tanggal -->
            <div class="mb-6">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Aktivitas <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal" id="tanggal" required
                       max="<?php echo e(date('Y-m-d')); ?>"
                       value="<?php echo e(old('tanggal', $aktivita->tanggal->format('Y-m-d'))); ?>"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="text-gray-500 text-sm mt-1">
                    <i class="fas fa-info-circle"></i> Tanggal tidak boleh lebih dari hari ini
                </p>
            </div>

            <!-- Jenis Aktivitas -->
            <div class="mb-6">
                <label for="jenis_aktivitas" class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Aktivitas <span class="text-red-500">*</span>
                </label>
                <select name="jenis_aktivitas" id="jenis_aktivitas" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['jenis_aktivitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Pilih Jenis Aktivitas</option>
                    <option value="Ibadah" <?php echo e((old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Ibadah' ? 'selected' : ''); ?>>Ibadah</option>
                    <option value="Kebersihan" <?php echo e((old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Kebersihan' ? 'selected' : ''); ?>>Kebersihan</option>
                    <option value="Administrasi" <?php echo e((old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Administrasi' ? 'selected' : ''); ?>>Administrasi</option>
                    <option value="Pengajaran" <?php echo e((old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Pengajaran' ? 'selected' : ''); ?>>Pengajaran</option>
                    <option value="Pembinaan" <?php echo e((old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Pembinaan' ? 'selected' : ''); ?>>Pembinaan</option>
                    <option value="Keuangan" <?php echo e((old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Keuangan' ? 'selected' : ''); ?>>Keuangan</option>
                    <option value="Sosial" <?php echo e((old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Sosial' ? 'selected' : ''); ?>>Sosial</option>
                    <option value="Lainnya" <?php echo e((old('jenis_aktivitas') ?? $aktivita->jenis_aktivitas) == 'Lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                </select>
                <?php $__errorArgs = ['jenis_aktivitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Aktivitas <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="5" required
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Jelaskan secara detail aktivitas yang dilakukan..."><?php echo e(old('deskripsi', $aktivita->deskripsi)); ?></textarea>
                <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="text-gray-500 text-sm mt-1">
                    <i class="fas fa-info-circle"></i> Minimal 10 karakter
                </p>
            </div>

            <!-- Durasi -->
            <div class="mb-6">
                <label for="durasi_jam" class="block text-sm font-medium text-gray-700 mb-2">
                    Durasi (jam)
                </label>
                <input type="number" name="durasi_jam" id="durasi_jam" 
                       min="0.5" max="24" step="0.5"
                       value="<?php echo e(old('durasi_jam', $aktivita->durasi_jam)); ?>"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['durasi_jam'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Contoh: 2.5">
                <?php $__errorArgs = ['durasi_jam'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="text-gray-500 text-sm mt-1">
                    <i class="fas fa-info-circle"></i> Opsional. Minimal 0.5 jam (30 menit), maksimal 24 jam
                </p>
            </div>

            <!-- Bukti Foto -->
            <div class="mb-6">
                <label for="bukti_foto" class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti Foto
                </label>
                
                <!-- Foto saat ini -->
                <?php if($aktivita->bukti_foto): ?>
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                    <img src="<?php echo e($aktivita->bukti_foto_url); ?>" alt="Foto saat ini" class="max-w-xs rounded-lg border border-gray-300">
                </div>
                <?php endif; ?>
                
                <input type="file" name="bukti_foto" id="bukti_foto" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['bukti_foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['bukti_foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="text-gray-500 text-sm mt-1">
                    <i class="fas fa-info-circle"></i> Opsional. Upload foto baru untuk mengganti foto lama. Format: JPG, PNG, JPEG. Maksimal 2MB
                </p>
                
                <!-- Preview -->
                <div id="preview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview foto baru:</p>
                    <img id="preview-image" src="" alt="Preview" class="max-w-xs rounded-lg border border-gray-300">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Aktivitas
                </button>
                <a href="<?php echo e(route('takmir.aktivitas.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Preview foto sebelum upload
    document.getElementById('bukti_foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').classList.remove('hidden');
                document.getElementById('preview-image').src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('preview').classList.add('hidden');
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\!KULIAH\!SEM7\menpro\manajemen_masjid\resources\views/modules/takmir/aktivitas/edit.blade.php ENDPATH**/ ?>