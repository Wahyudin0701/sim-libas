<x-filament-panels::page>
    <div 
        x-data="{
            deleteConfirmModalOpen: false,
            deleteComplaintId: null,
            deleteComplaintTitle: '',
            detailModalOpen: @entangle('detailModalOpen'),
            handlingModalOpen: @entangle('handlingModalOpen'),
            actionModalOpen: false,
            actionType: 'selesai',
            actionComplaintId: null,
            actionComplaintTitle: '',
            actionCatatan: '',
            openActionConfirm(id, title, type) {
                this.actionComplaintId = id;
                this.actionComplaintTitle = title;
                this.actionType = type;
                this.actionCatatan = '';
                this.actionModalOpen = true;
            },
            closeActionConfirm() {
                this.actionModalOpen = false;
                this.actionComplaintId = null;
                this.actionCatatan = '';
            },
            submitAction() {
                if (this.actionCatatan.trim().length === 0) return;
                const id = this.actionComplaintId;
                const catatan = this.actionCatatan.trim();
                const type = this.actionType;
                this.actionModalOpen = false;
                if (type === 'selesai') {
                    $wire.resolveComplaint(id, catatan);
                } else {
                    $wire.rejectComplaint(id, catatan);
                }
            },
            confirmDelete(id, title) {
                this.deleteComplaintId = id;
                this.deleteComplaintTitle = title;
                this.detailModalOpen = false;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteComplaintId = null;
                this.deleteComplaintTitle = '';
            },
            executeDelete() {
                if (this.deleteComplaintId) {
                    const id = this.deleteComplaintId;
                    this.closeDeleteConfirm();
                    $wire.deleteComplaint(id);
                }
            }
        }"
        @keydown.escape.window="if (actionModalOpen) { closeActionConfirm(); } else if (deleteConfirmModalOpen) { closeDeleteConfirm(); } else if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); } else if (handlingModalOpen) { handlingModalOpen = false; $wire.closeHandling(); }"
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
                        title="Pengaduan & Aspirasi Warga"
                        category="KOMUNIKASI WARGA RT 15"
                        description="Modul layanan penerimaan dan penanganan keluhan, aspirasi, saran, serta laporan kerusakan fasilitas umum yang diajukan oleh warga secara langsung atau melalui portal warga."
                        :steps="[
                            ['title' => 'Filter Status & Kategori', 'desc' => 'Gunakan filter Status (Menunggu, Diproses, Selesai, Ditolak) dan Kategori untuk memilah aduan warga.'],
                            ['title' => 'Tinjau & Tanggapi Aduan', 'desc' => 'Klik tombol aksi \'Lihat\' untuk membuka detail laporan, foto bukti keluhan, serta kronologi dari pelapor.'],
                            ['title' => 'Perbarui Progres Solusi', 'desc' => 'Ubah status menjadi \'Diproses\' saat penanganan dimulai, dan \'Selesai\' setelah ada tindak lanjut lapangan beserta catatan penanganan.'],
                            ['title' => 'Ekspor Laporan Rekapitulasi', 'desc' => 'Klik tombol \'Ekspor Data\' untuk membuat rekapitulasi penanganan aspirasi warga dalam bentuk PDF atau Excel.']
                        ]"
                        role="Ketua RT & Pengurus RT"
                        :tips="[
                            'Setiap aduan yang masuk sebaiknya segera direspon dalam waktu maksimal 1x24 jam untuk menjaga kepuasan warga.',
                            'Warga pelapor akan menerima pembaruan status aduan mereka secara transparan melalui portal warga.',
                            'Laporan anonim tetap wajib diverifikasi kebenarannya sebelum dilakukan tindak lanjut fisik di lingkungan.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Pengaduan & Aspirasi Warga
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="complaints" :extraParams="['status' => '$wire.statusFilter', 'kategori' => '$wire.kategoriFilter']" />
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
                        id="search-complaint-input"
                        name="search"
                        value="{{ $search }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari ... " 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-complaint-clear-btn" 
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
                    {{-- Filter Pilihan Status Pengaduan (Dropdown Pengganti Tab) --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-complaint-status"
                            name="status"
                            wire:model.live="statusFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Pengaduan ({{ $countSemua }})</option>
                            <option value="diterima" {{ $statusFilter === 'diterima' ? 'selected' : '' }}>Diterima ({{ $countDiterima }})</option>
                            <option value="diproses" {{ $statusFilter === 'diproses' ? 'selected' : '' }}>Diproses ({{ $countDiproses }})</option>
                            <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai ({{ $countSelesai }})</option>
                            <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak ({{ $countDitolak }})</option>
                        </select>
                    </div>

                    {{-- Filter Kategori Dropdown --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-complaint-kategori"
                            name="kategori"
                            wire:model.live="kategoriFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ $kategoriFilter === $kat ? 'selected' : '' }}>
                                    {{ ucfirst($kat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-complaint-container" class="shrink-0 flex items-center justify-center">
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
                            id="btn-reset-filter-complaint" 
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
             4. TABEL DATA PENGADUAN (DEEP NAVY #0B3A85 TABLE HEADER)
             ==================================================================== --}}
        <div class="bg-white rounded-none md:rounded-2xl border border-slate-200 overflow-hidden shadow-xs relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="statusFilter, kategoriFilter, search, resetFilters, setStatus, previousPage, nextPage, gotoPage"
                text="Memperbarui data pengaduan..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-full md:min-w-[1050px] text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white">
                            <th class="hidden md:table-cell py-3 px-4 font-bold text-center w-12 shrink-0">NO</th>
                            <th class="py-3 px-3 sm:px-4 font-bold md:min-w-[360px] md:w-[35%]"><span class="md:hidden">Judul</span><span class="hidden md:inline">Judul & Keluhan</span></th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[160px] whitespace-nowrap">Kategori & Lokasi</th>
                            <th class="py-3 px-2.5 sm:px-4 font-bold whitespace-nowrap md:min-w-[140px]">Pelapor</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[110px] whitespace-nowrap">Status</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[130px] whitespace-nowrap">Penanganan</th>
                            <th class="py-3 px-2 sm:px-4 font-bold text-center w-14 sm:w-20 md:min-w-[80px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody 
                        id="complaints-table-body" 
                        class="divide-y divide-slate-100 transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="statusFilter, kategoriFilter, search, resetFilters, setStatus, previousPage, nextPage, gotoPage"
                    >
                        @forelse($complaints as $index => $item)
                            <tr 
                                class="complaint-data-row transition-colors {{ $item->status === 'diterima' ? 'bg-amber-50/70 hover:bg-amber-100/70' : 'hover:bg-slate-50/80' }}"
                                data-status="{{ $item->status }}"
                                data-kategori="{{ $item->kategori }}"
                            >
                                {{-- Kolom Nomor Urut (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-3.5 px-4 text-center font-mono font-semibold text-slate-500 text-xs whitespace-nowrap {{ $item->status === 'diterima' ? 'border-l-4 border-l-amber-500' : '' }}">
                                    {{ $complaints->firstItem() ? ($complaints->firstItem() + $index) : ($index + 1) }}
                                </td>

                                {{-- Kolom Judul & Keluhan (Keterangan keluhan disembunyikan di Mobile) --}}
                                <td class="py-3.5 px-3 sm:px-4 md:min-w-[360px] {{ $item->status === 'diterima' ? 'border-l-4 border-l-amber-500 md:border-l-0' : '' }}">
                                    <div class="font-extrabold text-[#0F1F3D] text-[13px] sm:text-[14px] leading-snug hover:text-[#0B3A85] cursor-pointer" wire:click="openDetail({{ $item->id }})">
                                        {{ $item->judul }}
                                    </div>
                                    {{-- Keterangan Keluhan & Waktu Lapor (Hanya Desktop) --}}
                                    <p class="hidden md:block text-slate-500 text-[11px] leading-relaxed line-clamp-2 mt-1">
                                        {{ Str::limit($item->deskripsi, 100) }}
                                    </p>
                                    <div class="hidden md:block text-[10px] text-slate-400 mt-1.5 whitespace-nowrap">
                                        Dilaporkan: {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                                    </div>
                                </td>

                                {{-- Kolom Kategori & Lokasi (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10.5px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ ucfirst($item->kategori) }}
                                    </span>
                                    <div class="flex items-center gap-1 text-[11px] text-slate-500 mt-1">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate max-w-[140px]">{{ $item->lokasi ?? 'Area RT 15' }}</span>
                                    </div>
                                </td>

                                {{-- Kolom Pelapor --}}
                                <td class="py-3.5 px-2.5 sm:px-4 whitespace-nowrap">
                                    @if($item->is_anonim)
                                        <div class="flex items-center gap-1 text-slate-500 font-bold text-[11px] sm:text-xs">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                            </svg>
                                            <span>Warga (Anonim)</span>
                                        </div>
                                    @else
                                        <div class="font-bold text-[#0F1F3D] text-[11px] sm:text-xs">
                                            {{ $item->pelapor ? $item->pelapor->name : 'Warga RT' }}
                                        </div>
                                        <div class="hidden md:block text-[10px] text-slate-400 mt-0.5">
                                            {{ $item->pelapor ? $item->pelapor->email : '' }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Kolom Status (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-3.5 px-4 whitespace-nowrap">
                                    @if($item->status === 'diterima')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Diterima
                                        </span>
                                    @elseif($item->status === 'diproses')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                            Diproses
                                        </span>
                                    @elseif($item->status === 'selesai')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                {{-- Kolom Penanganan (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-3.5 px-4">
                                    <div class="text-[11px] text-slate-700 font-semibold truncate max-w-[150px]">
                                        {{ $item->penanggungJawab ? $item->penanggungJawab->name : '-' }}
                                    </div>
                                    @if($item->catatan_penanganan)
                                        <div class="text-[10px] text-slate-400 truncate max-w-[150px]" title="{{ $item->catatan_penanganan }}">
                                            {{ $item->catatan_penanganan }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Kolom Aksi: Hanya Tombol Mata (Sesuai Alur Standar SIM-LiBas) --}}
                                <td class="py-3.5 px-2 sm:px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        {{-- Detail Modal (Ikon Mata - Biru) --}}
                                        <button 
                                            type="button" 
                                            @click="detailModalOpen = true"
                                            wire:click="openDetail({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer"
                                            title="Lihat Detail Pengaduan"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="complaint-empty-row">
                                <td colspan="7" class="py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">Tidak ada pengaduan ditemukan</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                        Belum ada aspirasi atau laporan warga yang sesuai dengan kriteria filter yang dipilih.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Baris Empty State Pencarian Real-Time Client Side --}}
                        <tr id="complaint-client-empty-row" style="display: none;">
                            <td colspan="7" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Tidak ada pengaduan yang cocok</h4>
                                <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Livewire --}}
            @if($complaints->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $complaints->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL PENGADUAN (ALPINE.JS X-SHOW INSTANT OPEN)
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Layanan Aspirasi Warga RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="modal-title">
                                    Detail Pengaduan Warga
                                </h3>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false; $wire.closeDetail()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Asynchronous Skeleton Loader while Livewire fetches selectedComplaint --}}
                    <div wire:loading.flex wire:target="openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-28 bg-slate-200 rounded-md"></div>
                            <div class="h-6 w-24 bg-slate-200 rounded-md"></div>
                        </div>
                        <div class="h-6 w-3/4 bg-slate-200 rounded-lg"></div>
                        <div class="h-4 w-1/3 bg-slate-200 rounded-md"></div>
                        <div class="h-24 bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div class="h-14 bg-slate-100 rounded-lg"></div>
                            <div class="h-14 bg-slate-100 rounded-lg"></div>
                        </div>
                    </div>

                    {{-- Modal Body Content --}}
                    <div wire:loading.remove wire:target="openDetail">
                        @if($selectedComplaint)
                            <div class="p-6 space-y-4 text-xs sm:text-sm text-slate-700 max-h-[75vh] overflow-y-auto">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                        Kategori: {{ strtoupper($selectedComplaint->kategori) }}
                                    </span>

                                    @if($selectedComplaint->status === 'diterima')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            STATUS: DITERIMA
                                        </span>
                                    @elseif($selectedComplaint->status === 'diproses')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                            STATUS: DIPROSES
                                        </span>
                                    @elseif($selectedComplaint->status === 'selesai')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            STATUS: SELESAI
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            STATUS: DITOLAK
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <h2 class="text-base sm:text-lg font-black text-[#0F1F3D]">
                                        {{ $selectedComplaint->judul }}
                                    </h2>
                                    <div class="flex items-center gap-2 mt-1 text-xs text-slate-400">
                                        <span>Pelapor: <strong class="text-slate-600">{{ $selectedComplaint->is_anonim ? 'Anonim (Disamarkan)' : ($selectedComplaint->pelapor ? $selectedComplaint->pelapor->name : 'Warga RT') }}</strong></span>
                                        <span>•</span>
                                        <span>{{ $selectedComplaint->created_at ? $selectedComplaint->created_at->format('d F Y, H:i') : '-' }} WIB</span>
                                    </div>
                                </div>

                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 leading-relaxed whitespace-pre-line text-xs sm:text-sm font-normal">
                                    {{ $selectedComplaint->deskripsi }}
                                </div>

                                <div class="grid grid-cols-2 gap-3 pt-1">
                                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Lokasi Kejadian</div>
                                        <div class="text-xs font-bold text-slate-700 mt-0.5">
                                            {{ $selectedComplaint->lokasi ?? 'Wilayah RT 15' }}
                                        </div>
                                    </div>
                                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Penanggung Jawab</div>
                                        <div class="text-xs font-bold text-slate-700 mt-0.5">
                                            {{ $selectedComplaint->penanggungJawab ? $selectedComplaint->penanggungJawab->name : 'Belum Ditugaskan' }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Catatan Penanganan Tersimpan jika status 'selesai' atau 'ditolak' --}}
                                @if(in_array($selectedComplaint->status, ['selesai', 'ditolak']) && $selectedComplaint->catatan_penanganan)
                                    <div class="p-4 rounded-xl {{ $selectedComplaint->status === 'selesai' ? 'bg-emerald-50/70 border border-emerald-200' : 'bg-rose-50/70 border border-rose-200' }}">
                                        <div class="text-[11px] font-bold {{ $selectedComplaint->status === 'selesai' ? 'text-emerald-800' : 'text-rose-800' }} uppercase tracking-wider mb-1">
                                            {{ $selectedComplaint->status === 'selesai' ? 'Catatan Penanganan & Penyelesaian' : 'Alasan / Catatan Penolakan' }}
                                        </div>
                                        <p class="text-xs {{ $selectedComplaint->status === 'selesai' ? 'text-emerald-900' : 'text-rose-900' }} leading-relaxed">
                                            {{ $selectedComplaint->catatan_penanganan }}
                                        </p>
                                        @if($selectedComplaint->tanggal_selesai)
                                            <div class="text-[10.5px] text-emerald-700 font-semibold mt-2">
                                                Diselesaikan pada: {{ \Carbon\Carbon::parse($selectedComplaint->tanggal_selesai)->format('d F Y, H:i') }} WIB
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Modal Footer --}}
                            <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap">
                                <div>
                                    @if($selectedComplaint->status === 'diproses')
                                        <div class="flex items-center gap-2">
                                            {{-- Tombol Selesai (Emerald/Green) --}}
                                            <button 
                                                type="button" 
                                                @click="openActionConfirm({{ $selectedComplaint->id }}, '{{ addslashes($selectedComplaint->judul) }}', 'selesai')" 
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-all shadow-xs hover:shadow-md cursor-pointer"
                                                title="Selesaikan pengaduan ini dengan konfirmasi dan catatan"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Selesai</span>
                                            </button>

                                            {{-- Tombol Ditolak (Rose/Red) --}}
                                            <button 
                                                type="button" 
                                                @click="openActionConfirm({{ $selectedComplaint->id }}, '{{ addslashes($selectedComplaint->judul) }}', 'ditolak')" 
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition-all shadow-xs hover:shadow-md cursor-pointer"
                                                title="Tolak pengaduan ini dengan konfirmasi dan alasan"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                <span>Ditolak</span>
                                            </button>
                                        </div>
                                    @elseif(in_array($selectedComplaint->status, ['selesai', 'ditolak']))
                                        {{-- Tombol Hapus Pengaduan --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $selectedComplaint->id }}, '{{ addslashes($selectedComplaint->judul) }}')" 
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition-colors cursor-pointer"
                                            title="Hapus pengaduan ini"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                </div>

                                <button 
                                    type="button" 
                                    @click="detailModalOpen = false; $wire.closeDetail()" 
                                    class="px-4 py-2 bg-[#0B3A85] hover:bg-[#07275E] text-white rounded-xl text-xs font-bold transition-colors cursor-pointer ml-auto"
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
             6. MODAL TANGANI / UPDATE STATUS PENGADUAN (ALPINE.JS X-SHOW INSTANT OPEN)
             ==================================================================== --}}
        <div 
            x-show="handlingModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="form-handling-modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="handlingModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="handlingModalOpen = false; $wire.closeHandling()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="handlingModalOpen"
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Respon Pengurus RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="form-handling-modal-title">
                                    Tindak Lanjut Pengaduan
                                </h3>
                            </div>
                        </div>
                        <button type="button" @click="handlingModalOpen = false; $wire.closeHandling()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Form Skeleton Loading on openHandling --}}
                    <div wire:loading.flex wire:target="openHandling" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-4 w-28 bg-slate-200 rounded"></div>
                        <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        <div class="h-4 w-36 bg-slate-200 rounded"></div>
                        <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        <div class="h-4 w-32 bg-slate-200 rounded"></div>
                        <div class="h-24 w-full bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Form Body --}}
                    <div wire:loading.remove wire:target="openHandling">
                        <form wire:submit="saveHandling" class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Status Pengaduan <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    wire:model="form_status"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                    <option value="diterima">Diterima (Menunggu Tindak Lanjut)</option>
                                    <option value="diproses">Sedang Diproses (Petugas Turun Lapangan)</option>
                                    <option value="selesai">Selesai (Tuntas Ditangani)</option>
                                    <option value="ditolak">Ditolak (Tidak Valid / Diluar Kewenangan)</option>
                                </select>
                                @error('form_status') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Penanggung Jawab Penanganan
                                </label>
                                <select 
                                    wire:model="form_penanggung_jawab_id"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                    <option value="">-- Pilih Pengurus Penanggung Jawab --</option>
                                    @foreach($pengurusList as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Catatan Penanganan & Tindakan
                                </label>
                                <textarea 
                                    wire:model="form_catatan_penanganan" 
                                    rows="4" 
                                    placeholder="Jelaskan langkah yang telah atau sedang diambil oleh pengurus RT..."
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                ></textarea>
                                @error('form_catatan_penanganan') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Form Footer --}}
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button 
                                    type="button" 
                                    @click="handlingModalOpen = false; $wire.closeHandling()" 
                                    class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-4 py-2 rounded-xl text-xs font-bold bg-[#0B3A85] hover:bg-[#07275E] text-white transition-all shadow-xs cursor-pointer"
                                >
                                    Simpan Status & Tindakan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL KONFIRMASI HAPUS PENGADUAN (ALPINE.JS)
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-modal-title">Hapus Pengaduan Ini?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini akan memindahkan laporan ke arsip terhapus.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Judul Pengaduan:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteComplaintTitle"></span>
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
                            Ya, Hapus Pengaduan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             8. MODAL KONFIRMASI TINDAK LANJUT / STATUS PENGADUAN (SELESAI / TOLAK)
             ==================================================================== --}}
        <div 
            x-show="actionModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-[60] overflow-y-auto" 
            aria-labelledby="action-modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="actionModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="closeActionConfirm"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="actionModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-lg border border-slate-200 p-6 space-y-4"
                    @click.stop
                >
                    {{-- Header dengan Icon Dinamis --}}
                    <div class="flex items-start gap-3.5">
                        <div 
                            class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                            :class="actionType === 'selesai' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'"
                        >
                            {{-- Icon Selesai --}}
                            <svg x-show="actionType === 'selesai'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{-- Icon Ditolak --}}
                            <svg x-show="actionType === 'ditolak'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 
                                class="text-sm sm:text-base font-extrabold text-[#0F1F3D]" 
                                id="action-modal-title"
                                x-text="actionType === 'selesai' ? 'Konfirmasi Penyelesaian Pengaduan' : 'Konfirmasi Penolakan Pengaduan'"
                            ></h3>
                            <p 
                                class="text-xs text-slate-500 mt-0.5 leading-relaxed"
                                x-text="actionType === 'selesai' ? 'Tandai pengaduan ini telah selesai ditindaklanjuti oleh pengurus RT.' : 'Pengaduan tidak dapat diterima atau ditindaklanjuti. Berikan penjelasan kepada pelapor.'"
                            ></p>
                        </div>
                    </div>

                    {{-- Info Pengaduan --}}
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Judul Pengaduan</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="actionComplaintTitle"></span>
                    </div>

                    {{-- Textarea Catatan / Respon Pengurus --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-700">
                                <span x-text="actionType === 'selesai' ? 'Catatan Tindak Lanjut / Respon Pengurus' : 'Alasan Penolakan Pengaduan'"></span>
                                <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <span class="text-[10.5px] font-semibold text-rose-500">Wajib diisi</span>
                        </div>
                        <textarea 
                            x-model="actionCatatan" 
                            rows="4" 
                            :placeholder="actionType === 'selesai' ? 'Tuliskan tindakan yang telah dilakukan untuk menyelesaikan pengaduan...' : 'Tuliskan alasan penolakan pengaduan ini...'"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all resize-y"
                        ></textarea>
                        
                        {{-- Validasi & Indikator Karakter --}}
                        <div class="flex items-center justify-between text-[11px] pt-0.5">
                            <div>
                                <span x-show="actionCatatan.trim().length === 0" class="text-rose-500 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Catatan wajib diisi dan tidak boleh hanya berisi spasi
                                </span>
                                <span x-show="actionCatatan.trim().length > 0" class="text-emerald-600 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Catatan siap disimpan
                                </span>
                            </div>
                            <span class="text-slate-400 font-medium" x-text="actionCatatan.trim().length + ' karakter'"></span>
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button 
                            type="button" 
                            @click="closeActionConfirm" 
                            class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            @click="submitAction" 
                            :disabled="actionCatatan.trim().length === 0"
                            :class="actionCatatan.trim().length === 0 
                                ? 'opacity-50 cursor-not-allowed bg-slate-300 text-slate-500' 
                                : (actionType === 'selesai' ? 'bg-emerald-600 hover:bg-emerald-700 text-white cursor-pointer hover:shadow-md' : 'bg-rose-600 hover:bg-rose-700 text-white cursor-pointer hover:shadow-md')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs"
                        >
                            <span x-show="actionType === 'selesai'" class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Konfirmasi Selesai</span>
                            </span>
                            <span x-show="actionType === 'ditolak'" class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Konfirmasi Tolak</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================================
         9. SCRIPT REAL-TIME CLIENT-SIDE SEARCH & DYNAMIC RESET FILTER
         ATURAN STANDAR PENGEMBANGAN: SEARCH BAR & FILTER TABEL
         ==================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initComplaintSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initComplaintSearch();
        });

        document.addEventListener('livewire:init', function() {
            if (window.Livewire && window.Livewire.hook) {
                window.Livewire.hook('commit', ({ succeed }) => {
                    succeed(() => {
                        initComplaintSearch();
                    });
                });
            }
        });

        function initComplaintSearch() {
            const searchInput = document.getElementById('search-complaint-input');
            const clearBtn = document.getElementById('search-complaint-clear-btn');
            const statusSelect = document.getElementById('filter-complaint-status');
            const kategoriSelect = document.getElementById('filter-complaint-kategori');
            const resetContainer = document.getElementById('reset-filter-complaint-container');
            const tableBody = document.getElementById('complaints-table-body');

            if (!searchInput) return;

            const urlParams = new URLSearchParams(window.location.search);
            const hasServerFilter = urlParams.has('search') || urlParams.has('kategori') || (urlParams.has('status') && urlParams.get('status') !== 'semua');

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const statusVal = statusSelect ? statusSelect.value : 'semua';
                const katVal = kategoriSelect ? kategoriSelect.value : '';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || (statusVal !== 'semua' && statusVal !== '') || katVal !== '';
                let resetBtn = document.getElementById('btn-reset-filter-complaint');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-complaint';
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
                    const rows = tableBody.querySelectorAll('tr.complaint-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowStatus = row.dataset.status;
                        const rowKategori = row.dataset.kategori;

                        let matchesSearch = term === '' || content.includes(term);
                        let matchesStatus = (statusVal === 'semua' || !statusVal) ? true : (rowStatus === statusVal);
                        let matchesKategori = (!kategoriVal) ? true : (rowKategori && rowKategori.toLowerCase() === kategoriVal.toLowerCase());

                        if (matchesSearch && matchesStatus && matchesKategori) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('complaint-client-empty-row');
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
                    const btn = e.target.closest('#btn-reset-filter-complaint');
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
