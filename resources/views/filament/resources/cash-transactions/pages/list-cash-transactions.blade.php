<x-filament-panels::page>
    <div 
        x-data="{
            detailModalOpen: @entangle('detailModalOpen'),
            formModalOpen: @entangle('formModalOpen'),
            deleteConfirmModalOpen: false,
            deleteTxId: null,
            deleteTxInfo: '',
            confirmDelete(id, info) {
                this.deleteTxId = id;
                this.deleteTxInfo = info;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteTxId = null;
                this.deleteTxInfo = '';
            },
            executeDelete() {
                if (this.deleteTxId) {
                    const id = this.deleteTxId;
                    this.closeDeleteConfirm();
                    $wire.deleteTransaction(id);
                }
            },
        }"
        @keydown.escape.window="
            closeDeleteConfirm();
            if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); } 
            if (formModalOpen) { formModalOpen = false; $wire.closeFormModal(); }
        "
        class="space-y-4"
    >
        {{-- ====================================================================
             1. HEADER HALAMAN: SUBTITLE, JUDUL, DAN TOMBOL AKSI UTAMA
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>KEUANGAN RT 15</span>
                    <x-page-guide 
                        title="Buku Kas RT 15"
                        category="KEUANGAN RT 15"
                        description="Modul buku kas umum RT 15 untuk mencatat seluruh arus kas masuk (iuran warga, donasi, kas sosial) dan kas keluar (biaya operasional, perbaikan jalan, listrik pos kamling, konsumsi rapat)."
                        :steps="[
                            ['title' => 'Pencarian & Filter Transaksi', 'desc' => 'Cari berdasarkan uraian transaksi atau saring menurut Jenis Kas (Pemasukan / Pengeluaran) dan rentang tanggal.'],
                            ['title' => 'Catat Transaksi Manual', 'desc' => 'Klik tombol biru \'+ Catat Transaksi\' untuk menginput kas keluar atau kas masuk non-iuran beserta nota/bon bukti.'],
                            ['title' => 'Aksi & Audit Bukti Kas', 'desc' => 'Gunakan tombol \'Lihat\' untuk memeriksa foto kwitansi/nota pengeluaran, \'Ubah\' untuk perbaikan catatan, dan \'Hapus\' jika ada pembatalan.'],
                            ['title' => 'Ekspor Laporan Keuangan', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh laporan arus kas dan saldo kas akhir dalam format PDF atau Excel.']
                        ]"
                        role="Bendahara RT & Ketua RT"
                        :tips="[
                            'Setiap pengeluaran kas wajib melampirkan foto nota, bon, atau kwitansi fisik sebagai bukti pertanggungjawaban.',
                            'Pembayaran iuran warga yang divalidasi akan otomatis tercatat sebagai transaksi pemasukan di buku kas.',
                            'Saldo akhir kas dihitung secara otomatis dan ditampilkan transparan pada ringkasan keuangan warga.'
                        ]"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Buku Kas RT
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="cash-transactions" :extraParams="['jenis' => '$wire.jenisFilter', 'kategori' => '$wire.kategoriFilter', 'periode' => '$wire.periodeFilter', 'start_date' => '$wire.startDate', 'end_date' => '$wire.endDate']" />

                {{-- Tombol Primer: Tambah Transaksi Kas --}}
                @if(\App\Filament\Resources\CashTransactions\CashTransactionResource::canCreate())
                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#0B2F6B] text-white text-xs font-bold transition-all shadow-2xs hover:shadow-sm cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Catat Transaksi Kas</span>
                </button>
                @endif
            </div>
        </div>

        {{-- ====================================================================
             2. KARTU STATISTIK KAS RT (PEMASUKAN, PENGELUARAN, SALDO KAS)
             ==================================================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-4">
            {{-- Card 1: Total Pemasukan --}}
            <button 
                type="button" 
                wire:click="setJenis('masuk')"
                class="w-full text-left bg-white rounded-2xl p-4 sm:p-5 border {{ $jenisFilter === 'masuk' ? 'border-emerald-300 ring-2 ring-emerald-500/20 bg-emerald-50/20' : 'border-[#E2E8F0] hover:border-emerald-200' }} shadow-2xs hover:shadow-xs transition-all duration-150 flex items-center gap-3.5 sm:gap-4 cursor-pointer group"
                title="Filter Transaksi: Pemasukan"
            >
                <div class="w-12 h-12 rounded-2xl bg-[#ECFDF5] text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7m0 0H9m8 0v8"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] sm:text-[11px] font-bold text-slate-400 tracking-wider uppercase truncate">
                        TOTAL PEMASUKAN {{ $periodeFilter === 'bulan_ini' ? '(BULAN INI)' : ($periodeFilter === 'kustom' ? '(FILTER)' : '') }}
                    </div>
                    <div class="text-lg sm:text-2xl font-black text-[#0F1F3D] tracking-tight mt-0.5 truncate">
                        Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                    </div>
                </div>
            </button>

            {{-- Card 2: Total Pengeluaran --}}
            <button 
                type="button" 
                wire:click="setJenis('keluar')"
                class="w-full text-left bg-white rounded-2xl p-4 sm:p-5 border {{ $jenisFilter === 'keluar' ? 'border-rose-300 ring-2 ring-rose-500/20 bg-rose-50/20' : 'border-[#E2E8F0] hover:border-rose-200' }} shadow-2xs hover:shadow-xs transition-all duration-150 flex items-center gap-3.5 sm:gap-4 cursor-pointer group"
                title="Filter Transaksi: Pengeluaran"
            >
                <div class="w-12 h-12 rounded-2xl bg-[#FEF2F2] text-rose-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7l10 10m0 0H9m8 0V9"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] sm:text-[11px] font-bold text-slate-400 tracking-wider uppercase truncate">
                        TOTAL PENGELUARAN {{ $periodeFilter === 'bulan_ini' ? '(BULAN INI)' : ($periodeFilter === 'kustom' ? '(FILTER)' : '') }}
                    </div>
                    <div class="text-lg sm:text-2xl font-black text-[#0F1F3D] tracking-tight mt-0.5 truncate">
                        Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                    </div>
                </div>
            </button>

            {{-- Card 3: Saldo Kas Saat Ini / Periode --}}
            <button 
                type="button" 
                wire:click="setJenis('semua')"
                class="w-full text-left bg-white rounded-2xl p-4 sm:p-5 border {{ $jenisFilter === 'semua' ? 'border-[#0B3A85]/20' : 'border-[#E2E8F0] hover:border-[#0B3A85]/30' }} shadow-2xs hover:shadow-xs transition-all duration-150 flex items-center gap-3.5 sm:gap-4 cursor-pointer group"
                title="Tampilkan Semua Transaksi (Saldo Kas)"
            >
                <div class="w-12 h-12 rounded-2xl bg-[#ECFDF5] text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] sm:text-[11px] font-bold text-slate-400 tracking-wider uppercase truncate">
                        {{ $periodeFilter === 'semua' ? 'SALDO KAS SAAT INI' : 'SALDO KAS PERIODE' }}
                    </div>
                    <div class="text-lg sm:text-2xl font-black text-[#0F1F3D] tracking-tight mt-0.5 truncate">
                        Rp {{ number_format($saldoKas, 0, ',', '.') }}
                    </div>
                    @if($periodeFilter !== 'semua')
                        <div class="text-[9.5px] font-semibold text-slate-500 truncate mt-0.5" title="Saldo Kumulatif All-Time">
                            Kumulatif: Rp {{ number_format($saldoKumulatif, 0, ',', '.') }}
                        </div>
                    @endif
                </div>
            </button>
        </div>

        {{-- ====================================================================
             3. SEARCH BAR & DROPDOWN FILTER & RESET FILTER (STANDAR RESMI)
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
                        id="search-cash-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari keterangan transaksi, kategori kas, nominal, atau nama pencatat..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-cash-clear-btn" 
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

            {{-- Wrapper Filter & Reset: Di mobile tersusun rapi, di tablet/desktop sejajar horizontal --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 shrink-0">
                <div class="grid grid-cols-1 sm:grid-cols-3 sm:flex sm:items-center gap-2 sm:gap-2.5">
                    {{-- Filter Pilihan Arus Kas (Dropdown Pengganti Tab) --}}
                    <div class="w-full sm:w-44 lg:w-48">
                        <select 
                            id="filter-cash-jenis"
                            name="jenis"
                            wire:model.live="jenisFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="semua" {{ $jenisFilter === 'semua' ? 'selected' : '' }}>Semua Transaksi ({{ $countSemua }})</option>
                            <option value="masuk" {{ $jenisFilter === 'masuk' ? 'selected' : '' }}>Pemasukan ({{ $countMasuk }})</option>
                            <option value="keluar" {{ $jenisFilter === 'keluar' ? 'selected' : '' }}>Pengeluaran ({{ $countKeluar }})</option>
                        </select>
                    </div>

                    {{-- Filter Pilihan Kategori Kas --}}
                    <div class="w-full sm:w-44 lg:w-48">
                        <select 
                            id="filter-cash-kategori"
                            name="kategori"
                            wire:model.live="kategoriFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="">Semua Kategori Kas</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ (request('kategori') == $kat || $kategoriFilter === $kat) ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Pilihan Periode Waktu (Default: Semua Waktu / All Time) --}}
                    <div class="w-full sm:w-44 lg:w-48">
                        <select 
                            id="filter-cash-periode"
                            name="periode"
                            wire:model.live="periodeFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="semua" {{ $periodeFilter === 'semua' ? 'selected' : '' }}>Semua Waktu (All Time)</option>
                            <option value="bulan_ini" {{ $periodeFilter === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini ({{ now()->translatedFormat('M Y') }})</option>
                            <option value="kustom" {{ $periodeFilter === 'kustom' ? 'selected' : '' }}>Pilih Rentang Tanggal...</option>
                        </select>
                    </div>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-cash-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || ($jenisFilter !== 'semua' && filled($jenisFilter)) 
                            || filled($kategoriFilter) 
                            || ($periodeFilter !== 'semua' && filled($periodeFilter))
                            || filled($startDate)
                            || filled($endDate)
                            || request()->anyFilled(['search', 'kategori', 'periode', 'start_date', 'end_date']) 
                            || (request()->has('jenis') && request('jenis') !== 'semua' && request('jenis') !== '');
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter-cash" 
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

        {{-- Baris Rentang Tanggal Kustom (Tampil jika Periode = Kustom atau salah satu tanggal terisi) --}}
        @if($periodeFilter === 'kustom' || filled($startDate) || filled($endDate))
            <div class="flex flex-wrap items-center justify-between gap-3 p-3 bg-white border border-[#0B3A85]/20 rounded-xl shadow-2xs transition-all">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-1.5 text-xs font-bold text-[#0B3A85]">
                        <svg class="w-4 h-4 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Filter Tanggal Kas:</span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <label for="filter-start-date" class="text-[11px] font-semibold text-slate-500">Mulai:</label>
                        <input 
                            type="date" 
                            id="filter-start-date"
                            wire:model.live="startDate" 
                            class="px-2.5 py-1.5 bg-[#F8FAFC] border border-[#DDE5F0] rounded-lg text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] cursor-pointer"
                        />
                    </div>

                    <div class="flex items-center gap-1.5">
                        <label for="filter-end-date" class="text-[11px] font-semibold text-slate-500">Sampai:</label>
                        <input 
                            type="date" 
                            id="filter-end-date"
                            wire:model.live="endDate" 
                            class="px-2.5 py-1.5 bg-[#F8FAFC] border border-[#DDE5F0] rounded-lg text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] cursor-pointer"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        wire:click="setPeriode('bulan_ini')"
                        class="px-2.5 py-1 text-[11px] font-semibold rounded-lg border border-slate-200 hover:border-[#0B3A85] hover:text-[#0B3A85] text-slate-600 bg-white transition-colors cursor-pointer"
                    >
                        Bulan Ini
                    </button>
                    <button 
                        type="button" 
                        wire:click="setPeriode('semua')"
                        class="px-2.5 py-1 text-[11px] font-semibold rounded-lg border border-slate-200 hover:border-[#0B3A85] hover:text-[#0B3A85] text-slate-600 bg-white transition-colors cursor-pointer"
                    >
                        Semua Waktu
                    </button>
                </div>
            </div>
        @endif

        {{-- ====================================================================
             4. TABEL BUKU KAS RT (HEADER DEEP NAVY #0B3A85)
             ==================================================================== --}}
        <div class="bg-white rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="jenisFilter, kategoriFilter, periodeFilter, startDate, endDate, search, resetFilters, setPeriode, previousPage, nextPage, gotoPage"
                text="Memperbarui data buku kas..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left border-collapse">
                    {{-- Header Deep Navy --}}
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-3 min-w-[125px] whitespace-nowrap">TANGGAL</th>
                            <th class="py-2.5 px-3 text-center min-w-[110px] whitespace-nowrap">ARUS KAS</th>
                            <th class="py-2.5 px-3 min-w-[170px] whitespace-nowrap">KATEGORI</th>
                            <th class="py-2.5 px-3 min-w-[280px]">KETERANGAN / DESKRIPSI</th>
                            <th class="py-2.5 px-3 text-right min-w-[150px] whitespace-nowrap">NOMINAL (RP)</th>
                            <th class="py-2.5 px-3 text-center min-w-[100px] whitespace-nowrap">BUKTI</th>
                            <th class="py-2.5 px-3 min-w-[140px] whitespace-nowrap">PENCATAT</th>
                            <th class="py-2.5 px-3 text-center min-w-[100px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>

                    <tbody 
                        id="cash-table-body" 
                        class="divide-y divide-[#F1F5F9] text-xs transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="jenisFilter, kategoriFilter, periodeFilter, startDate, endDate, search, resetFilters, setPeriode, previousPage, nextPage, gotoPage"
                    >
                        {{-- Row Kosong Real-time Client-Side --}}
                        <tr id="cash-client-empty-row" style="display: none;">
                            <td colspan="9" class="py-12 text-center text-sm text-[#5B6B82]">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-[#0F1F3D]">Tidak ada catatan kas yang cocok.</p>
                                <p class="text-xs text-[#94A3B8] mt-0.5">Coba sesuaikan kata kunci pencarian Anda.</p>
                            </td>
                        </tr>

                        @forelse($transactions as $tx)
                            <tr 
                                class="cash-data-row hover:bg-[#F8FAFC] transition-colors"
                                data-jenis="{{ $tx->jenis }}"
                                data-kategori="{{ $tx->kategori }}"
                            >
                                {{-- 1. NO --}}
                                <td class="py-2.5 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $transactions->firstItem() ? ($transactions->firstItem() + $loop->index) : $loop->iteration }}
                                </td>

                                {{-- 2. TANGGAL --}}
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <div class="font-bold text-xs text-[#0F1F3D]">
                                        {{ \Carbon\Carbon::parse($tx->tanggal)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-[#5B6B82]">
                                        {{ \Carbon\Carbon::parse($tx->tanggal)->diffForHumans() }}
                                    </div>
                                </td>

                                {{-- 3. ARUS KAS (JENIS) --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($tx->jenis === 'masuk')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            </svg>
                                            <span>Masuk</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">
                                            <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                            </svg>
                                            <span>Keluar</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- 4. KATEGORI --}}
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $tx->kategori }}
                                    </span>
                                </td>

                                {{-- 5. KETERANGAN / DESKRIPSI --}}
                                <td class="py-2.5 px-3 min-w-[280px]">
                                    <div class="text-xs text-[#0F1F3D] font-medium leading-relaxed" title="{{ $tx->deskripsi }}">
                                        {{ $tx->deskripsi }}
                                    </div>
                                </td>

                                {{-- 6. NOMINAL (RP) --}}
                                <td class="py-2.5 px-3 text-right whitespace-nowrap font-mono">
                                    @if($tx->jenis === 'masuk')
                                        <span class="text-xs font-black text-emerald-600">
                                            + Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-xs font-black text-rose-600">
                                            - Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- 7. BUKTI TRANSAKSI --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($tx->bukti_path)
                                        <a 
                                            href="{{ \Illuminate\Support\Facades\Storage::url($tx->bukti_path) }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer" 
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-blue-50 text-[#0B3A85] border border-blue-200 hover:bg-blue-100 transition-colors"
                                            title="Buka lampiran bukti foto/kwitansi"
                                        >
                                            <svg class="w-3 h-3 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            <span>Ada Bukti</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-slate-400 font-sans italic">-</span>
                                    @endif
                                </td>

                                {{-- 8. PENCATAT --}}
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <div class="text-xs font-semibold text-[#0F1F3D]">
                                        {{ $tx->pencatat?->name ?? 'Pengurus RT' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        {{ $tx->created_at?->format('H:i') ?? '' }} WIB
                                    </div>
                                </td>

                                {{-- 9. AKSI --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1 whitespace-nowrap">
                                        {{-- Tombol Detail (Mata Navy) --}}
                                        <button 
                                            type="button" 
                                            @click="detailModalOpen = true"
                                            wire:click="openDetail({{ $tx->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer" 
                                            title="Lihat Detail Transaksi Kas"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Ubah (Pensil Amber Soft Hover) --}}
                                        @if(\App\Filament\Resources\CashTransactions\CashTransactionResource::canEdit($tx))
                                        <button 
                                            type="button" 
                                            @click="formModalOpen = true"
                                            wire:click="openEdit({{ $tx->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Ubah Catatan Transaksi"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        @endif

                                        {{-- Tombol Hapus (Sampah Rose Soft Hover) --}}
                                        @if(\App\Filament\Resources\CashTransactions\CashTransactionResource::canDelete($tx))
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $tx->id }}, '{{ addslashes(Str::limit($tx->kategori . ' - ' . $tx->deskripsi . ' (' . ($tx->jenis === 'masuk' ? '+' : '-') . 'Rp ' . number_format($tx->jumlah, 0, ',', '.') . ')', 70)) }}')" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Hapus Catatan Kas"
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
                                <td colspan="9" class="py-12 text-center text-sm text-[#5B6B82]">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    @if(filled($search))
                                        <p class="font-bold text-[#0F1F3D]">Tidak ada transaksi kas yang cocok.</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Tidak ditemukan transaksi dengan kata kunci "{{ $search }}".</p>
                                    @else
                                        <p class="font-bold text-[#0F1F3D]">Belum Ada Catatan Transaksi Kas</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Mulai catat transaksi penerimaan atau pengeluaran kas RT menggunakan tombol di atas.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($transactions->hasPages())
                <div class="p-3 border-t border-[#E2E8F0] bg-[#F8FAFC]">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL TRANSAKSI KAS (ALPINE.JS X-SHOW INSTANT OPEN)
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
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-lg border border-slate-200" 
                    @click.stop
                >
                    {{-- Header Modal --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold">Detail Transaksi Kas RT</h3>
                                <p class="text-[11px] text-white/70">
                                    ID Transaksi {{ $selectedTransaction ? '#' . $selectedTransaction->id : '' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false; $wire.closeDetail()" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Asynchronous Skeleton Loader while fetching selectedTransaction --}}
                    <div wire:loading.flex wire:target="openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-14 bg-slate-100 rounded-xl"></div>
                            <div class="h-14 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openDetail">
                        @if($selectedTransaction)
                            {{-- Isi Modal --}}
                            <div class="p-5 space-y-4 text-xs">
                                {{-- Ringkasan Nominal & Arus Kas --}}
                                <div class="p-4 rounded-xl {{ $selectedTransaction->jenis === 'masuk' ? 'bg-emerald-50 border border-emerald-200' : 'bg-rose-50 border border-rose-200' }} text-center">
                                    <span class="text-[11px] font-bold uppercase tracking-wider {{ $selectedTransaction->jenis === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $selectedTransaction->jenis === 'masuk' ? 'Pemasukan Kas' : 'Pengeluaran Kas' }}
                                    </span>
                                    <div class="text-2xl font-black mt-0.5 {{ $selectedTransaction->jenis === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $selectedTransaction->jenis === 'masuk' ? '+' : '-' }} Rp {{ number_format($selectedTransaction->jumlah, 0, ',', '.') }}
                                    </div>
                                    <span class="inline-block mt-1 text-[11px] font-semibold text-slate-500">
                                        Kategori: {{ $selectedTransaction->kategori }}
                                    </span>
                                </div>

                                {{-- Data Rincian --}}
                                <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                    <div>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold">Tanggal Transaksi</span>
                                        <p class="font-bold text-slate-800 mt-0.5">
                                            {{ \Carbon\Carbon::parse($selectedTransaction->tanggal)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold">Dicatat Oleh</span>
                                        <p class="font-bold text-slate-800 mt-0.5">
                                            {{ $selectedTransaction->pencatat?->name ?? 'Pengurus RT' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Keterangan / Uraian --}}
                                <div>
                                    <span class="text-[10.5px] font-bold text-slate-600 block mb-1">Keterangan / Deskripsi Transaksi:</span>
                                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 leading-relaxed">
                                        {{ $selectedTransaction->deskripsi }}
                                    </div>
                                </div>

                                {{-- Lampiran Bukti Foto / Kwitansi --}}
                                @if($selectedTransaction->bukti_path)
                                    <div>
                                        <span class="text-[10.5px] font-bold text-slate-600 block mb-1.5">Foto Bukti / Nota Pembayaran:</span>
                                        <div class="rounded-xl overflow-hidden border border-slate-200 max-h-56 bg-slate-100 flex items-center justify-center">
                                            <img 
                                                src="{{ \Illuminate\Support\Facades\Storage::url($selectedTransaction->bukti_path) }}" 
                                                alt="Bukti Transaksi" 
                                                class="w-full h-auto object-contain max-h-56"
                                            >
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Footer Modal --}}
                            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button 
                                    type="button" 
                                    @click="detailModalOpen = false; $wire.closeDetail()" 
                                    class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-all cursor-pointer"
                                >
                                    Tutup
                                </button>
                            </div>
                        @else
                            <div class="p-6 flex-col space-y-4 animate-pulse">
                                <div class="h-20 bg-slate-100 rounded-xl"></div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="h-14 bg-slate-100 rounded-xl"></div>
                                    <div class="h-14 bg-slate-100 rounded-xl"></div>
                                </div>
                                <div class="h-20 bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL FORM: TAMBAH / UBAH TRANSAKSI KAS (ALPINE.JS X-SHOW INSTANT OPEN)
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
                @click="formModalOpen = false; $wire.closeFormModal()"
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
                    {{-- Header Modal --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold">
                                    {{ $isEditing ? 'Ubah Catatan Transaksi Kas' : 'Catat Transaksi Kas Baru' }}
                                </h3>
                                <p class="text-[11px] text-white/70">Buku Kas Umum RT 15 Pematang Sulur</p>
                            </div>
                        </div>
                        <button type="button" @click="formModalOpen = false; $wire.closeFormModal()" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Form Skeleton Loading on openEdit --}}
                    <div wire:loading.flex wire:target="openEdit" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-10 w-full bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-20 w-full bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openEdit">
                        {{-- Form --}}
                        <form wire:submit.prevent="save" class="p-5 space-y-3.5 text-xs">
                            {{-- Pilihan Jenis: Masuk / Keluar --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Jenis Arus Kas *</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all {{ $form_jenis === 'masuk' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-bold' : 'bg-white border-slate-200 text-slate-600' }}">
                                        <input type="radio" wire:model.live="form_jenis" value="masuk" class="text-emerald-600 focus:ring-emerald-500">
                                        <span>Pemasukan (+ Masuk)</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all {{ $form_jenis === 'keluar' ? 'bg-rose-50 border-rose-300 text-rose-800 font-bold' : 'bg-white border-slate-200 text-slate-600' }}">
                                        <input type="radio" wire:model.live="form_jenis" value="keluar" class="text-rose-600 focus:ring-rose-500">
                                        <span>Pengeluaran (- Keluar)</span>
                                    </label>
                                </div>
                                @error('form_jenis') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Kategori Kas --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Kategori Kas *</label>
                                <input 
                                    type="text" 
                                    wire:model="form_kategori" 
                                    placeholder="Contoh: Iuran Warga, Operasional RT, Pemeliharaan, Donasi..." 
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                @error('form_kategori') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Grid: Nominal & Tanggal --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Nominal (Rp) *</label>
                                    <input 
                                        type="number" 
                                        min="1" 
                                        step="any" 
                                        wire:model="form_jumlah" 
                                        placeholder="Contoh: 250000" 
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 font-mono"
                                    >
                                    @error('form_jumlah') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Tanggal Transaksi *</label>
                                    <input 
                                        type="date" 
                                        wire:model="form_tanggal" 
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('form_tanggal') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Keterangan / Deskripsi --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Keterangan / Uraian Transaksi *</label>
                                <textarea 
                                    wire:model="form_deskripsi" 
                                    rows="3" 
                                    placeholder="Jelaskan rincian transaksi kas secara spesifik..." 
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                ></textarea>
                                @error('form_deskripsi') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Upload Foto Bukti Kwitansi / Nota --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Unggah Foto Bukti / Nota (Opsional)</label>
                                <input 
                                    type="file" 
                                    wire:model="form_bukti" 
                                    accept="image/*" 
                                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0B3A85]/10 file:text-[#0B3A85] hover:file:bg-[#0B3A85]/20 cursor-pointer"
                                >
                                @if($existing_bukti && !$form_bukti)
                                    <p class="text-[10px] text-slate-500 mt-1">Sudah ada foto bukti terlampir. Unggah file baru untuk menggantinya.</p>
                                @endif
                                @error('form_bukti') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Footer Form Buttons --}}
                            <div class="pt-2 flex items-center justify-end gap-2">
                                <button 
                                    type="button" 
                                    @click="formModalOpen = false; $wire.closeFormModal()" 
                                    class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    wire:loading.attr="disabled" 
                                    class="px-5 py-2.5 rounded-xl bg-[#0B3A85] hover:bg-[#0B2F6B] text-white text-xs font-bold transition-all shadow-2xs cursor-pointer inline-flex items-center gap-1.5"
                                >
                                    <span wire:loading.remove>{{ $isEditing ? 'Simpan Perubahan' : 'Catat Transaksi' }}</span>
                                    <span wire:loading>Menyimpan...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL KONFIRMASI HAPUS TRANSAKSI KAS
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="delete-tx-modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop --}}
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-tx-modal-title">Hapus Catatan Transaksi?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini akan menghapus catatan kas dan memperbarui saldo kas RT.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Transaksi Terpilih:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteTxInfo"></span>
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
                            <span>Ya, Hapus Transaksi</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================================
         7. SCRIPT CLIENT-SIDE REAL-TIME SEARCH & DYNAMIC RESET FILTER
         ==================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initCashClientSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initCashClientSearch();
        });

        document.addEventListener('livewire:initialized', function() {
            initCashClientSearch();
        });

        function initCashClientSearch() {
            const searchInput = document.getElementById('search-cash-input');
            const clearBtn = document.getElementById('search-cash-clear-btn');
            const jenisSelect = document.getElementById('filter-cash-jenis');
            const kategoriSelect = document.getElementById('filter-cash-kategori');
            const periodeSelect = document.getElementById('filter-cash-periode');
            const resetContainer = document.getElementById('reset-filter-cash-container');
            const tableBody = document.getElementById('cash-table-body');
            const hasServerFilter = {{ (request()->anyFilled(['search', 'kategori', 'periode', 'start_date', 'end_date']) || (request()->has('jenis') && request('jenis') !== 'semua' && request('jenis') !== '')) ? 'true' : 'false' }};

            if (!searchInput) return;

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const jenisVal = jenisSelect ? jenisSelect.value : 'semua';
                const kategoriVal = kategoriSelect ? kategoriSelect.value.toLowerCase() : '';
                const periodeVal = periodeSelect ? periodeSelect.value : 'semua';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || (jenisVal !== 'semua' && jenisVal !== '') || kategoriVal !== '' || (periodeVal !== 'semua' && periodeVal !== '');
                let resetBtn = document.getElementById('btn-reset-filter-cash');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-cash';
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
                    const rows = tableBody.querySelectorAll('tr.cash-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowJenis = row.dataset.jenis;
                        const rowKategori = row.dataset.kategori;

                        let matchesSearch = term === '' || content.includes(term);
                        let matchesJenis = (jenisVal === 'semua' || !jenisVal) ? true : (rowJenis === jenisVal);
                        let matchesKategori = (!kategoriVal) ? true : (rowKategori && rowKategori.toLowerCase() === kategoriVal);

                        if (matchesSearch && matchesJenis && matchesKategori) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('cash-client-empty-row');
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
                if (jenisSelect) jenisSelect.addEventListener('change', applyFilter);
                if (kategoriSelect) kategoriSelect.addEventListener('change', applyFilter);
                if (periodeSelect) periodeSelect.addEventListener('change', applyFilter);

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
                    const btn = e.target.closest('#btn-reset-filter-cash');
                    if (!btn) return;

                    searchInput.value = '';
                    if (jenisSelect) jenisSelect.value = 'semua';
                    if (kategoriSelect) kategoriSelect.value = '';
                    if (periodeSelect) periodeSelect.value = 'semua';
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
                                    comp.set('jenisFilter', 'semua');
                                    comp.set('kategoriFilter', '');
                                    comp.set('periodeFilter', 'semua');
                                    comp.set('startDate', '');
                                    comp.set('endDate', '');
                                }
                            }
                        }
                    }

                    if (hasServerFilter) {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            if (searchInput.value.trim().length > 0 || (jenisSelect && jenisSelect.value !== 'semua') || (kategoriSelect && kategoriSelect.value !== '') || (periodeSelect && periodeSelect.value !== 'semua')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
