@extends('layouts.app')
@section('title', 'Salurkan Dana ZIS')
@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-hand-holding-heart text-amber-600 mr-2"></i>Salurkan Dana ZIS
                    </h1>
                    <p class="text-gray-600 mt-1">Distribusi dana kepada mustahiq</p>
                </div>
                <a href="{{ route('zis.penyaluran.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            @php
                $saldoTersedia = \App\Models\Transaksi::sum('nominal') - \App\Models\Penyaluran::sum('nominal');
            @endphp

            <!-- Info Saldo -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                <p class="text-green-700">
                    <i class="fas fa-wallet mr-2"></i>
                    <strong>Saldo Tersedia:</strong> Rp {{ number_format($saldoTersedia, 0, ',', '.') }}
                </p>
            </div>

            <form action="{{ route('zis.penyaluran.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Penyaluran</label>
                    <input type="text" name="kode_penyaluran" value="{{ $nomorOtomatis }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mustahiq <span class="text-red-500">*</span></label>
                    <select name="mustahiq_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="">Pilih Mustahiq</option>
                        @foreach($mustahiq as $item)
                            <option value="{{ $item->id }}" {{ old('mustahiq_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_lengkap }} - {{ $item->kategori }}
                            </option>
                        @endforeach
                    </select>
                    @if($mustahiq->count() == 0)
                        <p class="text-sm text-red-500 mt-1">
                            Tidak ada mustahiq aktif. <a href="{{ route('zis.mustahiq.create') }}" class="text-amber-600 hover:underline">Tambah mustahiq baru</a>
                        </p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Bantuan <span class="text-red-500">*</span></label>
                    <select name="jenis_bantuan" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="">Pilih Jenis Bantuan</option>
                        <option value="Uang Tunai" {{ old('jenis_bantuan') == 'Uang Tunai' ? 'selected' : '' }}>Uang Tunai</option>
                        <option value="Sembako" {{ old('jenis_bantuan') == 'Sembako' ? 'selected' : '' }}>Sembako</option>
                        <option value="Pendidikan" {{ old('jenis_bantuan') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                        <option value="Kesehatan" {{ old('jenis_bantuan') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                        <option value="Modal Usaha" {{ old('jenis_bantuan') == 'Modal Usaha' ? 'selected' : '' }}>Modal Usaha</option>
                        <option value="Lainnya" {{ old('jenis_bantuan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nominal <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" name="nominal" value="{{ old('nominal') }}" required min="1000" max="{{ $saldoTersedia }}"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>
                    @error('nominal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penyaluran <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_penyaluran" value="{{ old('tanggal_penyaluran', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">{{ old('keterangan') }}</textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition" 
                        {{ $saldoTersedia < 1000 || $mustahiq->count() == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-hand-holding-heart mr-2"></i>Salurkan Dana
                    </button>
                    <a href="{{ route('zis.penyaluran.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-center transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
