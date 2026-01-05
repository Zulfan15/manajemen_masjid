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
            <!-- Dashboard - Semua User -->
            <a href="<?php echo e(route('dashboard')); ?>" 
               class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('dashboard') ? 'bg-green-100 text-green-700' : ''); ?>">
                <i class="fas fa-home w-6"></i>
                <span>Dashboard</span>
            </a>

            <!-- ========================================== -->
            <!-- MENU UNTUK JAMAAH/USER BIASA -->
            <!-- ========================================== -->
            <?php if(!auth()->user()->hasAnyRole(['super_admin', 'admin_jamaah', 'admin_keuangan', 'admin_kegiatan', 'admin_zis', 'admin_kurban', 'admin_inventaris', 'admin_takmir', 'admin_informasi', 'admin_laporan'])): ?>
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Menu Jamaah</p>
                
                <a href="<?php echo e(route('jamaah.pemasukan')); ?>" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('jamaah.pemasukan*') ? 'bg-green-100 text-green-700' : ''); ?>">
                    <i class="fas fa-wallet w-6"></i>
                    <span>Pemasukan Saya</span>
                </a>
            <?php endif; ?>

            <!-- ========================================== -->
            <!-- MENU MODUL UNTUK ADMIN -->
            <!-- ========================================== -->
            <?php
                $modules = [
                    'jamaah' => ['icon' => 'fa-users', 'label' => 'Manajemen Jamaah', 'route' => 'jamaah-admin.index'],
                    'keuangan' => ['icon' => 'fa-money-bill-wave', 'label' => 'Keuangan', 'route' => 'keuangan.index'],
                    'kegiatan' => ['icon' => 'fa-calendar-alt', 'label' => 'Kegiatan & Acara', 'route' => 'kegiatan.index'],
                    'zis' => ['icon' => 'fa-hand-holding-heart', 'label' => 'ZIS', 'route' => 'zis.index'],
                    'kurban' => ['icon' => 'fa-sheep', 'label' => 'Kurban', 'route' => 'kurban.index'],
                    'inventaris' => ['icon' => 'fa-boxes', 'label' => 'Inventaris', 'route' => 'inventaris.index'],
                    'takmir' => ['icon' => 'fa-user-tie', 'label' => 'Takmir', 'route' => 'takmir.index'],
                    'informasi' => ['icon' => 'fa-bullhorn', 'label' => 'Informasi', 'route' => 'informasi.index'],
                    'laporan' => ['icon' => 'fa-chart-bar', 'label' => 'Laporan', 'route' => 'laporan-umum.index'],
                ];

                $hasAnyModule = false;
                foreach($modules as $key => $module) {
                    if(auth()->user()->canAccessModule($key)) {
                        $hasAnyModule = true;
                        break;
                    }
                }
            ?>

            <?php if($hasAnyModule): ?>
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Modul</p>

                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(auth()->user()->canAccessModule($key)): ?>
                        <a href="<?php echo e(route($module['route'])); ?>" 
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs($key . '.*') || request()->routeIs('jamaah-admin.*') && $key == 'jamaah' || request()->routeIs('laporan-umum.*') && $key == 'laporan' ? 'bg-green-100 text-green-700' : ''); ?>">
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

                        
                        <?php if($key == 'keuangan' && auth()->user()->canAccessModule('keuangan')): ?>
                            <div class="ml-4 border-l-2 border-green-200">
                                <!-- Kelola Pemasukan -->
                                <a href="<?php echo e(route('pemasukan.index')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('pemasukan.*') ? 'bg-green-50 text-green-700' : ''); ?>">
                                    <i class="fas fa-money-bill-wave w-6 text-xs"></i>
                                    <span>Kelola Pemasukan</span>
                                </a>
                                
                                <!-- ✅ HISTORY DATA TERHAPUS - MENU BARU -->
                                <a href="<?php echo e(route('history.index')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('history.*') ? 'bg-green-50 text-green-700' : ''); ?>">
                                    <i class="fas fa-history w-6 text-xs"></i>
                                    <span>History Data Terhapus</span>
                                </a>
                                
                                <!-- Laporan Keuangan -->
                                <a href="<?php echo e(route('laporan.index')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('laporan.*') ? 'bg-green-50 text-green-700' : ''); ?>">
                                    <i class="fas fa-file-invoice w-6 text-xs"></i>
                                    <span>Laporan Keuangan</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <!-- ========================================== -->
            <!-- MENU KHUSUS SUPER ADMIN -->
            <!-- ========================================== -->
            <?php if(auth()->user()->hasRole('super_admin')): ?>
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Super Admin</p>
                
                <a href="<?php echo e(route('users.index')); ?>" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('users.index') ? 'bg-green-100 text-green-700' : ''); ?>">
                    <i class="fas fa-users-cog w-6"></i>
                    <span>Manajemen User</span>
                </a>

                <a href="<?php echo e(route('activity-logs.index')); ?>" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('activity-logs.*') ? 'bg-green-100 text-green-700' : ''); ?>">
                    <i class="fas fa-history w-6"></i>
                    <span>Log Aktivitas</span>
                </a>
            <?php endif; ?>

            <!-- ========================================== -->
            <!-- MENU KELOLA PENGURUS (MODULE ADMIN) -->
            <!-- ========================================== -->
            <?php
                $adminModules = [];
                foreach(['jamaah', 'keuangan', 'kegiatan', 'zis', 'kurban', 'inventaris', 'takmir', 'informasi', 'laporan'] as $module) {
                    if(auth()->user()->hasRole("admin_{$module}")) {
                        $adminModules[] = $module;
                    }
                }
            ?>

            <?php if(count($adminModules) > 0): ?>
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Kelola Pengurus</p>

                <?php $__currentLoopData = $adminModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('users.promote.show', $module)); ?>" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->is('users/promote/'.$module) ? 'bg-green-100 text-green-700' : ''); ?>">
                        <i class="fas fa-user-plus w-6"></i>
                        <span>Pengurus <?php echo e(ucfirst($module)); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <!-- ========================================== -->
            <!-- MENU AKTIVITAS SAYA - SEMUA USER -->
            <!-- ========================================== -->
            <hr class="my-4">
            <a href="<?php echo e(route('my-logs')); ?>" 
               class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition <?php echo e(request()->routeIs('my-logs') ? 'bg-green-100 text-green-700' : ''); ?>">
                <i class="fas fa-user-clock w-6"></i>
                <span>Aktivitas Saya</span>
            </a>
        </nav>
    </div>
</aside><?php /**PATH D:\xampp\htdocs\manajemen_masjid\manajemen_masjid\manajemen_masjid-main\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>