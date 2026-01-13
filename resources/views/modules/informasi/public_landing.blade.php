@extends('layouts.app')
@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
@endphp

@section('content')
    <!-- HERO SECTION -->
    <div class="relative bg-gradient-to-br from-green-600 to-teal-800 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" width="100%" height="100%" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <g fill="none" fill-rule="evenodd">
                    <g fill="#ffffff" fill-opacity="1">
                        <path d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/>
                    </g>
                </g>
            </svg>
        </div>

        <div class="container mx-auto px-4 py-20 md:py-32 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow-md">Selamat Datang di Portal Masjid</h1>
            <p class="text-xl md:text-2xl text-green-100 max-w-2xl mx-auto mb-8">
                Pusat informasi kegiatan, berita, dan syiar islam untuk jamaah dan masyarakat umum.
            </p>
            <div class="flex justify-center gap-4">
                <a href="#jadwal" class="bg-white text-green-700 px-6 py-3 rounded-full font-bold hover:bg-green-50 transition shadow-lg">
                    Lihat Jadwal Sholat
                </a>
                <a href="#berita" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-green-700 transition">
                    Baca Berita
                </a>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 w-full leading-none">
            <svg class="block w-full h-12 md:h-24" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#f3f4f6" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,250.7C960,235,1056,181,1152,165.3C1248,149,1344,171,1392,181.3L1440,192V320H1392C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320H0Z"></path>
            </svg>
        </div>
    </div>

    <!-- JADWAL SHOLAT (Floating Card) -->
    <section id="jadwal" class="container mx-auto px-4 -mt-16 relative z-20 mb-16">
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 max-w-5xl mx-auto border border-gray-100">
            <div class="flex items-center justify-between mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clock text-green-600 mr-3"></i>Jadwal Sholat Hari Ini
                </h2>
                <span class="text-gray-500 font-medium bg-gray-100 px-4 py-1 rounded-full text-sm">
                    {{ \Carbon\Carbon::now()->format('d F Y') }} • Bandung
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                @foreach($jadwalSholat as $sholat => $waktu)
                    <div class="group p-4 rounded-xl bg-gray-50 hover:bg-green-600 transition-all duration-300 cursor-default">
                        <div class="text-xs text-gray-500 uppercase font-semibold mb-1 group-hover:text-green-100">{{ $sholat }}</div>
                        <div class="text-2xl md:text-3xl font-bold text-gray-800 group-hover:text-white">{{ $waktu }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CONTENT GRID -->
    <div class="container mx-auto px-4 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- LEFT COLUMN: PENGUMUMAN (1/3 width on large screens) -->
            <div class="lg:col-span-1 space-y-8">
                <section id="pengumuman">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-yellow-500 pl-3">
                            Pengumuman
                        </h2>
                    </div>

                    <div class="space-y-4">
                        @forelse($pengumuman->take(3) as $item)
                            <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-yellow-400 hover:shadow-lg transition">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded">
                                        <i class="fas fa-bullhorn mr-1"></i>Info
                                    </span>
                                    <span class="text-gray-400 text-xs">{{ $item->created_at->format('d M') }}</span>
                                </div>
                                <h3 class="font-bold text-gray-800 mb-2 leading-tight hover:text-green-600 transition">
                                    <a href="{{ route('public.info.show', $item->slug) }}">
                                        {{ $item->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-2">
                                    {{ Str::limit(strip_tags($item->content), 80) }}
                                </p>
                            </div>
                        @empty
                            <div class="bg-gray-50 rounded-xl p-6 text-center text-gray-500 border border-dashed border-gray-300">
                                <i class="fas fa-bullhorn text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada pengumuman terbaru.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <div class="bg-green-50 rounded-xl p-6 border border-green-100">
                    <h3 class="font-bold text-green-800 mb-2"><i class="fas fa-hand-holding-heart mr-2"></i>Mari Berinfaq</h3>
                    <p class="text-sm text-green-700 mb-4">Salurkan infaq terbaik Anda untuk operasional dan kemakmuran masjid.</p>
                    <a href="#" class="block text-center bg-green-600 text-white font-bold py-2 rounded-lg hover:bg-green-700 transition">
                        Salurkan Donasi
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: BERITA & ARTIKEL (2/3 width on large screens) -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- BERITA -->
                <section id="berita">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-green-600 pl-3">
                            Berita Utama
                        </h2>
                        <a href="{{ route('public.info.index', ['tab' => 'berita']) }}" class="text-green-600 hover:text-green-800 text-sm font-semibold">
                            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($berita->take(4) as $post)
                            <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 group">
                                <div class="relative h-48 overflow-hidden">
                                    @if($post->thumbnail)
                                        <img src="{{ asset('storage/' . $post->thumbnail) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-tr from-gray-200 to-gray-300 flex items-center justify-center">
                                            <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="absolute top-0 right-0 bg-green-600 text-white text-xs font-bold px-3 py-1 m-3 rounded-full">
                                        {{ $post->category ?? 'Berita' }}
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="text-xs text-gray-500 mb-2 flex items-center">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $post->created_at->format('d M Y') }}
                                    </div>
                                    <h3 class="font-bold text-lg text-gray-800 mb-2 leading-tight group-hover:text-green-600 transition">
                                        <a href="{{ route('public.info.show', $post->slug) }}">
                                            {{ Str::limit($post->title, 50) }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                        {{ Str::limit(strip_tags($post->content), 100) }}
                                    </p>
                                    <a href="{{ route('public.info.show', $post->slug) }}" class="text-green-600 font-semibold text-sm hover:underline">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </article>
                        @empty
                            <p class="col-span-2 text-center py-10 text-gray-500">Belum ada berita dipublikasikan.</p>
                        @endforelse
                    </div>
                </section>

                <!-- ARTIKEL (List Style) -->
                <section id="artikel" class="bg-gray-50 rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-purple-600 pl-3">
                            Artikel Dakwah
                        </h2>
                    </div>

                    <div class="space-y-6">
                        @forelse($artikel->take(3) as $item)
                        <div class="flex flex-col md:flex-row gap-6 bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 hover:text-purple-600 transition">
                                    <a href="{{ route('public.info.show', $item->slug) }}">{{ $item->title }}</a>
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                    {{ Str::limit(strip_tags($item->content), 120) }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="far fa-user mr-1"></i> {{ $item->author ?? 'Admin' }}
                                        <span class="mx-2">•</span>
                                        <i class="far fa-clock mr-1"></i> {{ $item->created_at->diffForHumans() }}
                                    </div>
                                    <a href="{{ route('public.info.show', $item->slug) }}" class="text-purple-600 text-sm font-semibold hover:underline">
                                        Baca <i class="fas fa-chevron-right text-xs ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                            <p class="text-center text-gray-500">Belum ada artikel dakwah.</p>
                        @endforelse
                    </div>
                </section>

            </div>
        </div>
    </div>

    <!-- SIMPLE FOOTER -->
    <footer class="bg-gray-800 text-gray-400 py-8 text-center mt-12">
        <div class="container mx-auto px-4">
            <p class="mb-2 font-semibold text-white">Sistem Manajemen Masjid</p>
            <p class="text-sm">© {{ date('Y') }} Manpro Masjid. All rights reserved.</p>
        </div>
    </footer>
@endsection