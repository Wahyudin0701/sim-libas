<x-filament-panels::page>
    <div 
        x-data="{
            detailModalOpen: @entangle('detailModalOpen'),
            formModalOpen: @entangle('formModalOpen'),
            deleteConfirmModalOpen: false,
            deleteAnnouncementId: null,
            deleteAnnouncementTitle: '',
            confirmDelete(id, title) {
                this.deleteAnnouncementId = id;
                this.deleteAnnouncementTitle = title;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteAnnouncementId = null;
                this.deleteAnnouncementTitle = '';
            },
            executeDelete() {
                if (this.deleteAnnouncementId) {
                    const id = this.deleteAnnouncementId;
                    this.closeDeleteConfirm();
                    $wire.deleteAnnouncement(id);
                }
            }
        }"
        @keydown.escape.window="closeDeleteConfirm(); detailModalOpen = false; formModalOpen = false; if ($wire.detailModalOpen) $wire.closeDetail(); if ($wire.formModalOpen) $wire.closeForm();"
        class="w-full space-y-4"
    >
        {{-- ====================================================================
             1. HEADER KONTEN (KOMUNIKASI WARGA RT 15 - BIRU UTAMA #0B3A85)
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                {{-- Kategori Subtitle & Tombol Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>KOMUNIKASI WARGA RT 15</span>
                    <x-page-guide 
                        title="Pengumuman Warga"
                        category="KOMUNIKASI WARGA RT 15"
                        description="Modul publikasi papan pengumuman digital RT 15 untuk menyebarluaskan surat edaran, berita penting, himbauan lingkungan, serta agenda kerja bakti kepada seluruh warga melalui portal warga."
                        :steps="[
                            ['title' => 'Pencarian & Filter Kategori', 'desc' => 'Cari pengumuman berdasarkan judul/isi dan saring menurut kategori (Kegiatan, Himbauan, Edaran, Keuangan, dll).'],
                            ['title' => 'Buat Pengumuman Baru', 'desc' => 'Klik tombol biru \'+ Tambah Pengumuman\' untuk menulis judul, kategori, konten pengumuman, dan lampiran gambar.'],
                            ['title' => 'Aksi & Kelola Publikasi', 'desc' => 'Gunakan tombol aksi \'Lihat\' untuk pratinjau tampilan warga, \'Ubah\' untuk memperbarui informasi, atau \'Hapus\' untuk menarik pengumuman.'],
                            ['title' => 'Ekspor Rekap', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh rekap riwayat pengumuman warga dalam format PDF atau Excel.']
                        ]"
                        role="Sekretaris RT & Humas RT"
                        :tips="[
                            'Pastikan judul pengumuman singkat, padat, dan jelas agar mudah dibaca oleh warga.',
                            'Pengumuman yang telah diterbitkan akan langsung dapat diakses oleh seluruh warga di halaman utama portal warga.',
                            'Gunakan kategori yang tepat agar warga mudah menemukan jenis informasi yang dibutuhkan.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Pengumuman Warga
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Tambah Pengumuman) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="announcements" :extraParams="['status' => '$wire.statusFilter', 'target' => '$wire.targetFilter']" />

                {{-- Tombol Primer: Tambah Pengumuman --}}
                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Buat Pengumuman</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. SEARCH BAR & DROPDOWN FILTER & RESET FILTER (STANDAR RESMI)
             ==================================================================== --}}
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center lg:justify-between gap-2.5">
            {{-- Search Input Real-Time & Preservasi Nilai SSR --}}
            <div class="relative w-full sm:max-w-md">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#94A3B8]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <input 
                        type="text" 
                        id="search-announcement-input"
                        name="search"
                        value="{{ $search }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari judul pengumuman, isi pesan, atau target audiens..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-announcement-clear-btn" 
                        style="display: none;"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#94A3B8] hover:text-[#0F1F3D] transition-colors cursor-pointer"
                        title="Hapus kata kunci"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Wrapper Filter & Reset: Di mobile kedua filter sejajar (grid 2 cols), di tablet/desktop sejajar horizontal --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 shrink-0">
                <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 sm:gap-2.5">
                    {{-- Filter Pilihan Status Pengumuman (Dropdown) --}}
                    <div class="w-full sm:w-56">
                        <select 
                            id="filter-announcement-status"
                            name="status"
                            wire:model.live="statusFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Pengumuman ({{ $countSemua }})</option>
                            <option value="tayang" {{ $statusFilter === 'tayang' ? 'selected' : '' }}>Sedang Tayang ({{ $countTayang }})</option>
                            <option value="berakhir" {{ $statusFilter === 'berakhir' ? 'selected' : '' }}>Sudah Berakhir ({{ $countBerakhir }})</option>
                            @if($countTerjadwal > 0)
                                <option value="terjadwal" {{ $statusFilter === 'terjadwal' ? 'selected' : '' }}>Terjadwal ({{ $countTerjadwal }})</option>
                            @endif
                            <option value="pinned" {{ $statusFilter === 'pinned' ? 'selected' : '' }}>Disematkan (Pin) ({{ $countPinned }})</option>
                            <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft ({{ $countDraft }})</option>
                        </select>
                    </div>

                    {{-- Filter Pilihan Target Audiens --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-announcement-target"
                            name="target"
                            wire:model.live="targetFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="">Semua Target Audiens</option>
                            <option value="semua" {{ $targetFilter === 'semua' ? 'selected' : '' }}>Semua Warga</option>
                            <option value="pengurus" {{ $targetFilter === 'pengurus' ? 'selected' : '' }}>Khusus Pengurus</option>
                            <option value="warga" {{ $targetFilter === 'warga' ? 'selected' : '' }}>Warga Tetap & Kontrak</option>
                        </select>
                    </div>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-announcement-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || ($statusFilter !== 'semua' && filled($statusFilter)) 
                            || filled($targetFilter) 
                            || request()->anyFilled(['search', 'target']) 
                            || (request()->has('status') && request('status') !== 'semua' && request('status') !== '');
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter-announcement" 
                            class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition-all cursor-pointer whitespace-nowrap shadow-2xs w-full sm:w-auto"
                            title="Reset semua filter pencarian"
                        >
                            <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="text-red-600 font-bold">Reset Filter</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ====================================================================
             4. TABEL DATA PENGUMUMAN (DEEP NAVY #0B3A85 TABLE HEADER)
             ==================================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="statusFilter, targetFilter, search, resetFilters, setStatus, previousPage, nextPage, gotoPage"
                text="Memperbarui data pengumuman..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white">
                            <th class="py-3 px-4 font-bold text-center w-12 shrink-0">NO</th>
                            <th class="py-3 px-4 font-bold min-w-[460px] w-[50%]">Judul & Pesan Pengumuman</th>
                            <th class="py-3 px-4 font-bold min-w-[150px] whitespace-nowrap">Target Audiens</th>
                            <th class="py-3 px-4 font-bold min-w-[160px] whitespace-nowrap">Periode Tayang</th>
                            <th class="py-3 px-4 font-bold min-w-[110px] whitespace-nowrap">Status</th>
                            <th class="py-3 px-4 font-bold text-center w-40 min-w-[160px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody 
                        id="announcements-table-body" 
                        class="divide-y divide-slate-100 transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="statusFilter, targetFilter, search, resetFilters, setStatus, previousPage, nextPage, gotoPage"
                    >
                        @forelse($announcements as $index => $item)
                            <tr 
                                class="hover:bg-slate-50/80 transition-colors announcement-data-row {{ $item->is_pinned ? 'bg-amber-50/25' : '' }}"
                                data-status="{{ $item->status }}"
                                data-pinned="{{ $item->is_pinned ? '1' : '0' }}"
                                data-target="{{ $item->target }}"
                            >
                                {{-- Kolom Nomor Urut --}}
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-500 text-xs whitespace-nowrap">
                                    {{ $announcements->firstItem() ? ($announcements->firstItem() + $loop->index) : $loop->iteration }}
                                </td>

                                {{-- Kolom Judul & Ringkasan --}}
                                <td class="py-3.5 px-4 min-w-[460px]">
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-[#0F1F3D] text-[13px] sm:text-[14px] leading-snug hover:text-[#0B3A85] cursor-pointer" @click="detailModalOpen = true" wire:click="openDetail({{ $item->id }})">
                                            {{ $item->judul }}
                                        </span>
                                        @if($item->is_pinned)
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 shrink-0" title="Pengumuman Disematkan di Posisi Teratas">
                                                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24">
                                                    <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/>
                                                </svg>
                                                PIN
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-slate-500 text-[11px] sm:text-xs mt-1 leading-relaxed line-clamp-2">
                                        {{ Str::limit($item->isi, 130) }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1.5 text-[10px] sm:text-[11px] text-slate-400 whitespace-nowrap">
                                        <span>Dibuat: {{ $item->pembuat ? $item->pembuat->name : 'Pengurus RT' }}</span>
                                        <span>•</span>
                                        <span>{{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}</span>
                                    </div>
                                </td>

                                {{-- Kolom Target Audiens --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($item->target === 'semua')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-[#0B3A85] border border-blue-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Semua Warga
                                        </span>
                                    @elseif($item->target === 'pengurus')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Khusus Pengurus
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            Warga RT
                                        </span>
                                    @endif
                                </td>

                                {{-- Kolom Periode Tayang --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="text-[11px] font-bold text-slate-700">
                                        {{ $item->tanggal_tayang ? \Carbon\Carbon::parse($item->tanggal_tayang)->format('d M Y, H:i') : 'Langsung' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        s/d {{ $item->tanggal_berakhir ? \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y, H:i') : 'Tanpa batas' }}
                                    </div>
                                </td>

                                {{-- Kolom Status Dinamis --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($item->status === 'tayang')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Tayang
                                        </span>
                                    @elseif($item->status === 'berakhir')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Periode tayang pengumuman telah berakhir">
                                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Berakhir
                                        </span>
                                    @elseif($item->status === 'terjadwal')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200" title="Akan tayang sesuai jadwal mulai tayang">
                                            <svg class="w-3 h-3 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Terjadwal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                {{-- Kolom Aksi dengan Tombol Berwarna Konsisten (Standar SIM-LiBas) --}}
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        {{-- Toggle Pin (Kuning/Amber jika aktif, Abu-abu jika nonaktif) --}}
                                        <button 
                                            type="button" 
                                            wire:click="togglePin({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border {{ $item->is_pinned ? 'border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400' : 'border-slate-200 bg-slate-50 text-slate-400 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300' }} transition-all shadow-2xs cursor-pointer"
                                            title="{{ $item->is_pinned ? 'Lepas Sematan (Unpin)' : 'Sematkan di Atas (Pin)' }}"
                                        >
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/>
                                            </svg>
                                        </button>

                                        {{-- Detail Modal (Ikon Mata - Biru) --}}
                                        <button 
                                            type="button" 
                                            @click="detailModalOpen = true"
                                            wire:click="openDetail({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer"
                                            title="Lihat Detail Pengumuman"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Ubah Modal (Ikon Pensil - Kuning/Amber) --}}
                                        <button 
                                            type="button" 
                                            @click="formModalOpen = true"
                                            wire:click="openEdit({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer"
                                            title="Ubah Pengumuman"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Hapus Modal (Ikon Sampah - Merah/Rose) --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $item->id }}, '{{ addslashes($item->judul) }}')" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer"
                                            title="Hapus Pengumuman"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="announcement-empty-row">
                                <td colspan="6" class="py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">Tidak ada pengumuman ditemukan</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                        Tidak ada catatan pengumuman yang sesuai dengan kriteria filter saat ini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Baris Empty State Pencarian Real-Time Client Side --}}
                        <tr id="announcement-client-empty-row" style="display: none;">
                            <td colspan="6" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Tidak ada pengumuman yang cocok</h4>
                                <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Livewire --}}
            @if($announcements->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL PENGUMUMAN
             ==================================================================== --}}
        <div 
            x-show="detailModalOpen" 
            x-cloak
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="detailModalOpen = false; $wire.closeDetail()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-xl border border-slate-200" @click.stop>
                    {{-- Modal Header (Navy Theme #0B3A85) --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-white/10 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Papan Informasi Digital RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="modal-title">
                                    Detail Pengumuman Warga
                                </h3>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false; $wire.closeDetail()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Loading Skeleton saat openDetail sedang dieksekusi asynchronous --}}
                    <div wire:loading.flex wire:target="openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-24 bg-slate-200 rounded-md"></div>
                            <div class="h-6 w-20 bg-slate-200 rounded-md"></div>
                        </div>
                        <div class="h-6 bg-slate-200 rounded w-3/4"></div>
                        <div class="h-3 bg-slate-200 rounded w-1/2"></div>
                        <div class="p-4 rounded-xl bg-slate-100 space-y-2">
                            <div class="h-3.5 bg-slate-200 rounded w-full"></div>
                            <div class="h-3.5 bg-slate-200 rounded w-5/6"></div>
                            <div class="h-3.5 bg-slate-200 rounded w-4/6"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="h-14 bg-slate-100 rounded-lg"></div>
                            <div class="h-14 bg-slate-100 rounded-lg"></div>
                        </div>
                    </div>

                    <div wire:loading.remove wire:target="openDetail">
                        @if($selectedAnnouncement)
                            {{-- Modal Body --}}
                            <div class="p-6 space-y-4 text-xs sm:text-sm text-slate-700 max-h-[75vh] overflow-y-auto">
                                <div class="flex items-center gap-2">
                                    @if($selectedAnnouncement->is_pinned)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24">
                                                <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/>
                                            </svg>
                                            DISEMATKAN
                                        </span>
                                    @endif
                                    @if($selectedAnnouncement->status === 'tayang')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            SEDANG TAYANG
                                        </span>
                                    @elseif($selectedAnnouncement->status === 'berakhir')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            SUDAH BERAKHIR
                                        </span>
                                    @elseif($selectedAnnouncement->status === 'terjadwal')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-sky-100 text-sky-800 border border-sky-200">
                                            <svg class="w-3 h-3 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            TERJADWAL
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            DRAFT
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 text-[#0B3A85] border border-blue-200">
                                        TARGET: {{ strtoupper($selectedAnnouncement->target) }}
                                    </span>
                                </div>

                                <div>
                                    <h2 class="text-base sm:text-lg font-black text-[#0F1F3D]">
                                        {{ $selectedAnnouncement->judul }}
                                    </h2>
                                    <p class="text-xs text-slate-400 mt-1">
                                        Dipublikasikan oleh: <strong class="text-slate-600">{{ $selectedAnnouncement->pembuat ? $selectedAnnouncement->pembuat->name : 'Pengurus RT' }}</strong> • {{ $selectedAnnouncement->created_at ? $selectedAnnouncement->created_at->format('d F Y, H:i') : '-' }} WIB
                                    </p>
                                </div>

                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 leading-relaxed whitespace-pre-line text-xs sm:text-sm font-normal">
                                    {{ $selectedAnnouncement->isi }}
                                </div>

                                <div class="grid grid-cols-2 gap-3 pt-2">
                                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Mulai Tayang</div>
                                        <div class="text-xs font-bold text-slate-700 mt-0.5">
                                            {{ $selectedAnnouncement->tanggal_tayang ? \Carbon\Carbon::parse($selectedAnnouncement->tanggal_tayang)->format('d M Y, H:i') : 'Langsung' }}
                                        </div>
                                    </div>
                                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Berakhir Tayang</div>
                                        <div class="text-xs font-bold text-slate-700 mt-0.5">
                                            {{ $selectedAnnouncement->tanggal_berakhir ? \Carbon\Carbon::parse($selectedAnnouncement->tanggal_berakhir)->format('d M Y, H:i') : 'Selamanya (Tanpa Batas)' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex items-center justify-between">
                                <button 
                                    type="button" 
                                    wire:click="togglePin({{ $selectedAnnouncement->id }})" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 transition-colors cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5 {{ $selectedAnnouncement->is_pinned ? 'text-amber-500 fill-current' : 'text-slate-400' }}" viewBox="0 0 24 24">
                                        <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/>
                                    </svg>
                                    <span>{{ $selectedAnnouncement->is_pinned ? 'Lepas Sematan' : 'Sematkan Pengumuman' }}</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="detailModalOpen = false; $wire.closeDetail()" 
                                    class="px-4 py-2 bg-[#0B3A85] text-white rounded-xl text-xs font-bold hover:bg-[#07275E] transition-colors cursor-pointer"
                                >
                                    Tutup
                                </button>
                            </div>
                        @else
                            <div class="p-6 space-y-4 animate-pulse">
                                <div class="h-6 bg-slate-200 rounded w-1/2"></div>
                                <div class="h-20 bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL FORM TAMBAH / UBAH PENGUMUMAN
             ==================================================================== --}}
        <div 
            x-show="formModalOpen" 
            x-cloak
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="form-modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="formModalOpen = false; $wire.closeForm()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-lg border border-slate-200" @click.stop>
                    {{-- Modal Header (Navy Theme #0B3A85) --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-white/10 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Formulir Komunikasi RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="form-modal-title">
                                    {{ $isEditing ? 'Ubah Pengumuman' : 'Buat Pengumuman Baru' }}
                                </h3>
                            </div>
                        </div>
                        <button type="button" @click="formModalOpen = false; $wire.closeForm()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Loading Skeleton saat openEdit sedang mengambil data dari server (mencegah data lama tampil) --}}
                    <div wire:loading.flex wire:target="openEdit" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-4 bg-slate-200 rounded w-1/4"></div>
                        <div class="h-10 bg-slate-100 rounded-xl"></div>
                        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                        <div class="h-10 bg-slate-100 rounded-xl"></div>
                        <div class="h-4 bg-slate-200 rounded w-1/4"></div>
                        <div class="h-24 bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Modal Body / Form --}}
                    <form wire:loading.remove wire:target="openEdit" wire:submit="saveAnnouncement" class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Judul Pengumuman <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="form_judul" 
                                placeholder="Contoh: Kerja Bakti Massal Menyambut HUT RI ke-81"
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                            >
                            @error('form_judul') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Target Audiens <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    wire:model="form_target"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                    <option value="semua">Semua Warga</option>
                                    <option value="pengurus">Khusus Pengurus RT</option>
                                    <option value="warga">Warga Tetap & Kontrak</option>
                                </select>
                                @error('form_target') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="form_is_pinned" class="rounded border-slate-300 text-[#0B3A85] focus:ring-[#0B3A85]">
                                    <span class="text-xs font-bold text-slate-700">Sematkan (Pin)</span>
                                </label>

                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="form_is_published" class="rounded border-slate-300 text-[#0B3A85] focus:ring-[#0B3A85]">
                                    <span class="text-xs font-bold text-slate-700">Tayangkan</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Mulai Tayang</label>
                                <input 
                                    type="datetime-local" 
                                    wire:model="form_tanggal_tayang" 
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Berakhir Tayang (Opsional)</label>
                                <input 
                                    type="datetime-local" 
                                    wire:model="form_tanggal_berakhir" 
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Isi Pengumuman / Pesan <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                wire:model="form_isi" 
                                rows="5" 
                                placeholder="Tuliskan isi pengumuman lengkap secara jelas..."
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                            ></textarea>
                            @error('form_isi') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Form Footer --}}
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button 
                                type="button" 
                                @click="formModalOpen = false; $wire.closeForm()" 
                                class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-4 py-2 rounded-xl text-xs font-bold bg-[#0B3A85] hover:bg-[#07275E] text-white transition-all shadow-xs cursor-pointer"
                            >
                                {{ $isEditing ? 'Simpan Perubahan' : 'Terbitkan Pengumuman' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL KONFIRMASI HAPUS PENGUMUMAN (ALPINE.JS)
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="delete-modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="deleteConfirmModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="closeDeleteConfirm"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="deleteConfirmModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-md border border-slate-200 p-6 space-y-4"
                    @click.stop
                >
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-modal-title">Hapus Pengumuman Ini?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini akan memindahkan warta ke tempat sampah.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Judul Pengumuman:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteAnnouncementTitle"></span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button 
                            type="button" 
                            @click="closeDeleteConfirm" 
                            class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            @click="executeDelete" 
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition-all shadow-xs cursor-pointer"
                        >
                            Ya, Hapus Pengumuman
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================================
         8. SCRIPT REAL-TIME CLIENT-SIDE SEARCH & DYNAMIC RESET FILTER
         ATURAN STANDAR PENGEMBANGAN: SEARCH BAR & FILTER TABEL
         ==================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initAnnouncementSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initAnnouncementSearch();
        });

        document.addEventListener('livewire:init', function() {
            if (window.Livewire && window.Livewire.hook) {
                window.Livewire.hook('commit', ({ succeed }) => {
                    succeed(() => {
                        initAnnouncementSearch();
                    });
                });
            }
        });

        function initAnnouncementSearch() {
            const searchInput = document.getElementById('search-announcement-input');
            const clearBtn = document.getElementById('search-announcement-clear-btn');
            const statusSelect = document.getElementById('filter-announcement-status');
            const targetSelect = document.getElementById('filter-announcement-target');
            const resetContainer = document.getElementById('reset-filter-announcement-container');
            const tableBody = document.getElementById('announcements-table-body');

            if (!searchInput) return;

            const urlParams = new URLSearchParams(window.location.search);
            const hasServerFilter = urlParams.has('search') || (urlParams.has('status') && urlParams.get('status') !== 'semua') || urlParams.has('target');

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const statusVal = statusSelect ? statusSelect.value : 'semua';
                const targetVal = targetSelect ? targetSelect.value : '';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || (statusVal !== 'semua' && statusVal !== '') || targetVal !== '';
                let resetBtn = document.getElementById('btn-reset-filter-announcement');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-announcement';
                        resetBtn.setAttribute('wire:click.prevent', 'resetFilters');
                        resetBtn.className = 'inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition-all cursor-pointer whitespace-nowrap shadow-2xs w-full sm:w-auto';
                        resetBtn.innerHTML = `
                            <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="text-red-600 font-bold">${['Reset', 'Filter'].join(' ')}</span>
                        `;
                        resetContainer.appendChild(resetBtn);
                    }
                } else {
                    if (!hasServerFilter && resetBtn) {
                        resetBtn.remove();
                    }
                }

                if (tableBody) {
                    const rows = tableBody.querySelectorAll('tr.announcement-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowStatus = row.dataset.status;
                        const rowPinned = row.dataset.pinned;
                        const rowTarget = row.dataset.target;

                        let matchesSearch = term === '' || content.includes(term);
                        let matchesStatus = true;
                        if (statusVal === 'tayang') {
                            matchesStatus = rowStatus === 'tayang';
                        } else if (statusVal === 'berakhir') {
                            matchesStatus = rowStatus === 'berakhir';
                        } else if (statusVal === 'terjadwal') {
                            matchesStatus = rowStatus === 'terjadwal';
                        } else if (statusVal === 'draft') {
                            matchesStatus = rowStatus === 'draft';
                        } else if (statusVal === 'pinned') {
                            matchesStatus = rowPinned === '1';
                        }

                        let matchesTarget = targetVal === '' || rowTarget === targetVal;

                        if (matchesSearch && matchesStatus && matchesTarget) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('announcement-client-empty-row');
                    if (emptyRow) {
                        if (matched === 0 && rows.length > 0) {
                            emptyRow.style.display = '';
                        } else {
                            emptyRow.style.display = 'none';
                        }
                    }
                }
            }

            if (!searchInput.dataset.initialized) {
                searchInput.dataset.initialized = 'true';
                searchInput.addEventListener('input', applyFilter);
                if (statusSelect) statusSelect.addEventListener('change', applyFilter);
                if (targetSelect) targetSelect.addEventListener('change', applyFilter);

                if (clearBtn) {
                    clearBtn.addEventListener('click', function() {
                        searchInput.value = '';
                        applyFilter();
                        searchInput.dispatchEvent(new Event('input', { bubbles: true }));
                        if (window.Livewire) {
                            const compEl = searchInput.closest('[wire\\:id]');
                            if (compEl && window.Livewire.find) {
                                const comp = window.Livewire.find(compEl.getAttribute('wire:id'));
                                if (comp) comp.set('search', '');
                            }
                        }
                    });
                }
            }

            if (resetContainer && !resetContainer.dataset.delegated) {
                resetContainer.dataset.delegated = 'true';
                resetContainer.addEventListener('click', function(e) {
                    const btn = e.target.closest('#btn-reset-filter-announcement');
                    if (!btn) return;

                    searchInput.value = '';
                    if (statusSelect) statusSelect.value = 'semua';
                    if (targetSelect) targetSelect.value = '';
                    applyFilter();

                    if (window.Livewire) {
                        const compEl = searchInput.closest('[wire\\:id]');
                        if (compEl && window.Livewire.find) {
                            const comp = window.Livewire.find(compEl.getAttribute('wire:id'));
                            if (comp) {
                                if (typeof comp.call === 'function') {
                                    comp.call('resetFilters');
                                } else {
                                    comp.set('search', '');
                                    comp.set('statusFilter', 'semua');
                                    comp.set('targetFilter', '');
                                }
                            }
                        }
                    }

                    if (hasServerFilter) {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            if (searchInput.value.trim().length > 0 || (statusSelect && statusSelect.value !== 'semua') || (targetSelect && targetSelect.value !== '')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
