@extends('layouts.app')

@php
    $focusPerson = $person ?? $root;
@endphp

@section('title', $focusPerson->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <a href="{{ route('family-tree.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 mb-5 sm:mb-6 font-medium min-h-[44px] px-2 -ml-2 rounded-lg hover:bg-blue-50 transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Silsilah</span>
    </a>

    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-600 p-5 sm:p-7 text-white shadow-lg mb-6 sm:mb-8">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.22),transparent_45%)]"></div>
        <div class="relative z-10 flex items-center gap-3 sm:gap-4">
            <span class="h-12 w-12 sm:h-14 sm:w-14 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center text-xl">
                <i class="fas fa-user"></i>
            </span>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold leading-tight">{{ $focusPerson->name }}</h1>
                <p class="text-white/90 text-sm sm:text-base mt-1">Detail relasi keluarga per generasi</p>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-gray-100 bg-white/90 shadow-sm p-4 sm:p-6">
        <div class="flex items-center gap-2 mb-4 sm:mb-5">
            <span class="h-8 w-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fas fa-house-user"></i>
            </span>
            <h2 class="text-sm sm:text-base font-bold text-gray-800">Pohon Keluarga Inti</h2>
        </div>

        @include('family-tree.partials.family-unit', [
            'person' => $focusPerson,
            'relationshipLabel' => 'Kepala Keluarga',
            'generationLevel' => 0,
            'showSummaryOpen' => true,
        ])
    </section>
</div>
@endsection
