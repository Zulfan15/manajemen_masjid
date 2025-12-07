@extends('layouts.app')

@section('title', 'Edit Peserta Kurban')

@section('content')
<div class="container mx-auto max-w-3xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-edit text-green-700 mr-2"></i>Edit Peserta Kurban
                </h1>
                <p class="text-gray-600 mt-2">Nama: <strong>{{ $peserta->nama_peserta }}</strong> | Kurban: <strong>{{ $kurban->nomor_kurban }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('kurban.peserta.update', [$kurban, $peserta]) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Row 1: Nama Peserta & Identitas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Peserta <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_peserta" value="{{ old('nama_peserta', $peserta->nama_peserta) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('nama_peserta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Identitas (KTP)</label>
                    <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas', $peserta->nomor_identitas) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('nomor_identitas')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 2: Telepon & Tipe -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $peserta->nomor_telepon) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('nomor_telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Peserta <span class="text-red-500">*</span></label>
                    <select name="tipe_peserta" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="perorangan" {{ old('tipe_peserta', $peserta->tipe_peserta) === 'perorangan' ? 'selected' : '' }}>Perorangan</option>
                        <option value="keluarga" {{ old('tipe_peserta', $peserta->tipe_peserta) === 'keluarga' ? 'selected' : '' }}>Keluarga</option>
                    </select>
                    @error('tipe_peserta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('alamat', $peserta->alamat) }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 3: Jumlah Jiwa & Jumlah Bagian -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Jiwa <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_jiwa" value="{{ old('jumlah_jiwa', $peserta->jumlah_jiwa) }}" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    <small class="text-gray-500 mt-1 block">Jumlah jiwa jika tipe keluarga</small>
                    @error('jumlah_jiwa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Bagian <span class="text-red-500">*</span></label>
                    <input type="number" step="0.25" name="jumlah_bagian" value="{{ old('jumlah_bagian', $peserta->jumlah_bagian) }}" min="0.25" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    <small class="text-gray-500 mt-1 block">Minimum 0.25 bagian</small>
                    @error('jumlah_bagian')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 4: Pembayaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pembayaran (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="nominal_pembayaran" value="{{ old('nominal_pembayaran', $peserta->nominal_pembayaran) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('nominal_pembayaran')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pembayaran <span class="text-red-500">*</span></label>
                    <select name="status_pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="belum_lunas" {{ old('status_pembayaran', $peserta->status_pembayaran) === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="cicilan" {{ old('status_pembayaran', $peserta->status_pembayaran) === 'cicilan' ? 'selected' : '' }}>Cicilan</option>
                        <option value="lunas" {{ old('status_pembayaran', $peserta->status_pembayaran) === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    @error('status_pembayaran')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('catatan', $peserta->catatan) }}</textarea>
                @error('catatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('kurban.show', $kurban) }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 transition">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
