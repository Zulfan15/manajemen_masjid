@extends('layouts.app')
@section('title', 'Detail Pengurus')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-user-circle text-blue-600 mr-2"></i>Detail Pengurus
                    </h1>
                    <p class="text-gray-600 mt-2">Informasi lengkap data pengurus</p>
                </div>
                <div class="flex space-x-2">
                    @can('takmir.update')
                        <a href="{{ route('takmir.edit', $takmir->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endcan
                    <a href="{{ route('takmir.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column - Photo -->
            <div class="md:col-span-1">
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <img src="{{ $takmir->foto_url }}" alt="{{ $takmir->nama }}" 
                        class="h-48 w-48 rounded-full object-cover mx-auto mb-4 border-4 border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $takmir->nama }}</h2>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $takmir->jabatan }}
                    </span>
                    <div class="mt-4 space-y-2">
                        @if($takmir->status == 'aktif')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Status Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>Status Nonaktif
                            </span>
                        @endif
                        
                        @if($takmir->isVerifiedJamaah())
                            <div class="mt-3">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-user-check mr-1"></i>Jamaah Terverifikasi
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Details -->
            <div class="md:col-span-2">
                <div class="space-y-6">
                    <!-- Informasi Verifikasi Jamaah -->
                    @if($takmir->isVerifiedJamaah())
                        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                                <i class="fas fa-user-check text-green-600 mr-2"></i>Status Verifikasi Jamaah
                            </h3>
                            <div class="text-green-700">
                                <p class="mb-2">
                                    <i class="fas fa-check-circle mr-2"></i>Pengurus ini terverifikasi sebagai <strong>Jamaah Masjid</strong>
                                </p>
                                <p class="text-sm">
                                    <i class="fas fa-user mr-2"></i>Terhubung dengan akun: <strong>{{ $takmir->jamaah->name }}</strong> ({{ $takmir->jamaah->email }})
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Informasi Kontak -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-address-card text-blue-600 mr-2"></i>Informasi Kontak
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                                <p class="text-gray-900">
                                    @if($takmir->email)
                                        <i class="fas fa-envelope text-gray-400 mr-2"></i>{{ $takmir->email }}
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada data</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nomor Telepon</label>
                                <p class="text-gray-900">
                                    @if($takmir->phone)
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>{{ $takmir->phone }}
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada data</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                            <p class="text-gray-900">
                                @if($takmir->alamat)
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>{{ $takmir->alamat }}
                                @else
                                    <span class="text-gray-400 italic">Tidak ada data</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Periode Kepengurusan -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>Periode Kepengurusan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Periode Mulai</label>
                                <p class="text-gray-900 font-semibold">
                                    <i class="fas fa-calendar-check text-gray-400 mr-2"></i>{{ $takmir->periode_mulai->format('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Periode Akhir</label>
                                <p class="text-gray-900 font-semibold">
                                    <i class="fas fa-calendar-times text-gray-400 mr-2"></i>{{ $takmir->periode_akhir->format('d F Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Durasi</label>
                            <p class="text-gray-900">
                                <i class="fas fa-hourglass-half text-gray-400 mr-2"></i>
                                {{ $takmir->periode_mulai->diffInYears($takmir->periode_akhir) }} tahun 
                                {{ $takmir->periode_mulai->copy()->addYears($takmir->periode_mulai->diffInYears($takmir->periode_akhir))->diffInMonths($takmir->periode_akhir) }} bulan
                            </p>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    @if($takmir->keterangan)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-sticky-note text-blue-600 mr-2"></i>Keterangan
                            </h3>
                            <p class="text-gray-900">{{ $takmir->keterangan }}</p>
                        </div>
                    @endif

                    <!-- Informasi Sistem -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>Informasi Sistem
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Dibuat Pada</label>
                                <p class="text-gray-900">
                                    <i class="fas fa-clock text-gray-400 mr-2"></i>{{ $takmir->created_at->format('d F Y, H:i') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Terakhir Diupdate</label>
                                <p class="text-gray-900">
                                    <i class="fas fa-sync text-gray-400 mr-2"></i>{{ $takmir->updated_at->format('d F Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
            @can('takmir.delete')
                <form action="{{ route('takmir.destroy', $takmir->id) }}" method="POST" 
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengurus ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-trash mr-2"></i>Hapus Data
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>
@endsection
