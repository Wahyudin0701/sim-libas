@extends('layouts.public')

@section('title', 'Layanan Warga | SIM-LiBas RT 15 Pematang Sulur')

@section('content')
    <!-- Hero Header Direktori Layanan -->
    <section class="sim-gradient text-white relative overflow-hidden py-10 sm:py-20">
        <!-- Subtle background shapes -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Breadcrumbs -->
            <nav class="flex items-center justify-center gap-2 text-xs text-blue-200 mb-3 sm:mb-6">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Layanan Warga</span>
            </nav>

            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-gold-400 mb-3 sm:mb-4 border border-white/20">
                Ekosistem Digital Terpadu
            </span>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-2 sm:mb-4">
                Layanan Warga RT 15 Pematang Sulur
            </h1>
            <p class="hidden sm:block text-base sm:text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Pelajari rincian setiap layanan digital yang disediakan untuk mempermudah urusan administrasi, iuran, bank sampah, aspirasi, hingga kemajuan UMKM warga.
            </p>
        </div>
    </section>

    <!-- Grid 6 Kartu Layanan Warga -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-8 relative z-20 pb-16 sm:pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
            @foreach($services as $item)
                <div class="sim-card p-5 sm:p-8 flex flex-col justify-between hover:-translate-y-1.5 transition duration-300 shadow-sm hover:shadow-xl bg-white border border-border-soft rounded-2xl group">
                    <div>
                        <!-- Nama & Deskripsi Layanan -->
                        <h2 class="text-lg sm:text-xl font-bold text-primary-900 group-hover:text-primary-700 transition mb-2 sm:mb-3">
                            {{ $item['nama'] }}
                        </h2>
                        <p class="text-sm text-text-muted leading-relaxed mb-4">
                            {{ $item['deskripsi'] }}
                        </p>

                        <!-- Poin Keunggulan (Disembunyikan di Mobile) -->
                        <div class="hidden sm:flex p-3.5 rounded-xl bg-bg-soft border border-border-soft text-xs text-primary-900 mb-6 items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="leading-snug">{{ $item['keunggulan'] }}</span>
                        </div>
                    </div>

                    <!-- Tombol Aksi: Pelajari Layanan -->
                    <div class="pt-4 border-t border-border-soft flex items-center justify-between">
                        <a href="{{ route('public.layanan.detail', $item['slug']) }}" class="w-full text-center bg-primary-700 hover:bg-primary-900 text-white font-semibold text-sm py-3 px-5 rounded-xl transition shadow-sm hover:shadow flex items-center justify-center gap-2 group/btn">
                            <span>Pelajari Layanan</span>
                            <svg class="w-3.5 h-3.5 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Banner Sekretariat RT -->
        <div class="mt-16 bg-white border border-border-soft rounded-3xl p-8 sm:p-12 shadow-sm flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <span class="text-xs font-bold uppercase tracking-wider text-gold-700 mb-2 block">Butuh Bantuan Administrasi?</span>
                <h3 class="text-2xl font-bold text-primary-900 mb-3">
                    Pengurus RT 15 Siap Membantu Anda
                </h3>
                <p class="text-sm text-text-muted leading-relaxed">
                    Jika Anda memiliki kendala teknis dalam mengakses portal atau membutuhkan konsultasi berkas fisik, silakan hubungi Sekretariat RT 15 Pematang Sulur melalui Telepon / WhatsApp.
                </p>
            </div>
            <a href="https://wa.me/6281274001515?text=Halo%20Pengurus%20RT%2015,%20saya%20warga%20ingin%20bertanya%20mengenai%20layanan" target="_blank" rel="noopener noreferrer" class="bg-green-700 hover:bg-green-800 text-white font-bold text-sm px-6 py-3.5 rounded-xl transition shadow-sm hover:shadow shrink-0 flex items-center gap-2">
                <span>Hubungi Sekretariat WA</span>
            </a>
        </div>
    </section>
@endsection
