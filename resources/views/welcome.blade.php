@extends('layouts.app')

@section('title', 'Beranda - Silsilah Keluarga')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
    <div class="text-center mb-10 sm:mb-12">
        <p class="text-3xl mb-3" aria-hidden="true">🌳</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
            Silsilah Keluarga
        </h1>
        <p class="mt-3 text-sm sm:text-base text-gray-600 leading-relaxed max-w-md mx-auto">
            Halaman ini untuk keluarga saja. Silakan buka silsilah atau masuk jika Anda yang mengurus data.
        </p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <a href="{{ route('family-tree.index') }}" class="btn-primary flex-1 text-center">
                <i class="fas fa-tree text-sm"></i>
                Lihat silsilah
            </a>
            @if(!auth()->check())
                <a href="{{ route('login') }}" class="btn-secondary flex-1 text-center">
                    <i class="fas fa-lock text-sm"></i>
                    Masuk pengurus
                </a>
            @else
                <a href="{{ route('admin.people.index') }}" class="btn-secondary flex-1 text-center">
                    <i class="fas fa-cog text-sm"></i>
                    Kelola data
                </a>
            @endif
        </div>

        <p class="mt-6 text-xs text-gray-500 text-center leading-relaxed">
            Pengeditan data hanya untuk yang punya akses. Jangan bagikan tautan login ke orang di luar keluarga.
        </p>
    </div>
</div>
@endsection
