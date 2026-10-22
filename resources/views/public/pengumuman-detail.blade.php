@extends('layouts.public')

@section('title', $announcement->judul . ' - Pengumuman RT 15 | SIM-LiBas')

@section('content')
    <!-- 1. HERO HEADER PENGUMUMAN -->
    <section class="sim-gradient text-white relative overflow-hidden py-8 sm:py-14">
        <!-- Subtle background wave -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Navigasi Path Breadcrumb [Beranda / Pengumuman / Detail] -->
            <nav class="flex items-center gap-2 text-xs text-blue-200 mb-4 sm:mb-6 flex-wrap">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span class="text-blue-300">/</span>
                <a href="{{ route('public.pengumuman.index') }}" class="hover:text-white transition">Pengumuman</a>
                <span class="text-blue-300">/</span>
                <span class="text-white font-bold">Detail</span>
            </nav>

            <!-- Header Title & Badges -->
            <div class="max-w-3xl">
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-3">
                    {{ $announcement->judul }}
                </h1>

                @if($announcement->is_pinned)
                    <div class="mb-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-400 text-primary-900 shadow-xs">
                            <span>📌 Penting</span>
                        </span>
                    </div>
                @endif

                <div class="hidden sm:flex items-center gap-2 text-xs sm:text-sm text-blue-100 flex-wrap">
                    <span>Maklumat Resmi Pengurus RT 15 Pematang Sulur</span>
                    <span class="text-blue-300">•</span>
                    <span>Kampung Digital Pinang Masak Lestari</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. KONTEN DETAIL PENGUMUMAN -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- KOLOM KIRI (Satu Kartu Tunggal: Uraian Pengumuman Lengkap) - 8 Kolom -->
            <div class="lg:col-span-8 space-y-6">

                <!-- KARTU UTAMA: URAIAN PENGUMUMAN LENGKAP -->
                <div class="bg-gradient-to-br from-amber-50/70 via-orange-50/30 to-white border border-amber-200/90 rounded-3xl p-6 sm:p-9 shadow-sm relative overflow-hidden">
                    <!-- Subtle background watermark icon -->
                    <div class="absolute -right-4 -bottom-4 opacity-10 pointer-events-none text-amber-900">
                        <svg class="w-36 h-36" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                    </div>

                    <!-- Header Card: Uraian Pengumuman Lengkap -->
                    <div class="flex items-center gap-2 mb-5">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500 text-white shadow-2xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Uraian Pengumuman Lengkap</span>
                        </span>
                    </div>

                    <!-- Isi Lengkap Pengumuman Utuh dengan Tipografi Nyaman -->
                    <div class="prose max-w-none text-sm sm:text-base text-slate-800 leading-relaxed whitespace-pre-line mb-6 font-medium">
{{ $announcement->isi }}
                    </div>

                    <!-- Quick Meta Fact Grid (Sasaran & Tanggal) -->
                    <div class="grid grid-cols-2 gap-3 pt-5 border-t border-amber-200/70 text-xs">
                        <div class="bg-white/85 backdrop-blur-xs rounded-xl p-3 border border-amber-200/60 shadow-2xs">
                            <span class="text-[10px] uppercase font-bold text-amber-800/80 block">Sasaran Maklumat</span>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm">
                                {{ $announcement->target === 'semua' ? 'Semua Warga RT 15' : 'Warga ' . ucfirst($announcement->target) }}
                            </span>
                        </div>
                        <div class="bg-white/85 backdrop-blur-xs rounded-xl p-3 border border-amber-200/60 shadow-2xs">
                            <span class="text-[10px] uppercase font-bold text-amber-800/80 block">Tanggal Terbit</span>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm">
                                {{ $announcement->created_at->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Lampiran Berkas Jika Ada -->
                    @if($announcement->lampiran_path)
                        <div class="mt-6 pt-5 border-t border-amber-200/70">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2.5">Lampiran Berkas Pendukung</h3>
                            <a href="{{ Storage::url($announcement->lampiran_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-3 p-4 rounded-2xl bg-white/90 hover:bg-white border border-amber-200/80 hover:border-amber-400 transition duration-200 group shadow-2xs">
                                <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="text-xs font-bold text-primary-900 group-hover:text-primary-700 transition">Lihat / Unduh Dokumen Lampiran</div>
                                    <div class="text-[11px] text-text-muted mt-0.5">Format file dokumen resmi RT 15</div>
                                </div>
                            </a>
                        </div>
                    @endif

                    <!-- Bagikan ke Grup WhatsApp RT -->
                    <div class="mt-6 pt-5 border-t border-amber-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="text-xs text-text-muted">
                            <span class="font-medium">Ingin meneruskan maklumat ini ke warga lain?</span>
                        </div>
                        @php
                            $shareText = "Maklumat Pengumuman RT 15 Pematang Sulur:\n*" . $announcement->judul . "*\n\n" . \Illuminate\Support\Str::limit($announcement->isi, 120) . "\n\nBaca selengkapnya di: " . url()->current();
                        @endphp
                        <a href="https://wa.me/?text={{ urlencode($shareText) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-xs hover:shadow-md">
                            @include('components.umkm-social-icon', ['platform' => 'whatsapp', 'class' => 'w-4 h-4 fill-white'])
                            <span>Bagikan ke Grup WhatsApp RT</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (Sidebar Kontak Pengurus & Pengumuman Lainnya) - 4 Kolom -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Kartu Informasi Sekretariat RT -->
                <div class="bg-white border border-border-soft rounded-3xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-primary-900 mb-3">Sekretariat RT 15</h3>
                    <p class="text-xs text-text-muted leading-relaxed mb-4">
                        Ada pertanyaan atau butuh klarifikasi mengenai pengumuman ini? Pengurus RT siap membantu warga melalui kontak berikut:
                    </p>
                    <div class="space-y-2.5 text-xs text-slate-700">
                        <div class="flex items-start gap-2">
                            <span class="font-semibold text-primary-900 shrink-0 w-20">Alamat:</span>
                            <span>Jl. Pinang Masak No. 15, RT 15 Pematang Sulur, Kota Jambi</span>
                        </div>
                        @if($setting?->telepon_ketua_rt)
                            <div class="flex items-start gap-2">
                                <span class="font-semibold text-primary-900 shrink-0 w-20">Kontak RT:</span>
                                <span>{{ $setting->telepon_ketua_rt }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 pt-4 border-t border-border-soft">
                        <a href="{{ route('public.contact') }}" class="w-full text-center bg-blue-50 hover:bg-blue-100 text-primary-700 font-bold text-xs py-2 px-3 rounded-xl transition flex items-center justify-center gap-1.5">
                            <span>Buka Kontak & Sekretariat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Kartu Pengumuman Lainnya -->
                @if(isset($otherAnnouncements) && $otherAnnouncements->isNotEmpty())
                    <div class="bg-white border border-border-soft rounded-3xl p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-primary-900 mb-4">
                            Pengumuman Lainnya
                        </h3>

                        <div class="space-y-3.5">
                            @foreach($otherAnnouncements as $other)
                                <a href="{{ route('public.pengumuman.detail', $other->id) }}" class="block p-3 rounded-xl hover:bg-blue-50/70 border border-slate-100 transition group">
                                    <div class="text-[10px] text-text-muted mb-1">
                                        {{ $other->created_at->format('d M Y') }}
                                    </div>
                                    <h4 class="text-xs font-bold text-primary-900 group-hover:text-primary-700 transition leading-snug line-clamp-2">
                                        {{ $other->judul }}
                                    </h4>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </section>
@endsection
