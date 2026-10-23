<x-filament-panels::page>
    <div class="space-y-5" wire:poll.15s>
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
                        description="Halaman terpadu untuk memantau seluruh pemberitahuan dan permohonan aktivitas warga RT 15, meliputi pengajuan surat pengantar, aspirasi & aduan, konfirmasi pembayaran iuran, serta laporan tamu dan keamanan lingkungan."
                        :steps="[
                            ['title' => 'Filter Tab Kategori', 'desc' => 'Gunakan tab kategori di atas daftar untuk memilah notifikasi permohonan surat, pengaduan, konfirmasi iuran, atau laporan keamanan.'],
                            ['title' => 'Tindak Lanjuti Permohonan', 'desc' => 'Klik tombol \'Tindak Lanjuti\' pada kartu notifikasi untuk langsung membuka halaman modul terkait dan otomatis menandai notifikasi telah dibaca.'],
                            ['title' => 'Tandai Semua Dibaca', 'desc' => 'Gunakan tombol \'Tandai Semua Dibaca\' di sudut kanan atas jika seluruh permohonan baru telah Anda ketahui.'],
                            ['title' => 'Pembersihan Riwayat', 'desc' => 'Klik tombol \'Bersihkan Terbaca\' untuk menghapus arsip notifikasi lama yang sudah selesai ditindaklanjuti.']
                        ]"
                        role="Seluruh Pengurus RT 15"
                        :tips="[
                            'Halaman ini otomatis melakukan sinkronisasi data baru setiap 15 detik tanpa perlu me-refresh browser secara manual.',
                            'Badge merah pada menu navigasi dan ikon lonceng di atas menunjukkan jumlah notifikasi yang belum Anda buka.',
                            'Notifikasi yang baru masuk ditandai dengan garis aksen biru di tepi kiri dan label \'Baru\'.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Pusat Notifikasi
                    </h1>
                    @if ($this->stats['unread'] > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-600 animate-pulse"></span>
                            {{ $this->stats['unread'] }} Belum Dibaca
                        </span>
                    @endif
                </div>
            </div>

            {{-- Tombol Aksi Massal --}}
            <div class="flex items-center gap-2 shrink-0">
                @if ($this->stats['unread'] > 0)
                    <button 
                        type="button"
                        wire:click="markAllAsRead"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-[#0B3A85] hover:bg-[#08295E] rounded-xl shadow-xs transition duration-150 cursor-pointer disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Tandai Semua Dibaca</span>
                    </button>
                @endif

                @if ($this->stats['read'] > 0)
                    <button 
                        type="button"
                        wire:click="deleteAllRead"
                        wire:confirm="Apakah Anda yakin ingin menghapus seluruh notifikasi yang sudah dibaca?"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 rounded-xl transition duration-150 cursor-pointer disabled:opacity-50"
                        title="Hapus riwayat notifikasi yang telah dibaca">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span class="hidden sm:inline">Bersihkan Terbaca</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- ====================================================================
             2. KPI CARDS RINGKASAN NOTIFIKASI (4 KARTU METRIK)
             ==================================================================== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            {{-- Kartu 1: Total Notifikasi --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B3A85] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Notifikasi</div>
                    <div class="text-xl font-black text-[#0F1F3D]">{{ number_format($this->stats['total']) }}</div>
                </div>
            </div>

            {{-- Kartu 2: Belum Dibaca --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Belum Dibaca</div>
                    <div class="text-xl font-black {{ $this->stats['unread'] > 0 ? 'text-rose-600' : 'text-slate-700' }}">
                        {{ number_format($this->stats['unread']) }}
                    </div>
                </div>
            </div>

            {{-- Kartu 3: Notifikasi Hari Ini --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Hari Ini</div>
                    <div class="text-xl font-black text-[#0F1F3D]">{{ number_format($this->stats['today']) }}</div>
                </div>
            </div>

            {{-- Kartu 4: Sudah Selesai Dibaca --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Riwayat Terbaca</div>
                    <div class="text-xl font-black text-slate-700">{{ number_format($this->stats['read']) }}</div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             3. FILTER TABS & SEARCH INPUT (INTERAKTIF & RESPONSIF)
             ==================================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-4 shadow-2xs space-y-3">
            {{-- Baris Atas: Tab Kategori --}}
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 scrollbar-none">
                <button 
                    type="button"
                    wire:click="setTab('all')"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $this->tab === 'all' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Semua
                </button>

                <button 
                    type="button"
                    wire:click="setTab('unread')"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer flex items-center gap-1.5 {{ $this->tab === 'unread' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>Belum Dibaca</span>
                    @if ($this->stats['unread'] > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $this->tab === 'unread' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-700 font-black' }}">
                            {{ $this->stats['unread'] }}
                        </span>
                    @endif
                </button>

                <button 
                    type="button"
                    wire:click="setTab('letter')"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $this->tab === 'letter' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Surat Pengantar
                </button>

                <button 
                    type="button"
                    wire:click="setTab('complaint')"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $this->tab === 'complaint' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Pengaduan Warga
                </button>

                <button 
                    type="button"
                    wire:click="setTab('payment')"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $this->tab === 'payment' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Iuran & Keuangan
                </button>

                <button 
                    type="button"
                    wire:click="setTab('guest')"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $this->tab === 'guest' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Keamanan & Tamu
                </button>

                <button 
                    type="button"
                    wire:click="setTab('read')"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer {{ $this->tab === 'read' ? 'bg-[#0B3A85] text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Sudah Dibaca
                </button>
            </div>

            {{-- Baris Bawah: Pencarian & Reset Filter --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 pt-2 border-t border-slate-100">
                <div class="relative flex-1 max-w-lg">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari permohonan atau kata kunci dalam notifikasi..."
                        class="w-full pl-9 pr-8 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] transition outline-none placeholder:text-slate-400">
                    @if (filled($this->search))
                        <button 
                            type="button"
                            wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>

                @if ($this->tab !== 'all' || filled($this->search))
                    <button 
                        type="button"
                        wire:click="resetFilters"
                        class="text-xs font-semibold text-[#0B3A85] hover:underline self-end sm:self-center cursor-pointer">
                        Reset Filter
                    </button>
                @endif
            </div>
        </div>

        {{-- ====================================================================
             4. DAFTAR NOTIFIKASI (KARTU NOTIFIKASI RESPONSIF)
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
                        @if (filled($this->search) || $this->tab !== 'all')
                            Tidak ada notifikasi yang sesuai dengan kriteria filter atau pencarian Anda.
                        @else
                            Semua pengajuan dan aktivitas warga telah dipantau dan tidak ada permohonan baru yang tertunda.
                        @endif
                    </p>
                    @if (filled($this->search) || $this->tab !== 'all')
                        <div class="mt-4">
                            <button 
                                type="button"
                                wire:click="resetFilters"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-[#0B3A85] bg-blue-50 hover:bg-blue-100 rounded-xl transition cursor-pointer">
                                Tampilkan Seluruh Notifikasi
                            </button>
                        </div>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- ====================================================================
             5. PAGINATION CONTROLS
             ==================================================================== --}}
        @if ($notifications->hasPages())
            <div class="pt-2">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
