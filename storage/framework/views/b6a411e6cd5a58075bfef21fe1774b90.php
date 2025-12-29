<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kegiatan - <?php echo e($laporan->nama_kegiatan); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2d5016;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 20pt;
            color: #2d5016;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 16pt;
            color: #555;
            font-weight: normal;
        }
        .header p {
            font-size: 10pt;
            color: #777;
            margin-top: 5px;
        }
        .info-box {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            padding: 5px;
            font-weight: bold;
            color: #555;
        }
        .info-value {
            display: table-cell;
            padding: 5px;
            color: #333;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2d5016;
            border-bottom: 2px solid #2d5016;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .content {
            text-align: justify;
            margin-bottom: 15px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin: 15px 0;
        }
        .stat-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .stat-value {
            font-size: 24pt;
            font-weight: bold;
            color: #2d5016;
        }
        .stat-label {
            font-size: 10pt;
            color: #777;
            margin-top: 5px;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 3px;
            font-size: 10pt;
            font-weight: bold;
            margin-right: 5px;
        }
        .badge-published {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-draft {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        .badge-kajian {
            background-color: #cce5ff;
            color: #004085;
        }
        .badge-sosial {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-pendidikan {
            background-color: #e7d4f5;
            color: #4a148c;
        }
        .badge-perayaan {
            background-color: #ffe5cc;
            color: #cc5200;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            font-size: 10pt;
            color: #777;
        }
        .signature-box {
            margin-top: 30px;
            text-align: right;
        }
        .signature-line {
            display: inline-block;
            width: 200px;
            margin-top: 60px;
            border-top: 1px solid #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2d5016;
            color: white;
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN KEGIATAN</h1>
        <h2><?php echo e($laporan->nama_kegiatan); ?></h2>
        <p><?php echo e($laporan->tanggal_pelaksanaan->format('d F Y')); ?></p>
    </div>

    <!-- Status Badges -->
    <div style="margin-bottom: 20px;">
        <span class="badge badge-<?php echo e($laporan->status); ?>"><?php echo e(strtoupper($laporan->status)); ?></span>
        <span class="badge badge-<?php echo e($laporan->jenis_kegiatan); ?>"><?php echo e(strtoupper($laporan->jenis_kegiatan)); ?></span>
        <?php if($laporan->is_public): ?>
            <span class="badge" style="background-color: #cce5ff; color: #004085;">PUBLIK</span>
        <?php endif; ?>
    </div>

    <!-- Informasi Pelaksanaan -->
    <div class="section">
        <div class="section-title">INFORMASI PELAKSANAAN</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Tanggal</div>
                <div class="info-value"><?php echo e($laporan->tanggal_pelaksanaan->format('d F Y')); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Waktu</div>
                <div class="info-value"><?php echo e($laporan->waktu_pelaksanaan); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Lokasi</div>
                <div class="info-value"><?php echo e($laporan->lokasi); ?></div>
            </div>
            <?php if($laporan->penanggung_jawab): ?>
            <div class="info-row">
                <div class="info-label">Penanggung Jawab</div>
                <div class="info-value"><?php echo e($laporan->penanggung_jawab); ?></div>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <div class="info-label">Jenis Kegiatan</div>
                <div class="info-value"><?php echo e(ucfirst($laporan->jenis_kegiatan)); ?></div>
            </div>
        </div>
    </div>

    <!-- Statistik Peserta -->
    <div class="section">
        <div class="section-title">STATISTIK PESERTA</div>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?php echo e($laporan->jumlah_peserta); ?></div>
                <div class="stat-label">Total Peserta</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?php echo e($laporan->jumlah_hadir); ?></div>
                <div class="stat-label">Hadir</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?php echo e(number_format($laporan->getPersentaseKehadiran(), 1)); ?>%</div>
                <div class="stat-label">Kehadiran</div>
            </div>
        </div>
    </div>

    <!-- Deskripsi Kegiatan -->
    <div class="section">
        <div class="section-title">DESKRIPSI KEGIATAN</div>
        <div class="content">
            <?php echo e($laporan->deskripsi); ?>

        </div>
    </div>

    <!-- Hasil & Capaian -->
    <?php if($laporan->hasil_capaian): ?>
    <div class="section">
        <div class="section-title">HASIL & CAPAIAN</div>
        <div class="content">
            <?php echo e($laporan->hasil_capaian); ?>

        </div>
    </div>
    <?php endif; ?>

    <!-- Catatan & Kendala -->
    <?php if($laporan->catatan_kendala): ?>
    <div class="section">
        <div class="section-title">CATATAN & KENDALA</div>
        <div class="content">
            <?php echo e($laporan->catatan_kendala); ?>

        </div>
    </div>
    <?php endif; ?>

    <!-- Dokumentasi -->
    <?php if($laporan->foto_dokumentasi && count($laporan->foto_dokumentasi) > 0): ?>
    <div class="section">
        <div class="section-title">DOKUMENTASI</div>
        <p style="font-style: italic; color: #777;">
            Terdapat <?php echo e(count($laporan->foto_dokumentasi)); ?> foto dokumentasi yang tersimpan dalam sistem.
        </p>
    </div>
    <?php endif; ?>

    <!-- Tanda Tangan -->
    <div class="signature-box">
        <div>
            <p><?php echo e($laporan->tanggal_pelaksanaan->format('d F Y')); ?></p>
            <p style="margin-bottom: 10px;">Pembuat Laporan,</p>
            <div class="signature-line">
                <?php echo e($laporan->creator->name); ?>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; text-align: left; width: 50%;">
                    Dibuat: <?php echo e($laporan->created_at->format('d F Y, H:i')); ?> WIB
                </td>
                <td style="border: none; text-align: right; width: 50%;">
                    Dicetak: <?php echo e(now()->format('d F Y, H:i')); ?> WIB
                </td>
            </tr>
        </table>
        <p style="text-align: center; margin-top: 10px; font-size: 9pt;">
            Dokumen ini dihasilkan secara otomatis oleh Sistem Manajemen Masjid
        </p>
    </div>
</body>
</html>
<?php /**PATH E:\Ney\Kuliah\Semester 7\MANAJEMEN PROYEK\manajemen_masjid\resources\views/modules/kegiatan/laporan/pdf.blade.php ENDPATH**/ ?>