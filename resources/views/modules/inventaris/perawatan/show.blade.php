@extends('layouts.app')

@section('title', 'Detail Jadwal Perawatan')

@section('content')
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('inventaris.index') }}" class="hover:text-teal-600">Inventaris</a>
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    <a href="{{ route('inventaris.perawatan.index') }}" class="hover:text-teal-600">Jadwal Perawatan</a>
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    <span class="text-gray-700">Detail</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-check text-teal-600 mr-3"></i>
                    Detail Jadwal Perawatan
                </h1>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('inventaris.perawatan.edit', $jadwal->jadwal_id) }}"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('inventaris.perawatan.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Status Header -->
            @php
                $statusColors = [
                    'dijadwalkan' => 'bg-blue-500',
                    'selesai' => 'bg-green-500',
                    'dibatalkan' => 'bg-red-500',
                ];
                $statusLabels = [
                    'dijadwalkan' => 'Dijadwalkan',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ];
            @endphp
            <div class="{{ $statusColors[$jadwal->status] ?? 'bg-gray-500' }} px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm opacity-80">Status Perawatan</p>
                            <p class="text-xl font-bold">{{ $statusLabels[$jadwal->status] ?? $jadwal->status }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-80">Tanggal Jadwal</p>
                        <p class="text-xl font-bold">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Aset Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Informasi Aset</h3>
                        <div class="flex items-center">
                            <div class="p-3 bg-teal-100 rounded-lg mr-4">
                                <i class="fas fa-box text-teal-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-lg">{{ $jadwal->aset->nama_aset ?? '-' }}</p>
                                <p class="text-sm text-gray-500">{{ $jadwal->aset->kategori ?? 'Tanpa Kategori' }}</p>
                                <p class="text-sm text-gray-500">Lokasi: {{ $jadwal->aset->lokasi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Perawatan Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Jenis Perawatan</h3>
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg mr-4">
                                <i class="fas fa-tools text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-lg">{{ $jadwal->jenis_perawatan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Petugas -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Petugas</h3>
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg mr-4">
                                <i class="fas fa-user text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-lg">
                                    {{ $jadwal->petugas->name ?? 'Belum Ditentukan' }}</p>
                                @if($jadwal->petugas)
                                    <p class="text-sm text-gray-500">{{ $jadwal->petugas->email ?? '' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Timestamp -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Informasi Waktu</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Dibuat:</span>
                                <span
                                    class="text-gray-700">{{ $jadwal->created_at ? $jadwal->created_at->format('d M Y H:i') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Terakhir Diubah:</span>
                                <span
                                    class="text-gray-700">{{ $jadwal->updated_at ? $jadwal->updated_at->format('d M Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                @if($jadwal->note)
                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-yellow-700 uppercase tracking-wider mb-2">
                            <i class="fas fa-sticky-note mr-2"></i> Catatan
                        </h3>
                        <p class="text-gray-700">{{ $jadwal->note }}</p>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Update Status Cepat:</span>
                    <div class="flex space-x-2">
                        @if($jadwal->status !== 'selesai')
                            <form action="{{ route('inventaris.perawatan.update-status', $jadwal->jadwal_id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="selesai">
                                <button type="submit"
                                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">
                                    <i class="fas fa-check mr-1"></i> Tandai Selesai
                                </button>
                            </form>
                        @endif
                        @if($jadwal->status !== 'dibatalkan')
                            <form action="{{ route('inventaris.perawatan.update-status', $jadwal->jadwal_id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="dibatalkan">
                                <button type="submit"
                                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm"
                                    onclick="return confirm('Yakin ingin membatalkan jadwal ini?')">
                                    <i class="fas fa-times mr-1"></i> Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection