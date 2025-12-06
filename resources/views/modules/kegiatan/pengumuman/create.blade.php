@extends('layouts.app')
@section('title', 'Tambah Pengumuman')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('kegiatan.pengumuman.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-bullhorn text-green-700 mr-2"></i>Tambah Pengumuman
                    </h1>
                    <p class="text-gray-600 mt-2">Buat pengumuman baru untuk kegiatan masjid</p>
                </div>
            </div>

            <form action="#" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Pengumuman <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Contoh: Kajian Rutin Malam Jumat">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        <option value="kajian">Kajian</option>
                        <option value="kegiatan">Kegiatan</option>
                        <option value="event">Event Khusus</option>
                        <option value="umum">Umum</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Isi Pengumuman <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" required rows="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Tulis isi pengumuman di sini..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Berakhir
                        </label>
                        <input type="date" name="end_date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="aktif" checked class="mr-2">
                            <span class="text-gray-700">Aktif</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="nonaktif" class="mr-2">
                            <span class="text-gray-700">Tidak Aktif</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Prioritas
                    </label>
                    <select name="priority"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="normal">Normal</option>
                        <option value="tinggi">Tinggi</option>
                        <option value="mendesak">Mendesak</option>
                    </select>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('kegiatan.pengumuman.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-save mr-2"></i>Simpan Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
