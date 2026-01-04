@extends('layouts.app')

@section('title', 'Dashboard Modul Takmir')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-tachometer-alt text-green-600 mr-2"></i>Dashboard Modul Takmir
        </h1>
        <p class="text-gray-600 mt-2">Ringkasan dan statistik manajemen takmir/pengurus masjid</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Takmir -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">Total Takmir</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_takmir'] }}</h3>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-green-600 font-medium">{{ $stats['takmir_aktif'] }} Aktif</span>
                <span class="text-gray-400 mx-2">•</span>
                <span class="text-red-600">{{ $stats['takmir_nonaktif'] }} Non-aktif</span>
            </div>
        </div>

        <!-- Aktivitas Bulan Ini -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-clipboard-check text-purple-600 text-2xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">Aktivitas Bulan Ini</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800">{{ $stats['aktivitas_bulan_ini'] }}</h3>
            <p class="text-sm text-gray-600 mt-2">
                <i class="fas fa-calendar-alt mr-1"></i>{{ now()->format('F Y') }}
            </p>
        </div>

        <!-- Pemilihan Aktif -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-vote-yea text-indigo-600 text-2xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">Pemilihan</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800">{{ $stats['pemilihan_aktif'] }}</h3>
            <p class="text-sm text-gray-600 mt-2">
                @if($stats['pemilihan_aktif'] > 0)
                    <span class="text-green-600 font-medium">
                        <i class="fas fa-circle text-xs mr-1"></i>Sedang Berlangsung
                    </span>
                @else
                    <span class="text-gray-500">Tidak ada pemilihan aktif</span>
                @endif
            </p>
        </div>

        <!-- Total Votes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">Total Suara</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_votes'] }}</h3>
            <p class="text-sm text-gray-600 mt-2">Dari semua pemilihan</p>
        </div>
    </div>

    <!-- Charts & Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Aktivitas Harian Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-bar text-purple-600 mr-2"></i>Aktivitas 7 Hari Terakhir
            </h3>
            <div style="height: 250px;">
                <canvas id="aktivitasChart"></canvas>
            </div>
        </div>

        <!-- Distribusi Jabatan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-pie text-blue-600 mr-2"></i>Distribusi Jabatan
            </h3>
            <div style="height: 250px;">
                <canvas id="jabatanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Upcoming -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-history text-gray-600 mr-2"></i>Aktivitas Terbaru
                </h3>
                <a href="{{ route('takmir.aktivitas.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentActivities as $aktivitas)
                    <div class="flex items-start border-l-4 border-purple-500 pl-4 py-2 hover:bg-gray-50 transition">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $aktivitas->judul }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ str($aktivitas->deskripsi)->limit(80) }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>{{ $aktivitas->tanggal->format('d M Y') }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-tag mr-1"></i>{{ $aktivitas->kategori }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>Belum ada aktivitas tercatat</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Active Pemilihan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-vote-yea text-indigo-600 mr-2"></i>Pemilihan
                </h3>
                <a href="{{ route('takmir.pemilihan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Kelola <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($pemilihanList as $pemilihan)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $pemilihan->judul }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ str($pemilihan->deskripsi)->limit(60) }}</p>
                                <div class="flex items-center mt-2 text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $pemilihan->tanggal_mulai->format('d M') }} - {{ $pemilihan->tanggal_selesai->format('d M Y') }}
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $pemilihan->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($pemilihan->status) }}
                            </span>
                        </div>
                        @if($pemilihan->isBerlangsung())
                            <a href="{{ route('takmir.pemilihan.vote', $pemilihan->id) }}" 
                               class="block mt-3 bg-indigo-600 text-white text-center py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                                <i class="fas fa-vote-yea mr-1"></i>Berikan Suara
                            </a>
                        @else
                            <a href="{{ route('takmir.pemilihan.hasil', $pemilihan->id) }}" 
                               class="block mt-3 bg-gray-600 text-white text-center py-2 rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                                <i class="fas fa-chart-bar mr-1"></i>Lihat Hasil
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>Belum ada pemilihan</p>
                        @can('takmir.create')
                            <a href="{{ route('takmir.pemilihan.create') }}" class="inline-block mt-3 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                                <i class="fas fa-plus mr-1"></i>Buat Pemilihan
                            </a>
                        @endcan
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Aktivitas Chart
    const aktivitasCtx = document.getElementById('aktivitasChart').getContext('2d');
    new Chart(aktivitasCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['aktivitas_labels']),
            datasets: [{
                label: 'Jumlah Aktivitas',
                data: @json($chartData['aktivitas_data']),
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Jabatan Chart
    const jabatanCtx = document.getElementById('jabatanChart').getContext('2d');
    new Chart(jabatanCtx, {
        type: 'doughnut',
        data: {
            labels: @json($chartData['jabatan_labels']),
            datasets: [{
                data: @json($chartData['jabatan_data']),
                backgroundColor: [
                    'rgb(59, 130, 246)',   // Blue
                    'rgb(168, 85, 247)',   // Purple
                    'rgb(34, 197, 94)',    // Green
                    'rgb(234, 179, 8)',    // Yellow
                    'rgb(107, 114, 128)'   // Gray
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
