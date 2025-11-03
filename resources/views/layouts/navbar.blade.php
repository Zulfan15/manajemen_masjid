<nav class="bg-green-700 text-white fixed w-full top-0 z-50 shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo and Brand -->
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <i class="fas fa-mosque text-2xl"></i>
                    <span class="font-bold text-lg">Manajemen Masjid</span>
                </a>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 hover:bg-green-600 px-3 py-2 rounded">
                        <i class="fas fa-user-circle text-xl"></i>
                        <span>{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('my-logs') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-history mr-2"></i>Log Aktivitas
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
