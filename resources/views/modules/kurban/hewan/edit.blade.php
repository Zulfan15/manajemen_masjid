@extends('layouts.app')
@section('title', 'Edit Hewan Kurban')

@section('content')
<div class="container mx-auto">

    <div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-edit text-green-700 mr-2"></i>Edit Hewan Kurban
        </h1>

        <form action="{{ route('kurban.hewan.update', $hewan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Jenis -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Jenis Hewan</label>
                <select name="jenis" class="w-full border rounded px-3 py-2">
                    <option value="sapi"    {{ $hewan->jenis=='sapi' ? 'selected':'' }}>Sapi</option>
                    <option value="kambing" {{ $hewan->jenis=='kambing' ? 'selected':'' }}>Kambing</option>
                    <option value="domba"   {{ $hewan->jenis=='domba' ? 'selected':'' }}>Domba</option>
                </select>
            </div>

            <!-- Bobot -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Bobot (Kg)</label>
                <input type="number" name="bobot"
                       value="{{ $hewan->bobot }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Harga -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Harga (Rp)</label>
                <input type="number" name="harga"
                       value="{{ $hewan->harga }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold">Status Hewan</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="siap"    {{ $hewan->status=='siap' ? 'selected':'' }}>Siap</option>
                    <option value="ditunda" {{ $hewan->status=='ditunda' ? 'selected':'' }}>Ditunda</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('kurban.hewan.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
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
