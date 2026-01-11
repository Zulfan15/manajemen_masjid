@extends('layouts.app')
@section('title', 'Keuangan Masjid')
@section('content')
    @php
        use App\Models\Pemasukan;
        use App\Models\Pengeluaran;
        use App\Models\KategoriPengeluaran;
        use Carbon\Carbon;

        // Total Pemasukan (verified)
        $totalPemasukan = Pemasukan::verified()->sum('jumlah');
        $pemasukanBulanIni = Pemasukan::verified()
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');

        // Total Pengeluaran
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');

        // Saldo
        $saldo = $totalPemasukan - $totalPengeluaran;
        $saldoBulanIni = $pemasukanBulanIni - $pengeluaranBulanIni;

        // Statistik tambahan
        $totalTransaksiPemasukan = Pemasukan::count();
        $totalTransaksiPengeluaran = Pengeluaran::count();
        $pemasukanPending = Pemasukan::pending()->count();
        $kategoriCount = KategoriPengeluaran::count();

        // Data untuk Chart - 6 bulan terakhir
        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('M Y');

            $chartPemasukan[] = Pemasukan::verified()
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');

            $chartPengeluaran[] = Pengeluaran::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');
        }

        // Transaksi Terbaru
        $transaksiTerbaru = collect();

        // Gabungkan pemasukan
        $pemasukanRecent = Pemasukan::latest('tanggal')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'pemasukan',
                    'tanggal' => $item->tanggal,
                    'deskripsi' => $item->sumber . ' - ' . $item->jenis,
                    'jumlah' => $item->jumlah,
                    'status' => $item->status,
                ];
            });

        // Gabungkan pengeluaran
        $pengeluaranRecent = Pengeluaran::with('kategori')
            ->latest('tanggal')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'pengeluaran',
                    'tanggal' => $item->tanggal,
                    'deskripsi' => $item->judul_pengeluaran,
                    'jumlah' => $item->jumlah,
                    'status' => 'verified',
                    'kategori' => $item->kategori->nama_kategori ?? '-',
                ];
            });

        $transaksiTerbaru = $pemasukanRecent->merge($pengeluaranRecent)
            ->sortByDesc('tanggal')
            ->take(10);

        // Pemasukan per jenis
        $pemasukanPerJenis = Pemasukan::verified()
            ->selectRaw('jenis, SUM(jumlah) as total')
            ->groupBy('jenis')
            ->pluck('total', 'jenis');
    @endphp

    <div class="container mx-auto p-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-money-bill-wave text-green-700 mr-2"></i>Keuangan Masjid
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola keuangan dan transaksi masjid</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('keuangan.pemasukan.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-arrow-up mr-2"></i>Pemasukan
                    </a>
                    <a href="{{ route('keuangan.pengeluaran.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-arrow-down mr-2"></i>Pengeluaran
                    </a>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Saldo --}}
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Saldo Kas</p>
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                        <p class="text-indigo-200 text-xs mt-1">
                            Bulan ini: {{ $saldoBulanIni >= 0 ? '+' : '' }}Rp
                            {{ number_format($saldoBulanIni, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-indigo-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-wallet text-3xl text-indigo-100"></i>
                    </div>
                </div>
            </div>

            {{-- Total Pemasukan --}}
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Pemasukan</p>
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                        <p class="text-green-200 text-xs mt-1">
                            Bulan ini: Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-arrow-up text-3xl text-green-100"></i>
                    </div>
                </div>
            </div>

            {{-- Total Pengeluaran --}}
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Total Pengeluaran</p>
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                        <p class="text-red-200 text-xs mt-1">
                            Bulan ini: Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-arrow-down text-3xl text-red-100"></i>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Menunggu Verifikasi</p>
                        <h3 class="text-2xl font-bold mt-2">{{ $pemasukanPending }}</h3>
                        <p class="text-yellow-200 text-xs mt-1">transaksi pemasukan</p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-clock text-3xl text-yellow-100"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts & Quick Stats --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Line Chart --}}
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>Tren Keuangan 6 Bulan Terakhir
                </h3>
                <div class="h-64">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

            {{-- Pemasukan per Jenis --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>Komposisi Pemasukan
                </h3>
                <div class="h-48">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($pemasukanPerJenis as $jenis => $total)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $jenis }}</span>
                            <span class="font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick Links & Recent Transactions --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Quick Links --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>Akses Cepat
                </h3>

                <a href="{{ route('keuangan.pemasukan.index') }}"
                    class="block bg-white border-2 border-gray-200 rounded-lg p-4 hover:border-green-500 hover:shadow-lg transition">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-plus-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Transaksi Pemasukan</h4>
                            <p class="text-gray-500 text-sm">{{ $totalTransaksiPemasukan }} transaksi</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('keuangan.pengeluaran.index') }}"
                    class="block bg-white border-2 border-gray-200 rounded-lg p-4 hover:border-red-500 hover:shadow-lg transition">
                    <div class="flex items-center">
                        <div class="bg-red-100 rounded-full p-3 mr-4">
                            <i class="fas fa-minus-circle text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Transaksi Pengeluaran</h4>
                            <p class="text-gray-500 text-sm">{{ $totalTransaksiPengeluaran }} transaksi</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('keuangan.kategori-pengeluaran.index') }}"
                    class="block bg-white border-2 border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:shadow-lg transition">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-tags text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Kategori Pengeluaran</h4>
                            <p class="text-gray-500 text-sm">{{ $kategoriCount }} kategori</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('laporan.index') }}"
                    class="block bg-white border-2 border-gray-200 rounded-lg p-4 hover:border-purple-500 hover:shadow-lg transition">
                    <div class="flex items-center">
                        <div class="bg-purple-100 rounded-full p-3 mr-4">
                            <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Laporan Keuangan</h4>
                            <p class="text-gray-500 text-sm">Bulanan & Tahunan</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Recent Transactions --}}
            <div class="lg:col-span-2 bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-history text-gray-500 mr-2"></i>Transaksi Terbaru
                        </h3>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($transaksiTerbaru as $trx)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4
                                        {{ $trx['type'] == 'pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                                        <i
                                            class="fas {{ $trx['type'] == 'pemasukan' ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ Str::limit($trx['deskripsi'], 40) }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ Carbon::parse($trx['tanggal'])->format('d M Y') }}
                                            @if($trx['type'] == 'pengeluaran' && isset($trx['kategori']))
                                                · {{ $trx['kategori'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="font-semibold {{ $trx['type'] == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $trx['type'] == 'pemasukan' ? '+' : '-' }}Rp
                                        {{ number_format($trx['jumlah'], 0, ',', '.') }}
                                    </p>
                                    @if($trx['status'] == 'pending')
                                        <span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                                    @elseif($trx['status'] == 'verified')
                                        <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full">Verified</span>
                                    @endif
                                </div>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Line Chart
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            new Chart(lineCtx, {
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
                            tension: 0.4
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($chartPengeluaran) !!},
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Pie Chart
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($pemasukanPerJenis->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($pemasukanPerJenis->values()) !!},
                        backgroundColor: [
                            '#10B981', '#3B82F6', '#F59E0B', '#EF4444',
                            '#8B5CF6', '#EC4899', '#6B7280'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endsection