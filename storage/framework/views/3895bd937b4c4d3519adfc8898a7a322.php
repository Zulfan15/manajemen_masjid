<?php
$colors = [
    'kajian' => '#374151',
    'workshop' => '#374151',
    'pelatihan' => '#374151',
    'default' => '#374151',
];
$bg = $colors[$sertifikat->template] ?? '#374151';
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page { size: 792pt 612pt; margin: 0; }
* { margin: 0; padding: 0; box-sizing: border-box; }
html, body { width: 792pt; height: 612pt; overflow: hidden; font-family: DejaVu Sans, Arial, sans-serif; }
.wrap { width: 792pt; height: 612pt; background: #f3f4f6; padding: 30pt; }
.card { width: 732pt; height: 552pt; background: <?php echo $bg; ?>; border-radius: 10pt; padding: 25pt; }
.inner { border: 1pt solid rgba(255,255,255,0.3); border-radius: 6pt; width: 100%; height: 100%; text-align: center; color: white; padding: 40pt; }
.icon { font-size: 36pt; margin-bottom: 15pt; }
h1 { font-size: 34pt; font-weight: bold; letter-spacing: 3pt; margin-bottom: 8pt; }
.sub { font-size: 14pt; margin-bottom: 25pt; }
.namebox { background: white; color: #1f2937; display: inline-block; padding: 12pt 60pt; border-radius: 4pt; margin-bottom: 25pt; }
.name { font-size: 20pt; font-weight: bold; text-transform: uppercase; }
.lbl { font-size: 12pt; margin-bottom: 8pt; }
.evt { font-size: 16pt; font-weight: bold; margin-bottom: 30pt; }
.line { width: 80%; height: 1pt; background: rgba(255,255,255,0.2); margin: 0 auto 20pt; }
.ft { font-size: 10pt; opacity: 0.7; }
</style>
</head>
<body>
<div class="wrap"><div class="card"><div class="inner">
<div class="icon">🕌</div>
<h1>SERTIFIKAT</h1>
<div class="sub">Diberikan Kepada</div>
<div class="namebox"><div class="name"><?php echo e($sertifikat->nama_peserta); ?></div></div>
<div class="lbl">Telah mengikuti kegiatan</div>
<div class="evt"><?php echo e($sertifikat->nama_kegiatan); ?></div>
<div class="line"></div>
<div class="ft">Template: <?php echo e(strtoupper($sertifikat->template)); ?></div>
</div></div></div>
</body>
</html>
<?php /**PATH E:\Ney\Kuliah\Semester 7\MANAJEMEN PROYEK\manajemen_masjid\resources\views/modules/kegiatan/sertifikat/pdf.blade.php ENDPATH**/ ?>