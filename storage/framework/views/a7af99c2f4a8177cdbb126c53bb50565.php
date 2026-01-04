

<?php $__env->startSection('title', 'Pemilihan Ketua DKM'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-2"><?php echo e($pemilihan->judul); ?></h1>
        <p class="text-gray-600 text-lg"><?php echo e($pemilihan->deskripsi); ?></p>
        
        <div class="mt-4 flex justify-center items-center gap-4 text-sm">
            <div class="flex items-center">
                <i class="fas fa-calendar text-blue-600 mr-2"></i>
                <span><?php echo e($pemilihan->tanggal_mulai->format('d M Y')); ?> - <?php echo e($pemilihan->tanggal_selesai->format('d M Y')); ?></span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-vote-yea text-green-600 mr-2"></i>
                <span class="font-semibold">Pemilihan Sedang Berlangsung</span>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Petunjuk Pemilihan:</h3>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                    <li>Pilih salah satu kandidat dengan klik tombol "Pilih Kandidat"</li>
                    <li>Setiap pemilih hanya bisa memberikan 1 suara</li>
                    <li>Pastikan pilihan Anda sudah benar sebelum menekan tombol</li>
                    <li>Suara yang sudah diberikan tidak dapat diubah</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Kandidat Cards -->
    <form method="POST" action="<?php echo e(route('takmir.pemilihan.submitVote', $pemilihan->id)); ?>" id="voteForm">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="kandidat_id" id="selectedKandidat" required>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <?php $__currentLoopData = $pemilihan->kandidat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kandidat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 kandidat-card" data-kandidat-id="<?php echo e($kandidat->id); ?>">
                <!-- Nomor Urut Badge -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center py-3">
                    <div class="text-sm font-medium">Kandidat Nomor</div>
                    <div class="text-4xl font-bold"><?php echo e($kandidat->nomor_urut); ?></div>
                </div>

                <!-- Foto -->
                <div class="p-6">
                    <div class="flex justify-center mb-4">
                        <img src="<?php echo e($kandidat->foto_url); ?>" 
                             alt="<?php echo e($kandidat->takmir->nama); ?>" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-blue-100">
                    </div>

                    <!-- Nama & Jabatan -->
                    <div class="text-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800"><?php echo e($kandidat->takmir->nama); ?></h3>
                        <p class="text-gray-600 text-sm"><?php echo e($kandidat->takmir->jabatan); ?></p>
                    </div>

                    <!-- Visi -->
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Visi
                        </h4>
                        <p class="text-sm text-gray-600 leading-relaxed"><?php echo e($kandidat->visi); ?></p>
                    </div>

                    <!-- Misi -->
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-tasks text-green-500 mr-2"></i>Misi
                        </h4>
                        <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line"><?php echo e($kandidat->misi); ?></div>
                    </div>

                    <!-- Button Pilih -->
                    <button type="button" 
                            class="w-full btn-pilih bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200 flex items-center justify-center"
                            data-kandidat-id="<?php echo e($kandidat->id); ?>"
                            data-kandidat-nama="<?php echo e($kandidat->takmir->nama); ?>"
                            data-nomor-urut="<?php echo e($kandidat->nomor_urut); ?>">
                        <i class="fas fa-check-circle mr-2"></i>
                        Pilih Kandidat Nomor <?php echo e($kandidat->nomor_urut); ?>

                    </button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </form>

    <!-- Back Button -->
    <div class="text-center">
        <a href="<?php echo e(route('takmir.index')); ?>" class="inline-flex items-center text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Halaman Utama
        </a>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <i class="fas fa-vote-yea text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Konfirmasi Pilihan Anda</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-600">
                    Anda akan memilih:<br>
                    <span class="font-bold text-gray-800 text-lg" id="selectedNama"></span><br>
                    <span class="text-gray-500">(Kandidat Nomor <span id="selectedNomor"></span>)</span>
                </p>
                <p class="text-sm text-red-600 mt-3 font-medium">
                    Pilihan tidak dapat diubah setelah dikonfirmasi!
                </p>
            </div>
            <div class="flex gap-3 px-4 py-3">
                <button id="cancelBtn" type="button"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
                <button id="confirmBtn" type="button"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Ya, Saya Yakin
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    const confirmModal = document.getElementById('confirmModal');
    const selectedKandidatInput = document.getElementById('selectedKandidat');
    const voteForm = document.getElementById('voteForm');
    const btnPilihList = document.querySelectorAll('.btn-pilih');
    
    btnPilihList.forEach(btn => {
        btn.addEventListener('click', function() {
            const kandidatId = this.dataset.kandidatId;
            const kandidatNama = this.dataset.kandidatNama;
            const nomorUrut = this.dataset.nomorUrut;
            
            // Set data ke modal
            document.getElementById('selectedNama').textContent = kandidatNama;
            document.getElementById('selectedNomor').textContent = nomorUrut;
            selectedKandidatInput.value = kandidatId;
            
            // Show modal
            confirmModal.classList.remove('hidden');
        });
    });
    
    // Cancel button
    document.getElementById('cancelBtn').addEventListener('click', function() {
        confirmModal.classList.add('hidden');
        selectedKandidatInput.value = '';
    });
    
    // Confirm button - submit form
    document.getElementById('confirmBtn').addEventListener('click', function() {
        if (selectedKandidatInput.value) {
            voteForm.submit();
        }
    });
    
    // Close modal on outside click
    confirmModal.addEventListener('click', function(e) {
        if (e.target === confirmModal) {
            confirmModal.classList.add('hidden');
            selectedKandidatInput.value = '';
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\!KULIAH\!SEM7\menpro\manajemen_masjid\resources\views/modules/takmir/pemilihan/vote.blade.php ENDPATH**/ ?>