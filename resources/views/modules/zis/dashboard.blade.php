@extends('layouts.app')
@section('title', 'Dashboard ZIS')
@section('content')
    <div class="container mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-hand-holding-heart text-green-700 mr-2"></i>Dashboard ZIS
            </h1>
            <p class="text-gray-600 mt-2">Manajemen Zakat, Infak, dan Sedekah</p>
        </div>

        @if(auth()->user()->isSuperAdmin())
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6 rounded">
                <p class="text-blue-700"><i class="fas fa-info-circle mr-2"></i><strong>Mode View Only</strong> - Anda melihat
                    sebagai Super Admin</p>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Muzakki -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total Muzakki</p>
                        <h3 class="text-3xl font-bold">{{ number_format($total_muzakki ?? 0) }}</h3>
                        <p class="text-blue-200 text-xs mt-1">Pemberi Zakat</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 p-4 rounded-full">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Mustahiq -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Total Mustahiq</p>
                        <h3 class="text-3xl font-bold">{{ number_format($total_mustahiq ?? 0) }}</h3>
                        <p class="text-purple-200 text-xs mt-1">Penerima Zakat Aktif</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 p-4 rounded-full">
                        <i class="fas fa-hand-holding-heart text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Pemasukan -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Total Pemasukan</p>
                        <h3 class="text-2xl font-bold">Rp {{ number_format($total_pemasukan ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-green-200 text-xs mt-1">
                            @if(($persentase_perubahan ?? 0) >= 0)
                                <i class="fas fa-arrow-up"></i> {{ number_format($persentase_perubahan ?? 0, 1) }}% dari bulan
                                lalu
                            @else
                                <i class="fas fa-arrow-down"></i> {{ number_format(abs($persentase_perubahan ?? 0), 1) }}% dari
                                bulan lalu
                            @endif
                        </p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 p-4 rounded-full">
                        <i class="fas fa-wallet text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Saldo Akhir -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 text-sm">Saldo Tersedia</p>
                        <h3 class="text-2xl font-bold">Rp {{ number_format($saldo_akhir ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-amber-200 text-xs mt-1">Siap disalurkan</p>
                    </div>
                    <div class="bg-amber-400 bg-opacity-30 p-4 rounded-full">
                        <i class="fas fa-coins text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('zis.mustahiq.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition group">
                <div class="flex items-center">
                    <div
                        class="bg-purple-100 text-purple-600 p-4 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fas fa-user-friends text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-gray-800">Mustahiq</h4>
                        <p class="text-gray-500 text-sm">Kelola penerima zakat</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('zis.muzakki.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition group">
                <div class="flex items-center">
                    <div
                        class="bg-blue-100 text-blue-600 p-4 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-gray-800">Muzakki</h4>
                        <p class="text-gray-500 text-sm">Kelola pemberi zakat</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('zis.transaksi.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition group">
                <div class="flex items-center">
                    <div
                        class="bg-green-100 text-green-600 p-4 rounded-lg group-hover:bg-green-600 group-hover:text-white transition">
                        <i class="fas fa-plus-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-gray-800">Transaksi</h4>
                        <p class="text-gray-500 text-sm">Input ZIS masuk</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('zis.penyaluran.index') }}"
                class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition group">
                <div class="flex items-center">
                    <div
                        class="bg-amber-100 text-amber-600 p-4 rounded-lg group-hover:bg-amber-600 group-hover:text-white transition">
                        <i class="fas fa-hand-holding-usd text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-gray-800">Penyaluran</h4>
                        <p class="text-gray-500 text-sm">Distribusi zakat</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Ringkasan Penyaluran -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-pie text-green-600 mr-2"></i>Ringkasan Penyaluran
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-arrow-down text-green-600"></i>
                            </div>
                            <span class="text-gray-700">Total Penyaluran</span>
                        </div>
                        <span class="font-semibold text-gray-800">Rp
                            {{ number_format($total_penyaluran ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-percentage text-blue-600"></i>
                            </div>
                            <span class="text-gray-700">Persentase Tersalurkan</span>
                        </div>
                        <span class="font-semibold text-gray-800">
                            @if(($total_pemasukan ?? 0) > 0)
                                {{ number_format((($total_penyaluran ?? 0) / ($total_pemasukan ?? 1)) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Kategori Mustahiq -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-list text-purple-600 mr-2"></i>Kategori Mustahiq
                </h3>
                <div class="space-y-3">
                    @php
                        $kategori = ['Fakir', 'Miskin', 'Amil', 'Muallaf', 'Riqab', 'Gharimin', 'Fisabilillah', 'Ibnu Sabil'];
                    @endphp
                    @foreach($kategori as $kat)
                        <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                            <span class="text-gray-700">{{ $kat }}</span>
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                {{ \App\Models\Mustahiq::where('kategori', $kat)->where('status_aktif', 1)->count() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection