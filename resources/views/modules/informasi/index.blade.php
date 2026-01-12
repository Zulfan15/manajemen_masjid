@extends('layouts.app')

@section('title', 'Informasi & Pengumuman')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bullhorn text-green-600 mr-3"></i>
                        Informasi & Pengumuman
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola berita, pengumuman, dan artikel dakwah masjid</p>
                </div>

                @if(auth()->user()->hasPermission('informasi.create'))
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="bg-green-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-green-700 flex items-center transition">
                            <i class="fas fa-plus mr-2"></i> Tambah Informasi
                            <i class="fas fa-chevron-down ml-2 text-sm transition-transform"
                                :class="{ 'rotate-180': open }"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-56 bg-white shadow-lg rounded-lg border z-20">
                            <a href="{{ route('informasi.pengumuman.create') }}"
                                class="flex items-center px-4 py-3 hover:bg-gray-50 transition">
                                <i class="fas fa-bullhorn text-orange-500 mr-3"></i>
                                <span>Pengumuman Baru</span>
                            </a>
                            <a href="{{ route('informasi.berita.create') }}"
                                class="flex items-center px-4 py-3 hover:bg-gray-50 transition border-t">
                                <i class="fas fa-newspaper text-blue-500 mr-3"></i>
                                <span>Berita Baru</span>
                            </a>
                            <a href="{{ route('informasi.artikel.create') }}"
                                class="flex items-center px-4 py-3 hover:bg-gray-50 transition border-t">
                                <i class="fas fa-book-open text-purple-500 mr-3"></i>
                                <span>Artikel Dakwah</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-sm p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">Pengumuman</p>
                        <p class="text-3xl font-bold">{{ $pengumuman->count() }}</p>
                    </div>
                    <div class="p-3 bg-white/20 rounded-full">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Berita</p>
                        <p class="text-3xl font-bold">{{ $berita->count() }}</p>
                    </div>
                    <div class="p-3 bg-white/20 rounded-full">
                        <i class="fas fa-newspaper text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-sm p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Artikel Dakwah</p>
                        <p class="text-3xl font-bold">{{ $artikel->count() }}</p>
                    </div>
                    <div class="p-3 bg-white/20 rounded-full">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                </div>
            </div>

            <a href="{{ route('public.info.index') }}" target="_blank"
                class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm p-5 text-white hover:from-green-600 hover:to-green-700 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Halaman Publik</p>
                        <p class="text-lg font-semibold">Lihat Sekarang</p>
                    </div>
                    <div class="p-3 bg-white/20 rounded-full">
                        <i class="fas fa-external-link-alt text-xl"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex border-b border-gray-100">
                <a href="?tab=pengumuman"
                    class="flex-1 px-6 py-4 text-center font-medium transition {{ $tab == 'pengumuman' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-green-600 hover:bg-gray-50' }}">
                    <i class="fas fa-bullhorn mr-2"></i> Pengumuman
                </a>
                <a href="?tab=berita"
                    class="flex-1 px-6 py-4 text-center font-medium transition {{ $tab == 'berita' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-green-600 hover:bg-gray-50' }}">
                    <i class="fas fa-newspaper mr-2"></i> Berita
                </a>
                <a href="?tab=artikel"
                    class="flex-1 px-6 py-4 text-center font-medium transition {{ $tab == 'artikel' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-green-600 hover:bg-gray-50' }}">
                    <i class="fas fa-book-open mr-2"></i> Artikel
                </a>
            </div>

            <!-- Table Content -->
            <div class="p-6">
                @if($tab == 'pengumuman')
                    @include('modules.informasi.partials.table_pengumuman')
                @elseif($tab == 'berita')
                    @include('modules.informasi.partials.table_berita')
                @else
                    @include('modules.informasi.partials.table_artikel')
                @endif
            </div>
        </div>
    </div>
@endsection