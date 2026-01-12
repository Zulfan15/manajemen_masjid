@extends('layouts.app')
@section('title', 'Laporan ZIS')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>Laporan ZIS
                    </h1>
                    <p class="text-gray-600 mt-1">Laporan distribusi Zakat, Infak, dan Sedekah</p>
                </div>
                <a href="{{ route('zis.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <!-- Filter -->
            <form action="{{ route('zis.laporan.index') }}" method="GET" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" value="{{ $tgl_mulai }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai" value="{{ $tgl_selesai }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('zis.laporan.index') }}"
                            class="block w-full px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-center">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-green-50 p-6 rounded-lg border-l-4 border-green-500">
                    <p class="text-sm text-green-600 mb-1">Total Pemasukan</p>
                    <p class="text-2xl font-bold text-green-700">Rp {{ number_format($total_masuk, 0, ',', '.') }}</p>
                    <p class="text-sm text-green-500">{{ $pemasukan->count() }} transaksi</p>
                </div>
                <div class="bg-red-50 p-6 rounded-lg border-l-4 border-red-500">
                    <p class="text-sm text-red-600 mb-1">Total Penyaluran</p>
                    <p class="text-2xl font-bold text-red-700">Rp {{ number_format($total_keluar, 0, ',', '.') }}</p>
                    <p class="text-sm text-red-500">{{ $pengeluaran->count() }} penyaluran</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm text-blue-600 mb-1">Saldo Periode</p>
                    <p class="text-2xl font-bold text-blue-700">Rp
                        {{ number_format($total_masuk - $total_keluar, 0, ',', '.') }}</p>
                    <p class="text-sm text-blue-500">{{ \Carbon\Carbon::parse($tgl_mulai)->format('d M') }} -
                        {{ \Carbon\Carbon::parse($tgl_selesai)->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Tabs -->
            <div x-data="{ activeTab: 'pemasukan' }">
                <div class="border-b border-gray-200 mb-4">
                    <nav class="flex space-x-4">
                        <button @click="activeTab = 'pemasukan'"
                            :class="activeTab === 'pemasukan' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-3 px-4 border-b-2 font-medium text-sm transition">
                            <i class="fas fa-arrow-down mr-2"></i>Pemasukan ({{ $pemasukan->count() }})
                        </button>
                        <button @click="activeTab = 'penyaluran'"
                            :class="activeTab === 'penyaluran' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-3 px-4 border-b-2 font-medium text-sm transition">
                            <i class="fas fa-arrow-up mr-2"></i>Penyaluran ({{ $pengeluaran->count() }})
                        </button>
                    </nav>
                </div>

                <!-- Pemasukan Tab -->
                <div x-show="activeTab === 'pemasukan'">
                    @if($pemasukan->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Muzakki</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pemasukan as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->kode_transaksi }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-800">{{ $item->muzakki->nama_lengkap ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">{{ $item->jenis_transaksi }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold text-green-600">Rp
                                                {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Tidak ada pemasukan pada periode ini</p>
                        </div>
                    @endif
                </div>

                <!-- Penyaluran Tab -->
                <div x-show="activeTab === 'penyaluran'" style="display: none;">
                    @if($pengeluaran->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mustahiq
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pengeluaran as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($item->tanggal_penyaluran)->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->kode_penyaluran }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-800">{{ $item->mustahiq->nama_lengkap ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">{{ $item->mustahiq->kategori ?? '-' }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $item->jenis_bantuan }}</td>
                                            <td class="px-4 py-3 text-sm font-semibold text-red-600">Rp
                                                {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Tidak ada penyaluran pada periode ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection