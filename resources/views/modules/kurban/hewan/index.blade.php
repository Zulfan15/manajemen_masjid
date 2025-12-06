@extends('layouts.app')
@section('title', 'Data Hewan Kurban')

@section('content')
<div class="container mx-auto">

    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-cow text-green-700 mr-2"></i>Data Hewan Kurban
                </h1>
                <p class="text-gray-600 mt-2">Kelola daftar hewan yang akan disembelih</p>
            </div>

            @if(!auth()->user()->isSuperAdmin())
            <a href="{{ route('kurban.hewan.create') }}"
               class="bg-green-700 px-4 py-2 rounded text-white hover:bg-green-800 transition">
               <i class="fas fa-plus mr-2"></i>Tambah Hewan
            </a>
            @endif
        </div>

        @if(auth()->user()->isSuperAdmin())
            <div class="mt-4 bg-blue-50 border-l-4 border-blue-600 p-4">
                <p class="text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Mode View Only: Super Admin hanya dapat melihat data.
                </p>
            </div>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg p-6">
        @if($hewan->count() == 0)
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-cow text-6xl mb-4 text-gray-300"></i>
                <p>Belum ada data hewan kurban</p>
            </div>
        @else
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Jenis Hewan</th>
                        <th class="px-4 py-3 text-left">Bobot</th>
                        <th class="px-4 py-3 text-left">Harga</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($hewan as $h)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ ucfirst($h->jenis) }}</td>
                            <td class="px-4 py-3">{{ $h->bobot }} Kg</td>
                            <td class="px-4 py-3">Rp {{ number_format($h->harga,0,',','.') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-white
                                    {{ $h->status == 'siap' ? 'bg-green-600' : 'bg-yellow-600' }}">
                                    {{ ucfirst($h->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-4">

                                    <a href="{{ route('kurban.hewan.edit', $h->id) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if(!auth()->user()->isSuperAdmin())
                                    <form action="{{ route('kurban.hewan.destroy', $h->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection
