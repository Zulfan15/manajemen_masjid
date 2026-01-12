@extends('layouts.app')

@section('title', 'Detail Donasi')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('jamaah.pemasukan') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Riwayat
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-lg shadow overflow-hidden max-w-2xl mx-auto">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-500 to-green-600">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-receipt mr-2"></i>Detail Donasi
                </h1>
            </div>

            {{-- Status Badge --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
                        'verified' => 'bg-green-100 border-green-500 text-green-700',
                        'rejected' => 'bg-red-100 border-red-500 text-red-700',
                    ];
                    $statusIcons = [
                        'pending' => 'fas fa-clock',
                        'verified' => 'fas fa-check-circle',
                        'rejected' => 'fas fa-times-circle',
                    ];
                    $statusLabels = [
                        'pending' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ];
                @endphp
                <div class="flex items-center gap-3">
                    <span class="text-gray-600 font-medium">Status:</span>
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border-l-4 {{ $statusColors[$pemasukan->status] ?? 'bg-gray-100 text-gray-800' }}">
                        <i class="{{ $statusIcons[$pemasukan->status] ?? 'fas fa-question-circle' }} mr-2"></i>
                        {{ $statusLabels[$pemasukan->status] ?? ucfirst($pemasukan->status) }}
                    </span>
                </div>
            </div>

            {{-- Detail Content --}}
            <div class="p-6">
                {{-- Jumlah --}}
                <div class="text-center py-6 border-b border-gray-100 mb-6">
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">Jumlah Donasi</p>
                    <p class="text-4xl font-bold text-green-600">Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</p>
                </div>

                {{-- Detail Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase mb-1">Jenis Donasi</p>
                        <p class="text-lg font-medium text-gray-800">
                            @if($pemasukan->jenis == 'Donasi')
                                💰 {{ $pemasukan->jenis }}
                            @elseif($pemasukan->jenis == 'Zakat')
                                📿 {{ $pemasukan->jenis }}
                            @elseif($pemasukan->jenis == 'Infak')
                                🕌 {{ $pemasukan->jenis }}
                            @elseif($pemasukan->jenis == 'Sedekah')
                                ❤️ {{ $pemasukan->jenis }}
                            @else
                                {{ $pemasukan->jenis }}
                            @endif
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase mb-1">Tanggal Donasi</p>
                        <p class="text-lg font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($pemasukan->tanggal)->format('d F Y') }}
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase mb-1">Metode Pembayaran</p>
                        <p class="text-lg font-medium text-gray-800">{{ $pemasukan->sumber ?? '-' }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase mb-1">Waktu Submit</p>
                        <p class="text-lg font-medium text-gray-800">{{ $pemasukan->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                {{-- Keterangan --}}
                @if($pemasukan->keterangan)
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs text-blue-500 uppercase mb-1">Keterangan</p>
                        <p class="text-gray-700">{{ $pemasukan->keterangan }}</p>
                    </div>
                @endif

                {{-- Bukti Transfer --}}
                @if($pemasukan->bukti_transfer)
                    <div class="mt-6">
                        <p class="text-xs text-gray-500 uppercase mb-2">Bukti Transfer</p>
                        @php
                            $extension = pathinfo($pemasukan->bukti_transfer, PATHINFO_EXTENSION);
                        @endphp
                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ asset('uploads/bukti_transfer/' . $pemasukan->bukti_transfer) }}" alt="Bukti Transfer"
                                class="max-w-full rounded-lg border border-gray-200 shadow">
                        @else
                            <a href="{{ asset('uploads/bukti_transfer/' . $pemasukan->bukti_transfer) }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-file-pdf mr-2"></i>Lihat Bukti Transfer (PDF)
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Rejected Info --}}
                @if($pemasukan->status === 'rejected' && $pemasukan->alasan_tolak)
                    <div class="mt-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <p class="text-sm font-bold text-red-700 mb-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Alasan Penolakan
                        </p>
                        <p class="text-red-600">{{ $pemasukan->alasan_tolak }}</p>
                        @if($pemasukan->rejected_at)
                            <p class="text-xs text-red-500 mt-2">
                                Ditolak pada: {{ \Carbon\Carbon::parse($pemasukan->rejected_at)->format('d M Y H:i') }}
                            </p>
                        @endif
                    </div>
                @endif

                {{-- Verified Info --}}
                @if($pemasukan->status === 'verified')
                    <div class="mt-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                        <p class="text-sm font-bold text-green-700 mb-1">
                            <i class="fas fa-check-circle mr-1"></i>Donasi Terverifikasi
                        </p>
                        <p class="text-green-600">Donasi Anda telah diverifikasi dan masuk ke laporan keuangan masjid.</p>
                        @if($pemasukan->verified_at)
                            <p class="text-xs text-green-500 mt-2">
                                Diverifikasi pada: {{ \Carbon\Carbon::parse($pemasukan->verified_at)->format('d M Y H:i') }}
                            </p>
                        @endif
                    </div>
                @endif

                {{-- Pending Info --}}
                @if($pemasukan->status === 'pending')
                    <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                        <p class="text-sm font-bold text-yellow-700 mb-1">
                            <i class="fas fa-clock mr-1"></i>Menunggu Verifikasi
                        </p>
                        <p class="text-yellow-600">Donasi Anda sedang menunggu verifikasi dari pengurus masjid. Mohon bersabar.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
                <a href="{{ route('jamaah.pemasukan') }}"
                    class="inline-flex items-center px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Riwayat
                </a>
            </div>
        </div>
    </div>
@endsection