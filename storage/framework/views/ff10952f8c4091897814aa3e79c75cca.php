
<?php $__env->startSection('title', 'Laporan Kegiatan'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-file-alt text-green-700 mr-2"></i>Laporan Kegiatan
                    </h1>
                    <p class="text-gray-600 mt-2">Dokumentasi dan laporan kegiatan masjid</p>
                </div>
                <?php if(!auth()->user()->isSuperAdmin()): ?>
                    <a href="<?php echo e(route('kegiatan.laporan.create')); ?>"
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i>Buat Laporan
                    </a>
                <?php endif; ?>
            </div>

            <?php if(auth()->user()->isSuperAdmin()): ?>
                <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong></p>
                </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Laporan</p>
                            <h3 class="text-2xl font-bold">45</h3>
                        </div>
                        <i class="fas fa-file-alt text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Bulan Ini</p>
                            <h3 class="text-2xl font-bold">8</h3>
                        </div>
                        <i class="fas fa-calendar-check text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Peserta Total</p>
                            <h3 class="text-2xl font-bold">1,234</h3>
                        </div>
                        <i class="fas fa-users text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Kegiatan Aktif</p>
                            <h3 class="text-2xl font-bold">3</h3>
                        </div>
                        <i class="fas fa-tasks text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex gap-4">
                <div class="flex-1">
                    <input type="text" placeholder="Cari laporan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Jenis</option>
                    <option value="kajian">Kajian</option>
                    <option value="sosial">Sosial</option>
                    <option value="pendidikan">Pendidikan</option>
                </select>
                <input type="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <!-- Laporan List -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kegiatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-800">Kajian Rutin Jumat</p>
                                    <p class="text-sm text-gray-500">Kajian Islami</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                1 Des 2025
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <i class="fas fa-users mr-1"></i>120 orang
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Selesai</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button class="text-blue-600 hover:text-blue-800" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if(!auth()->user()->isSuperAdmin()): ?>
                                        <button class="text-green-600 hover:text-green-800" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600">Menampilkan 1-10 dari 45 laporan</p>
                <nav class="flex gap-2">
                    <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">Previous</button>
                    <button class="px-3 py-1 bg-green-700 text-white rounded">1</button>
                    <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">Next</button>
                </nav>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Bayu\Kuliah\Semester 7\Menpro\Kodingan\manajemen_masjid\resources\views/modules/kegiatan/laporan/index.blade.php ENDPATH**/ ?>