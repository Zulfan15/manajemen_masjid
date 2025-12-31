@extends('layouts.app')
@section('title', 'Pengumuman Kegiatan')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-bullhorn text-green-700 mr-2"></i>Pengumuman Kegiatan
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola pengumuman untuk kegiatan dan acara masjid</p>
                </div>
                @if (auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.create'))
                    <a href="{{ route('kegiatan.pengumuman.create') }}"
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Pengumuman
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
                            <p class="text-sm opacity-90">Total Pengumuman</p>
                            <h3 class="text-2xl font-bold">{{ $stats['total'] }}</h3>
                        </div>
                        <i class="fas fa-bullhorn text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Aktif</p>
                            <h3 class="text-2xl font-bold">{{ $stats['aktif'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Views</p>
                            <h3 class="text-2xl font-bold">{{ number_format($stats['total_views']) }}</h3>
                        </div>
                        <i class="fas fa-eye text-3xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Bulan Ini</p>
                            <h3 class="text-2xl font-bold">{{ $stats['bulan_ini'] }}</h3>
                        </div>
                        <i class="fas fa-calendar-alt text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <form method="GET" class="mb-6 flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengumuman..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <select name="kategori"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="kajian" {{ request('kategori') == 'kajian' ? 'selected' : '' }}>Kajian</option>
                    <option value="kegiatan" {{ request('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                    <option value="event" {{ request('kategori') == 'event' ? 'selected' : '' }}>Event</option>
                    <option value="umum" {{ request('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                </select>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </form>

            <!-- Pengumuman List -->
            <div class="space-y-4">
                @forelse($pengumumans as $pengumuman)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="{{ $pengumuman->getStatusBadgeClass() }} text-xs px-2 py-1 rounded">
                                        {{ ucfirst($pengumuman->status) }}
                                    </span>
                                    <span class="{{ $pengumuman->getPrioritasBadgeClass() }} text-xs px-2 py-1 rounded">
                                        {!! $pengumuman->getKategoriIcon() !!} {{ ucfirst($pengumuman->kategori) }}
                                    </span>
                                    @if ($pengumuman->prioritas != 'normal')
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ ucfirst($pengumuman->prioritas) }}
                                        </span>
                                    @endif
                                    <span class="text-sm text-gray-500">
                                        <i
                                            class="fas fa-calendar mr-1"></i>{{ $pengumuman->tanggal_mulai->format('d M Y') }}
                                    </span>
                                </div>
                                <a href="{{ route('kegiatan.pengumuman.show', $pengumuman) }}"
                                    class="text-lg font-semibold text-gray-800 hover:text-green-700 mb-2 block">
                                    {{ $pengumuman->judul }}
                                </a>
                                <p class="text-gray-600 mb-3">{{ $pengumuman->getExcerpt() }}</p>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span><i class="fas fa-eye mr-1"></i>{{ $pengumuman->views }} views</span>
                                    <span><i class="fas fa-user mr-1"></i>{{ $pengumuman->creator->name ?? 'N/A' }}</span>
                                    @if ($pengumuman->kegiatan)
                                        <span><i
                                                class="fas fa-link mr-1"></i>{{ $pengumuman->kegiatan->nama_kegiatan }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <a href="{{ route('kegiatan.pengumuman.show', $pengumuman) }}"
                                    class="text-green-600 hover:text-green-800 p-2" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if (auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.update'))
                                    <a href="{{ route('kegiatan.pengumuman.edit', $pengumuman) }}"
                                        class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                @if (auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.delete'))
                                    <form action="{{ route('kegiatan.pengumuman.destroy', $pengumuman) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
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
                        <i class="fas fa-bullhorn text-6xl mb-4 text-gray-300"></i>
                        <h3 class="text-xl font-semibold mb-2">Belum Ada Pengumuman</h3>
                        <p class="mb-4">Mulai buat pengumuman untuk kegiatan masjid</p>
                        @if (auth()->user()->isSuperAdmin() || auth()->user()->can('kegiatan.create'))
                            <a href="{{ route('kegiatan.pengumuman.create') }}"
                                class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition inline-block">
                                <i class="fas fa-plus mr-2"></i>Tambah Pengumuman
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($pengumumans->hasPages())
                <div class="mt-6">
                    {{ $pengumumans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
