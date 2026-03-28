@extends('layouts.app')

@section('title', 'Login - Admin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50 py-8 sm:py-12 px-4">
    <div class="w-full max-w-md">
        <div class="card overflow-hidden border-0 ring-1 ring-gray-100/80 shadow-xl">
            <div class="hero-gradient px-6 py-7 sm:px-8 sm:py-9 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
                <div class="relative z-10 text-center">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center text-2xl mx-auto mb-3">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold">Login Admin</h2>
                    <p class="text-white/90 text-sm sm:text-base mt-1">Masuk untuk kelola data keluarga</p>
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gradient-to-b from-white to-gray-50/60">
                <form action="{{ route('login.post') }}" method="POST" class="form-stack space-y-4 sm:space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                        <input id="username" name="username" type="text" autocomplete="username" required
                            class="text-sm sm:text-base min-h-[44px] {{ $errors->has('username') ? 'border-red-500' : '' }}"
                            placeholder="masukkan username"
                            value="{{ old('username') }}">
                        @error('username')
                            <p class="text-red-600 text-xs sm:text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="text-sm sm:text-base min-h-[44px] {{ $errors->has('password') ? 'border-red-500' : '' }}"
                            placeholder="masukkan password">
                        @error('password')
                            <p class="text-red-600 text-xs sm:text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full justify-center min-h-[48px] sm:min-h-0 text-base mt-2">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </button>
                </form>

                <div class="mt-6 p-4 sm:p-5 bg-blue-50 border border-blue-200 rounded-xl">
                    <h3 class="font-semibold text-blue-900 mb-2 text-sm sm:text-base">Default Credentials</h3>
                    <div class="space-y-2 text-xs sm:text-sm text-blue-900">
                        <div class="flex items-center gap-2">
                            <span class="w-20 text-blue-700">Username</span>
                            <span class="font-mono bg-white px-2 py-1 rounded border border-blue-200">admin</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-20 text-blue-700">Password</span>
                            <span class="font-mono bg-white px-2 py-1 rounded border border-blue-200">admin</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="/" class="text-blue-600 hover:text-blue-800 font-medium text-sm sm:text-base transition-colors">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

