@extends('layouts.app')
@section('title', 'Edit Jadwal Penyembelihan')

@section('content')
<div class="container mx-auto">

    <div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-edit text-green-700 mr-2"></i>Edit Jadwal Penyembelihan
        </h1>

        <form action="{{ route('kurban.penyembelihan.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tanggal -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Tanggal Penyembelihan</label>
                <input type="date" name="tanggal"
                       value="{{ $jadwal->tanggal }}"
                       class="w-full border rounded px-3 py-2"
                       required>
            </div>

            <!-- Lokasi -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold">Lokasi Penyembelihan</label>
                <input type="text" name="lokasi"
                       value="{{ $jadwal->lokasi }}"
                       class="w-full border rounded px-3 py-2"
                       required>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('kurban.penyembelihan.index') }}"
                   class="px-4 py-2 bg-gray-300 rounded text-gray-800 hover:bg-gray-400">
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
