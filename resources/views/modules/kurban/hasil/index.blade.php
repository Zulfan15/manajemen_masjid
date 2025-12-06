@extends('layouts.app')
@section('title', 'Hasil Pemotongan Hewan Kurban')

@section('content')
<div class="container mx-auto">

    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-drumstick-bite text-green-700 mr-2"></i>Hasil Pemotongan
                </h1>
                <p class="text-gray-600 mt-2">Catatan hasil pemotongan hewan kurban</p>
            </div>

            @if(!auth()->user()->isSuperAdmin())
            <a href="{{ route('kurban.hasil.create') }}"
               class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                <i class="fas fa-plus mr-2"></i>Input Hasil
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
    <div class="bg-white rounded-lg shadow p-6">

        @if($hasil->count() == 0)

        <div class="text-center py-16 text-gray-500">
            <i class="fas fa-drumstick-bite text-6xl mb-4 text-gray-300"></i>
            <p>Belum ada data hasil potong</p>
        </div>

        @else

        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">ID Penyembelihan</th>
                    <th class="px-4 py-3 text-left">Daging (kg)</th>
                    <th class="px-4 py-3 text-left">Tulang (kg)</th>
                    <th class="px-4 py-3 text-left">Jeroan (kg)</th>
                    <th class="px-4 py-3 text-left">Kulit (kg)</th>
                    <th class="px-4 py-3 text-left">Kantong</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach($hasil as $h)
                <tr class="border-b">

                    <td class="px-4 py-3">{{ $h->penyembelihan_id }}</td>
                    <td class="px-4 py-3">{{ $h->daging }}</td>
                    <td class="px-4 py-3">{{ $h->tulang }}</td>
                    <td class="px-4 py-3">{{ $h->jeroan }}</td>
                    <td class="px-4 py-3">{{ $h->kulit }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $h->total_kantong }}</td>

                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-4">

                            <a href="{{ route('kurban.hasil.show', $h->id) }}"
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if(!auth()->user()->isSuperAdmin())
                            <form action="{{ route('kurban.hasil.destroy', $h->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus data ini?')">
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
