@extends('layouts.app')

@section('title', 'Aktivitas Harian Pengurus')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Aktivitas Harian Pengurus</h1>
            <p class="text-gray-600 mt-1">Pencatatan aktivitas harian pengurus masjid</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('takmir.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Data Pengurus
            </a>
            <a href="{{ route('takmir.aktivitas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Aktivitas
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
    @endif

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('takmir.aktivitas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filter Pengurus (Only for Admin) -->
            @if(auth()->user()->hasRole('admin_takmir') && $pengurusList)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pengurus</label>
                <select name="takmir_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Semua Pengurus</option>
                    @foreach($pengurusList as $pengurus)
                        <option value="{{ $pengurus->id }}" {{ request('takmir_id') == $pengurus->id ? 'selected' : '' }}>
                            {{ $pengurus->nama }} - {{ $pengurus->jabatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Filter Tanggal Mulai -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <!-- Filter Tanggal Akhir -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <!-- Filter Jenis Aktivitas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aktivitas</label>
                <select name="jenis_aktivitas" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Semua Jenis</option>
                    <option value="Ibadah" {{ request('jenis_aktivitas') == 'Ibadah' ? 'selected' : '' }}>Ibadah</option>
                    <option value="Kebersihan" {{ request('jenis_aktivitas') == 'Kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                    <option value="Administrasi" {{ request('jenis_aktivitas') == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                    <option value="Pengajaran" {{ request('jenis_aktivitas') == 'Pengajaran' ? 'selected' : '' }}>Pengajaran</option>
                    <option value="Pembinaan" {{ request('jenis_aktivitas') == 'Pembinaan' ? 'selected' : '' }}>Pembinaan</option>
                    <option value="Keuangan" {{ request('jenis_aktivitas') == 'Keuangan' ? 'selected' : '' }}>Keuangan</option>
                    <option value="Sosial" {{ request('jenis_aktivitas') == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                    <option value="Lainnya" {{ request('jenis_aktivitas') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('takmir.aktivitas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Aktivitas Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        @if(auth()->user()->hasRole('admin_takmir'))
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengurus</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Aktivitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Foto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($aktivitas as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $aktivitas->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->tanggal->format('d/m/Y') }}
                            <div class="text-xs text-gray-500">{{ $item->tanggal->diffForHumans() }}</div>
                        </td>
                        @if(auth()->user()->hasRole('admin_takmir'))
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->takmir->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $item->takmir->jabatan }}</div>
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($item->jenis_aktivitas == 'Ibadah') bg-purple-100 text-purple-800
                                @elseif($item->jenis_aktivitas == 'Kebersihan') bg-green-100 text-green-800
                                @elseif($item->jenis_aktivitas == 'Administrasi') bg-blue-100 text-blue-800
                                @elseif($item->jenis_aktivitas == 'Pengajaran') bg-yellow-100 text-yellow-800
                                @elseif($item->jenis_aktivitas == 'Pembinaan') bg-indigo-100 text-indigo-800
                                @elseif($item->jenis_aktivitas == 'Keuangan') bg-red-100 text-red-800
                                @elseif($item->jenis_aktivitas == 'Sosial') bg-pink-100 text-pink-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $item->jenis_aktivitas }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                            <div class="line-clamp-2">{{ $item->deskripsi }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($item->durasi_jam)
                                <i class="fas fa-clock text-gray-400 mr-1"></i>
                                {{ $item->durasi_jam }} jam
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item->bukti_foto)
                                <a href="{{ $item->bukti_foto_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-image text-xl"></i>
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                <a href="{{ route('takmir.aktivitas.show', $item->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @php
                                    $canEdit = auth()->user()->hasRole('admin_takmir') || 
                                               (auth()->user()->hasRole('pengurus_takmir') && 
                                                $item->takmir->user_id == auth()->id() && 
                                                $item->created_at->diffInHours(now()) <= 24);
                                @endphp
                                
                                @if($canEdit)
                                <a href="{{ route('takmir.aktivitas.edit', $item->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasRole('admin_takmir') || 
                                    (auth()->user()->hasRole('pengurus_takmir') && $item->takmir->user_id == auth()->id()))
                                <form action="{{ route('takmir.aktivitas.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')"
                                            class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->hasRole('admin_takmir') ? '8' : '7' }}" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Belum ada data aktivitas harian</p>
                            <a href="{{ route('takmir.aktivitas.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                Tambah aktivitas pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($aktivitas->hasPages())
        <div class="px-6 py-4 bg-gray-50">
            {{ $aktivitas->links() }}
        </div>
        @endif
    </div>

    <!-- Summary Statistics (if admin) -->
    @if(auth()->user()->hasRole('admin_takmir') && $aktivitas->count() > 0)
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="text-blue-600 text-sm font-medium">Total Aktivitas</div>
            <div class="text-2xl font-bold text-blue-800">{{ $aktivitas->total() }}</div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="text-green-600 text-sm font-medium">Total Durasi</div>
            <div class="text-2xl font-bold text-green-800">
                {{ number_format($aktivitas->sum('durasi_jam'), 1) }} jam
            </div>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="text-purple-600 text-sm font-medium">Rata-rata per Aktivitas</div>
            <div class="text-2xl font-bold text-purple-800">
                {{ $aktivitas->sum('durasi_jam') > 0 ? number_format($aktivitas->sum('durasi_jam') / $aktivitas->count(), 1) : '0' }} jam
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
