{{-- Partial view for recent activities widget --}}
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-history text-green-600 mr-2"></i>Aktivitas Terbaru
        </h3>
    </div>
    <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
        @forelse($logs as $log)
            <div class="px-6 py-3 hover:bg-gray-50 transition">
                <div class="flex items-start gap-3">
                    <div
                        class="h-8 w-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                        {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : 'S' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 truncate">
                            <span class="font-medium">{{ $log->user ? $log->user->name : 'System' }}</span>
                            <span class="text-gray-500">{{ \Illuminate\Support\Str::limit($log->description, 50) }}</span>
                        </p>
                        <div class="flex items-center gap-2 mt-1">
                            <span
                                class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                {{ $log->module }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-history text-3xl mb-2 text-gray-300"></i>
                <p>Belum ada aktivitas tercatat</p>
            </div>
        @endforelse
    </div>
    @if(auth()->user()->hasRole('super_admin'))
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('activity-logs.index') }}" class="text-sm text-green-600 hover:text-green-800">
                Lihat semua log <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    @endif
</div>