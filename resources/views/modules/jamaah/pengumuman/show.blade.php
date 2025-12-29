@extends('layouts.app')

@section('title', 'Detail Pengumuman')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header -->
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-3 py-1 {{ $pengumuman->getPrioritasBadgeClass() }} text-sm rounded-full font-semibold">
                        {{ ucfirst($pengumuman->prioritas) }}
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full">
                        <i class="fas {{ $pengumuman->getKategoriIcon() }} mr-1"></i>
                        {{ ucfirst($pengumuman->kategori) }}
                    </span>
                    <span class="px-3 py-1 {{ $pengumuman->getStatusBadgeClass() }} text-sm rounded-full">
                        {{ $pengumuman->isAktif() ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-bullhorn text-green-700 mr-2"></i>
                    {{ $pengumuman->judul }}
                </h1>
            </div>
            <div>
                <a href="{{ route('jamaah.pengumuman.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="md:col-span-2 space-y-6">
                <!-- Content -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-align-left text-green-700 mr-2"></i>Isi Pengumuman
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($pengumuman->konten)) !!}
                        </div>
                    </div>
                </div>

                <!-- Related Kegiatan -->
                @if($pengumuman->kegiatan)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">
                            <i class="fas fa-calendar text-green-700 mr-2"></i>Kegiatan Terkait
                        </h3>
                        <div class="bg-green-50 p-5 rounded-lg border border-green-200">
                            <div class="flex items-start gap-4">
                                <!-- Kegiatan Icon/Image -->
                                <div class="flex-shrink-0">
                                    @if($pengumuman->kegiatan->gambar)
                                        <img src="{{ asset('storage/' . $pengumuman->kegiatan->gambar) }}" 
                                             alt="{{ $pengumuman->kegiatan->nama_kegiatan }}"
                                             class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-green-600 rounded-lg flex items-center justify-center">
                                            <i class="fas {{ $pengumuman->kegiatan->getKategoriIcon() }} text-white text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Kegiatan Info -->
                                <div class="flex-1">
                                    <h4 class="font-bold text-green-800 mb-2 text-lg">
                                        {{ $pengumuman->kegiatan->nama_kegiatan }}
                                    </h4>
                                    <div class="space-y-1 text-sm text-green-700 mb-3">
                                        <p>
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            {{ $pengumuman->kegiatan->tanggal_mulai->format('d F Y') }}
                                        </p>
                                        <p>
                                            <i class="fas fa-clock mr-2"></i>
                                            {{ date('H:i', strtotime($pengumuman->kegiatan->waktu_mulai)) }} WIB
                                        </p>
                                        <p>
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            {{ $pengumuman->kegiatan->lokasi }}
                                        </p>
                                    </div>
                                    
                                    <a href="{{ route('jamaah.kegiatan.show', $pengumuman->kegiatan->id) }}" 
                                       class="inline-block px-5 py-2.5 bg-green-700 text-white rounded-lg hover:bg-green-800 transition font-medium">
                                        <i class="fas fa-external-link-alt mr-2"></i>Lihat Detail Kegiatan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-4">
                <!-- Date Info -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-3">Periode Pengumuman</h4>
                    <div class="space-y-2 text-sm text-blue-700">
                        <p>
                            <i class="fas fa-calendar-check mr-2"></i>
                            <strong>Mulai:</strong><br>
                            <span class="ml-6">{{ $pengumuman->tanggal_mulai->format('d F Y') }}</span>
                        </p>
                        @if($pengumuman->tanggal_berakhir)
                            <p>
                                <i class="fas fa-calendar-times mr-2"></i>
                                <strong>Berakhir:</strong><br>
                                <span class="ml-6">{{ $pengumuman->tanggal_berakhir->format('d F Y') }}</span>
                            </p>
                        @else
                            <p class="text-xs italic">Tidak ada batas waktu berakhir</p>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-purple-800 mb-2">Statistik</h4>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ $pengumuman->views }}
                    </p>
                    <p class="text-sm text-purple-600">kali dilihat</p>
                </div>

                <!-- Creator Info -->
                @if($pengumuman->creator)
                    <div class="bg-gray-50 p-4 rounded-lg text-sm">
                        <p class="text-gray-500 mb-1">Dibuat oleh:</p>
                        <p class="text-gray-800 font-medium">{{ $pengumuman->creator->name }}</p>
                        <p class="text-gray-500 text-xs mt-2">
                            {{ $pengumuman->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
