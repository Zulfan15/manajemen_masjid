<aside class="bg-white w-64 fixed left-0 top-16 bottom-0 overflow-y-auto shadow-lg hidden md:block"
    x-data="{ sidebarOpen: true }">
    <div class="p-4">
        <div class="mb-4">
            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Peran Anda</p>
            <?php $__currentLoopData = auth()->user()->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mb-1 mr-1">
                    <?php echo e($role->name); ?>

                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <hr class="my-4">

        <!-- Navigation Menu -->
        <nav>
            <a href="<?php echo e(route('dashboard')); ?>"
                class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('dashboard') ? 'bg-green-100 text-green-700' : ''); ?>">
                <i class="fas fa-home w-6"></i>
                <span>Dashboard</span>
            </a>

            <?php
                $modules = [
                    'jamaah' => ['icon' => 'fa-users', 'label' => 'Manajemen Jamaah'],
                    'keuangan' => ['icon' => 'fa-money-bill-wave', 'label' => 'Keuangan'],
                    'kegiatan' => ['icon' => 'fa-calendar-alt', 'label' => 'Kegiatan & Acara'],
                    'zis' => ['icon' => 'fa-hand-holding-heart', 'label' => 'ZIS'],
                    'kurban' => ['icon' => 'fa-sheep', 'label' => 'Kurban'],
                    'inventaris' => ['icon' => 'fa-boxes', 'label' => 'Inventaris'],
                    'takmir' => ['icon' => 'fa-user-tie', 'label' => 'Takmir'],
                    'informasi' => ['icon' => 'fa-bullhorn', 'label' => 'Informasi'],
                    'laporan' => ['icon' => 'fa-chart-bar', 'label' => 'Laporan'],
                ];
            ?>

            <hr class="my-4">
            <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Modul</p>

            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(auth()->user()->canAccessModule($key)): ?>
                    <a href="<?php echo e(route($key . '.index')); ?>"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs($key . '.*') ? 'bg-green-100 text-green-700' : ''); ?>">
                        <i class="fas <?php echo e($module['icon']); ?> w-6"></i>
                        <span><?php echo e($module['label']); ?></span>
                        
                        <?php if(!auth()->user()->isSuperAdmin()): ?>
                            <span class="ml-auto text-xs text-green-600">
                                <i class="fas fa-edit"></i>
                            </span>
                        <?php else: ?>
                            <span class="ml-auto text-xs text-blue-600">
                                <i class="fas fa-eye"></i>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(auth()->user()->hasRole('super_admin')): ?>
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Super Admin</p>

                <a href="<?php echo e(route('activity-logs.index')); ?>"
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition">
                    <i class="fas fa-history w-6"></i>
                    <span>Log Aktivitas</span>
                </a>

                <a href="<?php echo e(route('users.index')); ?>"
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition">
                    <i class="fas fa-users-cog w-6"></i>
                    <span>Manajemen User</span>
                </a>
            <?php endif; ?>

            <?php $__currentLoopData = ['jamaah', 'keuangan', 'kegiatan', 'zis', 'kurban', 'inventaris', 'takmir', 'informasi', 'laporan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(auth()->user()->hasRole("admin_{$module}")): ?>
                    <hr class="my-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Admin <?php echo e(ucfirst($module)); ?></p>

                    <?php if($module === 'kegiatan'): ?>
                        <a href="<?php echo e(route('kegiatan.pengumuman.index')); ?>"
                            class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('kegiatan.pengumuman.*') ? 'bg-green-100 text-green-700' : ''); ?>">
                            <i class="fas fa-bullhorn w-6"></i>
                            <span>Pengumuman</span>
                        </a>

                        <a href="<?php echo e(route('kegiatan.laporan.index')); ?>"
                            class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('kegiatan.laporan.*') ? 'bg-green-100 text-green-700' : ''); ?>">
                            <i class="fas fa-file-alt w-6"></i>
                            <span>Laporan Kegiatan</span>
                        </a>

                        <a href="<?php echo e(route('kegiatan.sertifikat.index')); ?>"
                            class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('kegiatan.sertifikat.*') ? 'bg-green-100 text-green-700' : ''); ?>">
                            <i class="fas fa-certificate w-6"></i>
                            <span>Generate Sertifikat</span>
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('users.promote.show', $module)); ?>"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition">
                        <i class="fas fa-user-plus w-6"></i>
                        <span>Kelola Pengurus</span>
                    </a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
    </div>
</aside>
<?php /**PATH C:\laragon\www\manajemen_masjid\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>