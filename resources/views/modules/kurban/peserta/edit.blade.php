@extends('layouts.app')
@section('title', 'Edit Peserta Kurban')

@section('content')
<div class="container mx-auto">

    <div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-edit text-green-700 mr-2"></i>Edit Peserta Kurban
        </h1>

        <form action="{{ route('kurban.peserta.update', $peserta->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Nama Peserta</label>
                <input type="text" name="nama" value="{{ $peserta->nama }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Tipe -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Tipe Peserta</label>
                <select name="tipe" class="w-full border rounded px-3 py-2">
                    <option value="perorangan" {{ $peserta->tipe=='perorangan' ? 'selected':'' }}>Perorangan</option>
                    <option value="patungan" {{ $peserta->tipe=='patungan' ? 'selected':'' }}>Patungan</option>
                </select>
            </div>

            <!-- Iuran -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Iuran (Rp)</label>
                <input type="number" name="iuran" value="{{ $peserta->iuran }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold">Status Pembayaran</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="pending" {{ $peserta->status=='pending' ? 'selected':'' }}>Pending</option>
                    <option value="lunas"   {{ $peserta->status=='lunas'   ? 'selected':'' }}>Lunas</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('kurban.peserta.index') }}"
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
