@extends('layouts.app')

@section('title', 'Detail Jamaah - ' . $jamaah->nama_lengkap)

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('jamaah.index') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center gap-4">
                        @if($jamaah->foto)
                            <img src="{{ Storage::url($jamaah->foto) }}" class="w-16 h-16 rounded-full object-cover shadow">
                        @else
                            <div
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-2xl font-bold shadow">
                                {{ strtoupper(substr($jamaah->nama_lengkap, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $jamaah->nama_lengkap }}</h1>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($jamaah->categories as $cat)
                                    @php
                                        $colors = [
                                            'Umum' => 'bg-blue-100 text-blue-800',
                                            'Pengurus' => 'bg-purple-100 text-purple-800',
                                            'Donatur' => 'bg-green-100 text-green-800',
                                        ];
                                        $color = $colors[$cat->nama] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                        {{ $cat->nama }}
                                    </span>
                                @endforeach
                                @if(!$jamaah->status_aktif)
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Non-Aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('jamaah.edit', $jamaah) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('jamaah.donasi.create', $jamaah) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-hand-holding-usd mr-2"></i>Tambah Donasi
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Data Pribadi --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Info Pribadi --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-id-card text-green-600 mr-2"></i>Data Pribadi
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Nama Lengkap</label>
                            <p class="text-gray-800 font-medium">{{ $jamaah->nama_lengkap }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">No. HP</label>
                            <p class="text-gray-800 font-medium">{{ $jamaah->no_hp ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Tempat, Tanggal Lahir</label>
                            <p class="text-gray-800 font-medium">
                                {{ $jamaah->tempat_lahir ?? '-' }}{{ $jamaah->tanggal_lahir ? ', ' . $jamaah->tanggal_lahir->format('d M Y') : '' }}
                                @if($jamaah->umur)
                                    <span class="text-gray-500">({{ $jamaah->umur }} tahun)</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Jenis Kelamin</label>
                            <p class="text-gray-800 font-medium">{{ $jamaah->jenis_kelamin_label ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Status Pernikahan</label>
                            <p class="text-gray-800 font-medium">{{ $jamaah->status_pernikahan_label ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Pekerjaan</label>
                            <p class="text-gray-800 font-medium">{{ $jamaah->pekerjaan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Pendidikan Terakhir</label>
                            <p class="text-gray-800 font-medium">{{ $jamaah->pendidikan_terakhir ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs text-gray-500 uppercase">Alamat</label>
                            <p class="text-gray-800 font-medium">{{ $jamaah->alamat_lengkap ?: '-' }}</p>
                        </div>
                        @if($jamaah->catatan)
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-500 uppercase">Catatan</label>
                                <p class="text-gray-800">{{ $jamaah->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Riwayat Donasi --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-hand-holding-usd text-green-600 mr-2"></i>Riwayat Donasi
                        </h2>
                        <a href="{{ route('jamaah.donasi.create', $jamaah) }}"
                            class="text-sm text-green-600 hover:text-green-800">
                            <i class="fas fa-plus mr-1"></i>Tambah
                        </a>
                    </div>

                    @if($jamaah->donations->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-hand-holding-heart text-4xl text-gray-300 mb-2"></i>
                            <p>Belum ada riwayat donasi</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-gray-500">
                                        <th class="py-2">Tanggal</th>
                                        <th class="py-2">Jenis</th>
                                        <th class="py-2">Jumlah</th>
                                        <th class="py-2">Status</th>
                                        <th class="py-2 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jamaah->donations as $donasi)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3">{{ $donasi->donation_date?->format('d M Y') ?? '-' }}</td>
                                            <td class="py-3">
                                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                                                    {{ $donasi->type_label }}
                                                </span>
                                            </td>
                                            <td class="py-3 font-medium">Rp {{ number_format($donasi->amount, 0, ',', '.') }}</td>
                                            <td class="py-3">
                                                <span
                                                    class="px-2 py-1 text-xs rounded bg-{{ $donasi->status_color }}-100 text-{{ $donasi->status_color }}-800">
                                                    {{ $donasi->status_label }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-center">
                                                <form action="{{ route('jamaah.donasi.destroy', $donasi) }}" method="POST"
                                                    class="inline" onsubmit="return confirm('Yakin ingin menghapus donasi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Donasi (Confirmed):</span>
                                <span class="text-xl font-bold text-green-600">
                                    Rp {{ number_format($jamaah->total_donasi, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Riwayat Kegiatan --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-check text-green-600 mr-2"></i>Riwayat Keikutsertaan Kegiatan
                    </h2>

                    @if(!isset($kegiatanDiikuti) || $kegiatanDiikuti->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-calendar text-4xl text-gray-300 mb-2"></i>
                            <p>Belum mengikuti kegiatan</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($kegiatanDiikuti as $peserta)
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-calendar-day text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-800">
                                            {{ $peserta->kegiatan->nama ?? 'Kegiatan Tidak Ditemukan' }}
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $peserta->kegiatan->tanggal_mulai?->format('d M Y H:i') ?? '-' }}
                                        </p>
                                        @if($peserta->kegiatan->lokasi)
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $peserta->kegiatan->lokasi }}
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        @if($peserta->status_kehadiran == 'hadir')
                                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Hadir</span>
                                        @elseif($peserta->status_kehadiran == 'tidak_hadir')
                                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Tidak Hadir</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Terdaftar</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Statistik --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-green-600 mr-2"></i>Statistik
                    </h2>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-hand-holding-usd text-green-600 mr-3"></i>
                                <span class="text-sm text-gray-600">Total Donasi</span>
                            </div>
                            <span class="font-bold text-green-600">
                                Rp {{ number_format($jamaah->total_donasi, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-receipt text-blue-600 mr-3"></i>
                                <span class="text-sm text-gray-600">Jumlah Transaksi</span>
                            </div>
                            <span class="font-bold text-blue-600">{{ $jamaah->donations->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-purple-600 mr-3"></i>
                                <span class="text-sm text-gray-600">Kegiatan Diikuti</span>
                            </div>
                            <span
                                class="font-bold text-purple-600">{{ isset($kegiatanDiikuti) ? $kegiatanDiikuti->count() : 0 }}</span>
                        </div>
                    </div>
                </div>

                {{-- Info Akun --}}
                @if($jamaah->user)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle text-green-600 mr-2"></i>Akun Login
                        </h2>

                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Email</label>
                                <p class="text-gray-800">{{ $jamaah->user->email ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Username</label>
                                <p class="text-gray-800">{{ $jamaah->user->username ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Terdaftar Sejak</label>
                                <p class="text-gray-800">{{ $jamaah->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bolt text-green-600 mr-2"></i>Aksi Cepat
                    </h2>

                    <div class="space-y-2">
                        <a href="{{ route('jamaah.edit', $jamaah) }}"
                            class="flex items-center p-3 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 transition">
                            <i class="fas fa-edit mr-3"></i>
                            Edit Data Jamaah
                        </a>
                        <a href="{{ route('jamaah.role.edit', $jamaah) }}"
                            class="flex items-center p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                            <i class="fas fa-user-tag mr-3"></i>
                            Ubah Kategori
                        </a>
                        <a href="{{ route('jamaah.donasi.create', $jamaah) }}"
                            class="flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                            <i class="fas fa-hand-holding-usd mr-3"></i>
                            Catat Donasi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection