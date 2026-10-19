<x-filament-panels::page>
    <div 
        x-data="{
            deleteConfirmModalOpen: false,
            deleteReportId: null,
            deleteGuestName: '',
            catatPulangModalOpen: false,
            catatPulangId: null,
            catatPulangName: '',
            catatPulangHost: '',
            detailModalOpen: @entangle('detailModalOpen'),
            formModalOpen: @entangle('formModalOpen'),
            confirmDelete(id, name) {
                this.deleteReportId = id;
                this.deleteGuestName = name;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteReportId = null;
                this.deleteGuestName = '';
            },
            executeDelete() {
                if (this.deleteReportId) {
                    const id = this.deleteReportId;
                    this.closeDeleteConfirm();
                    $wire.deleteGuestReport(id);
                }
            },
            confirmCatatPulang(id, name, host) {
                this.catatPulangId = id;
                this.catatPulangName = name;
                this.catatPulangHost = host;
                this.catatPulangModalOpen = true;
            },
            closeCatatPulangConfirm() {
                this.catatPulangModalOpen = false;
                this.catatPulangId = null;
                this.catatPulangName = '';
                this.catatPulangHost = '';
            },
            executeCatatPulang() {
                if (this.catatPulangId) {
                    const id = this.catatPulangId;
                    this.closeCatatPulangConfirm();
                    if (this.detailModalOpen) {
                        this.detailModalOpen = false;
                        $wire.closeDetail();
                    }
                    $wire.catatPulang(id);
                }
            }
        }"
        @keydown.escape.window="closeDeleteConfirm(); closeCatatPulangConfirm(); if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); } if (formModalOpen) { formModalOpen = false; $wire.closeForm(); }"
        class="w-full space-y-4"
    >
        {{-- ====================================================================
             1. HEADER KONTEN (KEAMANAN LINGKUNGAN RT 15 - BIRU UTAMA #0B3A85)
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                {{-- Kategori Subtitle & Tombol Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>KEAMANAN LINGKUNGAN RT 15</span>
                    <x-page-guide 
                        title="Lapor Tamu 1x24 Jam"
                        category="KEAMANAN LINGKUNGAN RT 15"
                        description="Modul pengawasan dan pencatatan tamu atau kerabat luar wilayah yang menginap di lingkungan RT 15 guna mematuhi aturan wajib lapor 1x24 jam dan menjaga ketertiban lingkungan."
                        :steps="[
                            ['title' => 'Pencarian & Filter Status', 'desc' => 'Cari berdasarkan nama tamu, NIK, atau keluarga tuan rumah, serta filter status (Menunggu, Disetujui, Selesai).'],
                            ['title' => 'Catat Tamu Baru', 'desc' => 'Klik tombol biru \'+ Catat Tamu\' jika ada warga yang melapor secara langsung ke pos ronda atau pengurus RT.'],
                            ['title' => 'Validasi Identitas Tamu', 'desc' => 'Gunakan tombol \'Lihat\' untuk memeriksa foto KTP tamu, durasi menginap, serta kontak darurat yang dapat dihubungi.'],
                            ['title' => 'Ekspor Rekap Keamanan', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh buku mutasi tamu menginap dalam bentuk PDF atau Excel.']
                        ]"
                        role="Seksi Keamanan & Poskamling RT"
                        :tips="[
                            'Setiap tamu yang bermalam lebih dari 24 jam wajib mencantumkan identitas resmi (KTP/SIM/Paspor).',
                            'Periksa kejelasan alamat rumah warga RT 15 yang menjadi penjamin atau tempat menginap tamu.',
                            'Data tamu disimpan secara privat untuk keperluan pemeliharaan kamtibmas lingkungan.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Lapor Tamu 1x24 Jam
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Tambah Laporan Tamu) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="guest-reports" :extraParams="['status' => '$wire.statusFilter']" />

                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Lapor Tamu Baru</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. TAB FILTER STATUS & SEARCH BAR & RESET FILTER (STANDAR RESMI)
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
                        id="search-guest-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama tamu, NIK, asal daerah, nama kepala keluarga, atau alamat..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-guest-clear-btn" 
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

            {{-- Wrapper Filter & Reset --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 shrink-0">
                {{-- Filter Pilihan Status Tamu (Dropdown Pengganti Tab) --}}
                <div class="w-full sm:w-56">
                    <select 
                        id="filter-guest-status"
                        name="status"
                        wire:model.live="statusFilter" 
                        class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                    >
                        <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Tamu ({{ $countSemua }})</option>
                        <option value="menginap" {{ $statusFilter === 'menginap' ? 'selected' : '' }}>Sedang Menginap ({{ $countMenginap }})</option>
                        <option value="pulang" {{ $statusFilter === 'pulang' ? 'selected' : '' }}>Sudah Pulang ({{ $countPulang }})</option>
                    </select>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-guest-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || ($statusFilter !== 'semua' && filled($statusFilter)) 
                            || request()->filled('search') 
                            || (request()->has('status') && request('status') !== 'semua' && request('status') !== '');
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter-guest" 
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
             4. TABEL DATA LAPOR TAMU (DEEP NAVY #0B3A85 TABLE HEADER)
             ==================================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="statusFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                text="Memperbarui data laporan tamu..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white">
                            <th class="py-3 px-4 font-bold text-center w-12 shrink-0">NO</th>
                            <th class="py-3 px-4 font-bold min-w-[110px] whitespace-nowrap">Status</th>
                            <th class="py-3 px-4 font-bold min-w-[240px]">Identitas Tamu</th>
                            <th class="py-3 px-4 font-bold min-w-[220px]">Keluarga Tuan Rumah</th>
                            <th class="py-3 px-4 font-bold min-w-[160px] whitespace-nowrap">Tanggal Berkunjung</th>
                            <th class="py-3 px-4 font-bold min-w-[160px]">Tujuan</th>
                            <th class="py-3 px-4 font-bold text-center w-36 min-w-[140px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody 
                        id="guests-table-body" 
                        class="divide-y divide-slate-100 transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="statusFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                    >
                        @forelse($reports as $index => $item)
                            @php
                                $isMenginap = ($item->status ?? 'menginap') === 'menginap';
                            @endphp
                            <tr 
                                class="hover:bg-slate-50/80 transition-colors guest-data-row"
                                data-status="{{ $item->status ?? 'menginap' }}"
                            >
                                {{-- Kolom Nomor Urut --}}
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-500 text-xs whitespace-nowrap">
                                    {{ $reports->firstItem() ? ($reports->firstItem() + $index) : ($index + 1) }}
                                </td>

                                {{-- Kolom Status --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($isMenginap)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Menginap
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            Sudah Pulang
                                        </span>
                                    @endif
                                </td>

                                {{-- Kolom Nama Tamu & NIK --}}
                                <td class="py-3.5 px-4 min-w-[240px]">
                                    <div class="font-extrabold text-[#0F1F3D] text-[13px] hover:text-[#0B3A85] cursor-pointer" wire:click="openDetail({{ $item->id }})">
                                        {{ $item->nama_tamu }}
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        Asal: <strong class="text-slate-700">{{ $item->asal }}</strong>
                                    </div>
                                    @if($item->nik_tamu)
                                        <div class="text-[10px] font-mono text-slate-400 mt-0.5">
                                            NIK: {{ $item->nik_tamu }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Kolom Tuan Rumah --}}
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-[#0F1F3D]">
                                        {{ $item->household && $item->household->headOfHousehold ? $item->household->headOfHousehold->nama_lengkap : 'Keluarga RT 15' }}
                                    </div>
                                    <div class="text-[10.5px] text-slate-400 mt-0.5 truncate max-w-[160px]">
                                        {{ $item->household ? $item->household->alamat_lengkap : '-' }}
                                    </div>
                                </td>

                                {{-- Kolom Tanggal Datang - Pulang --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="text-[11px] font-bold text-slate-700">
                                        Datang: {{ \Carbon\Carbon::parse($item->tanggal_datang)->format('d M Y') }}
                                    </div>
                                    <div class="text-[10.5px] text-slate-500 mt-0.5">
                                        Pulang: {{ $item->tanggal_pulang ? \Carbon\Carbon::parse($item->tanggal_pulang)->format('d M Y') : 'Belum ditentukan' }}
                                    </div>
                                </td>

                                {{-- Kolom Tujuan --}}
                                <td class="py-3.5 px-4">
                                    <p class="text-[11px] text-slate-600 line-clamp-1 max-w-[180px]">
                                        {{ $item->tujuan ?? 'Kunjungan Silaturahmi' }}
                                    </p>
                                </td>

                                {{-- Kolom Aksi dengan Tombol Berwarna Konsisten (Standar SIM-LiBas) --}}
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        {{-- Catat Kepulangan jika sedang menginap (Emerald) --}}
                                        @if($isMenginap)
                                            <button 
                                                type="button" 
                                                @click="confirmCatatPulang({{ $item->id }}, '{{ addslashes($item->nama_tamu) }}', '{{ addslashes($item->household && $item->household->headOfHousehold ? $item->household->headOfHousehold->nama_lengkap : 'Keluarga RT 15') }}')" 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 hover:border-emerald-400 transition-all shadow-2xs cursor-pointer"
                                                title="Catat Pulang Hari Ini"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- Detail Modal (Ikon Mata - Biru) --}}
                                        <button 
                                            type="button" 
                                            @click="detailModalOpen = true"
                                            wire:click="openDetail({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer"
                                            title="Lihat Detail Tamu"
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
                                            title="Ubah Laporan Tamu"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Hapus Modal (Ikon Sampah - Merah/Rose) --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_tamu) }}')" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer"
                                            title="Hapus Laporan Tamu"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="guest-empty-row">
                                <td colspan="7" class="py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">Tidak ada laporan tamu ditemukan</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                        Tidak ada catatan tamu menginap yang sesuai dengan kriteria filter saat ini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Baris Empty State Pencarian Real-Time Client Side --}}
                        <tr id="guest-client-empty-row" style="display: none;">
                            <td colspan="7" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Tidak ada tamu yang cocok</h4>
                                <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Livewire --}}
            @if($reports->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL LAPORAN TAMU (ALPINE.JS X-SHOW INSTANT OPEN)
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Ketertiban Lingkungan RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="modal-title">
                                    Detail Laporan Tamu 1x24 Jam
                                </h3>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false; $wire.closeDetail()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Asynchronous Skeleton Loader while Livewire fetches selectedReport --}}
                    <div wire:loading.flex wire:target="openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-6 w-28 bg-slate-200 rounded-md"></div>
                        <div class="h-6 w-3/4 bg-slate-200 rounded-lg"></div>
                        <div class="h-4 w-1/3 bg-slate-200 rounded-md"></div>
                        <div class="h-24 bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="h-16 bg-slate-100 rounded-xl"></div>
                            <div class="h-16 bg-slate-100 rounded-xl"></div>
                        </div>
                    </div>

                    {{-- Modal Body Content --}}
                    <div wire:loading.remove wire:target="openDetail">
                        @if($selectedReport)
                            @php
                                $isDetailMenginap = ($selectedReport->status ?? 'menginap') === 'menginap';
                            @endphp
                            <div class="p-6 space-y-4 text-xs sm:text-sm text-slate-700 max-h-[75vh] overflow-y-auto">
                                <div class="flex items-center gap-2">
                                    @if($isDetailMenginap)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            SEDANG MENGINAP
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            SUDAH PULANG
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <h2 class="text-base sm:text-lg font-black text-[#0F1F3D]">
                                        {{ $selectedReport->nama_tamu }}
                                    </h2>
                                    <p class="text-xs text-slate-400 mt-1">
                                        Asal: <strong class="text-slate-600">{{ $selectedReport->asal }}</strong>
                                        @if($selectedReport->nik_tamu)
                                            • NIK: <strong class="font-mono text-slate-600">{{ $selectedReport->nik_tamu }}</strong>
                                        @endif
                                    </p>
                                </div>

                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                        Tuan Rumah / Keluarga yang Dikunjungi
                                    </div>
                                    <div class="font-extrabold text-[#0F1F3D] text-sm">
                                        {{ $selectedReport->household && $selectedReport->household->headOfHousehold ? $selectedReport->household->headOfHousehold->nama_lengkap : 'Keluarga RT 15' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Nomor Kartu Keluarga: <span class="font-mono font-bold">{{ $selectedReport->household ? $selectedReport->household->nomor_kk : '-' }}</span>
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Alamat: {{ $selectedReport->household ? $selectedReport->household->alamat_lengkap : '-' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Tanggal Kedatangan</div>
                                        <div class="text-xs font-bold text-slate-800 mt-0.5">
                                            {{ \Carbon\Carbon::parse($selectedReport->tanggal_datang)->translatedFormat('l, d F Y') }}
                                        </div>
                                    </div>

                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Tanggal Kepulangan</div>
                                        <div class="text-xs font-bold text-slate-800 mt-0.5">
                                            {{ $selectedReport->tanggal_pulang ? \Carbon\Carbon::parse($selectedReport->tanggal_pulang)->translatedFormat('l, d F Y') : 'Masih Menginap' }}
                                        </div>
                                    </div>
                                </div>

                                @if($selectedReport->tujuan)
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Tujuan Berkunjung</div>
                                        <div class="text-xs text-slate-700 mt-0.5 leading-relaxed">
                                            {{ $selectedReport->tujuan }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Modal Footer --}}
                            <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex items-center justify-between">
                                @if($isDetailMenginap)
                                    <button 
                                        type="button" 
                                        @click="confirmCatatPulang({{ $selectedReport->id }}, '{{ addslashes($selectedReport->nama_tamu) }}', '{{ addslashes($selectedReport->household && $selectedReport->household->headOfHousehold ? $selectedReport->household->headOfHousehold->nama_lengkap : 'Keluarga RT 15') }}')" 
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Catat Pulang Sekarang</span>
                                    </button>
                                @else
                                    <div></div>
                                @endif

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
                                <div class="h-6 w-28 bg-slate-200 rounded-md"></div>
                                <div class="h-6 w-3/4 bg-slate-200 rounded-lg"></div>
                                <div class="h-24 bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL FORM TAMBAH / UBAH LAPORAN TAMU (ALPINE.JS X-SHOW INSTANT OPEN)
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
                                    Ketertiban Lingkungan RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="form-modal-title">
                                    {{ $isEditing ? 'Ubah Laporan Tamu' : 'Catat Tamu 1x24 Jam Baru' }}
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
                        <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-20 w-full bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Modal Form --}}
                    <div wire:loading.remove wire:target="openEdit">
                        <form wire:submit="saveGuestReport" class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Keluarga Tuan Rumah (KK) <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    wire:model="form_household_id"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                    <option value="">-- Pilih Kepala Keluarga Tuan Rumah --</option>
                                    @foreach($householdList as $hh)
                                        <option value="{{ $hh->id }}">
                                            No. KK: {{ $hh->nomor_kk }} - {{ $hh->headOfHousehold ? $hh->headOfHousehold->nama_lengkap : 'Kepala Keluarga' }} ({{ $hh->alamat_lengkap }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('form_household_id') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Nama Lengkap Tamu <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="form_nama_tamu" 
                                        placeholder="Contoh: Budi Santoso"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_nama_tamu') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        NIK Tamu (Opsional)
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="form_nik_tamu" 
                                        placeholder="16 digit NIK tamu"
                                        maxlength="16"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 font-mono focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_nik_tamu') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Kota / Daerah Asal Tamu <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="form_asal" 
                                    placeholder="Contoh: Bandung, Jawa Barat"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                @error('form_asal') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Tanggal Datang <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        wire:model="form_tanggal_datang" 
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_tanggal_datang') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Tanggal Pulang (Opsional)
                                    </label>
                                    <input 
                                        type="date" 
                                        wire:model="form_tanggal_pulang" 
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_tanggal_pulang') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Tujuan Berkunjung / Keterangan
                                </label>
                                <textarea 
                                    wire:model="form_tujuan" 
                                    rows="3" 
                                    placeholder="Contoh: Silaturahmi keluarga menyambut hari raya..."
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                ></textarea>
                            </div>

                            @if($isEditing)
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Status Kunjungan <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="form_status"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                        <option value="menginap">Sedang Menginap (Aktif)</option>
                                        <option value="pulang">Sudah Pulang</option>
                                    </select>
                                    @error('form_status') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                            @endif

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
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Catat Tamu' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL KONFIRMASI HAPUS LAPORAN TAMU (ALPINE.JS)
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-modal-title">Hapus Laporan Tamu Ini?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Catatan tamu ini akan dihapus dari data sistem.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Tamu:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteGuestName"></span>
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
                            Ya, Hapus Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             8. MODAL KONFIRMASI CATAT KEPULANGAN TAMU (ALPINE.JS)
             ==================================================================== --}}
        <div 
            x-show="catatPulangModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="pulang-modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="catatPulangModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="closeCatatPulangConfirm"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="catatPulangModalOpen"
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
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="pulang-modal-title">Konfirmasi Kepulangan Tamu</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Catat bahwa tamu telah selesai menginap dan meninggalkan lingkungan RT 15.</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700 space-y-2">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Tamu:</span>
                            <span class="font-extrabold text-[#0F1F3D] text-sm" x-text="catatPulangName"></span>
                        </div>
                        <div class="flex items-center justify-between pt-1 border-t border-slate-200/60 text-[11px]">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Tuan Rumah:</span>
                                <span class="font-semibold text-slate-700" x-text="catatPulangHost"></span>
                            </div>
                            <div class="text-right">
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanggal Kepulangan:</span>
                                <span class="font-bold text-emerald-600">{{ now()->translatedFormat('d M Y') }} (Hari Ini)</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button 
                            type="button" 
                            @click="closeCatatPulangConfirm" 
                            class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            @click="executeCatatPulang" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-all shadow-xs cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Ya, Catat Pulang</span>
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
            initGuestSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initGuestSearch();
        });

        if (window.Livewire) {
            window.Livewire.hook('morph.updated', () => {
                initGuestSearch();
            });
        }

        function initGuestSearch() {
            const searchInput = document.getElementById('search-guest-input');
            const clearBtn = document.getElementById('search-guest-clear-btn');
            const statusSelect = document.getElementById('filter-guest-status');
            const resetContainer = document.getElementById('reset-filter-guest-container');
            const tableBody = document.getElementById('guests-table-body');
            const hasServerFilter = {{ (request()->filled('search') || (request()->has('status') && request('status') !== 'semua' && request('status') !== '')) ? 'true' : 'false' }};

            if (!searchInput) return;

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const statusVal = statusSelect ? statusSelect.value : 'semua';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || (statusVal !== 'semua' && statusVal !== '');
                let resetBtn = document.getElementById('btn-reset-filter-guest');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-guest';
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
                    const rows = tableBody.querySelectorAll('tr.guest-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowStatus = row.dataset.status;

                        let matchesSearch = term === '' || content.includes(term);
                        let matchesStatus = (statusVal === 'semua' || !statusVal) ? true : (rowStatus === statusVal);

                        if (matchesSearch && matchesStatus) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('guest-client-empty-row');
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
                    const btn = e.target.closest('#btn-reset-filter-guest');
                    if (!btn) return;

                    searchInput.value = '';
                    if (statusSelect) statusSelect.value = 'semua';
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
                                }
                            }
                        }
                    }

                    if (hasServerFilter) {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            if (searchInput.value.trim().length > 0 || (statusSelect && statusSelect.value !== 'semua')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
