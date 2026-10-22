@extends('layouts.public')

@section('title', $event->judul . ' - Agenda Kegiatan RT 15 | SIM-LiBas')

@section('content')
    <!-- 1. HERO HEADER AGENDA KEGIATAN -->
    <section class="sim-gradient text-white relative overflow-hidden py-8 sm:py-14">
        <!-- Subtle background wave -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Navigasi Path Breadcrumb [Beranda / Agenda / Detail] -->
            <nav class="flex items-center gap-2 text-xs text-blue-200 mb-4 sm:mb-6 flex-wrap">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span class="text-blue-300">/</span>
                <a href="{{ route('public.agenda.index') }}" class="hover:text-white transition">Agenda</a>
                <span class="text-blue-300">/</span>
                <span class="text-white font-bold">Detail</span>
            </nav>

            <!-- Header Title & Badges -->
            <div class="max-w-3xl">
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-3">
                    {{ $event->judul }}
                </h1>

                <div class="mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-white/20 text-white backdrop-blur-md border border-white/25">
                        <span>🏷️ {{ ucwords(str_replace('_', ' ', $event->kategori)) }}</span>
                    </span>
                </div>

                <div class="hidden sm:flex items-center gap-2 text-xs sm:text-sm text-blue-100 flex-wrap">
                    <span>Agenda Resmi Warga RT 15 Pematang Sulur</span>
                    <span class="text-blue-300">•</span>
                    <span>Kampung Digital Pinang Masak Lestari</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. KONTEN DETAIL AGENDA -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- KOLOM KIRI (Uraian Agenda Lengkap) - 8 Kolom -->
            <div class="lg:col-span-8 space-y-6">

                <!-- KARTU UTAMA: DETAIL AGENDA -->
                <div class="bg-gradient-to-br from-blue-50/70 via-indigo-50/30 to-white border border-blue-200/90 rounded-3xl p-6 sm:p-9 shadow-sm relative overflow-hidden">
                    <!-- Subtle background watermark icon -->
                    <div class="absolute -right-4 -bottom-4 opacity-10 pointer-events-none text-primary-900">
                        <svg class="w-36 h-36" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                        </svg>
                    </div>

                    <!-- Header Card: Uraian Agenda -->
                    <div class="flex items-center gap-2 mb-5">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-primary-700 text-white shadow-2xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Uraian Agenda Kegiatan</span>
                        </span>
                    </div>

                    <!-- Isi Lengkap Deskripsi Agenda -->
                    <div class="prose max-w-none text-sm sm:text-base text-slate-800 leading-relaxed whitespace-pre-line mb-6 font-medium">
{{ $event->deskripsi ?: 'Tidak ada uraian deskripsi tambahan untuk agenda ini. Warga diharapkan hadir tepat waktu sesuai jadwal dan lokasi yang telah ditentukan.' }}
                    </div>

                    <!-- Quick Meta Fact Grid (Waktu, Tempat, Penyelenggara, Kategori) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-5 border-t border-blue-200/70 text-xs">
                        <div class="bg-white/85 backdrop-blur-xs rounded-xl p-3.5 border border-blue-200/60 shadow-2xs">
                            <span class="text-[10px] uppercase font-bold text-primary-800/80 block">🗓️ Waktu Pelaksanaan</span>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm block mt-0.5">
                                {{ $event->tanggal_mulai->translatedFormat('l, d F Y') }}
                            </span>
                            <span class="text-[11px] text-text-muted mt-0.5 block">
                                Pukul {{ $event->tanggal_mulai->format('H:i') }} WIB {{ $event->tanggal_selesai ? 's/d ' . $event->tanggal_selesai->format('H:i') . ' WIB' : 's/d Selesai' }}
                            </span>
                        </div>

                        <div class="bg-white/85 backdrop-blur-xs rounded-xl p-3.5 border border-blue-200/60 shadow-2xs">
                            <span class="text-[10px] uppercase font-bold text-primary-800/80 block">📍 Lokasi Kegiatan</span>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm block mt-0.5">
                                {{ $event->lokasi ?? 'Lingkungan RT 15 Pematang Sulur' }}
                            </span>
                            <span class="text-[11px] text-text-muted mt-0.5 block">
                                Kawasan RT 15 Pematang Sulur, Telanaipura
                            </span>
                        </div>

                        <div class="bg-white/85 backdrop-blur-xs rounded-xl p-3.5 border border-blue-200/60 shadow-2xs">
                            <span class="text-[10px] uppercase font-bold text-primary-800/80 block">👥 Penyelenggara</span>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm block mt-0.5">
                                {{ $event->penyelenggara ?? 'Pengurus RT 15' }}
                            </span>
                            <span class="text-[11px] text-text-muted mt-0.5 block">
                                Inisiator / Panitia Pelaksana
                            </span>
                        </div>

                        <div class="bg-white/85 backdrop-blur-xs rounded-xl p-3.5 border border-blue-200/60 shadow-2xs">
                            <span class="text-[10px] uppercase font-bold text-primary-800/80 block">🏷️ Sifat / Kategori Acara</span>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm block mt-0.5">
                                {{ ucwords(str_replace('_', ' ', $event->kategori)) }}
                            </span>
                            <span class="text-[11px] text-text-muted mt-0.5 block">
                                {{ $event->is_recurring ? 'Kegiatan Rutin Berkala' : 'Kegiatan Terjadwal' }}
                            </span>
                        </div>
                    </div>

                    <!-- Bagikan ke Grup WhatsApp RT -->
                    <div class="mt-6 pt-5 border-t border-blue-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="text-xs text-text-muted">
                            <span class="font-medium">Ingin meneruskan agenda ini ke tetangga atau keluarga?</span>
                        </div>
                        @php
                            $shareText = "Agenda Warga RT 15 Pematang Sulur:\n*" . $event->judul . "*\n\n🗓️ Waktu: " . $event->tanggal_mulai->translatedFormat('l, d F Y (H:i') . " WIB)\n📍 Lokasi: " . ($event->lokasi ?? 'Lingkungan RT 15') . "\n👥 Penyelenggara: " . ($event->penyelenggara ?? 'Pengurus RT 15') . "\n\nDetail lengkap: " . url()->current();
                        @endphp
                        <a href="https://wa.me/?text={{ urlencode($shareText) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-xs hover:shadow-md">
                            @include('components.umkm-social-icon', ['platform' => 'whatsapp', 'class' => 'w-4 h-4 fill-white'])
                            <span>Bagikan ke Grup WhatsApp RT</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (Sidebar Kontak Pengurus & Agenda Lainnya) - 4 Kolom -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Kartu Informasi Sekretariat RT -->
                <div class="bg-white border border-border-soft rounded-3xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-primary-900 mb-3">Sekretariat RT 15</h3>
                    <p class="text-xs text-text-muted leading-relaxed mb-4">
                        Ada pertanyaan mengenai jadwal atau partisipasi dalam kegiatan ini? Pengurus RT siap membantu warga melalui kontak berikut:
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

                <!-- Kartu Agenda Lainnya -->
                @if(isset($otherEvents) && $otherEvents->isNotEmpty())
                    <div class="bg-white border border-border-soft rounded-3xl p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-primary-900 mb-4">
                            Agenda Lainnya
                        </h3>

                        <div class="space-y-3.5">
                            @foreach($otherEvents as $other)
                                <a href="{{ route('public.agenda.detail', $other->id) }}" class="block p-3 rounded-xl hover:bg-blue-50/70 border border-slate-100 transition group">
                                    <div class="text-[10px] text-text-muted mb-1">
                                        {{ $other->tanggal_mulai->translatedFormat('d M Y, H:i') }} WIB
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
