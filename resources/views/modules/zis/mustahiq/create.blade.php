@extends('layouts.app')
@section('title', 'Tambah Mustahiq')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-user-plus text-purple-600 mr-2"></i>Tambah Mustahiq
                    </h1>
                    <p class="text-gray-600 mt-1">Daftarkan penerima zakat baru</p>
                </div>
                <a href="{{ route('zis.mustahiq.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <form action="{{ route('zis.mustahiq.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('nama_lengkap') border-red-500 @enderror">
                    @error('nama_lengkap')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('no_hp') border-red-500 @enderror">
                    @error('no_hp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat <span
                            class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span
                            class="text-red-500">*</span></label>
                    <select name="kategori" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('kategori') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        <option value="Fakir" {{ old('kategori') == 'Fakir' ? 'selected' : '' }}>Fakir</option>
                        <option value="Miskin" {{ old('kategori') == 'Miskin' ? 'selected' : '' }}>Miskin</option>
                        <option value="Amil" {{ old('kategori') == 'Amil' ? 'selected' : '' }}>Amil</option>
                        <option value="Muallaf" {{ old('kategori') == 'Muallaf' ? 'selected' : '' }}>Muallaf</option>
                        <option value="Riqab" {{ old('kategori') == 'Riqab' ? 'selected' : '' }}>Riqab</option>
                        <option value="Gharimin" {{ old('kategori') == 'Gharimin' ? 'selected' : '' }}>Gharimin</option>
                        <option value="Fisabilillah" {{ old('kategori') == 'Fisabilillah' ? 'selected' : '' }}>Fisabilillah
                        </option>
                        <option value="Ibnu Sabil" {{ old('kategori') == 'Ibnu Sabil' ? 'selected' : '' }}>Ibnu Sabil</option>
                    </select>
                    @error('kategori')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="status_aktif" value="1" checked
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Status Aktif</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <a href="{{ route('zis.mustahiq.index') }}"
                        class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-center transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection