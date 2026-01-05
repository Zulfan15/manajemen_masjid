@extends('layouts.app')
@section('title', 'Keuangan Masjid')
@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-money-bill-wave text-green-700 mr-2"></i>Keuangan Masjid
                </h1>
                <p class="text-gray-600 mt-2">Kelola keuangan dan transaksi masjid</p>
            </div>
            @if(auth()->user()->canManageKeuangan())
                <button onclick="window.location.href='{{ route('pemasukan.create') }}'" 
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Transaksi
                </button>
            @endif
        </div>
        @if(auth()->user()->isSuperAdmin())
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Mode View Only</strong> - Anda hanya dapat melihat data keuangan
                </p>
            </div>
        @endif
        
        <!-- NAVIGASI MODUL KE MODUL CRUD -->
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Modul Keuangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- CARD PEMASUKAN -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-600 p-3 rounded-lg mr-4">
                            <i class="fas fa-money-bill-wave text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Pemasukan</h3>
                            <p class="text-gray-600 text-sm">Kelola pemasukan masjid</p>
                        </div>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-check-circle text-green-500 mr-2 text-sm"></i>
                            <span class="text-sm">CRUD Lengkap</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-chart-bar text-blue-500 mr-2 text-sm"></i>
                            <span class="text-sm">Statistik & Rekap</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-filter text-purple-500 mr-2 text-sm"></i>
                            <span class="text-sm">Filter & Pencarian</span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="window.location.href='{{ route('pemasukan.index') }}'" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded transition flex items-center justify-center">
                            <i class="fas fa-eye mr-2"></i>Lihat Data
                        </button>
                        @if(auth()->user()->canManageKeuangan())
                        @endif
                    </div>
                </div>

                <!-- CARD PENGELUARAN -->
                <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-600 p-3 rounded-lg mr-4">
                            <i class="fas fa-receipt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Pengeluaran</h3>
                            <p class="text-gray-600 text-sm">Kelola pengeluaran masjid</p>
                        </div>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-clock text-yellow-500 mr-2 text-sm"></i>
                            <span class="text-sm">Segera Hadir</span>
                        </div>
                    </div>
                    <button onclick="alert('Modul Pengeluaran sedang dalam pengembangan!')" 
                            class="w-full bg-gray-400 hover:bg-gray-500 text-white py-2 px-4 rounded transition flex items-center justify-center cursor-not-allowed">
                        <i class="fas fa-clock mr-2"></i>Dalam Pengembangan
                    </button>
                </div>

                <!-- CARD LAPORAN - UPDATED ✅ -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-600 p-3 rounded-lg mr-4">
                            <i class="fas fa-chart-bar text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Laporan</h3>
                            <p class="text-gray-600 text-sm">Analisis keuangan</p>
                        </div>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-file-pdf text-red-500 mr-2 text-sm"></i>
                            <span class="text-sm">Export PDF</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-file-excel text-green-500 mr-2 text-sm"></i>
                            <span class="text-sm">Export Excel</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-chart-pie text-purple-500 mr-2 text-sm"></i>
                            <span class="text-sm">Grafik Visual</span>
                        </div>
                    </div>
                    <button onclick="window.location.href='{{ route('laporan.index') }}'" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition flex items-center justify-center">
                        <i class="fas fa-chart-bar mr-2"></i>Lihat Rekap
                    </button>
                </div>
            </div>
        </div>

        <!-- TOMBOL AKSES CEPAT -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>Akses Cepat Pemasukan
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('pemasukan.index') }}" 
                   class="bg-white border border-gray-300 hover:border-green-500 hover:bg-green-50 rounded-lg p-4 text-center transition group">
                    <div class="text-green-600 mb-2">
                        <i class="fas fa-list text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-green-700">Daftar Pemasukan</h4>
                    <p class="text-gray-600 text-sm mt-1">Lihat semua data</p>
                </a>
                
                @if(auth()->user()->canManageKeuangan())
                <a href="{{ route('pemasukan.create') }}" 
                   class="bg-white border border-gray-300 hover:border-green-500 hover:bg-green-50 rounded-lg p-4 text-center transition group">
                    <div class="text-green-600 mb-2">
                        <i class="fas fa-plus-circle text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-green-700">Tambah Baru</h4>
                    <p class="text-gray-600 text-sm mt-1">Input pemasukan baru</p>
                </a>
                @endif
                
                <a href="{{ route('pemasukan.index') }}?filter=today" 
                   class="bg-white border border-gray-300 hover:border-blue-500 hover:bg-blue-50 rounded-lg p-4 text-center transition group">
                    <div class="text-blue-600 mb-2">
                        <i class="fas fa-calendar-day text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-blue-700">Hari Ini</h4>
                    <p class="text-gray-600 text-sm mt-1">Transaksi hari ini</p>
                </a>
                
                <!-- TOMBOL REKAP DATA - UPDATED ✅ -->
                <a href="{{ route('laporan.index') }}" 
                   class="bg-white border border-gray-300 hover:border-purple-500 hover:bg-purple-50 rounded-lg p-4 text-center transition group">
                    <div class="text-purple-600 mb-2">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-purple-700">Rekap Data</h4>
                    <p class="text-gray-600 text-sm mt-1">Analisis & statistik</p>
                </a>
            </div>
        </div>

        <!-- PANDUAN PENGGUNAAN -->
        <div class="border border-gray-300 rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Panduan Penggunaan
            </h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-arrow-right text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Akses Modul Pemasukan</h4>
                        <p class="text-gray-600 text-sm">Klik tombol "Lihat Data" pada card Pemasukan untuk mengakses data lengkap</p>
                    </div>
                </div>
                @if(auth()->user()->canManageKeuangan())
                <div class="flex items-start">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-plus text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Tambah Data Baru</h4>
                        <p class="text-gray-600 text-sm">Gunakan tombol "Tambah Transaksi" atau "Tambah Baru" untuk input data pemasukan baru</p>
                    </div>
                </div>
                @endif
                <div class="flex items-start">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-chart-bar text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Analisis Data</h4>
                        <p class="text-gray-600 text-sm">Gunakan fitur rekap untuk melihat statistik dan analisis data pemasukan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .group:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }
    button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }
</style>
@endpush