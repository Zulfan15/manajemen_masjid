@extends('layouts.app')

@section('title', 'Laporan & Statistik')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-chart-bar text-green-600 mr-2"></i>Laporan & Statistik
                    </h1>
                    <p class="text-gray-600 mt-2">Laporan keuangan, kegiatan, ZIS, dan statistik jamaah masjid</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('laporan.export.pdf', ['tahun' => $tahun]) }}"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </a>
                    <a href="{{ route('laporan.export.excel', ['tahun' => $tahun]) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-file-excel mr-2"></i>Export Excel
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Tahun --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-center gap-4">
                <label class="text-sm font-medium text-gray-700">Tahun:</label>
                <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    @foreach($tahunTersedia as $thn)
                        <option value="{{ $thn }}" {{ $thn == $tahun ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-filter mr-1"></i>Tampilkan
                </button>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Pemasukan</p>
                        <h3 class="text-xl font-bold mt-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-arrow-up text-2xl text-green-100"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Total Pengeluaran</p>
                        <h3 class="text-xl font-bold mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-arrow-down text-2xl text-red-100"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r {{ $saldo >= 0 ? 'from-blue-500 to-blue-600' : 'from-orange-500 to-red-500' }} rounded-lg shadow-lg p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="{{ $saldo >= 0 ? 'text-blue-100' : 'text-orange-100' }} text-sm font-medium">Saldo
                            {{ $tahun }}</p>
                        <h3 class="text-xl font-bold mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                    </div>
                    <div class="{{ $saldo >= 0 ? 'bg-blue-400' : 'bg-orange-400' }} bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-wallet text-2xl {{ $saldo >= 0 ? 'text-blue-100' : 'text-orange-100' }}"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-5 text-white"
                id="card-jamaah">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Jamaah</p>
                        <h3 class="text-xl font-bold mt-1" id="total-jamaah-display">Loading...</h3>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-users text-2xl text-purple-100"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Keuangan --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-line text-green-600 mr-2"></i>Grafik Keuangan Bulanan - {{ $tahun }}
            </h3>
            <div class="h-80">
                <canvas id="chartKeuangan"></canvas>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex overflow-x-auto">
                    <button data-target="keuangan"
                        class="tab-btn active text-green-700 border-b-2 border-green-700 px-6 py-4 text-sm font-medium hover:text-green-600 whitespace-nowrap">
                        <i class="fas fa-money-bill-wave mr-2"></i>Keuangan
                    </button>
                    <button data-target="kegiatan"
                        class="tab-btn px-6 py-4 text-sm font-medium text-gray-700 hover:text-green-600 border-b-2 border-transparent whitespace-nowrap">
                        <i class="fas fa-calendar-alt mr-2"></i>Kegiatan
                    </button>
                    <button data-target="zis"
                        class="tab-btn px-6 py-4 text-sm font-medium text-gray-700 hover:text-green-600 border-b-2 border-transparent whitespace-nowrap">
                        <i class="fas fa-hand-holding-heart mr-2"></i>Donasi & ZIS
                    </button>
                    <button data-target="jamaah"
                        class="tab-btn px-6 py-4 text-sm font-medium text-gray-700 hover:text-green-600 border-b-2 border-transparent whitespace-nowrap">
                        <i class="fas fa-users mr-2"></i>Statistik Jamaah
                    </button>
                </nav>
            </div>

            <div class="p-6">
                {{-- Tab Keuangan --}}
                <div id="content-keuangan" class="tab-content">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Anggaran Bulanan {{ $tahun }}</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase border">
                                        Bulan</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase border">
                                        Pemasukan</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase border">
                                        Pengeluaran</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase border">
                                        Saldo Bulan</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase border">
                                        Saldo Kumulatif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($anggaran as $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 border font-medium">{{ $data['bulan'] }}</td>
                                        <td class="px-4 py-3 text-right border text-green-700">Rp
                                            {{ number_format($data['pemasukan'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right border text-red-700">Rp
                                            {{ number_format($data['pengeluaran'], 0, ',', '.') }}</td>
                                        <td
                                            class="px-4 py-3 text-right border {{ $data['saldo'] >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                                            Rp {{ number_format($data['saldo'], 0, ',', '.') }}</td>
                                        <td
                                            class="px-4 py-3 text-right border font-semibold {{ $data['saldo_kumulatif'] >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                                            Rp {{ number_format($data['saldo_kumulatif'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100">
                                <tr class="font-bold">
                                    <td class="px-4 py-3 border">TOTAL</td>
                                    <td class="px-4 py-3 text-right border text-green-700">Rp
                                        {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right border text-red-700">Rp
                                        {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                                    <td
                                        class="px-4 py-3 text-right border {{ $saldo >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                                        Rp {{ number_format($saldo, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right border"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Export per bulan --}}
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-700 mb-3">Export Laporan Bulanan</h4>
                        <form action="{{ route('laporan.export.pdf') }}" method="GET"
                            class="flex flex-wrap gap-4 items-end">
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Pilih Bulan</label>
                                <select name="bulan" class="px-4 py-2 border border-gray-300 rounded-lg">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-file-pdf mr-1"></i>Download PDF
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Tab Kegiatan --}}
                <div id="content-kegiatan" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Laporan Kegiatan</h3>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Kegiatan:</label>
                        <select id="tahun-kegiatan" class="px-4 py-2 border border-gray-300 rounded-lg">
                            @foreach($tahunKegiatan as $thn)
                                <option value="{{ $thn }}" {{ $thn == date('Y') ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                            @if($tahunKegiatan->isEmpty())
                                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                            @endif
                        </select>
                        <button id="btn-filter-kegiatan"
                            class="ml-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Tampilkan
                        </button>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Jumlah Kegiatan per Bulan</h4>
                        <div class="h-64">
                            <canvas id="chartKegiatan"></canvas>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-3">Rekap Kegiatan Bulanan</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase border">
                                            Bulan</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase border">
                                            Jumlah Kegiatan</th>
                                    </tr>
                                </thead>
                                <tbody id="table-kegiatan">
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-gray-500">Klik "Tampilkan" untuk
                                            melihat data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Tab ZIS --}}
                <div id="content-zis" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Donasi & ZIS</h3>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun:</label>
                        <select id="tahun-zis" class="px-4 py-2 border border-gray-300 rounded-lg">
                            @foreach($tahunTersedia as $thn)
                                <option value="{{ $thn }}" {{ $thn == $tahun ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                        <button id="btn-filter-zis"
                            class="ml-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Tampilkan
                        </button>
                    </div>

                    {{-- ZIS Stats Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-700 text-sm font-medium">Total Zakat</p>
                            <h3 class="text-xl font-bold text-yellow-800 mt-1" id="total-zakat">Rp 0</h3>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-green-700 text-sm font-medium">Total Infak</p>
                            <h3 class="text-xl font-bold text-green-800 mt-1" id="total-infak">Rp 0</h3>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-blue-700 text-sm font-medium">Total Sedekah</p>
                            <h3 class="text-xl font-bold text-blue-800 mt-1" id="total-sedekah">Rp 0</h3>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <p class="text-purple-700 text-sm font-medium">Grand Total ZIS</p>
                            <h3 class="text-xl font-bold text-purple-800 mt-1" id="grand-total-zis">Rp 0</h3>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Grafik ZIS Bulanan</h4>
                        <div class="h-64">
                            <canvas id="chartZIS"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Tab Jamaah --}}
                <div id="content-jamaah" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Jamaah</h3>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun:</label>
                        <select id="tahun-jamaah" class="px-4 py-2 border border-gray-300 rounded-lg">
                            @foreach($tahunTersedia as $thn)
                                <option value="{{ $thn }}" {{ $thn == $tahun ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                        <button id="btn-filter-jamaah"
                            class="ml-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Tampilkan
                        </button>
                    </div>

                    {{-- Jamaah Stats Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                            <p class="text-indigo-700 text-sm font-medium">Total Jamaah</p>
                            <h3 class="text-2xl font-bold text-indigo-800 mt-1" id="stat-total-jamaah">0</h3>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-green-700 text-sm font-medium">Jamaah Terverifikasi</p>
                            <h3 class="text-2xl font-bold text-green-800 mt-1" id="stat-jamaah-verified">0</h3>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-blue-700 text-sm font-medium">Registrasi Bulan Ini</p>
                            <h3 class="text-2xl font-bold text-blue-800 mt-1" id="stat-jamaah-bulan-ini">0</h3>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Grafik Registrasi Jamaah per Bulan</h4>
                        <div class="h-64">
                            <canvas id="chartJamaah"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Data from Controller
        const pemasukanChart = {!! json_encode($pemasukanChart) !!};
        const pengeluaranChart = {!! json_encode($pengeluaranChart) !!};

        // Helper function to format currency
        function formatRupiah(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
        }

        // Main Chart
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('chartKeuangan').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: pemasukanChart,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: '#10B981',
                            borderWidth: 1
                        },
                        {
                            label: 'Pengeluaran',
                            data: pengeluaranChart,
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: '#EF4444',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return formatRupiah(value);
                                }
                            }
                        }
                    }
                }
            });

            // Load Jamaah stats on page load
            loadJamaahStats();
        });

        // Tab Handler and URL Parameter Logic
        document.addEventListener("DOMContentLoaded", () => {
            const tabs = document.querySelectorAll(".tab-btn");
            
            // Function to activate tab
            function activateTab(target) {
                document.querySelectorAll(".tab-content").forEach(content => {
                    content.classList.add("hidden");
                });

                tabs.forEach(b => {
                    b.classList.remove("active", "text-green-700", "border-green-700");
                    b.classList.add("text-gray-700", "border-transparent");
                });

                const contentEl = document.getElementById("content-" + target);
                const btnEl = document.querySelector(`.tab-btn[data-target="${target}"]`);

                if (contentEl && btnEl) {
                    contentEl.classList.remove("hidden");
                    btnEl.classList.add("active", "text-green-700", "border-green-700");
                    btnEl.classList.remove("text-gray-700", "border-transparent");

                    // Auto fetch data if needed
                    if (target === 'kegiatan') document.getElementById('btn-filter-kegiatan').click();
                    if (target === 'zis') document.getElementById('btn-filter-zis').click();
                    if (target === 'jamaah') document.getElementById('btn-filter-jamaah').click();
                }
            }

            // Click Handler
            tabs.forEach(btn => {
                btn.addEventListener("click", () => {
                    const target = btn.getAttribute("data-target");
                    // Update URL without reload
                    const url = new URL(window.location);
                    url.searchParams.set('tab', target);
                    window.history.pushState({}, '', url);
                    
                    activateTab(target);
                });
            });

            // Check URL Param on Load
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            if (activeTab) {
                activateTab(activeTab);
            }
        });

        // Kegiatan Chart
        let chartKegiatan = null;
        document.getElementById('btn-filter-kegiatan').addEventListener('click', async () => {
            const tahun = document.getElementById('tahun-kegiatan').value;

            try {
                const response = await fetch(`{{ route('laporan.data-kegiatan') }}?tahun=${tahun}`);
                const data = await response.json();

                if (chartKegiatan) chartKegiatan.destroy();

                const ctx = document.getElementById('chartKegiatan').getContext('2d');
                chartKegiatan = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                            label: 'Jumlah Kegiatan',
                            data: data.chart,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                    }
                });

                let tableHTML = '';
                data.tabel.forEach(item => {
                    tableHTML += `<tr class="hover:bg-gray-50"><td class="px-4 py-3 border">${item.bulan}</td><td class="px-4 py-3 text-center border font-medium">${item.jumlah}</td></tr>`;
                });
                document.getElementById('table-kegiatan').innerHTML = tableHTML;

            } catch (error) {
                console.error('Error loading kegiatan data:', error);
            }
        });

        // ZIS Chart
        let chartZIS = null;
        document.getElementById('btn-filter-zis').addEventListener('click', async () => {
            const tahun = document.getElementById('tahun-zis').value;

            try {
                const response = await fetch(`{{ route('laporan.data-zis') }}?tahun=${tahun}`);
                const data = await response.json();

                // Update stats
                document.getElementById('total-zakat').textContent = formatRupiah(data.totalZakat);
                document.getElementById('total-infak').textContent = formatRupiah(data.totalInfak);
                document.getElementById('total-sedekah').textContent = formatRupiah(data.totalSedekah);
                document.getElementById('grand-total-zis').textContent = formatRupiah(data.grandTotal);

                if (chartZIS) chartZIS.destroy();

                const ctx = document.getElementById('chartZIS').getContext('2d');
                chartZIS = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [
                            {
                                label: 'Zakat',
                                data: data.zakat,
                                backgroundColor: 'rgba(234, 179, 8, 0.8)',
                                borderColor: '#EAB308',
                                borderWidth: 1
                            },
                            {
                                label: 'Infak',
                                data: data.infak,
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                borderColor: '#22C55E',
                                borderWidth: 1
                            },
                            {
                                label: 'Sedekah',
                                data: data.sedekah,
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderColor: '#3B82F6',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: function (value) { return formatRupiah(value); } }
                            }
                        }
                    }
                });

            } catch (error) {
                console.error('Error loading ZIS data:', error);
            }
        });

        // Jamaah Chart
        let chartJamaah = null;

        async function loadJamaahStats() {
            try {
                const tahun = document.getElementById('tahun-jamaah')?.value || {{ $tahun }};
                const response = await fetch(`{{ route('laporan.data-jamaah') }}?tahun=${tahun}`);
                const data = await response.json();

                // Update header card
                document.getElementById('total-jamaah-display').textContent = data.totalJamaah + ' Orang';

                // Update stats
                document.getElementById('stat-total-jamaah').textContent = data.totalJamaah;
                document.getElementById('stat-jamaah-verified').textContent = data.jamaahVerified;
                document.getElementById('stat-jamaah-bulan-ini').textContent = data.jamaahBulanIni;

            } catch (error) {
                console.error('Error loading jamaah stats:', error);
            }
        }

        document.getElementById('btn-filter-jamaah').addEventListener('click', async () => {
            const tahun = document.getElementById('tahun-jamaah').value;

            try {
                const response = await fetch(`{{ route('laporan.data-jamaah') }}?tahun=${tahun}`);
                const data = await response.json();

                // Update stats
                document.getElementById('stat-total-jamaah').textContent = data.totalJamaah;
                document.getElementById('stat-jamaah-verified').textContent = data.jamaahVerified;
                document.getElementById('stat-jamaah-bulan-ini').textContent = data.jamaahBulanIni;

                if (chartJamaah) chartJamaah.destroy();

                const ctx = document.getElementById('chartJamaah').getContext('2d');
                chartJamaah = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                            label: 'Registrasi Jamaah',
                            data: data.registrasiBulanan,
                            backgroundColor: 'rgba(99, 102, 241, 0.8)',
                            borderColor: '#6366F1',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                    }
                });

            } catch (error) {
                console.error('Error loading jamaah data:', error);
            }
        });
    </script>
@endsection