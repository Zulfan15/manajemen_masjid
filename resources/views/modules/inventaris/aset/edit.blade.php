@extends('layouts.app')
@section('title', 'Edit Aset')

@section('content')
<div class="p-6 lg:p-10 bg-[#f6f8f7]">
    <div class="max-w-7xl mx-auto">
        {{-- Breadcrumb --}}
        <div class="flex flex-wrap gap-2 mb-4 text-sm">
            <a class="text-emerald-700 font-medium" href="{{ route('inventaris.aset.index') }}">Daftar Aset</a>
            <span class="text-gray-400 font-medium">/</span>
            <a class="text-emerald-700 font-medium" href="{{ route('inventaris.aset.show', $asset->aset_id) }}">Detail Aset</a>
            <span class="text-gray-400 font-medium">/</span>
            <span class="text-gray-800 font-medium">Edit Aset</span>
        </div>

        {{-- Heading --}}
        <div class="flex flex-wrap justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Edit Aset</h1>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi aset.</p>
            </div>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('inventaris.aset.update', $asset->aset_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    {{-- Nama Barang --}}
                    <label class="flex flex-col">
                        <span class="text-gray-800 text-sm font-medium pb-2">Nama Barang</span>
                        <input name="nama_aset"
                               value="{{ old('nama_aset', $asset->nama_aset) }}"
                               placeholder="Contoh: Karpet Sholat"
                               class="h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 text-sm text-gray-900
                                      focus:outline-0 focus:ring-2 focus:ring-emerald-700/30"/>
                        @error('nama_aset')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Kategori --}}
                    <label class="flex flex-col">
                        <span class="text-gray-800 text-sm font-medium pb-2">Kategori</span>
                        <select name="kategori"
                                class="h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 text-sm text-gray-900
                                       focus:outline-0 focus:ring-2 focus:ring-emerald-700/30">
                            <option value="">Pilih Kategori</option>
                            @foreach(($kategoriOptions ?? []) as $k)
                                <option value="{{ $k }}" @selected(old('kategori', $asset->kategori) == $k)>{{ $k }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Jenis Aset (masih placeholder UI; belum disimpan DB) --}}
                    <div class="flex flex-col">
                        <span class="text-gray-800 text-sm font-medium pb-2">Jenis Aset</span>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4">
                                <input type="radio" name="jenis_aset" value="bergerak"
                                       @checked(old('jenis_aset') == 'bergerak') class="text-emerald-700">
                                <span class="text-sm text-gray-700">Bergerak</span>
                            </label>
                            <label class="flex items-center gap-2 h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4">
                                <input type="radio" name="jenis_aset" value="tidak_bergerak"
                                       @checked(old('jenis_aset') == 'tidak_bergerak') class="text-emerald-700">
                                <span class="text-sm text-gray-700">Tidak Bergerak</span>
                            </label>
                        </div>
                    </div>

                    {{-- Kondisi / Status --}}
                    <label class="flex flex-col">
                        <span class="text-gray-800 text-sm font-medium pb-2">Kondisi</span>
                        <select name="status"
                                class="h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 text-sm text-gray-900
                                       focus:outline-0 focus:ring-2 focus:ring-emerald-700/30">
                            <option value="">Pilih Status</option>
                            <option value="aktif"   @selected(old('status', $asset->status) == 'aktif')>Layak / Aktif</option>
                            <option value="rusak"   @selected(old('status', $asset->status) == 'rusak')>Rusak</option>
                            <option value="hilang"  @selected(old('status', $asset->status) == 'hilang')>Hilang</option>
                            <option value="dibuang" @selected(old('status', $asset->status) == 'dibuang')>Dibuang</option>
                        </select>
                        @error('status')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Tanggal Pembelian --}}
                    <label class="flex flex-col md:col-span-2">
                        <span class="text-gray-800 text-sm font-medium pb-2">Tanggal Pembelian</span>
                        <input type="date" name="tanggal_perolehan"
                               value="{{ old('tanggal_perolehan', $asset->tanggal_perolehan) }}"
                               class="h-12 rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 text-sm text-gray-900
                                      focus:outline-0 focus:ring-2 focus:ring-emerald-700/30"/>
                        @error('tanggal_perolehan')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Lokasi Barang --}}
                    <label class="flex flex-col md:col-span-2">
                        <span class="text-gray-800 text-sm font-medium pb-2">Lokasi Barang</span>
                        <textarea name="lokasi" rows="3" placeholder="Contoh: Gudang utama, Lantai 2"
                                  class="rounded-lg border border-gray-300 bg-[#f6f8f7] px-4 py-3 text-sm text-gray-900
                                         focus:outline-0 focus:ring-2 focus:ring-emerald-700/30">{{ old('lokasi', $asset->lokasi) }}</textarea>
                        @error('lokasi')
                            <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                        @enderror
                    </label>

                    {{-- Upload Foto (belum kita fungsikan di step ini, tapi UI tetap konsisten) --}}
                    <div class="flex flex-col md:col-span-2">
                        <span class="text-gray-800 text-sm font-medium pb-2">Upload Foto Barang</span>
                        <label class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-gray-300 rounded-lg
                                      cursor-pointer bg-[#f6f8f7] hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                <div class="h-10 w-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <p class="mt-3 text-sm text-gray-500">
                                    <span class="font-semibold">Klik untuk upload</span> atau seret dan lepas
                                </p>
                                <p class="text-xs text-gray-400 mt-1">SVG, PNG, JPG atau GIF</p>
                            </div>
                            <input type="file" name="foto" class="hidden">
                        </label>
                    </div>
                </div>

                {{-- Action --}}
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('inventaris.aset.show', $asset->aset_id) }}"
                       class="h-12 rounded-lg bg-gray-100 text-gray-700 px-6 text-sm font-bold flex items-center gap-2 hover:bg-gray-200">
                        Batal
                    </a>

                    <button type="submit"
                            class="h-12 rounded-lg bg-emerald-800 text-white px-6 text-sm font-bold flex items-center gap-2
                                   hover:bg-emerald-800/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-800">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
