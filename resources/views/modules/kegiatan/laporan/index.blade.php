@extends('layouts.app')
@section('title', 'Laporan Kegiatan')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-file-alt text-green-700 mr-2"></i>Laporan Kegiatan
                    </h1>
                    <p class="text-gray-600 mt-2">Dokumentasi dan laporan kegiatan masjid</p>
                </div>
                @if (auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.create'))
                    <a href="{{ route('kegiatan.laporan.create') }}"
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i>Buat Laporan
                    </a>
                @endif
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6">
                    <p class="text-green-700"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Laporan</p>
                            <h3 class="text-2xl font-bold">{{ $stats['total'] }}</h3>
                        </div>
                        <i class="fas fa-file-alt text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Bulan Ini</p>
                            <h3 class="text-2xl font-bold">{{ $stats['bulan_ini'] }}</h3>
                        </div>
                        <i class="fas fa-calendar-check text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Peserta Total</p>
                            <h3 class="text-2xl font-bold">{{ number_format($stats['total_peserta']) }}</h3>
                        </div>
                        <i class="fas fa-users text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Kegiatan Aktif</p>
                            <h3 class="text-2xl font-bold">{{ $stats['kegiatan_aktif'] }}</h3>
                        </div>
                        <i class="fas fa-tasks text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <form method="GET" class="mb-6 flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari laporan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <select name="jenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="this.form.submit()">
                    <option value="">Semua Jenis</option>
                    <option value="kajian" {{ request('jenis') == 'kajian' ? 'selected' : '' }}>Kajian</option>
                    <option value="sosial" {{ request('jenis') == 'sosial' ? 'selected' : '' }}>Sosial</option>
                    <option value="pendidikan" {{ request('jenis') == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                    <option value="perayaan" {{ request('jenis') == 'perayaan' ? 'selected' : '' }}>Perayaan</option>
                    <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                <input type="month" name="bulan" value="{{ request('bulan') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="this.form.submit()">
            </form>

            <!-- Laporan List -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kegiatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($laporans as $laporan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $laporan->nama_kegiatan }}</p>
                                        <p class="text-sm text-gray-500">
                                            <span class="{{ $laporan->getJenisBadgeClass() }} text-xs px-2 py-1 rounded">
                                                {{ ucfirst($laporan->jenis_kegiatan) }}
                                            </span>
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $laporan->tanggal_pelaksanaan->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <i
                                        class="fas fa-users mr-1"></i>{{ $laporan->jumlah_hadir }}/{{ $laporan->jumlah_peserta }}
                                    ({{ number_format($laporan->getPersentaseKehadiran(), 1) }}%)
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $laporan->getStatusBadgeClass() }} text-xs px-2 py-1 rounded">
                                        {{ ucfirst($laporan->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('kegiatan.laporan.show', $laporan) }}"
                                            class="text-blue-600 hover:text-blue-800" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('kegiatan.update')
                                            <a href="{{ route('kegiatan.laporan.edit', $laporan) }}"
                                                class="text-green-600 hover:text-green-800" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('kegiatan.laporan.download', $laporan) }}"
                                                class="text-purple-600 hover:text-purple-800" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endcan
                                        @can('kegiatan.delete')
                                            <form action="{{ route('kegiatan.laporan.destroy', $laporan) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fas fa-file-alt text-6xl mb-4 text-gray-300"></i>
                                    <h3 class="text-xl font-semibold mb-2">Belum Ada Laporan</h3>
                                    <p class="mb-4">Mulai buat laporan untuk kegiatan yang telah selesai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($laporans->hasPages())
                <div class="mt-6">
                    {{ $laporans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
