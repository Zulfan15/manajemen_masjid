@extends('layouts.app')

@section('title', 'Jadwal Perawatan')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-calendar-check text-teal-600 mr-3"></i>
                Jadwal Perawatan
            </h1>
            <p class="text-gray-600 mt-2">Kelola jadwal perawatan aset masjid</p>
        </div>
        <a href="{{ route('inventaris.perawatan.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg shadow transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Jadwal
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('inventaris.perawatan.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Status</option>
                    <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Aset</label>
                <select name="aset_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
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
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-teal-600 text-white px-4 py-2.5 rounded-lg hover:bg-teal-700 transition">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('inventaris.perawatan.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition">
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Perawatan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Petugas</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jadwalPerawatan as $jadwal)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-teal-100 rounded-lg mr-3">
                                        <i class="fas fa-tools text-teal-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $jadwal->aset->nama_aset ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $jadwal->aset->kategori ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-700">{{ $jadwal->jenis_perawatan }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700">{{ $jadwal->petugas->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'dijadwalkan' => 'bg-blue-100 text-blue-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        'dibatalkan' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'dijadwalkan' => 'Dijadwalkan',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                    ];
                                @endphp
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$jadwal->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$jadwal->status] ?? $jadwal->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('inventaris.perawatan.show', $jadwal->jadwal_id) }}" class="p-2 text-gray-600 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventaris.perawatan.edit', $jadwal->jadwal_id) }}" class="p-2 text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('inventaris.perawatan.destroy', $jadwal->jadwal_id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
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
                                        <i class="fas fa-calendar-times text-3xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Jadwal Perawatan</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan jadwal perawatan baru.</p>
                                    <a href="{{ route('inventaris.perawatan.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                                        <i class="fas fa-plus mr-2"></i> Tambah Jadwal
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($jadwalPerawatan->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $jadwalPerawatan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
