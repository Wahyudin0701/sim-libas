<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Warga SIM-LiBas RT 15')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Vite Assets (CSS & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
@php
    $isHome = request()->routeIs('warga.dashboard');
    $currentMenuTitle = trim($__env->yieldContent('page_title'));
    if (empty($currentMenuTitle)) {
        $currentMenuTitle = match(true) {
            request()->routeIs('warga.profile*') => 'Profil Saya',
            request()->routeIs('warga.kartu-keluarga*') => 'Kartu Keluarga',
            request()->routeIs('warga.surat*') => 'Surat Pengantar',
            request()->routeIs('warga.iuran*') => 'Iuran Bulanan',
            request()->routeIs('warga.pengaduan*') => 'Pengaduan Warga',
            default => 'Portal Warga',
        };
    }

    // Profil Warga untuk User Menu Topbar
    $user = auth()->user();
    $userName = $user?->name ?? 'Warga';
    $words = preg_split('/\s+/', trim($userName));
    $initials = '';
    if (count($words) >= 2) {
        $initials = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
    } else {
        $initials = strtoupper(mb_substr($userName, 0, 2));
    }

    $resident = $user?->resident;
    $userRoleLabel = match(true) {
        $resident?->is_kepala_keluarga => 'KEPALA KELUARGA',
        $resident !== null => 'WARGA RT 15',
        default => 'WARGA AKTIF',
    };

    $unreadNotifCount = $user ? \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count() : 0;
@endphp
<body class="bg-bg-soft text-text-main font-sans antialiased min-h-screen pb-20 md:pb-6">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-border-soft sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <!-- Brand / Logo (Selalu tampil di Desktop; di Mobile hanya tampil di Beranda) -->
            <a href="{{ route('warga.dashboard') }}" class="{{ $isHome ? 'flex' : 'hidden md:flex' }} items-center gap-2.5 group">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIM-LiBas" class="h-9 w-auto object-contain">
                <div>
                    <div class="text-base font-extrabold text-primary-900 leading-none">
                        SIM-<span class="text-green-700">LiBas</span>
                    </div>
                    <div class="text-[10px] text-text-muted font-semibold">Portal Warga</div>
                </div>
            </a>

            <!-- Judul Menu Khusus Mobile (Tampil di Mobile saat berada di menu selain Beranda) -->
            @if(!$isHome)
                <div class="flex md:hidden items-center gap-2">
                    @if(request()->routeIs('warga.kartu-keluarga*') || request()->routeIs('warga.profile*'))
                        <a href="{{ route('warga.dashboard') }}" class="p-1 -ml-1 text-primary-900 hover:text-primary-700 active:scale-95 transition" title="Kembali ke Beranda" aria-label="Kembali ke Beranda">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif
                    <h1 class="text-base font-extrabold text-primary-900 tracking-tight">
                        {{ $currentMenuTitle }}
                    </h1>
                </div>
            @endif

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-semibold">
                <a href="{{ route('warga.dashboard') }}" class="{{ request()->routeIs('warga.dashboard') ? 'text-primary-700 font-bold' : 'text-text-muted hover:text-primary-700' }}">Beranda</a>
                <a href="{{ route('warga.surat') }}" class="{{ request()->routeIs('warga.surat*') ? 'text-primary-700 font-bold' : 'text-text-muted hover:text-primary-700' }}">Surat Pengantar</a>
                <a href="{{ route('warga.iuran') }}" class="{{ request()->routeIs('warga.iuran*') ? 'text-primary-700 font-bold' : 'text-text-muted hover:text-primary-700' }}">Iuran Bulanan</a>
                <a href="{{ route('warga.pengaduan') }}" class="{{ request()->routeIs('warga.pengaduan*') ? 'text-primary-700 font-bold' : 'text-text-muted hover:text-primary-700' }}">Pengaduan</a>
            </nav>

            <!-- Header Actions: Notifikasi & Profil Dropdown -->
            <div class="flex items-center gap-1.5 sm:gap-3">
                <!-- 1. Tombol & Dropdown Notifikasi Sistem -->
                <div class="relative" id="notif-menu-wrapper">
                    <button type="button" 
                            id="notif-menu-button" 
                            aria-expanded="false" 
                            aria-haspopup="true"
                            class="relative p-2 text-slate-500 hover:text-primary-700 hover:bg-slate-100 rounded-xl transition cursor-pointer focus:outline-none"
                            title="Notifikasi Saya"
                            aria-label="Notifikasi Saya">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notif-indicator" class="{{ $unreadNotifCount > 0 ? '' : 'hidden' }} absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    <!-- Dropdown Notifikasi Panel -->
                    <div id="notif-menu-dropdown" 
                         class="hidden absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-100 p-3 z-50 transition-all duration-150 transform opacity-0 scale-95 origin-top-right">
                        <div class="flex items-center justify-between pb-2.5 mb-2 border-b border-border-soft">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-primary-900 uppercase tracking-wider">Notifikasi</span>
                                <span id="notif-badge-count" class="{{ $unreadNotifCount > 0 ? '' : 'hidden' }} px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-700">
                                    {{ $unreadNotifCount }} baru
                                </span>
                            </div>
                            <button type="button" 
                                    id="btn-mark-all-read"
                                    class="text-[11px] font-semibold text-primary-700 hover:text-primary-900 hover:underline cursor-pointer">
                                Tandai Semua Dibaca
                            </button>
                        </div>
                        <div id="notif-items-list" class="max-h-72 overflow-y-auto space-y-2 no-scrollbar text-xs">
                            <p class="text-center text-text-muted py-4">Memuat notifikasi...</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Profile Dropdown (Format seperti Dashboard Pengurus) -->
                <div class="relative" id="user-menu-wrapper">
                    <button type="button" 
                            id="user-menu-button" 
                            aria-expanded="false" 
                            aria-haspopup="true"
                            class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1.5 rounded-2xl focus:outline-none cursor-pointer">
                        <!-- Avatar Lingkaran dengan Inisial (Hijau Elegan seperti di Filament) -->
                        <div class="w-9 h-9 rounded-full bg-[#1B7A3A] text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-xs ring-2 ring-emerald-500/20">
                            {{ $initials }}
                        </div>
                        
                        <!-- Teks Nama & Role (Hanya tampil di Desktop/Tablet >= sm) -->
                        <div class="hidden sm:block text-left min-w-0">
                            <div class="text-xs sm:text-sm font-bold text-[#0F1F3D] leading-tight truncate max-w-[150px]">
                                {{ $userName }}
                            </div>
                            <div class="text-[9.5px] uppercase tracking-wider text-[#5B6B82] font-semibold leading-tight mt-0.5">
                                {{ $userRoleLabel }}
                            </div>
                        </div>

                        <!-- Panah Dropdown Chevron (Hanya tampil di Desktop/Tablet >= sm) -->
                        <svg id="user-menu-chevron" class="hidden sm:block w-3.5 h-3.5 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu Panel -->
                    <div id="user-menu-dropdown" 
                         class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 p-1.5 z-50 transition-all duration-150 transform opacity-0 scale-95 origin-top-right">
                        <!-- Menu Profil Saya (Navigasi ke Halaman Profil Khusus Tanpa Ikon) -->
                        <a href="{{ route('warga.profile') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold text-[#0F1F3D] hover:bg-slate-50 hover:text-primary-900 transition group mb-1 border border-transparent hover:border-slate-100">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold leading-tight truncate">Profil Saya</p>
                                <p class="text-[10px] text-[#5B6B82] font-normal leading-tight truncate mt-0.5">Informasi Akun</p>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-primary-700 group-hover:translate-x-0.5 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>

                        <!-- Tombol Keluar (Form POST) -->
                        <form action="{{ route('citizen.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 hover:text-red-700 transition group text-left cursor-pointer">
                                <svg class="w-4 h-4 text-red-500 group-hover:text-red-700 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
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

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-sm text-rose-800 flex items-start gap-3 shadow-2xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1">
                    <span class="font-bold block text-rose-950 mb-1">Periksa kembali isian form Anda:</span>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
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

    <!-- Script Interaktif Header Dropdowns & Notifikasi -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // --- 1. User Profile Menu ---
            const userBtn = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-menu-dropdown');
            const userChevron = document.getElementById('user-menu-chevron');

            function toggleUserMenu(show) {
                if (!userDropdown) return;
                const isHidden = userDropdown.classList.contains('hidden');
                const shouldOpen = show !== undefined ? show : isHidden;

                if (shouldOpen) {
                    toggleNotifMenu(false);
                    userDropdown.classList.remove('hidden');
                    requestAnimationFrame(() => {
                        userDropdown.classList.remove('opacity-0', 'scale-95');
                        userDropdown.classList.add('opacity-100', 'scale-100');
                    });
                    if (userChevron) userChevron.classList.add('rotate-180');
                    userBtn?.setAttribute('aria-expanded', 'true');
                } else {
                    userDropdown.classList.remove('opacity-100', 'scale-100');
                    userDropdown.classList.add('opacity-0', 'scale-95');
                    if (userChevron) userChevron.classList.remove('rotate-180');
                    userBtn?.setAttribute('aria-expanded', 'false');
                    setTimeout(() => {
                        userDropdown.classList.add('hidden');
                    }, 150);
                }
            }

            if (userBtn && userDropdown) {
                userBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    toggleUserMenu();
                });
            }

            // --- 2. Notification Menu & Live Fetching ---
            const notifBtn = document.getElementById('notif-menu-button');
            const notifDropdown = document.getElementById('notif-menu-dropdown');
            const notifList = document.getElementById('notif-items-list');
            const notifIndicator = document.getElementById('notif-indicator');
            const notifBadgeCount = document.getElementById('notif-badge-count');
            const markAllBtn = document.getElementById('btn-mark-all-read');

            function toggleNotifMenu(show) {
                if (!notifDropdown) return;
                const isHidden = notifDropdown.classList.contains('hidden');
                const shouldOpen = show !== undefined ? show : isHidden;

                if (shouldOpen) {
                    toggleUserMenu(false);
                    notifDropdown.classList.remove('hidden');
                    requestAnimationFrame(() => {
                        notifDropdown.classList.remove('opacity-0', 'scale-95');
                        notifDropdown.classList.add('opacity-100', 'scale-100');
                    });
                    notifBtn?.setAttribute('aria-expanded', 'true');
                    loadNotifications();
                } else {
                    notifDropdown.classList.remove('opacity-100', 'scale-100');
                    notifDropdown.classList.add('opacity-0', 'scale-95');
                    notifBtn?.setAttribute('aria-expanded', 'false');
                    setTimeout(() => {
                        notifDropdown.classList.add('hidden');
                    }, 150);
                }
            }

            if (notifBtn && notifDropdown) {
                notifBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    toggleNotifMenu();
                });
            }

            function loadNotifications() {
                if (!notifList) return;
                fetch("{{ route('warga.notifications') }}", {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    updateNotifBadge(data.unread_count);
                    if (!data.notifications || data.notifications.length === 0) {
                        notifList.innerHTML = `
                            <div class="py-6 text-center text-text-muted">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <p class="text-xs font-semibold text-slate-600">Tidak ada notifikasi</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Pemberitahuan status surat & iuran akan muncul di sini</p>
                            </div>
                        `;
                        return;
                    }

                    notifList.innerHTML = data.notifications.map(n => `
                        <div onclick="handleNotifClick(${n.id}, '${n.url}')" 
                             class="p-2.5 rounded-xl cursor-pointer transition border ${n.is_read ? 'bg-white hover:bg-slate-50 border-slate-100 text-slate-600' : 'bg-blue-50/50 hover:bg-blue-50 border-blue-100 text-slate-800'}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="font-bold text-xs ${n.is_read ? 'text-slate-800' : 'text-primary-900'} leading-tight">
                                    ${n.judul}
                                </div>
                                ${!n.is_read ? '<span class="w-2 h-2 rounded-full bg-primary-600 shrink-0 mt-0.5"></span>' : ''}
                            </div>
                            <div class="text-[11px] text-text-muted mt-1 leading-snug">
                                ${n.isi}
                            </div>
                            <div class="text-[9.5px] text-slate-400 mt-1 font-medium">
                                ${n.time_ago}
                            </div>
                        </div>
                    `).join('');
                })
                .catch(() => {
                    notifList.innerHTML = '<p class="text-xs text-rose-500 text-center py-4">Gagal memuat notifikasi.</p>';
                });
            }

            function updateNotifBadge(count) {
                if (notifIndicator) {
                    if (count > 0) {
                        notifIndicator.classList.remove('hidden');
                    } else {
                        notifIndicator.classList.add('hidden');
                    }
                }
                if (notifBadgeCount) {
                    if (count > 0) {
                        notifBadgeCount.textContent = count + ' baru';
                        notifBadgeCount.classList.remove('hidden');
                    } else {
                        notifBadgeCount.classList.add('hidden');
                    }
                }
            }

            window.handleNotifClick = function(id, url) {
                fetch(`/warga/notifikasi/${id}/baca`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                }).finally(() => {
                    if (url && url !== '#') {
                        window.location.href = url;
                    } else {
                        loadNotifications();
                    }
                });
            };

            if (markAllBtn) {
                markAllBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    fetch("{{ route('warga.notifications.read-all') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    })
                    .then(() => {
                        updateNotifBadge(0);
                        loadNotifications();
                    });
                });
            }

            // Close on outside click
            document.addEventListener('click', function (e) {
                if (userBtn && userDropdown && !userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    toggleUserMenu(false);
                }
                if (notifBtn && notifDropdown && !notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                    toggleNotifMenu(false);
                }
            });

            // Close on escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    toggleUserMenu(false);
                    toggleNotifMenu(false);
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
