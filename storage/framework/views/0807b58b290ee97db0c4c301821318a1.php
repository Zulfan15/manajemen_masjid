<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pengeluaran Masjid</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .total-row td { font-weight: bold; background-color: #fafafa; }
        .text-right { text-align: right; }
        .tanda-tangan { margin-top: 40px; float: right; text-align: center; width: 200px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENGELUARAN DANA MASJID</h1>
        <p>Periode: Bulan <?php echo e($bulan); ?> Tahun <?php echo e($tahun); ?></p>
        <p>Sistem Informasi Manajemen Masjid AMANAH</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">Kategori</th>
                <th style="width: 35%">Keterangan / Judul</th>
                <th style="width: 25%" class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pengeluaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')); ?></td>
                <td><?php echo e($item->kategori->nama_kategori); ?></td>
                <td>
                    <strong><?php echo e($item->judul_pengeluaran); ?></strong><br>
                    <span style="color: #666; font-size: 10px;"><?php echo e($item->deskripsi); ?></span>
                </td>
                <td class="text-right"><?php echo e(number_format($item->jumlah, 0, ',', '.')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data pengeluaran pada periode ini.</td>
            </tr>
            <?php endif; ?>
            
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL PENGELUARAN</td>
                <td class="text-right">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="tanda-tangan">
        <p>Bandung, <?php echo e($tanggal_cetak); ?></p>
        <br><br><br>
        <p><strong><?php echo e(auth()->user()->name); ?></strong></p>
        <p>Bendahara / Admin Keuangan</p>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\manajemen_masjid\resources\views/modules/keuangan/pengeluaran/cetak_pdf.blade.php ENDPATH**/ ?>