

<?php $__env->startSection('title', 'Register - Manajemen Masjid'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 py-12">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="text-center mb-8">
                <i class="fas fa-mosque text-green-700 text-5xl mb-4"></i>
                <h1 class="text-3xl font-bold text-gray-800">Daftar Akun</h1>
                <p class="text-gray-600 mt-2">Bergabung dengan komunitas masjid</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="name">
                        <i class="fas fa-user mr-2"></i>Nama Lengkap
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="<?php echo e(old('name')); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="email">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?php echo e(old('email')); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="username">
                        <i class="fas fa-at mr-2"></i>Username
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="<?php echo e(old('username')); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="phone">
                        <i class="fas fa-phone mr-2"></i>No. Telepon (Opsional)
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="<?php echo e(old('phone')); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="address">
                        <i class="fas fa-map-marker-alt mr-2"></i>Alamat (Opsional)
                    </label>
                    <textarea id="address" 
                              name="address" 
                              rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"><?php echo e(old('address')); ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="password">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2" for="password_confirmation">
                        <i class="fas fa-lock mr-2"></i>Konfirmasi Password
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <button type="submit" 
                        class="w-full bg-green-700 text-white py-3 rounded-lg hover:bg-green-800 transition font-semibold">
                    <i class="fas fa-user-plus mr-2"></i>Daftar
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Sudah punya akun? 
                    <a href="<?php echo e(route('login')); ?>" class="text-green-600 hover:underline font-semibold">
                        Login Sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ASUS\Music\Semester 7\menpro\manajemen_masjid\resources\views/auth/register.blade.php ENDPATH**/ ?>