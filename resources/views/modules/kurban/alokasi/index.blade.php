@extends('layouts.app')
@section('title', 'Alokasi Peserta ke Hewan')

@section('content')
<div class="container mx-auto">

    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-link text-green-700 mr-2"></i>Alokasi Peserta
                </h1>
                <p class="text-gray-600 mt-2">Hubungkan peserta dengan hewan kurban</p>
            </div>

            @if(!auth()->user()->isSuperAdmin())
            <a href="{{ route('kurban.alokasi.create') }}"
               class="bg-green-700 px-4 py-2 rounded text-white hover:bg-green-800 transition">
               <i class="fas fa-plus mr-2"></i>Tambah Alokasi
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
        @if($alokasi->count() == 0)
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-link text-6xl mb-4 text-gray-300"></i>
                <p>Belum ada data alokasi</p>
            </div>
        @else
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Peserta</th>
                        <th class="px-4 py-3 text-left">Hewan</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($alokasi as $a)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $a->peserta->nama }}</td>
                            <td class="px-4 py-3">{{ ucfirst($a->hewan->jenis) }}</td>

                            <td class="px-4 py-3 text-center">

                                @if(!auth()->user()->isSuperAdmin())
                                <form action="{{ route('kurban.alokasi.destroy', $a->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus alokasi ini?')"
                                      class="inline">
                                    @csrf @method('DELETE')

                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 italic">View Only</span>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection
