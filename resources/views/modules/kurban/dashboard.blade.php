@extends('layouts.app')

@section('title', 'Dashboard Kurban')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-3"></i>
                    Dashboard Manajemen Kurban
                </h1>
                <p class="text-gray-600 mt-2">Monitor perkembangan kurban, peserta, dan distribusi secara real-time.</p>
            </div>
            <a href="{{ route('kurban.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow transition duration-200 flex items-center">
                <i class="fas fa-list mr-2"></i> Lihat Semua Kurban
            </a>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
            <form method="GET" action="{{ route('kurban.dashboard') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-1/4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                    <div class="relative">
                        <select name="tahun"
                            class="appearance-none w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-700 cursor-pointer"
                            onchange="this.form.submit()">
                            @for($y = now()->year; $y >= now()->year - 5; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <div class="relative">
                        <select name="status"
                            class="appearance-none w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-700 cursor-pointer"
                            onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="disiapkan" {{ request('status') == 'disiapkan' ? 'selected' : '' }}>Disiapkan
                            </option>
                            <option value="siap_sembelih" {{ request('status') == 'siap_sembelih' ? 'selected' : '' }}>Siap
                                Sembelih</option>
                            <option value="disembelih" {{ request('status') == 'disembelih' ? 'selected' : '' }}>Disembelih
                            </option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                @if(request('status') || request('tahun') != now()->year)
                    <div class="pb-0.5">
                        <a href="{{ route('kurban.dashboard') }}"
                            class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200">
                            <i class="fas fa-redo mr-2"></i> Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Card: Total Kurban -->
            <div
                class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start z-10 relative">
                    <div>
                        <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Kurban</h5>
                        <div class="text-3xl font-bold text-gray-800">{{ $statistics['total_kurban'] }}</div>
                        <p class="text-xs text-blue-600 font-medium mt-2">Ekor Hewan</p>
                    </div>
                    <div
                        class="p-3 bg-blue-100 rounded-full text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-sheep text-xl"></i>
                    </div>
                </div>
                <div
                    class="absolute -bottom-4 -right-4 text-9xl text-gray-100 opacity-50 z-0 rotate-12 group-hover:scale-110 transition duration-500">
                    <i class="fas fa-sheep"></i>
                </div>
            </div>

            <!-- Card: Total Peserta -->
            <div
                class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start z-10 relative">
                    <div>
                        <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Peserta</h5>
                        <div class="text-3xl font-bold text-gray-800">{{ $statistics['total_peserta'] }}</div>
                        <p class="text-xs text-green-600 font-medium mt-2">Shohibul Qurban</p>
                    </div>
                    <div
                        class="p-3 bg-green-100 rounded-full text-green-600 group-hover:bg-green-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <div
                    class="absolute -bottom-4 -right-4 text-9xl text-gray-100 opacity-50 z-0 rotate-12 group-hover:scale-110 transition duration-500">
                    <i class="fas fa-users"></i>
                </div>
            </div>

            <!-- Card: Total Pembayaran -->
            <div
                class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start z-10 relative">
                    <div>
                        <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Dana</h5>
                        <div class="text-2xl font-bold text-gray-800 truncate"
                            title="Rp {{ number_format($statistics['total_pembayaran'], 0, ',', '.') }}">
                            Rp {{ number_format($statistics['total_pembayaran'], 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-yellow-600 font-medium mt-2">Pembayaran Masuk</p>
                    </div>
                    <div
                        class="p-3 bg-yellow-100 rounded-full text-yellow-600 group-hover:bg-yellow-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                </div>
                <div
                    class="absolute -bottom-4 -right-4 text-9xl text-gray-100 opacity-50 z-0 rotate-12 group-hover:scale-110 transition duration-500">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>

            <!-- Card: Distribusi Daging -->
            <div
                class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500 relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start z-10 relative">
                    <div>
                        <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Distribusi</h5>
                        <div class="text-3xl font-bold text-gray-800">
                            {{ number_format($statistics['total_daging_distribusi'], 2) }} <span
                                class="text-lg text-gray-500 font-normal">kg</span></div>
                        <p class="text-xs text-purple-600 font-medium mt-2">Total Daging</p>
                    </div>
                    <div
                        class="p-3 bg-purple-100 rounded-full text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition duration-300">
                        <i class="fas fa-box-open text-xl"></i>
                    </div>
                </div>
                <div
                    class="absolute -bottom-4 -right-4 text-9xl text-gray-100 opacity-50 z-0 rotate-12 group-hover:scale-110 transition duration-500">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>
        </div>

        <!-- Status Distribution & Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Status Stats -->
            <div class="lg:col-span-3 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h6 class="text-lg font-bold text-gray-800 mb-6 flex items-center pb-4 border-b">
                    <i class="fas fa-chart-pie text-indigo-600 mr-2"></i> Distribusi Status Kurban
                </h6>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Status Item -->
                    <div class="text-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition duration-200">
                        <div class="text-3xl font-bold text-blue-600 mb-1">{{ $statistics['kurban_disiapkan'] }}</div>
                        <div class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Disiapkan</div>
                        <div class="mt-2 text-xs text-blue-500 bg-blue-100 inline-block px-2 py-1 rounded">Open Registration
                        </div>
                    </div>

                    <div class="text-center p-4 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition duration-200">
                        <div class="text-3xl font-bold text-yellow-600 mb-1">{{ $statistics['kurban_siap_sembelih'] }}</div>
                        <div class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Siap Sembelih</div>
                        <div class="mt-2 text-xs text-yellow-600 bg-yellow-100 inline-block px-2 py-1 rounded">Kuota Penuh
                        </div>
                    </div>

                    <div class="text-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition duration-200">
                        <div class="text-3xl font-bold text-purple-600 mb-1">{{ $statistics['kurban_disembelih'] }}</div>
                        <div class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Disembelih</div>
                        <div class="mt-2 text-xs text-purple-600 bg-purple-100 inline-block px-2 py-1 rounded">Proses Daging
                        </div>
                    </div>

                    <div class="text-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition duration-200">
                        <div class="text-3xl font-bold text-green-600 mb-1">{{ $statistics['kurban_selesai'] }}</div>
                        <div class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Selesai</div>
                        <div class="mt-2 text-xs text-green-600 bg-green-100 inline-block px-2 py-1 rounded">Terdistribusi
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress By Animal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h6 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-tasks text-teal-600 mr-2"></i> Progress Kuota per Hewan Kurban
                </h6>
                @if($kurbans->count() > 0)
                    <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border shadow-sm">
                        Menampilkan {{ $kurbans->count() }} data terbaru
                    </span>
                @endif
            </div>

            <div class="p-6">
                @if($kurbans->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($kurbans as $kurban)
                            @php
                                $sisaKuota = $kurban->getSisaKuota();
                                $kuotaTerisi = $kurban->getCurrentKuotaUsage();
                                $persentase = $kurban->getKuotaPercentage();

                                $progressColor = 'bg-blue-500';
                                $textColor = 'text-blue-600';
                                $bgColor = 'bg-blue-50';

                                if ($persentase >= 100) {
                                    $progressColor = 'bg-red-500';
                                    $textColor = 'text-red-600';
                                    $bgColor = 'bg-red-50';
                                } elseif ($persentase >= 75) {
                                    $progressColor = 'bg-yellow-500';
                                    $textColor = 'text-yellow-600';
                                    $bgColor = 'bg-yellow-50';
                                } else {
                                    $progressColor = 'bg-green-500';
                                    $textColor = 'text-green-600';
                                    $bgColor = 'bg-green-50';
                                }

                                $statusBadge = match ($kurban->status) {
                                    'disiapkan' => 'bg-blue-100 text-blue-800',
                                    'siap_sembelih' => 'bg-yellow-100 text-yellow-800',
                                    'disembelih' => 'bg-purple-100 text-purple-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };

                                $statusLabel = match ($kurban->status) {
                                    'disiapkan' => 'Disiapkan',
                                    'siap_sembelih' => 'Siap Sembelih',
                                    'disembelih' => 'Disembelih',
                                    'selesai' => 'Selesai',
                                    default => $kurban->status,
                                };
                            @endphp

                            <div
                                class="border rounded-xl p-5 hover:shadow-md transition duration-300 bg-white relative overflow-hidden">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="p-3 {{ $bgColor }} rounded-lg {{ $textColor }}">
                                            @if($kurban->jenis_hewan == 'sapi')
                                                <i class="fas fa-hippo text-xl"></i> <!-- Icon Sapi/Besar -->
                                            @else
                                                <i class="fas fa-regular fa-chess-knight text-xl"></i> <!-- Placeholder Icon Kambing -->
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-gray-800 text-lg">{{ $kurban->nomor_kurban }}</h5>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded {{ $statusBadge }}">
                                                    {{ $statusLabel }}
                                                </span>
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                                    {{ ucfirst($kurban->jenis_hewan) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-gray-800">{{ $persentase }}%</div>
                                        <span class="text-xs text-gray-500">Terisi</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                                    <div class="{{ $progressColor }} h-3 rounded-full transition-all duration-1000 ease-in-out"
                                        style="width: {{ $persentase }}%"></div>
                                </div>

                                <div class="flex justify-between items-center text-sm mb-4">
                                    <span class="font-medium {{ $sisaKuota == 0 ? 'text-red-600' : 'text-gray-600' }}">
                                        @if($sisaKuota == 0)
                                            <i class="fas fa-ban mr-1"></i> Penuh ({{ $kuotaTerisi }}/{{ $kurban->max_kuota }})
                                        @else
                                            <i class="fas fa-user-plus mr-1"></i> Tersedia {{ $sisaKuota }} Slot
                                        @endif
                                    </span>
                                    <span class="text-gray-500">
                                        <i class="fas fa-tag mr-1 text-gray-400"></i>
                                        Rp {{ number_format($kurban->harga_per_bagian, 0, ',', '.') }}/pax
                                    </span>
                                </div>

                                <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                                    <div class="flex -space-x-2">
                                        {{-- Placeholder Avatars for Participants --}}
                                        @foreach($kurban->pesertaKurbans->take(3) as $peserta)
                                            <div class="w-8 h-8 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs font-bold text-gray-600"
                                                title="{{ $peserta->nama_peserta }}">
                                                {{ substr($peserta->nama_peserta, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($kurban->pesertaKurbans->count() > 3)
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-xs text-gray-500 font-medium">
                                                +{{ $kurban->pesertaKurbans->count() - 3 }}
                                            </div>
                                        @endif
                                        @if($kurban->pesertaKurbans->count() == 0)
                                            <span class="text-xs text-gray-400 italic ml-2">Belum ada peserta</span>
                                        @endif
                                    </div>

                                    <div class="flex space-x-2">
                                        @if($kurban->status != 'disiapkan')
                                            <a href="{{ route('kurban.report.download', $kurban) }}" target="_blank"
                                                class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Download Laporan PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('kurban.show', $kurban) }}"
                                            class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                                            Detail <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-gray-200">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                            <i class="fas fa-sheep text-3xl text-gray-300"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Kurban</h3>
                        <p class="text-gray-500 max-w-sm mx-auto mb-6">Mulai dengan menambahkan data hewan kurban baru untuk
                            tahun {{ $tahun }}.</p>
                        <a href="{{ route('kurban.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm">
                            <i class="fas fa-plus mr-2"></i> Tambah Kurban Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection