@extends('layouts.app')
@section('title', 'Edit Kegiatan')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-edit text-green-700 mr-2"></i>Edit Kegiatan
                </h1>
                <p class="text-gray-600 mt-2">Edit informasi kegiatan atau event masjid</p>
            </div>

            <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informasi Dasar -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-info-circle text-green-700 mr-2"></i>Informasi Dasar
                        </h3>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kegiatan"
                            value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('nama_kegiatan') border-red-500 @enderror">
                        @error('nama_kegiatan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kegiatan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('jenis_kegiatan') border-red-500 @enderror">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="rutin"
                                {{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) == 'rutin' ? 'selected' : '' }}>Rutin
                            </option>
                            <option value="insidental"
                                {{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) == 'insidental' ? 'selected' : '' }}>
                                Insidental</option>
                            <option value="event_khusus"
                                {{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) == 'event_khusus' ? 'selected' : '' }}>
                                Event Khusus</option>
                        </select>
                        @error('jenis_kegiatan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('kategori') border-red-500 @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="kajian" {{ old('kategori') == 'kajian' ? 'selected' : '' }}>Kajian</option>
                            <option value="sosial" {{ old('kategori') == 'sosial' ? 'selected' : '' }}>Sosial</option>
                            <option value="ibadah" {{ old('kategori') == 'ibadah' ? 'selected' : '' }}>Ibadah</option>
                            <option value="pendidikan" {{ old('kategori') == 'pendidikan' ? 'selected' : '' }}>Pendidikan
                            </option>
                            <option value="ramadan" {{ old('kategori') == 'ramadan' ? 'selected' : '' }}>Ramadan</option>
                            <option value="maulid" {{ old('kategori') == 'maulid' ? 'selected' : '' }}>Maulid</option>
                            <option value="qurban" {{ old('kategori') == 'qurban' ? 'selected' : '' }}>Qurban</option>
                            <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu & Tempat -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-clock text-green-700 mr-2"></i>Waktu & Tempat
                        </h3>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('tanggal_selesai') border-red-500 @enderror">
                        @error('tanggal_selesai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('waktu_mulai') border-red-500 @enderror">
                        @error('waktu_mulai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('waktu_selesai') border-red-500 @enderror">
                        @error('waktu_selesai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="lokasi" value="{{ old('lokasi', 'Masjid') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('lokasi') border-red-500 @enderror">
                        @error('lokasi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penanggung Jawab -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-user-tie text-green-700 mr-2"></i>Penanggung Jawab
                        </h3>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama PIC</label>
                        <input type="text" name="pic" value="{{ old('pic') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kontak PIC</label>
                        <input type="text" name="kontak_pic" value="{{ old('kontak_pic') }}" placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Peserta & Budget -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-users text-green-700 mr-2"></i>Peserta & Anggaran
                        </h3>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kuota Peserta
                        </label>
                        <input type="number" name="kuota_peserta" value="{{ old('kuota_peserta') }}" min="1"
                            placeholder="Kosongkan jika unlimited"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ada batasan peserta</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Budget (Rp)</label>
                        <input type="number" name="budget" value="{{ old('budget') }}" min="0" step="1000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Pengaturan Tambahan -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-cog text-green-700 mr-2"></i>Pengaturan Tambahan
                        </h3>
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-center gap-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="butuh_pendaftaran" value="1"
                                    {{ old('butuh_pendaftaran', true) ? 'checked' : '' }}
                                    class="mr-2 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">Butuh Pendaftaran</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="sertifikat_tersedia" value="1"
                                    {{ old('sertifikat_tersedia') ? 'checked' : '' }}
                                    class="mr-2 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">Sertifikat Tersedia</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="is_recurring" value="1"
                                    {{ old('is_recurring') ? 'checked' : '' }} id="is_recurring"
                                    onchange="toggleRecurring()"
                                    class="mr-2 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">Kegiatan Berulang</span>
                            </label>
                        </div>
                    </div>

                    <div id="recurring_fields" class="md:col-span-2 hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pengulangan</label>
                                <select name="recurring_type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="harian" {{ old('recurring_type') == 'harian' ? 'selected' : '' }}>
                                        Harian</option>
                                    <option value="mingguan" {{ old('recurring_type') == 'mingguan' ? 'selected' : '' }}>
                                        Mingguan</option>
                                    <option value="bulanan" {{ old('recurring_type') == 'bulanan' ? 'selected' : '' }}>
                                        Bulanan</option>
                                    <option value="tahunan" {{ old('recurring_type') == 'tahunan' ? 'selected' : '' }}>
                                        Tahunan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hari/Tanggal</label>
                                <input type="text" name="recurring_day" value="{{ old('recurring_day') }}"
                                    placeholder="Contoh: Jumat, atau 1,15"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar/Poster</label>
                        <input type="file" name="gambar" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 mt-8 border-t pt-6">
                    <button type="submit"
                        class="px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                        <i class="fas fa-save mr-2"></i>Simpan Kegiatan
                    </button>
                    <a href="{{ route('kegiatan.index') }}"
                        class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleRecurring() {
            const checkbox = document.getElementById('is_recurring');
            const fields = document.getElementById('recurring_fields');
            fields.classList.toggle('hidden', !checkbox.checked);
        }

        // Check on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRecurring();
        });
    </script>
@endsection
