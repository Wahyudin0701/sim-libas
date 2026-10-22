<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIM-LiBas - Sistem Informasi Manajemen RT 15')</title>
    <meta name="description" content="Portal Terpadu Layanan Warga Kampung Digital Pinang Masak Lestari, RT 15 Pematang Sulur, Kota Jambi">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN fallback for instant loading & styling) -->
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
        html {
            scroll-behavior: smooth;
        }
        #mobile-menu {
            background-color: #FFFFFF !important;
        }
        .sim-gradient {
            background: linear-gradient(135deg, #0B3A85 0%, #1C7ED6 55%, #19A7E8 100%);
        }
        .sim-green-gradient {
            background: linear-gradient(135deg, #1B7A3A 0%, #6DB33F 100%);
        }
        .sim-card {
            background-color: #FFFFFF;
            border: 1px solid #DDE5F0;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(11, 58, 133, 0.06);
        }
        .sim-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #0B3A85;
            color: #FFFFFF;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease-in-out;
        }
        .sim-btn-primary:hover {
            background-color: #0B2F6B;
            color: #FFFFFF;
        }
        .sim-btn-gold {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #F2A900;
            color: #0B2F6B;
            font-weight: 700;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease-in-out;
        }
        .sim-btn-gold:hover {
            background-color: #C98A00;
            color: #071F47;
        }
        :root {
            --navbar-height: 4rem;
        }
        .hero-fullscreen {
            min-height: calc(100vh - var(--navbar-height));
            min-height: calc(100dvh - var(--navbar-height));
        }
    </style>
    @stack('styles')
</head>
<body class="bg-bg-soft text-text-main font-sans antialiased flex flex-col min-h-screen">

    <!-- Header & Navigasi -->
    <header class="bg-white border-b border-border-soft sticky top-0 z-50 shadow-sm h-16 box-border">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-10 h-full flex items-center justify-between gap-3 lg:gap-6">
            <!-- Brand Logo -->
            <a href="{{ route('public.home') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0 min-w-0">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIM-LiBas RT 15" class="h-9 sm:h-10 lg:h-11 w-auto object-contain shrink-0">
                <div class="truncate">
                    <div class="text-base sm:text-lg lg:text-xl font-bold tracking-tight text-primary-900 leading-none">
                        SIM-<span class="text-green-700">LiBas</span>
                    </div>
                    <div class="text-[10px] sm:text-xs text-text-muted font-medium mt-0.5 truncate">
                        RT 15 Pematang Sulur, Kota Jambi
                    </div>
                </div>
            </a>

            <!-- Navigasi Menu Desktop -->
            <nav class="hidden lg:flex items-center gap-3.5 xl:gap-6 text-xs xl:text-sm font-semibold text-text-muted">
                <a href="{{ route('public.home') }}" class="hover:text-primary-700 transition {{ request()->routeIs('public.home') ? 'text-primary-700 font-bold' : '' }}">Beranda</a>
                <a href="{{ route('public.struktur-pengurus') }}" class="hover:text-primary-700 transition {{ request()->routeIs('public.struktur-pengurus') ? 'text-primary-700 font-bold' : '' }}">Struktur Pengurus</a>
                <a href="{{ route('public.umkm') }}" class="hover:text-primary-700 transition {{ request()->routeIs('public.umkm*') ? 'text-primary-700 font-bold' : '' }}">Katalog UMKM</a>
                <a href="{{ route('public.bank-sampah') }}" class="hover:text-primary-700 transition {{ request()->routeIs('public.bank-sampah') ? 'text-primary-700 font-bold' : '' }}">Bank Sampah</a>
                
                <!-- Dropdown Layanan Warga -->
                <div class="relative group" id="layanan-nav-wrapper">
                    <button type="button" id="layanan-nav-btn" class="hover:text-primary-700 transition flex items-center gap-1 focus:outline-none py-2 font-semibold">
                        <span>Layanan Warga</span>
                        <svg class="w-3.5 h-3.5 text-text-muted transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <!-- Dropdown Panel Menu -->
                    <div id="layanan-nav-menu" class="hidden group-hover:block absolute left-0 top-full pt-2 w-72 z-50">
                        <div class="bg-white rounded-2xl shadow-xl border border-border-soft p-2.5">
                            <div class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-text-muted border-b border-border-soft mb-1">
                                <span>6 Layanan Warga</span>
                            </div>
                            @if(isset($citizenServices))
                                @foreach($citizenServices as $svc)
                                    <a href="{{ route('public.layanan.detail', $svc['slug']) }}" class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-blue-50/80 transition group/item">
                                        <span class="text-xs font-semibold text-primary-900 group-hover/item:text-primary-700 transition">
                                            {{ $svc['nama'] }}
                                        </span>
                                        <svg class="w-3.5 h-3.5 text-slate-300 group-hover/item:text-primary-700 group-hover/item:translate-x-0.5 transition shrink-0 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endforeach
                            @endif
                            <div class="mt-1 pt-1.5 border-t border-border-soft">
                                <a href="{{ route('public.layanan.index') }}" class="flex items-center justify-center gap-1.5 text-center text-xs font-bold text-primary-700 hover:bg-blue-50 py-1.5 rounded-lg transition group/all">
                                    <span>Buka Direktori Lengkap</span>
                                    <svg class="w-3.5 h-3.5 text-primary-700 group-hover/all:translate-x-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('public.contact') }}" class="hover:text-primary-700 transition {{ request()->routeIs('public.contact') ? 'text-primary-700 font-bold' : '' }}">Kontak RT</a>
            </nav>

            <!-- Tombol Masuk Portal Tunggal dengan Dropdown (Desktop) -->
            <div class="hidden md:flex items-center shrink-0 relative" id="auth-dropdown-wrapper">
                @auth
                    @if(auth()->user()->isWarga())
                        <a href="{{ route('warga.dashboard') }}" class="bg-primary-700 hover:bg-primary-900 text-white font-semibold text-xs xl:text-sm px-4 py-2 xl:px-4.5 xl:py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Portal Warga</span>
                        </a>
                    @else
                        <a href="/admin" class="bg-primary-700 hover:bg-primary-900 text-white font-semibold text-xs xl:text-sm px-4 py-2 xl:px-4.5 xl:py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                            <span>Dashboard Pengurus</span>
                        </a>
                    @endif
                @else
                    <button type="button" id="login-dropdown-btn" class="bg-primary-700 hover:bg-primary-900 text-white font-semibold text-xs xl:text-sm px-4 py-2 xl:px-4.5 xl:py-2.5 rounded-xl transition shadow-sm flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-primary-500 group">
                        <span>Masuk Portal</span>
                        <svg id="dropdown-chevron" class="w-3.5 h-3.5 text-blue-200 group-hover:text-white transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Panel (Simple Button Choices) -->
                    <div id="login-dropdown-menu" class="hidden absolute right-0 top-full mt-2 w-60 bg-white rounded-2xl shadow-xl border border-border-soft p-3 z-50 flex flex-col gap-2.5">
                        <div class="px-1 text-[11px] font-bold uppercase tracking-wider text-text-muted">
                            Pilih Akses Masuk
                        </div>

                        <!-- 1. Tombol Warga (Oranye / Gold) -->
                        <a href="{{ route('citizen.login') }}" class="w-full text-center bg-gold-500 hover:bg-gold-700 text-primary-900 font-bold text-sm py-2.5 px-4 rounded-xl transition shadow-sm hover:shadow">
                            Masuk Portal Warga
                        </a>

                        <!-- 2. Tombol Pengurus (Abu-Abu) -->
                        <a href="/admin/login" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-primary-900 font-semibold text-sm py-2.5 px-4 rounded-xl transition border border-slate-200">
                            Masuk Pengurus RT
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile / Tablet) -->
            <div class="flex items-center gap-2 md:hidden">
                <button id="mobile-menu-btn" type="button" aria-label="Buka Menu" class="p-2 rounded-xl text-text-muted hover:text-primary-900 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
                    <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 right-0 w-full bg-white border-b border-border-soft px-6 pt-4 pb-8 space-y-4 shadow-2xl max-h-[calc(100dvh-4rem)] overflow-y-auto z-50">
            <nav class="flex flex-col space-y-1 text-sm font-semibold text-text-muted">
                <a href="{{ route('public.home') }}" class="px-3 py-2.5 rounded-xl hover:bg-blue-50 hover:text-primary-700 transition flex items-center justify-between {{ request()->routeIs('public.home') ? 'bg-blue-50 text-primary-700 font-bold' : '' }}">
                    <span>Beranda</span>
                </a>
                <a href="{{ route('public.struktur-pengurus') }}" class="px-3 py-2.5 rounded-xl hover:bg-blue-50 hover:text-primary-700 transition flex items-center justify-between {{ request()->routeIs('public.struktur-pengurus') ? 'bg-blue-50 text-primary-700 font-bold' : '' }}">
                    <span>Struktur Pengurus</span>
                </a>
                <a href="{{ route('public.umkm') }}" class="px-3 py-2.5 rounded-xl hover:bg-blue-50 hover:text-primary-700 transition flex items-center justify-between {{ request()->routeIs('public.umkm*') ? 'bg-blue-50 text-primary-700 font-bold' : '' }}">
                    <span>Katalog UMKM</span>
                </a>
                <a href="{{ route('public.bank-sampah') }}" class="px-3 py-2.5 rounded-xl hover:bg-blue-50 hover:text-primary-700 transition flex items-center justify-between {{ request()->routeIs('public.bank-sampah') ? 'bg-blue-50 text-primary-700 font-bold' : '' }}">
                    <span>Bank Sampah</span>
                </a>
                <!-- Layanan Warga Submenu in Mobile -->
                <div class="py-1">
                    <div class="px-3 py-2 text-xs font-bold text-primary-900 uppercase tracking-wider">
                        <span>Layanan Warga RT 15</span>
                    </div>
                    <div class="grid grid-cols-1 gap-1 pl-2">
                        @if(isset($citizenServices))
                            @foreach($citizenServices as $svc)
                                <a href="{{ route('public.layanan.detail', $svc['slug']) }}" class="px-3 py-2 rounded-lg text-xs font-medium text-text-muted hover:bg-blue-50 hover:text-primary-700 transition flex items-center justify-between group/mob">
                                    <span>{{ $svc['nama'] }}</span>
                                    <svg class="w-3.5 h-3.5 text-gray-400 group-hover/mob:text-primary-700 group-hover/mob:translate-x-0.5 transition shrink-0 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <a href="{{ route('public.contact') }}" class="px-3 py-2.5 rounded-xl hover:bg-blue-50 hover:text-primary-700 transition flex items-center justify-between {{ request()->routeIs('public.contact') ? 'bg-blue-50 text-primary-700 font-bold' : '' }}">
                    <span>Kontak RT</span>
                </a>
            </nav>

            <div class="pt-3 border-t border-border-soft flex flex-col gap-2.5">
                @auth
                    @if(auth()->user()->isWarga())
                        <a href="{{ route('warga.dashboard') }}" class="w-full text-center bg-primary-700 hover:bg-primary-900 text-white font-semibold text-sm px-5 py-3 rounded-xl transition shadow-sm">
                            Buka Portal Warga
                        </a>
                    @else
                        <a href="/admin" class="w-full text-center bg-primary-700 hover:bg-primary-900 text-white font-semibold text-sm px-5 py-3 rounded-xl transition shadow-sm">
                            Dashboard Pengurus
                        </a>
                    @endif
                @else
                    <a href="{{ route('citizen.login') }}" class="w-full text-center bg-gold-500 hover:bg-gold-700 text-primary-900 font-bold text-sm px-5 py-3 rounded-xl transition shadow-sm">
                        Masuk Portal Warga
                    </a>
                    <a href="/admin/login" class="w-full text-center border border-border-soft text-text-muted hover:text-primary-900 font-semibold text-sm px-5 py-2.5 rounded-xl transition hover:bg-gray-50">
                        Masuk Pengurus RT
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Kampung Digital -->
    <footer class="bg-primary-900 text-white pt-16 pb-12 mt-20 border-t-4 border-gold-500" id="kontak">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Info RT -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIM-LiBas" class="h-10 w-auto object-contain">
                        <div class="text-2xl font-bold">SIM-LiBas RT 15</div>
                    </div>
                    <p class="text-gray-300 text-sm max-w-md leading-relaxed mb-4">
                        Sistem Informasi Manajemen Rukun Tetangga 15, Kampung Digital Pinang Masak Lestari, Kelurahan Pematang Sulur, Kecamatan Telanaipura, Kota Jambi.
                    </p>
                    <div class="text-xs text-gold-500 font-semibold tracking-wide">
                        "Satu Portal, Banyak Layanan, Manfaat Nyata untuk Warga."
                    </div>
                </div>

                <!-- Tautan Cepat -->
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4">Layanan Cepat</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ route('public.struktur-pengurus') }}" class="hover:text-gold-500 transition">Struktur Pengurus RT 15</a></li>
                        <li><a href="{{ route('citizen.login') }}" class="hover:text-gold-500 transition">Pengajuan Surat Pengantar</a></li>
                        <li><a href="{{ route('citizen.login') }}" class="hover:text-gold-500 transition">Bayar Iuran Bulanan</a></li>
                        <li><a href="{{ route('public.bank-sampah') }}" class="hover:text-gold-500 transition">Bank Sampah Pinang Masak</a></li>
                        <li><a href="{{ route('public.umkm') }}" class="hover:text-gold-500 transition">Katalog Produk UMKM</a></li>
                        <li><a href="{{ route('citizen.login') }}" class="hover:text-gold-500 transition">Pengaduan & Aspirasi</a></li>
                    </ul>
                </div>

                <!-- Kontak Pengurus -->
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-gray-300 mb-4">Sekretariat RT</h4>
                    <p class="text-sm text-gray-300 mb-2">
                        Jl. Pinang Masak, RT 15<br>
                        Kel. Pematang Sulur, Kec. Telanaipura<br>
                        Kota Jambi, Provinsi Jambi 36129
                    </p>
                    <p class="text-sm text-gold-500 font-semibold mt-4">
                        📞 Telepon: 0812-7400-1515
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('public.contact') }}" class="inline-flex items-center gap-1.5 text-xs text-blue-200 hover:text-white font-medium transition">
                            <span>Buka Halaman Kontak & Sekretariat</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400">
                <div>
                    &copy; {{ date('Y') }} SIM-LiBas RT 15 Pematang Sulur. Dibuat sesuai UU No. 27/2022 tentang Pelindungan Data Pribadi.
                </div>
                <div class="mt-4 sm:mt-0">
                    Kota Jambi Cerdas
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const hamburger = document.getElementById('hamburger-icon');
            const close = document.getElementById('close-icon');

            if (btn && menu) {
                btn.addEventListener('click', function () {
                    const isOpen = !menu.classList.contains('hidden');
                    if (isOpen) {
                        menu.classList.add('hidden');
                        hamburger.classList.remove('hidden');
                        close.classList.add('hidden');
                    } else {
                        menu.classList.remove('hidden');
                        hamburger.classList.add('hidden');
                        close.classList.remove('hidden');
                    }
                });
            }

            // Dropdown Masuk Portal (Desktop)
            const authWrapper = document.getElementById('auth-dropdown-wrapper');
            const authBtn = document.getElementById('login-dropdown-btn');
            const authMenu = document.getElementById('login-dropdown-menu');
            const authChevron = document.getElementById('dropdown-chevron');

            if (authBtn && authMenu) {
                authBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isHidden = authMenu.classList.contains('hidden');
                    if (isHidden) {
                        authMenu.classList.remove('hidden');
                        if (authChevron) authChevron.classList.add('rotate-180');
                    } else {
                        authMenu.classList.add('hidden');
                        if (authChevron) authChevron.classList.remove('rotate-180');
                    }
                });

                document.addEventListener('click', function (e) {
                    if (authWrapper && !authWrapper.contains(e.target)) {
                        authMenu.classList.add('hidden');
                        if (authChevron) authChevron.classList.remove('rotate-180');
                    }
                });
            }

            // Dropdown Layanan Warga (Desktop Click Support)
            const layananWrapper = document.getElementById('layanan-nav-wrapper');
            const layananBtn = document.getElementById('layanan-nav-btn');
            const layananMenu = document.getElementById('layanan-nav-menu');

            if (layananBtn && layananMenu) {
                layananBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    layananMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (layananWrapper && !layananWrapper.contains(e.target)) {
                        layananMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
