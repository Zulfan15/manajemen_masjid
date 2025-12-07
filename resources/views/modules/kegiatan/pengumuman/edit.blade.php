@extends('layouts.app')
@section('title', 'Edit Pengumuman')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('kegiatan.pengumuman.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-bullhorn text-green-700 mr-2"></i>Edit Pengumuman
                    </h1>
                    <p class="text-gray-600 mt-2">Update pengumuman kegiatan masjid</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-6">
                    <ul class="list-disc list-inside text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kegiatan.pengumuman.update', $pengumuman) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Pengumuman <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" required value="{{ old('judul', $pengumuman->judul) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Contoh: Kajian Rutin Malam Jumat">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        <option value="kajian" {{ old('kategori', $pengumuman->kategori) == 'kajian' ? 'selected' : '' }}>
                            Kajian</option>
                        <option value="kegiatan"
                            {{ old('kategori', $pengumuman->kategori) == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="event" {{ old('kategori', $pengumuman->kategori) == 'event' ? 'selected' : '' }}>
                            Event Khusus</option>
                        <option value="umum" {{ old('kategori', $pengumuman->kategori) == 'umum' ? 'selected' : '' }}>Umum
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Terkait Kegiatan (Opsional)
                    </label>
                    <select name="kegiatan_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach ($kegiatans as $keg)
                            <option value="{{ $keg->id }}"
                                {{ old('kegiatan_id', $pengumuman->kegiatan_id) == $keg->id ? 'selected' : '' }}>
                                {{ $keg->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Isi Pengumuman <span class="text-red-500">*</span>
                    </label>
                    <textarea name="konten" required rows="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Tulis isi pengumuman di sini...">{{ old('konten', $pengumuman->konten) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" required
                            value="{{ old('tanggal_mulai', $pengumuman->tanggal_mulai->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Berakhir
                        </label>
                        <input type="date" name="tanggal_berakhir"
                            value="{{ old('tanggal_berakhir', $pengumuman->tanggal_berakhir?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="aktif"
                                {{ old('status', $pengumuman->status) == 'aktif' ? 'checked' : '' }} class="mr-2">
                            <span class="text-gray-700">Aktif</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="nonaktif"
                                {{ old('status', $pengumuman->status) == 'nonaktif' ? 'checked' : '' }} class="mr-2">
                            <span class="text-gray-700">Tidak Aktif</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Prioritas
                    </label>
                    <select name="prioritas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="normal"
                            {{ old('prioritas', $pengumuman->prioritas) == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="tinggi"
                            {{ old('prioritas', $pengumuman->prioritas) == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="mendesak"
                            {{ old('prioritas', $pengumuman->prioritas) == 'mendesak' ? 'selected' : '' }}>Mendesak
                        </option>
                    </select>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('kegiatan.pengumuman.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-save mr-2"></i>Update Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
