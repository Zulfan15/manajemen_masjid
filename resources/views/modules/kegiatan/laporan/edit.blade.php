@extends('layouts.app')
@section('title', 'Edit Laporan Kegiatan')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('kegiatan.laporan.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-file-alt text-green-700 mr-2"></i>Edit Laporan Kegiatan
                    </h1>
                    <p class="text-gray-600 mt-2">Update dokumentasi kegiatan</p>
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

            <form action="{{ route('kegiatan.laporan.update', $laporan) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Kegiatan (Opsional)
                        </label>
                        <select name="kegiatan_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach ($kegiatans as $keg)
                                <option value="{{ $keg->id }}"
                                    {{ old('kegiatan_id', $laporan->kegiatan_id) == $keg->id ? 'selected' : '' }}>
                                    {{ $keg->nama }} - {{ $keg->tanggal_mulai->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Atau isi manual di bawah</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kegiatan" required
                            value="{{ old('nama_kegiatan', $laporan->nama_kegiatan) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Contoh: Kajian Rutin Jumat">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kegiatan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Pilih Jenis</option>
                            <option value="kajian"
                                {{ old('jenis_kegiatan', $laporan->jenis_kegiatan) == 'kajian' ? 'selected' : '' }}>Kajian
                                Islami</option>
                            <option value="sosial"
                                {{ old('jenis_kegiatan', $laporan->jenis_kegiatan) == 'sosial' ? 'selected' : '' }}>Kegiatan
                                Sosial</option>
                            <option value="pendidikan"
                                {{ old('jenis_kegiatan', $laporan->jenis_kegiatan) == 'pendidikan' ? 'selected' : '' }}>
                                Pendidikan</option>
                            <option value="perayaan"
                                {{ old('jenis_kegiatan', $laporan->jenis_kegiatan) == 'perayaan' ? 'selected' : '' }}>
                                Perayaan</option>
                            <option value="lainnya"
                                {{ old('jenis_kegiatan', $laporan->jenis_kegiatan) == 'lainnya' ? 'selected' : '' }}>
                                Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pelaksanaan" required
                            value="{{ old('tanggal_pelaksanaan', $laporan->tanggal_pelaksanaan->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="waktu_pelaksanaan" required
                            value="{{ old('waktu_pelaksanaan', $laporan->waktu_pelaksanaan->format('H:i')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="lokasi" required value="{{ old('lokasi', $laporan->lokasi) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Contoh: Ruang Utama Masjid">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Peserta <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah_peserta" required min="0"
                            value="{{ old('jumlah_peserta', $laporan->jumlah_peserta) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Hadir
                        </label>
                        <input type="number" name="jumlah_hadir" min="0"
                            value="{{ old('jumlah_hadir', $laporan->jumlah_hadir) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Penanggung Jawab
                        </label>
                        <input type="text" name="penanggung_jawab"
                            value="{{ old('penanggung_jawab', $laporan->penanggung_jawab) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" required rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Jelaskan jalannya kegiatan...">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hasil & Capaian
                    </label>
                    <textarea name="hasil_capaian" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Hasil atau capaian dari kegiatan ini...">{{ old('hasil_capaian', $laporan->hasil_capaian) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan/Kendala
                    </label>
                    <textarea name="catatan_kendala" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Catatan atau kendala yang dihadapi...">{{ old('catatan_kendala', $laporan->catatan_kendala) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tambah Foto Dokumentasi (Opsional)
                    </label>
                    <input type="file" name="foto_dokumentasi[]" multiple accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Upload maksimal 5 foto (JPG, PNG, max 2MB per file). Foto lama
                        akan tetap ada.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="draft" {{ old('status', $laporan->status) == 'draft' ? 'selected' : '' }}>
                                Draft</option>
                            <option value="published"
                                {{ old('status', $laporan->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_public" value="1"
                                {{ old('is_public', $laporan->is_public) ? 'checked' : '' }}
                                class="mr-2 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <span class="text-gray-700">Publikasikan untuk umum</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('kegiatan.laporan.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-save mr-2"></i>Update Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
