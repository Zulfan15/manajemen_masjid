

<?php $__env->startSection('title', 'History Data Terhapus'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    
    <!-- HEADER -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-history text-primary"></i> History Data Terhapus</h2>
            <p class="text-muted">Kelola data pemasukan yang sudah dihapus</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo e(route('pemasukan.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- ALERTS -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- TABLE CARD -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-trash-restore"></i> Data Terhapus
                        <span class="badge bg-light text-dark ms-2"><?php echo e($deletedData->total()); ?></span>
                    </h5>
                </div>
                
                <?php if($deletedData->count() > 0): ?>
                <div class="col-md-6 text-end">
                    <form action="<?php echo e(route('history.restoreAll')); ?>" method="POST" class="d-inline"
                          onsubmit="return confirm('Kembalikan semua data (<?php echo e($deletedData->total()); ?> item)?')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-undo"></i> Restore Semua
                        </button>
                    </form>
                    
                    <form action="<?php echo e(route('history.resetAll')); ?>" method="POST" class="d-inline"
                          onsubmit="return confirm('⚠️ HAPUS PERMANEN SEMUA DATA?\n\nData tidak bisa dikembalikan!')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Hapus Semua
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card-body p-0">
            <?php if($deletedData->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Sumber</th>
                                <th class="text-end">Jumlah</th>
                                <th>Status</th>
                                <th>Dihapus Oleh</th>
                                <th>Dihapus Pada</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $deletedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($deletedData->firstItem() + $index); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($data->tanggal)->format('d/m/Y')); ?></td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e($data->jenis); ?></span>
                                    </td>
                                    <td><?php echo e($data->sumber ?? '-'); ?></td>
                                    <td class="text-end">
                                        <strong class="text-success">
                                            Rp <?php echo e(number_format($data->jumlah, 0, ',', '.')); ?>

                                        </strong>
                                    </td>
                                    <td>
                                        <?php if($data->status == 'verified'): ?>
                                            <span class="badge bg-success">✓ Verified</span>
                                        <?php elseif($data->status == 'rejected'): ?>
                                            <span class="badge bg-danger">✗ Rejected</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">⏳ Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($data->user): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle me-2" 
                                                     style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;">
                                                    <small><?php echo e(strtoupper(substr($data->user->name, 0, 1))); ?></small>
                                                </div>
                                                <div>
                                                    <div class="fw-bold small"><?php echo e($data->user->name); ?></div>
                                                    <small class="text-muted"><?php echo e($data->user->username); ?></small>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?php echo e($data->deleted_at->format('d/m/Y')); ?></div>
                                        <small class="text-muted"><?php echo e($data->deleted_at->format('H:i')); ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <!-- Restore Button -->
                                            <form action="<?php echo e(route('history.restore', $data->id)); ?>" 
                                                  method="POST"
                                                  onsubmit="return confirm('Kembalikan data ini?')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-success" title="Restore">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Delete Permanent Button -->
                                            <form action="<?php echo e(route('history.forceDelete', $data->id)); ?>" 
                                                  method="POST"
                                                  onsubmit="return confirm('⚠️ HAPUS PERMANEN?\n\nTidak bisa dikembalikan!')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger" title="Hapus Permanen">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Menampilkan <?php echo e($deletedData->firstItem()); ?> - <?php echo e($deletedData->lastItem()); ?> 
                                dari <?php echo e($deletedData->total()); ?> data
                            </small>
                        </div>
                        <div class="col-md-6">
                            <?php echo e($deletedData->links()); ?>

                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Data Terhapus</h5>
                    <p class="text-muted">Semua data masih aktif atau sudah dihapus permanen</p>
                    <a href="<?php echo e(route('pemasukan.index')); ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left"></i> Kembali ke Pemasukan
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto hide alerts
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\manajemen_masjid\manajemen_masjid\manajemen_masjid-main\resources\views/history/index.blade.php ENDPATH**/ ?>