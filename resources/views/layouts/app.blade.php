<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Silsilah Keluarga')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-gray-50 text-gray-900 antialiased">
    <div class="min-h-screen lg:flex">
        <!-- Mobile overlay -->
        <div
            id="sidebar-backdrop"
            class="fixed inset-0 z-40 bg-black/50 opacity-0 pointer-events-none transition-opacity duration-200 lg:hidden"
            aria-hidden="true"
        ></div>

        <aside
            id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-zinc-800 bg-[var(--color-windmill-sidebar)] text-zinc-100 shadow-xl transition-transform duration-200 ease-out lg:static lg:z-auto lg:translate-x-0 -translate-x-full"
            aria-label="Menu utama"
        >
            <div class="flex h-16 shrink-0 items-center gap-2 border-b border-zinc-800 px-5">
                <span class="text-2xl" aria-hidden="true">🌳</span>
                <div class="min-w-0">
                    <a href="{{ url('/') }}" class="block truncate text-lg font-semibold text-white">Silsilah</a>
                    <p class="truncate text-xs text-zinc-500">Dashboard</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-home w-5 text-center text-zinc-400"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('family-tree.index') }}" class="nav-link {{ request()->routeIs('family-tree.*') ? 'active' : '' }}">
                    <i class="fas fa-tree w-5 text-center text-zinc-400"></i>
                    <span>Keluarga</span>
                </a>
                @if(auth()->check())
                    <a href="{{ route('admin.people.index') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="fas fa-table w-5 text-center text-zinc-400"></i>
                        <span>Data anggota</span>
                    </a>
                @endif
            </nav>

            <div class="border-t border-zinc-800 p-4">
                @if(auth()->check())
                    <p class="mb-3 truncate text-xs text-zinc-500">{{ auth()->user()->name }}</p>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-zinc-700 bg-zinc-800/80 px-3 py-2 text-sm font-medium text-zinc-200 transition hover:bg-zinc-800">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-primary w-full justify-center text-sm">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk pengurus
                    </a>
                @endif
            </div>
        </aside>

        <div class="flex min-h-screen min-w-0 flex-1 flex-col lg:min-h-0">
            <header class="sticky top-0 z-30 flex h-14 shrink-0 items-center gap-3 border-b border-gray-200 bg-white px-4 shadow-sm sm:h-16 sm:px-6 lg:px-8">
                <button
                    type="button"
                    id="sidebar-toggle"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 lg:hidden"
                    aria-controls="sidebar"
                    aria-expanded="false"
                    aria-label="Buka menu"
                >
                    <i class="fas fa-bars"></i>
                </button>
                <div class="min-w-0 flex-1">
                    <h1 class="truncate text-base font-semibold text-gray-900 sm:text-lg">
                        @yield('header', 'Silsilah Keluarga')
                    </h1>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                @if($errors->any())
                    <div class="mb-4">
                        <div class="card border-l-4 border-red-500 bg-red-50 p-4 sm:p-6">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-circle mt-1 text-xl text-red-600"></i>
                                <div class="flex-1">
                                    <h3 class="mb-2 font-semibold text-red-900">Ada Kesalahan</h3>
                                    @foreach($errors->all() as $error)
                                        <p class="mb-1 text-sm text-red-700">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4">
                        <div class="card border-l-4 border-green-500 bg-green-50 p-4 sm:p-6 fade-in">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle mt-1 text-xl text-green-600"></i>
                                <p class="font-medium text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="shrink-0 border-t border-gray-200 bg-white px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-xl" aria-hidden="true">🌳</span>
                        <span class="font-medium text-gray-800">Silsilah Keluarga</span>
                    </div>
                    <p class="text-center text-xs text-gray-500 sm:text-right">Dokumentasi silsilah keluarga digital</p>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
