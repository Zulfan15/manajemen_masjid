@extends('layouts.app')
@section('title', 'Tambah Alokasi Peserta')

@section('content')
<div class="container mx-auto">

    <div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-link text-green-700 mr-2"></i>Tambah Alokasi Peserta
        </h1>

        <form action="{{ route('kurban.alokasi.store') }}" method="POST">
            @csrf

            <!-- Pilih Peserta -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Pilih Peserta</label>
                <select name="peserta_id" class="w-full border rounded px-3 py-2" required>
                    @foreach($peserta as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->nama }} ({{ ucfirst($p->tipe) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Hewan -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-1">Pilih Hewan</label>
                <select name="hewan_id" class="w-full border rounded px-3 py-2" required>
                    @foreach($hewan as $h)
                        <option value="{{ $h->id }}">
                            {{ ucfirst($h->jenis) }} - {{ $h->bobot }} Kg
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('kurban.alokasi.index') }}"
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
