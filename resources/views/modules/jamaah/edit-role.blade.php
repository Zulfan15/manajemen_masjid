@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">
        Ubah Kategori Jamaah
    </h2>

    <p class="text-gray-600 mb-4">
        {{ $jamaah->nama_lengkap }}
    </p>

    <form method="POST" action="{{ route('jamaah.role.update', $jamaah->id) }}">
        @csrf

        <!-- ROLE UTAMA -->
        <div class="mb-4">
            <label class="font-semibold block mb-2">Peran Utama</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2">
                    <input type="radio" name="main_role" value="umum" required
                        {{ $jamaah->categories->contains('nama','umum') ? 'checked' : '' }}>
                    Umum
                </label>

                <label class="flex items-center gap-2">
                    <input type="radio" name="main_role" value="pengurus"
                        {{ $jamaah->categories->contains('nama','pengurus') ? 'checked' : '' }}>
                    Pengurus
                </label>
            </div>
        </div>

        <!-- DONATUR -->
        <div class="mb-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="donatur" value="1"
                    {{ $jamaah->categories->contains('nama','Donatur') ? 'checked' : '' }}>
                Aktif sebagai Donatur
            </label>
        </div>

        <!-- ACTION -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('jamaah.index') }}"
               class="px-4 py-2 border rounded">
               Batal
            </a>

            <button class="bg-indigo-600 text-white px-4 py-2 rounded">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
