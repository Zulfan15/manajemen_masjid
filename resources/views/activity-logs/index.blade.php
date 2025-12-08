@extends('layouts.app')

@section('title', 'Semua Log Aktivitas')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-list-ul text-purple-700 mr-2"></i>Semua Log Aktivitas
            </h1>
            <p class="text-gray-600 mt-2">Pantau semua aktivitas pengguna di sistem (Super Admin Only)</p>
        </div>

        <!-- Statistics Cards -->
        @if(isset($statistics))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total Aktivitas</p>
                        <p class="text-2xl font-bold">{{ number_format($statistics['total_activities'] ?? 0) }}</p>
                    </div>
                    <i class="fas fa-chart-line text-3xl text-blue-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">User Aktif</p>
                        <p class="text-2xl font-bold">{{ number_format($statistics['unique_users'] ?? 0) }}</p>
                    </div>
                    <i class="fas fa-users text-3xl text-green-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Modul Paling Aktif</p>
                        <p class="text-lg font-bold">
                            @if(isset($statistics['by_module']) && $statistics['by_module']->isNotEmpty())
                                {{ ucfirst($statistics['by_module']->keys()->first()) }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-fire text-3xl text-purple-200"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">Aksi Paling Banyak</p>
                        <p class="text-lg font-bold">
                            @if(isset($statistics['by_action']) && $statistics['by_action']->isNotEmpty())
                                {{ ucfirst($statistics['by_action']->keys()->first()) }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-bolt text-3xl text-orange-200"></i>
                </div>
            </div>
        </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Module Filter -->
                    <div>
                        <label for="module" class="block text-sm font-medium text-gray-700 mb-1">
                            Modul
                        </label>
                        <select name="module" id="module" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Semua Modul</option>
                            <option value="keuangan" {{ request('module') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                            <option value="jamaah" {{ request('module') == 'jamaah' ? 'selected' : '' }}>Jamaah</option>
                            <option value="kegiatan" {{ request('module') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="inventaris" {{ request('module') == 'inventaris' ? 'selected' : '' }}>Inventaris</option>
                            <option value="informasi" {{ request('module') == 'informasi' ? 'selected' : '' }}>Informasi</option>
                            <option value="zis" {{ request('module') == 'zis' ? 'selected' : '' }}>ZIS</option>
                            <option value="kurban" {{ request('module') == 'kurban' ? 'selected' : '' }}>Kurban</option>
                            <option value="takmir" {{ request('module') == 'takmir' ? 'selected' : '' }}>Takmir</option>
                            <option value="laporan" {{ request('module') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="users" {{ request('module') == 'users' ? 'selected' : '' }}>Users</option>
                            <option value="authentication" {{ request('module') == 'authentication' ? 'selected' : '' }}>Authentication</option>
                        </select>
                    </div>

                    <!-- Action Filter -->
                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700 mb-1">
                            Aksi
                        </label>
                        <select name="action" id="action" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Semua Aksi</option>
                            <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                            <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                            <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                            <option value="view" {{ request('action') == 'view' ? 'selected' : '' }}>View</option>
                            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                            <option value="promote" {{ request('action') == 'promote' ? 'selected' : '' }}>Promote</option>
                            <option value="demote" {{ request('action') == 'demote' ? 'selected' : '' }}>Demote</option>
                        </select>
                    </div>

                    <!-- User Filter -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                            User ID
                        </label>
                        <input type="number" name="user_id" id="user_id" 
                               value="{{ request('user_id') }}"
                               placeholder="Filter by User ID"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Start Date Filter -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Dari Tanggal
                        </label>
                        <input type="date" name="start_date" id="start_date" 
                               value="{{ request('start_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- End Date Filter -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" id="end_date" 
                               value="{{ request('end_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Search and Actions -->
                <div class="flex gap-2 justify-between items-end">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            Cari Deskripsi
                        </label>
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari dalam deskripsi..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('activity-logs.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Activity Logs Table -->
        @if($logs->isEmpty())
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Tidak ada log aktivitas</p>
                <p class="text-sm mt-2">Belum ada aktivitas yang tercatat atau tidak ada hasil yang sesuai dengan filter.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Modul
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP Address
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->user)
                                        <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->user->username }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $log->module == 'keuangan' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $log->module == 'jamaah' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $log->module == 'kegiatan' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $log->module == 'inventaris' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $log->module == 'informasi' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $log->module == 'zis' ? 'bg-pink-100 text-pink-800' : '' }}
                                        {{ $log->module == 'kurban' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $log->module == 'takmir' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $log->module == 'laporan' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $log->module == 'users' ? 'bg-teal-100 text-teal-800' : '' }}
                                        {{ $log->module == 'authentication' ? 'bg-cyan-100 text-cyan-800' : '' }}">
                                        {{ ucfirst($log->module ?? 'system') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $log->action == 'create' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $log->action == 'update' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $log->action == 'delete' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $log->action == 'view' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $log->action == 'login' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $log->action == 'logout' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $log->action == 'promote' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $log->action == 'demote' ? 'bg-orange-100 text-orange-800' : '' }}">
                                        @if($log->action == 'create')
                                            <i class="fas fa-plus mr-1"></i>
                                        @elseif($log->action == 'update')
                                            <i class="fas fa-edit mr-1"></i>
                                        @elseif($log->action == 'delete')
                                            <i class="fas fa-trash mr-1"></i>
                                        @elseif($log->action == 'view')
                                            <i class="fas fa-eye mr-1"></i>
                                        @elseif($log->action == 'login')
                                            <i class="fas fa-sign-in-alt mr-1"></i>
                                        @elseif($log->action == 'logout')
                                            <i class="fas fa-sign-out-alt mr-1"></i>
                                        @elseif($log->action == 'promote')
                                            <i class="fas fa-arrow-up mr-1"></i>
                                        @elseif($log->action == 'demote')
                                            <i class="fas fa-arrow-down mr-1"></i>
                                        @endif
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $log->description }}</div>
                                    @if($log->properties && is_array($log->properties))
                                        <details class="mt-1">
                                            <summary class="text-xs text-purple-600 cursor-pointer hover:text-purple-800">
                                                Lihat Detail
                                            </summary>
                                            <pre class="text-xs text-gray-600 mt-2 bg-gray-50 p-2 rounded overflow-x-auto">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $log->ip_address ?? '-' }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
