<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Warga SIM-LiBas RT 15')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-900': '#0B2F6B',
                        'primary-700': '#0B3A85',
                        'primary-500': '#1C7ED6',
                        'accent-cyan': '#19A7E8',
                        'green-700': '#1B7A3A',
                        'green-500': '#6DB33F',
                        'gold-500': '#F2A900',
                        'gold-700': '#C98A00',
                        'bg-soft': '#F5F8FC',
                        'surface': '#FFFFFF',
                        'border-soft': '#DDE5F0',
                        'text-main': '#0F1F3D',
                        'text-muted': '#5B6B82',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .sim-gradient {
            background: linear-gradient(135deg, #0B3A85 0%, #1C7ED6 55%, #19A7E8 100%);
        }
        .sim-card {
            background-color: #FFFFFF;
            border: 1px solid #DDE5F0;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(11, 58, 133, 0.05);
        }
        .sim-btn-primary {
            background-color: #0B3A85;
            color: #ffffff;
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .sim-btn-primary:hover {
            background-color: #0B2F6B;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-bg-soft text-text-main font-sans antialiased min-h-screen pb-20 md:pb-6">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-border-soft sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('warga.dashboard') }}" class="flex items-center gap-2.5 group">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIM-LiBas" class="h-9 w-auto object-contain">
                <div>
                    <div class="text-base font-extrabold text-primary-900 leading-none">
                        SIM-<span class="text-green-700">LiBas</span>
                    </div>
                    <div class="text-[10px] text-text-muted font-semibold">Portal Warga</div>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-semibold">
                <a href="{{ route('warga.dashboard') }}" class="{{ request()->routeIs('warga.dashboard') ? 'text-primary-700 font-bold' : 'text-text-muted hover:text-primary-700' }}">Beranda</a>
                <a href="{{ route('warga.surat') }}" class="{{ request()->routeIs('warga.surat*') ? 'text-primary-700 font-bold' : 'text-text-muted hover:text-primary-700' }}">Surat Pengantar</a>
                <a href="{{ route('warga.iuran') }}" class="{{ request()->routeIs('warga.iuran*') ? 'text-primary-700 font-bold' : 'text-text-muted hover:text-primary-700' }}">Iuran Bulanan</a>
                <a href="{{ route('warga.pengaduan') }}" class="{{ request()->routeIs('warga.pengaduan*') ? 'text-primary-700 font-bold' : 'text-text-muted hover:text-primary-700' }}">Pengaduan</a>
            </nav>

            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-primary-900 hidden sm:inline">
                    {{ auth()->user()->name }}
                </span>
                <form action="{{ route('citizen.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-semibold px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-50 transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-sm text-green-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bottom Navigation Bar (Mobile-first for Citizens) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-border-soft z-50 flex items-center justify-around h-16 shadow-lg">
        <a href="{{ route('warga.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full text-xs {{ request()->routeIs('warga.dashboard') ? 'text-primary-700 font-bold' : 'text-text-muted' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Beranda
        </a>
        <a href="{{ route('warga.surat') }}" class="flex flex-col items-center justify-center w-full h-full text-xs {{ request()->routeIs('warga.surat*') ? 'text-primary-700 font-bold' : 'text-text-muted' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Surat
        </a>
        <a href="{{ route('warga.iuran') }}" class="flex flex-col items-center justify-center w-full h-full text-xs {{ request()->routeIs('warga.iuran*') ? 'text-primary-700 font-bold' : 'text-text-muted' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Iuran
        </a>
        <a href="{{ route('warga.pengaduan') }}" class="flex flex-col items-center justify-center w-full h-full text-xs {{ request()->routeIs('warga.pengaduan*') ? 'text-primary-700 font-bold' : 'text-text-muted' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            Aduan
        </a>
    </nav>

    @stack('scripts')
</body>
</html>
