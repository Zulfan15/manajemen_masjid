
<?php $__env->startSection('title', 'Laporan & Statistik'); ?>
<?php $__env->startSection('content'); ?>

<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">

        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-chart-bar text-green-700 mr-2"></i>Laporan & Statistik
                </h1>
                <p class="text-gray-600 mt-2">Kelola laporan dan statistik masjid</p>
            </div>

            
            <?php if(!auth()->user()->isSuperAdmin()): ?>
            <button id="laporanActionBtn"
                class="bg-green-700 inline-flex items-center text-white px-4 py-2 rounded hover:bg-green-800 transition"
                type="button">
                <i class="fas fa-download mr-2"></i>
                <span id="laporanActionLabel">Download Semua Pengeluaran</span>
            </button>
            <?php endif; ?>
        </div>

        <?php if(auth()->user()->isSuperAdmin()): ?>
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-blue-700">
                <i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong>
            </p>
        </div>
        <?php endif; ?>

        
        <div class="border-b mb-6">
            <nav class="flex space-x-6 text-gray-600 font-semibold">
                <button data-target="keuangan"
                    class="tab-btn active text-green-700 border-b-2 border-green-700 pb-2">
                    Laporan Keuangan
                </button>
                <button data-target="kegiatan" class="tab-btn pb-2">Laporan Kegiatan</button>
                <button data-target="kehadiran" class="tab-btn pb-2">Kehadiran Jamaah</button>
                <button data-target="zakat" class="tab-btn pb-2">Zakat Mal</button>
                <button data-target="grafik" class="tab-btn pb-2">Perkembangan Masjid</button>
            </nav>
        </div>

        
        <div id="keuangan" class="tab-panel">
            <h3 class="text-xl font-bold text-green-700 mb-6">Laporan Keuangan</h3>
<form method="GET" action="<?php echo e(route('laporan.index')); ?>" class="mb-6 flex items-center gap-4">
    <label class="font-semibold text-gray-700">Tahun:</label>

    <select name="tahun" class="border rounded px-3 py-2">
        <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
            <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>>
                <?php echo e($y); ?>

            </option>
        <?php endfor; ?>
    </select>

    <button type="submit"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Tampilkan
    </button>
</form>

            <div class="bg-gradient-to-br from-blue-50 to-green-50 rounded-lg p-6 mb-8 shadow-sm border">
                <h4 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Grafik Pengeluaran Bulanan
                </h4>
                <div id="chart-keuangan"></div>
            </div>

            <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 p-6 rounded-lg flex justify-between border-l-4 border-red-500">
                <div>
                    <p class="text-sm font-medium text-red-700">Total Pengeluaran Keseluruhan</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">
                        Rp <?php echo e(number_format($totalPengeluaran ?? 0, 0, ',', '.')); ?>

                    </p>
                </div>
                <div class="text-5xl text-red-200 opacity-50">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>

            <h4 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-table text-green-600 mr-2"></i>Anggaran Bulanan Detail
            </h4>

            <div class="overflow-x-auto bg-white rounded-lg shadow-sm border">
                <table class="w-full text-sm">
                    <thead class="bg-green-50 border-b">
                        <tr>
                            <th class="p-3 text-left">Bulan</th>
                            <th class="p-3 text-right">Pemasukan</th>
                            <th class="p-3 text-right">Pengeluaran</th>
                            <th class="p-3 text-right">Sisa</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-anggaran"></tbody>
                </table>
            </div>
        </div>
                
<div id="kegiatan" class="tab-panel hidden">

    <h3 class="text-xl font-bold text-green-700 mb-6">
        Laporan Kegiatan
    </h3>

    
    <div class="mb-6 flex items-center gap-4">
        <label class="font-semibold text-gray-700">Tahun:</label>
        <select id="tahun-kegiatan"
            class="border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500">
            <?php $__currentLoopData = $tahunKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($y); ?>"><?php echo e($y); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button id="btn-filter-kegiatan"
            class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
            Tampilkan
        </button>
    </div>

    
    <div
        class="bg-gradient-to-br from-blue-50 to-green-50 rounded-lg p-6 mb-8 shadow-sm border border-green-100">

        <h4 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
            <i class="fas fa-chart-bar text-green-600 mr-2"></i>
            Jumlah Kegiatan per Bulan
        </h4>

        <div id="chart-kegiatan"></div>
    </div>

    
    <h4 class="text-lg font-semibold mb-4 flex items-center">
        <i class="fas fa-table text-green-600 mr-2"></i>
        Rekap Kegiatan Bulanan
    </h4>

    
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-200">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-green-50 to-green-100 border-b border-green-200">
                <tr>
                    <th class="p-3 text-left font-semibold text-gray-700">Bulan</th>
                    <th class="p-3 text-right font-semibold text-gray-700">
                        Jumlah Kegiatan
                    </th>
                </tr>
            </thead>
            <tbody id="tabel-kegiatan"></tbody>
        </table>
    </div>
</div>


        <div id="kegiatan" class="tab-panel hidden"><div id="chart-kegiatan"></div></div>
        <div id="kehadiran" class="tab-panel hidden"><div id="chart-kehadiran"></div></div>
        <div id="zakat" class="tab-panel hidden"><div id="chart-zakat"></div></div>
        <div id="grafik" class="tab-panel hidden"><div id="chart-grafik"></div></div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // ================= TAB HANDLER =================
    const tabs = document.querySelectorAll(".tab-btn");
    const panels = document.querySelectorAll(".tab-panel");

    tabs.forEach(btn => {
        btn.addEventListener("click", () => {
            tabs.forEach(b => b.classList.remove("active","text-green-700","border-green-700","border-b-2"));
            btn.classList.add("active","text-green-700","border-green-700","border-b-2");
            panels.forEach(p => p.classList.add("hidden"));
            document.getElementById(btn.dataset.target).classList.remove("hidden");
            updateLaporanAction(btn.dataset.target);
        });
    });

    // ================= PDF DOWNLOAD (TIDAK DIUBAH) =================
    const cetakAllUrl = "<?php echo e(route('keuangan.pengeluaran.cetakAll')); ?>";
    const actionBtn = document.getElementById('laporanActionBtn');
    const actionLabel = document.getElementById('laporanActionLabel');

    function updateLaporanAction(target) {
        if (!actionBtn) return;
        actionBtn.onclick = null;

        if (target === 'keuangan') {
            actionLabel.textContent = 'Download Semua Pengeluaran';
            actionBtn.onclick = () => window.open(cetakAllUrl, '_blank', 'noopener');
        } else {
            actionLabel.textContent = 'Fitur belum tersedia';
            actionBtn.onclick = () => alert('Fitur belum tersedia.');
        }
    }

    updateLaporanAction('keuangan');

    // ================= DATA KEUANGAN =================
    const bulan = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agt","Sep","Okt","Nov","Des"];

    // ❗ FIX UTAMA: tidak ada pemasukan
    const pemasukan = new Array(12).fill(0);
    const pengeluaran = <?php echo json_encode($pengeluaranChart, 15, 512) ?>;

    const anggaran = <?php echo json_encode($anggaran, 15, 512) ?>;
    const body = document.getElementById("tabel-anggaran");

    anggaran.forEach((item, i) => {
        body.innerHTML += `
        <tr class="${i % 2 ? 'bg-gray-50' : 'bg-white'} border-b">
            <td class="p-3">${item.bulan}</td>
            <td class="p-3 text-right text-gray-400">Rp 0</td>
            <td class="p-3 text-right text-red-600 font-semibold">
                Rp ${item.pengeluaran.toLocaleString('id-ID')}
            </td>
<td class="p-3 text-right font-semibold
    ${item.sisa < 0 ? 'text-red-600' : 'text-green-600'}">
    Rp ${item.sisa.toLocaleString('id-ID')}
</td>
        </tr>`;
    });

    new ApexCharts(document.querySelector("#chart-keuangan"), {
        chart: { type: 'bar', height: 400 },
        series: [
            { name: 'Pemasukan', data: pemasukan },
            { name: 'Pengeluaran', data: pengeluaran }
        ],
        xaxis: { categories: bulan },
        colors: ['#9ca3af','#ef4444'],
        dataLabels: { enabled: false }
    }).render();

function loadKegiatan(tahun) {
    fetch(`<?php echo e(route('laporan.data-kegiatan-bulanan')); ?>?tahun=${tahun}`)
        .then(res => res.json())
        .then(res => {

            // ===== GRAFIK =====
            document.querySelector("#chart-kegiatan").innerHTML = '';

            new ApexCharts(
                document.querySelector("#chart-kegiatan"),
                {
                    chart: {
                        type: 'bar',
                        height: 400,
                        toolbar: { show: true }
                    },
                    series: [{
                        name: 'Kegiatan',
                        data: res.chart
                    }],
                    xaxis: {
                        categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des']
                    },
                    yaxis: {
                        title: { text: 'Jumlah Kegiatan' }
                    },
                    colors: ['#16a34a'],
                    dataLabels: { enabled: false },
                    grid: { borderColor: '#e5e7eb' },
                    tooltip: {
                        y: {
                            formatter: val => val + ' kegiatan'
                        }
                    }
                }
            ).render();

            // ===== TABEL =====
            const tbody = document.getElementById('tabel-kegiatan');
            tbody.innerHTML = '';

            res.tabel.forEach((item, index) => {
                const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                tbody.innerHTML += `
                    <tr class="${rowClass} border-b border-gray-200 hover:bg-green-50 transition">
                        <td class="p-3 font-medium text-gray-800">${item.bulan}</td>
                        <td class="p-3 text-right font-semibold text-green-700">
                            ${item.jumlah}
                        </td>
                    </tr>
                `;
            });
        });
}

// INIT
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('tahun-kegiatan');

    loadKegiatan(select.value);

    document.getElementById('btn-filter-kegiatan')
        .addEventListener('click', () => {
            loadKegiatan(select.value);
        });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Semester paeh part 1\Menpro\mm3\manajemen_masjid\resources\views/modules/laporan/index.blade.php ENDPATH**/ ?>