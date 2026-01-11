@extends('layouts.app')

@section('title', 'Kelola Role - ' . $user->name)

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-user-tag text-indigo-700 mr-2"></i>Kelola Role
                    </h1>
                    <p class="text-gray-600 mt-2">Manage roles untuk: <strong>{{ $user->name }}</strong></p>
                </div>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- User Info Card -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-4 mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-indigo-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @forelse($user->roles as $role)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if(str_starts_with($role->name, 'super_admin')) bg-red-100 text-red-800
                                @elseif(str_starts_with($role->name, 'admin_')) bg-blue-100 text-blue-800
                                @elseif(str_starts_with($role->name, 'pengurus_')) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                <i class="fas fa-shield-alt mr-1"></i>
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </span>
                        @empty
                            <span class="text-gray-500 italic">Belum memiliki role</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Role Form -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-plus-circle text-green-600 mr-2"></i>Tambah Role
            </h2>
            <form action="{{ route('users.roles.assign', $user->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="role_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Role
                    </label>
                    <div class="flex gap-3">
                        <select name="role_name" id="role_name" required
                            class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="">-- Pilih Role --</option>
                            @foreach($allRoles as $role)
                                @if(!$user->hasRole($role->name))
                                    <option value="{{ $role->name }}">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-check mr-2"></i>Assign
                        </button>
                    </div>
                </div>
                @error('role_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </form>
        </div>

        <!-- Current Roles List -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-list text-indigo-600 mr-2"></i>Role Saat Ini
            </h2>
            
            @if($user->roles->count() > 0)
                <div class="space-y-3">
                    @foreach($user->roles as $role)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                    @if(str_starts_with($role->name, 'super_admin')) bg-red-100
                                    @elseif(str_starts_with($role->name, 'admin_')) bg-blue-100
                                    @elseif(str_starts_with($role->name, 'pengurus_')) bg-green-100
                                    @else bg-gray-200
                                    @endif">
                                    <i class="fas fa-shield-alt
                                        @if(str_starts_with($role->name, 'super_admin')) text-red-600
                                        @elseif(str_starts_with($role->name, 'admin_')) text-blue-600
                                        @elseif(str_starts_with($role->name, 'pengurus_')) text-green-600
                                        @else text-gray-600
                                        @endif"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ $role->permissions->count() }} permissions</p>
                                </div>
                            </div>
                            <form action="{{ route('users.roles.remove', ['userId' => $user->id, 'roleName' => $role->name]) }}" method="POST" 
                                onsubmit="return confirm('Hapus role {{ $role->name }} dari {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">User belum memiliki role</p>
                    <p class="text-sm text-gray-400 mt-1">Gunakan form di atas untuk menambahkan role</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        });
    </script>
@endif
@endsection
