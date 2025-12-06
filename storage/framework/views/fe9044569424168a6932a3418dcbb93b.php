
<?php $__env->startSection('title', 'Pengumuman Kegiatan'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-bullhorn text-green-700 mr-2"></i>Pengumuman Kegiatan
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola pengumuman untuk kegiatan dan acara masjid</p>
                </div>
                <?php if(!auth()->user()->isSuperAdmin()): ?>
                    <a href="<?php echo e(route('kegiatan.pengumuman.create')); ?>"
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Pengumuman
                    </a>
                <?php endif; ?>
            </div>

            <?php if(auth()->user()->isSuperAdmin()): ?>
                <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong></p>
                </div>
            <?php endif; ?>

            <!-- Filter & Search -->
            <div class="mb-6 flex gap-4">
                <div class="flex-1">
                    <input type="text" placeholder="Cari pengumuman..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Tidak Aktif</option>
                </select>
            </div>

            <!-- Pengumuman List -->
            <div class="space-y-4">
                <!-- Sample Pengumuman Card -->
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aktif</span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>5 Desember 2025
                                </span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Kajian Rutin Malam Jumat</h3>
                            <p class="text-gray-600 mb-3">Mengundang seluruh jamaah untuk mengikuti kajian rutin yang akan
                                dilaksanakan pada Jumat, 8 Desember 2025 pukul 19.30 WIB...</p>
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span><i class="fas fa-eye mr-1"></i>245 views</span>
                                <span><i class="fas fa-user mr-1"></i>Admin Kegiatan</span>
                            </div>
                        </div>
                        <?php if(!auth()->user()->isSuperAdmin()): ?>
                            <div class="flex gap-2 ml-4">
                                <button class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 p-2" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Empty State -->
                <div class="text-center py-16 text-gray-500 hidden">
                    <i class="fas fa-bullhorn text-6xl mb-4 text-gray-300"></i>
                    <h3 class="text-xl font-semibold mb-2">Belum Ada Pengumuman</h3>
                    <p class="mb-4">Mulai buat pengumuman untuk kegiatan masjid</p>
                    <?php if(!auth()->user()->isSuperAdmin()): ?>
                        <a href="<?php echo e(route('kegiatan.pengumuman.create')); ?>"
                            class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition inline-block">
                            <i class="fas fa-plus mr-2"></i>Tambah Pengumuman
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                <nav class="flex gap-2">
                    <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">Previous</button>
                    <button class="px-3 py-1 bg-green-700 text-white rounded">1</button>
                    <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">3</button>
                    <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">Next</button>
                </nav>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Bayu\Kuliah\Semester 7\Menpro\Kodingan\manajemen_masjid\resources\views/modules/kegiatan/pengumuman/index.blade.php ENDPATH**/ ?>