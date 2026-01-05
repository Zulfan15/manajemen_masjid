@extends('layouts.app')

@section('title', 'Kegiatan Masjid')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-calendar-alt text-green-700 mr-2"></i>Kegiatan Masjid
                </h1>
                <p class="text-gray-600 mt-2">Lihat daftar kegiatan dan acara masjid</p>
            </div>
        </div>

        <!-- Kegiatan Cards -->
        @if($kegiatans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kegiatans as $kegiatan)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                        <!-- Card Image/Poster -->
                        @if($kegiatan->gambar)
                            <div class="h-48 overflow-hidden bg-gray-200">
                                <img src="{{ asset('storage/' . $kegiatan->gambar) }}" 
                                     alt="{{ $kegiatan->nama_kegiatan }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center">
                                <i class="fas {{ $kegiatan->getKategoriIcon() }} text-white text-6xl opacity-50"></i>
                            </div>
                        @endif

                        <!-- Card Content -->
                        <div class="p-4">
                            <!-- Badges -->
                            <div class="flex gap-2 mb-3">
                                <span class="px-2 py-1 {{ $kegiatan->getStatusBadgeClass() }} text-xs rounded-full">
                                    {{ ucfirst($kegiatan->status) }}
                                </span>
                                <span class="px-2 py-1 {{ $kegiatan->getJenisBadgeClass() }} text-xs rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $kegiatan->jenis_kegiatan)) }}
                                </span>
                                
                                @if($kegiatan->peserta->isNotEmpty())
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>Sudah Terdaftar
                                    </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">
                                {{ $kegiatan->nama_kegiatan }}
                            </h3>

                            <!-- Info -->
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar w-5 text-green-700"></i>
                                    <span>{{ $kegiatan->tanggal_mulai->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock w-5 text-green-700"></i>
                                    <span>{{ date('H:i', strtotime($kegiatan->waktu_mulai)) }} WIB</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-5 text-green-700"></i>
                                    <span class="line-clamp-1">{{ $kegiatan->lokasi }}</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('jamaah.kegiatan.show', $kegiatan->id) }}" 
                               class="block w-full px-4 py-2 bg-green-700 text-white text-center rounded-lg hover:bg-green-800 transition">
                                <i class="fas fa-eye mr-2"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-calendar-alt text-6xl mb-4 text-gray-300"></i>
                <h3 class="text-xl font-semibold mb-2">Belum ada kegiatan yang tersedia</h3>
                <p class="text-sm">Kegiatan akan ditampilkan di sini ketika sudah tersedia</p>
            </div>
        @endif
    </div>
</div>
@endsection
