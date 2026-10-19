<x-filament-panels::page>
    <div 
        x-data="{
            deleteConfirmModalOpen: false,
            deleteScheduleId: null,
            deleteScheduleRegu: '',
            detailModalOpen: @entangle('detailModalOpen'),
            formModalOpen: @entangle('formModalOpen'),
            confirmDelete(id, regu) {
                this.deleteScheduleId = id;
                this.deleteScheduleRegu = regu;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteScheduleId = null;
                this.deleteScheduleRegu = '';
            },
            executeDelete() {
                if (this.deleteScheduleId) {
                    const id = this.deleteScheduleId;
                    this.closeDeleteConfirm();
                    $wire.deleteSchedule(id);
                }
            }
        }"
        @keydown.escape.window="closeDeleteConfirm(); if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); } if (formModalOpen) { formModalOpen = false; $wire.closeForm(); }"
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
                        title="Jadwal Ronda Malam"
                        category="KEAMANAN LINGKUNGAN RT 15"
                        description="Modul pengelolaan poskamling dan pembagian jadwal giliran ronda malam warga RT 15 untuk memastikan pengamanan lingkungan permukiman berjalan tertib dan teratur."
                        :steps="[
                            ['title' => 'Pencarian & Filter Regu', 'desc' => 'Cari nama petugas ronda atau filter berdasarkan pembagian Regu / Blok dan jadwal hari ronda.'],
                            ['title' => 'Buat Jadwal / Plotting Petugas', 'desc' => 'Klik tombol biru \'+ Tambah Jadwal Ronda\' untuk memplot petugas, hari tugas, dan pos jaga.'],
                            ['title' => 'Pantau Kehadiran & Catatan', 'desc' => 'Gunakan tombol \'Lihat\' untuk mengecek daftar personil ronda, catatan insiden malam, atau tombol \'Ubah\' untuk tukar dinas.'],
                            ['title' => 'Ekspor Jadwal Poskamling', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mencetak lembar jadwal ronda yang dapat ditempel di Pos Ronda RT 15 (PDF/Excel).']
                        ]"
                        role="Seksi Keamanan & Koordinator Poskamling"
                        :tips="[
                            'Pastikan setiap regu ronda memiliki koordinator regu yang bertanggung jawab membawa kunci pos dan senter.',
                            'Warga dapat melihat giliran jadwal ronda mereka masing-masing melalui menu portal warga.',
                            'Segala kejadian mencurigakan saat ronda wajib dicatat pada buku mutasi atau dilaporkan ke Ketua RT.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Jadwal Ronda Malam
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Tambah Jadwal Ronda) --}}
            <div class="flex items-center gap-2 shrink-0">
                <x-export-dropdown resource="patrol-schedules" :extraParams="['regu' => '$wire.reguFilter', 'waktu' => '$wire.waktuFilter']" />

                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Jadwal Ronda</span>
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
                        id="search-patrol-input"
                        name="search"
                        value="{{ $search }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari regu ronda, nama personil bertugas, pos, atau catatan..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-patrol-clear-btn" 
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
                    {{-- Filter Pilihan Waktu Jadwal (Dropdown Pengganti Tab) --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-patrol-waktu"
                            name="waktu"
                            wire:model.live="waktuFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="semua" {{ $waktuFilter === 'semua' ? 'selected' : '' }}>Semua Jadwal ({{ $countSemua }})</option>
                            <option value="mendatang" {{ $waktuFilter === 'mendatang' ? 'selected' : '' }}>Mendatang & Hari Ini ({{ $countMendatang }})</option>
                            <option value="selesai" {{ $waktuFilter === 'selesai' ? 'selected' : '' }}>Telah Selesai ({{ $countSelesai }})</option>
                        </select>
                    </div>

                    {{-- Filter Pilihan Regu --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-patrol-regu"
                            name="regu"
                            wire:model.live="reguFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="">Semua Regu Ronda</option>
                            @foreach($reguOptions as $regu)
                                <option value="{{ $regu }}" {{ $reguFilter === $regu ? 'selected' : '' }}>
                                    {{ $regu }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-patrol-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || ($waktuFilter !== 'semua' && filled($waktuFilter)) 
                            || filled($reguFilter) 
                            || request()->anyFilled(['search', 'regu']) 
                            || (request()->has('waktu') && request('waktu') !== 'semua' && request('waktu') !== '');
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter-patrol" 
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
             4. TABEL DATA JADWAL RONDA (DEEP NAVY #0B3A85 TABLE HEADER)
             ==================================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="waktuFilter, reguFilter, search, resetFilters, setWaktu, previousPage, nextPage, gotoPage"
                text="Memperbarui data jadwal ronda..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white">
                            <th class="py-3 px-4 font-bold text-center w-12 shrink-0">NO</th>
                            <th class="py-3 px-4 font-bold min-w-[150px] whitespace-nowrap">Tanggal & Status</th>
                            <th class="py-3 px-4 font-bold min-w-[140px] whitespace-nowrap">Nama Regu</th>
                            <th class="py-3 px-4 font-bold min-w-[130px] whitespace-nowrap">Jam Jaga</th>
                            <th class="py-3 px-4 font-bold min-w-[260px]">Personil Bertugas</th>
                            <th class="py-3 px-4 font-bold min-w-[200px]">Catatan / Pos</th>
                            <th class="py-3 px-4 font-bold text-center w-36 min-w-[140px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody 
                        id="patrols-table-body" 
                        class="divide-y divide-slate-100 transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="waktuFilter, reguFilter, search, resetFilters, setWaktu, previousPage, nextPage, gotoPage"
                    >
                        @forelse($schedules as $index => $item)
                            @php
                                $isToday = \Carbon\Carbon::parse($item->tanggal)->isToday();
                                $isFuture = \Carbon\Carbon::parse($item->tanggal)->isFuture();
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors patrol-data-row {{ $isToday ? 'bg-amber-50/30' : '' }}">
                                {{-- Kolom Nomor Urut --}}
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-500 text-xs whitespace-nowrap">
                                    {{ $schedules->firstItem() ? ($schedules->firstItem() + $index) : ($index + 1) }}
                                </td>

                                {{-- Kolom Tanggal & Status --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-extrabold text-[#0F1F3D]">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}
                                    </div>
                                    <div class="mt-1">
                                        @if($isToday)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                MALAM INI
                                            </span>
                                        @elseif($isFuture)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Mendatang
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                Selesai
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kolom Nama Regu --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-extrabold text-[#0F1F3D] text-[13px] hover:text-[#0B3A85] cursor-pointer" wire:click="openDetail({{ $item->id }})">
                                        {{ $item->regu }}
                                    </div>
                                    <div class="text-[10.5px] text-slate-400 mt-0.5">
                                        {{ $item->members->count() }} Anggota Bertugas
                                    </div>
                                </td>

                                {{-- Kolom Jam Ronda --}}
                                <td class="py-3.5 px-4 whitespace-nowrap font-mono text-slate-700 font-bold">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ substr($item->waktu_mulai, 0, 5) }} - {{ substr($item->waktu_selesai, 0, 5) }} WIB</span>
                                    </div>
                                </td>

                                {{-- Kolom Personil Bertugas --}}
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1 flex-wrap max-w-xs">
                                        @forelse($item->members->take(3) as $m)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $m->resident ? $m->resident->nama_lengkap : 'Warga' }}
                                            </span>
                                        @empty
                                            <span class="text-slate-400 italic text-[11px]">Belum ditugaskan</span>
                                        @endforelse

                                        @if($item->members->count() > 3)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-[#0B3A85]">
                                                +{{ $item->members->count() - 3 }} lagi
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kolom Catatan / Pos --}}
                                <td class="py-3.5 px-4">
                                    <p class="text-[11px] text-slate-600 line-clamp-1 max-w-[150px]">
                                        {{ $item->catatan ?? 'Pos Utama RT 15' }}
                                    </p>
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
                                            title="Lihat Detail Petugas Ronda"
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
                                            title="Atur Personil / Ubah Jadwal"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Hapus Modal (Ikon Sampah - Merah/Rose) --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $item->id }}, '{{ addslashes($item->regu) }}')" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer"
                                            title="Hapus Jadwal"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="patrol-empty-row">
                                <td colspan="7" class="py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">Tidak ada jadwal ronda ditemukan</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                        Tidak ada jadwal poskamling yang cocok dengan kriteria pencarian dan filter.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Baris Empty State Pencarian Real-Time Client Side --}}
                        <tr id="patrol-client-empty-row" style="display: none;">
                            <td colspan="7" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Tidak ada jadwal yang cocok</h4>
                                <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Livewire --}}
            @if($schedules->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL JADWAL RONDA (ALPINE.JS X-SHOW INSTANT OPEN)
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </span>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                    Keamanan Lingkungan RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="modal-title">
                                    Detail Shift Ronda Malam
                                </h3>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false; $wire.closeDetail()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Asynchronous Skeleton Loader while Livewire fetches selectedSchedule --}}
                    <div wire:loading.flex wire:target="openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-16 bg-slate-100 rounded-xl"></div>
                            <div class="h-16 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-14 bg-slate-100 rounded-xl"></div>
                        <div class="h-4 w-40 bg-slate-200 rounded"></div>
                        <div class="h-32 bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Modal Body Content --}}
                    <div wire:loading.remove wire:target="openDetail">
                        @if($selectedSchedule)
                            <div class="p-6 space-y-4 text-xs sm:text-sm text-slate-700 max-h-[75vh] overflow-y-auto">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Regu Bertugas</div>
                                        <div class="text-sm font-black text-[#0B3A85] mt-0.5">
                                            {{ $selectedSchedule->regu }}
                                        </div>
                                    </div>

                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Waktu Jaga</div>
                                        <div class="text-xs font-bold text-slate-800 mt-0.5">
                                            {{ substr($selectedSchedule->waktu_mulai, 0, 5) }} - {{ substr($selectedSchedule->waktu_selesai, 0, 5) }} WIB
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="text-[10.5px] font-bold text-slate-400 uppercase">Tanggal Pelaksanaan</div>
                                    <div class="text-xs font-bold text-slate-800 mt-0.5">
                                        {{ \Carbon\Carbon::parse($selectedSchedule->tanggal)->translatedFormat('l, d F Y') }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Daftar Anggota Personil Ronda ({{ $selectedSchedule->members->count() }} Orang)
                                    </div>
                                    <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden bg-white">
                                        @forelse($selectedSchedule->members as $idx => $m)
                                            <div class="p-3 flex items-center justify-between hover:bg-slate-50">
                                                <div class="flex items-center gap-2.5">
                                                    <span class="w-6 h-6 rounded-full bg-blue-100 text-[#0B3A85] text-xs font-bold flex items-center justify-center shrink-0">
                                                        {{ $idx + 1 }}
                                                    </span>
                                                    <div>
                                                        <div class="font-bold text-[#0F1F3D] text-xs">
                                                            {{ $m->resident ? $m->resident->nama_lengkap : 'Warga RT' }}
                                                        </div>
                                                        <div class="text-[10.5px] text-slate-400">
                                                            NIK: {{ $m->resident ? $m->resident->nik : '-' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    @if($m->is_pengganti)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                                            PENGGANTI
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                            PETUGAS UTAMA
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-4 text-center text-slate-400 text-xs italic">
                                                Belum ada anggota personil yang ditugaskan pada jadwal ini.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                @if($selectedSchedule->catatan)
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase">Instruksi / Pos Ronda</div>
                                        <div class="text-xs text-slate-700 mt-0.5">
                                            {{ $selectedSchedule->catatan }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Modal Footer --}}
                            <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex items-center justify-between">
                                <button 
                                    type="button" 
                                    @click="detailModalOpen = false; formModalOpen = true"
                                    wire:click="openEdit({{ $selectedSchedule->id }})" 
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white transition-colors cursor-pointer"
                                >
                                    Atur Anggota & Jadwal
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
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="h-16 bg-slate-100 rounded-xl"></div>
                                    <div class="h-16 bg-slate-100 rounded-xl"></div>
                                </div>
                                <div class="h-14 bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL FORM TAMBAH / UBAH JADWAL RONDA (ALPINE.JS X-SHOW INSTANT OPEN)
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
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-xl border border-slate-200" 
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
                                    Keamanan Lingkungan RT 15
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="form-modal-title">
                                    {{ $isEditing ? 'Ubah Jadwal Ronda' : 'Tambah Jadwal Ronda Baru' }}
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
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                            <div class="h-9 w-full bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-32 w-full bg-slate-100 rounded-xl"></div>
                        <div class="h-16 w-full bg-slate-100 rounded-xl"></div>
                    </div>

                    {{-- Modal Form --}}
                    <div wire:loading.remove wire:target="openEdit">
                        <form wire:submit="saveSchedule" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Tanggal Ronda <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        wire:model="form_tanggal" 
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_tanggal') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Nama Regu <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="form_regu" 
                                        placeholder="Contoh: Regu 1 (Garuda)"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_regu') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Jam Mulai <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="time" 
                                        wire:model="form_waktu_mulai" 
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_waktu_mulai') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Jam Selesai <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="time" 
                                        wire:model="form_waktu_selesai" 
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_waktu_selesai') <span class="text-rose-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div 
                                x-data="{
                                    residentSearch: '',
                                    onlySelected: false,
                                    clearSelection() {
                                        $wire.set('form_resident_ids', []);
                                    },
                                    removeResident(id) {
                                        let current = $wire.get('form_resident_ids') || [];
                                        $wire.set('form_resident_ids', current.filter(i => String(i) !== String(id)));
                                    }
                                }"
                                class="space-y-2"
                            >
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-slate-700">
                                        Pilih Anggota Personil Bertugas (Multi-select)
                                    </label>
                                    
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-[#0B3A85] border border-blue-200">
                                            <span x-text="($wire.form_resident_ids || []).length"></span> warga dipilih
                                        </span>

                                        <button 
                                            type="button" 
                                            x-show="($wire.form_resident_ids || []).length > 0"
                                            @click="clearSelection()"
                                            class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold hover:underline cursor-pointer"
                                        >
                                            Reset Pilihan
                                        </button>
                                    </div>
                                </div>

                                {{-- Input Pencarian Real-Time (Tanpa Delay / 0ms) --}}
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        x-model="residentSearch" 
                                        placeholder="Cari nama warga, ID Warga, atau alamat..."
                                        class="w-full pl-9 pr-8 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                                    >
                                    <button 
                                        type="button" 
                                        x-show="residentSearch.length > 0"
                                        @click="residentSearch = ''" 
                                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                        title="Hapus pencarian"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Tag Chips Warga Terpilih (Langsung Terlihat Tanpa Scroll) --}}
                                <div 
                                    x-show="($wire.form_resident_ids || []).length > 0" 
                                    class="p-2 bg-blue-50/40 border border-blue-100 rounded-xl space-y-1.5"
                                >
                                    <div class="text-[10px] font-bold text-[#0B3A85] uppercase tracking-wide">
                                        Personil Ronda Terpilih:
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto">
                                        @foreach($allResidents as $res)
                                            <span 
                                                x-show="($wire.form_resident_ids || []).map(String).includes('{{ $res->id }}')"
                                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-white border border-blue-200 text-xs font-semibold text-slate-800 shadow-2xs"
                                            >
                                                <span>{{ $res->nama }}</span>
                                                <button 
                                                    type="button" 
                                                    @click="removeResident('{{ $res->id }}')" 
                                                    class="text-slate-400 hover:text-rose-600 font-bold ml-0.5 cursor-pointer"
                                                    title="Hapus dari daftar regu"
                                                >
                                                    &times;
                                                </button>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Bar Filter Cepat & Info Total --}}
                                <div class="flex items-center justify-between text-[11px] text-slate-500 px-0.5">
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                                        <input 
                                            type="checkbox" 
                                            x-model="onlySelected" 
                                            class="rounded border-slate-300 text-[#0B3A85] focus:ring-[#0B3A85] w-3.5 h-3.5"
                                        >
                                        <span class="font-medium text-slate-600">Hanya tampilkan personil terpilih</span>
                                    </label>
                                    <span class="text-slate-400">Total: {{ count($allResidents) }} warga</span>
                                </div>

                                {{-- Container Daftar Warga (Scrollable & Ringan) --}}
                                <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-1.5 bg-slate-50/50 space-y-1 divide-y divide-slate-100">
                                    @forelse($allResidents as $res)
                                        @php
                                            $searchKey = strtolower($res->nama . ' ' . ($res->id_warga ?? '') . ' ' . ($res->household?->alamat ?? ''));
                                        @endphp
                                        <label 
                                            x-data="{ 
                                                id: '{{ $res->id }}',
                                                get isChecked() {
                                                    return ($wire.form_resident_ids || []).map(String).includes(this.id);
                                                },
                                                get isVisible() {
                                                    if (onlySelected && !this.isChecked) return false;
                                                    if (!residentSearch.trim()) return true;
                                                    return '{{ addslashes($searchKey) }}'.includes(residentSearch.toLowerCase().trim());
                                                }
                                            }"
                                            x-show="isVisible"
                                            :class="isChecked ? 'bg-blue-50/70 border-blue-200' : 'hover:bg-white border-transparent'"
                                            class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all border group select-none"
                                        >
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <input 
                                                    type="checkbox" 
                                                    value="{{ $res->id }}" 
                                                    wire:model="form_resident_ids"
                                                    class="rounded border-slate-300 text-[#0B3A85] focus:ring-[#0B3A85] shrink-0"
                                                >
                                                <div class="w-6 h-6 rounded-full bg-blue-100 text-[#0B3A85] font-bold text-[10px] flex items-center justify-center shrink-0 uppercase">
                                                    {{ substr($res->nama, 0, 1) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-bold text-xs text-slate-800 truncate group-hover:text-[#0B3A85] transition-colors">
                                                        {{ $res->nama }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-400 flex items-center gap-1.5 truncate">
                                                        @if($res->id_warga)
                                                            <span class="font-mono font-bold text-[#0B3A85] bg-blue-100/70 px-1 py-0.2 rounded text-[9.5px]">
                                                                {{ $res->id_warga }}
                                                            </span>
                                                        @endif
                                                        @if($res->household?->alamat)
                                                            <span class="truncate">{{ $res->household->alamat }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div x-show="isChecked" class="shrink-0 ml-2">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-[#0B3A85]">
                                                    Terpilih
                                                </span>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="py-6 text-center text-xs text-slate-400">
                                            Belum ada data warga terdaftar.
                                        </div>
                                    @endforelse

                                    {{-- Hasil Pencarian Kosong --}}
                                    <div 
                                        x-show="residentSearch.trim().length > 0 && !Array.from($el.parentElement.querySelectorAll('label')).some(el => el.offsetParent !== null)"
                                        class="py-6 text-center text-xs text-slate-400"
                                    >
                                        Tidak ditemukan warga dengan kata kunci "<span x-text="residentSearch" class="font-semibold text-slate-600"></span>".
                                    </div>
                                </div>
                                
                                <span class="text-[10.5px] text-slate-400 block">
                                    Ketik nama, ID warga, atau alamat di kotak pencarian untuk memilih personil dengan cepat.
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Catatan / Pos Jaga
                                </label>
                                <textarea 
                                    wire:model="form_catatan" 
                                    rows="2" 
                                    placeholder="Contoh: Titik kumpul Pos Ronda Depan RT 15, patroli keliling tiap 1 jam..."
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
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Tetapkan Jadwal' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL KONFIRMASI HAPUS JADWAL (ALPINE.JS)
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-modal-title">Hapus Jadwal Ronda Ini?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Jadwal poskamling dan penugasan personil akan dihapus.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Regu:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteScheduleRegu"></span>
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
                            Ya, Hapus Jadwal
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
            initPatrolSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initPatrolSearch();
        });

        document.addEventListener('livewire:init', function() {
            if (window.Livewire && window.Livewire.hook) {
                window.Livewire.hook('commit', ({ succeed }) => {
                    succeed(() => {
                        initPatrolSearch();
                    });
                });
            }
        });

        function initPatrolSearch() {
            const searchInput = document.getElementById('search-patrol-input');
            const clearBtn = document.getElementById('search-patrol-clear-btn');
            const waktuSelect = document.getElementById('filter-patrol-waktu');
            const reguSelect = document.getElementById('filter-patrol-regu');
            const resetContainer = document.getElementById('reset-filter-patrol-container');
            const tableBody = document.getElementById('patrols-table-body');

            if (!searchInput) return;

            const urlParams = new URLSearchParams(window.location.search);
            const hasServerFilter = urlParams.has('search') || urlParams.has('regu') || (urlParams.has('waktu') && urlParams.get('waktu') !== 'semua');

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const waktuVal = waktuSelect ? waktuSelect.value : 'semua';
                const reguVal = reguSelect ? reguSelect.value : '';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || (waktuVal !== 'semua' && waktuVal !== '') || reguVal !== '';
                let resetBtn = document.getElementById('btn-reset-filter-patrol');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-patrol';
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
                    const rows = tableBody.querySelectorAll('tr.patrol-data-row');
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

                    const emptyRow = document.getElementById('patrol-client-empty-row');
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
                if (reguSelect) reguSelect.addEventListener('change', applyFilter);

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
                    const btn = e.target.closest('#btn-reset-filter-patrol');
                    if (!btn) return;

                    searchInput.value = '';
                    if (waktuSelect) waktuSelect.value = 'semua';
                    if (reguSelect) reguSelect.value = '';
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
                                    comp.set('reguFilter', '');
                                }
                            }
                        }
                    }

                    if (hasServerFilter) {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            if (searchInput.value.trim().length > 0 || (waktuSelect && waktuSelect.value !== 'semua') || (reguSelect && reguSelect.value !== '')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
