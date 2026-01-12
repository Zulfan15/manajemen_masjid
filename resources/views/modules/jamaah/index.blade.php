@extends('layouts.app')

@section('title', 'Manajemen Jamaah')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-users text-green-600 mr-2"></i>Manajemen Jamaah
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola data jamaah masjid</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('jamaah.export') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        <i class="fas fa-download mr-2"></i>Export
                    </a>
                    @if(!auth()->user()->isSuperAdmin())
                        <a href="{{ route('jamaah.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-user-plus mr-2"></i>Tambah Jamaah
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total Jamaah</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalJamaah ?? 0 }}</h3>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-users text-3xl text-blue-100"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Jamaah Baru Bulan Ini</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $jamaahBaruBulanIni ?? 0 }}</h3>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-user-plus text-3xl text-green-100"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Pengurus Aktif</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalRelawan ?? 0 }}</h3>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-user-tie text-3xl text-purple-100"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 text-sm">Tingkat Partisipasi</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $tingkatPartisipasi ?? 0 }}%</h3>
                        <div class="w-full bg-amber-400 bg-opacity-30 rounded-full h-2 mt-3">
                            <div class="bg-white h-2 rounded-full transition-all"
                                style="width: {{ $tingkatPartisipasi ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div class="bg-amber-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-chart-line text-3xl text-amber-100"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('jamaah.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Jamaah</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau No HP..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-search mr-1"></i> Cari
                    </button>
                    <a href="{{ route('jamaah.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel Jamaah --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Jamaah</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kontak</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Donasi</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Kegiatan</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jamaahs as $j)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            @if($j->foto)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($j->foto) }}"
                                                    alt="{{ $j->nama_lengkap }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($j->nama_lengkap, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('jamaah.show', $j->id) }}"
                                                class="text-sm font-medium text-gray-900 hover:text-green-600">
                                                {{ $j->nama_lengkap }}
                                            </a>
                                            @if(!$j->status_aktif)
                                                <span
                                                    class="ml-2 px-2 py-0.5 text-xs bg-red-100 text-red-600 rounded-full">Non-Aktif</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $j->no_hp ?? '-' }}</div>
                                    @if($j->user && $j->user->email)
                                        <div class="text-xs text-gray-500">{{ $j->user->email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($j->categories as $c)
                                            @php
                                                $colors = [
                                                    'Umum' => 'bg-blue-100 text-blue-800',
                                                    'Pengurus' => 'bg-purple-100 text-purple-800',
                                                    'Donatur' => 'bg-green-100 text-green-800',
                                                ];
                                                $color = $colors[$c->nama] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                                {{ $c->nama }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-medium text-gray-900">
                                        Rp
                                        {{ number_format($j->donations->where('status', 'confirmed')->sum('amount'), 0, ',', '.') }}
                                    </span>
                                    <div class="text-xs text-gray-500">
                                        {{ $j->donations->count() }} transaksi
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $j->kegiatanPeserta->count() ?? 0 }} kegiatan
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('jamaah.show', $j->id) }}" class="text-blue-600 hover:text-blue-800"
                                            title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!auth()->user()->isSuperAdmin())
                                            <a href="{{ route('jamaah.edit', $j->id) }}"
                                                class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('jamaah.role.edit', $j->id) }}"
                                                class="text-purple-600 hover:text-purple-800" title="Ubah Kategori">
                                                <i class="fas fa-user-tag"></i>
                                            </a>
                                            <form action="{{ route('jamaah.destroy', $j->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data jamaah ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-users text-5xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada data jamaah</p>
                                        <a href="{{ route('jamaah.create') }}" class="mt-3 text-green-600 hover:text-green-800">
                                            <i class="fas fa-plus mr-1"></i> Tambah Jamaah Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                alert('{{ session('success') }}');
            });
        </script>
    @endif
@endsection