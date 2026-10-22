<x-filament-panels::page>
    <div 
        x-data="{
            editMasterModalOpen: @entangle('editMasterModalOpen'),
            detailModalOpen: @entangle('detailModalOpen'),
            historyModalOpen: @entangle('historyModalOpen'),
        }"
        @keydown.escape.window="
            if (editMasterModalOpen) { editMasterModalOpen = false; $wire.closeEditMaster(); }
            if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); }
            if (historyModalOpen) { historyModalOpen = false; $wire.closeHistory(); }
        "
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
                        title="Master Jadwal Ronda Mingguan"
                        category="KEAMANAN LINGKUNGAN RT 15"
                        description="Master jadwal giliran ronda malam warga RT 15 dengan pola 7 hari tetap (Senin s.d. Minggu). Pengurus dapat mengelola regu dan petugas untuk setiap hari serta mencatat presensi kehadiran dan evaluasi patroli malam tanpa perlu generate jadwal manual setiap minggu."
                        :steps="[
                            ['title' => 'Pola 7 Hari Tetap', 'desc' => 'Tabel utama menampilkan tepat 7 baris untuk hari Senin sampai Minggu yang berulang setiap pekannya secara otomatis.'],
                            ['title' => 'Penanda Hari Ini', 'desc' => 'Jadwal yang bertugas pada malam hari ini ditandai secara visual dengan badge hijau cerah dan garis aksen.'],
                            ['title' => 'Kelola Master Regu', 'desc' => 'Klik tombol pensil pada baris hari untuk menyesuaikan nama regu, jam jaga, pos, dan daftar personil tetap.'],
                            ['title' => 'Catat Presensi Malam', 'desc' => 'Klik tombol presensi (checklist) untuk mencatat kehadiran petugas, menugaskan personil pengganti, dan mengisi laporan kejadian malam.'],
                            ['title' => 'Lihat Riwayat Ronda', 'desc' => 'Gunakan tombol riwayat (jam) untuk meninjau arsip pelaksanaan ronda aktual beserta catatan keamanan terdahulu.']
                        ]"
                        role="Seksi Keamanan & Koordinator Poskamling"
                        :tips="[
                            'Perubahan master regu berlaku otomatis untuk siklus minggu berikutnya tanpa mengubah catatan riwayat terdahulu.',
                            'Data kehadiran dan insiden keamanan disimpan dengan tanggal aktual pada modul riwayat pelaksanaan.',
                            'Pastikan setiap regu ronda memiliki koordinator regu yang bertanggung jawab membawa kunci pos dan senter.'
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

            {{-- Tombol Aksi di Header (Ekspor CSV / Laporan) --}}
            <div class="flex items-center gap-2 shrink-0 flex-wrap sm:flex-nowrap">
                <x-export-dropdown resource="patrol-schedules" :extraParams="['regu' => '$wire.reguFilter']" />
            </div>
        </div>

        {{-- ====================================================================
             2. SEARCH BAR & DROPDOWN FILTER & RESET FILTER (STANDAR RESMI)
             - Mobile: Search Bar di atas, filter sejajar di bawahnya
             - Desktop: Semua elemen sejajar horizontal dalam satu baris (Tanpa Card)
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
                        placeholder="Cari ... " 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-patrol-clear-btn" 
                        wire:click="$set('search', '')"
                        style="{{ filled($search) ? '' : 'display: none;' }}"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#94A3B8] hover:text-[#0F1F3D] transition-colors cursor-pointer"
                        title="Hapus kata kunci"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Wrapper Filter & Reset: Di mobile filter & reset menyesuaikan, di tablet/desktop sejajar horizontal --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 shrink-0">
                {{-- Filter Dropdown: Regu Ronda --}}
                <div class="w-full sm:w-56">
                    <select 
                        id="filter-patrol-regu"
                        name="regu"
                        wire:model.live="reguFilter"
                        class="w-full py-2.5 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                    >
                        <option value="">Semua Regu Ronda</option>
                        @foreach($allRegus as $rName)
                            <option value="{{ $rName }}" {{ $reguFilter === $rName ? 'selected' : '' }}>{{ $rName }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah Konsisten) --}}
                <div id="reset-filter-patrol-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || filled($reguFilter) 
                            || request()->anyFilled(['search', 'regu']);
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
             3. TABEL MASTER JADWAL RONDA 7 HARI (SENIN S.D. MINGGU)
             - Tepat 7 baris mewakili 7 hari dalam sepekan
             - Penanda visual khusus pada 'Hari Ini'
             - Responsif mobile: hanya Hari & Status + Aksi (Regu di sub-teks)
             ==================================================================== --}}
        <div class="bg-white rounded-2xl border border-[#DDE5F0] shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="patrolTable">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[11px] sm:text-xs font-extrabold uppercase tracking-wider">
                            <th class="py-3 px-3 sm:px-4 text-center w-12">NO</th>
                            <th class="py-3 px-3 sm:px-4">HARI & STATUS</th>
                            <th class="py-3 px-3 sm:px-4 hidden md:table-cell">NAMA REGU</th>
                            <th class="py-3 px-3 sm:px-4 hidden md:table-cell">JAM JAGA</th>
                            <th class="py-3 px-3 sm:px-4 hidden md:table-cell">PERSONIL BERTUGAS</th>
                            <th class="py-3 px-3 sm:px-4 hidden md:table-cell">POS / CATATAN</th>
                            <th class="py-3 px-3 sm:px-4 text-center w-28 sm:w-36">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0] text-xs text-[#0F1F3D]">
                        @forelse($schedules as $index => $item)
                            @php
                                $isToday = ((int) $item->hari_ke === (int) $todayHariKe);
                            @endphp
                            <tr 
                                class="hover:bg-slate-50/80 transition-colors {{ $isToday ? 'bg-emerald-50/30 border-l-4 border-l-emerald-500' : '' }}"
                                data-hari="{{ strtolower($item->nama_hari) }}"
                                data-regu="{{ strtolower($item->regu) }}"
                                data-petugas="{{ strtolower($item->members->map(fn($m) => $m->resident?->nama)->implode(' ')) }}"
                                data-catatan="{{ strtolower($item->catatan . ' ' . $item->pos_lokasi) }}"
                            >
                                {{-- Kolom 1: Nomor Urut (1 s.d. 7) --}}
                                <td class="py-3.5 px-3 sm:px-4 text-center font-bold text-[#5B6B82]">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Kolom 2: Hari & Status (Di Mobile Tampil Bersama Nama Regu) --}}
                                <td class="py-3.5 px-3 sm:px-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-extrabold text-sm text-[#0F1F3D]">
                                            {{ $item->nama_hari }}
                                        </span>

                                        @if($isToday)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-2xs">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Hari Ini
                                            </span>
                                        @endif

                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold {{ $item->is_aktif ? 'bg-blue-50 text-[#0B3A85] border border-blue-200' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $item->is_aktif ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>

                                    {{-- Sub-teks Khusus Mobile: Nama Regu & Jam --}}
                                    <div class="md:hidden mt-1 text-[11px] space-y-0.5">
                                        <div class="font-bold text-[#0B3A85]">{{ $item->regu }}</div>
                                        <div class="text-slate-500 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ substr($item->waktu_mulai, 0, 5) }} - {{ substr($item->waktu_selesai, 0, 5) }} WIB</span>
                                            <span>&bull; {{ $item->members->count() }} Petugas</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom 3: Nama Regu (Desktop) --}}
                                <td class="py-3.5 px-3 sm:px-4 hidden md:table-cell">
                                    <div class="font-bold text-[#0B3A85] text-xs sm:text-sm">
                                        {{ $item->regu }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $item->members->count() }} Petugas Tetap
                                    </div>
                                </td>

                                {{-- Kolom 4: Jam Jaga (Desktop) --}}
                                <td class="py-3.5 px-3 sm:px-4 hidden md:table-cell whitespace-nowrap text-xs text-[#0F1F3D]">
                                    <div class="flex items-center gap-1.5 font-bold">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ substr($item->waktu_mulai, 0, 5) }} - {{ substr($item->waktu_selesai, 0, 5) }} WIB</span>
                                    </div>
                                </td>

                                {{-- Kolom 5: Personil Bertugas (Desktop) --}}
                                <td class="py-3.5 px-3 sm:px-4 hidden md:table-cell">
                                    <div class="flex flex-wrap gap-1 max-w-md">
                                        @forelse($item->members as $pm)
                                            @php
                                                $isKetua = ($pm->peran === 'ketua_regu');
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold border {{ $isKetua ? 'bg-amber-50 text-amber-900 border-amber-200' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                                @if($isKetua)
                                                    <span class="text-amber-500 font-bold" title="Ketua Regu">★</span>
                                                @endif
                                                <span>{{ $pm->resident?->nama ?? 'Warga' }}</span>
                                            </span>
                                        @empty
                                            <span class="text-slate-400 italic text-[11px]">Belum diplot</span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- Kolom 6: Pos / Catatan (Desktop) --}}
                                <td class="py-3.5 px-3 sm:px-4 hidden md:table-cell">
                                    <div class="text-xs text-slate-800 font-semibold truncate max-w-xs" title="{{ $item->pos_lokasi }}">
                                        {{ $item->pos_lokasi }}
                                    </div>
                                    @if($item->catatan)
                                        <div class="text-[11px] text-slate-500 truncate max-w-xs mt-0.5" title="{{ $item->catatan }}">
                                            {{ $item->catatan }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Kolom 7: Aksi (Presensi, Kelola Master, Riwayat) --}}
                                <td class="py-3.5 px-3 sm:px-4 text-center">
                                    <div class="flex items-center justify-center gap-1 sm:gap-1.5">
                                        {{-- Tombol Presensi & Pelaksanaan Aktual --}}
                                        <button 
                                            type="button"
                                            wire:click="openPresensi({{ $item->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 transition-colors cursor-pointer"
                                            title="Presensi & Pelaksanaan Ronda Malam"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Edit Master Jadwal --}}
                                        <button 
                                            type="button"
                                            wire:click="openEditMaster({{ $item->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#0B3A85] border border-blue-200 transition-colors cursor-pointer"
                                            title="Kelola Master Regu & Personil"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Riwayat Pelaksanaan --}}
                                        <button 
                                            type="button"
                                            wire:click="openHistory({{ $item->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors cursor-pointer"
                                            title="Lihat Riwayat Pelaksanaan Ronda"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-[#5B6B82]">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span class="text-xs font-semibold">Tidak ada jadwal ronda yang cocok dengan kriteria pencarian.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Info --}}
            <div class="px-4 py-3 bg-[#F8FAFC] border-t border-[#DDE5F0] flex flex-col sm:flex-row items-center justify-between text-xs text-[#5B6B82] gap-2">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Menampilkan master pola mingguan 7 hari (Senin s.d. Minggu).</span>
                </div>
                <div class="font-bold text-[#0B3A85]">
                    RT 15 / RW 04 &bull; Wilayah Keamanan Siaga
                </div>
            </div>
        </div>

        {{-- ====================================================================
             4. MODAL KELOLA MASTER REGU & PETUGAS TETAP
             ==================================================================== --}}
        @include('filament.resources.patrol-schedules.components.edit-master-modal')

        {{-- ====================================================================
             5. MODAL PRESENSI & LAPORAN PELAKSANAAN RONDA AKTUAL (Bertanggal)
             ==================================================================== --}}
        @include('filament.resources.patrol-schedules.components.detail-presensi-modal')

        {{-- ====================================================================
             6. MODAL RIWAYAT PELAKSANAAN RONDA
             ==================================================================== --}}
        @include('filament.resources.patrol-schedules.components.history-modal')
    </div>

    {{-- ====================================================================
         7. SCRIPT CLIENT-SIDE REAL-TIME SEARCH & DYNAMIC RESET FILTER
         ATURAN STANDAR PENGEMBANGAN: SEARCH BAR & FILTER TABEL
         ==================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initPatrolTableFilter();
        });

        document.addEventListener('livewire:navigated', function() {
            initPatrolTableFilter();
        });

        document.addEventListener('livewire:init', function() {
            if (window.Livewire && window.Livewire.hook) {
                window.Livewire.hook('commit', ({ succeed }) => {
                    succeed(() => {
                        initPatrolTableFilter();
                    });
                });
            }
        });

        function initPatrolTableFilter() {
            const searchInput = document.getElementById('search-patrol-input');
            const clearBtn = document.getElementById('search-patrol-clear-btn');
            const reguSelect = document.getElementById('filter-patrol-regu');
            const resetContainer = document.getElementById('reset-filter-patrol-container');
            const tableRows = document.querySelectorAll('#patrolTable tbody tr[data-hari]');

            if (!searchInput && !reguSelect) return;

            const urlParams = new URLSearchParams(window.location.search);
            const hasServerFilter = urlParams.has('search') || urlParams.has('regu');

            function applyFilter() {
                const term = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const selectedRegu = reguSelect ? reguSelect.value.toLowerCase().trim() : '';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || selectedRegu !== '';
                let resetBtn = document.getElementById('btn-reset-filter-patrol');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-patrol';
                        resetBtn.className = 'inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition-all cursor-pointer whitespace-nowrap shadow-2xs w-full sm:w-auto';
                        resetBtn.setAttribute('title', 'Reset semua filter pencarian');
                        resetBtn.innerHTML = `
                            <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="text-red-600 font-bold">${['Reset', 'Filter'].join(' ')}</span>
                        `;
                        resetBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (searchInput) searchInput.value = '';
                            if (reguSelect) reguSelect.value = '';
                            applyFilter();

                            if (window.Livewire) {
                                const compEl = searchInput ? searchInput.closest('[wire\\:id]') : null;
                                if (compEl && window.Livewire.find) {
                                    const comp = window.Livewire.find(compEl.getAttribute('wire:id'));
                                    if (comp) {
                                        if (typeof comp.call === 'function') {
                                            comp.call('resetFilters');
                                        } else {
                                            comp.set('search', '');
                                            comp.set('reguFilter', '');
                                        }
                                    }
                                }
                            }

                            if (hasServerFilter) {
                                window.location.href = window.location.pathname;
                            }
                        });
                        resetContainer.appendChild(resetBtn);
                    }
                } else {
                    if (!hasServerFilter && resetBtn) {
                        resetBtn.remove();
                    }
                }

                tableRows.forEach(row => {
                    const hari = row.getAttribute('data-hari') || '';
                    const regu = row.getAttribute('data-regu') || '';
                    const petugas = row.getAttribute('data-petugas') || '';
                    const catatan = row.getAttribute('data-catatan') || '';

                    const matchesQuery = !term || 
                        hari.includes(term) || 
                        regu.includes(term) || 
                        petugas.includes(term) || 
                        catatan.includes(term);

                    const matchesRegu = !selectedRegu || regu.includes(selectedRegu);

                    if (matchesQuery && matchesRegu) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyFilter);
            }
            if (reguSelect) {
                reguSelect.addEventListener('change', applyFilter);
            }
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    applyFilter();
                });
            }

            if (searchInput.value.trim().length > 0 || (reguSelect && reguSelect.value !== '')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
