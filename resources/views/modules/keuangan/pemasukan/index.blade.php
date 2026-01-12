@extends('layouts.app')

@section('title', 'Transaksi Pemasukan')

@section('content')
    @php
        use App\Models\Pengeluaran;
        use Carbon\Carbon;

        // Data pengeluaran untuk sinkronisasi
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');

        // Saldo
        $saldo = $totalPemasukan - $totalPengeluaran;
        $saldoBulanIni = $bulanIni - $pengeluaranBulanIni;
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
                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Transaksi Pemasukan
                        </h1>
                        <p class="text-gray-600 mt-1">Kelola semua pemasukan keuangan masjid</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    @if(auth()->user()->canManageKeuangan())
                        <button type="button" onclick="openCreateModal()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition shadow-lg">
                            <i class="fas fa-plus mr-2"></i>Tambah Pemasukan
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Summary Cards - Sinkron dengan Pengeluaran --}}
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
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($bulanIni, 0, ',', '.') }}</h3>
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
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h3>
                        <p class="text-red-200 text-xs mt-1">
                            <i class="fas fa-calendar mr-1"></i>{{ date('F Y') }}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-arrow-down text-3xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Pemasukan --}}
            <div
                class="bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-sm font-medium uppercase tracking-wide">Total Pemasukan</p>
                        <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                        <p class="text-emerald-200 text-xs mt-1">
                            <i class="fas fa-chart-line mr-1"></i>Sepanjang waktu
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-coins text-3xl"></i>
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

            {{-- Pie Chart - Jenis Pemasukan --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-pie text-green-500 mr-2"></i>Komposisi Pemasukan
                </h3>
                <div class="h-48">
                    <canvas id="pieChart"></canvas>
                </div>
                @php
                    $pemasukanPerJenis = \App\Models\Pemasukan::verified()
                        ->selectRaw('jenis, SUM(jumlah) as total')
                        ->groupBy('jenis')
                        ->pluck('total', 'jenis');
                @endphp
                <div class="mt-4 space-y-2 max-h-32 overflow-y-auto">
                    @foreach($pemasukanPerJenis as $jenis => $total)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 flex items-center">
                                <span class="w-3 h-3 rounded-full mr-2 bg-green-500"></span>
                                {{ $jenis }}
                            </span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Filter & Search --}}
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari transaksi..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select id="filterJenis"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Jenis</option>
                        <option value="Infaq">Infaq</option>
                        <option value="Sedekah">Sedekah</option>
                        <option value="Zakat">Zakat</option>
                        <option value="Donasi">Donasi</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    <select id="filterStatus"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Status</option>
                        <option value="verified">Verified</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Data Table --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-list text-gray-500 mr-2"></i>Daftar Pemasukan
                    </h3>
                    <span class="text-sm text-gray-500">Total: {{ $data->count() }} transaksi</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="dataTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Sumber</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            @if(auth()->user()->canManageKeuangan())
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($data as $index => $item)
                            <tr class="hover:bg-gray-50 transition" data-jenis="{{ $item->jenis }}"
                                data-status="{{ $item->status }}">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                                            <span
                                                class="text-green-600 font-bold text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('M Y') }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $item->jenis }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $item->sumber }}</p>
                                    <p class="text-sm text-gray-500 truncate max-w-xs">{{ $item->keterangan ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-green-600">+Rp
                                        {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status == 'verified')
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Verified
                                        </span>
                                    @elseif($item->status == 'pending')
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Rejected
                                        </span>
                                    @endif
                                </td>
                                @if(auth()->user()->canManageKeuangan())
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="editData({{ $item->id }})"
                                                class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteData({{ $item->id }})"
                                                class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                                        <p class="text-lg text-gray-500">Belum ada data pemasukan</p>
                                        <p class="text-sm text-gray-400">Klik tombol "Tambah Pemasukan" untuk mulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Pemasukan Baru
                    </h3>
                    <button onclick="closeCreateModal()" class="text-white hover:text-green-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form id="createForm" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis <span
                                    class="text-red-500">*</span></label>
                            <select name="jenis" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="">Pilih Jenis</option>
                                <option value="Infaq">Infaq</option>
                                <option value="Sedekah">Sedekah</option>
                                <option value="Zakat">Zakat</option>
                                <option value="Donasi">Donasi</option>
                                <option value="Sewa">Sewa</option>
                                <option value="Usaha">Usaha</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="sumber" required placeholder="Nama donatur/sumber dana"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" required min="1" placeholder="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeCreateModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">
                        <i class="fas fa-edit mr-2"></i>Edit Pemasukan
                    </h3>
                    <button onclick="closeEditModal()" class="text-white hover:text-blue-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form id="editForm" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" id="edit_tanggal" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis <span
                                    class="text-red-500">*</span></label>
                            <select name="jenis" id="edit_jenis" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Jenis</option>
                                <option value="Infaq">Infaq</option>
                                <option value="Sedekah">Sedekah</option>
                                <option value="Zakat">Zakat</option>
                                <option value="Donasi">Donasi</option>
                                <option value="Sewa">Sewa</option>
                                <option value="Usaha">Usaha</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="sumber" id="edit_sumber" required placeholder="Nama donatur/sumber dana"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" id="edit_jumlah" required min="1" placeholder="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" rows="3" placeholder="Keterangan tambahan..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition">
                        <i class="fas fa-save mr-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        // Data untuk chart
        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('M');

            $chartPemasukan[] = \App\Models\Pemasukan::verified()
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');

            $chartPengeluaran[] = Pengeluaran::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');
        }
    @endphp

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Comparison Chart
                new Chart(document.getElementById('comparisonChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: {!! json_encode($chartPemasukan) !!},
                                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                borderRadius: 8,
                            },
                            {
                                label: 'Pengeluaran',
                                data: {!! json_encode($chartPengeluaran) !!},
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                borderRadius: 8,
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
                                ticks: { callback: val => 'Rp ' + val.toLocaleString('id-ID') }
                            }
                        }
                    }
                });

                // Pie Chart
                new Chart(document.getElementById('pieChart').getContext('2d'), {
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
            });

            function openCreateModal() {
                document.getElementById('createModal').classList.remove('hidden');
            }

            function closeCreateModal() {
                document.getElementById('createModal').classList.add('hidden');
                document.getElementById('createForm').reset();
            }

            document.getElementById('createForm').addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch("{{ route('keuangan.pemasukan.store') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: formData
                    });

                    if (response.ok) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data pemasukan berhasil ditambahkan', timer: 2000 })
                            .then(() => window.location.reload());
                    } else {
                        throw new Error('Gagal menyimpan data');
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: error.message });
                }
            });

            function deleteData(id) {
                Swal.fire({
                    title: 'Hapus Data?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const response = await fetch(`{{ url('keuangan/pemasukan') }}/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        });
                        if (response.ok) {
                            Swal.fire('Terhapus!', 'Data berhasil dihapus', 'success').then(() => window.location.reload());
                        }
                    }
                });
            }

            // Edit Functions
            async function editData(id) {
                try {
                    // Fetch data from server
                    const response = await fetch(`{{ url('keuangan/pemasukan') }}/${id}/data`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data');
                    }
                    
                    const data = await response.json();
                    
                    // Populate form fields
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_tanggal').value = data.tanggal ? data.tanggal.split('T')[0] : '';
                    document.getElementById('edit_jenis').value = data.jenis;
                    document.getElementById('edit_sumber').value = data.sumber || '';
                    document.getElementById('edit_jumlah').value = data.jumlah;
                    document.getElementById('edit_keterangan').value = data.keterangan || '';
                    
                    // Open modal
                    openEditModal();
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: error.message });
                }
            }

            function openEditModal() {
                document.getElementById('editModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
                document.getElementById('editForm').reset();
            }

            // Edit form submission
            document.getElementById('editForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const id = document.getElementById('edit_id').value;
                const formData = new FormData(this);
                
                try {
                    const response = await fetch(`{{ url('keuangan/pemasukan') }}/${id}`, {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-HTTP-Method-Override': 'PUT'
                        },
                        body: formData
                    });
                    
                    if (response.ok) {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Berhasil!', 
                            text: 'Data pemasukan berhasil diperbarui', 
                            timer: 2000 
                        }).then(() => window.location.reload());
                    } else {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Gagal memperbarui data');
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: error.message });
                }
            });

            // Filters
            document.getElementById('searchInput').addEventListener('keyup', filterTable);
            document.getElementById('filterJenis').addEventListener('change', filterTable);
            document.getElementById('filterStatus').addEventListener('change', filterTable);

            function filterTable() {
                const search = document.getElementById('searchInput').value.toLowerCase();
                const jenis = document.getElementById('filterJenis').value.toLowerCase();
                const status = document.getElementById('filterStatus').value.toLowerCase();

                document.querySelectorAll('#dataTable tbody tr').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const rowJenis = row.dataset.jenis?.toLowerCase() || '';
                    const rowStatus = row.dataset.status?.toLowerCase() || '';
                    const match = text.includes(search) && (!jenis || rowJenis.includes(jenis)) && (!status || rowStatus.includes(status));
                    row.style.display = match ? '' : 'none';
                });
            }
        </script>
    @endpush
@endsection