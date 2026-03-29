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
<body class="min-h-full bg-gray-100 text-gray-900 antialiased">
    <header class="border-b border-gray-200 bg-white shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold text-gray-900">
                <span class="text-2xl" aria-hidden="true">🌳</span>
                <span>Silsilah</span>
            </a>
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('family-tree.index') }}" class="text-sm font-medium text-gray-600 hover:text-purple-700">
                    Keluarga
                </a>
                @if(auth()->check())
                    <a href="{{ route('admin.people.index') }}" class="text-sm font-medium text-gray-600 hover:text-purple-700">
                        Admin
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary text-sm px-4 py-2 min-h-0">
                        Masuk
                    </a>
                @endif
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
        @if($errors->any())
            <div class="mb-6">
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
            <div class="mb-6">
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

    <footer class="border-t border-gray-200 bg-white py-8">
        <div class="mx-auto max-w-6xl px-4 text-center text-sm text-gray-500 sm:px-6">
            Silsilah Keluarga — dokumentasi silsilah digital
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
