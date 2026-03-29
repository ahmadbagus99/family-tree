@extends('layouts.app')

@section('title', 'Silsilah Keluarga')

@section('header', 'Silsilah Keluarga')

@section('content')
<div class="max-w-7xl mx-auto">
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-700 p-6 sm:p-8 lg:p-10 shadow-xl mb-8 sm:mb-12">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.25),transparent_45%)]"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-white/20 text-white mb-4">
                <i class="fas fa-tree"></i>
                <span>Pohon Keluarga</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-2 leading-tight">
                Silsilah Keluarga
            </h1>
            <p class="text-sm sm:text-base text-white/90 max-w-2xl">
                Jelajahi hubungan antar generasi dengan tampilan visual yang lebih rapi, modern, dan nyaman di mobile.
            </p>
        </div>
    </section>

    @if($roots->isEmpty())
        <div class="card border-l-4 border-yellow-500 bg-yellow-50 p-6 sm:p-8">
            <div class="flex items-start gap-4">
                <i class="fas fa-info-circle text-yellow-600 text-xl mt-1"></i>
                <div>
                    <h3 class="font-semibold text-yellow-900 mb-2">Data Kosong</h3>
                    <p class="text-yellow-700 text-sm">
                        Belum ada data keluarga yang tersedia. Silakan hubungi admin untuk menambahkan data.
                    </p>
                </div>
            </div>
        </div>
    @else
        <section class="rounded-3xl border border-gray-100 bg-white/85 backdrop-blur-sm shadow-sm p-4 sm:p-6 lg:p-8">
            <div class="flex items-center gap-3 mb-5 sm:mb-7">
                <span class="h-9 w-9 flex items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                    <i class="fas fa-list"></i>
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-gray-900">Daftar Keluarga</h2>
                    <p class="text-xs sm:text-sm text-gray-500">Pilih keluarga untuk melihat pohon lengkapnya</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($roots as $root)
                    <a href="{{ route('family-tree.family', $root->family_slug) }}" class="group rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                        <div class="flex items-center gap-3">
                            <span class="h-10 w-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                                <i class="fas fa-tree"></i>
                            </span>
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 truncate">{{ $root->name }}</h3>
                                <p class="text-xs text-gray-500">Akar keluarga</p>
                            </div>
                        </div>
                        <div class="mt-3 text-sm text-purple-600 font-semibold inline-flex items-center gap-1">
                            <span>Lihat Keluarga</span>
                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

