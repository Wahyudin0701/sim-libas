@extends('layouts.public')

@section('title', 'Katalog UMKM Warga RT 15 | SIM-LiBas')

@section('content')
    <!-- Hero Header Katalog UMKM (Selaras dengan Direktori Layanan) -->
    <section class="sim-gradient text-white relative overflow-hidden py-16 sm:py-20">
        <!-- Subtle background shapes -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Breadcrumbs -->
            <nav class="flex items-center justify-center gap-2 text-xs text-blue-200 mb-6">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Katalog UMKM</span>
            </nav>

            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-gold-400 mb-4 border border-white/20">
                Gerakan Belanja Tetangga Sendiri
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-4">
                Katalog Usaha Warga RT 15
            </h1>
            <p class="text-base sm:text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Dukungan nyata untuk usaha tetangga di RT 15 Pematang Sulur. Temukan aneka kuliner, sembako harian, kerajinan tangan, hingga aneka jasa terpercaya—pesan praktis langsung via WhatsApp tanpa perantara.
            </p>
        </div>
    </section>

    <!-- Konten Utama: Search, Filter, Grid Produk & UMKM -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 pb-16">
        <!-- Kartu Registrasi Usaha Warga Cepat -->
        <div class="bg-white border border-border-soft rounded-2xl p-5 sm:p-6 mb-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gold-50 border border-gold-200 flex items-center justify-center shrink-0 text-gold-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-gold-700 bg-gold-50 px-2 py-0.5 rounded border border-gold-200">
                            Khusus Warga RT 15
                        </span>
                        <h3 class="text-base font-bold text-primary-900">Punya Usaha Rumahan atau Jasa?</h3>
                    </div>
                    <p class="text-xs sm:text-sm text-text-muted mt-0.5">
                        Promosikan produk kuliner, warung kelontong, atau keahlian Anda di katalog ini secara gratis melalui Portal Warga.
                    </p>
                </div>
            </div>
            <a href="{{ route('citizen.login') }}" class="bg-primary-700 hover:bg-primary-900 text-white font-semibold text-xs sm:text-sm py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow shrink-0 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Daftarkan Usaha Anda</span>
            </a>
        </div>

    <!-- Toolbar Pencarian & Filter Cepat (Unboxed / Tidak Dibungkus Card) -->
    <div class="mb-8 space-y-4">
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

        <!-- Filter Pills Kategori Cepat (Tanpa Card Wrapper) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
            <span class="text-text-muted font-semibold shrink-0 pr-1">Kategori:</span>
            @php
                $activeCat = request('kategori', '');
                $categories = [
                    '' => 'Semua',
                    'kuliner' => 'Kuliner & Makanan',
                    'sembako' => 'Warung & Sembako',
                    'jasa' => 'Jasa & Layanan',
                    'kerajinan' => 'Kerajinan',
                    'pertanian' => 'Hasil Kebun',
                ];
            @endphp
            @foreach($categories as $key => $label)
                <a href="{{ route('public.umkm', array_filter(['kategori' => $key, 'search' => request('search', request('q'))])) }}"
                   data-kategori="{{ $key }}"
                   class="category-pill shrink-0 px-3 py-1.5 rounded-lg font-medium transition cursor-pointer {{ $activeCat === $key ? 'bg-primary-900 text-white shadow-sm' : 'bg-white hover:bg-slate-100 text-text-muted border border-border-soft' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
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
        <div id="umkm-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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

                    $wa = preg_replace('/[^0-9]/', '', $item->whatsapp ?? $item->telepon);
                    if (str_starts_with($wa, '0')) {
                        $wa = '62' . substr($wa, 1);
                    }
                    $pesan = urlencode("Halo {$item->nama_usaha}, saya warga RT 15 melihat profil usaha Anda di Portal SIM-LiBas. Apakah produk masih tersedia?");
                @endphp

                <div class="umkm-card bg-white border border-border-soft rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between overflow-hidden group" data-kategori="{{ strtolower($item->kategori) }}">
                    
                    @if($item->foto_path)
                        <div class="h-44 w-full bg-slate-100 overflow-hidden relative">
                            <img src="{{ asset('storage/' . $item->foto_path) }}" alt="{{ $item->nama_usaha }}" class="w-full h-full object-cover">
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }} border shadow-sm backdrop-blur-sm bg-white/95">
                                {{ $categoryLabel }}
                            </span>
                        </div>
                    @endif

                    <div class="p-5 flex-grow flex flex-col">
                        @if(!$item->foto_path)
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }} border">
                                    {{ $categoryLabel }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Warga RT 15
                                </span>
                            </div>
                        @else
                            <div class="flex items-center justify-end mb-2">
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Warga RT 15
                                </span>
                            </div>
                        @endif

                        <h3 class="text-base sm:text-lg font-bold text-primary-900 group-hover:text-primary-700 transition leading-snug tracking-tight">
                            {{ $item->nama_usaha }}
                        </h3>

                        <div class="flex items-start gap-1.5 text-xs text-text-muted mt-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="leading-relaxed">{{ $item->alamat ?? 'RT 15 Pematang Sulur, Kota Jambi' }}</span>
                        </div>

                        <p class="text-xs sm:text-sm text-text-muted mt-3 leading-relaxed line-clamp-3">
                            {{ $item->deskripsi }}
                        </p>

                        @if($item->produk)
                            <div class="mt-4 pt-3 border-t border-border-soft/80 mt-auto">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Menu / Produk Unggulan:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach(array_map('trim', explode(',', $item->produk)) as $prod)
                                        @if(!empty($prod))
                                            <span class="inline-block text-[11px] bg-bg-soft text-slate-700 px-2.5 py-1 rounded-md font-medium border border-border-soft/60">
                                                {{ $prod }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Footer Kartu: Kontak WhatsApp -->
                    <div class="p-4 pt-3 bg-slate-50/60 border-t border-border-soft">
                        @if($wa)
                            <a href="https://wa.me/{{ $wa }}?text={{ $pesan }}" target="_blank" rel="noopener" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 px-3 rounded-xl flex items-center justify-center gap-2 transition shadow-sm hover:shadow">
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                </svg>
                                <span>Hubungi via WhatsApp</span>
                            </a>
                        @else
                            <div class="text-center text-xs text-text-muted py-1">
                                Kontak belum tersedia
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tampilan Tabel Alternatif (Mendukung Multi-Field Matching pada Baris Tabel tr) -->
        <div id="umkm-table-wrapper" class="hidden overflow-x-auto bg-white border border-border-soft rounded-2xl shadow-sm mb-6">
            <!-- Indikator Geser untuk Layar Mobile -->
            <div class="sm:hidden px-4 py-2 bg-blue-50/70 border-b border-border-soft text-[11px] text-primary-800 font-medium flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Geser ke samping untuk melihat seluruh data
                </span>
                <span class="text-[10px] text-text-muted">↔️ Scroll</span>
            </div>

            <table class="w-full min-w-[760px] text-left border-collapse" id="umkm-table">
                <thead>
                    <tr class="bg-bg-soft text-text-muted text-xs uppercase tracking-wider border-b border-border-soft">
                        <th class="py-3.5 px-5 font-semibold whitespace-nowrap min-w-[200px]">Nama Usaha</th>
                        <th class="py-3.5 px-5 font-semibold whitespace-nowrap min-w-[150px]">Kategori</th>
                        <th class="py-3.5 px-5 font-semibold whitespace-nowrap min-w-[220px]">Menu / Produk Unggulan</th>
                        <th class="py-3.5 px-5 font-semibold whitespace-nowrap min-w-[180px]">Alamat</th>
                        <th class="py-3.5 px-5 font-semibold text-right whitespace-nowrap min-w-[120px]">Kontak</th>
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
                            $waTable = preg_replace('/[^0-9]/', '', $item->whatsapp ?? $item->telepon);
                            if (str_starts_with($waTable, '0')) {
                                $waTable = '62' . substr($waTable, 1);
                            }
                            $pesanTable = urlencode("Halo {$item->nama_usaha}, saya warga RT 15 melihat profil usaha Anda di Portal SIM-LiBas. Apakah produk masih tersedia?");
                        @endphp
                        <tr class="umkm-tr hover:bg-blue-50/40 transition duration-150" data-kategori="{{ strtolower($item->kategori) }}">
                            <td class="py-4 px-5 font-bold text-primary-900 whitespace-nowrap">
                                <div>{{ $item->nama_usaha }}</div>
                                <span class="text-[11px] text-emerald-700 font-normal">Warga RT 15</span>
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClassTable }} whitespace-nowrap">
                                    {{ $categoryLabelTable }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-text-muted text-xs min-w-[200px]">
                                {{ $item->produk ?: '-' }}
                            </td>
                            <td class="py-4 px-5 text-text-muted text-xs whitespace-nowrap">
                                {{ $item->alamat ?: 'RT 15 Pematang Sulur' }}
                            </td>
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                @if($waTable)
                                    <a href="https://wa.me/{{ $waTable }}?text={{ $pesanTable }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition shadow-xs whitespace-nowrap">
                                        <span>WhatsApp</span>
                                    </a>
                                @else
                                    <span class="text-xs text-text-muted">-</span>
                                @endif
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
    const categoryPills = document.querySelectorAll('.category-pill');

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
            const val = this.value;
            categoryPills.forEach(function (pill) {
                const cat = pill.getAttribute('data-kategori') || '';
                if (cat === val) {
                    pill.classList.remove('bg-white', 'hover:bg-slate-100', 'text-text-muted', 'border-border-soft');
                    pill.classList.add('bg-primary-900', 'text-white', 'shadow-sm');
                } else {
                    pill.classList.remove('bg-primary-900', 'text-white', 'shadow-sm');
                    pill.classList.add('bg-white', 'hover:bg-slate-100', 'text-text-muted', 'border', 'border-border-soft');
                }
            });
            applySearch();
        });
    }

    // Filter kategori pills
    categoryPills.forEach(function (pill) {
        pill.addEventListener('click', function (e) {
            e.preventDefault();
            const cat = this.getAttribute('data-kategori') || '';
            if (kategoriSelect) {
                kategoriSelect.value = cat;
            }
            categoryPills.forEach(function (p) {
                p.classList.remove('bg-primary-900', 'text-white', 'shadow-sm');
                p.classList.add('bg-white', 'hover:bg-slate-100', 'text-text-muted', 'border', 'border-border-soft');
            });
            this.classList.remove('bg-white', 'hover:bg-slate-100', 'text-text-muted', 'border', 'border-border-soft');
            this.classList.add('bg-primary-900', 'text-white', 'shadow-sm');
            applySearch();
        });
    });

    // Reset button click
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (kategoriSelect) kategoriSelect.value = '';
            categoryPills.forEach(function (p, idx) {
                if (idx === 0) {
                    p.classList.remove('bg-white', 'hover:bg-slate-100', 'text-text-muted', 'border-border-soft');
                    p.classList.add('bg-primary-900', 'text-white', 'shadow-sm');
                } else {
                    p.classList.remove('bg-primary-900', 'text-white', 'shadow-sm');
                    p.classList.add('bg-white', 'hover:bg-slate-100', 'text-text-muted', 'border', 'border-border-soft');
                }
            });
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
