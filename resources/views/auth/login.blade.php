@extends('layouts.guest')

@section('title', 'Login - Admin')

@section('content')
<div class="flex min-h-[calc(100vh-12rem)] items-center justify-center bg-gradient-to-br from-slate-50 to-purple-50/40 py-8 sm:py-12 px-4">
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

                <div class="text-center mt-6">
                    <a href="/" class="text-purple-600 hover:text-purple-800 font-medium text-sm sm:text-base transition-colors">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

