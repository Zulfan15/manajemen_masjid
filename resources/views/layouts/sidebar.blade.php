<aside class="bg-white w-64 fixed left-0 top-16 bottom-0 overflow-y-auto shadow-lg hidden md:block"
    x-data="{ sidebarOpen: true }">
    <div class="p-4">
        <div class="mb-4">
            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Peran Anda</p>
            @foreach(auth()->user()->roles as $role)
                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mb-1 mr-1">
                    {{ $role->name }}
                </span>
            @endforeach
        </div>

        <hr class="my-4">

        <!-- Navigation Menu -->
        <nav>
            @php
                $modules = [
                    'jamaah' => ['icon' => 'fa-users', 'label' => 'Manajemen Jamaah'],
                    'keuangan' => ['icon' => 'fa-money-bill-wave', 'label' => 'Keuangan'],
                    'kegiatan' => ['icon' => 'fa-calendar-alt', 'label' => 'Kegiatan & Acara'],
                    'zis' => ['icon' => 'fa-hand-holding-heart', 'label' => 'ZIS'],
                    'kurban' => ['icon' => 'fa-sheep', 'label' => 'Kurban'],
                    'inventaris' => ['icon' => 'fa-boxes', 'label' => 'Inventaris'],
                    'takmir' => ['icon' => 'fa-user-tie', 'label' => 'Takmir'],
                    'informasi' => ['icon' => 'fa-bullhorn', 'label' => 'Informasi'],
                    'laporan' => ['icon' => 'fa-chart-bar', 'label' => 'Laporan'],
                ];
            @endphp

            <hr class="my-4">

            {{-- Quick Menu untuk Jamaah --}}
            @if(auth()->user()->hasRole('jamaah'))
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Menu Jamaah</p>

                <a href="{{ route('jamaah.pemasukan') }}"
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('jamaah.pemasukan*') ? 'bg-green-100 text-green-700' : '' }}">
                    <i class="fas fa-hand-holding-heart w-6 text-green-600"></i>
                    <span>Donasi Saya</span>
                </a>

                <hr class="my-4">
            @endif

            <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Modul</p>

            @foreach($modules as $key => $module)
                @if(auth()->user()->canAccessModule($key))
                    @if($key === 'keuangan')
                        <!-- Keuangan Module with Submenu -->
                        <div x-data="{ open: {{ request()->routeIs('keuangan.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('keuangan.*') ? 'bg-green-100 text-green-700' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas {{ $module['icon'] }} w-6"></i>
                                    <span>{{ $module['label'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('keuangan.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('keuangan.index') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-tachometer-alt w-6 text-xs"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('keuangan.pemasukan.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('keuangan.pemasukan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-arrow-up w-6 text-xs text-green-600"></i>
                                    <span>Pemasukan</span>
                                </a>
                                <a href="{{ route('keuangan.pengeluaran.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('keuangan.pengeluaran.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-arrow-down w-6 text-xs text-red-600"></i>
                                    <span>Pengeluaran</span>
                                </a>
                                <a href="{{ route('keuangan.kategori-pengeluaran.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('keuangan.kategori-pengeluaran.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-tags w-6 text-xs text-blue-600"></i>
                                    <span>Kategori</span>
                                </a>
                                <a href="{{ route('laporan.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('laporan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-file-pdf w-6 text-xs text-purple-600"></i>
                                    <span>Laporan</span>
                                </a>
                            </div>
                        </div>
                    @elseif($key === 'kegiatan')
                        <!-- Kegiatan Module with Submenu -->
                        <div
                            x-data="{ open: {{ request()->routeIs('kegiatan.*') || request()->routeIs('jamaah.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('kegiatan.*') || request()->routeIs('jamaah.*') ? 'bg-green-100 text-green-700' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas {{ $module['icon'] }} w-6"></i>
                                    <span>{{ $module['label'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                                @if(auth()->user()->hasRole('jamaah') && !auth()->user()->hasAnyRole(['super_admin', 'admin_kegiatan', 'pengurus_kegiatan']))
                                    {{-- JAMAAH VIEW - Using /jamaah/* routes --}}
                                    <a href="{{ route('jamaah.pengumuman.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('jamaah.pengumuman.*') ? 'bg-green-50 text-green-700' : '' }}">
                                        <i class="fas fa-bullhorn w-6 text-xs text-orange-600"></i>
                                        <span>Pengumuman</span>
                                    </a>

                                    <a href="{{ route('jamaah.kegiatan.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('jamaah.kegiatan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                        <i class="fas fa-calendar-check w-6 text-xs"></i>
                                        <span>Kegiatan</span>
                                    </a>

                                    <a href="{{ route('jamaah.sertifikat.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('jamaah.sertifikat.*') ? 'bg-green-50 text-green-700' : '' }}">
                                        <i class="fas fa-award w-6 text-xs text-amber-600"></i>
                                        <span>Sertifikat Saya</span>
                                    </a>
                                @else
                                    {{-- ADMIN/PENGURUS VIEW - Using /kegiatan/* routes --}}
                                    <a href="{{ route('kegiatan.pengumuman.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('kegiatan.pengumuman.*') ? 'bg-green-50 text-green-700' : '' }}">
                                        <i class="fas fa-bullhorn w-6 text-xs text-orange-600"></i>
                                        <span>Pengumuman</span>
                                    </a>

                                    <a href="{{ route('kegiatan.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('kegiatan.index') || request()->routeIs('kegiatan.create') || request()->routeIs('kegiatan.edit') || request()->routeIs('kegiatan.show') || request()->routeIs('kegiatan.absensi') ? 'bg-green-50 text-green-700' : '' }}">
                                        <i class="fas fa-calendar-check w-6 text-xs"></i>
                                        <span>Kegiatan</span>
                                    </a>

                                    <a href="{{ route('kegiatan.sertifikat.my') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('kegiatan.sertifikat.my') ? 'bg-green-50 text-green-700' : '' }}">
                                        <i class="fas fa-award w-6 text-xs text-amber-600"></i>
                                        <span>Sertifikat Saya</span>
                                    </a>

                                    <!-- Divider -->
                                    <hr class="my-2 border-gray-200">

                                    <!-- Laporan Kegiatan - Admin only -->
                                    <a href="{{ route('kegiatan.laporan.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('kegiatan.laporan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                        <i class="fas fa-file-alt w-6 text-xs text-blue-600"></i>
                                        <span>Laporan Kegiatan</span>
                                    </a>

                                    <!-- Sertifikat (Manajemen) - Admin only -->
                                    <a href="{{ route('kegiatan.sertifikat.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('kegiatan.sertifikat.index') ? 'bg-green-50 text-green-700' : '' }}">
                                        <i class="fas fa-certificate w-6 text-xs text-yellow-600"></i>
                                        <span>Generate Sertifikat</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @elseif($key === 'zis')
                        <!-- ZIS Module with Submenu -->
                        <div x-data="{ open: {{ request()->routeIs('zis.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('zis.*') ? 'bg-green-100 text-green-700' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas {{ $module['icon'] }} w-6"></i>
                                    <span>{{ $module['label'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('zis.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('zis.index') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-tachometer-alt w-6 text-xs"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('zis.mustahiq.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('zis.mustahiq.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-user-friends w-6 text-xs text-purple-600"></i>
                                    <span>Mustahiq</span>
                                </a>
                                <a href="{{ route('zis.muzakki.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('zis.muzakki.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-users w-6 text-xs text-blue-600"></i>
                                    <span>Muzakki</span>
                                </a>
                                <a href="{{ route('zis.transaksi.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('zis.transaksi.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-hand-holding-usd w-6 text-xs text-green-600"></i>
                                    <span>Transaksi</span>
                                </a>
                                <a href="{{ route('zis.penyaluran.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('zis.penyaluran.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-hand-holding-heart w-6 text-xs text-amber-600"></i>
                                    <span>Penyaluran</span>
                                </a>
                                <a href="{{ route('zis.laporan.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('zis.laporan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-file-alt w-6 text-xs text-red-600"></i>
                                    <span>Laporan</span>
                                </a>
                            </div>
                        </div>
                    @elseif($key === 'inventaris')
                        <!-- Inventaris Module with Submenu -->
                        <div x-data="{ open: {{ request()->routeIs('inventaris.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('inventaris.*') ? 'bg-green-100 text-green-700' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas {{ $module['icon'] }} w-6"></i>
                                    <span>{{ $module['label'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('inventaris.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('inventaris.index') || request()->routeIs('inventaris.dashboard') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-tachometer-alt w-6 text-xs"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('inventaris.aset.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('inventaris.aset.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-boxes w-6 text-xs text-blue-600"></i>
                                    <span>Data Aset</span>
                                </a>
                                <a href="{{ route('inventaris.perawatan.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('inventaris.perawatan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-calendar-check w-6 text-xs text-teal-600"></i>
                                    <span>Jadwal Perawatan</span>
                                </a>
                                <a href="{{ route('inventaris.kondisi.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('inventaris.kondisi.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-clipboard-check w-6 text-xs text-orange-600"></i>
                                    <span>Kondisi Barang</span>
                                </a>
                            </div>
                        </div>
                    @elseif($key === 'kurban')
                        <!-- Kurban Module with Submenu -->
                        <div x-data="{ open: {{ request()->routeIs('kurban.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('kurban.*') ? 'bg-green-100 text-green-700' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas {{ $module['icon'] }} w-6"></i>
                                    <span>{{ $module['label'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('kurban.dashboard') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('kurban.dashboard') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-tachometer-alt w-6 text-xs"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('kurban.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('kurban.index') || request()->routeIs('kurban.create') || request()->routeIs('kurban.edit') || request()->routeIs('kurban.show') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-sheep w-6 text-xs text-green-600"></i>
                                    <span>Data Hewan</span>
                                </a>
                            </div>
                        </div>
                    @elseif($key === 'takmir')
                        <!-- Takmir Module with Submenu -->
                        <div x-data="{ open: {{ request()->routeIs('takmir.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('takmir.*') ? 'bg-green-100 text-green-700' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas {{ $module['icon'] }} w-6"></i>
                                    <span>{{ $module['label'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('takmir.dashboard') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('takmir.dashboard') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-tachometer-alt w-6 text-xs"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('takmir.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('takmir.index') || request()->routeIs('takmir.create') || request()->routeIs('takmir.edit') || request()->routeIs('takmir.show') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-users w-6 text-xs"></i>
                                    <span>Data Pengurus</span>
                                </a>
                                <a href="{{ route('takmir.struktur-organisasi') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('takmir.struktur-organisasi') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-sitemap w-6 text-xs"></i>
                                    <span>Struktur Organisasi</span>
                                </a>
                                <a href="{{ route('takmir.verifikasi-jamaah.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('takmir.verifikasi-jamaah.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-user-check w-6 text-xs"></i>
                                    <span>Verifikasi Jamaah</span>
                                </a>
                                <a href="{{ route('takmir.aktivitas.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('takmir.aktivitas.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-clipboard-list w-6 text-xs"></i>
                                    <span>Aktivitas Harian</span>
                                </a>
                                <a href="{{ route('takmir.pemilihan.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('takmir.pemilihan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-vote-yea w-6 text-xs"></i>
                                    <span>Pemilihan</span>
                                </a>
                            </div>
                        </div>
                    @elseif($key === 'informasi')
                        <!-- Informasi Module with Submenu -->
                        <div x-data="{ open: {{ request()->routeIs('informasi.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('informasi.*') ? 'bg-green-100 text-green-700' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas {{ $module['icon'] }} w-6"></i>
                                    <span>{{ $module['label'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('informasi.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('informasi.index') || request()->routeIs('informasi.dashboard') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-tachometer-alt w-6 text-xs"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('informasi.pengumuman.create') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('informasi.pengumuman.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-bullhorn w-6 text-xs text-orange-600"></i>
                                    <span>Pengumuman</span>
                                </a>
                                <a href="{{ route('informasi.berita.create') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('informasi.berita.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-newspaper w-6 text-xs text-blue-600"></i>
                                    <span>Berita</span>
                                </a>
                                <a href="{{ route('informasi.artikel.create') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('informasi.artikel.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-book-open w-6 text-xs text-purple-600"></i>
                                    <span>Artikel Dakwah</span>
                                </a>
                                <a href="{{ route('public.info.index') }}" target="_blank"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition">
                                    <i class="fas fa-external-link-alt w-6 text-xs text-gray-400"></i>
                                    <span>Lihat Halaman Publik</span>
                                </a>
                            </div>
                        </div>
                    @elseif($key === 'laporan')
                        <!-- Laporan Module with Submenu -->
                        <div x-data="{ open: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('laporan.*') ? 'bg-green-100 text-green-700' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas {{ $module['icon'] }} w-6"></i>
                                    <span>{{ $module['label'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('laporan.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('laporan.index') || request()->routeIs('laporan.dashboard') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-tachometer-alt w-6 text-xs"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('laporan.index', ['tab' => 'keuangan']) }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition">
                                    <i class="fas fa-money-bill-wave w-6 text-xs text-green-600"></i>
                                    <span>Laporan Keuangan</span>
                                </a>
                                <a href="{{ route('laporan.index', ['tab' => 'kegiatan']) }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition">
                                    <i class="fas fa-calendar-alt w-6 text-xs text-blue-600"></i>
                                    <span>Laporan Kegiatan</span>
                                </a>
                                <a href="{{ route('laporan.index', ['tab' => 'zis']) }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition">
                                    <i class="fas fa-hand-holding-heart w-6 text-xs text-yellow-600"></i>
                                    <span>Statistik Donasi & ZIS</span>
                                </a>
                                <a href="{{ route('laporan.index', ['tab' => 'jamaah']) }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition">
                                    <i class="fas fa-users w-6 text-xs text-purple-600"></i>
                                    <span>Statistik Jamaah</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route($key . '.index') }}"
                            class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs($key . '.*') ? 'bg-green-100 text-green-700' : '' }}">
                            <i class="fas {{ $module['icon'] }} w-6"></i>
                            <span>{{ $module['label'] }}</span>
                            @if(!auth()->user()->isSuperAdmin())
                                <span class="ml-auto text-xs text-green-600">
                                    <i class="fas fa-edit"></i>
                                </span>
                            @else
                                <span class="ml-auto text-xs text-blue-600">
                                    <i class="fas fa-eye"></i>
                                </span>
                            @endif
                        </a>
                    @endif
                @endif
            @endforeach

            @if(auth()->user()->hasRole('super_admin'))
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Super Admin</p>

                <a href="{{ route('activity-logs.index') }}"
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition">
                    <i class="fas fa-history w-6"></i>
                    <span>Log Aktivitas</span>
                </a>

                <a href="{{ route('users.index') }}"
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition">
                    <i class="fas fa-users-cog w-6"></i>
                    <span>Manajemen User</span>
                </a>
            @endif

            @foreach(['jamaah', 'keuangan', 'kegiatan', 'zis', 'kurban', 'inventaris', 'takmir', 'informasi', 'laporan'] as $module)
                @if(auth()->user()->hasRole("admin_{$module}"))
                    <hr class="my-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Admin {{ ucfirst($module) }}</p>

                    <a href="{{ route('users.promote.show', $module) }}"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition">
                        <i class="fas fa-user-plus w-6"></i>
                        <span>Kelola Pengurus</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>
</aside>