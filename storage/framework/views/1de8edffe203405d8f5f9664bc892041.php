

<?php $__env->startSection('title', 'Manajemen Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-users text-indigo-700 mr-2"></i>Manajemen Users
            </h1>
            <p class="text-gray-600 mt-2">Kelola pengguna dan role di sistem</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm">Total Users</p>
                        <p class="text-2xl font-bold"><?php echo e($users->total()); ?></p>
                    </div>
                    <i class="fas fa-users text-3xl text-indigo-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Super Admin</p>
                        <p class="text-2xl font-bold"><?php echo e($users->filter(fn($u) => $u->hasRole('super_admin'))->count()); ?></p>
                    </div>
                    <i class="fas fa-crown text-3xl text-green-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Admin Modul</p>
                        <p class="text-2xl font-bold"><?php echo e($users->filter(fn($u) => $u->roles->contains(fn($r) => str_starts_with($r->name, 'admin_')))->count()); ?></p>
                    </div>
                    <i class="fas fa-user-shield text-3xl text-blue-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Jamaah</p>
                        <p class="text-2xl font-bold"><?php echo e($users->filter(fn($u) => $u->hasRole('jamaah'))->count()); ?></p>
                    </div>
                    <i class="fas fa-user text-3xl text-purple-200"></i>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <?php if($users->isEmpty()): ?>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-user-slash text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Tidak ada user</p>
                <p class="text-sm mt-2">Belum ada user terdaftar di sistem.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Username
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Roles
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Terdaftar
                            </th>
                            <?php if($currentUser->hasRole('super_admin')): ?>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 <?php echo e($user->id == $currentUser->id ? 'bg-blue-50' : ''); ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($user->id); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold">
                                                    <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo e($user->name); ?>

                                                <?php if($user->id == $currentUser->id): ?>
                                                    <span class="ml-2 text-xs text-blue-600">(Anda)</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($user->username); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo e($user->email); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                <?php echo e($role->name == 'super_admin' ? 'bg-red-100 text-red-800' : ''); ?>

                                                <?php echo e(str_starts_with($role->name, 'admin_') ? 'bg-blue-100 text-blue-800' : ''); ?>

                                                <?php echo e(str_starts_with($role->name, 'pengurus_') ? 'bg-green-100 text-green-800' : ''); ?>

                                                <?php echo e($role->name == 'jamaah' ? 'bg-gray-100 text-gray-800' : ''); ?>">
                                                <?php if($role->name == 'super_admin'): ?>
                                                    <i class="fas fa-crown mr-1"></i>
                                                <?php elseif(str_starts_with($role->name, 'admin_')): ?>
                                                    <i class="fas fa-user-shield mr-1"></i>
                                                <?php elseif(str_starts_with($role->name, 'pengurus_')): ?>
                                                    <i class="fas fa-user-tie mr-1"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-user mr-1"></i>
                                                <?php endif; ?>
                                                <?php echo e($role->name); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-500">
                                                No roles
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        <?php echo e($user->created_at ? $user->created_at->format('d M Y') : '-'); ?>

                                    </div>
                                </td>
                                <?php if($currentUser->hasRole('super_admin')): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2">
                                        <a href="<?php echo e(route('users.roles', $user->id)); ?>" 
                                           class="text-indigo-600 hover:text-indigo-900"
                                           title="Kelola Roles">
                                            <i class="fas fa-user-cog"></i>
                                        </a>
                                        <?php if($user->id != $currentUser->id): ?>
                                            <button onclick="confirmDelete(<?php echo e($user->id); ?>)" 
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Hapus User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <?php echo e($users->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php if($currentUser->hasRole('super_admin')): ?>
<script>
function confirmDelete(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
        // Implement delete functionality here
        alert('Fitur hapus user akan diimplementasikan');
    }
}
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\Downloads\Manpro Masjid\resources\views/users/index.blade.php ENDPATH**/ ?>