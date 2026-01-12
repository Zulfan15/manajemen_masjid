@extends('layouts.app')

@section('title', 'Detail Pemeriksaan Kondisi')

@section('content')
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('inventaris.index') }}" class="hover:text-orange-600">Inventaris</a>
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    <a href="{{ route('inventaris.kondisi.index') }}" class="hover:text-orange-600">Kondisi Barang</a>
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    <span class="text-gray-700">Detail</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clipboard-check text-orange-600 mr-3"></i>
                    Detail Pemeriksaan
                </h1>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('inventaris.kondisi.edit', $kondisi->kondisi_id) }}"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('inventaris.kondisi.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Kondisi Header -->
            @php
                $kondisiColors = [
                    'baik' => 'bg-green-500',
                    'perlu_perbaikan' => 'bg-yellow-500',
                    'rusak_berat' => 'bg-red-500',
                ];
                $kondisiLabels = [
                    'baik' => 'Baik / Layak Pakai',
                    'perlu_perbaikan' => 'Perlu Perbaikan',
                    'rusak_berat' => 'Rusak Berat',
                ];
            @endphp
            <div class="{{ $kondisiColors[$kondisi->kondisi] ?? 'bg-gray-500' }} px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-clipboard-check text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm opacity-80">Hasil Pemeriksaan</p>
                            <p class="text-xl font-bold">{{ $kondisiLabels[$kondisi->kondisi] ?? $kondisi->kondisi }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-80">Tanggal Pemeriksaan</p>
                        <p class="text-xl font-bold">
                            {{ $kondisi->tanggal_pemeriksaan ? \Carbon\Carbon::parse($kondisi->tanggal_pemeriksaan)->format('d M Y') : '-' }}
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
                            <div class="p-3 bg-orange-100 rounded-lg mr-4">
                                <i class="fas fa-box text-orange-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-lg">{{ $kondisi->aset->nama_aset ?? '-' }}</p>
                                <p class="text-sm text-gray-500">{{ $kondisi->aset->kategori ?? 'Tanpa Kategori' }}</p>
                                <p class="text-sm text-gray-500">Lokasi: {{ $kondisi->aset->lokasi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pemeriksa Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Pemeriksa</h3>
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg mr-4">
                                <i class="fas fa-user text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-lg">
                                    {{ $kondisi->petugas->name ?? $kondisi->petugas_pemeriksa ?? 'Tidak Diketahui' }}
                                </p>
                                @if($kondisi->petugas)
                                    <p class="text-sm text-gray-500">{{ $kondisi->petugas->email ?? '' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Timestamp -->
                    <div class="md:col-span-2 bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Informasi Waktu</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Dibuat:</span>
                                <span
                                    class="text-gray-700">{{ $kondisi->created_at ? $kondisi->created_at->format('d M Y H:i') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Terakhir Diubah:</span>
                                <span
                                    class="text-gray-700">{{ $kondisi->updated_at ? $kondisi->updated_at->format('d M Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                @if($kondisi->catatan)
                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-yellow-700 uppercase tracking-wider mb-2">
                            <i class="fas fa-sticky-note mr-2"></i> Catatan
                        </h3>
                        <p class="text-gray-700">{{ $kondisi->catatan }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recommendation -->
        @if($kondisi->kondisi === 'perlu_perbaikan')
            <div class="mt-6 bg-yellow-50 border border-yellow-300 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="p-3 bg-yellow-200 rounded-lg mr-4">
                        <i class="fas fa-exclamation-triangle text-yellow-700 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-yellow-800 mb-2">Rekomendasi Tindakan</h3>
                        <p class="text-yellow-700">Aset ini memerlukan perbaikan. Disarankan untuk menjadwalkan perawatan
                            segera.</p>
                        <a href="{{ route('inventaris.perawatan.create') }}?aset_id={{ $kondisi->aset_id }}"
                            class="inline-block mt-3 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                            <i class="fas fa-calendar-plus mr-2"></i> Jadwalkan Perawatan
                        </a>
                    </div>
                </div>
            </div>
        @elseif($kondisi->kondisi === 'rusak_berat')
            <div class="mt-6 bg-red-50 border border-red-300 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="p-3 bg-red-200 rounded-lg mr-4">
                        <i class="fas fa-times-circle text-red-700 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-800 mb-2">Perhatian!</h3>
                        <p class="text-red-700">Aset ini dalam kondisi rusak berat dan mungkin perlu dipertimbangkan untuk
                            penggantian atau penghapusan dari inventaris.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection