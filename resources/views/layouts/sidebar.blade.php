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
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-700' : '' }}">
                <i class="fas fa-home w-6"></i>
                <span>Dashboard</span>
            </a>

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
                    
                    @php
                        // --- LOGIKA KHUSUS KELOMPOK B10 ---
                        // Defaultnya ambil route berdasarkan nama key (contoh: jamaah.index)
                        $targetRoute = route($key . '.index');
                        $isActive = request()->routeIs($key . '.*');

                        // Khusus jika kuncinya 'keuangan', kita belokkan ke 'pengeluaran.index'
                        if ($key == 'keuangan') {
                            $targetRoute = route('pengeluaran.index');
                            // Sidebar aktif kalau sedang buka Pengeluaran ATAU Kategori
                            $isActive = request()->routeIs('pengeluaran.*') || request()->routeIs('kategori-pengeluaran.*');
                        }
                    @endphp

                    <a href="{{ $targetRoute }}" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition {{ $isActive ? 'bg-green-100 text-green-700' : '' }}">
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
