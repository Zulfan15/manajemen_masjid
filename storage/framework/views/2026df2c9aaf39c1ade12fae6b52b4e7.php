

<?php $__env->startSection('title', 'Verifikasi Keanggotaan Jamaah'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-user-check text-green-600 mr-2"></i>Verifikasi Keanggotaan Jamaah
            </h1>
            <p class="text-gray-600 mt-2">Kelola verifikasi status keanggotaan jamaah untuk sistem voting</p>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p><?php echo e(session('success')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p><?php echo e(session('error')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('info')): ?>
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <p><?php echo e(session('info')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Jamaah</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($stats['total']); ?></h3>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Terverifikasi</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-2"><?php echo e($stats['verified']); ?></h3>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <?php echo e($stats['total'] > 0 ? round(($stats['verified'] / $stats['total']) * 100, 1) : 0); ?>% dari total
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Belum Verifikasi</p>
                    <h3 class="text-3xl font-bold text-red-600 mt-2"><?php echo e($stats['unverified']); ?></h3>
                </div>
                <div class="bg-red-100 rounded-full p-4">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <?php echo e($stats['total'] > 0 ? round(($stats['unverified'] / $stats['total']) * 100, 1) : 0); ?>% dari total
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="<?php echo e(route('takmir.verifikasi-jamaah.index')); ?>" class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>Status Verifikasi
                </label>
                <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="verified" <?php echo e(request('status') == 'verified' ? 'selected' : ''); ?>>Terverifikasi</option>
                    <option value="unverified" <?php echo e(request('status') == 'unverified' ? 'selected' : ''); ?>>Belum Verifikasi</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>Cari Jamaah
                </label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="Nama, email, username, atau telepon..." 
                       class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <?php if(request('status') || request('search')): ?>
                <a href="<?php echo e(route('takmir.verifikasi-jamaah.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Jamaah List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <?php if($jamaahList->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jamaah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kontak
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Verifikasi
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $jamaahList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jamaah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <?php if($jamaah->photo): ?>
                                            <img src="<?php echo e(asset('storage/' . $jamaah->photo)); ?>" alt="<?php echo e($jamaah->name); ?>" 
                                                 class="w-10 h-10 rounded-full object-cover mr-3">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-gray-500"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="font-semibold text-gray-800"><?php echo e($jamaah->name); ?></div>
                                            <div class="text-sm text-gray-600">{{ $jamaah->username }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-800">
                                        <div><i class="fas fa-envelope text-gray-400 mr-1"></i><?php echo e($jamaah->email); ?></div>
                                        <?php if($jamaah->phone): ?>
                                            <div class="mt-1"><i class="fas fa-phone text-gray-400 mr-1"></i><?php echo e($jamaah->phone); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($jamaah->is_verified): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Belum Verifikasi
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($jamaah->is_verified): ?>
                                        <div class="text-sm text-gray-600">
                                            <div class="font-medium">Oleh: <?php echo e($jamaah->verifier ? $jamaah->verifier->name : '-'); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo e($jamaah->verified_at ? $jamaah->verified_at->format('d M Y H:i') : '-'); ?></div>
                                            <?php if($jamaah->verification_notes): ?>
                                                <div class="text-xs text-gray-600 mt-1 italic">"<?php echo e($jamaah->verification_notes); ?>"</div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-500">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($jamaah->is_verified): ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('takmir.update')): ?>
                                            <form method="POST" action="<?php echo e(route('takmir.verifikasi-jamaah.unverify', $jamaah->id)); ?>" 
                                                  onsubmit="return confirm('Yakin ingin membatalkan verifikasi jamaah ini?')"
                                                  class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition">
                                                    <i class="fas fa-times mr-1"></i>Batalkan
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('takmir.update')): ?>
                                            <button onclick="openVerifyModal(<?php echo e($jamaah->id); ?>, '<?php echo e($jamaah->name); ?>')" 
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">
                                                <i class="fas fa-check mr-1"></i>Verifikasi
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                <?php echo e($jamaahList->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-16">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Data</h3>
                <p class="text-gray-500">
                    <?php if(request('search') || request('status')): ?>
                        Tidak ada jamaah yang sesuai dengan filter yang dipilih
                    <?php else: ?>
                        Belum ada jamaah terdaftar di sistem
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Verify Modal -->
<div id="verifyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>Verifikasi Jamaah
        </h3>
        <form id="verifyForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <p class="text-gray-700 mb-4">Anda akan memverifikasi jamaah:</p>
                <div class="bg-gray-50 p-3 rounded border border-gray-200">
                    <p class="font-semibold text-gray-800" id="jamaahName"></p>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea name="notes" rows="3" 
                          class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                          placeholder="Tambahkan catatan verifikasi..."></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeVerifyModal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-check mr-2"></i>Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVerifyModal(userId, jamaahName) {
        document.getElementById('verifyModal').classList.remove('hidden');
        document.getElementById('verifyModal').classList.add('flex');
        document.getElementById('jamaahName').textContent = jamaahName;
        document.getElementById('verifyForm').action = `/takmir/verifikasi-jamaah/${userId}/verify`;
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
        document.getElementById('verifyModal').classList.remove('flex');
        document.getElementById('verifyForm').reset();
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVerifyModal();
        }
    });

    // Close modal on outside click
    document.getElementById('verifyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeVerifyModal();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\!KULIAH\!SEM7\menpro\manajemen_masjid\resources\views/modules/takmir/verifikasi-jamaah.blade.php ENDPATH**/ ?>