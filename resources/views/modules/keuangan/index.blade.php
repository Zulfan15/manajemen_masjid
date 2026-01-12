@extends('layouts.app')
@section('title', 'Dashboard Keuangan')
@section('content')
    @php
        use App\Models\Pemasukan;
        use App\Models\Pengeluaran;
        use App\Models\KategoriPengeluaran;
        use Carbon\Carbon;

        // ===== PEMASUKAN =====
        $totalPemasukan = Pemasukan::verified()->sum('jumlah');
        $pemasukanBulanIni = Pemasukan::verified()
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');
        $pemasukanBulanLalu = Pemasukan::verified()
            ->whereMonth('tanggal', date('m', strtotime('-1 month')))
            ->whereYear('tanggal', date('Y', strtotime('-1 month')))
            ->sum('jumlah');
        $pemasukanPending = Pemasukan::pending()->count();
        $transaksiPemasukan = Pemasukan::count();

        // ===== PENGELUARAN =====
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');
        $pengeluaranBulanLalu = Pengeluaran::whereMonth('tanggal', date('m', strtotime('-1 month')))
            ->whereYear('tanggal', date('Y', strtotime('-1 month')))
            ->sum('jumlah');
        $transaksiPengeluaran = Pengeluaran::count();
        $kategoriCount = KategoriPengeluaran::count();

        // ===== SALDO & KALKULASI =====
        $saldo = $totalPemasukan - $totalPengeluaran;
        $saldoBulanIni = $pemasukanBulanIni - $pengeluaranBulanIni;
        $saldoBulanLalu = $pemasukanBulanLalu - $pengeluaranBulanLalu;

        // Persentase perubahan
        $perubahanPemasukan = $pemasukanBulanLalu > 0 ? (($pemasukanBulanIni - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100 : 0;
        $perubahanPengeluaran = $pengeluaranBulanLalu > 0 ? (($pengeluaranBulanIni - $pengeluaranBulanLalu) / $pengeluaranBulanLalu) * 100 : 0;

        // ===== CHART DATA (12 Bulan) =====
        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];
        $chartSaldo = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('M');

            $pemasukan = Pemasukan::verified()
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');

            $pengeluaran = Pengeluaran::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');

            $chartPemasukan[] = $pemasukan;
            $chartPengeluaran[] = $pengeluaran;
            $chartSaldo[] = $pemasukan - $pengeluaran;
        }

        // ===== PIE CHARTS DATA =====
        $pemasukanPerJenis = Pemasukan::verified()
            ->selectRaw('jenis, SUM(jumlah) as total')
            ->groupBy('jenis')
            ->pluck('total', 'jenis');

        // Ambil data pengeluaran per kategori dengan benar
        $pengeluaranPerKategori = \Illuminate\Support\Facades\DB::table('pengeluaran')
            ->join('kategori_pengeluaran', 'pengeluaran.kategori_id', '=', 'kategori_pengeluaran.id')
            ->select('kategori_pengeluaran.nama_kategori', \Illuminate\Support\Facades\DB::raw('SUM(pengeluaran.jumlah) as total'))
            ->groupBy('kategori_pengeluaran.nama_kategori')
            ->get();

        // ===== TRANSAKSI TERBARU =====
        $pemasukanRecent = Pemasukan::latest('tanggal')->take(5)->get();
        $pengeluaranRecent = Pengeluaran::with('kategori')->latest('tanggal')->take(5)->get();

        // Gabungkan dan sorting
        $allTransaksi = collect();

        foreach ($pemasukanRecent as $item) {
            $allTransaksi->push([
                'type' => 'pemasukan',
                'tanggal' => $item->tanggal,
                'deskripsi' => ($item->sumber ?? 'Anonim') . ' - ' . $item->jenis,
                'jumlah' => $item->jumlah,
                'status' => $item->status,
            ]);
        }

        foreach ($pengeluaranRecent as $item) {
            $allTransaksi->push([
                'type' => 'pengeluaran',
                'tanggal' => $item->tanggal,
                'deskripsi' => $item->judul_pengeluaran,
                'jumlah' => $item->jumlah,
                'status' => 'verified',
                'kategori' => $item->kategori->nama_kategori ?? '-',
            ]);
        }

        $transaksiTerbaru = $allTransaksi->sortByDesc('tanggal')->take(10)->values();
    @endphp

    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-chart-line text-green-600 mr-3"></i>Dashboard Keuangan
                    </h1>
                    <p class="text-gray-600 mt-2">Ringkasan keuangan masjid secara real-time</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('keuangan.pemasukan.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition shadow-lg">
                        <i class="fas fa-arrow-up mr-2"></i>Pemasukan
                    </a>
                    <a href="{{ route('keuangan.pengeluaran.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition shadow-lg">
                        <i class="fas fa-arrow-down mr-2"></i>Pengeluaran
                    </a>
                    <a href="{{ route('laporan.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-lg">
                        <i class="fas fa-file-alt mr-2"></i>Laporan
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Saldo Kas --}}
            <div
                class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white bg-opacity-10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-indigo-200 text-sm font-medium uppercase tracking-wide">Saldo Kas</span>
                        <div class="bg-white bg-opacity-20 rounded-full p-2">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-2">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                    <div class="flex items-center text-sm">
                        <span class="{{ $saldoBulanIni >= 0 ? 'text-green-300' : 'text-red-300' }} flex items-center">
                            <i class="fas {{ $saldoBulanIni >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                            {{ $saldoBulanIni >= 0 ? '+' : '' }}Rp {{ number_format($saldoBulanIni, 0, ',', '.') }}
                        </span>
                        <span class="text-indigo-300 ml-2">bulan ini</span>
                    </div>
                </div>
            </div>

            {{-- Pemasukan Bulan Ini --}}
            <div
                class="bg-gradient-to-br from-green-500 via-green-600 to-emerald-700 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-green-200 text-sm font-medium uppercase tracking-wide">Pemasukan</span>
                        <div class="bg-white bg-opacity-20 rounded-full p-2">
                            <i class="fas fa-arrow-up text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-2">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h3>
                    <div class="flex items-center text-sm">
                        @if($perubahanPemasukan != 0)
                            <span class="{{ $perubahanPemasukan >= 0 ? 'text-green-200' : 'text-red-200' }} flex items-center">
                                <i class="fas {{ $perubahanPemasukan >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                {{ number_format(abs($perubahanPemasukan), 1) }}%
                            </span>
                        @endif
                        <span class="text-green-200 ml-2">vs bulan lalu</span>
                    </div>
                </div>
            </div>

            {{-- Pengeluaran Bulan Ini --}}
            <div
                class="bg-gradient-to-br from-red-500 via-red-600 to-rose-700 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-red-200 text-sm font-medium uppercase tracking-wide">Pengeluaran</span>
                        <div class="bg-white bg-opacity-20 rounded-full p-2">
                            <i class="fas fa-arrow-down text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-2">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h3>
                    <div class="flex items-center text-sm">
                        @if($perubahanPengeluaran != 0)
                            <span
                                class="{{ $perubahanPengeluaran <= 0 ? 'text-green-200' : 'text-red-200' }} flex items-center">
                                <i class="fas {{ $perubahanPengeluaran >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                {{ number_format(abs($perubahanPengeluaran), 1) }}%
                            </span>
                        @endif
                        <span class="text-red-200 ml-2">vs bulan lalu</span>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div
                class="bg-gradient-to-br from-amber-500 via-orange-500 to-orange-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-amber-200 text-sm font-medium uppercase tracking-wide">Menunggu Verifikasi</span>
                        <div class="bg-white bg-opacity-20 rounded-full p-2">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-2">{{ $pemasukanPending }}</h3>
                    <div class="text-amber-200 text-sm">
                        transaksi pemasukan
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Main Chart --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-chart-area text-blue-500 mr-2"></i>Tren Keuangan
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">Perbandingan pemasukan dan pengeluaran 12 bulan terakhir</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex items-center text-sm text-gray-600">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>Pemasukan
                        </span>
                        <span class="flex items-center text-sm text-gray-600">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>Pengeluaran
                        </span>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="space-y-6">
                {{-- Total Statistik --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-calculator text-purple-500 mr-2"></i>Total Keuangan
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <span class="text-gray-700">Total Pemasukan</span>
                            <span class="font-bold text-green-600">Rp
                                {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <span class="text-gray-700">Total Pengeluaran</span>
                            <span class="font-bold text-red-600">Rp
                                {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 {{ $saldo >= 0 ? 'bg-blue-50' : 'bg-orange-50' }} rounded-lg">
                            <span class="text-gray-700 font-medium">Saldo Akhir</span>
                            <span class="font-bold {{ $saldo >= 0 ? 'text-blue-600' : 'text-orange-600' }}">Rp
                                {{ number_format($saldo, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Transaction Count --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-receipt text-indigo-500 mr-2"></i>Jumlah Transaksi
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                            <p class="text-3xl font-bold text-green-600">{{ $transaksiPemasukan }}</p>
                            <p class="text-sm text-gray-600">Pemasukan</p>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 rounded-xl">
                            <p class="text-3xl font-bold text-red-600">{{ $transaksiPengeluaran }}</p>
                            <p class="text-sm text-gray-600">Pengeluaran</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pie Charts & Recent Transactions --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Komposisi Pemasukan --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-pie text-green-500 mr-2"></i>Komposisi Pemasukan
                </h3>
                <div class="h-48">
                    <canvas id="pieChartPemasukan"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($pemasukanPerJenis as $jenis => $total)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $jenis }}</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Komposisi Pengeluaran --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-pie text-red-500 mr-2"></i>Komposisi Pengeluaran
                </h3>
                <div class="h-48">
                    <canvas id="pieChartPengeluaran"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($pengeluaranPerKategori as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $item->nama_kategori ?? 'Lainnya' }}</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Transaksi Terbaru --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-history text-gray-500 mr-2"></i>Transaksi Terbaru
                    </h3>
                </div>
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @forelse($transaksiTerbaru as $trx)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3
                                                        {{ $trx['type'] == 'pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                                        <i
                                            class="fas {{ $trx['type'] == 'pemasukan' ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">
                                            {{ \Illuminate\Support\Str::limit($trx['deskripsi'], 25) }}</p>
                                        <p class="text-xs text-gray-500">{{ Carbon::parse($trx['tanggal'])->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="font-bold text-sm {{ $trx['type'] == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $trx['type'] == 'pemasukan' ? '+' : '-' }}Rp
                                    {{ number_format($trx['jumlah'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                            <p>Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('keuangan.pemasukan.index') }}"
                class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition flex items-center group">
                <div class="bg-green-100 rounded-xl p-4 mr-4 group-hover:bg-green-200 transition">
                    <i class="fas fa-plus-circle text-2xl text-green-600"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Tambah Pemasukan</h4>
                    <p class="text-sm text-gray-500">Input transaksi baru</p>
                </div>
            </a>

            <a href="{{ route('keuangan.pengeluaran.index') }}"
                class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition flex items-center group">
                <div class="bg-red-100 rounded-xl p-4 mr-4 group-hover:bg-red-200 transition">
                    <i class="fas fa-minus-circle text-2xl text-red-600"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Tambah Pengeluaran</h4>
                    <p class="text-sm text-gray-500">Input transaksi baru</p>
                </div>
            </a>

            <a href="{{ route('keuangan.kategori-pengeluaran.index') }}"
                class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition flex items-center group">
                <div class="bg-blue-100 rounded-xl p-4 mr-4 group-hover:bg-blue-200 transition">
                    <i class="fas fa-tags text-2xl text-blue-600"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Kategori</h4>
                    <p class="text-sm text-gray-500">{{ $kategoriCount }} kategori</p>
                </div>
            </a>

            <a href="{{ route('laporan.index') }}"
                class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition flex items-center group">
                <div class="bg-purple-100 rounded-xl p-4 mr-4 group-hover:bg-purple-200 transition">
                    <i class="fas fa-file-pdf text-2xl text-purple-600"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Export Laporan</h4>
                    <p class="text-sm text-gray-500">PDF & Excel</p>
                </div>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Main Chart
            new Chart(document.getElementById('mainChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: {!! json_encode($chartPemasukan) !!},
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#10B981'
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($chartPengeluaran) !!},
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#EF4444'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                }
                            }
                        }
                    }
                }
            });

            // Pie Chart Pemasukan
            new Chart(document.getElementById('pieChartPemasukan').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($pemasukanPerJenis->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($pemasukanPerJenis->values()) !!},
                        backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });

            // Pie Chart Pengeluaran
            new Chart(document.getElementById('pieChartPengeluaran').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($pengeluaranPerKategori->pluck('nama_kategori')) !!},
                    datasets: [{
                        data: {!! json_encode($pengeluaranPerKategori->pluck('total')) !!},
                        backgroundColor: ['#EF4444', '#3B82F6', '#F59E0B', '#10B981', '#8B5CF6', '#EC4899'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
@endsection