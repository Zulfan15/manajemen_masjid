

<?php $__env->startSection('title', 'Kelola Pengurus ' . ucfirst($module)); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-user-plus text-blue-700 mr-2"></i>Kelola Pengurus <?php echo e(ucfirst($module)); ?>

            </h1>
            <p class="text-gray-600 mt-2">Promosikan jamaah menjadi pengurus atau turunkan pengurus ke jamaah</p>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-bold">Sukses!</p>
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Error!</p>
                <p><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        <!-- Daftar Pengurus Saat Ini -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-users text-green-600 mr-2"></i>Pengurus <?php echo e(ucfirst($module)); ?> Saat Ini
            </h2>
            
            <?php if($officers->isEmpty()): ?>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center text-gray-500">
                    <i class="fas fa-user-slash text-4xl mb-3 text-gray-300"></i>
                    <p>Belum ada pengurus untuk modul ini.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__currentLoopData = $officers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $officer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($officer->name); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo e($officer->username); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo e($officer->email); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            pengurus_<?php echo e($module); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <form action="<?php echo e(route('users.demote', ['module' => $module, 'userId' => $officer->id])); ?>" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menurunkan <?php echo e($officer->name); ?> ke jamaah?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                                                <i class="fas fa-arrow-down mr-1"></i>Turunkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Promosikan Jamaah -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-user-plus text-blue-600 mr-2"></i>Promosikan Jamaah
            </h2>
            
            <?php if($promotableUsers->isEmpty()): ?>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center text-gray-500">
                    <i class="fas fa-info-circle text-4xl mb-3 text-gray-300"></i>
                    <p>Tidak ada jamaah yang dapat dipromosikan saat ini.</p>
                    <p class="text-sm mt-2">Semua jamaah mungkin sudah menjadi pengurus atau belum ada user dengan role jamaah.</p>
                </div>
            <?php else: ?>
                <form action="<?php echo e(route('users.promote', $module)); ?>" method="POST" class="mb-6">
                    <?php echo csrf_field(); ?>
                    <div class="flex items-end gap-4">
                        <div class="flex-1">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Jamaah
                            </label>
                            <select name="user_id" 
                                    id="user_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">-- Pilih Jamaah --</option>
                                <?php $__currentLoopData = $promotableUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>">
                                        <?php echo e($user->name); ?> (<?php echo e($user->username); ?>) - <?php echo e($user->email); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition font-medium">
                                <i class="fas fa-arrow-up mr-2"></i>Promosikan
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Daftar Jamaah yang Dapat Dipromosikan -->
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__currentLoopData = $promotableUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($user->name); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo e($user->username); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo e($user->email); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            jamaah
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fadhi\Documents\KULIah\SEMESTER 7\Manajemen Proyek\New folder\manajemen_masjid\resources\views/users/promote.blade.php ENDPATH**/ ?>