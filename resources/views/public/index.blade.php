@extends('layouts.public')

@section('title', 'SIM-LiBas | Portal Terpadu RT 15 Pematang Sulur Kota Jambi')

@section('content')
    <!-- Hero Section -->
    <section class="sim-gradient text-white relative hero-fullscreen flex items-center justify-center py-6 sm:py-8 lg:py-10 z-20">
        <!-- Background subtle wave shapes -->
        <div class="absolute inset-0 opacity-10 pointer-events-none overflow-hidden">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 w-full my-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                <div class="lg:col-span-7 flex flex-col justify-center py-1 lg:py-2">
                    <div>
                        <!-- Badge Kampung Digital -->
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-[11px] sm:text-xs font-semibold text-gold-500 mb-4 lg:mb-12 border border-white/20">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            Kampung Digital Pinang Masak Lestari
                        </div>

                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white mb-4">
                            Satu Portal, Banyak Layanan, <span class="text-gold-500">Manfaat Nyata</span> untuk Warga.
                        </h1>

                        <p class="text-xs sm:text-sm lg:text-base text-blue-100 max-w-xl leading-relaxed">
                            Selamat datang di portal pelayanan dan informasi digital RT 15 Pematang Sulur, Kota Jambi. Mengurus surat pengantar, pantau iuran, kelola bank sampah, hingga dukung UMKM warga dalam satu aplikasi.
                        </p>
                    </div>

                    <!-- Tombol Aksi Hero: Masuk Portal Warga & Layanan Warga (Proporsional di Mobile) -->
                    <div class="mt-7 sm:mt-8 grid grid-cols-2 gap-2.5 sm:gap-4 sm:flex sm:items-center w-full sm:w-auto">
                        @auth
                            @if(auth()->user()->isWarga())
                                <a href="{{ route('warga.dashboard') }}" class="w-full sm:w-auto bg-gold-500 hover:bg-gold-700 text-primary-900 font-bold text-xs sm:text-sm px-3 sm:px-6 py-3 rounded-xl transition shadow-lg hover:shadow-xl flex items-center justify-center text-center">
                                    <span>Masuk Portal Warga</span>
                                </a>
                            @else
                                <a href="/admin" class="w-full sm:w-auto bg-gold-500 hover:bg-gold-700 text-primary-900 font-bold text-xs sm:text-sm px-3 sm:px-6 py-3 rounded-xl transition shadow-lg hover:shadow-xl flex items-center justify-center text-center">
                                    <span>Dashboard Pengurus</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('citizen.login') }}" class="w-full sm:w-auto bg-gold-500 hover:bg-gold-700 text-primary-900 font-bold text-xs sm:text-sm px-3 sm:px-6 py-3 rounded-xl transition shadow-lg hover:shadow-xl flex items-center justify-center text-center">
                                <span>Masuk Portal Warga</span>
                            </a>
                        @endauth

                        <div class="relative w-full sm:w-auto" id="hero-layanan-wrapper">
                            <button type="button" id="hero-layanan-btn" class="w-full sm:w-auto bg-white/20 hover:bg-white/30 text-white font-bold text-xs sm:text-sm px-3 sm:px-6 py-3 rounded-xl backdrop-blur-md border border-white/30 transition flex items-center justify-center gap-1.5 sm:gap-2 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white/50 group text-center">
                                <span>Layanan Warga</span>
                                <svg id="hero-chevron" class="w-4 h-4 text-blue-200 group-hover:text-white transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Panel Layanan Hero (Disederhanakan & Anti-Terpotong) -->
                            <div id="hero-layanan-menu" class="hidden absolute right-0 sm:right-auto sm:left-0 top-full mt-2 w-64 sm:w-72 max-w-[calc(100vw-2rem)] bg-white text-gray-800 rounded-2xl shadow-2xl border border-border-soft p-2.5 z-50 animate-in fade-in slide-in-from-top-2 duration-150">
                                <div class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-text-muted border-b border-border-soft mb-1">
                                    <span>Pilih Layanan RT 15</span>
                                </div>

                                <div class="flex flex-col gap-0.5">
                                    @if(isset($citizenServices))
                                        @foreach($citizenServices as $svc)
                                            <a href="{{ route('public.layanan.detail', $svc['slug']) }}" class="px-3 py-2 rounded-xl hover:bg-blue-50 text-xs sm:text-sm font-semibold text-primary-900 hover:text-primary-700 transition flex items-center justify-between group">
                                                <span>{{ $svc['nama'] }}</span>
                                                <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-primary-700 group-hover:translate-x-0.5 transition shrink-0 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="mt-1 pt-1.5 border-t border-border-soft">
                                    <a href="{{ route('public.layanan.index') }}" class="flex items-center justify-center gap-1.5 text-xs font-bold text-primary-700 hover:bg-blue-50 py-1.5 rounded-xl transition group/all">
                                        <span>Buka Direktori Lengkap</span>
                                        <svg class="w-3.5 h-3.5 text-primary-700 group-hover/all:translate-x-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:mt-24 mt-8 flex flex-wrap items-center gap-y-2.5 gap-x-5 text-xs text-blue-200">
                        <span class="inline-flex items-center gap-1.5 shrink-0">
                            <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Aman (UU PDP)
                        </span>
                        <span class="inline-flex items-center gap-1.5 shrink-0">
                            <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Resmi RT 15 Pematang Sulur
                        </span>
                        <span class="inline-flex items-center gap-1.5 shrink-0">
                            <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Verifikasi QR Anti-Pemalsuan
                        </span>
                    </div>
                </div>

                <!-- Kolom Kanan: Ringkasan Lingkungan & Cek Surat Pengantar Terpisah -->
                <div class="lg:col-span-5 flex flex-col gap-4">
                    <!-- Card 1: Ringkasan Lingkungan -->
                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-xl text-white">
                        <div class="flex items-center justify-between pb-4 border-b border-white/10">
                            <div>
                                <div class="text-[11px] uppercase tracking-wider text-blue-200">Rukun Tetangga 15</div>
                                <div class="text-lg sm:text-xl font-bold">Ringkasan Lingkungan</div>
                            </div>
                        </div>

                        <!-- 4 Kartu Mini Metrik -->
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                                <div class="text-[11px] text-blue-200">Kartu Keluarga</div>
                                <div class="text-xl sm:text-2xl font-extrabold text-white mt-0.5">{{ $stats['total_kk'] }} KK</div>
                                <div class="text-[10px] text-green-300 mt-0.5">Terdata & Terverifikasi</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                                <div class="text-[11px] text-blue-200">Warga Terdaftar</div>
                                <div class="text-xl sm:text-2xl font-extrabold text-white mt-0.5">{{ $stats['total_warga'] }} Jiwa</div>
                                <div class="text-[10px] text-blue-200 mt-0.5">Penduduk Aktif</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                                <div class="text-[11px] text-blue-200">Bank Sampah</div>
                                <div class="text-xl sm:text-2xl font-extrabold text-green-400 mt-0.5">{{ number_format($stats['total_sampah_kg'], 0) }} Kg</div>
                                <div class="text-[10px] text-green-300 mt-0.5">Sampah Terpilah</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                                <div class="text-[11px] text-blue-200">UMKM Warga</div>
                                <div class="text-xl sm:text-2xl font-extrabold text-gold-400 mt-0.5">{{ $stats['total_umkm'] }} Usaha</div>
                                <div class="text-[10px] text-gold-300 mt-0.5">Katalog Produk</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Cek Verifikasi Surat Pengantar RT -->
                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-4 sm:p-5 shadow-lg text-white">
                        <div class="text-xs font-semibold text-white mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gold-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            <span class="text-sm font-bold">Punya Surat Pengantar RT?</span>
                        </div>
                        <p class="text-xs text-blue-100 mb-3 leading-relaxed">
                            Pindai QR Code pada surat atau masukkan nomor surat untuk memverifikasi keabsahannya.
                        </p>
                        <form action="{{ url('/verifikasi-surat') }}" method="GET" class="flex gap-2">
                            <input type="text" name="token" placeholder="Masukkan nomor atau token QR..." class="bg-white text-gray-800 text-xs px-3.5 py-2.5 rounded-xl flex-grow focus:outline-none focus:ring-2 focus:ring-gold-500 placeholder-gray-400 min-w-0">
                            <button type="submit" class="bg-gold-500 hover:bg-gold-700 text-primary-900 font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-sm hover:shadow shrink-0">
                                Cek
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3 Layanan Unggulan Warga (Direktori Lengkap Tersedia di Halaman Layanan) -->
    <section class="py-12 sm:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="layanan">
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16">
            <h2 class="text-xs uppercase tracking-widest text-primary-700 font-bold mb-2 sm:mb-3">Ekosistem Pelayanan</h2>
            <p class="text-2xl sm:text-4xl font-extrabold text-primary-900">Semua Kebutuhan Warga Ada di Sini</p>
            <p class="text-xs sm:text-base text-text-muted mt-2 sm:mt-3">Dirancang sederhana dan ramah untuk seluruh kelompok usia warga RT 15.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @if(isset($citizenServices))
                @foreach(array_slice($citizenServices, 0, 3) as $svc)
                    <div class="sim-card p-6 sm:p-8 flex flex-col justify-between hover:-translate-y-1.5 transition duration-300 shadow-sm hover:shadow-xl bg-white border border-border-soft rounded-2xl group">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-primary-900 group-hover:text-primary-700 transition mb-2 sm:mb-3">
                                {{ $svc['nama'] }}
                            </h3>
                            <p class="text-xs sm:text-sm text-text-muted leading-relaxed mb-4">
                                {{ $svc['deskripsi'] }}
                            </p>

                            {{-- Highlight Keunggulan: Disembunyikan di versi mobile agar ringkas --}}
                            <div class="hidden sm:flex p-3.5 rounded-xl bg-bg-soft border border-border-soft text-xs text-primary-900 mb-6 items-start gap-2.5">
                                <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="leading-snug">{{ $svc['keunggulan'] }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border-soft mt-auto">
                            <a href="{{ route('public.layanan.detail', $svc['slug']) }}" class="w-full text-center bg-primary-700 hover:bg-primary-900 text-white font-semibold text-xs sm:text-sm py-2.5 px-4 rounded-xl transition shadow-sm hover:shadow flex items-center justify-center gap-2 group/btn">
                                <span>Pelajari Layanan</span>
                                <svg class="w-3.5 h-3.5 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('public.layanan.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary-700 hover:text-primary-900 underline">
                <span>Buka Direktori Lengkap Layanan Warga</span>
            </a>
        </div>
    </section>

    <!-- Pengumuman & Agenda Terkini -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="pengumuman">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Pengumuman Warga -->
            <div class="lg:col-span-7">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-primary-900">Pengumuman RT 15</h2>
                        <p class="text-xs text-text-muted mt-1">Kabar penting dan maklumat pengurus untuk warga</p>
                    </div>
                </div>

                @if($pengumuman->isEmpty())
                    <div class="sim-card p-8 text-center text-text-muted">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        Belum ada pengumuman baru dari pengurus.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($pengumuman as $item)
                            <a href="{{ route('public.pengumuman.detail', $item->id) }}" class="sim-card p-5 sm:p-6 hover:border-primary-500 hover:shadow-md hover:-translate-y-0.5 transition block group cursor-pointer">
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-primary-700">
                                            {{ $item->target }}
                                        </span>
                                        @if($item->is_pinned)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                                📌 Penting
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-text-muted">{{ $item->created_at->format('d M Y') }}</span>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-primary-900 group-hover:text-primary-700 transition leading-snug mb-2">
                                    {{ $item->judul }}
                                </h3>
                                <p class="text-xs sm:text-sm text-text-muted line-clamp-2 leading-relaxed mb-3">
                                    {{ $item->isi }}
                                </p>
                                <div class="flex items-center gap-1 text-xs font-semibold text-primary-700 group-hover:text-primary-900 group-hover:translate-x-1 transition duration-200">
                                    <span>Baca Selengkapnya</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Agenda Terdekat -->
            <div class="lg:col-span-5">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-primary-900">Agenda Kegiatan</h2>
                        <p class="text-xs text-text-muted mt-1">Jadwal kegiatan lingkungan RT 15</p>
                    </div>
                </div>

                @if($agenda->isEmpty())
                    <div class="sim-card p-8 text-center text-text-muted">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Belum ada agenda terdekat yang dijadwalkan.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($agenda as $event)
                            <div class="sim-card p-5 flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl sim-gradient text-white flex flex-col items-center justify-center shrink-0">
                                    <span class="text-xs font-semibold leading-none">{{ $event->tanggal_mulai->format('M') }}</span>
                                    <span class="text-xl font-extrabold leading-none mt-1">{{ $event->tanggal_mulai->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-primary-900 text-sm">{{ $event->judul }}</h4>
                                    <p class="text-xs text-text-muted mt-1 flex items-center gap-1">
                                        📍 {{ $event->lokasi ?? 'Lingkungan RT 15' }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">
                                            {{ ucwords(str_replace('_', ' ', $event->kategori)) }}
                                        </span>
                                        @if($event->penyelenggara)
                                            <span class="text-[11px] text-text-muted">
                                                Oleh: <strong class="text-slate-700">{{ $event->penyelenggara }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const heroWrapper = document.getElementById('hero-layanan-wrapper');
        const heroBtn = document.getElementById('hero-layanan-btn');
        const heroMenu = document.getElementById('hero-layanan-menu');
        const heroChevron = document.getElementById('hero-chevron');

        if (heroBtn && heroMenu) {
            heroBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const isHidden = heroMenu.classList.contains('hidden');
                if (isHidden) {
                    heroMenu.classList.remove('hidden');
                    if (heroChevron) heroChevron.classList.add('rotate-180');

                    // Posisi dropdown: rata kanan pada mobile (<640px) agar pas dengan kolom kanan,
                    // dan periksa batas layar pada desktop
                    if (window.innerWidth < 640) {
                        heroMenu.classList.remove('left-0');
                        heroMenu.classList.add('right-0');
                    } else {
                        heroMenu.classList.remove('right-0');
                        heroMenu.classList.add('left-0');
                        const rect = heroMenu.getBoundingClientRect();
                        if (rect.right > window.innerWidth - 12) {
                            heroMenu.classList.remove('left-0');
                            heroMenu.classList.add('right-0');
                        }
                    }
                } else {
                    heroMenu.classList.add('hidden');
                    if (heroChevron) heroChevron.classList.remove('rotate-180');
                }
            });

            document.addEventListener('click', function (e) {
                if (heroWrapper && !heroWrapper.contains(e.target)) {
                    heroMenu.classList.add('hidden');
                    if (heroChevron) heroChevron.classList.remove('rotate-180');
                }
            });
        }
    });
</script>
@endpush
