@extends('layouts.app')
@section('title', 'Transaksi ZIS')
@section('content')
    <div class="container mx-auto">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-hand-holding-usd text-green-600 mr-2"></i>Transaksi ZIS
                    </h1>
                    <p class="text-gray-600 mt-1">Input penerimaan Zakat, Infak, Sedekah</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('zis.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    @if(!auth()->user()->isSuperAdmin())
                        <a href="{{ route('zis.transaksi.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i>Input Transaksi
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-600 mb-1">Total Transaksi</p>
                    <p class="text-2xl font-bold text-green-700">{{ $transaksi->total() }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-600 mb-1">Zakat</p>
                    <p class="text-xl font-bold text-blue-700">Rp {{ number_format(\App\Models\Transaksi::where('jenis_transaksi', 'Zakat')->sum('nominal'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-sm text-purple-600 mb-1">Infak</p>
                    <p class="text-xl font-bold text-purple-700">Rp {{ number_format(\App\Models\Transaksi::where('jenis_transaksi', 'Infak')->sum('nominal'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-amber-50 p-4 rounded-lg">
                    <p class="text-sm text-amber-600 mb-1">Sedekah</p>
                    <p class="text-xl font-bold text-amber-700">Rp {{ number_format(\App\Models\Transaksi::where('jenis_transaksi', 'Sedekah')->sum('nominal'), 0, ',', '.') }}</p>
                </div>
            </div>

            @if($transaksi->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Muzakki</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaksi as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $item->kode_transaksi }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800">{{ $item->muzakki->nama_lengkap ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $colors = [
                                                'Zakat' => 'bg-blue-100 text-blue-700',
                                                'Infak' => 'bg-purple-100 text-purple-700',
                                                'Sedekah' => 'bg-amber-100 text-amber-700',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 {{ $colors[$item->jenis_transaksi] ?? 'bg-gray-100 text-gray-700' }} text-xs rounded-full">
                                            {{ $item->jenis_transaksi }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if(!auth()->user()->isSuperAdmin())
                                            <form action="{{ route('zis.transaksi.destroy', $item->id) }}" method="POST" 
                                                onsubmit="return confirm('Hapus transaksi ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-sm">View Only</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transaksi->links() }}
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-hand-holding-usd text-5xl mb-3 text-gray-300"></i>
                    <p class="text-lg">Belum ada transaksi ZIS</p>
                    @if(!auth()->user()->isSuperAdmin())
                        <a href="{{ route('zis.transaksi.create') }}" class="text-green-600 hover:underline mt-2 inline-block">
                            Input transaksi pertama
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
