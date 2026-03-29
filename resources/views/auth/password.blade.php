@extends('layouts.app')

@section('title', 'Ubah password')

@section('header', 'Ubah password')

@section('content')
<div class="max-w-md mx-auto">
    <div class="card p-6 sm:p-8">
        <p class="text-sm text-gray-600 mb-6">Masukkan password saat ini lalu password baru. Default awal pengguna keluarga biasanya <strong>admin</strong> — sebaiknya diganti.</p>

        <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-semibold text-gray-800 mb-2">Password saat ini</label>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                    class="text-sm sm:text-base min-h-[44px] {{ $errors->has('current_password') ? 'border-red-500' : '' }}">
                @error('current_password')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-800 mb-2">Password baru</label>
                <input type="password" id="password" name="password" required autocomplete="new-password"
                    class="text-sm sm:text-base min-h-[44px] {{ $errors->has('password') ? 'border-red-500' : '' }}">
                @error('password')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-800 mb-2">Ulangi password baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                    class="text-sm sm:text-base min-h-[44px]">
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" class="btn-primary justify-center flex-1">Simpan password</button>
                <a href="{{ route('admin.people.index') }}" class="btn-secondary justify-center flex-1 text-center">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
