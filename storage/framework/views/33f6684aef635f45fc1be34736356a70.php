
<?php $__env->startSection('title', 'Daftar Petugas'); ?>

<?php $__env->startSection('content'); ?>
<?php
  // warna primary mockup
  $primary = 'emerald-800';
?>

<div class="p-6">
    
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex min-w-72 flex-col gap-1">
            <p class="text-2xl font-bold leading-tight tracking-tight text-gray-900">Daftar Petugas</p>
            <p class="text-sm font-normal text-gray-500">Manage and view all staff accounts in the system.</p>
        </div>

        <a href="<?php echo e(route('inventaris.petugas.create')); ?>"
           class="flex h-10 min-w-[84px] items-center justify-center gap-2 overflow-hidden rounded-lg bg-emerald-800 px-4 text-sm font-bold text-white shadow-sm hover:bg-emerald-800/90">
            <i class="fa-solid fa-plus text-sm"></i>
            <span class="truncate">Tambah Petugas Baru</span>
        </a>
    </div>

    
    <div class="mt-6">
        <form method="GET" action="<?php echo e(route('inventaris.petugas.index')); ?>">
            <label class="flex h-12 w-full flex-col min-w-40">
                <div class="flex h-full w-full items-stretch rounded-lg border border-gray-200/80 bg-white">
                    <div class="flex items-center justify-center pl-4 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input name="search" value="<?php echo e(request('search')); ?>"
                           class="h-full min-w-0 flex-1 rounded-r-lg border-none bg-transparent px-3 text-sm font-normal text-gray-900 placeholder:text-gray-400 focus:outline-0 focus:ring-0"
                           placeholder="Search by name, username, or role..." />
                </div>
            </label>
        </form>
    </div>

    
    <div class="mt-4">
        <div class="overflow-hidden rounded-lg border border-gray-200/50 bg-white">
            <table class="min-w-full divide-y divide-gray-200/50">
                <thead class="bg-[#f6f8f7]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Foto/Avatar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Petugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status Akun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200/50">
                    <?php $__empty_1 = true; $__currentLoopData = $petugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // ROLE (Spatie): ambil role pertama kalau ada
                            $roleName = '-';
                            if (isset($p->roles) && $p->roles->count() > 0) {
                                $roleName = $p->roles->first()->name;
                            }

                            $roleLower = strtolower($roleName);
                            $roleBadgeClass = str_contains($roleLower, 'admin')
                                ? 'bg-emerald-800/10 text-emerald-800'
                                : 'bg-gray-100 text-gray-800';

                            // STATUS (users.status enum aktif/nonaktif)
                            $isActive = empty($p->locked_until); // NULL => aktif
                            $statusText = $isActive ? 'Aktif' : 'Nonaktif';
                            $statusBadgeClass = $isActive
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800';

                            // Avatar placeholder: inisial
                            $initials = collect(explode(' ', trim($p->name ?? 'U')))
                                ->filter()
                                ->take(2)
                                ->map(fn($w) => strtoupper(mb_substr($w,0,1)))
                                ->implode('');
                        ?>

                        <tr class="hover:bg-emerald-800/5">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 text-xs font-bold">
                                    <?php echo e($initials ?: 'U'); ?>

                                </div>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                <?php echo e($p->name); ?>

                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                <?php echo e($p->username ?? '-'); ?>

                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold leading-5 <?php echo e($roleBadgeClass); ?>">
                                    <?php echo e($roleName); ?>

                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold leading-5 <?php echo e($statusBadgeClass); ?>">
                                    <?php echo e($statusText); ?>

                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <div class="flex items-center gap-3">

                                
                            <a href="<?php echo e(route('inventaris.petugas.edit', $p->id)); ?>"
                            class="text-emerald-700 hover:text-emerald-800"
                            title="Edit">
                            <i class="fa-regular fa-pen-to-square text-lg"></i>
                            </a>

                            
                            <form method="POST" action="<?php echo e(route('inventaris.petugas.reset_password', $p->id)); ?>"
                                onsubmit="return confirm('Reset password untuk <?php echo e($p->username); ?>? Password baru akan ditampilkan sekali.')">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-amber-600 hover:text-amber-700" title="Reset Password">
                                <i class="fa-solid fa-key text-lg"></i>
                            </button>
                            </form>

                            
                            <form method="POST" action="<?php echo e(route('inventaris.petugas.destroy', $p->id)); ?>"
                                onsubmit="return confirm('Hapus petugas <?php echo e($p->username); ?>?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-rose-600 hover:text-rose-700" title="Hapus">
                                <i class="fa-regular fa-trash-can text-lg"></i>
                            </button>
                            </form>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="h-12 w-12 rounded-full bg-emerald-800/10 flex items-center justify-center text-emerald-800">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </div>
                                    <p class="mt-3 text-sm font-semibold text-gray-900">Belum ada petugas</p>
                                    <p class="mt-1 text-sm text-gray-500">Tambahkan petugas baru untuk mulai mengelola inventaris.</p>
                                    <a href="<?php echo e(route('inventaris.petugas.create')); ?>"
                                    class="mt-4 inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-emerald-800 px-4 text-sm font-bold text-white hover:bg-emerald-800/90">
                                        <i class="fa-solid fa-plus text-sm"></i>
                                        Tambah Petugas Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="mt-3 flex justify-end">
            <?php echo e($petugas->onEachSide(1)->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Backup\tahun ajaran 4-1 smester 7\ManPro\Manajemen Masjid\manajemen_masjid\resources\views/modules/inventaris/petugas/index.blade.php ENDPATH**/ ?>