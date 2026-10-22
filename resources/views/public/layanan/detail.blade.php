@extends('layouts.public')

@section('title', $service['nama'] . ' | Layanan Warga RT 15 Pematang Sulur')

@section('content')
    <!-- 1. HERO SECTION LAYANAN & BREADCRUMBS -->
    <section class="sim-gradient text-white relative overflow-hidden py-16 sm:py-24">
        <!-- Subtle background wave -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Navigasi Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-blue-200 mb-8 flex-wrap">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('public.layanan.index') }}" class="hover:text-white transition">Layanan Warga</a>
                <span>/</span>
                <span class="text-white font-semibold">{{ $service['short_name'] }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Info Utama Hero -->
                <div class="lg:col-span-8">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-gold-400 mb-6 border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        {{ $service['kategori'] }}
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight text-white mb-6">
                        {{ $service['nama'] }}
                    </h1>

                    <p class="text-base sm:text-lg text-blue-100 max-w-2xl leading-relaxed mb-8">
                        {{ $service['deskripsi'] }}
                    </p>

                    <!-- Tombol Aksi Utama Hero -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        <a href="{{ url($service['action_url']) }}" class="sim-btn-gold text-base shadow-lg hover:shadow-xl text-center">
                            <span>{{ $service['action_text'] }}</span>
                        </a>

                        <a href="#cara-menggunakan" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white/15 hover:bg-white/25 text-white font-semibold text-base rounded-xl backdrop-blur-sm border border-white/30 transition duration-200">
                            <span>Pelajari Cara Penggunaan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Kartu Visual / Keunggulan Hero -->
                <div class="lg:col-span-4">
                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 sm:p-8 shadow-2xl text-white">
                        <div class="text-xs uppercase tracking-wider text-gold-400 font-bold mb-2">Keunggulan Layanan</div>
                        <h3 class="text-xl font-bold text-white mb-4 leading-snug">
                            {{ $service['keunggulan'] }}
                        </h3>
                        <p class="text-xs text-blue-100 leading-relaxed mb-6">
                            Layanan resmi Kampung Digital Pinang Masak Lestari, Kelurahan Pematang Sulur, Kota Jambi.
                        </p>
                        <div class="pt-4 border-t border-white/15 flex items-center justify-between text-xs text-blue-200">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Siap Digunakan
                            </span>
                            <span class="text-white/80 font-medium">RT 15 Jambi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. PENJELASAN LENGKAP & TUJUAN LAYANAN -->
    <section class="py-16 sm:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <div class="lg:col-span-8 space-y-8">
                <!-- Penjelasan Detail -->
                <div class="bg-white border border-border-soft rounded-3xl p-8 sm:p-10 shadow-sm">
                    <h2 class="text-xs uppercase tracking-widest text-primary-700 font-bold mb-3">Tentang Layanan</h2>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-primary-900 mb-6">
                        Fungsi dan Tujuan {{ $service['nama'] }}
                    </h3>
                    <p class="text-base text-text-muted leading-relaxed mb-6">
                        {{ $service['detail_overview'] }}
                    </p>
                    <div class="p-5 rounded-2xl bg-blue-50/70 border border-blue-100 text-sm text-primary-900 leading-relaxed">
                        <strong class="font-bold block mb-1 text-primary-900">Misi Layanan:</strong>
                        {{ $service['tujuan'] }}
                    </div>
                </div>

                <!-- 3. MANFAAT LAYANAN -->
                <div class="bg-white border border-border-soft rounded-3xl p-8 sm:p-10 shadow-sm">
                    <h2 class="text-xs uppercase tracking-widest text-primary-700 font-bold mb-3">Nilai Tambah</h2>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-primary-900 mb-8">
                        Manfaat Utama Bagi Warga RT 15
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($service['manfaat'] as $item)
                            <div class="p-5 rounded-2xl bg-bg-soft border border-border-soft hover:border-primary-500/30 transition">
                                <h4 class="font-bold text-primary-900 text-base mb-2">{{ $item['judul'] }}</h4>
                                <p class="text-xs sm:text-sm text-text-muted leading-relaxed">{{ $item['deskripsi'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 4. CARA MENGGUNAKAN LAYANAN -->
                <div class="bg-white border border-border-soft rounded-3xl p-8 sm:p-10 shadow-sm scroll-mt-24" id="cara-menggunakan">
                    <h2 class="text-xs uppercase tracking-widest text-primary-700 font-bold mb-3">Panduan Langkah</h2>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-primary-900 mb-8">
                        Cara Menggunakan {{ $service['short_name'] }}
                    </h3>
                    <div class="space-y-6">
                        @foreach($service['langkah'] as $step)
                            <div class="flex items-start gap-4 sm:gap-6 p-4 sm:p-5 rounded-2xl hover:bg-bg-soft transition border border-transparent hover:border-border-soft">
                                <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-primary-700 text-white font-extrabold text-sm sm:text-base flex items-center justify-center shrink-0 shadow-sm">
                                    {{ $step['nomor'] }}
                                </span>
                                <div>
                                    <h4 class="text-base sm:text-lg font-bold text-primary-900 mb-1.5">
                                        {{ $step['judul'] }}
                                    </h4>
                                    <p class="text-sm text-text-muted leading-relaxed">
                                        {{ $step['deskripsi'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Catatan Khusus -->
                    @if(!empty($service['catatan_penting']))
                        <div class="mt-8 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs sm:text-sm text-amber-900 flex items-start gap-3">
                            <svg class="w-5 h-5 text-gold-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="leading-relaxed"><strong>Catatan Warga:</strong> {{ $service['catatan_penting'] }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Navigasi & Layanan Terkait -->
            <div class="lg:col-span-4 space-y-8 sticky top-28">
                <!-- 5. CTA CARD KANAN -->
                <div class="bg-primary-900 text-white rounded-3xl p-8 shadow-xl border-t-4 border-gold-500 text-center">
                    <span class="text-xs uppercase tracking-wider text-gold-400 font-bold block mb-2">Akses Langsung</span>
                    <h3 class="text-xl font-bold mb-3">Siap Menggunakan Layanan?</h3>
                    <p class="text-xs text-gray-300 leading-relaxed mb-6">
                        Gunakan akun warga Anda untuk mengakses modul ini dan menikmati kemudahan pelayanan digital RT 15.
                    </p>
                    <a href="{{ url($service['action_url']) }}" class="w-full inline-block bg-gold-500 hover:bg-gold-700 text-primary-900 font-bold text-sm py-3 px-5 rounded-xl transition shadow-md">
                        {{ $service['action_text'] }}
                    </a>
                </div>

                <!-- 6. NAVIGASI KEMBALI & LAYANAN LAINNYA -->
                <div class="bg-white border border-border-soft rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-border-soft">
                        <h4 class="text-sm font-bold text-primary-900 uppercase tracking-wider">Layanan Lainnya</h4>
                        <a href="{{ route('public.layanan.index') }}" class="text-xs font-semibold text-primary-700 hover:text-primary-900">
                            Semua
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($otherServices as $other)
                            <a href="{{ route('public.layanan.detail', $other['slug']) }}" class="block p-3 rounded-xl hover:bg-bg-soft transition border border-transparent hover:border-border-soft group">
                                <div class="text-xs font-bold text-primary-900 group-hover:text-primary-700 transition">
                                    {{ $other['nama'] }}
                                </div>
                                <div class="text-[11px] text-text-muted truncate mt-0.5">
                                    {{ $other['deskripsi'] }}
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Tombol Kembali ke Direktori -->
                    <div class="mt-6 pt-4 border-t border-border-soft">
                        <a href="{{ route('public.layanan.index') }}" class="w-full text-center inline-flex items-center justify-center gap-2 border border-border-soft hover:bg-bg-soft text-text-muted hover:text-primary-900 font-semibold text-xs py-2.5 px-4 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Kembali ke Direktori Layanan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const smoothLinks = document.querySelectorAll('a[href^="#"]');
        smoothLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId && targetId !== '#') {
                    const targetEl = document.querySelector(targetId);
                    if (targetEl) {
                        e.preventDefault();
                        targetEl.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        if (history.pushState) {
                            history.pushState(null, null, targetId);
                        } else {
                            location.hash = targetId;
                        }
                    }
                }
            });
        });
    });
</script>
@endpush
