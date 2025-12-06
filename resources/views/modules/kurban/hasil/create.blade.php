@extends('layouts.app')
@section('title', 'Input Hasil Pemotongan')

@section('content')
<div class="container mx-auto">

    <div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-drumstick-bite text-green-700 mr-2"></i>Input Hasil Pemotongan
        </h1>

        <form action="{{ route('kurban.hasil.store') }}" method="POST">
            @csrf

            <!-- Penyembelihan -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">ID Penyembelihan</label>
                <select name="penyembelihan_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Jadwal Penyembelihan --</option>
                    @foreach($penyembelihan as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->id }} — {{ $p->tanggal }} ({{ $p->lokasi }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Daging -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Daging (kg)</label>
                <input type="number" step="0.1" name="daging" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Tulang -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Tulang (kg)</label>
                <input type="number" step="0.1" name="tulang" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Jeroan -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Jeroan (kg)</label>
                <input type="number" step="0.1" name="jeroan" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Kulit -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Kulit (kg)</label>
                <input type="number" step="0.1" name="kulit" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Kantong -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold">Total Kantong</label>
                <input type="number" name="total_kantong" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('kurban.hasil.index') }}"
                   class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
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
