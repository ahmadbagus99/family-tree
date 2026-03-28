<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Silsilah Keluarga')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .family-tree {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn-primary {
            @apply inline-flex items-center justify-center gap-2 font-semibold text-white rounded-lg px-5 py-2.5 min-h-[44px] border-2 border-blue-800/40 shadow-md hover:shadow-lg hover:brightness-105 active:scale-[0.99] transition-all duration-200;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .btn-secondary {
            @apply inline-flex items-center justify-center gap-2 font-semibold text-gray-900 rounded-lg px-5 py-2.5 min-h-[44px] bg-white border-2 border-gray-300 shadow-sm hover:bg-gray-50 hover:border-gray-400 active:scale-[0.99] transition-all duration-200;
        }

        .btn-danger {
            @apply inline-flex items-center justify-center gap-2 font-semibold text-white rounded-lg px-5 py-2.5 min-h-[44px] border-2 border-red-900/25 shadow-md bg-red-600 hover:bg-red-700 active:scale-[0.99] transition-all duration-200 w-full sm:w-auto;
        }

        /* Form: pembatas jelas antar blok */
        .form-stack {
            @apply rounded-xl border-2 border-gray-200 bg-gray-50/40 p-4 sm:p-6 shadow-sm;
        }

        .form-divider {
            @apply my-6 border-t-2 border-dashed border-gray-200;
        }

        .btn-icon {
            @apply inline-flex items-center justify-center w-10 h-10 rounded-lg border-2 border-gray-300 bg-white text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-400 active:scale-95 transition-all;
        }

        .card {
            @apply bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .person-card {
            @apply text-white p-4 sm:p-5 text-center fade-in rounded-2xl;
            min-width: 180px;
            max-width: 240px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .person-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 24px rgba(59, 130, 246, 0.3);
        }

        .connector-line {
            height: 2rem;
            width: 3px;
            background: linear-gradient(to bottom, #bfdbfe, #dbeafe);
            margin: 0 auto;
            border-radius: 2px;
        }

        .tree-generation {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            align-items: center;
            width: 100%;
        }

        .children-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem sm:gap-2rem;
            justify-content: center;
            margin-top: 1.5rem sm:margin-top-2rem;
            padding-top: 1.5rem sm:padding-top-2rem;
            border-top: 2px solid #f0f0f0;
        }

        .nav-link {
            @apply text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2;
        }

        .nav-link.active {
            @apply text-blue-600 bg-blue-50;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        select,
        textarea {
            @apply w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400 transition-colors duration-200;
        }

        /* File input biasa (bukan overlay di label custom) */
        input[type="file"]:not([class*="opacity-0"]) {
            @apply w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg bg-white text-sm;
        }

        .badge {
            @apply inline-block px-3 py-1 rounded-full text-xs font-semibold;
        }

        .badge-primary {
            @apply badge bg-blue-100 text-blue-800;
        }

        .badge-success {
            @apply badge bg-green-100 text-green-800;
        }

        .badge-info {
            @apply badge bg-purple-100 text-purple-800;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-40 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2 group">
                    <div class="text-2xl sm:text-3xl">🌳</div>
                    <span class="text-lg sm:text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Silsilah
                    </span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('family-tree.index') }}" class="nav-link {{ request()->routeIs('family-tree.*') ? 'active' : '' }}">
                        <i class="fas fa-tree"></i>
                        <span>Keluarga</span>
                    </a>
                    @if(auth()->check())
                        <a href="{{ route('admin.people.index') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            <span>Admin</span>
                        </a>
                    @endif
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    @if(auth()->check())
                        <span class="text-sm text-gray-600 hidden sm:block">
                            {{ auth()->user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-secondary text-sm sm:text-base px-3 sm:px-5">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="hidden sm:inline">Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary text-sm sm:text-base">
                            <i class="fas fa-sign-in-alt"></i>
                            <span class="hidden sm:inline">Masuk</span>
                        </a>
                    @endif

                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-menu-btn" class="md:hidden text-gray-600 hover:text-blue-600 text-xl">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-100">
                <a href="{{ route('family-tree.index') }}" class="block nav-link {{ request()->routeIs('family-tree.*') ? 'active' : '' }}">
                    <i class="fas fa-tree"></i>
                    <span>Keluarga</span>
                </a>
                @if(auth()->check())
                    <a href="{{ route('admin.people.index') }}" class="block nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Admin</span>
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-6 sm:py-8 lg:py-10 min-h-screen">
        <!-- Alerts -->
        @if($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="card border-l-4 border-red-500 bg-red-50 p-4 sm:p-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl mt-1"></i>
                        <div class="flex-1">
                            <h3 class="font-semibold text-red-900 mb-2">Ada Kesalahan</h3>
                            @foreach($errors->all() as $error)
                                <p class="text-red-700 text-sm mb-1">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="card border-l-4 border-green-500 bg-green-50 p-4 sm:p-6 fade-in">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                        <div>
                            <p class="text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-12 sm:mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center space-x-2">
                    <div class="text-2xl">🌳</div>
                    <span class="font-semibold text-gray-900">Silsilah Keluarga</span>
                </div>
                <p class="text-gray-600 text-sm text-center sm:text-left">
                    Dokumentasi silsilah keluarga digital
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Close menu when link is clicked
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', function() {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

