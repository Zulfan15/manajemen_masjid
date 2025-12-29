@extends('layouts.app')

@section('title', 'Detail Kegiatan')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Header -->
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-3 py-1 {{ $kegiatan->getStatusBadgeClass() }} text-sm rounded-full">
                        {{ ucfirst($kegiatan->status) }}
                    </span>
                    <span class="px-3 py-1 {{ $kegiatan->getJenisBadgeClass() }} text-sm rounded-full">
                        {{ ucfirst(str_replace('_', ' ', $kegiatan->jenis_kegiatan)) }}
                    </span>
                    
                    @if($kegiatan->sudahDaftar(auth()->id()))
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Sudah Terdaftar
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas {{ $kegiatan->getKategoriIcon() }} text-green-700 mr-2"></i>
                    {{ $kegiatan->nama_kegiatan }}
                </h1>
            </div>
            <div>
                <a href="{{ route('jamaah.kegiatan.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Poster/Image -->
        @if($kegiatan->gambar)
            <div class="mb-6 rounded-lg overflow-hidden">
                <img src="{{ asset('storage/' . $kegiatan->gambar) }}" 
                     alt="{{ $kegiatan->nama_kegiatan }}"
                     class="w-full max-h-96 object-cover">
            </div>
        @endif

        <!-- Tabs Navigation -->
        @if($kegiatan->butuh_pendaftaran && !in_array($kegiatan->status, ['dibatalkan', 'selesai']))
            <div x-data="{ activeTab: 'detail' }">
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex gap-4">
                        <button @click="activeTab = 'detail'" 
                                class="border-b-2 py-3 px-4 font-medium transition"
                                :class="activeTab === 'detail' ? 'border-green-700 text-green-700' : 'border-transparent text-gray-600 hover:text-gray-800'">
                            <i class="fas fa-info-circle mr-2"></i>Detail
                        </button>
                        <button @click="activeTab = 'daftar'" 
                                class="border-b-2 py-3 px-4 font-medium transition"
                                :class="activeTab === 'daftar' ? 'border-green-700 text-green-700' : 'border-transparent text-gray-600 hover:text-gray-800'">
                            <i class="fas fa-user-plus mr-2"></i>Pendaftaran
                        </button>
                    </nav>
                </div>

                <!-- Tab: Detail -->
                <div x-show="activeTab === 'detail'">
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="md:col-span-2 space-y-6">
                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-align-left text-green-700 mr-2"></i>Deskripsi
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $kegiatan->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}
                    </p>
                </div>

                <!-- Date & Time -->
                <div>
                    <h4 class="font-semibold text-gray-700 mb-3">
                        <i class="fas fa-calendar text-green-700 mr-2"></i>Tanggal & Waktu
                    </h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-800 font-medium">
                            {{ $kegiatan->tanggal_mulai->format('d F Y') }}
                        </p>
                        @if($kegiatan->tanggal_selesai)
                            <p class="text-gray-600 text-sm">
                                s/d {{ $kegiatan->tanggal_selesai->format('d F Y') }}
                            </p>
                        @endif
                        <p class="text-gray-600 mt-2">
                            <i class="fas fa-clock mr-2"></i>
                            {{ date('H:i', strtotime($kegiatan->waktu_mulai)) }}
                            @if($kegiatan->waktu_selesai)
                                - {{ date('H:i', strtotime($kegiatan->waktu_selesai)) }}
                            @endif
                            WIB
                        </p>
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <h4 class="font-semibold text-gray-700 mb-3">
                        <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>Lokasi
                    </h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-800">{{ $kegiatan->lokasi }}</p>
                    </div>
                </div>

                <!-- PIC (Person In Charge) -->
                @if($kegiatan->pic)
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">
                            <i class="fas fa-user-tie text-green-700 mr-2"></i>Penanggung Jawab
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-800 font-medium">{{ $kegiatan->pic }}</p>
                            @if($kegiatan->kontak_pic)
                                <p class="text-gray-600 text-sm mt-1">
                                    <i class="fas fa-phone mr-2"></i>{{ $kegiatan->kontak_pic }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if($kegiatan->catatan)
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">
                            <i class="fas fa-sticky-note text-green-700 mr-2"></i>Catatan
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-600">{{ $kegiatan->catatan }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-4">
                <!-- Category Badge -->
                <div class="bg-green-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-green-800 mb-2">Kategori</h4>
                    <p class="text-green-700 capitalize">{{ ucfirst($kegiatan->kategori) }}</p>
                </div>

                <!-- Participant Info -->
                @if($kegiatan->kuota_peserta)
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">Kuota Peserta</h4>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $kegiatan->jumlah_peserta }} / {{ $kegiatan->kuota_peserta }}
                        </p>
                        <p class="text-sm text-blue-600 mt-1">
                            Sisa: {{ $kegiatan->sisaKuota() }} tempat
                        </p>
                    </div>
                @endif

                <!-- Status Info for Completed/Cancelled Events (Moved to top) -->
                @if(in_array($kegiatan->status, ['dibatalkan', 'selesai']))
                    <div class="bg-gray-100 border-l-4 border-gray-500 p-4 rounded mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-gray-600 mr-3 mt-1"></i>
                            <div>
                                <p class="font-semibold text-gray-800">
                                    @if($kegiatan->status === 'dibatalkan')
                                        Kegiatan Dibatalkan
                                    @else
                                        Kegiatan Telah Selesai
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Pendaftaran untuk kegiatan ini sudah tidak tersedia.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Additional Info -->
                <div class="space-y-2">
                    @if($kegiatan->butuh_pendaftaran && !in_array($kegiatan->status, ['dibatalkan', 'selesai']))
                        <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-3 rounded">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span>Butuh Pendaftaran</span>
                        </div>
                    @endif
                    
                    @if($kegiatan->sertifikat_tersedia)
                        <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-3 rounded">
                            <i class="fas fa-certificate text-yellow-600 mr-2"></i>
                            <span>Sertifikat Tersedia</span>
                        </div>
                    @endif
                    
                    @if($kegiatan->is_recurring)
                        <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-3 rounded">
                            <i class="fas fa-redo text-blue-600 mr-2"></i>
                            <span>
                                Kegiatan Berulang
                                @if($kegiatan->recurring_type)
                                    ({{ ucfirst($kegiatan->recurring_type) }})
                                @endif
                            </span>
                        </div>
                    @endif
                </div>

                <!-- (Status alert already moved above) -->
                <!-- Created By -->
                @if($kegiatan->creator)
                    <div class="bg-gray-50 p-4 rounded-lg text-sm">
                        <p class="text-gray-500 mb-1">Dibuat oleh:</p>
                        <p class="text-gray-800 font-medium">{{ $kegiatan->creator->name }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($kegiatan->butuh_pendaftaran && !in_array($kegiatan->status, ['dibatalkan', 'selesai']))
                </div>
                
                <!-- Tab: Pendaftaran -->
                <div x-show="activeTab === 'daftar'">
                    <div class="max-w-2xl mx-auto">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-user-plus text-green-700 mr-2"></i>Form Pendaftaran Kegiatan
                        </h3>

                        @php
                            $sudahDaftar = $kegiatan->sudahDaftar(auth()->id());
                        @endphp

                        @if($kegiatan->isFull())
                            <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-6">
                                <p class="text-red-700">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Kuota peserta sudah penuh!
                                </p>
                            </div>
                        @elseif($sudahDaftar)
                            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                                <p class="text-blue-700">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Anda sudah terdaftar di kegiatan ini.
                                </p>
                            </div>
                        @else
                            <form action="{{ route('jamaah.kegiatan.register', $kegiatan->id) }}" method="POST" class="space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_peserta" 
                                           value="{{ old('nama_peserta', auth()->user()->name) }}" 
                                           required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    @error('nama_peserta')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" 
                                           value="{{ old('email', auth()->user()->email) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                                    <input type="text" name="no_hp" 
                                           value="{{ old('no_hp', auth()->user()->phone ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    @error('no_hp')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                    <textarea name="alamat" rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ old('alamat', auth()->user()->address ?? '') }}</textarea>
                                    @error('alamat')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Keterangan (opsional)
                                    </label>
                                    <textarea name="keterangan" rows="2" 
                                              placeholder="Pertanyaan, kebutuhan khusus, dll"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" 
                                        class="w-full px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                                    <i class="fas fa-check mr-2"></i>Daftar Sekarang
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
