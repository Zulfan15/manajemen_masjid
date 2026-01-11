@extends('layouts.app')

@section('title', 'Daftar Donasi Jamaah')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-hand-holding-usd text-green-600 mr-2"></i>Daftar Donasi
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola semua donasi jamaah masjid</p>
                </div>
                <a href="{{ route('jamaah.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Jamaah
                </a>
            </div>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Total Donasi (Confirmed)</p>
                        <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($totalDonasi ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-money-bill-wave text-3xl text-green-100"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm">Menunggu Konfirmasi</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $donasiPending ?? 0 }}</h3>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-clock text-3xl text-yellow-100"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('jamaah.donasi.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                    <select name="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Jenis</option>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div class="w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('jamaah.donasi.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel Donasi --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Donatur</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Jenis</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($donations as $donasi)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $donasi->donation_date?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($donasi->jamaah)
                                        <a href="{{ route('jamaah.show', $donasi->jamaah) }}"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                            {{ $donasi->jamaah->nama_lengkap }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                                        {{ $donasi->type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($donasi->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('jamaah.donasi.update-status', $donasi) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="text-xs px-2 py-1 rounded border-0 bg-{{ $donasi->status_color }}-100 text-{{ $donasi->status_color }}-800 cursor-pointer">
                                            <option value="pending" {{ $donasi->status == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="confirmed" {{ $donasi->status == 'confirmed' ? 'selected' : '' }}>
                                                Confirmed</option>
                                            <option value="cancelled" {{ $donasi->status == 'cancelled' ? 'selected' : '' }}>
                                                Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($donasi->bukti_transfer)
                                            <a href="{{ Storage::url($donasi->bukti_transfer) }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800" title="Lihat Bukti">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('jamaah.donasi.destroy', $donasi) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Yakin ingin menghapus donasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-hand-holding-heart text-5xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada data donasi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($donations->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                alert('{{ session('success') }}');
            });
        </script>
    @endif
@endsection