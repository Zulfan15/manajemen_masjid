

<?php $__env->startSection('title', 'Struktur Organisasi Takmir'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-sitemap text-blue-600 mr-2"></i>Struktur Organisasi Takmir
                </h1>
                <p class="text-gray-600 mt-2">Visualisasi hierarki kepengurusan masjid</p>
            </div>
            <div class="flex gap-2">
                <button onclick="printOrgChart()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button onclick="downloadOrgChart()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-download mr-2"></i>Download
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="<?php echo e(route('takmir.struktur-organisasi')); ?>" class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-1"></i>Filter Periode
                </label>
                <select name="periode" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="">Semua Periode</option>
                    <?php $__currentLoopData = $periodeList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($periode); ?>" <?php echo e(request('periode') == $periode ? 'selected' : ''); ?>>
                            <?php echo e($periode); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>Status
                </label>
                <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="non-aktif" <?php echo e(request('status') == 'non-aktif' ? 'selected' : ''); ?>>Non-Aktif</option>
                </select>
            </div>
            <?php if(request('periode') || request('status')): ?>
                <a href="<?php echo e(route('takmir.struktur-organisasi')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Organizational Chart -->
    <div class="bg-white rounded-lg shadow-md p-8" id="orgChartContainer">
        <div class="org-chart-wrapper">
            <?php if($ketua): ?>
                <!-- Level 1: Ketua (DKM) -->
                <div class="org-level level-1">
                    <div class="org-card ketua">
                        <?php if($ketua->foto): ?>
                            <img src="<?php echo e(asset('storage/' . $ketua->foto)); ?>" alt="<?php echo e($ketua->nama); ?>" class="org-photo">
                        <?php else: ?>
                            <div class="org-photo-placeholder">
                                <i class="fas fa-user text-3xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="org-info">
                            <div class="org-jabatan"><?php echo e($ketua->jabatan); ?></div>
                            <div class="org-nama"><?php echo e($ketua->nama); ?></div>
                            <?php if($ketua->email): ?>
                                <div class="org-contact">
                                    <i class="fas fa-envelope text-xs"></i> <?php echo e($ketua->email); ?>

                                </div>
                            <?php endif; ?>
                            <?php if($ketua->telepon): ?>
                                <div class="org-contact">
                                    <i class="fas fa-phone text-xs"></i> <?php echo e($ketua->telepon); ?>

                                </div>
                            <?php endif; ?>
                            <div class="org-status <?php echo e($ketua->is_active ? 'status-active' : 'status-inactive'); ?>">
                                <?php echo e($ketua->is_active ? 'Aktif' : 'Non-Aktif'); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connector Line -->
                <div class="org-connector vertical"></div>

                <!-- Level 2: Wakil Ketua -->
                <?php if($wakilKetua): ?>
                    <div class="org-level level-2">
                        <div class="org-card wakil">
                            <?php if($wakilKetua->foto): ?>
                                <img src="<?php echo e(asset('storage/' . $wakilKetua->foto)); ?>" alt="<?php echo e($wakilKetua->nama); ?>" class="org-photo">
                            <?php else: ?>
                                <div class="org-photo-placeholder">
                                    <i class="fas fa-user text-3xl"></i>
                                </div>
                            <?php endif; ?>
                            <div class="org-info">
                                <div class="org-jabatan"><?php echo e($wakilKetua->jabatan); ?></div>
                                <div class="org-nama"><?php echo e($wakilKetua->nama); ?></div>
                                <?php if($wakilKetua->email): ?>
                                    <div class="org-contact">
                                        <i class="fas fa-envelope text-xs"></i> <?php echo e($wakilKetua->email); ?>

                                    </div>
                                <?php endif; ?>
                                <?php if($wakilKetua->telepon): ?>
                                    <div class="org-contact">
                                        <i class="fas fa-phone text-xs"></i> <?php echo e($wakilKetua->telepon); ?>

                                    </div>
                                <?php endif; ?>
                                <div class="org-status <?php echo e($wakilKetua->is_active ? 'status-active' : 'status-inactive'); ?>">
                                    <?php echo e($wakilKetua->is_active ? 'Aktif' : 'Non-Aktif'); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Connector Line -->
                    <div class="org-connector vertical"></div>
                <?php endif; ?>

                <!-- Level 3: Sekretaris & Bendahara -->
                <?php if($sekretaris || $bendahara): ?>
                    <div class="org-level level-3">
                        <div class="org-row">
                            <?php if($sekretaris): ?>
                                <div class="org-card sekretaris">
                                    <?php if($sekretaris->foto): ?>
                                        <img src="<?php echo e(asset('storage/' . $sekretaris->foto)); ?>" alt="<?php echo e($sekretaris->nama); ?>" class="org-photo">
                                    <?php else: ?>
                                        <div class="org-photo-placeholder">
                                            <i class="fas fa-user text-2xl"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="org-info">
                                        <div class="org-jabatan"><?php echo e($sekretaris->jabatan); ?></div>
                                        <div class="org-nama"><?php echo e($sekretaris->nama); ?></div>
                                        <?php if($sekretaris->email): ?>
                                            <div class="org-contact">
                                                <i class="fas fa-envelope text-xs"></i> <?php echo e($sekretaris->email); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="org-status <?php echo e($sekretaris->is_active ? 'status-active' : 'status-inactive'); ?>">
                                            <?php echo e($sekretaris->is_active ? 'Aktif' : 'Non-Aktif'); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($bendahara): ?>
                                <div class="org-card bendahara">
                                    <?php if($bendahara->foto): ?>
                                        <img src="<?php echo e(asset('storage/' . $bendahara->foto)); ?>" alt="<?php echo e($bendahara->nama); ?>" class="org-photo">
                                    <?php else: ?>
                                        <div class="org-photo-placeholder">
                                            <i class="fas fa-user text-2xl"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="org-info">
                                        <div class="org-jabatan"><?php echo e($bendahara->jabatan); ?></div>
                                        <div class="org-nama"><?php echo e($bendahara->nama); ?></div>
                                        <?php if($bendahara->email): ?>
                                            <div class="org-contact">
                                                <i class="fas fa-envelope text-xs"></i> <?php echo e($bendahara->email); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="org-status <?php echo e($bendahara->is_active ? 'status-active' : 'status-inactive'); ?>">
                                            <?php echo e($bendahara->is_active ? 'Aktif' : 'Non-Aktif'); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Connector Line -->
                    <?php if($pengurusList->isNotEmpty()): ?>
                        <div class="org-connector vertical"></div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Level 4: Pengurus -->
                <?php if($pengurusList->isNotEmpty()): ?>
                    <div class="org-level level-4">
                        <div class="org-row pengurus-row">
                            <?php $__currentLoopData = $pengurusList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengurus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="org-card pengurus">
                                    <?php if($pengurus->foto): ?>
                                        <img src="<?php echo e(asset('storage/' . $pengurus->foto)); ?>" alt="<?php echo e($pengurus->nama); ?>" class="org-photo-small">
                                    <?php else: ?>
                                        <div class="org-photo-placeholder-small">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="org-info">
                                        <div class="org-jabatan-small">Pengurus</div>
                                        <div class="org-nama-small"><?php echo e($pengurus->nama); ?></div>
                                        <div class="org-status-small <?php echo e($pengurus->is_active ? 'status-active' : 'status-inactive'); ?>">
                                            <?php echo e($pengurus->is_active ? 'Aktif' : 'Non-Aktif'); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-16">
                    <i class="fas fa-sitemap text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Data Takmir</h3>
                    <p class="text-gray-500">Silakan tambahkan data takmir terlebih dahulu</p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('takmir.create')): ?>
                        <a href="<?php echo e(route('takmir.create')); ?>" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-plus mr-2"></i>Tambah Takmir
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Legend -->
    <?php if($ketua): ?>
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle mr-2"></i>Keterangan
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-100 border-2 border-blue-600 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Ketua (DKM)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-purple-100 border-2 border-purple-600 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Wakil Ketua</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-100 border-2 border-green-600 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Sekretaris</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-100 border-2 border-yellow-600 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Bendahara</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Status Aktif</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Status Non-Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Styles -->
<style>
    .org-chart-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        min-height: 400px;
    }

    .org-level {
        margin: 20px 0;
        display: flex;
        justify-content: center;
    }

    .org-row {
        display: flex;
        gap: 40px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .pengurus-row {
        gap: 20px;
        max-width: 1000px;
    }

    .org-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        min-width: 250px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .org-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .org-card.ketua {
        border: 3px solid #2563eb;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    }

    .org-card.wakil {
        border: 3px solid #9333ea;
        background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
    }

    .org-card.sekretaris {
        border: 3px solid #16a34a;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }

    .org-card.bendahara {
        border: 3px solid #eab308;
        background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%);
    }

    .org-card.pengurus {
        border: 2px solid #6b7280;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        min-width: 180px;
        padding: 15px;
    }

    .org-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 15px;
        border: 4px solid white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .org-photo-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: #9ca3af;
        border: 4px solid white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .org-photo-small {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 10px;
        border: 3px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .org-photo-placeholder-small {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: #9ca3af;
        border: 3px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .org-info {
        text-align: center;
    }

    .org-jabatan {
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .org-jabatan-small {
        font-size: 11px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 3px;
    }

    .org-nama {
        font-size: 18px;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .org-nama-small {
        font-size: 14px;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .org-contact {
        font-size: 12px;
        color: #6b7280;
        margin: 3px 0;
    }

    .org-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 8px;
    }

    .org-status-small {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 600;
        margin-top: 5px;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .org-connector {
        width: 2px;
        height: 40px;
        background: #cbd5e1;
    }

    .org-connector.vertical {
        width: 2px;
        height: 30px;
        background: linear-gradient(180deg, #cbd5e1 0%, #e2e8f0 100%);
    }

    @media print {
        body * {
            visibility: hidden;
        }
        #orgChartContainer, #orgChartContainer * {
            visibility: visible;
        }
        #orgChartContainer {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .org-card:hover {
            transform: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    }

    @media (max-width: 768px) {
        .org-card {
            min-width: 200px;
        }
        .org-row {
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
    }
</style>

<!-- Scripts -->
<script>
    function printOrgChart() {
        window.print();
    }

    function downloadOrgChart() {
        // Simple approach: trigger print dialog with save as PDF option
        alert('Gunakan Print dialog dan pilih "Save as PDF" untuk menyimpan bagan organisasi.');
        window.print();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\Downloads\Manpro Masjid\resources\views/modules/takmir/struktur-organisasi.blade.php ENDPATH**/ ?>