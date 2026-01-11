@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- PROFIL JAMAAH -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-2">
            {{ $jamaah->nama_lengkap }}
        </h2>

        <p class="text-gray-600">No HP: {{ $jamaah->no_hp ?? '-' }}</p>
        <p class="text-gray-600">Alamat: {{ $jamaah->alamat ?? '-' }}</p>

        <div class="mt-3">
            @foreach($jamaah->categories as $cat)
                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">
                    {{ $cat->nama }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- GRID DONASI & KEGIATAN -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- RIWAYAT DONASI -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3">Riwayat Donasi</h3>

            @if($jamaah->donations->isEmpty())
                <p class="text-gray-500 text-sm">
                    Belum ada donasi
                </p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jamaah->donations as $donasi)
                        <tr class="border-b">
                            <td>{{ $donasi->tanggal }}</td>
                            <td>{{ ucfirst($donasi->jenis_donasi) }}</td>
                            <td>Rp {{ number_format($donasi->jumlah) }}</td>
                            <td>
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $donasi->status == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $donasi->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- RIWAYAT KEGIATAN -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3">Riwayat Kegiatan</h3>

            @if($jamaah->participations->isEmpty())
                <p class="text-gray-500 text-sm">
                    Belum mengikuti kegiatan
                </p>
            @else
                <ul class="space-y-2">
                    @foreach($jamaah->participations as $p)
                    <li class="border-b pb-2">
                        <p class="font-medium">
                            {{ $p->activity->nama_kegiatan }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $p->activity->tanggal }}
                        </p>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

</div>
@endsection
