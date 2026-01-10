
<?php $__env->startSection('title', 'Detail Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    
    <div class="text-sm text-gray-500 mb-2">
        <span class="text-emerald-700">Aset</span> <span class="mx-1">/</span> <span>Detail Aset</span>
    </div>

    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <h1 class="text-3xl font-semibold text-gray-900">Detail Aset</h1>

        <div class="flex gap-3">
            <button type="button"
                onclick="printQrCode()"
                class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50">
                <i class="fa-solid fa-print mr-2 text-xs"></i>
                Cetak QR Code
            </button>

            <a href="<?php echo e(route('inventaris.aset.edit', $asset->aset_id)); ?>"
               class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                <i class="fa-solid fa-pen mr-2 text-xs"></i>
                Edit Aset
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="aspect-[4/3] rounded-xl bg-gray-100 overflow-hidden">
                <?php if($asset->foto_path): ?>
                    <img src="<?php echo e(\Illuminate\Support\Facades\Storage::url($asset->foto_path)); ?>"
                        alt="Foto <?php echo e($asset->nama_aset); ?>"
                        class="w-full h-full object-cover"
                        loading="lazy"
                        onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=&quot;w-full h-full flex items-center justify-center text-gray-400&quot;>Foto Barang</div>';">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        Foto Barang
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Nama Barang</div>
                <div class="text-lg font-semibold text-gray-900"><?php echo e($asset->nama_aset); ?></div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Kategori</div>
                <div class="text-lg font-semibold text-gray-900"><?php echo e($asset->kategori ?? '-'); ?></div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Jenis Aset</div>
                <div class="text-lg font-semibold text-gray-900">-</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Tanggal Pembelian</div>
                <div class="text-lg font-semibold text-gray-900">
                    <?php echo e($asset->tanggal_perolehan ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->translatedFormat('d F Y') : '-'); ?>

                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Lokasi</div>
                <div class="text-lg font-semibold text-gray-900"><?php echo e($asset->lokasi ?? '-'); ?></div>
            </div>

            
            <?php
                $kondisi = strtolower($kondisiTerbaru->kondisi ?? '-');
                $badge = match($kondisi) {
                    'baik','layak' => 'bg-emerald-100 text-emerald-700',
                    'perlu_perbaikan','perbaikan' => 'bg-amber-100 text-amber-700',
                    'rusak' => 'bg-rose-100 text-rose-700',
                    default => 'bg-gray-100 text-gray-600',
                };
            ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-2">Kondisi</div>
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold <?php echo e($badge); ?>">
                    <?php echo e($kondisi !== '-' ? ucfirst(str_replace('_',' ',$kondisi)) : '-'); ?>

                </span>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Umur Aset</div>
                <div class="text-lg font-semibold text-gray-900"><?php echo e($umurText); ?></div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col items-center justify-center">
                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100" data-qr-wrapper>
                    <?php echo QrCode::size(130)->margin(1)->generate($qrCodeText); ?>

                </div>
                <div class="mt-3 text-sm font-medium text-gray-700"><?php echo e($qrCodeText); ?></div>
            </div>
        </div>
    </div>

    
    <div class="mt-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Riwayat Perawatan</h2>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">TANGGAL</th>
                        <th class="px-4 py-3 text-left">KETERANGAN</th>
                        <th class="px-4 py-3 text-left">BIAYA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $riwayatPerawatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <?php echo e($row->tanggal_jadwal ? \Carbon\Carbon::parse($row->tanggal_jadwal)->translatedFormat('d F Y') : '-'); ?>

                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                <?php echo e($row->note ?? $row->jenis_perawatan ?? '-'); ?>

                            </td>
                            <td class="px-4 py-3 text-gray-700">-</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                Belum ada riwayat perawatan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <form action="<?php echo e(route('inventaris.aset.destroy', $asset->aset_id)); ?>"
                method="POST"
                onsubmit="return confirm('Yakin ingin menghapus aset ini?')"
                class="inline-block">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700">
                    <i class="fa-solid fa-trash mr-2 text-xs"></i>
                    Hapus Aset
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function printQrCode() {
    const qrWrapper = document.querySelector('[data-qr-wrapper]');
    const qrSvg = qrWrapper ? qrWrapper.innerHTML : '';

    if (!qrSvg || !qrSvg.trim()) {
        alert('QR Code belum siap / tidak ditemukan.');
        return;
    }

    const codeText = <?php echo json_encode($qrCodeText, 15, 512) ?>;
    const asetName = <?php echo json_encode($asset->nama_aset, 15, 512) ?>;

    const html = `
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Cetak QR Aset</title>
  <style>
    body{
      display:flex;align-items:center;justify-content:center;height:100vh;
      font-family:Arial,sans-serif;background:#fff;margin:0;
    }
    .card{
      text-align:center;border:1px solid #ddd;padding:24px;border-radius:12px;
      min-width:280px;
    }
    .qr svg{ width:220px; height:220px; }
    .code{ margin-top:12px; font-weight:700; letter-spacing:1px; }
    .name{ margin-top:6px; font-size:14px; color:#555; }
  </style>
</head>
<body>
  <div class="card">
    <div class="qr">${qrSvg}</div>
    <div class="code">${codeText}</div>
    <div class="name">${asetName}</div>
  </div>

  <script>
    window.onload = function () {
      setTimeout(function () { window.print(); }, 250);
    };
    window.onafterprint = function () {
      setTimeout(function(){ window.close(); }, 150);
    };
  <\/script>
</body>
</html>
    `;

    const win = window.open('', '_blank', 'width=520,height=650');
    if (!win) {
        alert('Pop-up diblokir browser. Izinin pop-up untuk cetak QR ya.');
        return;
    }

    win.document.open();
    win.document.write(html);
    win.document.close();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Backup\tahun ajaran 4-1 smester 7\ManPro\Manajemen Masjid\manajemen_masjid\resources\views/modules/inventaris/aset/show.blade.php ENDPATH**/ ?>