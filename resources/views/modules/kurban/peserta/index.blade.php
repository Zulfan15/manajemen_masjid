@extends('layouts.app')
@section('title', 'Peserta Kurban')

@section('content')
<div class="container mx-auto">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-users text-green-700 mr-2"></i>Peserta Kurban
                </h1>
                <p class="text-gray-600 mt-2">Daftar peserta kurban tahun ini</p>
            </div>

            @if(!auth()->user()->isSuperAdmin())
                <a href="{{ route('kurban.peserta.create') }}"
                   class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Peserta
                </a>
            @endif
        </div>

        @if(auth()->user()->isSuperAdmin())
            <div class="mt-4 bg-blue-50 border-l-4 border-blue-500 p-4">
                <p class="text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Mode View Only: Super Admin hanya dapat melihat data.
                </p>
            </div>
        @endif
    </div>

    <!-- Tabel Peserta -->
    <div class="bg-white rounded-lg shadow p-6">
        @if($peserta->count() == 0)
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-users text-6xl mb-4 text-gray-300"></i>
                <p>Belum ada peserta kurban</p>
            </div>
        @else
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Jenis Peserta</th>
                        <th class="px-4 py-3 text-left">Iuran</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($peserta as $p)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $p->nama }}</td>
                            <td class="px-4 py-3 capitalize">{{ $p->tipe }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($p->iuran,0,',','.') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-white
                                    {{ $p->status == 'lunas' ? 'bg-green-600' : 'bg-yellow-500' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-3">

                                    <a href="{{ route('kurban.peserta.edit', $p->id) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if(!auth()->user()->isSuperAdmin())
                                        <form action="{{ route('kurban.peserta.destroy', $p->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus?')">
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
