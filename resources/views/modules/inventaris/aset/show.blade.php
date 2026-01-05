@extends('layouts.app')
@section('title', 'Detail Aset')

@section('content')
<div class="p-6">
    {{-- breadcrumb --}}
    <div class="text-sm text-gray-500 mb-2">
        <span class="text-emerald-700">Aset</span> <span class="mx-1">/</span> <span>Detail Aset</span>
    </div>

    {{-- header + actions --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <h1 class="text-3xl font-semibold text-gray-900">Detail Aset</h1>

        <div class="flex gap-3">
            <button type="button"
                class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50">
                <i class="fa-solid fa-print mr-2 text-xs"></i>
                Cetak QR Code
            </button>

            {{-- CRUD nanti, jadi tombolnya tampil dulu --}}
            <a href="{{ route('inventaris.aset.edit', $asset->aset_id) }}"
            class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                <i class="fa-solid fa-pen mr-2 text-xs"></i>
                Edit Aset
            </a>
        </div>
    </div>

    {{-- top section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- FOTO --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="aspect-[4/3] rounded-xl bg-gray-100 overflow-hidden">
                @if($asset->foto_path)
                    <img src="{{ asset('storage/'.$asset->foto_path) }}"
                        alt="Foto {{ $asset->nama_aset }}"
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        Foto Barang
                    </div>
                @endif
            </div>
        </div>

        {{-- INFO KARTU --}}
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Nama Barang --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Nama Barang</div>
                <div class="text-lg font-semibold text-gray-900">{{ $asset->nama_aset }}</div>
            </div>

            {{-- Kategori --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Kategori</div>
                <div class="text-lg font-semibold text-gray-900">{{ $asset->kategori ?? '-' }}</div>
            </div>

            {{-- Jenis Aset (belum ada di tabel aset, placeholder) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Jenis Aset</div>
                <div class="text-lg font-semibold text-gray-900">-</div>
            </div>

            {{-- Tanggal Pembelian (pakai tanggal_perolehan) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Tanggal Pembelian</div>
                <div class="text-lg font-semibold text-gray-900">
                    {{ $asset->tanggal_perolehan ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->translatedFormat('d F Y') : '-' }}
                </div>
            </div>

            {{-- Lokasi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Lokasi</div>
                <div class="text-lg font-semibold text-gray-900">{{ $asset->lokasi ?? '-' }}</div>
            </div>

            {{-- Kondisi (ambil dari kondisi_barang terbaru) --}}
            @php
                $kondisi = strtolower($kondisiTerbaru->kondisi ?? '-');
                $badge = match($kondisi) {
                    'baik','layak' => 'bg-emerald-100 text-emerald-700',
                    'perlu_perbaikan','perbaikan' => 'bg-amber-100 text-amber-700',
                    'rusak' => 'bg-rose-100 text-rose-700',
                    default => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-2">Kondisi</div>
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                    {{ $kondisi !== '-' ? ucfirst(str_replace('_',' ',$kondisi)) : '-' }}
                </span>
            </div>

            {{-- Umur Aset --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-xs text-gray-500 mb-1">Umur Aset</div>
                <div class="text-lg font-semibold text-gray-900">{{ $umurText }}</div>
            </div>

            {{-- QR --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col items-center justify-center">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col items-center justify-center">
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                        {!! QrCode::size(130)->margin(1)->generate($qrCodeText) !!}
                    </div>
                    <div class="mt-3 text-sm font-medium text-gray-700">{{ $qrCodeText }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Perawatan --}}
    <div class="mt-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Riwayat Perawatan</h2>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">TANGGAL</th>
                        <th class="px-4 py-3 text-left">KETERANGAN</th>
                        <th class="px-4 py-3 text-left">BIAYA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatPerawatan as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ $row->tanggal_jadwal ? \Carbon\Carbon::parse($row->tanggal_jadwal)->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $row->note ?? $row->jenis_perawatan ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                -
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                Belum ada riwayat perawatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- tombol hapus (tampil dulu, belum aktif) --}}
        <div class="mt-6">
            <form action="{{ route('inventaris.aset.destroy', $asset->aset_id) }}"
                method="POST"
                onsubmit="return confirm('Yakin ingin menghapus aset ini?')"
                class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700">
                    <i class="fa-solid fa-trash mr-2 text-xs"></i>
                    Hapus Aset
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
