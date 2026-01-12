@extends('layouts.app')
@section('title', 'Edit Mustahiq')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-user-edit text-purple-600 mr-2"></i>Edit Mustahiq
                    </h1>
                    <p class="text-gray-600 mt-1">Update data penerima zakat</p>
                </div>
                <a href="{{ route('zis.mustahiq.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <form action="{{ route('zis.mustahiq.update', $mustahiq->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mustahiq->nama_lengkap) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $mustahiq->nik) }}" maxlength="16"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $mustahiq->no_hp) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat <span
                            class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('alamat', $mustahiq->alamat) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span
                            class="text-red-500">*</span></label>
                    <select name="kategori" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        @foreach(['Fakir', 'Miskin', 'Amil', 'Muallaf', 'Riqab', 'Gharimin', 'Fisabilillah', 'Ibnu Sabil'] as $kat)
                            <option value="{{ $kat }}" {{ old('kategori', $mustahiq->kategori) == $kat ? 'selected' : '' }}>
                                {{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="hidden" name="status_aktif" value="0">
                        <input type="checkbox" name="status_aktif" value="1" {{ $mustahiq->status_aktif ? 'checked' : '' }}
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Status Aktif</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-save mr-2"></i>Update
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