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

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-semibold">Terjadi kesalahan:</p>
                            <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('kegiatan.laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Kegiatan (Opsional)
                    </label>
                    <select name="kegiatan_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">-- Manual Input --</option>
                        @foreach($kegiatans as $kegiatan)
                            <option value="{{ $kegiatan->id }}" {{ old('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>
                                {{ $kegiatan->nama }} ({{ $kegiatan->tanggal_pelaksanaan->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih dari kegiatan yang sudah selesai atau input manual di bawah</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nama_kegiatan') border-red-500 @enderror"
                            placeholder="Contoh: Kajian Rutin Jumat">
                        @error('nama_kegiatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kegiatan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('jenis_kegiatan') border-red-500 @enderror">
                            <option value="">Pilih Jenis</option>
                            <option value="kajian" {{ old('jenis_kegiatan') == 'kajian' ? 'selected' : '' }}>Kajian Islami</option>
                            <option value="sosial" {{ old('jenis_kegiatan') == 'sosial' ? 'selected' : '' }}>Kegiatan Sosial</option>
                            <option value="pendidikan" {{ old('jenis_kegiatan') == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="perayaan" {{ old('jenis_kegiatan') == 'perayaan' ? 'selected' : '' }}>Perayaan</option>
                            <option value="lainnya" {{ old('jenis_kegiatan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_kegiatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pelaksanaan" value="{{ old('tanggal_pelaksanaan') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tanggal_pelaksanaan') border-red-500 @enderror">
                        @error('tanggal_pelaksanaan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="waktu_pelaksanaan" value="{{ old('waktu_pelaksanaan') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('waktu_pelaksanaan') border-red-500 @enderror">
                        @error('waktu_pelaksanaan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('lokasi') border-red-500 @enderror"
                        placeholder="Contoh: Ruang Utama Masjid">
                    @error('lokasi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Peserta <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta') }}" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('jumlah_peserta') border-red-500 @enderror"
                            placeholder="0">
                        @error('jumlah_peserta')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Hadir
                        </label>
                        <input type="number" name="jumlah_hadir" value="{{ old('jumlah_hadir') }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Penanggung Jawab
                        </label>
                        <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Nama Penanggung Jawab">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" required rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                        placeholder="Jelaskan jalannya kegiatan...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hasil & Capaian
                    </label>
                    <textarea name="hasil_capaian" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Hasil atau capaian dari kegiatan ini...">{{ old('hasil_capaian') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan/Kendala
                    </label>
                    <textarea name="catatan_kendala" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Catatan atau kendala yang dihadapi...">{{ old('catatan_kendala') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Publikasi <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="published" {{ old('status', 'draft') == 'published' ? 'checked' : '' }} class="mr-2">
                            <span class="text-gray-700">Published (Publik)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="draft" {{ old('status', 'draft') == 'draft' ? 'checked' : '' }} class="mr-2">
                            <span class="text-gray-700">Draft (Belum Dipublikasikan)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm text-gray-700">Tampilkan di halaman publik</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dokumentasi Foto
                    </label>
                    <input type="file" name="foto_dokumentasi[]" multiple accept="image/*"
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
