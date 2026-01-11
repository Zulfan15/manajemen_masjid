@extends('layouts.app')

@section('title', 'Transaksi Pengeluaran')

@section('content')
    @php
        use App\Models\Pemasukan;
        use Carbon\Carbon;

        // Pemasukan data untuk sinkronisasi
        $totalPemasukanVerified = Pemasukan::verified()->sum('jumlah');
        $pemasukanBulanIni = Pemasukan::verified()
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');

        // Saldo
        $saldo = $totalPemasukanVerified - $totalSemua;
        $saldoBulanIni = $pemasukanBulanIni - $totalBulanIni;
    @endphp

    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('keuangan.index') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">
                            <i class="fas fa-money-bill-wave text-red-500 mr-2"></i>Transaksi Pengeluaran
                        </h1>
                        <p class="text-gray-600 mt-1">Kelola semua pengeluaran keuangan masjid</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    @if(auth()->user()->hasPermission('keuangan.create'))
                        <button type="button" id="btnTambah"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition shadow-lg">
                            <i class="fas fa-plus mr-2"></i>Tambah Pengeluaran
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Summary Cards - Sinkron dengan Pemasukan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Saldo --}}
            <div
                class="bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium uppercase tracking-wide">Saldo Kas</p>
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                        <p class="text-indigo-200 text-xs mt-1 flex items-center">
                            <i class="fas {{ $saldoBulanIni >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                            Bulan ini: {{ $saldoBulanIni >= 0 ? '+' : '' }}Rp
                            {{ number_format($saldoBulanIni, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-wallet text-3xl"></i>
                    </div>
                </div>
            </div>

            {{-- Pemasukan Bulan Ini --}}
            <div
                class="bg-gradient-to-br from-green-500 via-green-600 to-emerald-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase tracking-wide">Pemasukan Bulan Ini</p>
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h3>
                        <p class="text-green-200 text-xs mt-1">
                            <i class="fas fa-check-circle mr-1"></i>{{ date('F Y') }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-arrow-up text-3xl"></i>
                    </div>
                </div>
            </div>

            {{-- Pengeluaran Bulan Ini --}}
            <div
                class="bg-gradient-to-br from-red-500 via-red-600 to-rose-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium uppercase tracking-wide">Pengeluaran Bulan Ini</p>
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</h3>
                        <p class="text-red-200 text-xs mt-1">
                            <i class="fas fa-calendar mr-1"></i>{{ date('F Y') }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-arrow-down text-3xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Pengeluaran --}}
            <div
                class="bg-gradient-to-br from-orange-500 via-orange-600 to-amber-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium uppercase tracking-wide">Total Pengeluaran</p>
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalSemua, 0, ',', '.') }}</h3>
                        <p class="text-orange-200 text-xs mt-1">
                            <i class="fas fa-chart-line mr-1"></i>Sepanjang waktu
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-receipt text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Comparison Chart --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-chart-bar text-blue-500 mr-2"></i>Perbandingan Pemasukan vs Pengeluaran
                    </h3>
                    <span class="text-sm text-gray-500">6 Bulan Terakhir</span>
                </div>
                <div class="h-64">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>

            {{-- Pie Chart --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-pie text-purple-500 mr-2"></i>Komposisi Pengeluaran
                </h3>
                <div class="h-48">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="mt-4 space-y-2 max-h-32 overflow-y-auto">
                    @foreach($labels as $index => $label)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 flex items-center">
                                <span class="w-3 h-3 rounded-full mr-2"
                                    style="background-color: {{ ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#6B7280'][$index % 7] }}"></span>
                                {{ $label }}
                            </span>
                            <span class="font-medium text-gray-800">Rp
                                {{ number_format($dataset[$index] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Filter & Actions --}}
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari transaksi..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select id="filterKategori"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $cat)
                            <option value="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('keuangan.pengeluaran.cetak') }}" method="GET" target="_blank"
                        class="flex gap-2">
                        <select name="bulan" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                            @endfor
                        </select>
                        <select name="tahun" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                            <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                            <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                        </select>
                        <button type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center gap-2">
                            <i class="fas fa-file-pdf"></i>Export PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Data Table --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-list text-gray-500 mr-2"></i>Daftar Pengeluaran
                    </h3>
                    <span class="text-sm text-gray-500">Total: {{ $pengeluaran->count() }} transaksi</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="dataTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Judul & Deskripsi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Bukti</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($pengeluaran as $item)
                            <tr class="hover:bg-gray-50 transition" data-kategori="{{ $item->kategori->nama_kategori ?? '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                                            <span
                                                class="text-red-600 font-bold text-sm">{{ Carbon::parse($item->tanggal)->format('d') }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ Carbon::parse($item->tanggal)->format('M Y') }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ Carbon::parse($item->tanggal)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">{{ $item->judul_pengeluaran }}</p>
                                    <p class="text-sm text-gray-500 truncate max-w-xs">{{ $item->deskripsi ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $item->kategori->nama_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-red-600">-Rp
                                        {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->bukti_transaksi)
                                        <a href="{{ asset('storage/' . $item->bukti_transaksi) }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition text-xs">
                                            <i class="fas fa-image mr-1"></i>Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if(auth()->user()->hasPermission('keuangan.update'))
                                            <button
                                                onclick="editData({{ $item->id }}, '{{ addslashes($item->judul_pengeluaran) }}', '{{ $item->kategori_id }}', '{{ $item->jumlah }}', '{{ $item->tanggal }}', '{{ addslashes($item->deskripsi) }}')"
                                                class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        @if(auth()->user()->hasPermission('keuangan.delete'))
                                            <form action="{{ route('keuangan.pengeluaran.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus pengeluaran ini?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                                        <p class="text-lg text-gray-500">Belum ada data pengeluaran</p>
                                        <p class="text-sm text-gray-400">Klik tombol "Tambah Pengeluaran" untuk mulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah/Edit --}}
    <div id="modalForm" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
        x-data="{ open: false }" x-show="open" x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all"
            @click.away="document.getElementById('modalForm').classList.add('hidden')">
            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold" id="modalTitle">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Pengeluaran
                    </h3>
                    <button onclick="closeModal()" class="text-white hover:text-red-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="formPengeluaran" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading text-gray-400 mr-1"></i>Judul Pengeluaran <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul_pengeluaran" id="inputJudul" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Contoh: Pembayaran listrik">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tags text-gray-400 mr-1"></i>Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori_id" id="inputKategori" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategori as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar text-gray-400 mr-1"></i>Tanggal <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="inputTanggal" required value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-money-bill text-gray-400 mr-1"></i>Jumlah (Rp) <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah" id="inputJumlah" required min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                            placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left text-gray-400 mr-1"></i>Deskripsi
                        </label>
                        <textarea name="deskripsi" id="inputDeskripsi" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                            placeholder="Keterangan tambahan..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image text-gray-400 mr-1"></i>Bukti Transaksi
                        </label>
                        <input type="file" name="bukti_transaksi" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition font-medium">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        // Data untuk chart perbandingan 6 bulan
        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('M');

            $chartPemasukan[] = Pemasukan::verified()
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');

            $chartPengeluaran[] = \App\Models\Pengeluaran::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');
        }
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Comparison Chart
            const compCtx = document.getElementById('comparisonChart').getContext('2d');
            new Chart(compCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: {!! json_encode($chartPemasukan) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: '#10B981',
                            borderWidth: 2,
                            borderRadius: 8,
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($chartPengeluaran) !!},
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: '#EF4444',
                            borderWidth: 2,
                            borderRadius: 8,
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
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        data: {!! json_encode($dataset) !!},
                        backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#6B7280'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });

        // Modal Functions
        document.getElementById('btnTambah')?.addEventListener('click', function () {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle mr-2"></i>Tambah Pengeluaran';
            document.getElementById('formPengeluaran').action = "{{ route('keuangan.pengeluaran.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formPengeluaran').reset();
            document.getElementById('inputTanggal').value = "{{ date('Y-m-d') }}";
            document.getElementById('modalForm').classList.remove('hidden');
        });

        function editData(id, judul, kategoriId, jumlah, tanggal, deskripsi) {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Pengeluaran';
            document.getElementById('formPengeluaran').action = "{{ url('keuangan/pengeluaran') }}/" + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('inputJudul').value = judul;
            document.getElementById('inputKategori').value = kategoriId;
            document.getElementById('inputJumlah').value = jumlah;
            document.getElementById('inputTanggal').value = tanggal;
            document.getElementById('inputDeskripsi').value = deskripsi;
            document.getElementById('modalForm').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modalForm').classList.add('hidden');
        }

        // Search & Filter
        document.getElementById('searchInput').addEventListener('keyup', filterTable);
        document.getElementById('filterKategori').addEventListener('change', filterTable);

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const kategori = document.getElementById('filterKategori').value.toLowerCase();
            const rows = document.querySelectorAll('#dataTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const rowKategori = row.dataset.kategori?.toLowerCase() || '';
                const matchSearch = text.includes(search);
                const matchKategori = !kategori || rowKategori.includes(kategori);
                row.style.display = matchSearch && matchKategori ? '' : 'none';
            });
        }
    </script>
@endsection