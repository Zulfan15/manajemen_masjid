@extends('layouts.app')
@section('title', 'Buat Laporan Kegiatan')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('kegiatan.laporan.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-file-alt text-green-700 mr-2"></i>Buat Laporan Kegiatan
                    </h1>
                    <p class="text-gray-600 mt-2">Dokumentasikan kegiatan yang telah dilaksanakan</p>
                </div>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Contoh: Kajian Rutin Jumat">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Pilih Jenis</option>
                            <option value="kajian">Kajian Islami</option>
                            <option value="sosial">Kegiatan Sosial</option>
                            <option value="pendidikan">Pendidikan</option>
                            <option value="perayaan">Perayaan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="time" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Contoh: Ruang Utama Masjid">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Peserta <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="participants" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Penanggung Jawab
                        </label>
                        <input type="text" name="pic"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Nama Penanggung Jawab">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" required rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Jelaskan jalannya kegiatan..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hasil & Capaian
                    </label>
                    <textarea name="achievements" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Hasil atau capaian dari kegiatan ini..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan/Kendala
                    </label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Catatan atau kendala yang dihadapi..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dokumentasi Foto
                    </label>
                    <input type="file" name="photos[]" multiple accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Upload maksimal 5 foto (JPG, PNG, max 2MB per file)</p>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('kegiatan.laporan.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-save mr-2"></i>Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
