@extends('layouts.app')

@section('title', 'Daftar Aset')

@section('content')
<div class="p-6">
    {{-- Header + tombol tambah --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Daftar Aset</h1>
            <p class="text-sm text-gray-500">
                Kelola data aset inventaris masjid.
            </p>
        </div>

        <a href="{{ route('inventaris.aset.create') }}"
           class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium shadow-sm hover:bg-emerald-700">
            <i class="fa-solid fa-plus mr-2 text-xs"></i>
            Tambah Aset Baru
        </a>
    </div>

    {{-- Card utama --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        {{-- Search + filter bar --}}
        <div class="px-4 py-3 border-b border-gray-100">
            <form method="GET"
                  action="{{ route('inventaris.aset.index') }}"
                  class="flex flex-col md:flex-row gap-3 md:items-center">

                {{-- Search --}}
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama barang..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    >
                </div>

                {{-- Filter kategori --}}
                <select name="kategori"
                        onchange="this.form.submit()"
                        class="w-full md:w-44 text-sm border border-gray-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Kategori</option>
                    @isset($kategoriOptions)
                        @foreach($kategoriOptions as $kategori)
                            <option value="{{ $kategori }}" @selected(request('kategori') == $kategori)>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    @endisset
                </select>

                {{-- Filter jenis aset (sementara dummy, nanti bisa dihubungkan ke kolom/relasi) --}}
                <select name="jenis_aset"
                        class="w-full md:w-44 text-sm border border-gray-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Jenis Aset</option>
                    {{-- isi nanti kalau sudah ada kolom jenis_aset --}}
                </select>

                {{-- Filter kondisi (pakai nilai yang kamu pakai di tabel kondisi_barang) --}}
                <select name="status"
                        onchange="this.form.submit()"
                        class="w-full md:w-44 text-sm border rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Status</option>
                    <option value="aktif"  @selected(request('status') == 'aktif')>Aktif</option>
                    <option value="hilang" @selected(request('status') == 'hilang')>Hilang</option>
                    <option value="dibuang" @selected(request('status') == 'dibuang')>Dibuang</option>
                    <option value="rusak"  @selected(request('status') == 'rusak')>Rusak</option>
                </select>

                <a href="{{ route('inventaris.aset.index') }}"
                class="w-full md:w-auto text-center text-sm border border-gray-200 rounded-lg py-2 px-3 bg-white text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold text-gray-500">
                        <th class="px-4 py-3 text-left">Foto Barang</th>
                        <th class="px-4 py-3 text-left">Nama Barang</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Jenis Aset</th>
                        <th class="px-4 py-3 text-left">Lokasi</th>
                        <th class="px-4 py-3 text-left">Kondisi</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Umur Barang</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">QR Code</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($assets as $asset)
                        @php
                            // Hitung umur dari tanggal_perolehan (kalau ada)
                            $umurTahun = !empty($asset->tanggal_perolehan)
                                ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->diffInYears(now())
                                : null;

                            // Ambil kondisi (kalau kamu nanti sudah join dengan kondisi_barang,
                            // ganti ke $asset->kondisi_terbaru misalnya)
                            $kondisi = strtolower($asset->kondisi ?? $asset->status ?? '-');

                            $badgeClass = match($kondisi) {
                                'baik', 'layak' => 'bg-emerald-100 text-emerald-700',
                                'perlu_perbaikan', 'perbaikan' => 'bg-amber-100 text-amber-700',
                                'rusak' => 'bg-rose-100 text-rose-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50">
                            {{-- FOTO BARANG: sementara placeholder --}}
                            <td class="px-4 py-3">
                                <div class="h-10 w-16 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] text-gray-400">
                                    Foto
                                </div>
                            </td>

                            {{-- NAMA BARANG --}}
                            <td class="px-4 py-3 text-gray-800 font-medium">
                                {{ $asset->nama_aset }}
                            </td>

                            {{-- KATEGORI --}}
                            <td class="px-4 py-3 text-gray-700">
                                {{ $asset->kategori ?? '-' }}
                            </td>

                            {{-- JENIS ASET (sementara kosong / placeholder) --}}
                            <td class="px-4 py-3 text-gray-700">
                                {{ $asset->jenis_aset ?? '-' }}
                            </td>

                            {{-- LOKASI --}}
                            <td class="px-4 py-3 text-gray-700">
                                {{ $asset->lokasi ?? '-' }}
                            </td>

                            {{-- KONDISI --}}
                            <td class="px-4 py-3">
                                <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold {{ $badgeClass }}">
                                    {{ $kondisi !== '-' ? ucfirst(str_replace('_', ' ', $kondisi)) : '-' }}
                                </span>
                            </td>

                            {{-- UMUR BARANG --}}
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                @if (!is_null($umurTahun))
                                    {{ $umurTahun }} Tahun
                                @else
                                    -
                                @endif
                            </td>

                            {{-- QR CODE --}}
                            <td class="px-4 py-3 text-center">
                                {{-- nanti bisa diarahkan ke route untuk generate / show QR --}}
                                <a href="#"
                                   class="inline-flex items-center justify-center h-7 w-7 rounded-full border border-gray-200 text-gray-500 hover:bg-gray-100">
                                    <i class="fa-solid fa-qrcode text-xs"></i>
                                </a>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- DETAIL --}}
                                    <a href="{{ route('inventaris.aset.show', $asset->aset_id) }}"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200"
                                    title="Detail">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('inventaris.aset.edit', $asset->aset_id) }}"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-100"
                                    title="Edit">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>

                                    {{-- DELETE --}}
                                    <form action="{{ route('inventaris.aset.destroy', $asset->aset_id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus aset: {{ addslashes($asset->nama_aset) }} ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-rose-50 text-rose-600 hover:bg-rose-100"
                                                title="Hapus">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada data aset.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: info jumlah & pagination --}}
        <div class="px-4 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-xs text-gray-500">
            <div>
                @if ($assets->total() > 0)
                    Showing
                    <span class="font-semibold">{{ $assets->firstItem() }}</span>
                    to
                    <span class="font-semibold">{{ $assets->lastItem() }}</span>
                    of
                    <span class="font-semibold">{{ $assets->total() }}</span>
                    entries
                @else
                    Showing 0 entries
                @endif
            </div>
            <div>
                {{ $assets->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
