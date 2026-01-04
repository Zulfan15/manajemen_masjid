

<?php $__env->startSection('title', 'Login - Manajemen Masjid'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="text-center mb-8">
                <i class="fas fa-mosque text-green-700 text-5xl mb-4"></i>
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Masjid</h1>
                <p class="text-gray-600 mt-2">Silakan login untuk melanjutkan</p>
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

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="username">
                        <i class="fas fa-user mr-2"></i>Username atau Email
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="<?php echo e(old('username')); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2" for="password">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span class="text-sm text-gray-700">Ingat Saya</span>
                    </label>
                    <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-green-600 hover:underline">
                        Lupa Password?
                    </a>
                </div>

                <button type="submit" 
                        class="w-full bg-green-700 text-white py-3 rounded-lg hover:bg-green-800 transition font-semibold">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Belum punya akun? 
                    <a href="<?php echo e(route('register')); ?>" class="text-green-600 hover:underline font-semibold">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-600">
            <p class="text-sm">© 2024 Manajemen Masjid. All rights reserved.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\!KULIAH\!SEM7\menpro\manajemen_masjid\resources\views/auth/login.blade.php ENDPATH**/ ?>