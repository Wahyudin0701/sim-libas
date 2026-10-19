<x-filament-panels::page>
    <div 
        x-data="{
            detailLetterModalOpen: @entangle('detailLetterModalOpen'),
            approveLetterModalOpen: @entangle('approveLetterModalOpen'),
            rejectLetterModalOpen: @entangle('rejectLetterModalOpen'),
            letterModalOpen: @entangle('letterModalOpen'),
            deleteConfirmModalOpen: false,
            deleteLetterId: null,
            deleteLetterInfo: '',
            confirmDelete(id, info) {
                this.deleteLetterId = id;
                this.deleteLetterInfo = info;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteLetterId = null;
                this.deleteLetterInfo = '';
            },
            executeDelete() {
                if (this.deleteLetterId) {
                    const id = this.deleteLetterId;
                    this.closeDeleteConfirm();
                    $wire.deleteLetter(id);
                }
            },
        }" 
        @close-detail-letter-modal.window="detailLetterModalOpen = false"
        @open-detail-letter-modal.window="detailLetterModalOpen = true"
        @close-approve-letter-modal.window="approveLetterModalOpen = false"
        @open-approve-letter-modal.window="approveLetterModalOpen = true"
        @close-reject-letter-modal.window="rejectLetterModalOpen = false"
        @open-reject-letter-modal.window="rejectLetterModalOpen = true"
        @close-letter-form-modal.window="letterModalOpen = false"
        @open-letter-form-modal.window="letterModalOpen = true"
        @keydown.escape.window="
            closeDeleteConfirm();
            detailLetterModalOpen = false;
            approveLetterModalOpen = false;
            rejectLetterModalOpen = false;
            letterModalOpen = false;
            if ($wire.detailLetterModalOpen) $wire.closeDetailLetter();
            if ($wire.approveLetterModalOpen) $wire.closeApproveLetter();
            if ($wire.rejectLetterModalOpen) $wire.closeRejectLetter();
            if ($wire.letterModalOpen) $wire.closeLetterModal();
        " 
        class="w-full space-y-4"
    >
        {{-- ====================================================================
             1. HEADER KONTEN (DOMINAN BIRU UTAMA #0B3A85)
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                {{-- Kategori Subtitle (Biru Utama) & Tombol Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>LAYANAN ADMINISTRASI RT 15</span>
                    <x-page-guide 
                        title="Surat Pengantar RT"
                        category="LAYANAN ADMINISTRASI RT 15"
                        description="Modul pelayanan penerbitan surat pengantar resmi RT 15 untuk keperluan warga ke Kelurahan, Kecamatan, Kepolisian (SKCK), Disdukcapil, pembuatan KTP, KK, dan surat keterangan domisili/kematian/kelahiran."
                        :steps="[
                            ['title' => 'Filter Pengajuan Surat', 'desc' => 'Gunakan filter Status Surat (Menunggu, Disetujui, Ditolak, Selesai) dan Jenis Format Surat untuk memilah permohonan.'],
                            ['title' => 'Buat Surat Pengantar Baru', 'desc' => 'Klik tombol biru \'+ Buat Surat\' jika ada warga yang datang mengurus langsung secara tatap muka.'],
                            ['title' => 'Verifikasi Berkas & Tanda Tangan', 'desc' => 'Gunakan tombol \'Lihat\' untuk cek lampiran berkas pemohon, lalu klik \'Setujui\' untuk men-generate nomor surat resmi.'],
                            ['title' => 'Cetak Surat & Ekspor', 'desc' => 'Klik tombol \'Cetak\' untuk mencetak surat format resmi bertanda tangan Ketua RT, atau \'Ekspor Data\' untuk rekap arsip.']
                        ]"
                        role="Sekretaris RT & Ketua RT"
                        :tips="[
                            'Nomor registrasi surat resmi akan ter-generate otomatis secara urut saat status disetujui.',
                            'Warga yang mengajukan mandiri lewat portal dapat langsung mengunduh surat PDF yang telah disetujui.',
                            'Pastikan pemohon telah melengkapi persyaratan berkas yang diminta sesuai jenis surat.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Surat Pengantar
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Tambah) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="letters" :extraParams="['status' => '$wire.statusFilter', 'letter_type_id' => '$wire.typeFilter']" />

                {{-- Tombol Primer: Buat Surat Pengantar --}}
                <button 
                    type="button" 
                    @click="letterModalOpen = true"
                    wire:click="openCreateLetter" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Buat Surat Pengantar</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. FILTER CEPAT STATUS & SEARCH BAR STANDAR
             ==================================================================== --}}
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center lg:justify-between gap-2.5">
            {{-- Input Search Real-Time Client-Side & Server-Side Preservasi SSR --}}
            <div class="relative w-full sm:max-w-md">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#94A3B8]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    {{-- Input dengan atribut name="search" dan value="{{ request('search') }}" sesuai standar --}}
                    <input 
                        type="text" 
                        id="search-letters-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nomor surat, jenis surat, nama warga / pemohon, NIK, atau keperluan..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    {{-- Tombol Hapus Cepat di dalam Search Input --}}
                    <button 
                        type="button" 
                        id="search-letters-clear-btn" 
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
                    {{-- Filter Pilihan Status Surat (Dropdown Pengganti Tab) --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-letter-status"
                            name="status"
                            wire:model.live="statusFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Status ({{ $totalSemua }})</option>
                            <option value="diajukan" {{ $statusFilter === 'diajukan' ? 'selected' : '' }}>Diajukan ({{ $totalDiajukan }})</option>
                            <option value="diperiksa" {{ $statusFilter === 'diperiksa' ? 'selected' : '' }}>Diperiksa ({{ $totalDiperiksa }})</option>
                            <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui ({{ $totalDisetujui }})</option>
                            <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak ({{ $totalDitolak }})</option>
                            <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai ({{ $totalSelesai }})</option>
                        </select>
                    </div>

                    {{-- Filter Pilihan Jenis Surat --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-letter-type"
                            name="type"
                            wire:model.live="typeFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="">Semua Jenis Surat</option>
                            @foreach($letterTypes as $type)
                                <option value="{{ $type->id }}" {{ (request('type') == $type->id || $typeFilter == $type->id) ? 'selected' : '' }}>
                                    [{{ $type->kode }}] {{ $type->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || ($statusFilter !== 'semua' && filled($statusFilter)) 
                            || filled($typeFilter) 
                            || request()->anyFilled(['search', 'type']) 
                            || (request()->has('status') && request('status') !== 'semua' && request('status') !== '');
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter" 
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
             3. TABEL DATA SURAT PENGANTAR (HEADER DEEP NAVY #0B3A85)
             ==================================================================== --}}
        <div class="bg-white rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="statusFilter, typeFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                text="Memperbarui data surat pengantar..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1150px] text-left border-collapse">
                    {{-- Header Tabel Deep Navy --}}
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-3 min-w-[170px] whitespace-nowrap">NOMOR SURAT</th>
                            <th class="py-2.5 px-3 min-w-[190px] whitespace-nowrap">JENIS SURAT</th>
                            <th class="py-2.5 px-3 min-w-[210px] whitespace-nowrap">PEMOHON</th>
                            <th class="py-2.5 px-3 min-w-[220px]">KEPERLUAN</th>
                            <th class="py-2.5 px-3 text-center min-w-[100px] whitespace-nowrap">STATUS</th>
                            <th class="py-2.5 px-3 min-w-[130px] whitespace-nowrap">DIAJUKAN PADA</th>
                            <th class="py-2.5 px-3 text-center min-w-[150px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>

                    {{-- Isi Data Surat Pengantar --}}
                    <tbody 
                        id="letter-table-body" 
                        class="divide-y divide-[#F1F5F9] text-xs transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="statusFilter, typeFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                    >
                        {{-- Row Kosong Real-time Client-Side --}}
                        <tr id="letter-client-empty-row" style="display: none;">
                            <td colspan="8" class="py-12 text-center text-sm text-[#5B6B82]">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-[#0F1F3D]">Tidak ada surat pengantar yang cocok.</p>
                                <p class="text-xs text-[#94A3B8] mt-0.5">Tidak ditemukan data pada baris yang sesuai dengan kata kunci pencarian.</p>
                            </td>
                        </tr>

                        @forelse($letters as $letter)
                            <tr 
                                class="letter-data-row hover:bg-[#F8FAFC] transition-colors {{ $letter->status === 'diajukan' ? 'bg-amber-50/20' : '' }}"
                                data-status="{{ $letter->status }}"
                                data-type="{{ $letter->letter_type_id }}"
                            >
                                {{-- 1. NO --}}
                                <td class="py-2.5 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $letters->firstItem() ? ($letters->firstItem() + $loop->index) : $loop->iteration }}
                                </td>

                                {{-- 2. NOMOR SURAT --}}
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    @if($letter->nomor_surat)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF] whitespace-nowrap">
                                            {{ $letter->nomor_surat }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10.5px] font-medium bg-slate-100 text-slate-500 border border-slate-200 whitespace-nowrap">
                                            (Belum Terbit)
                                        </span>
                                    @endif
                                </td>

                                {{-- 3. JENIS SURAT --}}
                                <td class="py-2.5 px-3 min-w-[190px]">
                                    <div class="font-bold text-xs sm:text-sm text-[#0F1F3D]">
                                        {{ $letter->letterType?->nama ?? 'Surat Pengantar' }}
                                    </div>
                                    <div class="mt-0.5">
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-mono font-bold bg-slate-100 text-[#0B3A85] border border-slate-200">
                                            {{ $letter->letterType?->kode ?? 'SP' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- 4. PEMOHON --}}
                                <td class="py-2.5 px-3 min-w-[210px]">
                                    <div class="font-bold text-xs sm:text-sm text-[#0F1F3D] whitespace-nowrap">
                                        {{ $letter->resident?->nama ?? '-' }}
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                        <span class="text-[10.5px] font-mono text-[#5B6B82] select-all whitespace-nowrap">
                                            NIK: {{ $letter->resident?->masked_nik ?? '-' }}
                                        </span>
                                        @if($letter->resident?->id_warga)
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-mono font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF]">
                                                {{ $letter->resident->id_warga }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- 5. KEPERLUAN --}}
                                <td class="py-2.5 px-3 min-w-[220px]">
                                    <div class="text-xs text-[#0F1F3D] font-medium line-clamp-2" title="{{ $letter->keperluan }}">
                                        {{ $letter->keperluan }}
                                    </div>
                                    @if($letter->catatan_pengurus)
                                        <div class="text-[10.5px] text-[#5B6B82] italic mt-0.5 line-clamp-1" title="{{ $letter->catatan_pengurus }}">
                                            Catatan: {{ $letter->catatan_pengurus }}
                                        </div>
                                    @endif
                                    @if($letter->alasan_tolak && $letter->status === 'ditolak')
                                        <div class="text-[10.5px] text-rose-600 font-medium mt-0.5 line-clamp-1" title="{{ $letter->alasan_tolak }}">
                                            Tolak: {{ $letter->alasan_tolak }}
                                        </div>
                                    @endif
                                </td>

                                {{-- 6. STATUS --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($letter->status === 'diajukan')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                            Diajukan
                                        </span>
                                    @elseif($letter->status === 'diperiksa')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-sky-50 text-sky-700 border border-sky-200 whitespace-nowrap">
                                            Diperiksa
                                        </span>
                                    @elseif($letter->status === 'disetujui')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                            Disetujui
                                        </span>
                                    @elseif($letter->status === 'ditolak')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">
                                            Ditolak
                                        </span>
                                    @elseif($letter->status === 'selesai')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 text-slate-700 border border-slate-200 whitespace-nowrap">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 text-slate-700 border border-slate-200 whitespace-nowrap">
                                            {{ ucfirst($letter->status) }}
                                        </span>
                                    @endif
                                </td>

                                {{-- 7. DIAJUKAN PADA --}}
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <div class="font-semibold text-xs text-[#0F1F3D]">
                                        {{ $letter->created_at?->translatedFormat('d M Y') ?? '-' }}
                                    </div>
                                    <div class="text-[10.5px] text-[#5B6B82] font-mono">
                                        {{ $letter->created_at?->format('H:i') ?? '' }} WIB
                                    </div>
                                </td>

                                {{-- 8. AKSI --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1 whitespace-nowrap">
                                        {{-- Tombol Detail Surat (Ikon Mata) --}}
                                        <button 
                                            type="button" 
                                            @click="detailLetterModalOpen = true"
                                            wire:click="openDetailLetter({{ $letter->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer" 
                                            title="Lihat Detail Permohonan Surat"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Cepat Setujui (Ikon Centang) - Hanya untuk status diajukan / diperiksa --}}
                                        @if(in_array($letter->status, ['diajukan', 'diperiksa']))
                                            <button 
                                                type="button" 
                                                @click="approveLetterModalOpen = true"
                                                wire:click="openApproveLetter({{ $letter->id }})" 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 hover:border-emerald-400 transition-all shadow-2xs cursor-pointer" 
                                                title="Setujui Surat & Terbitkan Nomor"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>

                                            {{-- Tombol Cepat Tolak (Ikon Silang) --}}
                                            <button 
                                                type="button" 
                                                @click="rejectLetterModalOpen = true"
                                                wire:click="openRejectLetter({{ $letter->id }})" 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer" 
                                                title="Tolak Permohonan Surat"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- Tombol Cetak Surat Resmi (Ikon Printer) - Jika Disetujui atau Selesai --}}
                                        @if(in_array($letter->status, ['disetujui', 'selesai']))
                                            <a 
                                                href="{{ route('letters.print', $letter->id) }}" 
                                                target="_blank" 
                                                rel="noopener noreferrer" 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-blue-300 bg-blue-50 text-blue-700 hover:bg-blue-100 hover:text-blue-800 hover:border-blue-400 transition-all shadow-2xs cursor-pointer" 
                                                title="Cetak Lembar Surat Pengantar Resmi RT 15"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                </svg>
                                            </a>
                                        @endif

                                        {{-- Tombol Ubah Data (Ikon Pensil) --}}
                                        <button 
                                            type="button" 
                                            @click="letterModalOpen = true"
                                            wire:click="openEditLetter({{ $letter->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Ubah Data Surat"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Hapus Data (Ikon Sampah) --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $letter->id }}, '{{ addslashes(($letter->nomor_surat ?: ($letter->letterType?->nama ?? 'Surat Pengantar')) . ' - ' . ($letter->resident?->nama ?? '')) }}')" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Hapus Surat Pengantar"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-sm text-[#5B6B82]">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    @if(filled($search) || $statusFilter !== 'semua' || filled($typeFilter))
                                        <p class="font-bold text-[#0F1F3D]">Tidak ada surat pengantar yang cocok.</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Coba sesuaikan kata kunci pencarian Anda.</p>
                                    @else
                                        <p class="font-bold text-[#0F1F3D]">Belum Ada Pengajuan Surat Pengantar</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Klik tombol "Buat Surat Pengantar" di atas untuk menambahkan permohonan baru.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            @if($letters->hasPages())
                <div class="px-4 py-3 border-t border-[#E2E8F0] bg-slate-50/50">
                    {{ $letters->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             4. MODAL POPUP DETAIL SURAT PENGANTAR
             ==================================================================== --}}
        @include('filament.resources.letters.components.detail-modal')

        {{-- ====================================================================
             5. MODAL PERSETUJUAN & PENOLAKAN CEPAT
             ==================================================================== --}}
        @include('filament.resources.letters.components.action-modals')

        {{-- ====================================================================
             6. MODAL FORM TAMBAH / UBAH SURAT PENGANTAR
             ==================================================================== --}}
        @include('filament.resources.letters.components.form-modal')

        {{-- ====================================================================
             7. MODAL POPUP KONFIRMASI HAPUS SURAT PENGANTAR (ALPINE + LIVEWIRE)
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="delete-letter-modal-title" 
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-letter-modal-title">Hapus Surat Pengantar?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini akan menghapus arsip pengajuan surat dari sistem.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Surat Terpilih:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteLetterInfo"></span>
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
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition-all shadow-xs cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span>Ya, Hapus Surat</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================================
         7. SCRIPT CLIENT-SIDE REAL-TIME MULTI-FIELD SEARCH & DYNAMIC RESET
         ==================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initLetterClientSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initLetterClientSearch();
        });

        document.addEventListener('livewire:initialized', function() {
            initLetterClientSearch();
        });

        function initLetterClientSearch() {
            const searchInput = document.getElementById('search-letters-input');
            const clearBtn = document.getElementById('search-letters-clear-btn');
            const statusSelect = document.getElementById('filter-letter-status');
            const typeSelect = document.getElementById('filter-letter-type');
            const resetContainer = document.getElementById('reset-filter-container');
            const tableBody = document.getElementById('letter-table-body');
            const hasServerFilter = {{ (request()->anyFilled(['search', 'type']) || (request()->has('status') && request('status') !== 'semua' && request('status') !== '')) ? 'true' : 'false' }};

            if (!searchInput) return;

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const statusVal = statusSelect ? statusSelect.value : 'semua';
                const typeVal = typeSelect ? typeSelect.value : '';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || (statusVal !== 'semua' && statusVal !== '') || typeVal !== '';
                let resetBtn = document.getElementById('btn-reset-filter');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter';
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
                    const rows = tableBody.querySelectorAll('tr.letter-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowStatus = row.dataset.status;
                        const rowType = row.dataset.type;

                        let matchesSearch = term === '' || content.includes(term);
                        let matchesStatus = (statusVal === 'semua' || !statusVal) ? true : (rowStatus === statusVal);
                        let matchesType = (!typeVal) ? true : (rowType === typeVal);

                        if (matchesSearch && matchesStatus && matchesType) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('letter-client-empty-row');
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
                if (typeSelect) typeSelect.addEventListener('change', applyFilter);

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
                    const btn = e.target.closest('#btn-reset-filter');
                    if (!btn) return;

                    searchInput.value = '';
                    if (statusSelect) statusSelect.value = 'semua';
                    if (typeSelect) typeSelect.value = '';
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
                                    comp.set('typeFilter', '');
                                }
                            }
                        }
                    }

                    if (hasServerFilter) {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            if (searchInput.value.trim().length > 0 || (statusSelect && statusSelect.value !== 'semua') || (typeSelect && typeSelect.value !== '')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
