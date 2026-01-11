@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <h1 class="text-2xl font-bold">Profil Saya</h1>

    <!-- DATA PRIBADI -->
    <div class="bg-white p-6 shadow rounded-lg">
        <p><strong>Nama:</strong> {{ $jamaah->nama_lengkap }}</p>
        <p><strong>No HP:</strong> {{ $jamaah->no_hp ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $jamaah->alamat ?? '-' }}</p>

        <div class="mt-2">
            @foreach($jamaah->categories as $cat)
                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">
                    {{ $cat->nama }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- RIWAYAT KEGIATAN -->
    <div class="bg-white p-6 shadow rounded-lg">
        <h2 class="text-lg font-semibold mb-3">Kegiatan yang Saya Ikuti</h2>

        @if($jamaah->participations->isEmpty())
            <p class="text-gray-500">Belum mengikuti kegiatan.</p>
        @else
            <ul class="space-y-2">
                @foreach($jamaah->participations as $p)
                <li class="border-b pb-2">
                    <p class="font-medium">{{ $p->activity->nama_kegiatan }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $p->activity->tanggal }}
                    </p>
                </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- RIWAYAT DONASI (OPSIONAL) -->
    <div class="bg-white p-6 shadow rounded-lg">
        <h2 class="text-lg font-semibold mb-3">Riwayat Donasi Saya</h2>

        @if($jamaah->donations->isEmpty())
            <p class="text-gray-500">Belum ada donasi.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="text-left">Tanggal</th>
                        <th class="text-left">Jenis</th>
                        <th class="text-left">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jamaah->donations as $d)
                    <tr class="border-b">
                        <td>{{ $d->tanggal }}</td>
                        <td>{{ ucfirst($d->jenis_donasi) }}</td>
                        <td>Rp {{ number_format($d->jumlah) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection
