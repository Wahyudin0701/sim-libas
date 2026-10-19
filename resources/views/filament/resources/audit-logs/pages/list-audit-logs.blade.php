<x-filament-panels::page>
    <div 
        x-data="{
            detailModalOpen: @entangle('detailModalOpen'),
            showRawJson: false,
        }"
        @keydown.escape.window="
            if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); }
        "
        class="space-y-4"
    >
        {{-- ====================================================================
             1. HEADER HALAMAN: SUBTITLE, JUDUL, PANDUAN, DAN TOMBOL EKSPOR
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>PENGATURAN SISTEM</span>
                    <x-page-guide 
                        title="Audit Log & Jejak Aktivitas"
                        category="PENGATURAN SISTEM"
                        description="Modul pemantauan jejak audit (audit trail) untuk merekam seluruh penambahan, pengubahan, dan penghapusan data penting di SIM-LiBas RT 15 sesuai UU Pelindungan Data Pribadi (UU PDP No. 27/2022)."
                        :steps="[
                            ['title' => 'Pencarian & Penyaringan', 'desc' => 'Gunakan kolom pencarian untuk melacak aktivitas berdasarkan nama pengurus, email, modul, atau jenis event (Dibuat, Diubah, Dihapus).'],
                            ['title' => 'Filter Berdasarkan Modul & Waktu', 'desc' => 'Saring log menurut modul spesifik (misal: Surat, Warga, User) atau rentang waktu tertentu (Hari ini, 7 hari, 30 hari).'],
                            ['title' => 'Inspeksi Rincian Perubahan', 'desc' => 'Klik ikon mata \'Lihat Detail\' pada baris log untuk memeriksa komparasi data sebelum dan sesudah perubahan.'],
                            ['title' => 'Ekspor Berkas Audit', 'desc' => 'Klik tombol \'Ekspor CSV\' untuk mengunduh salinan berkas log audit untuk kebutuhan pelaporan formal kepengurusan RT.']
                        ]"
                        role="Super Administrator & Pimpinan RT"
                        :tips="[
                            'Seluruh catatan log bersifat permanen (immutable) dan tidak dapat dihapus maupun diubah oleh siapapun demi menjamin keaslian data.',
                            'Data sensitif seperti kata sandi dan token otomatis disensor demi keamanan privasi.',
                            'Setiap surat yang diterbitkan dan kas yang dicatat terekam bersama identitas akun pengurus yang mengeksekusinya.'
                        ]"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Audit Log & Jejak Aktivitas
                    </h1>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Aktif Merekam
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Tombol Ekspor CSV --}}
                <button 
                    type="button" 
                    wire:click="exportCsv" 
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-[#DDE5F0] text-[#0F1F3D] text-xs font-bold transition-all shadow-2xs hover:shadow-xs cursor-pointer"
                    title="Unduh salinan berkas CSV audit log"
                >
                    <svg class="w-4 h-4 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Ekspor CSV</span>
                </button>

                {{-- Tombol Segarkan Data --}}
                <button 
                    type="button" 
                    wire:click="$refresh" 
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-2xs hover:shadow-xs cursor-pointer"
                    title="Segarkan data log terkini"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Segarkan</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. KARTU STATISTIK (KPI OVERVIEW)
             ==================================================================== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            {{-- Card 1: Total Log Aktivitas --}}
            <div class="bg-white rounded-xl p-4 border border-[#E2E8F0] shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#5B6B82]">Total Log</span>
                    <span class="p-1.5 rounded-lg bg-blue-50 text-[#0B3A85]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-black text-[#0F1F3D]">{{ number_format($totalLogs, 0, ',', '.') }}</span>
                    <span class="text-xs text-[#5B6B82] font-semibold">rekaman</span>
                </div>
                <p class="text-[11px] text-[#94A3B8] mt-0.5">Seluruh jejak aktivitas sistem</p>
            </div>

            {{-- Card 2: Aktivitas Hari Ini --}}
            <div class="bg-white rounded-xl p-4 border border-[#E2E8F0] shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#5B6B82]">Aktivitas Hari Ini</span>
                    <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-black text-[#0F1F3D]">{{ number_format($todayLogs, 0, ',', '.') }}</span>
                    <span class="text-xs text-emerald-700 font-semibold">aktivitas</span>
                </div>
                <p class="text-[11px] text-[#94A3B8] mt-0.5">Operasi per {{ date('d M Y') }}</p>
            </div>

            {{-- Card 3: Perubahan Data --}}
            <div class="bg-white rounded-xl p-4 border border-[#E2E8F0] shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#5B6B82]">Modifikasi Data</span>
                    <span class="p-1.5 rounded-lg bg-amber-50 text-amber-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-black text-[#0F1F3D]">{{ number_format($changeLogs, 0, ',', '.') }}</span>
                    <span class="text-xs text-amber-700 font-semibold">perubahan</span>
                </div>
                <p class="text-[11px] text-[#94A3B8] mt-0.5">Aksi Buat / Ubah / Hapus</p>
            </div>

            {{-- Card 4: Pengguna Teraktif --}}
            <div class="bg-white rounded-xl p-4 border border-[#E2E8F0] shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#5B6B82]">Pelaku Teraktif</span>
                    <span class="p-1.5 rounded-lg bg-indigo-50 text-indigo-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-2 truncate">
                    <span class="text-lg font-black text-[#0F1F3D] truncate" title="{{ $mostActiveUserName }}">{{ $mostActiveUserName }}</span>
                </div>
                <p class="text-[11px] text-indigo-700 font-semibold mt-0.5">{{ $mostActiveUserCount }} operasi tercatat</p>
            </div>
        </div>

        {{-- ====================================================================
             3. SEARCH BAR & MULTI DROPDOWN FILTER & RESET FILTER
             ==================================================================== --}}
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center lg:justify-between gap-2.5">
            {{-- Search Bar --}}
            <div class="relative w-full lg:max-w-md">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#94A3B8]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari pelaku, email, modul, ID, atau deskripsi..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    @if(filled($search))
                        <button 
                            type="button" 
                            wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#94A3B8] hover:text-[#0F1F3D] transition-colors cursor-pointer"
                            title="Hapus kata kunci pencarian"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Filter Sisi Kanan (Event, Modul, Periode & Reset) --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 flex-wrap">
                {{-- Filter Event / Tindakan --}}
                <div class="w-full sm:w-40">
                    <select 
                        wire:model.live="eventFilter" 
                        class="w-full py-2.5 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium"
                    >
                        <option value="semua">Semua Tindakan</option>
                        <option value="created">Data Dibuat (Created)</option>
                        <option value="updated">Data Diubah (Updated)</option>
                        <option value="deleted">Data Dihapus (Deleted)</option>
                        <option value="login">Masuk (Login)</option>
                    </select>
                </div>

                {{-- Filter Modul --}}
                <div class="w-full sm:w-44">
                    <select 
                        wire:model.live="moduleFilter" 
                        class="w-full py-2.5 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium"
                    >
                        <option value="semua">Semua Modul</option>
                        @foreach($availableModules as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Periode Waktu --}}
                <div class="w-full sm:w-36">
                    <select 
                        wire:model.live="periodFilter" 
                        class="w-full py-2.5 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium"
                    >
                        <option value="semua">Semua Waktu</option>
                        <option value="hari_ini">Hari Ini</option>
                        <option value="7_hari">7 Hari Terakhir</option>
                        <option value="30_hari">30 Hari Terakhir</option>
                        <option value="bulan_ini">Bulan Ini</option>
                    </select>
                </div>

                {{-- Tombol Reset Filter --}}
                @if(filled($search) || $eventFilter !== 'semua' || $moduleFilter !== 'semua' || $periodFilter !== 'semua')
                    <button 
                        type="button" 
                        wire:click="resetFilters" 
                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition-all cursor-pointer whitespace-nowrap shadow-2xs"
                        title="Kembalikan semua filter ke kondisi awal"
                    >
                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Reset Filter</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- ====================================================================
             4. TABEL AUDIT LOG (HEADER DEEP NAVY #0B3A85)
             ==================================================================== --}}
        <div class="relative bg-white rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden">
            {{-- Standard Loading Indicator --}}
            <x-table-loading-indicator 
                target="search, eventFilter, moduleFilter, periodFilter, resetFilters, exportCsv, previousPage, nextPage, gotoPage" 
                text="Memperbarui rekaman audit log..." 
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3 text-center w-12 min-w-[48px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-3 min-w-[170px]">WAKTU & TANGGAL (WIB)</th>
                            <th class="py-2.5 px-3 min-w-[210px]">PELAKU / PENGGUNA</th>
                            <th class="py-2.5 px-3 min-w-[140px]">TINDAKAN (EVENT)</th>
                            <th class="py-2.5 px-3 min-w-[170px]">MODUL & TARGET</th>
                            <th class="py-2.5 px-3 min-w-[240px]">RINGKASAN AKTIVITAS</th>
                            <th class="py-2.5 px-3 text-center w-24 min-w-[96px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9] text-xs text-[#0F1F3D]">
                        @forelse($logs as $index => $log)
                            <tr class="hover:bg-[#F8FAFC] transition-colors {{ $loop->odd ? 'bg-white' : 'bg-[#FAFCFE]' }}">
                                {{-- Nomor Urut --}}
                                <td class="py-3 px-3 text-center font-bold text-[#5B6B82]">
                                    {{ $logs->firstItem() + $index }}
                                </td>

                                {{-- Waktu & Tanggal --}}
                                <td class="py-3 px-3">
                                    <div class="font-bold text-[#0F1F3D] font-mono text-[11.5px]">
                                        {{ $log->formatted_date }}
                                    </div>
                                    <div class="text-[10.5px] text-[#94A3B8] font-medium mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $log->relative_time }}</span>
                                    </div>
                                </td>

                                {{-- Pelaku / Pengguna --}}
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#0B3A85] to-blue-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($log->causer_name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-[#0F1F3D] truncate" title="{{ $log->causer_name }}">
                                                {{ $log->causer_name }}
                                            </div>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-100 text-slate-600">
                                                    {{ $log->causer_role }}
                                                </span>
                                                @if($log->causer_email)
                                                    <span class="text-[10px] text-slate-400 font-mono truncate" title="{{ $log->causer_email }}">
                                                        {{ $log->causer_email }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tindakan (Event Badge) --}}
                                <td class="py-3 px-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-extrabold border {{ $log->action_badge_color }}">
                                        @if($log->event === 'created')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        @elseif($log->event === 'updated')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        @elseif($log->event === 'deleted')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                        <span>{{ $log->action_label }}</span>
                                    </span>
                                </td>

                                {{-- Modul & Target Objek --}}
                                <td class="py-3 px-3">
                                    <div class="font-bold text-[#0B3A85]">
                                        {{ $log->module_name }}
                                    </div>
                                    <div class="text-[10.5px] text-slate-500 font-mono mt-0.5">
                                        Target ID: <span class="font-bold text-slate-700">#{{ $log->subject_id ?? '-' }}</span>
                                    </div>
                                </td>

                                {{-- Deskripsi Ringkas --}}
                                <td class="py-3 px-3">
                                    <div class="font-medium text-[#0F1F3D] line-clamp-2">
                                        {{ $log->description }}
                                    </div>
                                    @php
                                        $safeProps = $log->getSafeProperties();
                                        $attrCount = isset($safeProps['attributes']) ? count($safeProps['attributes']) : 0;
                                    @endphp
                                    @if($attrCount > 0)
                                        <div class="text-[10px] text-[#5B6B82] mt-0.5">
                                            {{ $attrCount }} atribut data tercatat
                                        </div>
                                    @endif
                                </td>

                                {{-- Aksi: Buka Modal Detail --}}
                                <td class="py-3 px-3 text-center">
                                    <button 
                                        type="button" 
                                        wire:click="openDetail({{ $log->id }})" 
                                        class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-blue-300 bg-blue-50 text-[#0B3A85] hover:bg-blue-100 hover:text-[#07275E] hover:border-blue-400 text-xs font-bold transition-all shadow-2xs cursor-pointer"
                                        title="Periksa Rincian Lengkap & Diff Perubahan"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>Detail</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-sm font-semibold text-slate-600">Tidak ada data audit log yang sesuai</p>
                                        <p class="text-xs text-slate-400 mt-1">Sesuaikan kata kunci pencarian atau filter tindakan Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="p-3 border-t border-[#F1F5F9] bg-[#F8FAFC]">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL POPUP: RINCIAN AUDIT LOG & KOMPARASI DATA
             ==================================================================== --}}
        <div 
            x-show="detailModalOpen" 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-audit-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="detailModalOpen = false; $wire.closeDetail();"
            ></div>

            <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
                <div 
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-3xl border border-slate-200"
                    @click.stop
                >
                    @if($selectedLog)
                        {{-- Header Modal (Navy Standard #0B3A85) --}}
                        <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="p-2 rounded-xl bg-white/10 text-white shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                <div>
                                    <h3 id="modal-audit-title" class="text-base font-extrabold text-white">
                                        Rincian Log Aktivitas #{{ $selectedLog->id }}
                                    </h3>
                                    <p class="text-xs text-blue-100 font-mono mt-0.5">
                                        Terekam pada: {{ $selectedLog->formatted_date }} ({{ $selectedLog->relative_time }})
                                    </p>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                @click="detailModalOpen = false; $wire.closeDetail();" 
                                class="text-blue-200 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                                title="Tutup jendela rincian"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Body Modal --}}
                        <div class="p-5 sm:p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                            {{-- Seksi 1: Metadata Aktivitas --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pelaku Tindakan</span>
                                    <div class="font-extrabold text-[#0F1F3D] text-sm mt-0.5">
                                        {{ $selectedLog->causer_name }}
                                    </div>
                                    <div class="text-xs text-slate-600 mt-0.5">
                                        Peran: <span class="font-bold text-[#0B3A85]">{{ $selectedLog->causer_role }}</span>
                                        @if($selectedLog->causer_email)
                                            • <span class="font-mono text-slate-500">{{ $selectedLog->causer_email }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tindakan & Modul</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-extrabold border {{ $selectedLog->action_badge_color }}">
                                            {{ $selectedLog->action_label }}
                                        </span>
                                        <span class="font-bold text-[#0F1F3D] text-xs">
                                            {{ $selectedLog->module_name }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                        Target ID: #{{ $selectedLog->subject_id ?? '-' }}
                                    </div>
                                </div>

                                <div class="sm:col-span-2 pt-2 border-t border-slate-200/80">
                                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Deskripsi Aktivitas</span>
                                    <p class="text-xs text-slate-700 font-medium mt-0.5">
                                        {{ $selectedLog->description }}
                                    </p>
                                </div>
                            </div>

                            {{-- Seksi 2: Komparasi Perubahan Data (Diff) --}}
                            @php
                                $safeProps = $selectedLog->getSafeProperties();
                                $hasOld = isset($safeProps['old']) && is_array($safeProps['old']) && count($safeProps['old']) > 0;
                                $hasAttributes = isset($safeProps['attributes']) && is_array($safeProps['attributes']) && count($safeProps['attributes']) > 0;
                            @endphp

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-xs font-bold text-[#0F1F3D] uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                        <span>Rincian Nilai Data Terdampak</span>
                                    </h4>

                                    <button 
                                        type="button" 
                                        @click="showRawJson = !showRawJson" 
                                        class="text-[11px] text-[#0B3A85] font-bold hover:underline cursor-pointer"
                                    >
                                        <span x-show="!showRawJson">Tampilkan Format Mentah (JSON)</span>
                                        <span x-show="showRawJson" style="display: none;">Tampilkan Tampilan Tabel</span>
                                    </button>
                                </div>

                                {{-- Tampilan Mode Mentah (JSON) --}}
                                <div x-show="showRawJson" style="display: none;" class="p-3 bg-slate-900 text-emerald-400 font-mono text-[11px] rounded-xl overflow-x-auto max-h-60">
                                    <pre>{{ json_encode($safeProps, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>

                                {{-- Tampilan Mode Tabel Ramah Pengguna --}}
                                <div x-show="!showRawJson" class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                                    @if($hasOld && $hasAttributes)
                                        {{-- Kasus Diubah (Updated) - Tampilkan Side-by-side Diff --}}
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-xs border-collapse">
                                                <thead>
                                                    <tr class="bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider border-b border-slate-200">
                                                        <th class="py-2 px-3 w-1/3">Nama Atribut</th>
                                                        <th class="py-2 px-3 w-1/3 text-rose-700 bg-rose-50/50">Nilai Sebelumnya</th>
                                                        <th class="py-2 px-3 w-1/3 text-emerald-700 bg-emerald-50/50">Nilai Terbaru</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    @foreach($safeProps['attributes'] as $attrKey => $newVal)
                                                        @php
                                                            $oldVal = $safeProps['old'][$attrKey] ?? null;
                                                            $isChanged = $oldVal !== $newVal;
                                                        @endphp
                                                        <tr class="{{ $isChanged ? 'bg-amber-50/30' : '' }}">
                                                            <td class="py-2 px-3 font-mono font-bold text-slate-800">
                                                                {{ $attrKey }}
                                                            </td>
                                                            <td class="py-2 px-3 text-slate-600 font-mono bg-rose-50/20 break-all">
                                                                @if(is_null($oldVal))
                                                                    <span class="italic text-slate-400">null</span>
                                                                @elseif(is_bool($oldVal))
                                                                    <span class="font-bold">{{ $oldVal ? 'true' : 'false' }}</span>
                                                                @elseif(is_array($oldVal))
                                                                    <span>{{ json_encode($oldVal) }}</span>
                                                                @else
                                                                    <span>{{ (string) $oldVal }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="py-2 px-3 font-mono font-bold text-emerald-800 bg-emerald-50/20 break-all">
                                                                @if(is_null($newVal))
                                                                    <span class="italic text-slate-400">null</span>
                                                                @elseif(is_bool($newVal))
                                                                    <span>{{ $newVal ? 'true' : 'false' }}</span>
                                                                @elseif(is_array($newVal))
                                                                    <span>{{ json_encode($newVal) }}</span>
                                                                @else
                                                                    <span>{{ (string) $newVal }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @elseif($hasAttributes)
                                        {{-- Kasus Dibuat (Created) --}}
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-xs border-collapse">
                                                <thead>
                                                    <tr class="bg-emerald-50 text-emerald-800 text-[10px] font-bold uppercase tracking-wider border-b border-emerald-200">
                                                        <th class="py-2 px-3 w-1/3">Nama Atribut</th>
                                                        <th class="py-2 px-3 w-2/3">Nilai Disimpan</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    @foreach($safeProps['attributes'] as $attrKey => $newVal)
                                                        <tr>
                                                            <td class="py-2 px-3 font-mono font-bold text-slate-800">
                                                                {{ $attrKey }}
                                                            </td>
                                                            <td class="py-2 px-3 font-mono text-slate-700 break-all">
                                                                @if(is_null($newVal))
                                                                    <span class="italic text-slate-400">null</span>
                                                                @elseif(is_bool($newVal))
                                                                    <span class="font-bold">{{ $newVal ? 'true' : 'false' }}</span>
                                                                @elseif(is_array($newVal))
                                                                    <span>{{ json_encode($newVal) }}</span>
                                                                @else
                                                                    <span>{{ (string) $newVal }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @elseif($hasOld)
                                        {{-- Kasus Dihapus (Deleted) --}}
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-xs border-collapse">
                                                <thead>
                                                    <tr class="bg-rose-50 text-rose-800 text-[10px] font-bold uppercase tracking-wider border-b border-rose-200">
                                                        <th class="py-2 px-3 w-1/3">Nama Atribut Terhapus</th>
                                                        <th class="py-2 px-3 w-2/3">Nilai Terakhir Sebelum Dihapus</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    @foreach($safeProps['old'] as $attrKey => $oldVal)
                                                        <tr>
                                                            <td class="py-2 px-3 font-mono font-bold text-slate-800">
                                                                {{ $attrKey }}
                                                            </td>
                                                            <td class="py-2 px-3 font-mono text-slate-700 break-all">
                                                                @if(is_null($oldVal))
                                                                    <span class="italic text-slate-400">null</span>
                                                                @elseif(is_bool($oldVal))
                                                                    <span class="font-bold">{{ $oldVal ? 'true' : 'false' }}</span>
                                                                @elseif(is_array($oldVal))
                                                                    <span>{{ json_encode($oldVal) }}</span>
                                                                @else
                                                                    <span>{{ (string) $oldVal }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="p-4 text-center text-xs text-slate-400">
                                            Tidak ada atribut data spesifik yang dicatat untuk aktivitas ini.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Notice UU PDP & Integritas --}}
                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-blue-50/70 border border-blue-200 text-xs text-blue-900">
                                <svg class="w-4 h-4 text-[#0B3A85] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-[11px] leading-relaxed">
                                    <strong>Kepatuhan UU PDP No. 27/2022:</strong> Rekaman audit log ini bersifat tetap (*immutable*) dan tersimpan dengan enkripsi integritas untuk kepentingan audit independen, transparansi kelembagaan RT 15, dan pencegahan sengketa data warga.
                                </div>
                            </div>
                        </div>

                        {{-- Footer Modal --}}
                        <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end">
                            <button 
                                type="button" 
                                @click="detailModalOpen = false; $wire.closeDetail();" 
                                class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-colors cursor-pointer"
                            >
                                Tutup Rincian
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
