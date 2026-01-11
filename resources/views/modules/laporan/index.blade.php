@extends('layouts.app')

@section('title', 'Laporan & Statistik')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-chart-bar text-green-600 mr-2"></i>Laporan & Statistik
                </h1>
                <p class="text-gray-600 mt-2">Kelola laporan dan statistik masjid</p>
            </div>
            
            @if(!auth()->user()->isSuperAdmin())
            <button id="laporanActionBtn" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition">
                <i class="fas fa-download mr-2"></i>
                <span id="laporanActionLabel">Download Laporan</span>
            </button>
            @endif
        </div>
    </div>

    @if(auth()->user()->isSuperAdmin())
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
        <p class="text-blue-700">
            <i class="fas fa-info-circle mr-2"></i><strong>Mode View Only:</strong> Anda hanya dapat melihat data
        </p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex">
                <button data-target="keuangan" class="tab-btn active text-green-700 border-b-2 border-green-700 px-6 py-4 text-sm font-medium hover:text-green-600">
                    Laporan Keuangan
                </button>
                <button data-target="kegiatan" class="tab-btn px-6 py-4 text-sm font-medium text-gray-700 hover:text-green-600 border-b-2 border-transparent">
                    Laporan Kegiatan
                </button>
                <button data-target="kehadiran" class="tab-btn px-6 py-4 text-sm font-medium text-gray-700 hover:text-green-600 border-b-2 border-transparent">
                    Kehadiran Jamaah
                </button>
                <button data-target="zakat" class="tab-btn px-6 py-4 text-sm font-medium text-gray-700 hover:text-green-600 border-b-2 border-transparent">
                    Zakat Mal
                </button>
                <button data-target="grafik" class="tab-btn px-6 py-4 text-sm font-medium text-gray-700 hover:text-green-600 border-b-2 border-transparent">
                    Perkembangan Masjid
                </button>
            </nav>
        </div>

        <div class="p-6">
            <div id="content-keuangan" class="tab-content">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Laporan Keuangan</h2>
                <p class="text-gray-600 mb-6">Laporan keuangan masjid mencakup pemasukan dan pengeluaran</p>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun:</label>
                    <select id="tahun-keuangan" class="px-4 py-2 border border-gray-300 rounded-lg">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button onclick="loadKeuangan()" class="ml-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        Tampilkan
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-green-50 rounded-lg p-6">
                        <p class="text-sm text-green-600 mb-2">Total Pemasukan</p>
                        <p id="stat-pemasukan" class="text-3xl font-bold text-green-700">Rp 0</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-6">
                        <p class="text-sm text-red-600 mb-2">Total Pengeluaran</p>
                        <p id="stat-pengeluaran" class="text-3xl font-bold text-red-700">Rp 0</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-6">
                        <p class="text-sm text-blue-600 mb-2">Saldo</p>
                        <p id="stat-saldo" class="text-3xl font-bold text-blue-700">Rp 0</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Grafik Pengeluaran Bulanan</h3>
                    <div id="chart-keuangan"></div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Anggaran Bulanan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase border">Bulan</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase border">Pemasukan</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase border">Pengeluaran</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase border">Sisa</th>
                                </tr>
                            </thead>
                            <tbody id="table-keuangan">
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Pilih tahun dan klik Tampilkan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="content-kegiatan" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Laporan Kegiatan</h2>
                <p class="text-gray-600 mb-6">Rekap kegiatan dan acara yang telah dilaksanakan</p>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun:</label>
                    <select id="tahun-kegiatan" class="px-4 py-2 border border-gray-300 rounded-lg">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button id="btn-filter-kegiatan" class="ml-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        Tampilkan
                    </button>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Jumlah Kegiatan per Bulan</h3>
                    <div id="chart-kegiatan"></div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Rekap Kegiatan Bulanan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase border">Bulan</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase border">Jumlah Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody id="table-kegiatan">
                                <tr>
                                    <td colspan="2" class="px-4 py-8 text-center text-gray-500">Pilih tahun dan klik Tampilkan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="content-kehadiran" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Kehadiran Jamaah</h2>
                <p class="text-gray-600 mb-6">Statistik kehadiran jamaah di masjid</p>
                
                <div class="text-center text-gray-500 py-12">
                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                    <p class="text-lg">Fitur kehadiran jamaah akan segera tersedia</p>
                </div>
            </div>

            <div id="content-zakat" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Zakat Mal</h2>
                <p class="text-gray-600 mb-6">Laporan penerimaan dan penyaluran zakat mal</p>
                
                <div class="text-center text-gray-500 py-12">
                    <i class="fas fa-hand-holding-heart text-6xl text-gray-300 mb-4"></i>
                    <p class="text-lg">Data zakat mal akan ditampilkan di sini</p>
                </div>
            </div>

            <div id="content-grafik" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Perkembangan Masjid</h2>
                <p class="text-gray-600 mb-6">Grafik perkembangan berbagai aspek masjid</p>
                
                <div class="text-center text-gray-500 py-12">
                    <i class="fas fa-chart-area text-6xl text-gray-300 mb-4"></i>
                    <p class="text-lg">Grafik perkembangan akan ditampilkan di sini</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Tab Handler
const tabs = document.querySelectorAll(".tab-btn");
tabs.forEach(btn => {
    btn.addEventListener("click", () => {
        const target = btn.getAttribute("data-target");
        
        // Hide all content
        document.querySelectorAll(".tab-content").forEach(content => {
            content.classList.add("hidden");
        });
        
        // Remove active from all tabs
        tabs.forEach(b => {
            b.classList.remove("active", "text-green-700", "border-green-700");
            b.classList.add("text-gray-700", "border-transparent");
        });
        
        // Show selected content
        document.getElementById("content-" + target).classList.remove("hidden");
        
        // Add active to clicked tab
        btn.classList.add("active", "text-green-700", "border-green-700");
        btn.classList.remove("text-gray-700", "border-transparent");
    });
});

// Charts
let chartKeuangan = null;
let chartKegiatan = null;

function loadKeuangan() {
    const tahun = document.getElementById('tahun-keuangan').value;
    
    // Dummy data - replace with actual API call
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    const pemasukan = Array(12).fill(0).map(() => Math.floor(Math.random() * 10000000));
    const pengeluaran = Array(12).fill(0).map(() => Math.floor(Math.random() * 8000000));
    
    const totalPemasukan = pemasukan.reduce((a, b) => a + b, 0);
    const totalPengeluaran = pengeluaran.reduce((a, b) => a + b, 0);
    const saldo = totalPemasukan - totalPengeluaran;
    
    // Update stats
    document.getElementById('stat-pemasukan').textContent = 'Rp ' + totalPemasukan.toLocaleString('id-ID');
    document.getElementById('stat-pengeluaran').textContent = 'Rp ' + totalPengeluaran.toLocaleString('id-ID');
    document.getElementById('stat-saldo').textContent = 'Rp ' + saldo.toLocaleString('id-ID');
    
    // Update chart
    if (chartKeuangan) {
        chartKeuangan.destroy();
    }
    
    const options = {
        series: [{
            name: 'Pemasukan',
            data: pemasukan
        }, {
            name: 'Pengeluaran',
            data: pengeluaran
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: months,
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return 'Rp ' + val.toLocaleString('id-ID');
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return 'Rp ' + val.toLocaleString('id-ID');
                }
            }
        },
        colors: ['#10b981', '#ef4444']
    };
    
    chartKeuangan = new ApexCharts(document.querySelector("#chart-keuangan"), options);
    chartKeuangan.render();
    
    // Update table
    let tableHTML = '';
    months.forEach((month, i) => {
        const sisa = pemasukan[i] - pengeluaran[i];
        tableHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 border">${month}</td>
                <td class="px-4 py-3 text-right border text-green-700">Rp ${pemasukan[i].toLocaleString('id-ID')}</td>
                <td class="px-4 py-3 text-right border text-red-700">Rp ${pengeluaran[i].toLocaleString('id-ID')}</td>
                <td class="px-4 py-3 text-right border ${sisa >= 0 ? 'text-blue-700' : 'text-red-700'}">Rp ${sisa.toLocaleString('id-ID')}</td>
            </tr>
        `;
    });
    document.getElementById('table-keuangan').innerHTML = tableHTML;
}

function loadKegiatan(tahun) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    const jumlahKegiatan = Array(12).fill(0).map(() => Math.floor(Math.random() * 15));
    
    // Update chart
    if (chartKegiatan) {
        chartKegiatan.destroy();
    }
    
    const options = {
        series: [{
            name: 'Jumlah Kegiatan',
            data: jumlahKegiatan
        }],
        chart: {
            type: 'area',
            height: 350
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: months
        },
        colors: ['#10b981']
    };
    
    chartKegiatan = new ApexCharts(document.querySelector("#chart-kegiatan"), options);
    chartKegiatan.render();
    
    // Update table
    let tableHTML = '';
    months.forEach((month, i) => {
        tableHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 border">${month}</td>
                <td class="px-4 py-3 text-center border">${jumlahKegiatan[i]}</td>
            </tr>
        `;
    });
    document.getElementById('table-kegiatan').innerHTML = tableHTML;
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btn-filter-kegiatan').addEventListener('click', () => {
        const tahun = document.getElementById('tahun-kegiatan').value;
        loadKegiatan(tahun);
    });
});
</script>
@endsection
