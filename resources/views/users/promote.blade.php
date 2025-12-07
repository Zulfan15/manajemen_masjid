@extends('layouts.app')

@section('title', 'Kelola Pengurus ' . ucfirst($module))

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-user-plus text-blue-700 mr-2"></i>Kelola Pengurus {{ ucfirst($module) }}
            </h1>
            <p class="text-gray-600 mt-2">Promosikan jamaah menjadi pengurus atau turunkan pengurus ke jamaah</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Daftar Pengurus Saat Ini -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-users text-green-600 mr-2"></i>Pengurus {{ ucfirst($module) }} Saat Ini
            </h2>
            
            @if($officers->isEmpty())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center text-gray-500">
                    <i class="fas fa-user-slash text-4xl mb-3 text-gray-300"></i>
                    <p>Belum ada pengurus untuk modul ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($officers as $officer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $officer->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $officer->username }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $officer->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            pengurus_{{ $module }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <form action="{{ route('users.demote', ['module' => $module, 'userId' => $officer->id]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menurunkan {{ $officer->name }} ke jamaah?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                                                <i class="fas fa-arrow-down mr-1"></i>Turunkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Promosikan Jamaah -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-user-plus text-blue-600 mr-2"></i>Promosikan Jamaah
            </h2>
            
            @if($promotableUsers->isEmpty())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center text-gray-500">
                    <i class="fas fa-info-circle text-4xl mb-3 text-gray-300"></i>
                    <p>Tidak ada jamaah yang dapat dipromosikan saat ini.</p>
                    <p class="text-sm mt-2">Semua jamaah mungkin sudah menjadi pengurus atau belum ada user dengan role jamaah.</p>
                </div>
            @else
                <form action="{{ route('users.promote', $module) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex items-end gap-4">
                        <div class="flex-1">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Jamaah
                            </label>
                            <select name="user_id" 
                                    id="user_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">-- Pilih Jamaah --</option>
                                @foreach($promotableUsers as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->username }}) - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition font-medium">
                                <i class="fas fa-arrow-up mr-2"></i>Promosikan
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Daftar Jamaah yang Dapat Dipromosikan -->
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($promotableUsers as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->username }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            jamaah
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
