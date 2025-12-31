@extends('layouts.app')

@section('title', 'Edit Kurban')

@section('content')
<div class="container mx-auto max-w-3xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-edit text-green-700 mr-2"></i>Edit Kurban
                </h1>
                <p class="text-gray-600 mt-2">Nomor: <strong>{{ $kurban->nomor_kurban }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('kurban.update', $kurban) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Row 1: Nama Hewan & Berat Badan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Hewan (Opsional)</label>
                    <input type="text" name="nama_hewan" value="{{ old('nama_hewan', $kurban->nama_hewan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('nama_hewan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Berat Badan (kg) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="berat_badan" value="{{ old('berat_badan', $kurban->berat_badan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('berat_badan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 2: Kondisi Kesehatan & Tanggal Persiapan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kondisi Kesehatan <span class="text-red-500">*</span></label>
                    <select name="kondisi_kesehatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="sehat" {{ old('kondisi_kesehatan', $kurban->kondisi_kesehatan) === 'sehat' ? 'selected' : '' }}>Sehat</option>
                        <option value="cacat_ringan" {{ old('kondisi_kesehatan', $kurban->kondisi_kesehatan) === 'cacat_ringan' ? 'selected' : '' }}>Cacat Ringan</option>
                        <option value="cacat_berat" {{ old('kondisi_kesehatan', $kurban->kondisi_kesehatan) === 'cacat_berat' ? 'selected' : '' }}>Cacat Berat</option>
                    </select>
                    @error('kondisi_kesehatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Persiapan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_persiapan" value="{{ old('tanggal_persiapan', $kurban->tanggal_persiapan->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('tanggal_persiapan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 3: Tanggal Penyembelihan & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Penyembelihan (Opsional)</label>
                    <input type="date" name="tanggal_penyembelihan" value="{{ old('tanggal_penyembelihan', $kurban->tanggal_penyembelihan?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('tanggal_penyembelihan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="disiapkan" {{ old('status', $kurban->status) === 'disiapkan' ? 'selected' : '' }}>Disiapkan</option>
                        <option value="siap_sembelih" {{ old('status', $kurban->status) === 'siap_sembelih' ? 'selected' : '' }}>Siap Disembelih</option>
                        <option value="disembelih" {{ old('status', $kurban->status) === 'disembelih' ? 'selected' : '' }}>Disembelih</option>
                        <option value="didistribusi" {{ old('status', $kurban->status) === 'didistribusi' ? 'selected' : '' }}>Didistribusi</option>
                        <option value="selesai" {{ old('status', $kurban->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 4: Harga Hewan & Biaya Operasional -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Belian Hewan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="harga_hewan" value="{{ old('harga_hewan', $kurban->harga_hewan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('harga_hewan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Biaya Operasional (Rp)</label>
                    <input type="number" step="0.01" name="biaya_operasional" value="{{ old('biaya_operasional', $kurban->biaya_operasional) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <small class="text-gray-500 mt-1 block">Penyembelihan, transportasi, dll</small>
                    @error('biaya_operasional')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="catatan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('catatan', $kurban->catatan) }}</textarea>
                @error('catatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <p class="text-sm text-blue-700"><i class="fas fa-info-circle mr-2"></i>Total Biaya akan dihitung otomatis dari Harga Hewan + Biaya Operasional</p>
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
