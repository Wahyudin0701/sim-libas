@extends('layouts.public')

@section('title', $umkm->nama_usaha . ' - Katalog UMKM RT 15 | SIM-LiBas')

@section('content')
    @php
        $badgeClass = match($umkm->kategori) {
            'kuliner' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
            'sembako' => 'bg-amber-50 text-amber-800 border-amber-200/80',
            'jasa' => 'bg-blue-50 text-blue-800 border-blue-200/80',
            'kerajinan', 'fashion' => 'bg-purple-50 text-purple-800 border-purple-200/80',
            'pertanian' => 'bg-lime-50 text-lime-800 border-lime-200/80',
            default => 'bg-slate-50 text-slate-800 border-slate-200/80',
        };

        $categoryLabel = match($umkm->kategori) {
            'kuliner' => 'Kuliner & Makanan',
            'sembako' => 'Warung & Sembako',
            'jasa' => 'Jasa & Layanan',
            'kerajinan' => 'Kerajinan Tangan',
            'fashion' => 'Fashion & Busana',
            'pertanian' => 'Hasil Kebun & Tani',
            default => ucfirst($umkm->kategori ?? 'Usaha Warga'),
        };

        $platforms = $umkm->getAvailableSocialPlatforms();
        $headOfHousehold = $umkm->household?->headOfHousehold?->name;
    @endphp

    <!-- 1. HERO HEADER DETAIL UMKM -->
    <section class="sim-gradient text-white relative overflow-hidden py-8 sm:py-14">
        <!-- Subtle background wave -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Navigasi Breadcrumbs & Tombol Kembali -->
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 sm:mb-6">
                <nav class="flex items-center gap-2 text-xs text-blue-200 flex-wrap">
                    <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('public.umkm') }}" class="hover:text-white transition">Katalog UMKM</a>
                    <span>/</span>
                    <span class="text-white font-semibold truncate max-w-[200px] sm:max-w-xs">{{ $umkm->nama_usaha }}</span>
                </nav>

                <a href="{{ route('public.umkm') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-blue-100 hover:text-white text-xs font-semibold backdrop-blur-sm border border-white/20 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Kembali ke Katalog</span>
                </a>
            </div>

            <!-- Header Title & Badges -->
            <div class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $badgeClass }} border shadow-xs bg-white/95">
                        {{ $categoryLabel }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Usaha Terverifikasi Warga RT 15</span>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-3">
                    {{ $umkm->nama_usaha }}
                </h1>

                <div class="flex items-center gap-2 text-xs sm:text-sm text-blue-100 flex-wrap">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $umkm->alamat ?: 'RT 15 Pematang Sulur, Kota Jambi' }}</span>
                    </span>
                    @if($headOfHousehold)
                        <span class="text-blue-300">•</span>
                        <span>Keluarga {{ $headOfHousehold }}</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- 2. KONTEN DETAIL UTAMA -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- KOLOM KIRI (Info Usaha, Foto, Menu, Lokasi) - 8 Kolom -->
            <div class="lg:col-span-8 space-y-6 sm:space-y-8">
                
                <!-- Card Foto Usaha Utama -->
                <div class="bg-white border border-border-soft rounded-3xl overflow-hidden shadow-sm">
                    <div class="relative w-full aspect-[16/10] sm:aspect-[21/9] bg-slate-100 overflow-hidden">
                        @if($umkm->hasPhoto())
                            <img src="{{ $umkm->getPhotoUrl() }}"
                                 alt="{{ $umkm->nama_usaha }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover">
                        @else
                            {{-- Placeholder estetik berbasis kategori --}}
                            <div class="w-full h-full bg-gradient-to-br from-slate-100 via-blue-50/50 to-slate-200 flex flex-col items-center justify-center p-6 text-center">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/90 border border-slate-200/80 shadow-xs flex items-center justify-center text-primary-600 mb-3">
                                    @php
                                        $cat = $umkm->kategori;
                                    @endphp
                                    @if($cat === 'kuliner')
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    @elseif($cat === 'sembako')
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    @elseif($cat === 'jasa')
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    @elseif($cat === 'kerajinan' || $cat === 'fashion')
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                    @else
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <span class="text-sm font-semibold text-slate-700">{{ $categoryLabel }} RT 15</span>
                                <span class="text-xs text-slate-400 mt-0.5">Representasi visual usaha warga</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Deskripsi Usaha -->
                <div class="bg-white border border-border-soft rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-primary-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-primary-900">Tentang Usaha</h2>
                    </div>

                    <div class="prose max-w-none text-sm sm:text-base text-text-muted leading-relaxed whitespace-pre-line">
                        {{ $umkm->deskripsi }}
                    </div>
                </div>

                <!-- Card Menu / Produk Unggulan -->
                @if($umkm->produk)
                    <div class="bg-white border border-border-soft rounded-3xl p-6 sm:p-8 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-gold-50 border border-gold-200 flex items-center justify-center text-gold-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-primary-900">Menu & Produk Unggulan</h2>
                        </div>

                        <p class="text-xs sm:text-sm text-text-muted mb-4">
                            Daftar pilihan produk, menu makanan, atau jenis jasa utama yang dapat Anda pesan langsung:
                        </p>

                        <div class="flex flex-wrap gap-2 sm:gap-2.5">
                            @foreach(array_map('trim', explode(',', $umkm->produk)) as $prod)
                                @if(!empty($prod))
                                    <span class="inline-flex items-center gap-1.5 text-xs sm:text-sm bg-bg-soft hover:bg-blue-50/80 text-primary-900 px-3.5 py-2 rounded-xl font-semibold border border-border-soft transition">
                                        <svg class="w-3.5 h-3.5 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span>{{ $prod }}</span>
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Card Lokasi & Verifikasi Komunitas -->
                <div class="bg-white border border-border-soft rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-primary-900">Lokasi & Alamat Usaha</h2>
                    </div>

                    <div class="space-y-3 text-sm text-text-muted">
                        <div class="flex items-start gap-2.5">
                            <span class="font-semibold text-primary-900 shrink-0 w-28">Alamat:</span>
                            <span class="text-text-main">{{ $umkm->alamat ?: 'RT 15 Pematang Sulur' }}</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="font-semibold text-primary-900 shrink-0 w-28">Lingkungan:</span>
                            <span class="text-text-main">RT 15 Kampung Digital Pinang Masak Lestari, Kelurahan Pematang Sulur, Telanaipura, Kota Jambi</span>
                        </div>
                        @if($headOfHousehold)
                            <div class="flex items-start gap-2.5">
                                <span class="font-semibold text-primary-900 shrink-0 w-28">Pemilik Usaha:</span>
                                <span class="text-text-main">Keluarga {{ $headOfHousehold }} (Warga Terdaftar)</span>
                            </div>
                        @endif
                        <div class="flex items-start gap-2.5">
                            <span class="font-semibold text-primary-900 shrink-0 w-28">Status Moderasi:</span>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-200">
                                ✓ Disetujui Pengurus RT
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (Kontak Media Sosial & Marketplace - HANYA YANG TERSEDIA) - 4 Kolom Sticky -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                <!-- Kartu Hubungi & Pesan -->
                <div class="bg-white border border-border-soft rounded-3xl p-6 shadow-sm">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gold-700 bg-gold-50 px-2.5 py-1 rounded-md border border-gold-200 inline-block mb-3">
                        Pesan & Hubungi Langsung
                    </span>
                    <h3 class="text-lg font-bold text-primary-900 mb-1">
                        Kontak Usaha
                    </h3>
                    <p class="text-xs text-text-muted mb-5 leading-relaxed">
                        Pesan praktis langsung ke pemilik usaha tanpa biaya perantara atau komisi tambahan.
                    </p>

                    @if(count($platforms) === 0 && empty($umkm->telepon))
                        {{-- Kasus tidak ada kontak sama sekali --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center text-xs text-slate-500">
                            <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-semibold text-slate-700 mb-1">Kontak Digital Belum Tersedia</p>
                            <p>Pemilik usaha belum menyertakan kontak media sosial. Silakan datang langsung ke alamat usaha di atas.</p>
                        </div>
                    @else
                        {{-- Daftar Platform Kontak yang HANYA TERSEDIA --}}
                        <div class="space-y-3">
                            
                            {{-- WhatsApp --}}
                            @if(in_array('whatsapp', $platforms))
                                @php
                                    $waUrl = $umkm->getSocialUrl('whatsapp');
                                    $waLabel = $umkm->getSocialLabel('whatsapp');
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-between p-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-md hover:shadow-lg transition-all duration-200">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                            @include('components.umkm-social-icon', ['platform' => 'whatsapp', 'class' => 'w-5 h-5 fill-white'])
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold leading-tight">Hubungi via WhatsApp</div>
                                            <div class="text-[11px] text-emerald-100 truncate mt-0.5">{{ $waLabel }}</div>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-emerald-200 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- Instagram --}}
                            @if(in_array('instagram', $platforms))
                                @php
                                    $igUrl = $umkm->getSocialUrl('instagram');
                                    $igLabel = $umkm->getSocialLabel('instagram');
                                @endphp
                                <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-between p-3.5 rounded-2xl bg-gradient-to-r from-amber-500 via-rose-500 to-purple-600 hover:opacity-95 text-white shadow-md hover:shadow-lg transition-all duration-200">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                            @include('components.umkm-social-icon', ['platform' => 'instagram', 'class' => 'w-5 h-5 fill-white'])
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold leading-tight">Kunjungi Instagram</div>
                                            <div class="text-[11px] text-white/90 truncate mt-0.5">{{ $igLabel }}</div>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-white/80 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- TikTok --}}
                            @if(in_array('tiktok', $platforms))
                                @php
                                    $ttUrl = $umkm->getSocialUrl('tiktok');
                                    $ttLabel = $umkm->getSocialLabel('tiktok');
                                @endphp
                                <a href="{{ $ttUrl }}" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-between p-3.5 rounded-2xl bg-slate-900 hover:bg-black text-white shadow-md hover:shadow-lg transition-all duration-200">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                            @include('components.umkm-social-icon', ['platform' => 'tiktok', 'class' => 'w-5 h-5 fill-white'])
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold leading-tight">Buka di TikTok</div>
                                            <div class="text-[11px] text-slate-300 truncate mt-0.5">{{ $ttLabel }}</div>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- Shopee --}}
                            @if(in_array('shopee', $platforms))
                                @php
                                    $shopeeUrl = $umkm->getSocialUrl('shopee');
                                    $shopeeLabel = $umkm->getSocialLabel('shopee');
                                @endphp
                                <a href="{{ $shopeeUrl }}" target="_blank" rel="noopener noreferrer" style="background-color: #EE4D2D;" class="group flex items-center justify-between p-3.5 rounded-2xl bg-[#EE4D2D] hover:bg-[#d83f20] text-white shadow-md hover:shadow-lg transition-all duration-200">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                            @include('components.umkm-social-icon', ['platform' => 'shopee', 'class' => 'w-5 h-5 fill-white'])
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold leading-tight">Belanja di Shopee</div>
                                            <div class="text-[11px] text-white/90 truncate mt-0.5">{{ $shopeeLabel }}</div>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-white/80 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- Facebook --}}
                            @if(in_array('facebook', $platforms))
                                @php
                                    $fbUrl = $umkm->getSocialUrl('facebook');
                                    $fbLabel = $umkm->getSocialLabel('facebook');
                                @endphp
                                <a href="{{ $fbUrl }}" target="_blank" rel="noopener noreferrer" style="background-color: #1877F2;" class="group flex items-center justify-between p-3.5 rounded-2xl bg-[#1877F2] hover:bg-[#166fe5] text-white shadow-md hover:shadow-lg transition-all duration-200">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                            @include('components.umkm-social-icon', ['platform' => 'facebook', 'class' => 'w-5 h-5 fill-white'])
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold leading-tight">Buka di Facebook</div>
                                            <div class="text-[11px] text-white/90 truncate mt-0.5">{{ $fbLabel }}</div>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-white/80 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- GrabFood / Marketplace --}}
                            @if(in_array('grabfood', $platforms))
                                @php
                                    $grabUrl = $umkm->getSocialUrl('grabfood');
                                    $grabLabel = $umkm->getSocialLabel('grabfood');
                                @endphp
                                <a href="{{ $grabUrl }}" target="_blank" rel="noopener noreferrer" style="background-color: #00B14F;" class="group flex items-center justify-between p-3.5 rounded-2xl bg-[#00B14F] hover:bg-[#009b45] text-white shadow-md hover:shadow-lg transition-all duration-200">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                            @include('components.umkm-social-icon', ['platform' => 'grabfood', 'class' => 'w-5 h-5 fill-white'])
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold leading-tight">Pesan di GrabFood</div>
                                            <div class="text-[11px] text-white/90 truncate mt-0.5">{{ $grabLabel }}</div>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-white/80 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- Sambungan Telepon Biasa (Jika ada dan belum tercakup) --}}
                            @if(!empty($umkm->telepon))
                                @php
                                    $cleanPhone = preg_replace('/[^0-9+]/', '', $umkm->telepon);
                                @endphp
                                <a href="tel:{{ $cleanPhone }}" class="group flex items-center justify-between p-3.5 rounded-2xl bg-blue-50 hover:bg-blue-100/80 text-primary-900 border border-blue-200 transition-all duration-200">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold leading-tight">Telepon Langsung</div>
                                            <div class="text-[11px] text-text-muted truncate mt-0.5">{{ $umkm->telepon }}</div>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-primary-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                        </div>
                    @endif

                    <!-- Bagikan Usaha ke Warga Lain -->
                    <div class="mt-6 pt-4 border-t border-border-soft flex items-center justify-between text-xs text-text-muted">
                        <span class="font-medium">Bantu promosikan:</span>
                        <a href="https://wa.me/?text={{ urlencode('Yuk dukung usaha tetangga kita di RT 15: *' . $umkm->nama_usaha . '*! Cek profil usahanya di: ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-emerald-700 hover:text-emerald-800 font-bold">
                            @include('components.umkm-social-icon', ['platform' => 'whatsapp', 'class' => 'w-3.5 h-3.5 fill-current'])
                            <span>Share ke WA RT</span>
                        </a>
                    </div>
                </div>

                <!-- Kartu Gerakan Belanja Tetangga -->
                <div class="bg-gradient-to-br from-blue-50/70 via-indigo-50/40 to-white border border-blue-100 rounded-3xl p-6 shadow-2xs">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-base">🤝</span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-900">Gerakan Belanja Tetangga</h4>
                    </div>
                    <p class="text-xs text-text-muted leading-relaxed">
                        Membeli produk dari tetangga sekitar RT 15 memperkuat perputaran ekonomi keluarga dan mempererat kerukunan antarwarga lingkungan.
                    </p>
                </div>

            </div>

        </div>

        <!-- 3. REKOMENDASI USAHA WARGA LAINNYA -->
        @if(isset($relatedUmkm) && $relatedUmkm->isNotEmpty())
            <div class="mt-16 pt-12 border-t border-border-soft">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gold-700">Dukungan Komunitas</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-primary-900">Usaha Warga RT 15 Lainnya</h3>
                    </div>
                    <a href="{{ route('public.umkm') }}" class="text-xs sm:text-sm font-semibold text-primary-700 hover:text-primary-900 flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($relatedUmkm as $rel)
                        @php
                            $relBadgeClass = match($rel->kategori) {
                                'kuliner' => 'bg-emerald-50 text-emerald-800 border-emerald-200/70',
                                'sembako' => 'bg-amber-50 text-amber-800 border-amber-200/70',
                                'jasa' => 'bg-blue-50 text-blue-800 border-blue-200/70',
                                'kerajinan', 'fashion' => 'bg-purple-50 text-purple-800 border-purple-200/70',
                                default => 'bg-slate-50 text-slate-800 border-slate-200/70',
                            };
                            $relCategoryLabel = match($rel->kategori) {
                                'kuliner' => 'Kuliner',
                                'sembako' => 'Sembako',
                                'jasa' => 'Jasa',
                                'kerajinan' => 'Kerajinan',
                                'fashion' => 'Fashion',
                                default => ucfirst($rel->kategori ?? 'Usaha'),
                            };
                        @endphp
                        <a href="{{ route('public.umkm.detail', $rel->id) }}" class="group bg-white border border-border-soft rounded-2xl overflow-hidden shadow-2xs hover:shadow-md hover:-translate-y-1 transition duration-200 flex flex-col justify-between">
                            <div class="relative w-full aspect-[4/3] bg-slate-100 overflow-hidden">
                                <x-umkm-card-image :item="$rel" aspect="aspect-[4/3]" />
                                <span class="absolute top-2 left-2 z-10 px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider {{ $relBadgeClass }} border bg-white/95 backdrop-blur-xs">
                                    {{ $relCategoryLabel }}
                                </span>
                            </div>
                            <div class="p-3 sm:p-4 flex-grow flex flex-col justify-between">
                                <div>
                                    <h4 class="text-xs sm:text-sm font-bold text-primary-900 group-hover:text-primary-700 transition leading-snug line-clamp-2">
                                        {{ $rel->nama_usaha }}
                                    </h4>
                                    <p class="text-[11px] text-text-muted mt-1 truncate">
                                        {{ $rel->alamat ?: 'RT 15 Pematang Sulur' }}
                                    </p>
                                </div>
                                <div class="mt-3 pt-2 border-t border-border-soft flex items-center justify-between text-[11px] font-semibold text-primary-700">
                                    <span>Lihat Detail</span>
                                    <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </section>
@endsection
