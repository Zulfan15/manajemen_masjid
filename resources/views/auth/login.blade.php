@extends('layouts.app')

@section('title', 'Login - Manajemen Masjid')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="text-center mb-8">
                <i class="fas fa-mosque text-green-700 text-5xl mb-4"></i>
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Masjid</h1>
                <p class="text-gray-600 mt-2">Silakan login untuk melanjutkan</p>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="username">
                        <i class="fas fa-user mr-2"></i>Username atau Email
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="{{ old('username') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2" for="password">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span class="text-sm text-gray-700">Ingat Saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:underline">
                        Lupa Password?
                    </a>
                </div>

                <button type="submit" 
                        class="w-full bg-green-700 text-white py-3 rounded-lg hover:bg-green-800 transition font-semibold">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-green-600 hover:underline font-semibold">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-600">
            <p class="text-sm">© 2024 Manajemen Masjid. All rights reserved.</p>
        </div>
    </div>
</div>
@endsection
