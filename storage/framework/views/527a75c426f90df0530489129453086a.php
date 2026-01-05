

<?php $__env->startSection('title', 'Pemasukan Masjid - CRUD Lengkap'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3 px-md-4 py-4">
    <!-- HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="header-section">
                <div class="header-content">
                    <div class="header-icon-wrapper">
                        <div class="header-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="header-text">
                        <h1 class="page-title">Data Pemasukan Masjid</h1>
                        <p class="page-subtitle">
                            <?php if(auth()->user()->role == 'jamaah'): ?>
                                <i class="fas fa-user me-2"></i>Menampilkan data pemasukan yang Anda input
                            <?php else: ?>
                                <i class="fas fa-chart-pie me-2"></i>Manajemen data pemasukan masjid
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="header-actions">
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus-circle"></i> Tambah Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- INFO STATS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Total Pemasukan</p>
                    <h3 class="stat-value">Rp <?php echo e(number_format($data->sum('jumlah'), 0, ',', '.')); ?></h3>
                    <div class="stat-badge">
                        <i class="fas fa-receipt"></i> <?php echo e($data->count()); ?> Transaksi
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Rata-rata Transaksi</p>
                    <h3 class="stat-value">
                        <?php if($data->count() > 0): ?>
                            Rp <?php echo e(number_format($data->avg('jumlah'), 0, ',', '.')); ?>

                        <?php else: ?>
                            Rp 0
                        <?php endif; ?>
                    </h3>
                    <div class="stat-badge">
                        <i class="fas fa-chart-line"></i> Analisis
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Bulan Ini</p>
                    <h3 class="stat-value">Rp <?php echo e(number_format($bulanIni, 0, ',', '.')); ?></h3>
                    <div class="stat-badge">
                        <i class="fas fa-calendar"></i> <?php echo e(date('F Y')); ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Status Akun</p>
                    <?php if(auth()->user()->role == 'jamaah'): ?>
                        <div class="user-badge user-badge-info">
                            <i class="fas fa-user"></i> Mode Jamaah
                        </div>
                    <?php else: ?>
                        <div class="user-badge user-badge-success">
                            <i class="fas fa-user-shield"></i> Administrator
                        </div>
                    <?php endif; ?>
                    <div class="stat-badge mt-2">
                        <i class="fas fa-user"></i> <?php echo e(auth()->user()->name); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERT NOTIFICATION -->
    <?php if(session('success')): ?>
    <div class="custom-alert custom-alert-success">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Berhasil!</strong>
            <span><?php echo e(session('success')); ?></span>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="custom-alert custom-alert-danger">
        <div class="alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
            <strong>Terjadi Kesalahan!</strong>
            <span><?php echo e(session('error')); ?></span>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <!-- REKAP PER JENIS -->
    <div class="data-card mb-4">
        <div class="data-card-header">
            <div class="data-card-title">
                <i class="fas fa-chart-pie"></i>
                <span>Rekap Per Jenis Pemasukan</span>
            </div>
        </div>
        <div class="data-card-body">
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th class="text-center">Jumlah Transaksi</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $totalAll = $data->sum('jumlah');
                            $jenisData = [
                                'Donasi' => ['color' => 'primary', 'icon' => 'fa-hands-helping'],
                                'Zakat' => ['color' => 'success', 'icon' => 'fa-hand-holding-heart'],
                                'Infak' => ['color' => 'info', 'icon' => 'fa-coins'],
                                'Sedekah' => ['color' => 'warning', 'icon' => 'fa-gift'],
                                'Sewa' => ['color' => 'secondary', 'icon' => 'fa-building'],
                                'Usaha' => ['color' => 'dark', 'icon' => 'fa-store'],
                                'Lain-lain' => ['color' => 'danger', 'icon' => 'fa-ellipsis-h']
                            ];
                        ?>
                        <?php $__currentLoopData = $jenisData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $filtered = $data->where('jenis', $jenis);
                                $total = $filtered->sum('jumlah');
                                $count = $filtered->count();
                                $percentage = $totalAll > 0 ? ($total / $totalAll) * 100 : 0;
                            ?>
                            <?php if($count > 0): ?>
                            <tr>
                                <td>
                                    <span class="type-badge type-badge-<?php echo e($config['color']); ?>">
                                        <i class="fas <?php echo e($config['icon']); ?>"></i> <?php echo e($jenis); ?>

                                    </span>
                                </td>
                                <td class="text-center"><?php echo e($count); ?></td>
                                <td class="text-end"><strong>Rp <?php echo e(number_format($total, 0, ',', '.')); ?></strong></td>
                                <td class="text-center">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-<?php echo e($config['color']); ?>" 
                                             style="width: <?php echo e($percentage); ?>%">
                                            <?php echo e(number_format($percentage, 1)); ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <th>Total Keseluruhan</th>
                            <th class="text-center"><?php echo e($data->count()); ?></th>
                            <th class="text-end">Rp <?php echo e(number_format($totalAll, 0, ',', '.')); ?></th>
                            <th class="text-center">100%</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE -->
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-title">
                <i class="fas fa-list-ul"></i>
                <span>Daftar Transaksi Pemasukan</span>
            </div>
            <div class="data-card-actions">
                <div class="data-badge">
                    <i class="fas fa-database"></i> <?php echo e($data->count()); ?> Data
                </div>
            </div>
        </div>
        <div class="data-card-body">
            <div class="table-container">
                <table class="custom-table" id="dataTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="60">#</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Sumber</th>
                            <th class="text-end">Jumlah</th>
                            <th>Keterangan</th>
                            <?php if(auth()->user()->role != 'jamaah'): ?>
                                <th>Input Oleh</th>
                            <?php endif; ?>
                            <th class="text-center" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr data-tanggal="<?php echo e($item->tanggal); ?>" data-id="<?php echo e($item->id); ?>">
                            <td class="text-center">
                                <span class="row-number"><?php echo e($loop->iteration); ?></span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <span class="date-primary"><?php echo e(\Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y')); ?></span>
                                    <small class="date-secondary">
                                        <i class="far fa-clock"></i> <?php echo e($item->created_at->diffForHumans()); ?>

                                    </small>
                                </div>
                            </td>
                            <td>
                                <?php
                                    $badgeConfig = [
                                        'Donasi' => ['color' => 'primary', 'icon' => 'fa-hands-helping'],
                                        'Zakat' => ['color' => 'success', 'icon' => 'fa-hand-holding-heart'],
                                        'Infak' => ['color' => 'info', 'icon' => 'fa-coins'],
                                        'Sedekah' => ['color' => 'warning', 'icon' => 'fa-gift'],
                                        'Sewa' => ['color' => 'secondary', 'icon' => 'fa-building'],
                                        'Usaha' => ['color' => 'dark', 'icon' => 'fa-store'],
                                        'Lain-lain' => ['color' => 'danger', 'icon' => 'fa-ellipsis-h']
                                    ];
                                    $config = $badgeConfig[$item->jenis] ?? ['color' => 'primary', 'icon' => 'fa-money-bill'];
                                ?>
                                <span class="type-badge type-badge-<?php echo e($config['color']); ?>" data-jenis="<?php echo e($item->jenis); ?>">
                                    <i class="fas <?php echo e($config['icon']); ?>"></i> <?php echo e($item->jenis); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($item->sumber): ?>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="user-name" data-sumber="<?php echo e($item->sumber); ?>"><?php echo e($item->sumber); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted" data-sumber="">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="amount-info">
                                    <span class="amount-primary" data-jumlah="<?php echo e($item->jumlah); ?>">Rp <?php echo e(number_format($item->jumlah, 0, ',', '.')); ?></span>
                                    <?php if($item->jumlah >= 1000): ?>
                                        <small class="amount-secondary"><?php echo e(number_format($item->jumlah / 1000, 1)); ?>K</small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if($item->keterangan): ?>
                                    <div class="note-info">
                                        <i class="fas fa-sticky-note"></i>
                                        <span class="note-text" data-keterangan="<?php echo e($item->keterangan); ?>" data-bs-toggle="tooltip" title="<?php echo e($item->keterangan); ?>">
                                            <?php echo e(Str::limit($item->keterangan, 40)); ?>

                                        </span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted" data-keterangan="">—</span>
                                <?php endif; ?>
                            </td>
                            <?php if(auth()->user()->role != 'jamaah'): ?>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar user-avatar-info">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name"><?php echo e($item->user->name ?? '—'); ?></div>
                                            <small class="user-role"><?php echo e($item->user->role ?? '—'); ?></small>
                                        </div>
                                    </div>
                                </td>
                            <?php endif; ?>
                            <td class="text-center">
                                <div class="action-buttons">
                                    
                                    <?php if(($item->status ?? 'pending') === 'pending' && auth()->user()->canManageKeuangan()): ?>
                                        <button type="button" 
                                                class="btn-action btn-action-success" 
                                                onclick="verifikasiTransaksi(<?php echo e($item->id); ?>)"
                                                title="Verifikasi Transaksi">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php elseif(($item->status ?? 'pending') === 'verified'): ?>
                                        <span class="badge-verified" title="Sudah Terverifikasi pada <?php echo e($item->verified_at ? $item->verified_at->format('d/m/Y H:i') : '-'); ?>">
                                            <i class="fas fa-check-circle"></i> Verified
                                        </span>
                                    <?php elseif(($item->status ?? 'pending') === 'rejected'): ?>
                                        <span class="badge-rejected" title="Ditolak: <?php echo e($item->alasan_tolak ?? '-'); ?>">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    <?php endif; ?>

                                    
                                    <button type="button" 
                                            class="btn-action btn-action-warning" 
                                            onclick="editData(<?php echo e($item->id); ?>, '<?php echo e($item->tanggal); ?>', '<?php echo e($item->jenis); ?>', '<?php echo e(addslashes($item->sumber ?? '')); ?>', <?php echo e($item->jumlah); ?>, '<?php echo e(addslashes($item->keterangan ?? '')); ?>')"
                                            title="Edit Data"
                                            <?php if(auth()->user()->role == 'jamaah'): ?> disabled <?php endif; ?>>
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    
                                    <button type="button" 
                                            class="btn-action btn-action-danger" 
                                            onclick="deleteData(<?php echo e($item->id); ?>)"
                                            title="Hapus Data"
                                            <?php if(auth()->user()->role == 'jamaah'): ?> disabled <?php endif; ?>>
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    
                                    <?php if(($item->status ?? 'pending') === 'pending' && auth()->user()->canManageKeuangan()): ?>
                                        <button type="button" 
                                                class="btn-action btn-action-dark" 
                                                onclick="tolakTransaksi(<?php echo e($item->id); ?>)"
                                                title="Tolak Transaksi">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e(auth()->user()->role == 'jamaah' ? 7 : 8); ?>" class="text-center">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h4 class="empty-title">Belum Ada Data Pemasukan</h4>
                                    <p class="empty-text">Mulai tambahkan data pemasukan pertama Anda</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CREATE MODAL -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle"></i> Tambah Pemasukan Baru
                </h5>
                <button type="button" class="btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createForm" action="<?php echo e(route('pemasukan.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-layer-group"></i> Jenis Pemasukan <span class="required">*</span>
                                </label>
                                <select class="form-control" id="jenis" name="jenis" required>
                                    <option value="" selected disabled>— Pilih Jenis —</option>
                                    <option value="Donasi">💝 Donasi</option>
                                    <option value="Zakat">🕌 Zakat</option>
                                    <option value="Infak">💰 Infak</option>
                                    <option value="Sedekah">🎁 Sedekah</option>
                                    <option value="Sewa">🏢 Sewa</option>
                                    <option value="Usaha">🏪 Usaha</option>
                                    <option value="Lain-lain">📋 Lain-lain</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-money-bill-wave"></i> Jumlah (Rp) <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                           min="1000" step="1000" placeholder="0" required>
                                </div>
                                <small class="form-text">Masukkan jumlah tanpa titik atau koma</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Tanggal <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                       value="<?php echo e(date('Y-m-d')); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i> Sumber
                                </label>
                                <input type="text" class="form-control" id="sumber" name="sumber" 
                                       placeholder="Nama donatur atau sumber dana">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-sticky-note"></i> Keterangan
                                </label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                          placeholder="Tambahkan catatan atau keterangan tambahan..."></textarea>
                                <small class="form-text">Maksimal 500 karakter</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="createSubmitBtn">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-warning">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i> Edit Pemasukan
                </h5>
                <button type="button" class="btn-close-dark" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Jenis Pemasukan <span class="required">*</span></label>
                                <select class="form-control" name="jenis" id="edit_jenis" required>
                                    <option value="" disabled>— Pilih Jenis —</option>
                                    <option value="Donasi">💝 Donasi</option>
                                    <option value="Zakat">🕌 Zakat</option>
                                    <option value="Infak">💰 Infak</option>
                                    <option value="Sedekah">🎁 Sedekah</option>
                                    <option value="Sewa">🏢 Sewa</option>
                                    <option value="Usaha">🏪 Usaha</option>
                                    <option value="Lain-lain">📋 Lain-lain</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Jumlah (Rp) <span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="jumlah" id="edit_jumlah"
                                           min="1000" step="1000" placeholder="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal <span class="required">*</span></label>
                                <input type="date" class="form-control" name="tanggal" id="edit_tanggal" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Sumber</label>
                                <input type="text" class="form-control" name="sumber" id="edit_sumber"
                                       placeholder="Nama donatur atau sumber dana">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="edit_keterangan" rows="3"
                                          placeholder="Tambahkan catatan atau keterangan tambahan..."></textarea>
                                <small class="form-text">Maksimal 500 karakter</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning" id="editSubmitBtn">
                        <i class="fas fa-save"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* General Styles */
:root {
    --primary: #0d6efd;
    --success: #198754;
    --warning: #ffc107;
    --danger: #dc3545;
    --info: #0dcaf0;
    --secondary: #6c757d;
    --dark: #212529;
}

/* Header Section */
.header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon-wrapper {
    background: rgba(255, 255, 255, 0.2);
    padding: 20px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.header-icon {
    font-size: 2.5rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.page-subtitle {
    margin: 5px 0 0 0;
    opacity: 0.9;
}

.header-actions {
    display: flex;
    gap: 10px;
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 5px solid;
    display: flex;
    gap: 20px;
    align-items: center;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.stat-card-success { border-left-color: var(--success); }
.stat-card-primary { border-left-color: var(--primary); }
.stat-card-warning { border-left-color: var(--warning); }
.stat-card-info { border-left-color: var(--info); }

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.stat-card-success .stat-icon {
    background: rgba(25, 135, 84, 0.1);
    color: var(--success);
}

.stat-card-primary .stat-icon {
    background: rgba(13, 110, 253, 0.1);
    color: var(--primary);
}

.stat-card-warning .stat-icon {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
}

.stat-card-info .stat-icon {
    background: rgba(13, 202, 240, 0.1);
    color: var(--info);
}

.stat-content {
    flex: 1;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0 0 8px 0;
    font-weight: 500;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 10px 0;
    color: #212529;
}

.stat-badge {
    display: inline-block;
    padding: 5px 12px;
    background: #f8f9fa;
    border-radius: 20px;
    font-size: 0.85rem;
    color: #6c757d;
}

.user-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 1rem;
}

.user-badge-info {
    background: rgba(13, 202, 240, 0.1);
    color: var(--info);
}

.user-badge-success {
    background: rgba(25, 135, 84, 0.1);
    color: var(--success);
}

/* Custom Alert */
.custom-alert {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border-left: 5px solid;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.custom-alert-success {
    background: rgba(25, 135, 84, 0.1);
    border-left-color: var(--success);
    color: var(--success);
}

.custom-alert-danger {
    background: rgba(220, 53, 69, 0.1);
    border-left-color: var(--danger);
    color: var(--danger);
}

.alert-icon {
    font-size: 1.5rem;
}

.alert-content {
    flex: 1;
}

.alert-close {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.alert-close:hover {
    opacity: 1;
}

/* Data Card */
.data-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}

.data-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 30px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.data-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.2rem;
    font-weight: 600;
}

.data-card-actions {
    display: flex;
    gap: 10px;
}

.data-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    backdrop-filter: blur(10px);
}

.data-card-body {
    padding: 0;
}

/* Table Styles */
.table-container {
    overflow-x: auto;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
}

.custom-table thead {
    background: #f8f9fa;
}

.custom-table thead th {
    padding: 15px 20px;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.custom-table tbody td {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f3f5;
    vertical-align: middle;
}

.custom-table tbody tr {
    transition: background 0.2s;
}

.custom-table tbody tr:hover {
    background: #f8f9fa;
}

.row-number {
    display: inline-block;
    width: 30px;
    height: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    line-height: 30px;
    font-weight: 600;
}

/* Date Info */
.date-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.date-primary {
    font-weight: 600;
    color: #212529;
}

.date-secondary {
    color: #6c757d;
    font-size: 0.85rem;
}

/* Type Badge */
.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    white-space: nowrap;
}

.type-badge-primary {
    background: rgba(13, 110, 253, 0.1);
    color: var(--primary);
}

.type-badge-success {
    background: rgba(25, 135, 84, 0.1);
    color: var(--success);
}

.type-badge-info {
    background: rgba(13, 202, 240, 0.1);
    color: var(--info);
}

.type-badge-warning {
    background: rgba(255, 193, 7, 0.1);
    color: #d39e00;
}

.type-badge-secondary {
    background: rgba(108, 117, 125, 0.1);
    color: var(--secondary);
}

.type-badge-dark {
    background: rgba(33, 37, 41, 0.1);
    color: var(--dark);
}

.type-badge-danger {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.user-avatar-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
}

.user-name {
    font-weight: 500;
    color: #212529;
}

.user-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.user-role {
    color: #6c757d;
    font-size: 0.8rem;
}

/* Amount Info */
.amount-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
}

.amount-primary {
    font-weight: 700;
    color: var(--success);
    font-size: 1.1rem;
}

.amount-secondary {
    color: #6c757d;
    font-size: 0.85rem;
}

/* Note Info */
.note-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
}

.note-text {
    font-size: 0.9rem;
}

/* Badge Verified/Rejected */
.badge-verified {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    background: rgba(25, 135, 84, 0.1);
    color: var(--success);
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-rejected {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.9rem;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-action-success {
    background: var(--success);
    color: white;
}

.btn-action-warning {
    background: var(--warning);
    color: #212529;
}

.btn-action-danger {
    background: var(--danger);
    color: white;
}

.btn-action-dark {
    background: var(--dark);
    color: white;
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-title {
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-text {
    color: #adb5bd;
    margin: 0;
}

/* Modal Styles */
.modal-header-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.modal-header-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.btn-close-white,
.btn-close-dark {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.btn-close-dark {
    filter: none;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.required {
    color: var(--danger);
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 10px 15px;
    transition: all 0.2s;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.input-group-text {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-right: none;
    font-weight: 600;
}

.form-text {
    color: #6c757d;
    font-size: 0.85rem;
    margin-top: 5px;
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .header-section {
        padding: 20px;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .action-buttons {
        gap: 5px;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// =============================================================================
// 🎨 EDIT DATA FUNCTION
// =============================================================================
function editData(id, tanggal, jenis, sumber, jumlah, keterangan) {
    const form = document.getElementById('editForm');
    form.action = `/pemasukan/${id}`;
    
    document.getElementById('edit_tanggal').value = tanggal;
    document.getElementById('edit_jenis').value = jenis;
    document.getElementById('edit_sumber').value = sumber || '';
    document.getElementById('edit_jumlah').value = jumlah;
    document.getElementById('edit_keterangan').value = keterangan || '';
    
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

// =============================================================================
// 🗑️ DELETE DATA FUNCTION (WITH SWEETALERT)
// =============================================================================
function deleteData(id) {
    Swal.fire({
        title: '🗑️ Hapus Data?',
        html: `
            <p>Data yang dihapus <strong>dapat dikembalikan pada halaman history!</strong></p>
            <p class="text-muted small">Pastikan Anda yakin sebelum menghapus.</p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times"></i> Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger mx-1',
            cancelButton: 'btn btn-secondary mx-1'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                html: '<div class="spinner-border text-danger" role="status"></div>',
                allowOutsideClick: false,
                showConfirmButton: false
            });
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/pemasukan/${id}`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '<?php echo e(csrf_token()); ?>';
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// =============================================================================
// ✅ VERIFIKASI TRANSAKSI FUNCTION (DENGAN SWEETALERT2)
// =============================================================================
function verifikasiTransaksi(id) {
    Swal.fire({
        title: '✅ Verifikasi Transaksi?',
        html: `
            <div class="text-start">
                <p>Transaksi ini akan diverifikasi dan <strong>notifikasi email</strong> akan dikirim ke user.</p>
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle"></i> 
                    Pastikan data sudah benar sebelum verifikasi.
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check"></i> Ya, Verifikasi!',
        cancelButtonText: '<i class="fas fa-times"></i> Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-success mx-1',
            cancelButton: 'btn btn-secondary mx-1'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses Verifikasi...',
                html: `
                    <div class="d-flex flex-column align-items-center">
                        <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mb-0">Sedang memverifikasi transaksi dan mengirim email...</p>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });
            
            // Kirim request verifikasi
            fetch(`/pemasukan/${id}/verifikasi`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Verifikasi Berhasil!',
                        html: `
                            <div class="alert alert-success text-start">
                                ${data.message}
                            </div>
                            ${data.data ? `
                                <hr>
                                <div class="text-start">
                                    <h6 class="mb-3"><i class="fas fa-info-circle"></i> Detail Verifikasi:</h6>
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <td><strong>👤 Nama User:</strong></td>
                                            <td>${data.data.user_name}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>📧 Email Dikirim ke:</strong></td>
                                            <td>${data.data.email_sent_to}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>⏰ Waktu Verifikasi:</strong></td>
                                            <td>${data.data.verified_at}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>✍️ Diverifikasi Oleh:</strong></td>
                                            <td>${data.data.verified_by}</td>
                                        </tr>
                                    </table>
                                </div>
                            ` : ''}
                        `,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: '<i class="fas fa-sync-alt"></i> Reload Halaman',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
                    }).then(() => {
                        // Reload halaman untuk update tampilan
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Verifikasi Gagal!',
                        html: `
                            <div class="alert alert-danger text-start">
                                ${data.message || 'Terjadi kesalahan saat memverifikasi transaksi'}
                            </div>
                        `,
                        confirmButtonColor: '#dc3545',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '💥 Terjadi Kesalahan!',
                    html: `
                        <div class="alert alert-danger text-start">
                            <p class="mb-2"><strong>Gagal memverifikasi transaksi:</strong></p>
                            <code class="d-block p-2 bg-light text-danger">${error.message}</code>
                        </div>
                        <hr>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-lightbulb"></i> 
                            Silakan cek console browser (F12) untuk detail error atau cek file <code>storage/logs/laravel.log</code>
                        </p>
                    `,
                    confirmButtonColor: '#dc3545',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });
            });
        }
    });
}

// =============================================================================
// ❌ TOLAK TRANSAKSI FUNCTION (DENGAN SWEETALERT2)
// =============================================================================
function tolakTransaksi(id) {
    Swal.fire({
        title: '❌ Tolak Transaksi?',
        html: `
            <div class="text-start">
                <p class="mb-3">Silakan masukkan <strong>alasan penolakan</strong> yang akan dikirim ke user:</p>
                <textarea 
                    id="alasan_tolak" 
                    class="form-control" 
                    placeholder="Contoh: Data tidak lengkap, nominal tidak sesuai, bukti transfer tidak valid, dll..." 
                    rows="4"
                    style="resize: vertical; font-size: 0.95rem;"
                ></textarea>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Perhatian:</strong> Alasan ini akan dikirim ke email user.
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-ban"></i> Ya, Tolak Transaksi!',
        cancelButtonText: '<i class="fas fa-times"></i> Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger mx-1',
            cancelButton: 'btn btn-secondary mx-1'
        },
        buttonsStyling: false,
        preConfirm: () => {
            const alasan = document.getElementById('alasan_tolak').value.trim();
            
            if (!alasan) {
                Swal.showValidationMessage('⚠️ Alasan penolakan harus diisi!');
                return false;
            }
            
            if (alasan.length < 10) {
                Swal.showValidationMessage('⚠️ Alasan terlalu singkat! Minimal 10 karakter.');
                return false;
            }
            
            if (alasan.length > 500) {
                Swal.showValidationMessage('⚠️ Alasan terlalu panjang! Maksimal 500 karakter.');
                return false;
            }
            
            return alasan;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses Penolakan...',
                html: `
                    <div class="d-flex flex-column align-items-center">
                        <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mb-0">Sedang menolak transaksi dan mengirim notifikasi...</p>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });
            
            // Kirim request tolak
            fetch(`/pemasukan/${id}/tolak`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    alasan_tolak: result.value
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Transaksi Ditolak!',
                        html: `
                            <div class="alert alert-success text-start">
                                ${data.message}
                            </div>
                            <p class="text-muted mt-3">Email notifikasi penolakan telah dikirim ke user.</p>
                        `,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: '<i class="fas fa-sync-alt"></i> Reload Halaman',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Penolakan Gagal!',
                        html: `
                            <div class="alert alert-danger text-start">
                                ${data.message || 'Terjadi kesalahan'}
                            </div>
                        `,
                        confirmButtonColor: '#dc3545',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '💥 Terjadi Kesalahan!',
                    html: `
                        <div class="alert alert-danger text-start">
                            <p class="mb-2"><strong>Gagal menolak transaksi:</strong></p>
                            <code class="d-block p-2 bg-light text-danger">${error.message}</code>
                        </div>
                    `,
                    confirmButtonColor: '#dc3545',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });
            });
        }
    });
}

// =============================================================================
// 🎯 INITIALIZE TOOLTIPS & AUTO-HIDE ALERTS
// =============================================================================
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto hide custom alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.custom-alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\manajemen_masjid\manajemen_masjid\manajemen_masjid-main\resources\views/modules/keuangan/pemasukan/index.blade.php ENDPATH**/ ?>