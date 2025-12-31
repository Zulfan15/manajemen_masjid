@extends('layouts.app')
@section('title', 'Detail Laporan Kegiatan')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <a href="{{ route('kegiatan.laporan.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">
                            <i class="fas fa-file-alt text-green-700 mr-2"></i>{{ $laporan->nama_kegiatan }}
                        </h1>
                        <p class="text-gray-600 mt-2">Laporan Kegiatan</p>
                    </div>
                </div>
                @can('kegiatan.update')
                    <div class="flex gap-2">
                        <a href="{{ route('kegiatan.laporan.edit', $laporan) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <a href="{{ route('kegiatan.laporan.download', $laporan) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </a>
                    </div>
                @endcan
            </div>

            <!-- Status & Badges -->
            <div class="flex items-center gap-2 mb-6">
                <span class="{{ $laporan->getStatusBadgeClass() }} px-3 py-1 rounded-full text-sm">
                    {{ ucfirst($laporan->status) }}
                </span>
                <span class="{{ $laporan->getJenisBadgeClass() }} px-3 py-1 rounded-full text-sm">
                    {{ ucfirst($laporan->jenis_kegiatan) }}
                </span>
                @if ($laporan->is_public)
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        <i class="fas fa-globe mr-1"></i>Publik
                    </span>
                @endif
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-600 mb-1">Total Peserta</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $laporan->jumlah_peserta }}</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-600 mb-1">Hadir</p>
                    <p class="text-2xl font-bold text-green-900">{{ $laporan->jumlah_hadir }}</p>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-red-600 mb-1">Tidak Hadir</p>
                    <p class="text-2xl font-bold text-red-900">{{ $laporan->jumlah_tidak_hadir }}</p>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <p class="text-sm text-purple-600 mb-1">Kehadiran</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $laporan->getPersentaseKehadiran() }}%</p>
                </div>
            </div>

            <!-- Detail Informasi -->
            <div class="space-y-6">
                <!-- Waktu & Tempat -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Informasi Pelaksanaan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="font-medium">{{ $laporan->tanggal_pelaksanaan->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Waktu</p>
                            <p class="font-medium">{{ $laporan->waktu_pelaksanaan->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Lokasi</p>
                            <p class="font-medium">{{ $laporan->lokasi }}</p>
                        </div>
                        @if ($laporan->penanggung_jawab)
                            <div>
                                <p class="text-sm text-gray-600">Penanggung Jawab</p>
                                <p class="font-medium">{{ $laporan->penanggung_jawab }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Deskripsi Kegiatan</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $laporan->deskripsi }}</p>
                </div>

                <!-- Hasil & Capaian -->
                @if ($laporan->hasil_capaian)
                    <div class="border border-gray-200 rounded-lg p-4 bg-green-50">
                        <h3 class="font-semibold text-gray-800 mb-3">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>Hasil & Capaian
                        </h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $laporan->hasil_capaian }}</p>
                    </div>
                @endif

                <!-- Catatan & Kendala -->
                @if ($laporan->catatan_kendala)
                    <div class="border border-gray-200 rounded-lg p-4 bg-yellow-50">
                        <h3 class="font-semibold text-gray-800 mb-3">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>Catatan & Kendala
                        </h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $laporan->catatan_kendala }}</p>
                    </div>
                @endif

                <!-- Foto Dokumentasi -->
                @if ($laporan->foto_dokumentasi && count($laporan->foto_dokumentasi) > 0)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3">
                            <i class="fas fa-images text-blue-600 mr-2"></i>Foto Dokumentasi
                            ({{ count($laporan->foto_dokumentasi) }})
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($laporan->foto_dokumentasi as $foto)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $foto) }}" alt="Dokumentasi {{ $laporan->nama_kegiatan }}"
                                        class="w-full h-48 object-cover rounded-lg border border-gray-300 cursor-pointer hover:opacity-90 transition"
                                        onclick="window.open('{{ asset('storage/' . $foto) }}', '_blank')">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition flex items-center justify-center">
                                        <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 text-2xl transition"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="border border-gray-200 rounded-lg p-6 text-center bg-gray-50">
                        <i class="fas fa-images text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-500">Tidak ada foto dokumentasi</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div>
                        <p>Dibuat oleh: <strong>{{ $laporan->creator->name }}</strong></p>
                        <p>{{ $laporan->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                    @if ($laporan->kegiatan)
                        <div class="text-right">
                            <p>Terkait dengan kegiatan:</p>
                            <a href="{{ route('kegiatan.show', $laporan->kegiatan_id) }}"
                                class="text-green-700 font-semibold hover:underline">
                                {{ $laporan->kegiatan->nama }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
