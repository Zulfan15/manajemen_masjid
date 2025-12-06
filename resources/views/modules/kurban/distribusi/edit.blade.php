@extends('layouts.app')
@section('title', 'Edit Distribusi')

@section('content')
<div class="container mx-auto">

    <div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-edit text-green-700 mr-2"></i>Edit Distribusi
        </h1>

        <form action="{{ route('kurban.distribusi.update', $distribusi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tanggal -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $distribusi->tanggal }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Tujuan -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Tujuan Distribusi</label>
                <input type="text" name="tujuan" class="w-full border rounded px-3 py-2"
                       value="{{ $distribusi->tujuan }}" required>
            </div>

            <!-- Jumlah Kantong -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Jumlah Kantong</label>
                <input type="number" name="jumlah_kantong"
                       value="{{ $distribusi->jumlah_kantong }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Penanggung Jawab -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold">Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab"
                       value="{{ $distribusi->penanggung_jawab }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('kurban.distribusi.index') }}"
                   class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </a>

                <button class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">
                    Perbarui
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
