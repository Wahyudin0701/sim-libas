@extends('layouts.citizen')

@section('title', 'Pusat Notifikasi | SIM-LiBas RT 15')
@section('page_title', 'Notifikasi')

@section('content')
<div class="space-y-4">
    {{-- ====================================================================
         1. HEADER HALAMAN: SUBTITLE, JUDUL, INDIKATOR & TOMBOL AKSI
         ==================================================================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <div class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                PEMBERITAHUAN AKTIVITAS RT 15
            </div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                    Pusat Notifikasi
                </h1>
                @if ($unreadCount > 0)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                        <span class="w-2 h-2 rounded-full bg-rose-600 animate-pulse"></span>
                        {{ $unreadCount }} Belum Dibaca
                    </span>
                @endif
            </div>
        </div>

        {{-- Tombol Aksi di Pojok Kanan Atas: Tandai Semua Telah Dibaca & Tombol Kembali --}}
        <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 flex-wrap sm:flex-nowrap">
            @if ($unreadCount > 0)
                <form action="{{ route('warga.notifications.read-all') }}" method="POST">
                    @csrf
                    <button 
                        type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-[#0B3A85] hover:bg-[#08295E] rounded-xl shadow-xs transition duration-150 cursor-pointer">
                        <span>Tandai Semua Telah Dibaca</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('warga.dashboard') }}"
               id="warga-page-back-btn"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#0F1F3D] border border-[#CBD5E1] hover:bg-[#F8FAFC] hover:border-[#0B3A85] hover:text-[#0B3A85] transition-all duration-150 text-xs font-bold shadow-2xs hover:shadow-xs cursor-pointer group active:scale-95"
               title="Kembali ke halaman sebelumnya atau Beranda">
                <svg class="w-3.5 h-3.5 text-[#5B6B82] group-hover:text-[#0B3A85] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    {{-- ====================================================================
         2. FILTER TABS (SEMUA, BELUM DIBACA, SURAT PENGANTAR) & AKSI PEMBERSIHAN
         ==================================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-2.5 sm:p-3 shadow-2xs flex flex-wrap items-center justify-between gap-2.5">
        {{-- Tab Filter: Semua, Belum Dibaca, Surat Pengantar --}}
        <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none">
            {{-- 1. Semua --}}
            <a href="{{ route('warga.notifications', ['tab' => 'all']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $tab === 'all' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Semua
            </a>

            {{-- 2. Belum Dibaca --}}
            <a href="{{ route('warga.notifications', ['tab' => 'unread']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer flex items-center gap-1.5 {{ $tab === 'unread' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Belum Dibaca</span>
                @if ($unreadCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'unread' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-700 font-black' }}">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>

            {{-- 3. Surat Pengantar --}}
            <a href="{{ route('warga.notifications', ['tab' => 'letter']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $tab === 'letter' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Surat Pengantar
            </a>
        </div>

        {{-- Opsi Pembersihan Riwayat (Jika ada notifikasi yang telah dibaca) --}}
        @if ($hasReadNotifications)
            <form action="{{ route('warga.notifications.clear-read') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membersihkan riwayat notifikasi yang sudah dibaca?');">
                @csrf
                <button 
                    type="submit"
                    class="text-xs font-semibold text-slate-500 hover:text-rose-600 transition cursor-pointer flex items-center gap-1 self-center px-2 py-1 rounded-lg hover:bg-rose-50"
                    title="Hapus riwayat notifikasi yang telah dibaca">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Bersihkan Terbaca</span>
                </button>
            </form>
        @endif
    </div>

    {{-- ====================================================================
         3. DAFTAR NOTIFIKASI WARGA
         ==================================================================== --}}
    <div class="space-y-2.5">
        @forelse ($notifications as $item)
            @php
                $isUnread = !$item->is_read;
                $urlOrTitle = strtolower(($item->url ?? '') . ' ' . $item->judul);

                if (str_contains($urlOrTitle, 'surat') || str_contains($urlOrTitle, 'letter')) {
                    $categoryName = 'Surat Pengantar';
                    $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                    $iconBg = 'bg-blue-100 text-[#0B3A85]';
                    $categoryType = 'letter';
                } elseif (str_contains($urlOrTitle, 'iuran') || str_contains($urlOrTitle, 'pembayaran') || str_contains($urlOrTitle, 'invoice')) {
                    $categoryName = 'Iuran & Tagihan';
                    $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    $iconBg = 'bg-emerald-100 text-emerald-700';
                    $categoryType = 'payment';
                } elseif (str_contains($urlOrTitle, 'pengaduan') || str_contains($urlOrTitle, 'complaint')) {
                    $categoryName = 'Pengaduan Warga';
                    $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                    $iconBg = 'bg-amber-100 text-amber-700';
                    $categoryType = 'complaint';
                } else {
                    $categoryName = 'Informasi RT';
                    $badgeClass = 'bg-slate-50 text-slate-700 border-slate-200';
                    $iconBg = 'bg-slate-100 text-slate-600';
                    $categoryType = 'general';
                }
            @endphp

            <div class="p-4 sm:p-4.5 rounded-2xl border transition duration-150 {{ $isUnread ? 'bg-white border-slate-300 shadow-xs border-l-4 border-l-[#0B3A85]' : 'bg-slate-50/70 border-slate-200/70 opacity-90 hover:opacity-100 hover:bg-white' }}">
                <div class="flex items-start gap-3.5">
                    {{-- Icon Kategori Aktivitas --}}
                    <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0 mt-0.5 shadow-2xs">
                        @if ($categoryType === 'letter')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @elseif ($categoryType === 'payment')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        @elseif ($categoryType === 'complaint')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Isi Konten Notifikasi --}}
                    <div class="flex-1 min-w-0">
                        {{-- Baris Meta: Badge Kategori, Waktu & Indikator Status --}}
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $badgeClass }}">
                                {{ $categoryName }}
                            </span>

                            @if ($isUnread)
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-black bg-rose-600 text-white">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                    Baru
                                </span>
                            @endif

                            <span class="text-[11px] text-slate-400 font-medium">
                                &bull; {{ $item->created_at->diffForHumans() }}
                            </span>

                            <span class="hidden sm:inline text-[10px] text-slate-400">
                                ({{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB)
                            </span>
                        </div>

                        {{-- Judul Notifikasi --}}
                        <a href="{{ route('warga.notifications.open', $item) }}"
                           class="text-sm font-bold text-[#0F1F3D] hover:text-[#0B3A85] transition leading-snug block">
                            {{ $item->judul }}
                        </a>

                        {{-- Deskripsi/Isi Notifikasi --}}
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            {{ $item->isi }}
                        </p>

                        {{-- Baris Aksi Tindak Lanjut --}}
                        <div class="flex flex-wrap items-center gap-3 mt-3 pt-2.5 border-t border-slate-100">
                            {{-- Tombol Tindak Lanjuti --}}
                            <a href="{{ route('warga.notifications.open', $item) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B3A85] hover:text-[#08295E] hover:underline cursor-pointer">
                                <span>Tindak Lanjuti</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>

                            @if ($isUnread)
                                <span class="text-slate-300">&bull;</span>
                                <form action="{{ route('warga.notifications.read', $item) }}" method="POST" class="inline">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="text-[11px] font-medium text-slate-500 hover:text-slate-800 transition cursor-pointer">
                                        Tandai Telah Dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- State Kosong Ketika Tidak Ada Notifikasi --}}
            <div class="p-10 sm:p-14 bg-white rounded-2xl border border-slate-200/80 text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h4 class="text-sm font-bold text-[#0F1F3D]">Tidak Ada Notifikasi</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                    @if ($tab === 'unread')
                        Semua permohonan dan notifikasi aktivitas warga telah Anda baca.
                    @elseif ($tab === 'letter')
                        Saat ini belum ada pembaruan status terkait surat pengantar Anda.
                    @else
                        Belum ada notifikasi atau pemberitahuan baru yang memerlukan perhatian Anda.
                    @endif
                </p>
                @if ($tab !== 'all')
                    <div class="mt-4">
                        <a href="{{ route('warga.notifications', ['tab' => 'all']) }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold text-[#0B3A85] bg-blue-50 hover:bg-blue-100 rounded-xl transition cursor-pointer">
                            Tampilkan Semua Notifikasi
                        </a>
                    </div>
                @endif
            </div>
        @endforelse
    </div>

    {{-- ====================================================================
         4. PAGINATION CONTROLS
         ==================================================================== --}}
    @if ($notifications->hasPages())
        <div class="pt-2">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var defaultUrl = "{{ route('warga.dashboard') }}";
        var storageKey = 'simlibas_warga_notif_referrer';

        if (document.referrer) {
            try {
                var refUrl = new URL(document.referrer);
                if (refUrl.host === window.location.host && !refUrl.pathname.includes('/notifikasi')) {
                    sessionStorage.setItem(storageKey, document.referrer);
                }
            } catch (e) {}
        }

        function handleBackNavigation(e) {
            if (e) e.preventDefault();
            var target = sessionStorage.getItem(storageKey);
            if (target && target !== window.location.href) {
                sessionStorage.removeItem(storageKey);
                window.location.href = target;
            } else if (window.history.length > 1 && document.referrer && document.referrer.indexOf(window.location.host) !== -1 && !document.referrer.includes('/notifikasi')) {
                window.history.back();
            } else {
                window.location.href = defaultUrl;
            }
        }

        var topbarBackBtn = document.getElementById('citizen-topbar-back-btn');
        if (topbarBackBtn) {
            topbarBackBtn.addEventListener('click', handleBackNavigation);
        }

        var pageBackBtn = document.getElementById('warga-page-back-btn');
        if (pageBackBtn) {
            pageBackBtn.addEventListener('click', handleBackNavigation);
        }
    })();
</script>
@endpush
