
<?php $__env->startSection('title', 'Detail Kegiatan'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container mx-auto">
        <!-- Alert Messages -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p><i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p><i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow p-6">
            <!-- Header -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 <?php echo e($kegiatan->getStatusBadgeClass()); ?> text-sm rounded-full">
                            <?php echo e(ucfirst($kegiatan->status)); ?>

                        </span>
                        <span class="px-3 py-1 <?php echo e($kegiatan->getJenisBadgeClass()); ?> text-sm rounded-full">
                            <?php echo e(ucfirst(str_replace('_', ' ', $kegiatan->jenis_kegiatan))); ?>

                        </span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas <?php echo e($kegiatan->getKategoriIcon()); ?> text-green-700 mr-2"></i>
                        <?php echo e($kegiatan->nama_kegiatan); ?>

                    </h1>
                </div>
                <div class="flex gap-2">
                    <a href="<?php echo e(route('kegiatan.index')); ?>"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <?php if(!auth()->user()->isSuperAdmin()): ?>
                        <a href="<?php echo e(route('kegiatan.edit', $kegiatan->id)); ?>"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex gap-4">
                    <button class="tab-button border-b-2 border-green-700 text-green-700 py-3 px-4 font-medium"
                        onclick="showTab('detail')">
                        <i class="fas fa-info-circle mr-2"></i>Detail
                    </button>
                    <button class="tab-button border-b-2 border-transparent text-gray-600 py-3 px-4 hover:text-gray-800"
                        onclick="showTab('peserta')">
                        <i class="fas fa-users mr-2"></i>Peserta (<?php echo e($pesertaStats['total']); ?>)
                    </button>
                    <?php if(!auth()->user()->isSuperAdmin() && $kegiatan->status != 'dibatalkan'): ?>
                        <button class="tab-button border-b-2 border-transparent text-gray-600 py-3 px-4 hover:text-gray-800"
                            onclick="showTab('daftar')">
                            <i class="fas fa-user-plus mr-2"></i>Pendaftaran
                        </button>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Tab: Detail -->
            <div id="tab-detail">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Main Info -->
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Deskripsi</h3>
                            <p class="text-gray-600"><?php echo e($kegiatan->deskripsi ?? 'Tidak ada deskripsi'); ?></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar text-green-700 mr-2"></i>Tanggal & Waktu
                                </h4>
                                <p class="text-gray-600"><?php echo e($kegiatan->tanggal_mulai->format('d F Y')); ?></p>
                                <?php if($kegiatan->tanggal_selesai): ?>
                                    <p class="text-gray-600">s/d <?php echo e($kegiatan->tanggal_selesai->format('d F Y')); ?></p>
                                <?php endif; ?>
                                <p class="text-gray-600 mt-1">
                                    <?php echo e(date('H:i', strtotime($kegiatan->waktu_mulai))); ?>

                                    <?php if($kegiatan->waktu_selesai): ?>
                                        - <?php echo e(date('H:i', strtotime($kegiatan->waktu_selesai))); ?>

                                    <?php endif; ?>
                                    WIB
                                </p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>Lokasi
                                </h4>
                                <p class="text-gray-600"><?php echo e($kegiatan->lokasi); ?></p>
                            </div>
                        </div>

                        <?php if($kegiatan->pic): ?>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user-tie text-green-700 mr-2"></i>Penanggung Jawab
                                </h4>
                                <p class="text-gray-600"><?php echo e($kegiatan->pic); ?></p>
                                <?php if($kegiatan->kontak_pic): ?>
                                    <p class="text-gray-600"><?php echo e($kegiatan->kontak_pic); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if($kegiatan->catatan): ?>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-sticky-note text-green-700 mr-2"></i>Catatan
                                </h4>
                                <p class="text-gray-600"><?php echo e($kegiatan->catatan); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Sidebar Stats -->
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">Peserta</h4>
                            <p class="text-2xl font-bold text-blue-600">
                                <?php echo e($kegiatan->jumlah_peserta); ?>

                                <?php if($kegiatan->kuota_peserta): ?>
                                    / <?php echo e($kegiatan->kuota_peserta); ?>

                                <?php endif; ?>
                            </p>
                            <p class="text-sm text-blue-600">
                                <?php if($kegiatan->kuota_peserta): ?>
                                    Sisa: <?php echo e($kegiatan->sisaKuota()); ?> tempat
                                <?php else: ?>
                                    Unlimited
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-2">Kehadiran</h4>
                            <p class="text-2xl font-bold text-green-600"><?php echo e($pesertaStats['hadir']); ?></p>
                            <p class="text-sm text-green-600">orang hadir</p>
                        </div>

                        <?php if($kegiatan->budget): ?>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-purple-800 mb-2">Budget</h4>
                                <p class="text-lg font-bold text-purple-600">Rp
                                    <?php echo e(number_format($kegiatan->budget, 0, ',', '.')); ?></p>
                                <?php if($kegiatan->realisasi_biaya): ?>
                                    <p class="text-sm text-purple-600">Realisasi: Rp
                                        <?php echo e(number_format($kegiatan->realisasi_biaya, 0, ',', '.')); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-2">
                            <?php if($kegiatan->butuh_pendaftaran): ?>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>Butuh Pendaftaran
                                </p>
                            <?php endif; ?>
                            <?php if($kegiatan->sertifikat_tersedia): ?>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-certificate text-yellow-600 mr-2"></i>Sertifikat Tersedia
                                </p>
                            <?php endif; ?>
                            <?php if($kegiatan->is_recurring): ?>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-redo text-blue-600 mr-2"></i>Kegiatan Berulang
                                    <?php if($kegiatan->recurring_type): ?>
                                        (<?php echo e(ucfirst($kegiatan->recurring_type)); ?>)
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <?php if(!auth()->user()->isSuperAdmin() && $kegiatan->status == 'direncanakan'): ?>
                            <a href="<?php echo e(route('kegiatan.absensi', $kegiatan->id)); ?>"
                                class="block w-full px-4 py-3 bg-orange-600 text-white text-center rounded-lg hover:bg-orange-700 transition">
                                <i class="fas fa-clipboard-check mr-2"></i>Kelola Absensi
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tab: Peserta -->
            <div id="tab-peserta" class="hidden">
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Peserta</h3>
                    <div class="text-sm text-gray-600">
                        <span class="mr-4"><i class="fas fa-users mr-1"></i>Total: <?php echo e($pesertaStats['total']); ?></span>
                        <span class="mr-4 text-green-600"><i class="fas fa-check mr-1"></i>Hadir:
                            <?php echo e($pesertaStats['hadir']); ?></span>
                    </div>
                </div>

                <?php if($kegiatan->peserta->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Absensi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                        Daftar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $kegiatan->peserta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $peserta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($index + 1); ?></td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800"><?php echo e($peserta->nama_peserta); ?></p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <?php if($peserta->email): ?>
                                                <p><?php echo e($peserta->email); ?></p>
                                            <?php endif; ?>
                                            <?php if($peserta->no_hp): ?>
                                                <p><?php echo e($peserta->no_hp); ?></p>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 <?php echo e($peserta->getStatusBadgeClass()); ?> text-xs rounded">
                                                <?php echo e(ucfirst($peserta->status_pendaftaran)); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if($peserta->absensi): ?>
                                                <span
                                                    class="px-2 py-1 <?php echo e($peserta->absensi->getStatusBadgeClass()); ?> text-xs rounded">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $peserta->absensi->status_kehadiran))); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-sm">Belum absen</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <?php echo e($peserta->tanggal_daftar->format('d M Y H:i')); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-users text-5xl mb-3 text-gray-300"></i>
                        <p>Belum ada peserta yang mendaftar</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab: Pendaftaran -->
            <?php if(!auth()->user()->isSuperAdmin() && $kegiatan->status != 'dibatalkan'): ?>
                <div id="tab-daftar" class="hidden">
                    <div class="max-w-2xl mx-auto">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Form Pendaftaran Peserta</h3>

                        <?php if($kegiatan->isFull()): ?>
                            <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-6">
                                <p class="text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i>Kuota peserta
                                    sudah penuh!</p>
                            </div>
                        <?php elseif($kegiatan->sudahDaftar(auth()->id())): ?>
                            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                                <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i>Anda sudah terdaftar di
                                    kegiatan ini</p>
                            </div>
                        <?php else: ?>
                            <form action="<?php echo e(route('kegiatan.register', $kegiatan->id)); ?>" method="POST"
                                class="space-y-4">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="user_id" value="<?php echo e(auth()->id()); ?>">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                    <input type="text" name="nama_peserta" value="<?php echo e(auth()->user()->name); ?>"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="<?php echo e(auth()->user()->email); ?>"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                                    <input type="text" name="no_hp" value="<?php echo e(auth()->user()->phone ?? ''); ?>"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                    <textarea name="alamat" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"><?php echo e(auth()->user()->address ?? ''); ?></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan
                                        (opsional)</label>
                                    <textarea name="keterangan" rows="2" placeholder="Pertanyaan, kebutuhan khusus, dll"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                                    <i class="fas fa-check mr-2"></i>Daftar Sekarang
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showTab(tab) {
            // Hide all tabs
            document.getElementById('tab-detail').classList.add('hidden');
            document.getElementById('tab-peserta').classList.add('hidden');
            <?php if(!auth()->user()->isSuperAdmin()): ?>
                const daftarTab = document.getElementById('tab-daftar');
                if (daftarTab) daftarTab.classList.add('hidden');
            <?php endif; ?>

            // Show selected tab
            document.getElementById('tab-' + tab).classList.remove('hidden');

            // Update button styles
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(btn => {
                btn.classList.remove('border-green-700', 'text-green-700');
                btn.classList.add('border-transparent', 'text-gray-600');
            });
            event.target.closest('.tab-button').classList.remove('border-transparent', 'text-gray-600');
            event.target.closest('.tab-button').classList.add('border-green-700', 'text-green-700');
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Ney\Kuliah\Semester 7\MANAJEMEN PROYEK\manajemen_masjid\resources\views/modules/kegiatan/show.blade.php ENDPATH**/ ?>