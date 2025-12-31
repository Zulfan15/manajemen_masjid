@extends('layouts.app')

@section('title', 'Manajemen Pemilihan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-vote-yea text-purple-600 mr-2"></i>Manajemen Pemilihan
                </h1>
                <p class="text-gray-600 mt-2">Kelola pemilihan Ketua DKM</p>
            </div>
            @can('takmir.create')
            <a href="{{ route('takmir.pemilihan.create') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>Buat Pemilihan Baru
            </a>
            @endcan
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Pemilihan List -->
        @forelse($pemilihanList as $pemilihan)
        <div class="bg-white border rounded-lg shadow-md p-6 mb-4 hover:shadow-lg transition duration-200">
            <div class="flex items-start justify-between">
                <!-- Info Pemilihan -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-bold text-gray-800">{{ $pemilihan->judul }}</h3>
                        @if($pemilihan->status === 'draft')
                            <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm font-medium">
                                <i class="fas fa-file-alt mr-1"></i>Draft
                            </span>
                        @elseif($pemilihan->status === 'aktif')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                <i class="fas fa-check-circle mr-1"></i>Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                <i class="fas fa-flag-checkered mr-1"></i>Selesai
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-gray-600 mb-3">{{ $pemilihan->deskripsi }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar text-blue-600 mr-2"></i>
                            <span>
                                {{ $pemilihan->tanggal_mulai->format('d M Y') }} - 
                                {{ $pemilihan->tanggal_selesai->format('d M Y') }}
                            </span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            <span>{{ $pemilihan->kandidat_count }} Kandidat</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-check-to-slot text-green-600 mr-2"></i>
                            <span>{{ $pemilihan->votes_count }} Suara</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-2 ml-4">
                    <a href="{{ route('takmir.pemilihan.show', $pemilihan->id) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition duration-200 text-center">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </a>
                    <a href="{{ route('takmir.pemilihan.hasil', $pemilihan->id) }}" 
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition duration-200 text-center">
                        <i class="fas fa-chart-bar mr-1"></i>Hasil
                    </a>
                    @can('takmir.update')
                    <a href="{{ route('takmir.pemilihan.edit', $pemilihan->id) }}" 
                       class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm transition duration-200 text-center">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    @endcan
                    @can('takmir.delete')
                    <form action="{{ route('takmir.pemilihan.destroy', $pemilihan->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus pemilihan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition duration-200">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        @empty
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
            <p class="text-gray-600 text-lg">Belum ada pemilihan yang dibuat.</p>
            @can('takmir.create')
            <a href="{{ route('takmir.pemilihan.create') }}" 
               class="inline-block mt-4 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-200">
                <i class="fas fa-plus mr-2"></i>Buat Pemilihan Pertama
            </a>
            @endcan
        </div>
        @endforelse

        <!-- Pagination -->
        @if($pemilihanList->hasPages())
        <div class="mt-6">
            {{ $pemilihanList->links() }}
        </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6 text-center">
            <a href="{{ route('takmir.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Manajemen Takmir
            </a>
        </div>
    </div>
</div>
@endsection
