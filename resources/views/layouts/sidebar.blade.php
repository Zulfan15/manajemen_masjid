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
            <!-- Dashboard - Semua User -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-700' : '' }}">
                <i class="fas fa-home w-6"></i>
                <span>Dashboard</span>
            </a>

            <!-- ========================================== -->
            <!-- MENU UNTUK JAMAAH/USER BIASA -->
            <!-- ========================================== -->
            @if(!auth()->user()->hasAnyRole(['super_admin', 'admin_jamaah', 'admin_keuangan', 'admin_kegiatan', 'admin_zis', 'admin_kurban', 'admin_inventaris', 'admin_takmir', 'admin_informasi', 'admin_laporan']))
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Menu Jamaah</p>
                
                <a href="{{ route('jamaah.pemasukan') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('jamaah.pemasukan*') ? 'bg-green-100 text-green-700' : '' }}">
                    <i class="fas fa-wallet w-6"></i>
                    <span>Pemasukan Saya</span>
                </a>
            @endif

            <!-- ========================================== -->
            <!-- MENU MODUL UNTUK ADMIN -->
            <!-- ========================================== -->
            @php
                $modules = [
                    'jamaah' => ['icon' => 'fa-users', 'label' => 'Manajemen Jamaah', 'route' => 'jamaah-admin.index'],
                    'keuangan' => ['icon' => 'fa-money-bill-wave', 'label' => 'Keuangan', 'route' => 'keuangan.index'],
                    'kegiatan' => ['icon' => 'fa-calendar-alt', 'label' => 'Kegiatan & Acara', 'route' => 'kegiatan.index'],
                    'zis' => ['icon' => 'fa-hand-holding-heart', 'label' => 'ZIS', 'route' => 'zis.index'],
                    'kurban' => ['icon' => 'fa-sheep', 'label' => 'Kurban', 'route' => 'kurban.index'],
                    'inventaris' => ['icon' => 'fa-boxes', 'label' => 'Inventaris', 'route' => 'inventaris.index'],
                    'takmir' => ['icon' => 'fa-user-tie', 'label' => 'Takmir', 'route' => 'takmir.index'],
                    'informasi' => ['icon' => 'fa-bullhorn', 'label' => 'Informasi', 'route' => 'informasi.index'],
                    'laporan' => ['icon' => 'fa-chart-bar', 'label' => 'Laporan', 'route' => 'laporan-umum.index'],
                ];

                $hasAnyModule = false;
                foreach($modules as $key => $module) {
                    if(auth()->user()->canAccessModule($key)) {
                        $hasAnyModule = true;
                        break;
                    }
                }
            @endphp

            @if($hasAnyModule)
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Modul</p>

                @foreach($modules as $key => $module)
                    @if(auth()->user()->canAccessModule($key))
                        <a href="{{ route($module['route']) }}" 
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs($key . '.*') || request()->routeIs('jamaah-admin.*') && $key == 'jamaah' || request()->routeIs('laporan-umum.*') && $key == 'laporan' ? 'bg-green-100 text-green-700' : '' }}">
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

                        {{-- SUBMENU KHUSUS KEUANGAN --}}
                        @if($key == 'keuangan' && auth()->user()->canAccessModule('keuangan'))
                            <div class="ml-4 border-l-2 border-green-200">
                                <!-- Kelola Pemasukan -->
                                <a href="{{ route('pemasukan.index') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('pemasukan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-money-bill-wave w-6 text-xs"></i>
                                    <span>Kelola Pemasukan</span>
                                </a>
                                
                                <!-- ✅ HISTORY DATA TERHAPUS - MENU BARU -->
                                <a href="{{ route('history.index') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('history.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-history w-6 text-xs"></i>
                                    <span>History Data Terhapus</span>
                                </a>
                                
                                <!-- Laporan Keuangan -->
                                <a href="{{ route('laporan.index') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('laporan.*') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-file-invoice w-6 text-xs"></i>
                                    <span>Laporan Keuangan</span>
                                </a>
                            </div>
                        @endif
                    @endif
                @endforeach
            @endif

            <!-- ========================================== -->
            <!-- MENU KHUSUS SUPER ADMIN -->
            <!-- ========================================== -->
            @if(auth()->user()->hasRole('super_admin'))
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Super Admin</p>
                
                <a href="{{ route('users.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('users.index') ? 'bg-green-100 text-green-700' : '' }}">
                    <i class="fas fa-users-cog w-6"></i>
                    <span>Manajemen User</span>
                </a>

                <a href="{{ route('activity-logs.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('activity-logs.*') ? 'bg-green-100 text-green-700' : '' }}">
                    <i class="fas fa-history w-6"></i>
                    <span>Log Aktivitas</span>
                </a>
            @endif

            <!-- ========================================== -->
            <!-- MENU KELOLA PENGURUS (MODULE ADMIN) -->
            <!-- ========================================== -->
            @php
                $adminModules = [];
                foreach(['jamaah', 'keuangan', 'kegiatan', 'zis', 'kurban', 'inventaris', 'takmir', 'informasi', 'laporan'] as $module) {
                    if(auth()->user()->hasRole("admin_{$module}")) {
                        $adminModules[] = $module;
                    }
                }
            @endphp

            @if(count($adminModules) > 0)
                <hr class="my-4">
                <p class="text-xs text-gray-500 uppercase font-semibold px-4 mb-2">Kelola Pengurus</p>

                @foreach($adminModules as $module)
                    <a href="{{ route('users.promote.show', $module) }}" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->is('users/promote/'.$module) ? 'bg-green-100 text-green-700' : '' }}">
                        <i class="fas fa-user-plus w-6"></i>
                        <span>Pengurus {{ ucfirst($module) }}</span>
                    </a>
                @endforeach
            @endif

            <!-- ========================================== -->
            <!-- MENU AKTIVITAS SAYA - SEMUA USER -->
            <!-- ========================================== -->
            <hr class="my-4">
            <a href="{{ route('my-logs') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('my-logs') ? 'bg-green-100 text-green-700' : '' }}">
                <i class="fas fa-user-clock w-6"></i>
                <span>Aktivitas Saya</span>
            </a>
        </nav>
    </div>
</aside>