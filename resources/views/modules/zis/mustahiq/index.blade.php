@extends('layouts.app')
@section('title', 'Data Mustahiq')
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
                        <i class="fas fa-user-friends text-purple-600 mr-2"></i>Data Mustahiq
                    </h1>
                    <p class="text-gray-600 mt-1">Kelola data penerima zakat</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('zis.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    @if(!auth()->user()->isSuperAdmin())
                        <a href="{{ route('zis.mustahiq.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                            <i class="fas fa-plus mr-2"></i>Tambah Mustahiq
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-sm text-purple-600 mb-1">Total Mustahiq</p>
                    <p class="text-2xl font-bold text-purple-700">{{ $mustahiq->total() }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-600 mb-1">Aktif</p>
                    <p class="text-2xl font-bold text-green-700">{{ \App\Models\Mustahiq::where('status_aktif', 1)->count() }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <p class="text-sm text-red-600 mb-1">Tidak Aktif</p>
                    <p class="text-2xl font-bold text-red-700">{{ \App\Models\Mustahiq::where('status_aktif', 0)->count() }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-600 mb-1">Penyaluran</p>
                    <p class="text-2xl font-bold text-blue-700">{{ \App\Models\Penyaluran::count() }}</p>
                </div>
            </div>

            @if($mustahiq->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. HP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($mustahiq as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $mustahiq->firstItem() + $index }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800">{{ $item->nama_lengkap }}</p>
                                        <p class="text-sm text-gray-500">{{ Str::limit($item->alamat, 30) }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item->nik ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item->no_hp }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">
                                            {{ $item->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->status_aktif)
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            @if(!auth()->user()->isSuperAdmin())
                                                <a href="{{ route('zis.mustahiq.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('zis.mustahiq.destroy', $item->id) }}" method="POST" 
                                                    onsubmit="return confirm('Hapus data mustahiq ini?')" class="inline">
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
                    {{ $mustahiq->links() }}
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-user-friends text-5xl mb-3 text-gray-300"></i>
                    <p class="text-lg">Belum ada data mustahiq</p>
                    @if(!auth()->user()->isSuperAdmin())
                        <a href="{{ route('zis.mustahiq.create') }}" class="text-purple-600 hover:underline mt-2 inline-block">
                            Tambah mustahiq pertama
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
