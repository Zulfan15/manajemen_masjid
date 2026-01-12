@extends('layouts.app')
@section('title', 'Input Transaksi ZIS')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-plus-circle text-green-600 mr-2"></i>Input Transaksi ZIS
                    </h1>
                    <p class="text-gray-600 mt-1">Catat penerimaan Zakat/Infak/Sedekah</p>
                </div>
                <a href="{{ route('zis.transaksi.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <form action="{{ route('zis.transaksi.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Transaksi</label>
                    <input type="text" name="kode_transaksi" value="{{ $nomorOtomatis }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Muzakki <span
                            class="text-red-500">*</span></label>
                    <select name="muzakki_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Muzakki</option>
                        @foreach($muzakki as $item)
                            <option value="{{ $item->id }}" {{ old('muzakki_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_lengkap }} - {{ $item->no_hp }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">
                        Belum terdaftar? <a href="{{ route('zis.muzakki.create') }}"
                            class="text-green-600 hover:underline">Tambah muzakki baru</a>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi <span
                            class="text-red-500">*</span></label>
                    <select name="jenis_transaksi" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Jenis</option>
                        <option value="Zakat" {{ old('jenis_transaksi') == 'Zakat' ? 'selected' : '' }}>Zakat</option>
                        <option value="Infak" {{ old('jenis_transaksi') == 'Infak' ? 'selected' : '' }}>Infak</option>
                        <option value="Sedekah" {{ old('jenis_transaksi') == 'Sedekah' ? 'selected' : '' }}>Sedekah</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nominal <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" name="nominal" value="{{ old('nominal') }}" required min="1000"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    @error('nominal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi <span
                            class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ old('keterangan') }}</textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-save mr-2"></i>Simpan Transaksi
                    </button>
                    <a href="{{ route('zis.transaksi.index') }}"
                        class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-center transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection