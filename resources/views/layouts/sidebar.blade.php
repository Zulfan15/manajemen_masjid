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
                                <a href="{{ route('keuangan.pemasukan.index') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('keuangan.pemasukan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-arrow-up w-6 text-xs"></i>
                                    <span>Pemasukan</span>
                                </a>
                                <a href="{{ route('keuangan.pengeluaran.index') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('keuangan.pengeluaran.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-arrow-down w-6 text-xs"></i>
                                    <span>Pengeluaran</span>
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
