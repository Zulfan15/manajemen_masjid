@extends('layouts.app')

@section('title', 'Pengumuman Masjid')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-bullhorn text-green-700 mr-2"></i>Pengumuman Masjid
                </h1>
                <p class="text-gray-600 mt-2">Informasi dan pengumuman kegiatan masjid</p>
            </div>
        </div>

        <!-- Pengumuman List -->
        @if($pengumumen->count() > 0)
            <div class="space-y-4">
                @foreach($pengumumen as $item)
                    <div class="border-l-4 {{ $item->getPrioritasBadgeClass() }} border-opacity-50 bg-white rounded-lg shadow hover:shadow-md transition p-5">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Badges -->
                                <div class="flex gap-2 mb-3">
                                    <span class="px-3 py-1 {{ $item->getPrioritasBadgeClass() }} text-xs rounded-full font-semibold">
                                        {{ ucfirst($item->prioritas) }}
                                    </span>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                        <i class="fas {{ $item->getKategoriIcon() }} mr-1"></i>
                                        {{ ucfirst($item->kategori) }}
                                    </span>
                                    @if($item->kegiatan)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Terkait Kegiatan
                                        </span>
                                    @endif
                                </div>

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-800 mb-2">
                                    {{ $item->judul }}
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-gray-600 mb-3 line-clamp-3">
                                    {{ $item->getExcerpt(200) }}
                                </p>

                                <!-- Meta Info -->
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span>
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $item->tanggal_mulai->format('d M Y') }}
                                    </span>
                                    @if($item->tanggal_berakhir)
                                        <span>
                                            <i class="fas fa-arrow-right mr-1"></i>
                                            {{ $item->tanggal_berakhir->format('d M Y') }}
                                        </span>
                                    @endif
                                    <span>
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ $item->views }} kali dilihat
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="ml-4">
                                <a href="{{ route('jamaah.pengumuman.show', $item->id) }}" 
                                   class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition whitespace-nowrap">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-bullhorn text-6xl mb-4 text-gray-300"></i>
                <h3 class="text-xl font-semibold mb-2">Belum ada pengumuman</h3>
                <p class="text-sm">Pengumuman akan ditampilkan di sini ketika sudah tersedia</p>
            </div>
        @endif
    </div>
</div>
@endsection
