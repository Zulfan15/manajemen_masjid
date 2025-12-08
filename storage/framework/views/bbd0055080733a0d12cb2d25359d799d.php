

<?php $__env->startSection('title', 'Log Aktivitas Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-history text-blue-700 mr-2"></i>Log Aktivitas Saya
            </h1>
            <p class="text-gray-600 mt-2">Riwayat aktivitas yang telah Anda lakukan di sistem</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" action="<?php echo e(route('my-logs')); ?>" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Module Filter -->
                    <div>
                        <label for="module" class="block text-sm font-medium text-gray-700 mb-1">
                            Modul
                        </label>
                        <select name="module" id="module" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Modul</option>
                            <option value="keuangan" <?php echo e(request('module') == 'keuangan' ? 'selected' : ''); ?>>Keuangan</option>
                            <option value="jamaah" <?php echo e(request('module') == 'jamaah' ? 'selected' : ''); ?>>Jamaah</option>
                            <option value="kegiatan" <?php echo e(request('module') == 'kegiatan' ? 'selected' : ''); ?>>Kegiatan</option>
                            <option value="inventaris" <?php echo e(request('module') == 'inventaris' ? 'selected' : ''); ?>>Inventaris</option>
                            <option value="informasi" <?php echo e(request('module') == 'informasi' ? 'selected' : ''); ?>>Informasi</option>
                            <option value="zis" <?php echo e(request('module') == 'zis' ? 'selected' : ''); ?>>ZIS</option>
                            <option value="kurban" <?php echo e(request('module') == 'kurban' ? 'selected' : ''); ?>>Kurban</option>
                            <option value="takmir" <?php echo e(request('module') == 'takmir' ? 'selected' : ''); ?>>Takmir</option>
                            <option value="laporan" <?php echo e(request('module') == 'laporan' ? 'selected' : ''); ?>>Laporan</option>
                            <option value="users" <?php echo e(request('module') == 'users' ? 'selected' : ''); ?>>Users</option>
                        </select>
                    </div>

                    <!-- Action Filter -->
                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700 mb-1">
                            Aksi
                        </label>
                        <select name="action" id="action" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Aksi</option>
                            <option value="create" <?php echo e(request('action') == 'create' ? 'selected' : ''); ?>>Create</option>
                            <option value="update" <?php echo e(request('action') == 'update' ? 'selected' : ''); ?>>Update</option>
                            <option value="delete" <?php echo e(request('action') == 'delete' ? 'selected' : ''); ?>>Delete</option>
                            <option value="view" <?php echo e(request('action') == 'view' ? 'selected' : ''); ?>>View</option>
                            <option value="login" <?php echo e(request('action') == 'login' ? 'selected' : ''); ?>>Login</option>
                            <option value="logout" <?php echo e(request('action') == 'logout' ? 'selected' : ''); ?>>Logout</option>
                            <option value="promote" <?php echo e(request('action') == 'promote' ? 'selected' : ''); ?>>Promote</option>
                            <option value="demote" <?php echo e(request('action') == 'demote' ? 'selected' : ''); ?>>Demote</option>
                        </select>
                    </div>

                    <!-- Start Date Filter -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Dari Tanggal
                        </label>
                        <input type="date" name="start_date" id="start_date" 
                               value="<?php echo e(request('start_date')); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- End Date Filter -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" id="end_date" 
                               value="<?php echo e(request('end_date')); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="flex gap-2 justify-end">
                    <a href="<?php echo e(route('my-logs')); ?>" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Activity Logs Table -->
        <?php if($logs->isEmpty()): ?>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Tidak ada log aktivitas</p>
                <p class="text-sm mt-2">Belum ada aktivitas yang tercatat atau tidak ada hasil yang sesuai dengan filter.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Modul
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP Address
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?php echo e(\Carbon\Carbon::parse($log->created_at)->format('d M Y')); ?>

                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo e(\Carbon\Carbon::parse($log->created_at)->format('H:i:s')); ?>

                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo e($log->module == 'keuangan' ? 'bg-green-100 text-green-800' : ''); ?>

                                        <?php echo e($log->module == 'jamaah' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                        <?php echo e($log->module == 'kegiatan' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                        <?php echo e($log->module == 'inventaris' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                        <?php echo e($log->module == 'informasi' ? 'bg-indigo-100 text-indigo-800' : ''); ?>

                                        <?php echo e($log->module == 'zis' ? 'bg-pink-100 text-pink-800' : ''); ?>

                                        <?php echo e($log->module == 'kurban' ? 'bg-red-100 text-red-800' : ''); ?>

                                        <?php echo e($log->module == 'takmir' ? 'bg-orange-100 text-orange-800' : ''); ?>

                                        <?php echo e($log->module == 'laporan' ? 'bg-gray-100 text-gray-800' : ''); ?>

                                        <?php echo e($log->module == 'users' ? 'bg-teal-100 text-teal-800' : ''); ?>">
                                        <?php echo e(ucfirst($log->module ?? 'system')); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo e($log->action == 'create' ? 'bg-green-100 text-green-800' : ''); ?>

                                        <?php echo e($log->action == 'update' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                        <?php echo e($log->action == 'delete' ? 'bg-red-100 text-red-800' : ''); ?>

                                        <?php echo e($log->action == 'view' ? 'bg-gray-100 text-gray-800' : ''); ?>

                                        <?php echo e($log->action == 'login' ? 'bg-indigo-100 text-indigo-800' : ''); ?>

                                        <?php echo e($log->action == 'logout' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                        <?php echo e($log->action == 'promote' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                        <?php echo e($log->action == 'demote' ? 'bg-orange-100 text-orange-800' : ''); ?>">
                                        <?php if($log->action == 'create'): ?>
                                            <i class="fas fa-plus mr-1"></i>
                                        <?php elseif($log->action == 'update'): ?>
                                            <i class="fas fa-edit mr-1"></i>
                                        <?php elseif($log->action == 'delete'): ?>
                                            <i class="fas fa-trash mr-1"></i>
                                        <?php elseif($log->action == 'view'): ?>
                                            <i class="fas fa-eye mr-1"></i>
                                        <?php elseif($log->action == 'login'): ?>
                                            <i class="fas fa-sign-in-alt mr-1"></i>
                                        <?php elseif($log->action == 'logout'): ?>
                                            <i class="fas fa-sign-out-alt mr-1"></i>
                                        <?php elseif($log->action == 'promote'): ?>
                                            <i class="fas fa-arrow-up mr-1"></i>
                                        <?php elseif($log->action == 'demote'): ?>
                                            <i class="fas fa-arrow-down mr-1"></i>
                                        <?php endif; ?>
                                        <?php echo e(ucfirst($log->action)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?php echo e($log->description); ?></div>
                                    <?php if($log->properties && is_array($log->properties)): ?>
                                        <details class="mt-1">
                                            <summary class="text-xs text-blue-600 cursor-pointer hover:text-blue-800">
                                                Lihat Detail
                                            </summary>
                                            <pre class="text-xs text-gray-600 mt-2 bg-gray-50 p-2 rounded overflow-x-auto"><?php echo e(json_encode($log->properties, JSON_PRETTY_PRINT)); ?></pre>
                                        </details>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo e($log->ip_address ?? '-'); ?></div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <?php echo e($logs->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\Downloads\Manpro Masjid\resources\views/activity-logs/my-logs.blade.php ENDPATH**/ ?>