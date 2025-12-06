@extends('layouts.app')
@section('title', 'Manajemen Kurban')
@section('content')
<div class="container mx-auto">

    <div class="bg-white rounded-lg shadow p-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-sheep text-green-700 mr-2"></i>Manajemen Kurban
                </h1>
                <p class="text-gray-600 mt-2">Kelola data kurban</p>
            </div>

            @if(!auth()->user()->isSuperAdmin())
                <button class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Data Kurban
                </button>
            @endif
        </div>

        <!-- Superadmin Notice -->
        @if(auth()->user()->isSuperAdmin())
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Mode View Only</strong>: Super Admin hanya dapat melihat data.
                </p>
            </div>
        @endif

        <!-- Navigasi Submodul -->
        <div class="text-center py-12 text-gray-700">

            <i class="fas fa-sheep text-6xl mb-6 text-gray-300"></i>

            <h3 class="text-xl font-semibold mb-6">Navigasi Modul Kurban</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">

                <!-- Peserta -->
                <a href="{{ route('kurban.peserta.index') }}"
                   class="block p-5 border rounded-lg hover:bg-gray-50 transition shadow-sm hover:shadow">
                    <i class="fas fa-users text-green-700 text-3xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Peserta Kurban</p>
                    <p class="text-sm text-gray-600 mt-1">Kelola peserta individu / kelompok</p>
                </a>

                <!-- Hewan -->
                <a href="{{ route('kurban.hewan.index') }}"
                   class="block p-5 border rounded-lg hover:bg-gray-50 transition shadow-sm hover:shadow">
                    <i class="fas fa-cow text-green-700 text-3xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Data Hewan Kurban</p>
                    <p class="text-sm text-gray-600 mt-1">Kelola hewan dan detailnya</p>
                </a>

                <!-- Alokasi -->
                <a href="{{ route('kurban.alokasi.index') }}"
                   class="block p-5 border rounded-lg hover:bg-gray-50 transition shadow-sm hover:shadow">
                    <i class="fas fa-link text-green-700 text-3xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Alokasi Peserta</p>
                    <p class="text-sm text-gray-600 mt-1">Hubungkan peserta ke hewan</p>
                </a>

                <!-- Penyembelihan -->
                <a href="{{ route('kurban.penyembelihan.index') }}"
                   class="block p-5 border rounded-lg hover:bg-gray-50 transition shadow-sm hover:shadow">
                    <i class="fas fa-knife-kitchen text-green-700 text-3xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Jadwal Penyembelihan</p>
                    <p class="text-sm text-gray-600 mt-1">Atur tanggal & lokasi</p>
                </a>

                <!-- Hasil -->
                <a href="{{ route('kurban.hasil.index') }}"
                   class="block p-5 border rounded-lg hover:bg-gray-50 transition shadow-sm hover:shadow">
                    <i class="fas fa-drumstick-bite text-green-700 text-3xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Hasil Pemotongan</p>
                    <p class="text-sm text-gray-600 mt-1">Input daging, jeroan, kulit</p>
                </a>

                <!-- Distribusi -->
                <a href="{{ route('kurban.distribusi.index') }}"
                   class="block p-5 border rounded-lg hover:bg-gray-50 transition shadow-sm hover:shadow">
                    <i class="fas fa-hand-holding-heart text-green-700 text-3xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Distribusi Daging</p>
                    <p class="text-sm text-gray-600 mt-1">Kelola pembagian daging</p>
                </a>

            </div>

        </div>

    </div>

</div>
@endsection
