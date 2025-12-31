@extends('layouts.app')

@section('title', 'Detail Aktivitas Harian')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Aktivitas Harian</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap aktivitas harian pengurus</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('takmir.aktivitas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
            
            @php
                $canEdit = auth()->user()->hasRole('admin_takmir') || 
                           (auth()->user()->hasRole('pengurus_takmir') && 
                            $aktivita->takmir->user_id == auth()->id() && 
                            $aktivita->created_at->diffInHours(now()) <= 24);
            @endphp
            
            @if($canEdit)
            <a href="{{ route('takmir.aktivitas.edit', $aktivita->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2">
            <!-- Informasi Utama -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                    Informasi Aktivitas
                </h2>

                <!-- Tanggal -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Tanggal</label>
                    <div class="mt-1 flex items-center">
                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                        <span class="text-gray-900 font-medium">{{ $aktivita->tanggal->format('d F Y') }}</span>
                        <span class="ml-2 text-sm text-gray-500">({{ $aktivita->tanggal->diffForHumans() }})</span>
                    </div>
                </div>

                <!-- Jenis Aktivitas -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Jenis Aktivitas</label>
                    <div class="mt-1">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($aktivita->jenis_aktivitas == 'Ibadah') bg-purple-100 text-purple-800
                            @elseif($aktivita->jenis_aktivitas == 'Kebersihan') bg-green-100 text-green-800
                            @elseif($aktivita->jenis_aktivitas == 'Administrasi') bg-blue-100 text-blue-800
                            @elseif($aktivita->jenis_aktivitas == 'Pengajaran') bg-yellow-100 text-yellow-800
                            @elseif($aktivita->jenis_aktivitas == 'Pembinaan') bg-indigo-100 text-indigo-800
                            @elseif($aktivita->jenis_aktivitas == 'Keuangan') bg-red-100 text-red-800
                            @elseif($aktivita->jenis_aktivitas == 'Sosial') bg-pink-100 text-pink-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $aktivita->jenis_aktivitas }}
                        </span>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Deskripsi Aktivitas</label>
                    <div class="mt-1 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-900 whitespace-pre-line">{{ $aktivita->deskripsi }}</p>
                    </div>
                </div>

                <!-- Durasi -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Durasi</label>
                    <div class="mt-1 flex items-center">
                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                        @if($aktivita->durasi_jam)
                            <span class="text-gray-900 font-medium">{{ $aktivita->durasi_jam }} jam</span>
                        @else
                            <span class="text-gray-400">Tidak dicatat</span>
                        @endif
                    </div>
                </div>

                <!-- Bukti Foto -->
                @if($aktivita->bukti_foto)
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500 mb-2 block">Bukti Foto</label>
                    <a href="{{ $aktivita->bukti_foto_url }}" target="_blank" class="inline-block">
                        <img src="{{ $aktivita->bukti_foto_url }}" alt="Bukti Foto" 
                             class="max-w-full rounded-lg border border-gray-300 hover:opacity-90 transition cursor-pointer">
                    </a>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-info-circle"></i> Klik foto untuk melihat ukuran penuh
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Pengurus & Meta Info -->
        <div class="lg:col-span-1">
            <!-- Info Pengurus -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Pengurus
                </h2>

                <div class="flex items-center mb-4">
                    <img src="{{ $aktivita->takmir->foto_url }}" alt="{{ $aktivita->takmir->nama }}" 
                         class="w-16 h-16 rounded-full object-cover mr-4">
                    <div>
                        <div class="font-bold text-gray-900">{{ $aktivita->takmir->nama }}</div>
                        <div class="text-sm text-gray-600">{{ $aktivita->takmir->jabatan }}</div>
                        
                        @if($aktivita->takmir->isVerifiedJamaah())
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 mt-1">
                            <i class="fas fa-check-circle mr-1"></i> Jamaah Terverifikasi
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Contact Info -->
                @if($aktivita->takmir->email)
                <div class="mb-2 flex items-center text-sm">
                    <i class="fas fa-envelope text-gray-400 mr-2 w-4"></i>
                    <a href="mailto:{{ $aktivita->takmir->email }}" class="text-blue-600 hover:underline">
                        {{ $aktivita->takmir->email }}
                    </a>
                </div>
                @endif

                @if($aktivita->takmir->phone)
                <div class="mb-2 flex items-center text-sm">
                    <i class="fas fa-phone text-gray-400 mr-2 w-4"></i>
                    <a href="tel:{{ $aktivita->takmir->phone }}" class="text-blue-600 hover:underline">
                        {{ $aktivita->takmir->phone }}
                    </a>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('takmir.show', $aktivita->takmir->id) }}" 
                       class="text-sm text-blue-600 hover:underline flex items-center">
                        <i class="fas fa-external-link-alt mr-1"></i> Lihat profil lengkap
                    </a>
                </div>
            </div>

            <!-- Informasi Sistem -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informasi Sistem
                </h2>

                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-medium text-gray-500">DICATAT PADA</label>
                        <div class="text-sm text-gray-900">
                            {{ $aktivita->created_at->format('d/m/Y H:i') }}
                            <div class="text-xs text-gray-500">{{ $aktivita->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500">TERAKHIR DIUPDATE</label>
                        <div class="text-sm text-gray-900">
                            {{ $aktivita->updated_at->format('d/m/Y H:i') }}
                            <div class="text-xs text-gray-500">{{ $aktivita->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    @if(auth()->user()->hasRole('pengurus_takmir') && $aktivita->takmir->user_id == auth()->id())
                    <div class="pt-3 border-t border-gray-200">
                        @if($aktivita->created_at->diffInHours(now()) <= 24)
                        <div class="text-xs text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>
                            Masih bisa diedit (dalam 24 jam)
                        </div>
                        @else
                        <div class="text-xs text-gray-500">
                            <i class="fas fa-lock mr-1"></i>
                            Tidak bisa diedit lagi (lewat 24 jam)
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tombol Delete (jika authorized) -->
            @if(auth()->user()->hasRole('admin_takmir') || 
                (auth()->user()->hasRole('pengurus_takmir') && $aktivita->takmir->user_id == auth()->id()))
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h2 class="text-xl font-bold text-red-600 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Zona Bahaya
                </h2>
                <p class="text-sm text-gray-600 mb-4">Aksi ini tidak dapat dibatalkan. Pastikan Anda yakin sebelum menghapus.</p>
                <form action="{{ route('takmir.aktivitas.destroy', $aktivita->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini? Aksi ini tidak dapat dibatalkan!')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Aktivitas
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
