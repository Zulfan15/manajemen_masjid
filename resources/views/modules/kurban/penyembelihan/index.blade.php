@extends('layouts.app')
@section('title', 'Jadwal Penyembelihan')

@section('content')
<div class="container mx-auto">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-knife-kitchen text-green-700 mr-2"></i>Jadwal Penyembelihan
                </h1>
                <p class="text-gray-600 mt-2">Pengaturan jadwal dan lokasi penyembelihan</p>
            </div>

            @if(!auth()->user()->isSuperAdmin())
            <a href="{{ route('kurban.penyembelihan.create') }}"
               class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
               <i class="fas fa-plus mr-2"></i>Buat Jadwal
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

    <!-- Tabel Jadwal -->
    <div class="bg-white shadow rounded-lg p-6">

        @if($jadwal->count() == 0)
        <div class="text-center py-16 text-gray-500">
            <i class="fas fa-knife-kitchen text-6xl mb-4 text-gray-300"></i>
            <p>Belum ada jadwal penyembelihan</p>
        </div>

        @else
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach($jadwal as $j)
                <tr class="border-b">
                    <td class="px-4 py-3">{{ $j->tanggal }}</td>
                    <td class="px-4 py-3">{{ $j->lokasi }}</td>

                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-4">

                            <a href="{{ route('kurban.penyembelihan.edit', $j->id) }}"
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>

                            @if(!auth()->user()->isSuperAdmin())
                            <form action="{{ route('kurban.penyembelihan.destroy', $j->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')"
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
