<x-filament-panels::page>
    <div 
        x-data="{
            deleteConfirmModalOpen: false,
            deleteMsmeId: null,
            deleteMsmeTitle: '',
            detailModalOpen: @entangle('detailModalOpen'),
            moderationModalOpen: @entangle('moderationModalOpen'),
            formModalOpen: @entangle('formModalOpen'),
            confirmDelete(id, title) {
                this.deleteMsmeId = id;
                this.deleteMsmeTitle = title;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteMsmeId = null;
                this.deleteMsmeTitle = '';
            },
            executeDelete() {
                if (this.deleteMsmeId) {
                    const id = this.deleteMsmeId;
                    this.closeDeleteConfirm();
                    $wire.deleteMsme(id);
                }
            }
        }"
        @keydown.escape.window="closeDeleteConfirm(); if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); } if (moderationModalOpen) { moderationModalOpen = false; $wire.closeModeration(); } if (formModalOpen) { formModalOpen = false; $wire.closeForm(); }"
        class="w-full space-y-4"
    >
        {{-- ====================================================================
             1. HEADER KONTEN (KAMPUNG DIGITAL RT 15 - BIRU UTAMA #0B3A85)
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                {{-- Kategori Subtitle & Tombol Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>KAMPUNG DIGITAL RT 15</span>
                    <x-page-guide 
                        title="Katalog UMKM Warga"
                        category="KAMPUNG DIGITAL RT 15"
                        description="Modul direktori dan etalase promosi usaha mikro, kecil, rumahan, kerajinan, dan kuliner milik warga RT 15 untuk mendukung pemberdayaan ekonomi lokal."
                        :steps="[
                            ['title' => 'Pencarian & Filter Usaha', 'desc' => 'Cari berdasarkan nama toko/usaha, nama pemilik warga, atau kategori produk (Makanan, Jasa, Kerajinan, dll).'],
                            ['title' => 'Daftarkan Profil Usaha', 'desc' => 'Klik tombol biru \'+ Daftarkan UMKM\' untuk menginput nama toko, kontak WhatsApp, deskripsi produk, dan foto etalase.'],
                            ['title' => 'Moderasi & Pembaruan', 'desc' => 'Gunakan tombol \'Lihat\' untuk cek tampilan profil, \'Ubah\' untuk memperbarui nomor kontak atau menu, dan \'Hapus\' jika usaha tutup.'],
                            ['title' => 'Ekspor Direktori UMKM', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh buku katalog usaha warga RT 15 dalam format PDF atau Excel.']
                        ]"
                        role="Seksi Usaha & Pemberdayaan Ekonomi RT"
                        :tips="[
                            'Pastikan pemilik UMKM adalah warga terdaftar di lingkungan RT 15.',
                            'Nomor telepon yang dicantumkan terhubung langsung dengan tombol WhatsApp belanja di portal warga.',
                            'Gunakan foto produk yang jelas dan menarik agar meningkatkan minat beli antar-warga.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Katalog UMKM Warga
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Daftarkan UMKM) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="msme-profiles" :extraParams="['kategori' => '$wire.kategoriFilter']" />

                {{-- Tombol Primer: Daftarkan UMKM --}}
                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Daftarkan UMKM</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. TAB FILTER STATUS & SEARCH BAR & RESET FILTER (STANDAR RESMI)
             ==================================================================== --}}
        {{-- ====================================================================
             2. SEARCH BAR & DROPDOWN FILTER & RESET FILTER (STANDAR RESMI)
             - Mobile: Search Bar di atas, kedua filter sejajar (grid 2 kolom) di bawahnya
             - Desktop: Semua elemen sejajar horizontal dalam satu baris
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
                        id="search-msme-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari ... " 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-msme-clear-btn" 
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
                    {{-- Filter Pilihan Status Moderasi (Dropdown Pengganti Tab) --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-msme-status"
                            name="status"
                            wire:model.live="statusFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Usaha ({{ $countSemua }})</option>
                            <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui ({{ $countApproved }})</option>
                            <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Menunggu Review ({{ $countPending }})</option>
                            <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak ({{ $countDitolak }})</option>
                        </select>
                    </div>

                    {{-- Filter Pilihan Kategori UMKM --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-msme-kategori"
                            name="kategori"
                            wire:model.live="kategoriFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="">Semua Kategori Usaha</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ (request('kategori') === $kat || $kategoriFilter === $kat) ? 'selected' : '' }}>
                                    {{ ucfirst($kat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-msme-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || ($statusFilter !== 'semua' && filled($statusFilter)) 
                            || filled($kategoriFilter) 
                            || request()->anyFilled(['search', 'kategori']) 
                            || (request()->has('status') && request('status') !== 'semua' && request('status') !== '');
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter-msme" 
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
             4. TABEL DATA UMKM (DEEP NAVY #0B3A85 TABLE HEADER)
             ==================================================================== --}}
        <div class="bg-white rounded-none md:rounded-2xl border border-slate-200 overflow-hidden shadow-xs relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="statusFilter, kategoriFilter, search, resetFilters, setStatus, previousPage, nextPage, gotoPage"
                text="Memperbarui data profil UMKM..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-full md:min-w-[1100px] text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white">
                            <th class="hidden md:table-cell py-3 px-4 font-bold text-center w-12 shrink-0">NO</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[110px] whitespace-nowrap">Status</th>
                            <th class="py-3 px-3 sm:px-4 font-bold md:min-w-[240px]">Nama Usaha & Kategori</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[200px]">Pemilik & Alamat</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[170px] whitespace-nowrap">NO. TELEPON / WA</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[200px]">Produk Unggulan</th>
                            <th class="py-3 px-2 sm:px-4 font-bold text-center w-20 sm:w-28 md:w-40 md:min-w-[160px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody 
                        id="msmes-table-body" 
                        class="divide-y divide-slate-100 transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="statusFilter, kategoriFilter, search, resetFilters, setStatus, previousPage, nextPage, gotoPage"
                    >
                        @forelse($msmes as $index => $item)
                            <tr 
                                class="hover:bg-slate-50/80 transition-colors msme-data-row"
                                data-status="{{ $item->status_moderasi }}"
                                data-kategori="{{ $item->kategori }}"
                            >
                                {{-- Kolom Nomor Urut --}}
                                <td class="hidden md:table-cell py-3.5 px-4 text-center font-mono font-semibold text-slate-500 text-xs whitespace-nowrap">
                                    {{ $msmes->firstItem() ? ($msmes->firstItem() + $index) : ($index + 1) }}
                                </td>

                                {{-- Kolom Status Moderasi --}}
                                <td class="hidden md:table-cell py-3.5 px-4 whitespace-nowrap">
                                    @if($item->status_moderasi === 'disetujui')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Disetujui
                                        </span>
                                    @elseif($item->status_moderasi === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu Review
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                {{-- Kolom Nama Usaha & Kategori --}}
                                <td class="py-3.5 px-3 sm:px-4">
                                    <div class="flex items-center gap-2.5 sm:gap-3">
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl overflow-hidden shrink-0 border border-slate-200">
                                            <x-umkm-card-image :item="$item" aspect="aspect-square" />
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-extrabold text-[#0F1F3D] text-[12px] sm:text-[13px] hover:text-[#0B3A85] cursor-pointer transition-colors truncate" @click="detailModalOpen = true" wire:click="openDetail({{ $item->id }})">
                                                {{ $item->nama_usaha }}
                                            </div>
                                            <div class="mt-0.5">
                                                <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-md text-[9px] sm:text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                                    {{ $item->kategori ?? 'Lainnya' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom Pemilik & Alamat --}}
                                <td class="hidden md:table-cell py-3.5 px-4">
                                    <div class="font-bold text-slate-700">
                                        {{ $item->household && $item->household->headOfHousehold ? $item->household->headOfHousehold->nama : 'Warga RT 15' }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate max-w-[200px]">
                                            {{ $item->alamat ?: ($item->household ? $item->household->alamat : '-') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Kolom Kontak & Multi-Medsos --}}
                                <td class="hidden md:table-cell py-3.5 px-4 text-xs whitespace-nowrap min-w-[170px]">
                                    @php
                                        $displayPhone = $item->telepon ?: $item->whatsapp;
                                        $cleanWa = $item->getSocialUrl('whatsapp');
                                        $tablePlatforms = $item->getAvailableSocialPlatforms();
                                    @endphp
                                    @if($displayPhone)
                                        <div class="font-mono font-bold text-slate-700 select-all">{{ $displayPhone }}</div>
                                    @else
                                        <span class="text-slate-400 font-sans italic">-</span>
                                    @endif
                                    @if(!empty($tablePlatforms))
                                        <div class="flex items-center gap-1 mt-1">
                                            @foreach($tablePlatforms as $tp)
                                                <a href="{{ $item->getSocialUrl($tp) }}" target="_blank" rel="noopener noreferrer" title="{{ ucfirst($tp) }}: {{ $item->getSocialLabel($tp) }}" class="w-5 h-5 rounded flex items-center justify-center text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition">
                                                    @include('components.umkm-social-icon', ['platform' => $tp, 'class' => 'w-3 h-3 fill-current'])
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                {{-- Kolom Produk Unggulan --}}
                                <td class="hidden md:table-cell py-3.5 px-4">
                                    <div class="text-[11.5px] font-medium text-slate-700 max-w-[220px] line-clamp-2" title="{{ $item->produk }}">
                                        {{ $item->produk ?: '-' }}
                                    </div>
                                    @if($item->deskripsi)
                                        <div class="text-[10px] text-slate-400 max-w-[220px] truncate mt-0.5" title="{{ $item->deskripsi }}">
                                            {{ $item->deskripsi }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Kolom Aksi dengan Tombol Berwarna Konsisten Berdasarkan Status UMKM --}}
                                <td class="py-3.5 px-2 sm:px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex flex-col md:flex-row items-center justify-center gap-1 md:gap-1.5 whitespace-nowrap">
                                        @if($item->status_moderasi === 'pending')
                                            {{-- UMKM Menunggu Review: HANYA SATU AKSI -> REVIEW --}}
                                            <button 
                                                type="button" 
                                                @click="moderationModalOpen = true"
                                                wire:click="openModeration({{ $item->id }})" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-700 hover:bg-amber-100 hover:text-amber-800 hover:border-amber-400 font-bold text-xs transition-all shadow-2xs cursor-pointer"
                                                title="Review Pengajuan UMKM Warga"
                                            >
                                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                <span>Review</span>
                                            </button>
                                        @elseif($item->status_moderasi === 'disetujui')
                                            {{-- UMKM Disetujui: TEPAT TIGA AKSI: Lihat Detail [👁] -> Edit [✏] -> Hapus [🗑] --}}
                                            {{-- 1. Lihat Detail (Ikon Mata - Biru Deep Navy SIM-LiBas) --}}
                                            <button 
                                                type="button" 
                                                @click="detailModalOpen = true"
                                                wire:click="openDetail({{ $item->id }})" 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer"
                                                title="Lihat Detail Profil UMKM"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            {{-- 2. Edit (Ikon Pensil - Amber / Kuning) --}}
                                            <button 
                                                type="button" 
                                                @click="formModalOpen = true"
                                                wire:click="openEdit({{ $item->id }})" 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer"
                                                title="Ubah Data Usaha"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            {{-- 3. Hapus (Ikon Tempat Sampah - Rose / Merah) --}}
                                            <button 
                                                type="button" 
                                                @click="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_usaha) }}')"
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer"
                                                title="Hapus UMKM"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @else
                                            {{-- UMKM Ditolak: Arsip Riwayat -> Lihat Detail [👁] -> Hapus [🗑] --}}
                                            {{-- 1. Lihat Detail (Ikon Mata - Biru Deep Navy SIM-LiBas) --}}
                                            <button 
                                                type="button" 
                                                @click="detailModalOpen = true"
                                                wire:click="openDetail({{ $item->id }})" 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer"
                                                title="Lihat Alasan Penolakan dan Detail Pengajuan"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            {{-- 2. Hapus (Ikon Tempat Sampah - Rose / Merah) --}}
                                            <button 
                                                type="button" 
                                                @click="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_usaha) }}')"
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer"
                                                title="Hapus Arsip Pengajuan Ditolak"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <p class="text-sm font-semibold text-slate-600">Belum ada UMKM terdaftar</p>
                                        <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Daftarkan UMKM" untuk menambahkan usaha warga baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Baris Khusus Hasil Filter Kosong (Client-Side JS) --}}
                        <tr id="msme-client-empty-row" style="display: none;">
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-slate-600">Tidak ada usaha warga yang cocok dengan pencarian Anda</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Coba gunakan kata kunci nama usaha, produk, atau kategori lain.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if($msmes->hasPages())
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $msmes->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL UMKM (ALPINE.JS X-SHOW INSTANT OPEN)
             ==================================================================== --}}
        <div 
            x-show="detailModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-detail-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="detailModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="detailModalOpen = false; $wire.closeDetail()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="detailModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-lg border border-slate-200" 
                    @click.stop
                >
                    {{-- Modal Header (Navy Theme #0B3A85) --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-white/10 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Etalase UMKM RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="modal-detail-title">
                                    {{ $selectedMsme ? $selectedMsme->nama_usaha : 'Detail Profil Usaha' }}
                                </h3>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="detailModalOpen = false; $wire.closeDetail()" 
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                            title="Tutup"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Asynchronous Skeleton Loader while Livewire fetches selectedMsme --}}
                    <div wire:loading.flex wire:target="openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-24 bg-slate-200 rounded-md"></div>
                            <div class="h-6 w-28 bg-slate-200 rounded-md"></div>
                        </div>
                        <div class="h-24 bg-slate-100 rounded-xl"></div>
                        <div class="h-12 bg-slate-100 rounded-xl"></div>
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Modal Content --}}
                    <div wire:loading.remove wire:target="openDetail">
                        @if($selectedMsme)
                            <div class="p-6 space-y-4 text-xs text-slate-700 max-h-[75vh] overflow-y-auto">
                                {{-- Status & Kategori Badges --}}
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($selectedMsme->status_moderasi === 'disetujui')
                                        <span class="px-2.5 py-1 rounded-md font-bold text-[11px] uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            STATUS: DISETUJUI
                                        </span>
                                    @elseif($selectedMsme->status_moderasi === 'pending')
                                        <span class="px-2.5 py-1 rounded-md font-bold text-[11px] uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                            STATUS: MENUNGGU REVIEW
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-md font-bold text-[11px] uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-200">
                                            STATUS: DITOLAK
                                        </span>
                                    @endif
                                    <span class="px-2.5 py-1 rounded-md font-bold text-[11px] uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                        KATEGORI: {{ strtoupper($selectedMsme->kategori) }}
                                    </span>
                                    @if($selectedMsme->status_moderasi === 'disetujui')
                                        <span class="px-2.5 py-1 rounded-md font-bold text-[11px] {{ $selectedMsme->is_active ? 'bg-blue-50 text-[#0B3A85] border border-blue-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                            {{ $selectedMsme->is_active ? 'AKTIF TAYANG' : 'NONAKTIF' }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Visual Header Foto Usaha --}}
                                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-xs">
                                    <x-umkm-card-image :item="$selectedMsme" aspect="aspect-[16/9]" />
                                </div>

                                {{-- Pemilik & Kontak --}}
                                <div class="bg-slate-50 rounded-xl p-3.5 space-y-2 border border-slate-200/60">
                                    <div>
                                        <span class="text-slate-400 font-medium text-[11px] block">Pemilik Usaha (Kepala KK):</span>
                                        <p class="font-bold text-slate-800 text-[13px] mt-0.5">
                                            {{ $selectedMsme->household && $selectedMsme->household->headOfHousehold ? $selectedMsme->household->headOfHousehold->nama : 'Warga RT 15' }}
                                            @if($selectedMsme->household)
                                                <span class="text-[11px] font-normal text-slate-500 font-mono"> (No. KK: {{ $selectedMsme->household->no_kk }})</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-medium text-[11px] block">Alamat Usaha:</span>
                                        <p class="text-slate-700 mt-0.5">
                                            {{ $selectedMsme->alamat ?: ($selectedMsme->household ? $selectedMsme->household->alamat : '-') }}
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
                                        <div>
                                            <span class="text-slate-400 font-medium text-[11px] block">Telepon/HP:</span>
                                            <p class="font-bold text-slate-800 mt-0.5">{{ $selectedMsme->telepon }}</p>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 font-medium text-[11px] block">WhatsApp:</span>
                                            <p class="font-bold text-emerald-600 mt-0.5">{{ $selectedMsme->whatsapp ?: $selectedMsme->telepon }}</p>
                                        </div>
                                    </div>

                                    @php
                                        $selectedPlatforms = $selectedMsme->getAvailableSocialPlatforms();
                                    @endphp
                                    @if(!empty($selectedPlatforms))
                                        <div class="pt-2 border-t border-slate-200/60">
                                            <span class="text-slate-400 font-medium text-[11px] block mb-1.5">Saluran Komunikasi & Toko Online:</span>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($selectedPlatforms as $sp)
                                                    @php
                                                        $sUrl = $selectedMsme->getSocialUrl($sp);
                                                        $sBadge = match($sp) {
                                                            'whatsapp' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                            'instagram' => 'bg-rose-50 text-rose-800 border-rose-200',
                                                            'tiktok' => 'bg-slate-100 text-slate-800 border-slate-200',
                                                            'shopee' => 'bg-orange-50 text-orange-800 border-orange-200',
                                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                                        };
                                                    @endphp
                                                    <a href="{{ $sUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[11px] font-semibold {{ $sBadge }}">
                                                        @include('components.umkm-social-icon', ['platform' => $sp, 'class' => 'w-3.5 h-3.5 fill-current'])
                                                        <span>{{ ucfirst($sp) }}: {{ $selectedMsme->getSocialLabel($sp) }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Produk Unggulan & Deskripsi --}}
                                <div>
                                    <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Produk / Layanan Unggulan</span>
                                    <div class="p-3 bg-white border border-slate-200 rounded-xl font-semibold text-slate-800">
                                        {{ $selectedMsme->produk ?: 'Belum diisi' }}
                                    </div>
                                </div>

                                <div>
                                    <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Deskripsi Profil Usaha</span>
                                    <div class="p-3 bg-white border border-slate-200 rounded-xl text-slate-700 leading-relaxed whitespace-pre-line">
                                        {{ $selectedMsme->deskripsi ?: 'Tidak ada keterangan tambahan.' }}
                                    </div>
                                </div>

                                {{-- Catatan Moderasi / Alasan Penolakan bila ada --}}
                                @if($selectedMsme->catatan_moderasi)
                                    <div class="p-3.5 rounded-xl {{ $selectedMsme->status_moderasi === 'ditolak' ? 'bg-rose-50 border border-rose-200 text-rose-900' : 'bg-amber-50 border border-amber-200 text-amber-900' }}">
                                        <div class="flex items-center gap-1.5 font-bold text-xs {{ $selectedMsme->status_moderasi === 'ditolak' ? 'text-rose-700' : 'text-amber-800' }}">
                                            @if($selectedMsme->status_moderasi === 'ditolak')
                                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                <span>Alasan Penolakan Pengajuan:</span>
                                            @else
                                                <span>Catatan Pengurus / Moderasi:</span>
                                            @endif
                                        </div>
                                        <p class="text-xs mt-1 font-medium leading-relaxed {{ $selectedMsme->status_moderasi === 'ditolak' ? 'text-rose-800' : 'text-amber-900' }}">{{ $selectedMsme->catatan_moderasi }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Modal Footer --}}
                            <div class="bg-slate-50 px-6 py-3 flex justify-end gap-2 border-t border-slate-100">
                                <button 
                                    type="button" 
                                    @click="detailModalOpen = false; $wire.closeDetail()" 
                                    class="px-4 py-2 rounded-xl bg-[#0B3A85] text-white font-bold hover:bg-[#07275E] text-xs transition-colors cursor-pointer"
                                >
                                    Tutup
                                </button>
                            </div>
                        @else
                            <div class="p-6 flex-col space-y-4 animate-pulse">
                                <div class="h-6 w-24 bg-slate-200 rounded-md"></div>
                                <div class="h-24 bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. POPUP REVIEW UMKM (ALPINE.JS X-SHOW INSTANT OPEN)
             ==================================================================== --}}
        <div 
            x-show="moderationModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-moderation-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="moderationModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="moderationModalOpen = false; $wire.closeModeration()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="moderationModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-xl border border-slate-200" 
                    @click.stop
                >
                    {{-- Modal Header (Navy Theme #0B3A85) --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-white/10 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Kurasi Pengajuan Usaha Warga
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="modal-moderation-title">
                                    Review Pengajuan UMKM
                                </h3>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="moderationModalOpen = false; $wire.closeModeration()" 
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                            title="Tutup"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Moderation Skeleton Loading on openModeration --}}
                    <div wire:loading.flex wire:target="openModeration" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-5 w-40 bg-slate-200 rounded"></div>
                        <div class="h-28 bg-slate-100 rounded-xl"></div>
                        <div class="h-16 bg-slate-100 rounded-xl"></div>
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Konten Review UMKM --}}
                    <div wire:loading.remove wire:target="openModeration">
                        @if($moderatingMsme)
                            <div class="p-6 space-y-4 text-xs text-slate-700 max-h-[75vh] overflow-y-auto">
                                {{-- Alert Info Status --}}
                                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-2.5">
                                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-xs">
                                        <p class="font-bold">Pemeriksaan Pengajuan UMKM</p>
                                        <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                                            Periksa seluruh informasi usaha warga di bawah ini. Anda dapat menyetujui agar langsung tampil di Katalog UMKM atau menolak dengan mencantumkan alasan penolakan.
                                        </p>
                                    </div>
                                </div>

                                {{-- Kartu Data Usaha & Pemilik --}}
                                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 space-y-3">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Nama Usaha:</span>
                                            <h4 class="text-sm sm:text-base font-extrabold text-[#0F1F3D] mt-0.5">
                                                {{ $moderatingMsme->nama_usaha }}
                                            </h4>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10.5px] font-bold uppercase tracking-wider bg-slate-200 text-slate-700">
                                            {{ $moderatingMsme->kategori ?? 'Lainnya' }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2.5 border-t border-slate-200">
                                        <div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Pemilik (Kepala KK):</span>
                                            <p class="font-bold text-slate-800 text-xs mt-0.5">
                                                {{ $moderatingMsme->household && $moderatingMsme->household->headOfHousehold ? $moderatingMsme->household->headOfHousehold->nama : 'Warga RT 15' }}
                                            </p>
                                            @if($moderatingMsme->household)
                                                <p class="text-[10.5px] font-mono text-slate-500">No. KK: {{ $moderatingMsme->household->no_kk }}</p>
                                            @endif
                                        </div>

                                        <div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Kontak / Telepon:</span>
                                            <p class="font-mono font-bold text-slate-800 text-xs mt-0.5">{{ $moderatingMsme->telepon }}</p>
                                            @if($moderatingMsme->whatsapp)
                                                <p class="text-[11px] font-semibold text-emerald-600 mt-0.5">WA: {{ $moderatingMsme->whatsapp }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="pt-2.5 border-t border-slate-200">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Alamat Usaha:</span>
                                        <p class="text-slate-700 text-xs mt-0.5">
                                            {{ $moderatingMsme->alamat ?: ($moderatingMsme->household ? $moderatingMsme->household->alamat : '-') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Produk & Deskripsi --}}
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Produk / Layanan Unggulan</span>
                                        <div class="p-3 bg-white border border-slate-200 rounded-xl font-semibold text-slate-800 text-xs">
                                            {{ $moderatingMsme->produk ?: 'Belum diisi' }}
                                        </div>
                                    </div>

                                    <div>
                                        <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wider block mb-1">Deskripsi Profil Usaha</span>
                                        <div class="p-3 bg-white border border-slate-200 rounded-xl text-slate-700 leading-relaxed whitespace-pre-line text-xs">
                                            {{ $moderatingMsme->deskripsi ?: 'Tidak ada keterangan tambahan.' }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Input Alasan Penolakan / Catatan Evaluasi --}}
                                <div class="space-y-1.5 pt-1">
                                    <label class="block font-bold text-slate-700 text-xs">
                                        Alasan Penolakan / Catatan Pengurus:
                                    </label>
                                    <textarea 
                                        wire:model="form_catatan_moderasi" 
                                        rows="3" 
                                        placeholder="Tuliskan alasan jika pengajuan ditolak (wajib diisi saat menolak), atau catatan tambahan jika disetujui..."
                                        class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-xs text-slate-800 placeholder-slate-400"
                                    ></textarea>
                                    @error('form_catatan_moderasi') 
                                        <span class="text-rose-600 font-semibold text-[11px] block mt-0.5">{{ $message }}</span> 
                                    @enderror
                                    <p class="text-[10.5px] text-slate-400 italic">
                                        * Catatan ini wajib diisi jika Anda memilih aksi <strong>Tolak</strong> agar warga mengetahui alasan pengajuannya ditolak.
                                    </p>
                                </div>

                                {{-- Tombol Aksi di Bawah Form: Batal | Tolak | Setujui --}}
                                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-2.5 pt-3 border-t border-slate-200">
                                    <button 
                                        type="button" 
                                        @click="moderationModalOpen = false; $wire.closeModeration()" 
                                        class="w-full sm:w-auto px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors cursor-pointer"
                                    >
                                        Batal
                                    </button>

                                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                                        {{-- Tombol Tolak (Merah) --}}
                                        <button 
                                            type="button" 
                                            wire:click="rejectMsme"
                                            wire:loading.attr="disabled"
                                            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-colors shadow-xs cursor-pointer"
                                            title="Tolak pendaftaran UMKM"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <span>Tolak</span>
                                        </button>

                                        {{-- Tombol Setujui (Hijau) --}}
                                        <button 
                                            type="button" 
                                            wire:click="approveMsme"
                                            wire:loading.attr="disabled"
                                            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-4.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors shadow-xs cursor-pointer"
                                            title="Setujui pendaftaran UMKM"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Setujui</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-6 flex-col space-y-4 animate-pulse">
                                <div class="h-4 w-32 bg-slate-200 rounded"></div>
                                <div class="h-20 w-full bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL FORM (TAMBAH / UBAH UMKM) (ALPINE.JS X-SHOW INSTANT OPEN)
             ==================================================================== --}}
        <div 
            x-show="formModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="form-msme-modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="formModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="formModalOpen = false; $wire.closeForm()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="formModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-xl border border-slate-200" 
                    @click.stop
                >
                    {{-- Modal Header (Navy Theme #0B3A85) --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-white/10 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Formulir UMKM Warga RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="form-msme-modal-title">
                                    {{ $isEditing ? 'Ubah Profil Usaha Warga' : 'Daftarkan Usaha Warga Baru' }}
                                </h3>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="formModalOpen = false; $wire.closeForm()" 
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                            title="Tutup"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Form Skeleton Loading on openEdit --}}
                    <div wire:loading.flex wire:target="openEdit" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-4 w-32 bg-slate-200 rounded"></div>
                        <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-16 w-full bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Modal Form --}}
                    <div wire:loading.remove wire:target="openEdit">
                        <form wire:submit.prevent="saveMsme" class="p-6 space-y-3.5 text-xs max-h-[75vh] overflow-y-auto">
                            {{-- Pilih Keluarga Pemilik --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Keluarga / Kepala KK Pemilik Usaha <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    wire:model="form_household_id" 
                                    class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                >
                                    <option value="">-- Pilih Kartu Keluarga Pemilik --</option>
                                    @foreach($householdList as $hh)
                                        <option value="{{ $hh->id }}">
                                            KK No. {{ $hh->no_kk }} - {{ $hh->headOfHousehold ? $hh->headOfHousehold->nama : 'Tanpa Kepala KK' }} ({{ $hh->alamat }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('form_household_id') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                            </div>

                            {{-- Nama Usaha & Kategori --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Nama Usaha / Toko <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="form_nama_usaha" 
                                        placeholder="Contoh: Dapur Bu Lina"
                                        class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                    >
                                    @error('form_nama_usaha') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Kategori Usaha <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="form_kategori" 
                                        class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                    >
                                        @foreach($kategoriList as $kat)
                                            <option value="{{ $kat }}">{{ ucfirst($kat) }}</option>
                                        @endforeach
                                    </select>
                                    @error('form_kategori') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Foto Usaha / Produk --}}
                            <div class="bg-slate-50/70 p-3.5 rounded-xl border border-slate-200/80 space-y-2">
                                <label class="block font-bold text-slate-700">
                                    Foto Usaha / Produk (Opsional)
                                </label>
                                
                                {{-- Preview Foto --}}
                                @if ($form_foto)
                                    <div class="relative w-full max-w-xs h-36 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 group shadow-2xs">
                                        <img src="{{ $form_foto->temporaryUrl() }}" alt="Preview Unggahan Foto" class="w-full h-full object-cover">
                                        <button type="button" wire:click="clearPhoto" class="absolute top-2 right-2 bg-rose-600 text-white p-1 rounded-lg text-xs hover:bg-rose-700 shadow-sm transition cursor-pointer" title="Batalkan Foto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                        <span class="absolute bottom-1.5 left-2 bg-black/60 text-white text-[10px] font-semibold px-2 py-0.5 rounded backdrop-blur-sm">Siap Dioptimasi ke WebP</span>
                                    </div>
                                @elseif ($current_foto_path && !$remove_foto)
                                    <div class="relative w-full max-w-xs h-36 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 group shadow-2xs">
                                        <img src="{{ asset('storage/' . $current_foto_path) }}" alt="Foto Usaha Saat Ini" class="w-full h-full object-cover">
                                        <button type="button" wire:click="clearPhoto" class="absolute top-2 right-2 bg-rose-600 text-white p-1 rounded-lg text-xs hover:bg-rose-700 shadow-sm transition cursor-pointer" title="Hapus Foto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                        <span class="absolute bottom-1.5 left-2 bg-emerald-700/80 text-white text-[10px] font-semibold px-2 py-0.5 rounded backdrop-blur-sm">Foto Aktif</span>
                                    </div>
                                @endif

                                {{-- Input File Unggahan --}}
                                <input 
                                    type="file" 
                                    wire:model="form_foto" 
                                    accept="image/jpeg,image/png,image/webp" 
                                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#0B3A85] hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1 bg-white"
                                >
                                <div wire:loading wire:target="form_foto" class="text-[11px] text-[#0B3A85] font-medium flex items-center gap-1.5">
                                    <svg class="animate-spin w-3.5 h-3.5 text-[#0B3A85]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Mengunggah file foto...</span>
                                </div>
                                <p class="text-[11px] text-slate-400">Maks. 5 MB (JPG, PNG, WebP). Otomatis dikompresi dan dikonversi ke WebP untuk hemat storage VPS.</p>
                                @error('form_foto') <span class="text-rose-600 text-[11px] font-semibold block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Kontak & Media Sosial Multi-Platform --}}
                            <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 space-y-3">
                                <div class="text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    <span>Kontak & Media Sosial Usaha</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">
                                            Nomor Telepon / HP <span class="text-rose-500">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            wire:model="form_telepon" 
                                            placeholder="081234567890"
                                            class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800 font-mono"
                                        >
                                        @error('form_telepon') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">
                                            Nomor WhatsApp (Opsional)
                                        </label>
                                        <input 
                                            type="text" 
                                            wire:model="form_whatsapp" 
                                            placeholder="081234567890 (otomatis jika kosong)"
                                            class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800 font-mono"
                                        >
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">
                                            Instagram (Opsional)
                                        </label>
                                        <input 
                                            type="text" 
                                            wire:model="form_instagram" 
                                            placeholder="@akun atau tautan profil"
                                            class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                        >
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1">
                                            TikTok (Opsional)
                                        </label>
                                        <input 
                                            type="text" 
                                            wire:model="form_tiktok" 
                                            placeholder="@akun atau tautan profil"
                                            class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                        >
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block font-bold text-slate-700 mb-1">
                                            Toko Shopee (Opsional)
                                        </label>
                                        <input 
                                            type="text" 
                                            wire:model="form_shopee" 
                                            placeholder="Nama toko atau https://shopee.co.id/namatoko"
                                            class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                        >
                                    </div>
                                </div>
                            </div>

                            {{-- Alamat Toko / Lapak --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Alamat Lokasi Usaha
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="form_alamat" 
                                    placeholder="Contoh: Jl. Merpati No. 15 (Kosongkan jika sama dengan domisili KK)"
                                    class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                >
                            </div>

                            {{-- Produk & Deskripsi --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Produk / Layanan Unggulan
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="form_produk" 
                                    placeholder="Contoh: Sambal Roa Khas Manado, Peyek Teri Renyah"
                                    class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                >
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Deskripsi Profil Usaha
                                </label>
                                <textarea 
                                    wire:model="form_deskripsi" 
                                    rows="3" 
                                    placeholder="Tuliskan cerita singkat tentang usaha, jam operasional, atau cara pemesanan..."
                                    class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                ></textarea>
                            </div>

                            {{-- Checkbox Tayang Aktif --}}
                            <div class="pt-1">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="form_is_active" class="rounded border-slate-300 text-[#0B3A85] focus:ring-[#0B3A85]">
                                    <span class="font-bold text-slate-700">Aktifkan dan tayangkan langsung di Katalog Warga</span>
                                </label>
                            </div>

                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                                <button 
                                    type="button" 
                                    @click="formModalOpen = false; $wire.closeForm()" 
                                    class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-colors cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-5 py-2 rounded-xl bg-[#0B3A85] text-white font-bold hover:bg-[#07275E] transition-colors shadow-xs cursor-pointer"
                                >
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Daftarkan UMKM' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             8. MODAL KONFIRMASI HAPUS (ALPINE JS)
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="delete-msme-modal-title" 
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
                @click="closeDeleteConfirm()"
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-msme-modal-title">Hapus UMKM Warga?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Usaha warga akan dihapus dari katalog RT 15.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Usaha:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteMsmeTitle"></span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button 
                            type="button" 
                            @click="closeDeleteConfirm()" 
                            class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            @click="executeDelete()" 
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition-all shadow-xs cursor-pointer"
                        >
                            Ya, Hapus UMKM
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================================
         9. JAVASCRIPT STANDAR: REAL-TIME SEARCH & FILTER RESET INJECTION
         ==================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initMsmeSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initMsmeSearch();
        });

        function initMsmeSearch() {
            const searchInput = document.getElementById('search-msme-input');
            const clearBtn = document.getElementById('search-msme-clear-btn');
            const statusSelect = document.getElementById('filter-msme-status');
            const kategoriSelect = document.getElementById('filter-msme-kategori');
            const resetContainer = document.getElementById('reset-filter-msme-container');
            const tableBody = document.getElementById('msmes-table-body');

            if (!searchInput) return;

            const urlParams = new URLSearchParams(window.location.search);
            const hasServerFilter = urlParams.has('search') || urlParams.has('status') || urlParams.has('kategori');

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const statusVal = statusSelect ? statusSelect.value : 'semua';
                const kategoriVal = kategoriSelect ? kategoriSelect.value : '';

                // Tampilkan/sembunyikan tombol clear
                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                // Injeksi elemen tombol pembersih pencarian secara dinamis (warna merah menyala & soft background)
                const hasActiveFilter = term.length > 0 || (statusVal !== 'semua' && statusVal !== '') || (kategoriVal !== '');
                let resetBtn = document.getElementById('btn-reset-filter-msme');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-msme';
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

                // Real-time filtering multi-field pada tabel data
                if (tableBody) {
                    const rows = tableBody.querySelectorAll('tr.msme-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        if (term === '' || content.includes(term)) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('msme-client-empty-row');
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
                if (kategoriSelect) kategoriSelect.addEventListener('change', applyFilter);

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
                    const btn = e.target.closest('#btn-reset-filter-msme');
                    if (!btn) return;

                    searchInput.value = '';
                    if (statusSelect) statusSelect.value = 'semua';
                    if (kategoriSelect) kategoriSelect.value = '';
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
                                    comp.set('kategoriFilter', '');
                                }
                            }
                        }
                    }

                    if (hasServerFilter) {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            if (searchInput.value.trim().length > 0 || (statusSelect && statusSelect.value !== 'semua') || (kategoriSelect && kategoriSelect.value !== '')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
