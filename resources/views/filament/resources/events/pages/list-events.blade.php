<x-filament-panels::page>
    <div 
        x-data="{
            deleteConfirmModalOpen: false,
            deleteEventId: null,
            deleteEventTitle: '',
            detailModalOpen: @entangle('detailModalOpen'),
            formModalOpen: @entangle('formModalOpen'),
            confirmDelete(id, title) {
                this.deleteEventId = id;
                this.deleteEventTitle = title;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteEventId = null;
                this.deleteEventTitle = '';
            },
            executeDelete() {
                if (this.deleteEventId) {
                    const id = this.deleteEventId;
                    this.closeDeleteConfirm();
                    $wire.deleteEvent(id);
                }
            }
        }"
        @keydown.escape.window="closeDeleteConfirm(); if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); } if (formModalOpen) { formModalOpen = false; $wire.closeForm(); }"
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
                        title="Agenda Kegiatan Warga"
                        category="KOMUNIKASI WARGA RT 15"
                        description="Modul kalender dan pencatatan kegiatan lingkungan RT 15 seperti kerja bakti, rapat warga, peringatan hari besar nasional (PHBN/PHBI), posyandu, dan senam bersama."
                        :steps="[
                            ['title' => 'Pencarian & Filter Waktu', 'desc' => 'Cari agenda berdasarkan nama kegiatan dan saring jadwal (Mendatang, Sedang Berlangsung, atau Selesai).'],
                            ['title' => 'Tambah Kegiatan Baru', 'desc' => 'Klik tombol biru \'+ Tambah Kegiatan\' untuk menentukan judul, waktu mulai/selesai, lokasi, penanggung jawab, dan estimasi biaya.'],
                            ['title' => 'Aksi & Dokumentasi', 'desc' => 'Gunakan tombol \'Lihat\' untuk membuka detail agenda dan lokasi, \'Ubah\' untuk memperbarui jadwal, atau \'Hapus\' jika acara dibatalkan.'],
                            ['title' => 'Ekspor Kalender Acara', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh rekap jadwal kegiatan warga ke dalam format PDF atau Excel.']
                        ]"
                        role="Seksi Kegiatan, Sosial & Humas RT"
                        :tips="[
                            'Pastikan menentukan tanggal dan jam pelaksanaan dengan akurat agar warga dapat mempersiapkan diri.',
                            'Cantumkan lokasi yang spesifik (misal: Lapangan Bulutangkis RT 15 atau Rumah Ketua RT).',
                            'Kegiatan berstatus \'Mendatang\' akan secara otomatis disorot di widget kalender portal warga.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Agenda Kegiatan Warga
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Tambah Agenda) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="events" :extraParams="['kategori' => '$wire.kategoriFilter', 'waktu' => '$wire.waktuFilter']" />

                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Agenda</span>
                </button>
            </div>
        </div>

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
                        id="search-event-input"
                        name="search"
                        value="{{ $search }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama kegiatan, lokasi, deskripsi, atau kategori acara..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-event-clear-btn" 
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
                    {{-- Filter Pilihan Waktu Kegiatan (Dropdown Pengganti Tab) --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-event-waktu"
                            name="waktu"
                            wire:model.live="waktuFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="semua" {{ $waktuFilter === 'semua' ? 'selected' : '' }}>Semua Agenda ({{ $countSemua }})</option>
                            <option value="mendatang" {{ $waktuFilter === 'mendatang' ? 'selected' : '' }}>Akan Datang ({{ $countMendatang }})</option>
                            <option value="bulan_ini" {{ $waktuFilter === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini ({{ $countBulanIni }})</option>
                            <option value="lewat" {{ $waktuFilter === 'lewat' ? 'selected' : '' }}>Telah Lewat ({{ $countLewat }})</option>
                        </select>
                    </div>

                    {{-- Filter Pilihan Kategori --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-event-kategori"
                            name="kategori"
                            wire:model.live="kategoriFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="">Semua Kategori</option>
                            <option value="kerja_bakti" {{ $kategoriFilter === 'kerja_bakti' ? 'selected' : '' }}>Kerja Bakti</option>
                            <option value="arisan" {{ $kategoriFilter === 'arisan' ? 'selected' : '' }}>Arisan Warga</option>
                            <option value="rapat" {{ $kategoriFilter === 'rapat' ? 'selected' : '' }}>Rapat Warga / Pengurus</option>
                            <option value="pengajian" {{ $kategoriFilter === 'pengajian' ? 'selected' : '' }}>Pengajian / Keagamaan</option>
                            <option value="perayaan" {{ $kategoriFilter === 'perayaan' ? 'selected' : '' }}>Perayaan / 17 Agustus</option>
                            <option value="lainnya" {{ $kategoriFilter === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-event-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || ($waktuFilter !== 'semua' && filled($waktuFilter)) 
                            || filled($kategoriFilter) 
                            || request()->anyFilled(['search', 'kategori']) 
                            || (request()->has('waktu') && request('waktu') !== 'semua' && request('waktu') !== '');
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter-event" 
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
             4. TABEL DATA AGENDA KEGIATAN (DEEP NAVY #0B3A85 TABLE HEADER)
             ==================================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="waktuFilter, kategoriFilter, search, resetFilters, setWaktu, previousPage, nextPage, gotoPage"
                text="Memperbarui data agenda kegiatan..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white">
                            <th class="py-3 px-4 font-bold text-center w-12 shrink-0">NO</th>
                            <th class="py-3 px-4 font-bold min-w-[180px] whitespace-nowrap">Waktu & Tanggal</th>
                            <th class="py-3 px-4 font-bold min-w-[340px] w-[35%]">Nama Agenda & Kategori</th>
                            <th class="py-3 px-4 font-bold min-w-[160px] whitespace-nowrap">Lokasi Pelaksanaan</th>
                            <th class="py-3 px-4 font-bold min-w-[140px] whitespace-nowrap">Penyelenggara</th>
                            <th class="py-3 px-4 font-bold text-center w-36 min-w-[140px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody 
                        id="events-table-body" 
                        class="divide-y divide-slate-100 transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="waktuFilter, kategoriFilter, search, resetFilters, setWaktu, previousPage, nextPage, gotoPage"
                    >
                        @forelse($events as $index => $item)
                            <tr 
                                class="hover:bg-slate-50/80 transition-colors event-data-row"
                                data-kategori="{{ $item->kategori }}"
                            >
                                {{-- Kolom Nomor Urut --}}
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-500 text-xs whitespace-nowrap">
                                    {{ $events->firstItem() ? ($events->firstItem() + $index) : ($index + 1) }}
                                </td>

                                {{-- Kolom Tanggal & Waktu --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-[#0B3A85] flex flex-col items-center justify-center shrink-0">
                                            <span class="text-[9px] font-black uppercase">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('M') }}</span>
                                            <span class="text-sm font-extrabold leading-tight">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d') }}</span>
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-[#0F1F3D]">
                                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('l, d M Y') }}
                                            </div>
                                            <div class="text-[10.5px] text-slate-500 mt-0.5">
                                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('H:i') }}
                                                @if($item->tanggal_selesai)
                                                    - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('H:i') }} WIB
                                                @else
                                                    WIB - Selesai
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom Nama Kegiatan & Kategori --}}
                                <td class="py-3.5 px-4 min-w-[340px]">
                                    <div class="font-extrabold text-[#0F1F3D] text-[13px] hover:text-[#0B3A85] cursor-pointer" wire:click="openDetail({{ $item->id }})">
                                        {{ $item->judul }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase">
                                            {{ str_replace('_', ' ', $item->kategori) }}
                                        </span>
                                        @if($item->is_recurring)
                                            <span class="inline-flex items-center gap-0.5 text-[10.5px] font-bold text-blue-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Rutin
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kolom Lokasi Pelaksanaan --}}
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5 text-slate-700 font-semibold">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>{{ $item->lokasi ?? 'Lingkungan RT 15' }}</span>
                                    </div>
                                </td>

                                {{-- Kolom Penyelenggara --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="text-[11px] font-bold text-slate-700">
                                        {{ $item->penyelenggara ?: ($item->pembuat ? $item->pembuat->name : 'Pengurus RT 15') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        Dibuat: {{ $item->pembuat ? $item->pembuat->name : 'Pengurus' }} • {{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}
                                    </div>
                                </td>

                                {{-- Kolom Aksi dengan Tombol Berwarna Konsisten (Standar SIM-LiBas) --}}
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        {{-- Detail Modal (Ikon Mata - Biru) --}}
                                        <button 
                                            type="button" 
                                            @click="detailModalOpen = true"
                                            wire:click="openDetail({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer"
                                            title="Lihat Detail Agenda"
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
                                            title="Ubah Agenda Kegiatan"
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
                                            title="Hapus Agenda"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="event-empty-row">
                                <td colspan="6" class="py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">Tidak ada agenda kegiatan</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                        Tidak ada jadwal kegiatan warga yang sesuai dengan kriteria filter saat ini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Baris Empty State Pencarian Real-Time Client Side --}}
                        <tr id="event-client-empty-row" style="display: none;">
                            <td colspan="6" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Tidak ada kegiatan yang cocok</h4>
                                <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Livewire --}}
            @if($events->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $events->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL AGENDA (ALPINE.JS X-SHOW INSTANT OPEN)
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
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-xl border border-slate-200" 
                    @click.stop
                >
                    {{-- Modal Header (Navy Theme #0B3A85) --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-white/10 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Agenda Warga RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="modal-title">
                                    Detail Agenda Kegiatan
                                </h3>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false; $wire.closeDetail()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Asynchronous Skeleton Loader while Livewire fetches selectedEvent --}}
                    <div wire:loading.flex wire:target="openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-24 bg-slate-200 rounded-md"></div>
                            <div class="h-6 w-28 bg-slate-200 rounded-md"></div>
                        </div>
                        <div class="h-6 w-3/4 bg-slate-200 rounded-lg"></div>
                        <div class="h-4 w-1/3 bg-slate-200 rounded-md"></div>
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="h-16 bg-slate-100 rounded-xl"></div>
                            <div class="h-16 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-14 bg-slate-100 rounded-xl"></div>
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Modal Body Content --}}
                    <div wire:loading.remove wire:target="openDetail">
                        @if($selectedEvent)
                            <div class="p-6 space-y-4 text-xs sm:text-sm text-slate-700 max-h-[75vh] overflow-y-auto">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 text-[#0B3A85] border border-blue-200 uppercase">
                                        Kategori: {{ str_replace('_', ' ', $selectedEvent->kategori) }}
                                    </span>
                                    @if($selectedEvent->is_recurring)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            AGENDA RUTIN
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <h2 class="text-base sm:text-lg font-black text-[#0F1F3D]">
                                        {{ $selectedEvent->judul }}
                                    </h2>
                                    <div class="flex items-center gap-2 text-xs text-slate-500 mt-1 flex-wrap">
                                        <span>Penyelenggara: <strong class="text-slate-700">{{ $selectedEvent->penyelenggara ?: ($selectedEvent->pembuat ? $selectedEvent->pembuat->name : 'Pengurus RT 15') }}</strong></span>
                                        <span>•</span>
                                        <span>Dibuat oleh: <strong class="text-slate-600">{{ $selectedEvent->pembuat ? $selectedEvent->pembuat->name : 'Pengurus RT' }}</strong></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Waktu Mulai</div>
                                        <div class="text-xs font-bold text-slate-800 mt-0.5">
                                            {{ \Carbon\Carbon::parse($selectedEvent->tanggal_mulai)->translatedFormat('l, d F Y, H:i') }} WIB
                                        </div>
                                    </div>

                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Waktu Selesai</div>
                                        <div class="text-xs font-bold text-slate-800 mt-0.5">
                                            {{ $selectedEvent->tanggal_selesai ? \Carbon\Carbon::parse($selectedEvent->tanggal_selesai)->translatedFormat('l, d F Y, H:i') . ' WIB' : 'Sampai Selesai' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="text-[10.5px] font-bold text-slate-400 uppercase">Lokasi Acara</div>
                                    <div class="text-xs font-bold text-slate-800 mt-0.5">
                                        {{ $selectedEvent->lokasi ?? 'Lingkungan RT 15' }}
                                    </div>
                                </div>

                                @if($selectedEvent->deskripsi)
                                    <div>
                                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                            Deskripsi & Rincian Agenda
                                        </div>
                                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 leading-relaxed whitespace-pre-line text-xs font-normal">
                                            {{ $selectedEvent->deskripsi }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Modal Footer --}}
                            <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex items-center justify-between">
                                <button 
                                    type="button" 
                                    @click="detailModalOpen = false; formModalOpen = true"
                                    wire:click="openEdit({{ $selectedEvent->id }})" 
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white transition-colors cursor-pointer"
                                >
                                    Ubah Agenda
                                </button>

                                <button 
                                    type="button" 
                                    @click="detailModalOpen = false; $wire.closeDetail()" 
                                    class="px-4 py-2 bg-[#0B3A85] hover:bg-[#07275E] text-white rounded-xl text-xs font-bold transition-colors cursor-pointer"
                                >
                                    Tutup
                                </button>
                            </div>
                        @else
                            <div class="p-6 flex-col space-y-4 animate-pulse">
                                <div class="h-6 w-24 bg-slate-200 rounded-md"></div>
                                <div class="h-6 w-3/4 bg-slate-200 rounded-lg"></div>
                                <div class="grid grid-cols-2 gap-3 pt-2">
                                    <div class="h-16 bg-slate-100 rounded-xl"></div>
                                    <div class="h-16 bg-slate-100 rounded-xl"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL FORM TAMBAH / UBAH AGENDA (ALPINE.JS X-SHOW INSTANT OPEN)
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
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-lg border border-slate-200" 
                    @click.stop
                >
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
                                    Agenda Warga RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="form-modal-title">
                                    {{ $isEditing ? 'Ubah Agenda Kegiatan' : 'Tambah Agenda Baru' }}
                                </h3>
                            </div>
                        </div>
                        <button type="button" @click="formModalOpen = false; $wire.closeForm()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Form Skeleton Loading on openEdit --}}
                    <div wire:loading.flex wire:target="openEdit" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-4 w-28 bg-slate-200 rounded"></div>
                        <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-20 w-full bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Modal Form --}}
                    <div wire:loading.remove wire:target="openEdit">
                        <form wire:submit="saveEvent" class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Nama Kegiatan <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="form_judul" 
                                    placeholder="Contoh: Kerja Bakti Massal Saluran Air"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                @error('form_judul') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Kategori Kegiatan <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="form_kategori"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                        <option value="kerja_bakti">Kerja Bakti</option>
                                        <option value="arisan">Arisan Warga</option>
                                        <option value="rapat">Rapat RT</option>
                                        <option value="pengajian">Pengajian</option>
                                        <option value="perayaan">Perayaan / HUT RI</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @error('form_kategori') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Penyelenggara Kegiatan <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="form_penyelenggara" 
                                        placeholder="Contoh: Pengurus RT 15, Karang Taruna, PKK..."
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_penyelenggara') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Lokasi Acara <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="form_lokasi" 
                                    placeholder="Contoh: Balai Warga RT 15"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                @error('form_lokasi') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Waktu Mulai <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        wire:model="form_tanggal_mulai" 
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_tanggal_mulai') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Waktu Selesai (Opsional)
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        wire:model="form_tanggal_selesai" 
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_tanggal_selesai') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="pt-1">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="form_is_recurring" class="rounded border-slate-300 text-[#0B3A85] focus:ring-[#0B3A85]">
                                    <span class="text-xs font-bold text-slate-700">Agenda Rutin Berkala (Bulanan / Mingguan)</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Keterangan / Agenda Kegiatan
                                </label>
                                <textarea 
                                    wire:model="form_deskripsi" 
                                    rows="4" 
                                    placeholder="Rincian informasi agenda, perlengkapan yang perlu dibawa warga, dll..."
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                ></textarea>
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
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Jadwalkan Agenda' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL KONFIRMASI HAPUS AGENDA (ALPINE.JS)
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-modal-title">Hapus Agenda Kegiatan Ini?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini akan memindahkan jadwal ke arsip terhapus.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Agenda:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteEventTitle"></span>
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
                            Ya, Hapus Agenda
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
            initEventSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initEventSearch();
        });

        document.addEventListener('livewire:init', function() {
            if (window.Livewire && window.Livewire.hook) {
                window.Livewire.hook('commit', ({ succeed }) => {
                    succeed(() => {
                        initEventSearch();
                    });
                });
            }
        });

        function initEventSearch() {
            const searchInput = document.getElementById('search-event-input');
            const clearBtn = document.getElementById('search-event-clear-btn');
            const waktuSelect = document.getElementById('filter-event-waktu');
            const kategoriSelect = document.getElementById('filter-event-kategori');
            const resetContainer = document.getElementById('reset-filter-event-container');
            const tableBody = document.getElementById('events-table-body');

            if (!searchInput) return;

            const urlParams = new URLSearchParams(window.location.search);
            const hasServerFilter = urlParams.has('search') || urlParams.has('kategori') || (urlParams.has('waktu') && urlParams.get('waktu') !== 'semua');

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const waktuVal = waktuSelect ? waktuSelect.value : 'semua';
                const katVal = kategoriSelect ? kategoriSelect.value : '';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || (waktuVal !== 'semua' && waktuVal !== '') || katVal !== '';
                let resetBtn = document.getElementById('btn-reset-filter-event');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-event';
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
                    const rows = tableBody.querySelectorAll('tr.event-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowKat = row.dataset.kategori;

                        let matchesSearch = term === '' || content.includes(term);
                        let matchesKat = (!katVal) ? true : (rowKat === katVal);

                        if (matchesSearch && matchesKat) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('event-client-empty-row');
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
                if (waktuSelect) waktuSelect.addEventListener('change', applyFilter);
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
                    const btn = e.target.closest('#btn-reset-filter-event');
                    if (!btn) return;

                    searchInput.value = '';
                    if (waktuSelect) waktuSelect.value = 'semua';
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
                                    comp.set('waktuFilter', 'semua');
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

            if (searchInput.value.trim().length > 0 || (waktuSelect && waktuSelect.value !== 'semua') || (kategoriSelect && kategoriSelect.value !== '')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
