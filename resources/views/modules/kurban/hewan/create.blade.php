@extends('layouts.app')
@section('title', 'Tambah Hewan Kurban')

@section('content')
<div class="container mx-auto">

    <div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-cow text-green-700 mr-2"></i>Tambah Hewan Kurban
        </h1>

        <form action="{{ route('kurban.hewan.store') }}" method="POST">
            @csrf

            <!-- Jenis -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Jenis Hewan</label>
                <select name="jenis" class="w-full border rounded px-3 py-2">
                    <option value="sapi">Sapi</option>
                    <option value="kambing">Kambing</option>
                    <option value="domba">Domba</option>
                </select>
            </div>

            <!-- Bobot -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Bobot (Kg)</label>
                <input type="number" name="bobot" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Harga -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Harga (Rp)</label>
                <input type="number" name="harga" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold">Status Hewan</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="siap">Siap</option>
                    <option value="ditunda">Ditunda</option>
                </select>
            </div>

            <!-- Aksi -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('kurban.hewan.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
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
