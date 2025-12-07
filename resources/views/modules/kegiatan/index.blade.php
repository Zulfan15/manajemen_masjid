@extends('layouts.app')
@section('title', 'Kegiatan & Acara')
@section('content')
    <div class="container mx-auto">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-calendar-alt text-green-700 mr-2"></i>Kegiatan & Acara
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola kegiatan dan acara masjid</p>
                </div>
                @if (!auth()->user()->isSuperAdmin())
                    <a href="{{ route('kegiatan.create') }}"
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Kegiatan
                    </a>
                @endif
            </div>

            @if (auth()->user()->isSuperAdmin())
                <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong></p>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Kegiatan</p>
                            <h3 class="text-2xl font-bold">{{ $stats['total'] }}</h3>
                        </div>
                        <i class="fas fa-calendar-check text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Mendatang</p>
                            <h3 class="text-2xl font-bold">{{ $stats['mendatang'] }}</h3>
                        </div>
                        <i class="fas fa-clock text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Berlangsung</p>
                            <h3 class="text-2xl font-bold">{{ $stats['berlangsung'] }}</h3>
                        </div>
                        <i class="fas fa-play-circle text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-500 to-gray-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Selesai</p>
                            <h3 class="text-2xl font-bold">{{ $stats['selesai'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <form method="GET" action="{{ route('kegiatan.index') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <select name="jenis"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Jenis</option>
                            <option value="rutin" {{ request('jenis') == 'rutin' ? 'selected' : '' }}>Rutin</option>
                            <option value="insidental" {{ request('jenis') == 'insidental' ? 'selected' : '' }}>Insidental
                            </option>
                            <option value="event_khusus" {{ request('jenis') == 'event_khusus' ? 'selected' : '' }}>Event
                                Khusus</option>
                        </select>
                    </div>
                    <div>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="direncanakan" {{ request('status') == 'direncanakan' ? 'selected' : '' }}>
                                Direncanakan</option>
                            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>
                                Berlangsung</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                            </option>
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Kegiatan List -->
            <div class="space-y-4">
                @forelse($kegiatans as $kegiatan)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="px-2 py-1 {{ $kegiatan->getStatusBadgeClass() }} text-xs rounded">
                                        {{ ucfirst($kegiatan->status) }}
                                    </span>
                                    <span class="px-2 py-1 {{ $kegiatan->getJenisBadgeClass() }} text-xs rounded">
                                        {{ ucfirst(str_replace('_', ' ', $kegiatan->jenis_kegiatan)) }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>{{ $kegiatan->tanggal_mulai->format('d M Y') }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        <i
                                            class="fas fa-clock mr-1"></i>{{ date('H:i', strtotime($kegiatan->waktu_mulai)) }}
                                    </span>
                                    @if ($kegiatan->kuota_peserta)
                                        <span class="text-sm text-gray-500">
                                            <i
                                                class="fas fa-users mr-1"></i>{{ $kegiatan->jumlah_peserta }}/{{ $kegiatan->kuota_peserta }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('kegiatan.show', $kegiatan->id) }}"
                                    class="text-lg font-semibold text-gray-800 hover:text-green-700">
                                    <i class="fas {{ $kegiatan->getKategoriIcon() }} text-green-700 mr-1"></i>
                                    {{ $kegiatan->nama_kegiatan }}
                                </a>
                                <p class="text-gray-600 mt-1 line-clamp-2">{{ $kegiatan->deskripsi }}</p>
                                <div class="flex items-center gap-4 text-sm text-gray-500 mt-2">
                                    <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $kegiatan->lokasi }}</span>
                                    @if ($kegiatan->pic)
                                        <span><i class="fas fa-user mr-1"></i>{{ $kegiatan->pic }}</span>
                                    @endif
                                    @if ($kegiatan->creator)
                                        <span><i class="fas fa-user-edit mr-1"></i>{{ $kegiatan->creator->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <a href="{{ route('kegiatan.show', $kegiatan->id) }}"
                                    class="text-blue-600 hover:text-blue-800 p-2" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (!auth()->user()->isSuperAdmin())
                                    <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
                                        class="text-green-600 hover:text-green-800 p-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-2" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-gray-500">
                        <i class="fas fa-calendar-alt text-6xl mb-4 text-gray-300"></i>
                        <h3 class="text-xl font-semibold mb-2">Belum Ada Kegiatan</h3>
                        <p class="mb-4">Mulai tambah kegiatan untuk masjid</p>
                        @if (!auth()->user()->isSuperAdmin())
                            <a href="{{ route('kegiatan.create') }}"
                                class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition inline-block">
                                <i class="fas fa-plus mr-2"></i>Tambah Kegiatan
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($kegiatans->hasPages())
                <div class="mt-6">
                    {{ $kegiatans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
