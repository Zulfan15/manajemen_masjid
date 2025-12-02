@extends('layouts.app')

@section('title', 'Manajemen Informasi')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-bullhorn text-green-700 mr-2"></i>Kelola Informasi</h1>
            <div class="flex gap-2">
                <a href="{{ route('public.home') }}" target="_blank" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Lihat Web</a>
                @if(!auth()->user()->isSuperAdmin())
                <a href="{{ route('informasi.create') }}" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800"><i class="fas fa-plus mr-1"></i> Tambah</a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-3 border-b">Judul</th>
                        <th class="p-3 border-b">Kategori</th>
                        <th class="p-3 border-b">Tanggal</th>
                        <th class="p-3 border-b text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-3 font-medium">{{ $post->title }}</td>
                        <td class="p-3"><span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ ucfirst($post->category) }}</span></td>
                        <td class="p-3 text-sm text-gray-500">{{ $post->created_at->format('d M Y') }}</td>
                        <td class="p-3 text-right">
                            <form action="{{ route('informasi.broadcast', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Kirim email ke jamaah?')">
                                @csrf <button class="text-yellow-600 mr-2" title="Broadcast"><i class="fas fa-envelope"></i></button>
                            </form>
                            @if(!auth()->user()->isSuperAdmin())
                            <a href="{{ route('informasi.edit', $post->id) }}" class="text-blue-600 mr-2"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('informasi.destroy', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE') <button class="text-red-600"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-5 text-center text-gray-500">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection