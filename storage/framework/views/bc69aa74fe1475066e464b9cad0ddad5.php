

<?php $__env->startSection('title', 'Hasil ' . $pemilihan->judul); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-2"><?php echo e($pemilihan->judul); ?></h1>
        <p class="text-gray-600 text-lg"><?php echo e($pemilihan->deskripsi); ?></p>
        
        <div class="mt-4 flex justify-center items-center gap-4 text-sm flex-wrap">
            <div class="flex items-center">
                <i class="fas fa-calendar text-blue-600 mr-2"></i>
                <span><?php echo e($pemilihan->tanggal_mulai->format('d M Y')); ?> - <?php echo e($pemilihan->tanggal_selesai->format('d M Y')); ?></span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-users text-purple-600 mr-2"></i>
                <span>Total Suara Masuk: <strong><?php echo e($totalVotes); ?></strong></span>
            </div>
            <?php if($pemilihan->isSelesai()): ?>
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="font-semibold text-green-700">Pemilihan Selesai</span>
            </div>
            <?php else: ?>
            <div class="flex items-center">
                <i class="fas fa-spinner text-orange-600 mr-2"></i>
                <span class="font-semibold text-orange-700">Masih Berlangsung</span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($totalVotes > 0): ?>
    <!-- Winner Card (hanya tampil jika pemilihan selesai) -->
    <?php if($pemilihan->isSelesai() && $pemenang): ?>
    <div class="bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 rounded-lg shadow-2xl p-6 mb-8 text-white">
        <div class="flex items-center justify-center mb-4">
            <i class="fas fa-trophy text-6xl"></i>
        </div>
        <h2 class="text-3xl font-bold text-center mb-2">🎉 Pemenang 🎉</h2>
        <div class="text-center">
            <img src="<?php echo e($pemenang->foto_url); ?>" 
                 alt="<?php echo e($pemenang->takmir->nama); ?>"
                 class="w-32 h-32 rounded-full object-cover border-4 border-white mx-auto mb-4">
            <h3 class="text-2xl font-bold"><?php echo e($pemenang->takmir->nama); ?></h3>
            <p class="text-lg"><?php echo e($pemenang->takmir->jabatan); ?></p>
            <div class="mt-4 text-3xl font-bold">
                <?php echo e($pemenang->votes_count); ?> Suara (<?php echo e(number_format($pemenang->persentase, 1)); ?>%)
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hasil per Kandidat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <?php $__currentLoopData = $kandidat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden <?php echo e($index === 0 && $pemilihan->isSelesai() ? 'ring-4 ring-yellow-400' : ''); ?>">
            <!-- Badge Peringkat -->
            <div class="bg-gradient-to-r <?php echo e($index === 0 ? 'from-yellow-500 to-yellow-600' : ($index === 1 ? 'from-gray-400 to-gray-500' : 'from-orange-600 to-orange-700')); ?> text-white text-center py-3">
                <div class="text-sm font-medium">
                    <?php if($index === 0): ?>
                        <i class="fas fa-trophy mr-1"></i>Peringkat 1
                    <?php elseif($index === 1): ?>
                        <i class="fas fa-medal mr-1"></i>Peringkat 2
                    <?php elseif($index === 2): ?>
                        <i class="fas fa-award mr-1"></i>Peringkat 3
                    <?php else: ?>
                        Peringkat <?php echo e($index + 1); ?>

                    <?php endif; ?>
                </div>
                <div class="text-3xl font-bold">Nomor <?php echo e($k->nomor_urut); ?></div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Foto -->
                <div class="flex justify-center mb-4">
                    <img src="<?php echo e($k->foto_url); ?>" 
                         alt="<?php echo e($k->takmir->nama); ?>"
                         class="w-28 h-28 rounded-full object-cover border-4 border-gray-100">
                </div>

                <!-- Nama & Jabatan -->
                <div class="text-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800"><?php echo e($k->takmir->nama); ?></h3>
                    <p class="text-gray-600 text-sm"><?php echo e($k->takmir->jabatan); ?></p>
                </div>

                <!-- Statistik Suara -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700 font-medium">Total Suara:</span>
                        <span class="text-2xl font-bold text-blue-600"><?php echo e($k->votes_count); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-medium">Persentase:</span>
                        <span class="text-2xl font-bold text-green-600"><?php echo e(number_format($k->persentase, 1)); ?>%</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-6 mb-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold transition-all duration-500"
                         style="width: <?php echo e($k->persentase); ?>%">
                        <?php echo e(number_format($k->persentase, 1)); ?>%
                    </div>
                </div>

                <!-- Visi Misi (Collapsible) -->
                <details class="mt-4">
                    <summary class="cursor-pointer text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        <i class="fas fa-chevron-down mr-1"></i>Lihat Visi & Misi
                    </summary>
                    <div class="mt-3 pl-4 border-l-2 border-blue-300">
                        <div class="mb-2">
                            <h4 class="font-semibold text-gray-700 text-sm mb-1">
                                <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>Visi
                            </h4>
                            <p class="text-xs text-gray-600"><?php echo e($k->visi); ?></p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700 text-sm mb-1">
                                <i class="fas fa-tasks text-green-500 mr-1"></i>Misi
                            </h4>
                            <div class="text-xs text-gray-600 whitespace-pre-line"><?php echo e($k->misi); ?></div>
                        </div>
                    </div>
                </details>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Grafik Bar Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-chart-bar text-blue-600 mr-3"></i>
            Perbandingan Perolehan Suara
        </h3>
        <div class="space-y-4">
            <?php $__currentLoopData = $kandidat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-gray-700">
                        No. <?php echo e($k->nomor_urut); ?> - <?php echo e($k->takmir->nama); ?>

                    </span>
                    <span class="text-sm font-bold text-gray-800">
                        <?php echo e($k->votes_count); ?> suara (<?php echo e(number_format($k->persentase, 1)); ?>%)
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                    <div class="h-8 rounded-full flex items-center px-3 text-white font-bold transition-all duration-500 <?php echo e($loop->index === 0 ? 'bg-gradient-to-r from-yellow-500 to-yellow-600' : 'bg-gradient-to-r from-blue-500 to-blue-600'); ?>"
                         style="width: <?php echo e($k->persentase); ?>%">
                        <?php if($k->persentase > 15): ?>
                            <?php echo e(number_format($k->persentase, 1)); ?>%
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <?php else: ?>
    <!-- Belum Ada Suara -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-8 text-center">
        <i class="fas fa-info-circle text-yellow-600 text-4xl mb-3"></i>
        <p class="text-yellow-800 text-lg font-medium">
            Belum ada suara yang masuk untuk pemilihan ini.
        </p>
    </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="flex justify-center gap-4">
        <?php if($pemilihan->isBerlangsung() && !$pemilihan->userHasVoted(auth()->id())): ?>
        <a href="<?php echo e(route('takmir.pemilihan.vote', $pemilihan->id)); ?>" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
            <i class="fas fa-vote-yea mr-2"></i>
            Gunakan Hak Suara Anda
        </a>
        <?php elseif($pemilihan->isBerlangsung() && $pemilihan->userHasVoted(auth()->id())): ?>
        <div class="inline-flex items-center px-6 py-3 bg-green-100 text-green-800 font-bold rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            Anda Sudah Memberikan Suara
        </div>
        <?php endif; ?>
        
        <a href="<?php echo e(route('takmir.index')); ?>" 
           class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        
        <button onclick="window.location.reload()" 
                class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition duration-200">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh Hasil
        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\!KULIAH\!SEM7\menpro\manajemen_masjid\resources\views/modules/takmir/pemilihan/hasil.blade.php ENDPATH**/ ?>