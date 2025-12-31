@extends('layouts.app')

@section('title', 'Tambah Kurban Baru')

@section('content')
<div class="container mx-auto max-w-3xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-plus text-green-700 mr-2"></i>Tambah Kurban Baru
                </h1>
                <p class="text-gray-600 mt-2">Form untuk membuat data kurban baru</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('kurban.store') }}" class="space-y-6">
            @csrf

            <!-- Row 1: Nomor Kurban & Jenis Hewan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Kurban <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_kurban" value="{{ old('nomor_kurban') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Contoh: KURBAN-2025-001" required>
                    @error('nomor_kurban')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Hewan <span class="text-red-500">*</span></label>
                    <select name="jenis_hewan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="sapi" {{ old('jenis_hewan') === 'sapi' ? 'selected' : '' }}>Sapi</option>
                        <option value="kambing" {{ old('jenis_hewan') === 'kambing' ? 'selected' : '' }}>Kambing</option>
                        <option value="domba" {{ old('jenis_hewan') === 'domba' ? 'selected' : '' }}>Domba</option>
                    </select>
                    @error('jenis_hewan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 2: Nama Hewan & Berat Badan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Hewan (Opsional)</label>
                    <input type="text" name="nama_hewan" value="{{ old('nama_hewan') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Contoh: Si Putih">
                    @error('nama_hewan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Berat Badan (kg) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="berat_badan" value="{{ old('berat_badan') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Contoh: 500" required>
                    @error('berat_badan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 3: Kondisi Kesehatan & Tanggal Persiapan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kondisi Kesehatan <span class="text-red-500">*</span></label>
                    <select name="kondisi_kesehatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="sehat" {{ old('kondisi_kesehatan') === 'sehat' ? 'selected' : '' }}>Sehat</option>
                        <option value="cacat_ringan" {{ old('kondisi_kesehatan') === 'cacat_ringan' ? 'selected' : '' }}>Cacat Ringan</option>
                        <option value="cacat_berat" {{ old('kondisi_kesehatan') === 'cacat_berat' ? 'selected' : '' }}>Cacat Berat</option>
                    </select>
                    @error('kondisi_kesehatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Persiapan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_persiapan" value="{{ old('tanggal_persiapan') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('tanggal_persiapan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 4: Harga Hewan & Biaya Operasional -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Belian Hewan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="harga_hewan" value="{{ old('harga_hewan') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Contoh: 15000000" required>
                    @error('harga_hewan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Biaya Operasional (Rp)</label>
                    <input type="number" step="0.01" name="biaya_operasional" value="{{ old('biaya_operasional', 0) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Contoh: 1000000">
                    <small class="text-gray-500 mt-1 block">Penyembelihan, transportasi, dll</small>
                    @error('biaya_operasional')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="catatan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan catatan tambahan jika ada">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('kurban.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 transition">
                    <i class="fas fa-save mr-2"></i>Simpan Kurban
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
