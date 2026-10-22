@extends('layouts.public')

@section('title', 'Katalog UMKM Warga RT 15 | SIM-LiBas')

@section('content')
    <!-- Hero Header Katalog UMKM (Selaras dengan Direktori Layanan) -->
    <section class="sim-gradient text-white relative overflow-hidden py-10 sm:py-20">
        <!-- Subtle background shapes -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Breadcrumbs -->
            <nav class="flex items-center justify-center gap-2 text-xs text-blue-200 mb-3 sm:mb-6">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Katalog UMKM</span>
            </nav>

            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-gold-400 mb-3 sm:mb-4 border border-white/20">
                Gerakan Belanja Tetangga Sendiri
            </span>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-2 sm:mb-4">
                Katalog Usaha Warga RT 15
            </h1>
            <p class="hidden sm:block text-base sm:text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Dukungan nyata untuk usaha tetangga di RT 15 Pematang Sulur. Temukan aneka kuliner, sembako harian, kerajinan tangan, hingga aneka jasa terpercaya—pesan praktis langsung via WhatsApp tanpa perantara.
            </p>
        </div>
    </section>

    <!-- Konten Utama: Search, Filter, Grid Produk & UMKM -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-8 relative z-20 pb-16">
        <!-- Kartu Registrasi Usaha Warga Cepat -->
        <div class="bg-white border border-border-soft rounded-2xl p-4 sm:p-6 mb-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6">
            <div class="flex items-start sm:items-center justify-between gap-3 sm:gap-4 w-full md:w-auto">
                <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gold-50 border border-gold-200 flex items-center justify-center shrink-0 text-gold-600">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                            <span class="text-[9px] sm:text-[10px] uppercase font-bold tracking-wider text-gold-700 bg-gold-50 px-2 py-0.5 rounded border border-gold-200">
                                Khusus Warga RT 15
                            </span>
                            <h3 class="text-sm sm:text-base font-bold text-primary-900 leading-tight">Punya Usaha Rumahan atau Jasa?</h3>
                        </div>
                        <p class="hidden sm:block text-xs sm:text-sm text-text-muted mt-0.5">
                            Promosikan produk kuliner, warung kelontong, atau keahlian Anda di katalog ini secara gratis melalui Portal Warga.
                        </p>
                    </div>
                </div>

                <!-- Tombol Mobile di Kanan Atas -->
                <a href="{{ route('citizen.login') }}" class="md:hidden bg-primary-700 hover:bg-primary-900 text-white font-semibold text-xs py-2 px-3 rounded-xl transition shadow-xs shrink-0 flex items-center gap-1.5 self-start">
                    <svg class="w-3.5 h-3.5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Daftar</span>
                </a>
            </div>

            <!-- Tombol Desktop -->
            <a href="{{ route('citizen.login') }}" class="hidden md:flex bg-primary-700 hover:bg-primary-900 text-white font-semibold text-xs sm:text-sm py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow shrink-0 items-center gap-2">
                <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Daftarkan Usaha Anda</span>
            </a>
        </div>

    <!-- Toolbar Pencarian & Filter Cepat (Unboxed / Tidak Dibungkus Card) -->
    <div class="mb-6">
        <!-- Form Pencarian Real-Time (Tanpa Card Wrapper) -->
        <form action="{{ route('public.umkm') }}" method="GET" id="search-form" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative w-full sm:max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       id="search-input"
                       name="search"
                       value="{{ request('search', request('q')) }}"
                       placeholder="Cari nama usaha, produk, atau menu (misal: Pempek, Jahit, Katering)..."
                       autocomplete="off"
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-text-main shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 placeholder:text-gray-400 transition">
            </div>

            <div class="sm:w-56 shrink-0">
                <select id="kategori-select" name="kategori" class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-sm text-text-main shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-medium transition cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <option value="kuliner" {{ request('kategori') == 'kuliner' ? 'selected' : '' }}>Kuliner & Makanan</option>
                    <option value="sembako" {{ request('kategori') == 'sembako' ? 'selected' : '' }}>Warung & Sembako</option>
                    <option value="jasa" {{ request('kategori') == 'jasa' ? 'selected' : '' }}>Jasa & Layanan</option>
                    <option value="kerajinan" {{ request('kategori') == 'kerajinan' ? 'selected' : '' }}>Kerajinan & Fashion</option>
                    <option value="pertanian" {{ request('kategori') == 'pertanian' ? 'selected' : '' }}>Hasil Kebun & Pertanian</option>
                </select>
            </div>

            <a href="{{ route('public.umkm') }}" id="search-reset-btn" title="Reset Filter" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition shadow-2xs shrink-0 {{ (request('search') || request('q') || request('kategori')) ? '' : 'hidden' }}">
                <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span class="text-red-600 font-bold">Reset Filter</span>
            </a>
        </form>
    </div>

    <!-- Ringkasan Hasil Pencarian & View Toggle -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-text-muted mb-6 gap-3 px-1">
        <div>
            Menampilkan <span id="umkm-count" class="font-bold text-primary-900">{{ $umkms->total() }}</span> usaha terdaftar
            <span id="search-keyword-wrapper" class="{{ (request('search') || request('q')) ? '' : 'hidden' }}">
                untuk kata kunci "<span id="search-keyword-display" class="font-semibold text-primary-900">{{ request('search', request('q')) }}</span>"
            </span>
            @if(request('kategori'))
                <span id="category-filter-display">dalam kategori <span class="font-semibold text-primary-900">{{ ucfirst(request('kategori')) }}</span></span>
            @endif
        </div>

        @if(!$umkms->isEmpty())
            <!-- Switch Tampilan: Grid & Tabel -->
            <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl self-start sm:self-auto border border-slate-200/60">
                <button type="button" id="btn-view-grid" class="px-2.5 py-1 rounded-lg text-primary-900 bg-white shadow-xs font-semibold text-xs flex items-center gap-1.5 transition" title="Tampilan Kartu Grid">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Grid</span>
                </button>
                <button type="button" id="btn-view-table" class="px-2.5 py-1 rounded-lg text-text-muted hover:text-primary-900 font-semibold text-xs flex items-center gap-1.5 transition" title="Tampilan Tabel Baris">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>Tabel</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Grid & Tabel Kartu UMKM -->
    @if($umkms->isEmpty())
        <div class="bg-white border border-border-soft rounded-2xl p-12 text-center max-w-md mx-auto my-8 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-primary-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-primary-900">Belum Ada Usaha yang Cocok</h3>
            <p class="text-xs text-text-muted mt-1 leading-relaxed">
                Tidak ditemukan usaha warga dengan kriteria yang dicari. Silakan ubah kata kunci atau reset filter kategori.
            </p>
            <div class="mt-4">
                <a href="{{ route('public.umkm') }}" class="inline-flex items-center text-xs font-semibold text-primary-700 hover:text-primary-900 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition">
                    Lihat Semua Usaha
                </a>
            </div>
        </div>
    @else
        <!-- Placeholder Kosong Real-time Client-Side -->
        <div id="client-no-results" class="hidden bg-white border border-border-soft rounded-2xl p-12 text-center max-w-md mx-auto my-8 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-primary-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-primary-900">Belum Ada Usaha yang Cocok</h3>
            <p class="text-xs text-text-muted mt-1 leading-relaxed">
                Tidak ditemukan usaha warga dengan kata kunci atau filter pencarian tersebut secara real-time.
            </p>
            <div class="mt-4">
                <button type="button" id="client-clear-search-btn" class="inline-flex items-center text-xs font-semibold text-primary-700 hover:text-primary-900 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition">
                    Bersihkan Pencarian
                </button>
            </div>
        </div>

        <!-- Tampilan Kartu Grid -->
        <div id="umkm-grid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-3 sm:gap-6">
            @foreach($umkms as $item)
                @php
                    $badgeClass = match($item->kategori) {
                        'kuliner' => 'bg-emerald-50 text-emerald-800 border-emerald-200/70',
                        'sembako' => 'bg-amber-50 text-amber-800 border-amber-200/70',
                        'jasa' => 'bg-blue-50 text-blue-800 border-blue-200/70',
                        'kerajinan', 'fashion' => 'bg-purple-50 text-purple-800 border-purple-200/70',
                        'pertanian' => 'bg-lime-50 text-lime-800 border-lime-200/70',
                        default => 'bg-slate-50 text-slate-800 border-slate-200/70',
                    };

                    $categoryLabel = match($item->kategori) {
                        'kuliner' => 'Kuliner & Makanan',
                        'sembako' => 'Warung & Sembako',
                        'jasa' => 'Jasa & Layanan',
                        'kerajinan' => 'Kerajinan Tangan',
                        'fashion' => 'Fashion & Busana',
                        'pertanian' => 'Hasil Kebun & Tani',
                        default => ucfirst($item->kategori ?? 'Usaha Warga'),
                    };

                    $platforms = $item->getAvailableSocialPlatforms();
                @endphp

                <div class="umkm-card relative bg-white border border-border-soft rounded-2xl shadow-xs hover:shadow-lg hover:-translate-y-1 transition duration-200 flex flex-col justify-between overflow-hidden group cursor-pointer" data-kategori="{{ strtolower($item->kategori) }}" onclick="if(!event.target.closest('a')) { window.location.href = '{{ route('public.umkm.detail', $item->id) }}'; }">
                    
                    {{-- Header Visual Foto / Placeholder Konsisten --}}
                    <div class="relative w-full overflow-hidden bg-slate-100">
                        <a href="{{ route('public.umkm.detail', $item->id) }}" class="block">
                            <x-umkm-card-image :item="$item" aspect="aspect-[4/3] sm:aspect-[16/10]" />
                        </a>
                        
                        {{-- Badge Kategori Overlay di Atas Gambar --}}
                        <div class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 z-10 flex items-center gap-1">
                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md text-[9px] sm:text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }} border shadow-2xs backdrop-blur-md bg-white/95">
                                {{ $categoryLabel }}
                            </span>
                        </div>
                    </div>

                    {{-- Konten Utama Card: Lebih ringkas & proporsional untuk 2 kolom --}}
                    <div class="p-3 sm:p-4 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-xs sm:text-base font-bold text-primary-900 group-hover:text-primary-700 transition leading-snug tracking-tight line-clamp-2">
                                <a href="{{ route('public.umkm.detail', $item->id) }}">
                                    {{ $item->nama_usaha }}
                                </a>
                            </h3>

                            <div class="flex items-center gap-1 text-[10px] sm:text-xs text-text-muted mt-1 truncate">
                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate">{{ $item->alamat ?: 'RT 15 Pematang Sulur' }}</span>
                            </div>

                            <p class="text-[11px] sm:text-xs text-text-muted mt-1.5 sm:mt-2 leading-relaxed line-clamp-2">
                                {{ $item->deskripsi }}
                            </p>
                        </div>

                        @if($item->produk)
                            <div class="hidden sm:block mt-2.5 pt-2 border-t border-border-soft/60">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Unggulan:</span>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice(array_map('trim', explode(',', $item->produk)), 0, 2) as $prod)
                                        @if(!empty($prod))
                                            <span class="inline-block text-[10px] bg-bg-soft text-slate-700 px-2 py-0.5 rounded font-medium border border-border-soft/60 truncate max-w-[120px]">
                                                {{ $prod }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Footer Kartu: Multi-Kontak Ringkas & Navigasi Detail -->
                    <div class="pt-2 sm:pt-2.5 px-2.5 sm:px-4 pb-2 sm:pb-3 bg-slate-50/70 border-t border-border-soft flex items-center justify-between gap-1 text-[10px] sm:text-xs">
                        <div class="flex items-center gap-1 sm:gap-1.5">
                            @if(empty($platforms))
                                <span class="text-[10px] text-slate-400 font-medium">Info Usaha</span>
                            @else
                                @foreach($platforms as $p)
                                    @php
                                        $pUrl = $item->getSocialUrl($p);
                                        $iconStyle = match($p) {
                                            'whatsapp' => 'text-emerald-700 bg-emerald-100/80 hover:bg-emerald-600 hover:text-white border-emerald-200',
                                            'instagram' => 'text-rose-700 bg-rose-100/80 hover:bg-rose-600 hover:text-white border-rose-200',
                                            'tiktok' => 'text-slate-900 bg-slate-200/80 hover:bg-slate-900 hover:text-white border-slate-300',
                                            'shopee' => 'text-[#EE4D2D] bg-orange-100/80 hover:bg-[#EE4D2D] hover:text-white border-orange-200',
                                            default => 'text-primary-700 bg-blue-100/80 hover:bg-primary-700 hover:text-white border-blue-200',
                                        };
                                        $pLabel = $p === 'whatsapp' ? 'Hubungi via WhatsApp' : ucfirst($p);
                                    @endphp
                                    <a href="{{ $pUrl }}" target="_blank" rel="noopener noreferrer" title="{{ $pLabel }}" onclick="event.stopPropagation();" class="w-5 h-5 sm:w-6 sm:h-6 rounded-md border flex items-center justify-center transition shrink-0 {{ $iconStyle }}">
                                        @include('components.umkm-social-icon', ['platform' => $p, 'class' => 'w-2.5 h-2.5 sm:w-3 sm:h-3 fill-current'])
                                        <span class="sr-only">{{ $pLabel }}</span>
                                    </a>
                                @endforeach
                            @endif
                        </div>

                        <a href="{{ route('public.umkm.detail', $item->id) }}" class="text-primary-700 group-hover:text-primary-900 font-bold flex items-center gap-0.5 sm:gap-1 text-[10px] sm:text-xs shrink-0">
                            <span>Detail</span>
                            <svg class="w-3 h-3 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Tampilan Tabel Alternatif (Adaptif Responsif: 2 Kolom Ringkas di Mobile, Multi-Kolom di Desktop) -->
        <div id="umkm-table-wrapper" class="hidden overflow-hidden bg-white border border-border-soft rounded-2xl shadow-sm mb-6">
            <table class="w-full text-left border-collapse" id="umkm-table">
                <thead>
                    <tr class="bg-bg-soft text-text-muted text-xs uppercase tracking-wider border-b border-border-soft">
                        <th class="py-3 px-3.5 sm:px-5 font-semibold">Nama Usaha</th>
                        <th class="hidden md:table-cell py-3.5 px-5 font-semibold whitespace-nowrap">Kategori</th>
                        <th class="hidden lg:table-cell py-3.5 px-5 font-semibold whitespace-nowrap">Menu / Produk Unggulan</th>
                        <th class="hidden sm:table-cell py-3.5 px-5 font-semibold whitespace-nowrap">Alamat</th>
                        <th class="py-3 px-3.5 sm:px-5 font-semibold text-right whitespace-nowrap">Aksi / Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft text-sm">
                    @foreach($umkms as $item)
                        @php
                            $badgeClassTable = match($item->kategori) {
                                'kuliner' => 'bg-emerald-50 text-emerald-800 border-emerald-200/70',
                                'sembako' => 'bg-amber-50 text-amber-800 border-amber-200/70',
                                'jasa' => 'bg-blue-50 text-blue-800 border-blue-200/70',
                                'kerajinan', 'fashion' => 'bg-purple-50 text-purple-800 border-purple-200/70',
                                'pertanian' => 'bg-lime-50 text-lime-800 border-lime-200/70',
                                default => 'bg-slate-50 text-slate-800 border-slate-200/70',
                            };
                            $categoryLabelTable = match($item->kategori) {
                                'kuliner' => 'Kuliner & Makanan',
                                'sembako' => 'Warung & Sembako',
                                'jasa' => 'Jasa & Layanan',
                                'kerajinan' => 'Kerajinan Tangan',
                                'fashion' => 'Fashion & Busana',
                                'pertanian' => 'Hasil Kebun & Tani',
                                default => ucfirst($item->kategori ?? 'Usaha Warga'),
                            };
                            $platforms = $item->getAvailableSocialPlatforms();
                        @endphp
                        <tr class="umkm-tr hover:bg-blue-50/40 transition duration-150 cursor-pointer" data-kategori="{{ strtolower($item->kategori) }}" onclick="if(!event.target.closest('a')) { window.location.href = '{{ route('public.umkm.detail', $item->id) }}'; }">
                            <td class="py-3 px-3.5 sm:px-5 font-bold text-primary-900">
                                <a href="{{ route('public.umkm.detail', $item->id) }}" class="flex items-center gap-2.5 sm:gap-3 group/link">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl overflow-hidden shrink-0 border border-slate-200 bg-slate-100">
                                        <x-umkm-card-image :item="$item" aspect="aspect-square" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs sm:text-sm font-bold text-primary-900 group-hover/link:text-primary-700 transition leading-snug line-clamp-1 sm:line-clamp-none">
                                            {{ $item->nama_usaha }}
                                        </div>
                                        {{-- Info Tambahan Khusus Mobile: Kategori & Alamat Singkat --}}
                                        <div class="flex items-center gap-1.5 mt-1 md:hidden flex-wrap">
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-semibold border {{ $badgeClassTable }} whitespace-nowrap">
                                                {{ $categoryLabelTable }}
                                            </span>
                                            <span class="text-[10px] text-text-muted truncate max-w-[140px]">
                                                {{ $item->alamat ?: 'RT 15 Pematang Sulur' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td class="hidden md:table-cell py-3.5 px-5 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badgeClassTable }} whitespace-nowrap">
                                    {{ $categoryLabelTable }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell py-3.5 px-5 text-text-muted text-xs min-w-[200px]">
                                {{ $item->produk ?: '-' }}
                            </td>
                            <td class="hidden sm:table-cell py-3.5 px-5 text-text-muted text-xs whitespace-nowrap">
                                {{ $item->alamat ?: 'RT 15 Pematang Sulur' }}
                            </td>
                            <td class="py-3 px-3.5 sm:px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5 sm:gap-2">
                                    @if(!empty($platforms))
                                        <div class="hidden sm:flex items-center gap-1.5">
                                            @foreach($platforms as $p)
                                                @php
                                                    $url = $item->getSocialUrl($p);
                                                    $iconStyle = match($p) {
                                                        'whatsapp' => 'text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border-emerald-200',
                                                        'instagram' => 'text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border-rose-200',
                                                        'tiktok' => 'text-slate-900 hover:text-black bg-slate-100 hover:bg-slate-200 border-slate-300',
                                                        'shopee' => 'text-[#EE4D2D] hover:text-[#d43d1f] bg-orange-50 hover:bg-orange-100 border-orange-200',
                                                        default => 'text-primary-700 bg-blue-50 border-blue-200',
                                                    };
                                                    $tableLabel = $p === 'whatsapp' ? 'Hubungi via WhatsApp' : ucfirst($p);
                                                @endphp
                                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" title="{{ $tableLabel }}" onclick="event.stopPropagation();" class="w-7 h-7 rounded-lg border flex items-center justify-center transition {{ $iconStyle }}">
                                                    @include('components.umkm-social-icon', ['platform' => $p, 'class' => 'w-3.5 h-3.5 fill-current'])
                                                    <span class="sr-only">{{ $tableLabel }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                    <a href="{{ route('public.umkm.detail', $item->id) }}" class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-primary-700 text-primary-700 hover:text-white text-xs font-bold transition shadow-2xs">
                                        <span>Detail</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="umkm-pagination" class="mt-10">
            {{ $umkms->links() }}
        </div>
    @endif

    <!-- Banner Informasi Komunitas di Bagian Bawah -->
    <div class="mt-16 bg-white border border-border-soft rounded-3xl p-8 sm:p-12 shadow-sm flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-wider text-gold-700 mb-2 block">Dukungan Nyata UMKM</span>
            <h3 class="text-2xl font-bold text-primary-900 mb-3">
                Punya Usaha Rumahan di Lingkungan RT 15?
            </h3>
            <p class="text-sm text-text-muted leading-relaxed">
                Setiap warga yang memiliki usaha kuliner, warung, kerajinan tangan, atau jasa dapat mempromosikan usahanya di katalog ini secara gratis. Cukup daftarkan profil usaha Anda melalui akun Portal Warga.
            </p>
        </div>
        <a href="{{ route('citizen.login') }}" class="sim-btn-gold text-sm px-6 py-3.5 rounded-xl transition shadow-sm hover:shadow shrink-0 flex items-center gap-2">
            <span>Daftarkan Usaha Sekarang</span>
        </a>
    </div>

</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('input[name="search"]') || document.getElementById('search-input');
    const kategoriSelect = document.getElementById('kategori-select');
    const searchResetBtn = document.getElementById('search-reset-btn');
    const clearSearchBtn = document.getElementById('client-clear-search-btn');
    const umkmCards = document.querySelectorAll('.umkm-card');
    const tableRows = document.querySelectorAll('tbody tr.umkm-tr');
    const counterEl = document.getElementById('umkm-count');
    const noResultsEl = document.getElementById('client-no-results');
    const umkmGrid = document.getElementById('umkm-grid');
    const umkmTableWrapper = document.getElementById('umkm-table-wrapper');
    const paginationEl = document.getElementById('umkm-pagination');
    const keywordWrapper = document.getElementById('search-keyword-wrapper');
    const keywordDisplay = document.getElementById('search-keyword-display');

    const btnGrid = document.getElementById('btn-view-grid');
    const btnTable = document.getElementById('btn-view-table');

    let activeView = 'grid';

    function applySearch() {
        if (!searchInput) return;

        // Aturan spesifikasi: toLowerCase().trim()
        const query = searchInput.value.toLowerCase().trim();
        const selectedKategori = kategoriSelect ? kategoriSelect.value.toLowerCase().trim() : '';

        // Tampilkan/sembunyikan tombol reset
        if (searchResetBtn) {
            if (query !== '' || selectedKategori !== '') {
                searchResetBtn.classList.remove('hidden');
            } else {
                searchResetBtn.classList.add('hidden');
            }
        }

        // Update indikator kata kunci di teks ringkasan
        if (keywordWrapper && keywordDisplay) {
            if (query !== '') {
                keywordDisplay.textContent = searchInput.value;
                keywordWrapper.classList.remove('hidden');
            } else {
                keywordWrapper.classList.add('hidden');
            }
        }

        // 1. Multi-Field Matching: Teks pencarian dicocokkan ke seluruh kolom data baris tabel (tr) secara case-insensitive
        let visibleTableCount = 0;
        tableRows.forEach(function (tr) {
            const trText = tr.textContent.toLowerCase();
            const trCategory = (tr.getAttribute('data-kategori') || '').toLowerCase();

            const matchQuery = query === '' || trText.includes(query);
            const matchCategory = selectedKategori === '' || trCategory === selectedKategori;

            if (matchQuery && matchCategory) {
                tr.style.display = '';
                visibleTableCount++;
            } else {
                tr.style.display = 'none';
            }
        });

        // 2. Multi-Field Matching pada Kartu Grid UMKM
        let visibleCardCount = 0;
        umkmCards.forEach(function (card) {
            const cardText = card.textContent.toLowerCase();
            const cardCategory = (card.getAttribute('data-kategori') || '').toLowerCase();

            const matchQuery = query === '' || cardText.includes(query);
            const matchCategory = selectedKategori === '' || cardCategory === selectedKategori;

            if (matchQuery && matchCategory) {
                card.style.display = '';
                visibleCardCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const activeCount = umkmCards.length > 0 ? visibleCardCount : visibleTableCount;

        // Update live counter
        if (counterEl) {
            counterEl.textContent = activeCount;
        }

        // Tampilkan placeholder jika tidak ada hasil
        if (noResultsEl) {
            if (activeCount === 0 && (umkmCards.length > 0 || tableRows.length > 0)) {
                noResultsEl.classList.remove('hidden');
                if (umkmGrid) umkmGrid.classList.add('hidden');
                if (umkmTableWrapper) umkmTableWrapper.classList.add('hidden');
            } else {
                noResultsEl.classList.add('hidden');
                if (activeView === 'grid' && umkmGrid) umkmGrid.classList.remove('hidden');
                if (activeView === 'table' && umkmTableWrapper) umkmTableWrapper.classList.remove('hidden');
            }
        }

        // Sembunyikan pagination saat client-side filtering aktif
        if (paginationEl) {
            paginationEl.style.display = (query !== '' || selectedKategori !== '') ? 'none' : '';
        }
    }

    // Aturan 1: Real-Time Client-Side menggunakan JavaScript event 'input'
    if (searchInput) {
        searchInput.addEventListener('input', applySearch);
    }

    // Hindari reload halaman jika tombol Enter ditekan pada input pencarian
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            applySearch();
        });
    }

    // Filter kategori dropdown
    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', function () {
            applySearch();
        });
    }

    // Reset button click
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (kategoriSelect) kategoriSelect.value = '';
            applySearch();
            if (searchInput) searchInput.focus();
        });
    }

    // Toggle Tampilan: Grid vs Tabel
    if (btnGrid && btnTable) {
        btnGrid.addEventListener('click', function () {
            activeView = 'grid';
            if (umkmGrid) umkmGrid.classList.remove('hidden');
            if (umkmTableWrapper) umkmTableWrapper.classList.add('hidden');

            btnGrid.classList.remove('text-text-muted');
            btnGrid.classList.add('text-primary-900', 'bg-white', 'shadow-xs');
            btnTable.classList.remove('text-primary-900', 'bg-white', 'shadow-xs');
            btnTable.classList.add('text-text-muted');
        });

        btnTable.addEventListener('click', function () {
            activeView = 'table';
            if (umkmGrid) umkmGrid.classList.add('hidden');
            if (umkmTableWrapper) umkmTableWrapper.classList.remove('hidden');

            btnTable.classList.remove('text-text-muted');
            btnTable.classList.add('text-primary-900', 'bg-white', 'shadow-xs');
            btnGrid.classList.remove('text-primary-900', 'bg-white', 'shadow-xs');
            btnGrid.classList.add('text-text-muted');
        });
    }

    // Jalankan pencarian jika sudah ada nilai awal dari SSR
    if (searchInput && searchInput.value.trim() !== '') {
        applySearch();
    }
});
</script>
@endpush
