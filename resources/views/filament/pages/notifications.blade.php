<x-filament-panels::page>
    <div class="space-y-4" wire:poll.15s>
        {{-- ====================================================================
             1. HEADER HALAMAN: SUBTITLE, PANDUAN (!), JUDUL & TOMBOL AKSI
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                {{-- Kategori Subtitle & Tombol Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>KOMUNIKASI WARGA RT 15</span>
                    <x-page-guide 
                        title="Pusat Notifikasi Pengurus"
                        category="KOMUNIKASI WARGA RT 15"
                        description="Halaman terpadu untuk memantau permohonan dan aktivitas warga RT 15, terutama pengajuan surat pengantar baru yang membutuhkan tindak lanjut pengurus."
                        :steps="[
                            ['title' => 'Filter Notifikasi', 'desc' => 'Pilih filter Semua, Belum Dibaca, atau Surat Pengantar untuk menyaring daftar permohonan.'],
                            ['title' => 'Tindak Lanjuti Permohonan', 'desc' => 'Klik tombol \'Tindak Lanjuti\' pada kartu notifikasi untuk langsung membuka halaman permohonan surat terkait.'],
                            ['title' => 'Tandai Semua Dibaca', 'desc' => 'Klik tombol \'Tandai Semua Telah Dibaca\' untuk menghapus tanda belum dibaca sekaligus.']
                        ]"
                        role="Seluruh Pengurus RT 15"
                        :tips="[
                            'Halaman ini otomatis melakukan sinkronisasi data baru setiap 15 detik.',
                            'Badge merah pada tab \'Belum Dibaca\' menunjukkan jumlah notifikasi yang belum Anda buka.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman & Indikator Belum Dibaca --}}
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Pusat Notifikasi
                    </h1>
                    @if ($this->unreadCount > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-600 animate-pulse"></span>
                            {{ $this->unreadCount }} Belum Dibaca
                        </span>
                    @endif
                </div>
            </div>

            {{-- Tombol Aksi: Tandai Semua Telah Dibaca --}}
            <div class="flex items-center gap-2 shrink-0">
                <button 
                    type="button"
                    wire:click="markAllAsRead"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-[#0B3A85] hover:bg-[#08295E] rounded-xl shadow-xs transition duration-150 cursor-pointer disabled:opacity-50">
                    <span>Tandai Semua Telah Dibaca</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. FILTER TABS (SEMUA, BELUM DIBACA, SURAT PENGANTAR) & AKSI PEMBERSIHAN
             ==================================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-2.5 sm:p-3 shadow-2xs flex flex-wrap items-center justify-between gap-2.5">
            {{-- Tab Filter: Semua, Belum Dibaca, Surat Pengantar --}}
            <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none">
                {{-- 1. Semua --}}
                <button 
                    type="button"
                    wire:click="setTab('all')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $this->tab === 'all' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Semua
                </button>

                {{-- 2. Belum Dibaca --}}
                <button 
                    type="button"
                    wire:click="setTab('unread')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer flex items-center gap-1.5 {{ $this->tab === 'unread' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>Belum Dibaca</span>
                    @if ($this->unreadCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $this->tab === 'unread' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-700 font-black' }}">
                            {{ $this->unreadCount }}
                        </span>
                    @endif
                </button>

                {{-- 3. Surat Pengantar --}}
                <button 
                    type="button"
                    wire:click="setTab('letter')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $this->tab === 'letter' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Surat Pengantar
                </button>
            </div>

            {{-- Opsi Pembersihan Riwayat (Jika ada notifikasi yang telah dibaca) --}}
            @if ($this->hasReadNotifications)
                <button 
                    type="button"
                    wire:click="deleteAllRead"
                    wire:confirm="Apakah Anda yakin ingin membersihkan riwayat notifikasi yang sudah dibaca?"
                    wire:loading.attr="disabled"
                    class="text-xs font-semibold text-slate-500 hover:text-rose-600 transition cursor-pointer flex items-center gap-1 self-center px-2 py-1 rounded-lg hover:bg-rose-50"
                    title="Hapus riwayat notifikasi yang telah dibaca">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Bersihkan Terbaca</span>
                </button>
            @endif
        </div>

        {{-- ====================================================================
             3. DAFTAR NOTIFIKASI (KARTU NOTIFIKASI RESPONSIF)
             ==================================================================== --}}
        @php
            $notifications = $this->notifications;
        @endphp

        <div class="space-y-2.5">
            @forelse ($notifications as $item)
                @php
                    $meta = $this->getCategoryMeta($item);
                    $isUnread = !$item->is_read;
                @endphp

                <div class="p-4 sm:p-4.5 rounded-2xl border transition duration-150 {{ $isUnread ? 'bg-white border-slate-300 shadow-xs border-l-4 border-l-[#0B3A85]' : 'bg-slate-50/70 border-slate-200/70 opacity-90 hover:opacity-100 hover:bg-white' }}">
                    <div class="flex items-start gap-3.5">
                        {{-- Icon Kategori Aktivitas --}}
                        <div class="w-10 h-10 rounded-xl {{ $meta['icon_bg'] }} flex items-center justify-center shrink-0 mt-0.5 shadow-2xs">
                            @if ($meta['category'] === 'letter')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @elseif ($meta['category'] === 'complaint')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            @elseif ($meta['category'] === 'payment')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            @elseif ($meta['category'] === 'guest')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Isi Konten Notifikasi --}}
                        <div class="flex-1 min-w-0">
                            {{-- Baris Meta: Badge Kategori, Waktu & Indikator Status --}}
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $meta['badge'] }}">
                                    {{ $meta['name'] }}
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

                            {{-- Judul Notifikasi (Klik untuk Buka) --}}
                            <h3 
                                wire:click="openNotification({{ $item->id }})"
                                class="text-sm font-bold text-[#0F1F3D] hover:text-[#0B3A85] transition cursor-pointer leading-snug">
                                {{ $item->judul }}
                            </h3>

                            {{-- Deskripsi/Isi Notifikasi --}}
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                {{ $item->isi }}
                            </p>

                            {{-- Baris Aksi Tindak Lanjut --}}
                            <div class="flex flex-wrap items-center gap-3 mt-3 pt-2.5 border-t border-slate-100">
                                {{-- Tombol Tindak Lanjuti --}}
                                <button 
                                    type="button"
                                    wire:click="openNotification({{ $item->id }})"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B3A85] hover:text-[#08295E] hover:underline cursor-pointer">
                                    <span>Tindak Lanjuti</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>

                                <span class="text-slate-300">&bull;</span>

                                {{-- Toggle Tandai Dibaca / Belum Dibaca --}}
                                @if ($isUnread)
                                    <button 
                                        type="button"
                                        wire:click="markAsRead({{ $item->id }})"
                                        class="text-[11px] font-medium text-slate-500 hover:text-slate-800 transition cursor-pointer">
                                        Tandai Telah Dibaca
                                    </button>
                                @else
                                    <button 
                                        type="button"
                                        wire:click="markAsUnread({{ $item->id }})"
                                        class="text-[11px] font-medium text-slate-500 hover:text-slate-800 transition cursor-pointer">
                                        Tandai Belum Dibaca
                                    </button>
                                @endif

                                <span class="text-slate-300">&bull;</span>

                                {{-- Hapus Notifikasi --}}
                                <button 
                                    type="button"
                                    wire:click="deleteNotification({{ $item->id }})"
                                    wire:confirm="Hapus notifikasi ini?"
                                    class="text-[11px] font-medium text-rose-500 hover:text-rose-700 hover:underline transition cursor-pointer">
                                    Hapus
                                </button>
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
                        @if ($this->tab === 'unread')
                            Semua permohonan dan notifikasi pengurus telah Anda baca.
                        @elseif ($this->tab === 'letter')
                            Saat ini belum ada pengajuan surat pengantar dari warga.
                        @else
                            Belum ada notifikasi atau aktivitas baru yang memerlukan perhatian Anda.
                        @endif
                    </p>
                    @if ($this->tab !== 'all')
                        <div class="mt-4">
                            <button 
                                type="button"
                                wire:click="setTab('all')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold text-[#0B3A85] bg-blue-50 hover:bg-blue-100 rounded-xl transition cursor-pointer">
                                Tampilkan Semua Notifikasi
                            </button>
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
</x-filament-panels::page>
