@extends('layouts.app')

@section('title', 'Inventaris Masjid')

@section('content')
<div class="container mx-auto">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-boxes text-green-700 mr-2"></i>Inventaris Masjid
                </h1>
                <p class="text-gray-600 mt-2">Ringkasan kondisi aset dan aktivitas inventaris masjid.</p>
            </div>

            @if(auth()->user()->hasPermission('inventaris.create'))
                <div class="flex gap-3">
                    <a href="#"
                       class="inline-flex items-center bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-plus mr-2"></i> Tambah Aset
                    </a>
                    <a href="#"
                       class="inline-flex items-center bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-user-friends mr-2"></i> Data Petugas
                    </a>
                </div>
            @endif
        </div>

        @if(auth()->user()->isSuperAdmin())
            <div class="mt-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
                <p class="font-semibold"><i class="fas fa-info-circle mr-2"></i>Mode View Only</p>
                <p class="text-sm mt-1">
                    Sebagai <strong>Super Administrator</strong> Anda hanya dapat melihat data inventaris.
                    Aksi tambah/ubah/hapus hanya dapat dilakukan oleh petugas modul inventaris.
                </p>
            </div>
        @endif
    </div>

    {{-- Top stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        {{-- Total Aset --}}
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500 mb-1">Total Aset</p>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalAset ?? 0) }}</p>
            <p class="text-xs text-green-600 mt-1">+1.2% bulan ini (dummy)</p>
        </div>

        {{-- Jadwal Perawatan --}}
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500 mb-1">Jadwal Perawatan</p>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalJadwalPerawatan ?? 0) }}</p>
            <p class="text-xs text-green-600 mt-1">+{{ $totalJadwalPerawatan ?? 0 }} minggu ini (dummy)</p>
        </div>

        {{-- Kondisi Barang --}}
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500 mb-1">Kondisi Barang</p>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalPerluPerbaikan ?? 0) }}</p>
            <p class="text-xs text-red-600 mt-1">Perlu perbaikan</p>
        </div>

        {{-- Transaksi --}}
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500 mb-1">Transaksi</p>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalTransaksiBulanIni ?? 0) }}</p>
            <p class="text-xs text-red-600 mt-1">-2% bulan ini (dummy)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Chart jumlah aset per kategori --}}
        <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Jumlah Aset per Kategori</h2>

            @php
                $maxKategori = max(($asetPerKategori ?? collect())->pluck('total')->toArray() ?: [1]);
            @endphp

            @forelse(($asetPerKategori ?? collect()) as $row)
                <div class="flex items-center mb-3">
                    <span class="w-24 text-sm text-gray-700 capitalize">{{ $row->kategori ?? 'Lainnya' }}</span>
                    <div class="flex-1 bg-gray-100 h-2 rounded">
                        <div class="h-2 rounded bg-green-600"
                             style="width: {{ ($row->total / $maxKategori) * 100 }}%"></div>
                    </div>
                    <span class="ml-2 text-sm font-semibold text-gray-800">{{ $row->total }}</span>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada data aset per kategori.</p>
            @endforelse
        </div>

        {{-- Aktivitas terbaru --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>

            @forelse(($aktivitasTerbaru ?? collect()) as $log)
                <div class="flex items-start mb-4">
                    <div class="mt-1 mr-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-green-100 text-green-700">
                            <i class="fas fa-plus text-xs"></i>
                        </span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">
                            Aset
                            <span class="font-semibold">
                                {{ optional($log->aset)->nama_aset ?? '-' }}
                            </span>
                            ({{ ucfirst($log->tipe_transaksi ?? 'transaksi') }})
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ optional($log->petugas)->name ?? 'Petugas tidak diketahui' }} ·
                            {{ optional($log->created_at)->diffForHumans() ?? '-' }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada aktivitas transaksi aset.</p>
            @endforelse
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Daftar aset terbaru --}}
        <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Aset Terbaru</h2>
                <a href="{{ route('inventaris.aset.index') }}" class="text-success small">Lihat semua</a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse(($asetTerbaru ?? collect()) as $aset)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $aset->nama_aset ?? '-' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ ucfirst($aset->kategori ?? 'Lainnya') }} ·
                                Kode: {{ $aset->kode_aset ?? '-' }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-500">
                            {{ optional($aset->created_at)->diffForHumans() ?? '-' }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada aset yang tercatat.</p>
                @endforelse
            </div>
        </div>

        {{-- Cards Tambah Aset & Data Petugas --}}
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center h-full min-h-[150px]
                        @if(auth()->user()->isSuperAdmin()) opacity-60 cursor-not-allowed @endif">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-3">
                    <i class="fas fa-plus text-green-700"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Tambah Aset</h3>
                <p class="text-xs text-gray-500 mb-3 text-center">
                    Tambahkan aset baru ke dalam sistem inventaris masjid.
                </p>
                @if(!auth()->user()->isSuperAdmin())
                    <a href="#"
                       class="text-xs px-3 py-1 rounded bg-green-700 text-white hover:bg-green-800 transition">
                        Mulai
                    </a>
                @else
                    <span class="text-[10px] text-gray-400">Hanya petugas inventaris yang dapat menambah aset.</span>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center h-full min-h-[150px]">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                    <i class="fas fa-user-friends text-gray-700"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Data Petugas</h3>
                <p class="text-xs text-gray-500 mb-3 text-center">
                    Lihat daftar petugas yang bertanggung jawab atas inventaris masjid.
                </p>
                <a href="{{ route('inventaris.petugas.index') }}"
                    class="inline-flex items-center px-3 py-1.5 text-xs rounded bg-gray-800 text-white hover:bg-gray-900">
                        Lihat
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
