@extends('layouts.app')
@section('title', 'Buat Jadwal Penyembelihan')

@section('content')
<div class="container mx-auto">

    <div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-knife-kitchen text-green-700 mr-2"></i>Buat Jadwal Penyembelihan
        </h1>

        <form action="{{ route('kurban.penyembelihan.store') }}" method="POST">
            @csrf

            <!-- Tanggal -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Tanggal Penyembelihan</label>
                <input type="date" name="tanggal"
                       class="w-full border rounded px-3 py-2"
                       required>
            </div>

            <!-- Lokasi -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold">Lokasi Penyembelihan</label>
                <input type="text" name="lokasi"
                       class="w-full border rounded px-3 py-2"
                       placeholder="Contoh: Halaman Masjid, Lapangan Desa"
                       required>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('kurban.penyembelihan.index') }}"
                   class="px-4 py-2 bg-gray-300 rounded text-gray-800 hover:bg-gray-400">
                    Batal
                </a>

                <button class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
