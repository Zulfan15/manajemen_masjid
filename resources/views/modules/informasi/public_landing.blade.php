@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp
@section('content')

<!-- JADWAL SHOLAT -->
<section id="jadwal" class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-l-4 border-green-600 pl-3">
        Jadwal Sholat Hari Ini
    </h2>

    <div class="bg-white text-gray-800 rounded-lg shadow p-6 max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach($jadwalSholat as $sholat => $waktu)
        <div class="text-center p-3 rounded bg-gray-50">
            <div class="text-xs text-gray-500 uppercase">{{ $sholat }}</div>
            <div class="text-2xl font-bold">{{ $waktu }}</div>
        </div>
        @endforeach
    </div>
</section>

<!-- PENGUMUMAN -->
<section id="info" class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-l-4 border-green-600 pl-3">
        Pengumuman Terbaru
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($pengumuman as $item)
        <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
            <h3 class="text-lg font-bold text-gray-800">
                <a href="{{ route('public.info.show', $item->slug) }}" class="hover:text-green-700">
                    {{ $item->title }}
                </a>
            </h3>
            <p class="text-gray-500 text-sm mt-1">{{ $item->created_at->format('d M Y') }}</p>
            <p class="text-gray-600 text-sm mt-3">
                {{ Str::limit(strip_tags($item->content), 90) }}
            </p>
            <a href="{{ route('public.info.show', $item->slug) }}" class="text-green-700 text-sm font-semibold mt-3 inline-block">
                Baca Selengkapnya →
            </a>
        </div>
        @empty
        <p class="col-span-3 text-center text-gray-500">Belum ada pengumuman.</p>
        @endforelse
    </div>
</section>

<!-- BERITA -->
<section class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-l-4 border-green-600 pl-3">
        Berita Masjid
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($berita as $post)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
            @if($post->thumbnail)
            <img src="{{ asset('storage/' . $post->thumbnail) }}"
                class="w-full h-64 object-cover">
        @else
            <div class="h-64 bg-gray-200 flex items-center justify-center">
                <i class="fas fa-image text-6xl text-gray-400"></i>
            </div>
        @endif

            <div class="p-6">
                <span class="text-xs font-bold text-green-600 uppercase">{{ $post->category }}</span>

                <h3 class="text-lg font-bold mt-2 text-gray-800 hover:text-green-700">
                    <a href="{{ route('public.info.show', $post->slug) }}">
                        {{ $post->title }}
                    </a>
                </h3>

                <p class="text-gray-600 text-sm mt-2">
                    {{ Str::limit(strip_tags($post->content), 100) }}
                </p>

                <a href="{{ route('public.info.show', $post->slug) }}" 
                   class="mt-4 inline-block text-green-700 font-semibold text-sm">
                   Baca Selengkapnya →
                </a>
            </div>
        </div>
        @empty
        <p class="col-span-3 text-center text-gray-500">Belum ada berita.</p>
        @endforelse
    </div>
</section>

<!-- ARTIKEL -->
<section class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-l-4 border-green-600 pl-3">
        Artikel Dakwah
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($artikel as $item)
        <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
            <h3 class="font-bold text-lg text-gray-800">
                <a href="{{ route('public.info.show', $item->slug) }}" class="hover:text-green-700">
                    {{ $item->title }}
                </a>
            </h3>
            <p class="text-gray-600 text-sm mt-2">
                {{ Str::limit(strip_tags($item->content), 120) }}
            </p>
            <a href="{{ route('public.info.show', $item->slug) }}" class="text-green-700 text-sm font-semibold mt-3 inline-block">
                Baca Selengkapnya →
            </a>
        </div>
        @empty
        <p class="col-span-3 text-center text-gray-500">Belum ada artikel.</p>
        @endforelse
    </div>
</section>

@endsection
