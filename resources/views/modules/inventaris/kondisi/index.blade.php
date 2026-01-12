@extends('layouts.app')

@section('title', 'Kondisi Barang')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-clipboard-check text-orange-600 mr-3"></i>
                Pemeriksaan Kondisi Barang
            </h1>
            <p class="text-gray-600 mt-2">Riwayat pemeriksaan kondisi aset masjid</p>
        </div>
        <a href="{{ route('inventaris.kondisi.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-lg shadow transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Pemeriksaan
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('inventaris.kondisi.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kondisi</label>
                <select name="kondisi" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="perlu_perbaikan" {{ request('kondisi') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Aset</label>
                <select name="aset_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Aset</option>
                    @foreach($asets as $aset)
                        <option value="{{ $aset->aset_id }}" {{ request('aset_id') == $aset->aset_id ? 'selected' : '' }}>
                            {{ $aset->nama_aset }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-orange-600 text-white px-4 py-2.5 rounded-lg hover:bg-orange-700 transition">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('inventaris.kondisi.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Periksa</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pemeriksa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Catatan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kondisiBarang as $kondisi)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-orange-100 rounded-lg mr-3">
                                        <i class="fas fa-box text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $kondisi->aset->nama_aset ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $kondisi->aset->kategori ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700">{{ $kondisi->tanggal_pemeriksaan ? \Carbon\Carbon::parse($kondisi->tanggal_pemeriksaan)->format('d M Y') : '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $kondisiColors = [
                                        'baik' => 'bg-green-100 text-green-800',
                                        'perlu_perbaikan' => 'bg-yellow-100 text-yellow-800',
                                        'rusak_berat' => 'bg-red-100 text-red-800',
                                    ];
                                    $kondisiLabels = [
                                        'baik' => 'Baik',
                                        'perlu_perbaikan' => 'Perlu Perbaikan',
                                        'rusak_berat' => 'Rusak Berat',
                                    ];
                                @endphp
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $kondisiColors[$kondisi->kondisi] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $kondisiLabels[$kondisi->kondisi] ?? $kondisi->kondisi }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700">{{ $kondisi->petugas->name ?? $kondisi->petugas_pemeriksa ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-600 text-sm">{{ Str::limit($kondisi->catatan, 50) ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('inventaris.kondisi.show', $kondisi->kondisi_id) }}" class="p-2 text-gray-600 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventaris.kondisi.edit', $kondisi->kondisi_id) }}" class="p-2 text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('inventaris.kondisi.destroy', $kondisi->kondisi_id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-clipboard-list text-3xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Pemeriksaan</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan pemeriksaan kondisi baru.</p>
                                    <a href="{{ route('inventaris.kondisi.create') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                                        <i class="fas fa-plus mr-2"></i> Tambah Pemeriksaan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($kondisiBarang->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $kondisiBarang->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
