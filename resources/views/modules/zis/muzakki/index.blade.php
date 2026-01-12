@extends('layouts.app')
@section('title', 'Data Muzakki')
@section('content')
    <div class="container mx-auto">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-users text-blue-600 mr-2"></i>Data Muzakki
                    </h1>
                    <p class="text-gray-600 mt-1">Kelola data pemberi zakat</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('zis.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    @if(!auth()->user()->isSuperAdmin())
                        <a href="{{ route('zis.muzakki.create') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Tambah Muzakki
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-600 mb-1">Total Muzakki</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $muzakki->total() }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-600 mb-1">Total Transaksi</p>
                    <p class="text-2xl font-bold text-green-700">{{ \App\Models\Transaksi::count() }}</p>
                </div>
                <div class="bg-amber-50 p-4 rounded-lg">
                    <p class="text-sm text-amber-600 mb-1">Total ZIS Diterima</p>
                    <p class="text-2xl font-bold text-amber-700">Rp
                        {{ number_format(\App\Models\Transaksi::sum('nominal'), 0, ',', '.') }}</p>
                </div>
            </div>

            @if($muzakki->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. HP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Kelamin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Kontribusi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($muzakki as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $muzakki->firstItem() + $index }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800">{{ $item->nama_lengkap }}</p>
                                        <p class="text-sm text-gray-500">{{ Str::limit($item->alamat, 30) }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item->nik ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item->no_hp }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 {{ $item->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }} text-xs rounded-full">
                                            {{ $item->jenis_kelamin }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-green-600">
                                        Rp {{ number_format($item->transaksi->sum('nominal'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            @if(!auth()->user()->isSuperAdmin())
                                                <a href="{{ route('zis.muzakki.edit', $item->id) }}"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('zis.muzakki.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus data muzakki ini?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-sm">View Only</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $muzakki->links() }}
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-users text-5xl mb-3 text-gray-300"></i>
                    <p class="text-lg">Belum ada data muzakki</p>
                    @if(!auth()->user()->isSuperAdmin())
                        <a href="{{ route('zis.muzakki.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                            Tambah muzakki pertama
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection