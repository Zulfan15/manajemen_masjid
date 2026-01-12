@extends('layouts.app')
@section('title', 'Sertifikat Saya')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-certificate text-yellow-600 mr-2"></i>Sertifikat Saya
                    </h1>
                    <p class="text-gray-600 mt-2">Daftar sertifikat kegiatan yang Anda miliki</p>
                </div>
                <a href="{{ route('kegiatan.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            @if($sertifikats->count() > 0)
                <!-- Sertifikat Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sertifikats as $sertifikat)
                        <div
                            class="bg-gradient-to-br from-amber-50 to-yellow-100 border border-amber-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
                            <!-- Certificate Icon -->
                            <div class="text-center mb-4">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-200 rounded-full">
                                    <i class="fas fa-award text-3xl text-amber-700"></i>
                                </div>
                            </div>

                            <!-- Certificate Info -->
                            <div class="text-center mb-4">
                                <h3 class="font-bold text-gray-800 text-lg mb-1">
                                    {{ $sertifikat->nama_kegiatan }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ $sertifikat->tanggal_kegiatan->format('d F Y') }}
                                </p>
                            </div>

                            <!-- Certificate Details -->
                            <div class="bg-white bg-opacity-60 rounded-lg p-3 mb-4 text-sm">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Nomor:</span>
                                    <span class="font-mono text-gray-800">{{ $sertifikat->nomor_sertifikat }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Diterbitkan:</span>
                                    <span class="text-gray-800">{{ $sertifikat->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <!-- Download Button -->
                            <a href="{{ route('kegiatan.sertifikat.download', $sertifikat->id) }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                                <i class="fas fa-download mr-2"></i>Download PDF
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($sertifikats->hasPages())
                    <div class="mt-6">
                        {{ $sertifikats->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                        <i class="fas fa-certificate text-5xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Sertifikat</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        Anda belum memiliki sertifikat dari kegiatan manapun.
                        Ikuti kegiatan dan sertifikat akan tersedia setelah kegiatan selesai.
                    </p>
                    <a href="{{ route('kegiatan.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-calendar-alt mr-2"></i>Lihat Kegiatan
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection