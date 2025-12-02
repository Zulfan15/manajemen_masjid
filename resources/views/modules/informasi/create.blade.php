@extends('layouts.app')

@section('title', isset($post) ? 'Edit Info' : 'Tambah Info')

@section('content')
<div class="container mx-auto max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">{{ isset($post) ? 'Edit Informasi' : 'Tulis Informasi Baru' }}</h2>
        
        <form action="{{ isset($post) ? route('informasi.update', $post->id) : route('informasi.store') }}" method="POST">
            @csrf
            @if(isset($post)) @method('PUT') @endif

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Judul</label>
                <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                <select name="category" class="w-full border rounded p-2">
                    @foreach(['berita', 'pengumuman', 'artikel', 'dakwah'] as $cat)
                        <option value="{{ $cat }}" {{ (old('category', $post->category ?? '') == $cat) ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Isi Konten</label>
                <textarea name="content" rows="10" class="w-full border rounded p-2" required>{{ old('content', $post->content ?? '') }}</textarea>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('informasi.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2">Batal</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded hover:bg-green-800">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection