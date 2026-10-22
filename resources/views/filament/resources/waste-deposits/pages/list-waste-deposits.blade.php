<x-filament-panels::page>
    <div 
        x-data="{
            deleteConfirmModalOpen: false,
            deleteDepositId: null,
            deleteDepositLabel: '',
            detailModalOpen: @entangle('detailModalOpen'),
            formModalOpen: @entangle('formModalOpen'),
            confirmDelete(id, label) {
                this.deleteDepositId = id;
                this.deleteDepositLabel = label;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteDepositId = null;
                this.deleteDepositLabel = '';
            },
            executeDelete() {
                if (this.deleteDepositId) {
                    const id = this.deleteDepositId;
                    this.closeDeleteConfirm();
                    $wire.deleteDeposit(id);
                }
            }
        }"
        @keydown.escape.window="closeDeleteConfirm(); if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); } if (formModalOpen) { formModalOpen = false; $wire.closeForm(); }"
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
                        title="Setoran Bank Sampah Lestari"
                        category="BANK SAMPAH RT 15"
                        description="Modul pencatatan transaksi penimbangan sampah anorganik terpilah warga RT 15, penghitungan saldo tabungan lingkungan, serta riwayat konversi berat menjadi rupiah."
                        :steps="[
                            ['title' => 'Pencarian & Filter Jenis Sampah', 'desc' => 'Cari berdasarkan nama nasabah/warga atau filter transaksi menurut jenis komoditas sampah terpilah.'],
                            ['title' => 'Catat Penimbangan Baru', 'desc' => 'Klik tombol biru \'+ Catat Penimbangan\' untuk memilih nama warga, jenis sampah, berat (kg), dan kalkulasi otomatis nominal.'],
                            ['title' => 'Aksi & Verifikasi Nota', 'desc' => 'Gunakan tombol \'Lihat\' untuk membuka nota penimbangan, \'Ubah\' untuk koreksi timbangan, atau \'Hapus\' jika terjadi duplikasi.'],
                            ['title' => 'Ekspor Buku Tabungan', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh rekap timbangan dan tabungan nasabah dalam format PDF atau Excel.']
                        ]"
                        role="Pengurus Bank Sampah Lestari RT 15"
                        :tips="[
                            'Pastikan komoditas sampah dalam kondisi bersih dan kering sebelum ditimbang.',
                            'Harga beli per kg dihitung otomatis berdasarkan data Master Jenis Sampah yang sedang aktif.',
                            'Saldo tabungan warga akan langsung terakumulasi pada dompet virtual bank sampah di portal warga.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Setoran Bank Sampah Lestari
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Catat Penimbangan) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Dropdown Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="waste-deposits" :extraParams="['type' => '$wire.wasteTypeFilter']" />

                {{-- Tombol Primer: Catat Penimbangan --}}
                @if(\App\Filament\Resources\WasteDeposits\WasteDepositResource::canCreate())
                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Catat Penimbangan</span>
                </button>
                @endif
            </div>
        </div>

        {{-- ====================================================================
             2. SEARCH BAR & FILTER DROPDOWN & RESET FILTER (STANDAR RESMI)
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center sm:justify-between gap-2.5">
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
                        id="search-deposit-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari ... " 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-deposit-clear-btn" 
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

            {{-- Wrapper Filter & Reset: Di mobile bertumpuk/sejajar, di tablet/desktop sejajar horizontal di sisi kanan --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 shrink-0">
                {{-- Filter Pilihan Jenis Sampah --}}
                <div class="w-full sm:w-60">
                    <select 
                        id="filter-deposit-type"
                        name="type"
                        wire:model.live="wasteTypeFilter" 
                        class="w-full py-2.5 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                    >
                        <option value="">Semua Jenis Sampah</option>
                        @foreach($wasteTypeList as $wt)
                            <option value="{{ $wt->id }}" {{ request('type') == $wt->id ? 'selected' : '' }}>
                                {{ $wt->nama }} (Rp {{ number_format($wt->harga_per_kg, 0, ',', '.') }}/{{ $wt->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-deposit-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || filled($wasteTypeFilter) 
                            || request()->anyFilled(['search', 'type']);
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter-deposit" 
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
             4. TABEL DATA SETORAN (DEEP NAVY #0B3A85 TABLE HEADER)
             ==================================================================== --}}
        <div class="bg-white rounded-none md:rounded-2xl border border-slate-200 overflow-hidden shadow-xs relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="wasteTypeFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                text="Memperbarui data setoran sampah..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-full md:min-w-[1050px] text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white">
                            <th class="hidden md:table-cell py-3 px-4 font-bold text-center w-12 shrink-0">NO</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[150px] whitespace-nowrap">Waktu Setor</th>
                            <th class="py-3 px-3 sm:px-4 font-bold min-w-[140px] md:min-w-[220px]">Keluarga Penyetor (KK)</th>
                            <th class="py-3 px-3 sm:px-4 font-bold min-w-[120px] md:min-w-[160px] whitespace-nowrap">Jenis Komoditas</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold text-right min-w-[130px] whitespace-nowrap">Berat Timbangan</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold text-right min-w-[130px] whitespace-nowrap">Nilai Tabungan</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[140px] whitespace-nowrap">Petugas Pencatat</th>
                            <th class="py-3 px-2 sm:px-4 font-bold text-center w-16 sm:w-24 md:w-32 md:min-w-[130px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody 
                        id="deposits-table-body" 
                        class="divide-y divide-slate-100 transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="wasteTypeFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                    >
                        @forelse($deposits as $index => $item)
                            <tr 
                                class="hover:bg-slate-50/80 transition-colors deposit-data-row"
                                data-waste-type="{{ $item->waste_type_id }}"
                            >
                                {{-- Kolom Nomor Urut --}}
                                <td class="hidden md:table-cell py-3.5 px-4 text-center font-mono font-semibold text-slate-500 text-xs whitespace-nowrap">
                                    {{ $deposits->firstItem() ? ($deposits->firstItem() + $index) : ($index + 1) }}
                                </td>

                                {{-- Kolom Waktu Setor --}}
                                <td class="hidden md:table-cell py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-bold text-[#0F1F3D]">
                                        {{ $item->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        {{ $item->created_at->format('H:i') }} WIB
                                    </div>
                                </td>

                                {{-- Kolom Keluarga Penyetor --}}
                                <td class="py-3.5 px-3 sm:px-4">
                                    <div class="font-bold text-slate-800 text-[12px] sm:text-[12.5px]">
                                        {{ $item->household && $item->household->headOfHousehold ? $item->household->headOfHousehold->nama : 'Warga RT 15' }}
                                    </div>
                                    <div class="text-[10px] sm:text-[10.5px] text-slate-400 flex flex-wrap items-center gap-1 mt-0.5">
                                        <span class="font-mono">KK: {{ $item->household ? $item->household->no_kk : '-' }}</span>
                                        @if($item->household && $item->household->alamat)
                                            <span class="hidden sm:inline">•</span>
                                            <span class="hidden sm:inline truncate max-w-[150px]">{{ $item->household->alamat }}</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kolom Jenis Komoditas Sampah --}}
                                <td class="py-3.5 px-3 sm:px-4">
                                    <div class="font-extrabold text-[#0F1F3D] text-[12px] sm:text-[13px]">
                                        {{ $item->wasteType ? $item->wasteType->nama : '-' }}
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1 sm:gap-1.5 mt-0.5">
                                        @php
                                            $kat = $item->wasteType ? $item->wasteType->kategori : 'lainnya';
                                            $badgeClass = match($kat) {
                                                'organik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'anorganik' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'b3' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-1.5 sm:px-2 py-0.2 rounded-md text-[9px] sm:text-[9.5px] font-bold uppercase tracking-wider border {{ $badgeClass }}">
                                            {{ $kat }}
                                        </span>
                                        <span class="text-[9.5px] sm:text-[10px] text-slate-400">
                                            @ Rp {{ number_format($item->harga_saat_setor, 0, ',', '.') }}/{{ $item->wasteType->satuan ?? 'kg' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Kolom Berat Timbangan --}}
                                <td class="hidden md:table-cell py-3.5 px-4 text-right whitespace-nowrap">
                                    <span class="font-black text-[13px] text-slate-800">
                                        {{ number_format($item->berat_kg, 2, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-medium ml-0.5">
                                        {{ $item->wasteType->satuan ?? 'kg' }}
                                    </span>
                                </td>

                                {{-- Kolom Nilai Tabungan --}}
                                <td class="hidden md:table-cell py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="font-black text-emerald-600 text-[13px]">
                                        Rp {{ number_format($item->nilai, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[9.5px] text-slate-400 mt-0.5">
                                        Masuk Saldo KK
                                    </div>
                                </td>

                                {{-- Kolom Petugas Pencatat --}}
                                <td class="hidden md:table-cell py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-medium text-slate-700">
                                        {{ $item->pencatat ? $item->pencatat->name : 'Pengurus RT' }}
                                    </div>
                                    @if($item->catatan)
                                        <div class="text-[10px] text-slate-400 max-w-[120px] truncate" title="{{ $item->catatan }}">
                                            {{ $item->catatan }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Kolom Aksi dengan Tombol Berwarna Konsisten (Standar SIM-LiBas) --}}
                                <td class="py-3.5 px-2 sm:px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex flex-col md:flex-row items-center justify-center gap-1 md:gap-1.5 whitespace-nowrap">
                                        {{-- Detail Modal (Biru Deep Navy) --}}
                                        <button 
                                            type="button" 
                                            @click="detailModalOpen = true"
                                            wire:click="openDetail({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer"
                                            title="Lihat Detail Setoran"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Ubah Modal (Kuning/Amber) --}}
                                        @if(\App\Filament\Resources\WasteDeposits\WasteDepositResource::canEdit($item))
                                        <button 
                                            type="button" 
                                            @click="formModalOpen = true"
                                            wire:click="openEdit({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer"
                                            title="Koreksi Data Timbangan"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        @endif

                                        {{-- Hapus Modal (Merah/Rose) --}}
                                        @if(\App\Filament\Resources\WasteDeposits\WasteDepositResource::canDelete($item))
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $item->id }}, 'Setoran {{ $item->wasteType ? addslashes($item->wasteType->nama) : 'Sampah' }} ({{ number_format($item->berat_kg, 2) }} kg)')"
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer"
                                            title="Batalkan & Hapus Data Setoran"
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
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <p class="text-sm font-semibold text-slate-600">Belum ada catatan setoran sampah</p>
                                        <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Catat Penimbangan" untuk membukukan setoran warga.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Baris Khusus Hasil Filter Kosong (Client-Side JS) --}}
                        <tr id="deposit-client-empty-row" style="display: none;">
                            <td colspan="8" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-slate-600">Tidak ada data setoran yang cocok dengan pencarian Anda</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Coba cari dengan nama kepala keluarga, jenis sampah, atau nomor KK.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if($deposits->hasPages())
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $deposits->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL SETORAN (ALPINE.JS X-SHOW INSTANT OPEN)
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
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-md border border-slate-200" 
                    @click.stop
                >
                    <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-blue-200">Rincian Penimbangan</span>
                            <h3 class="text-base font-black">Setoran Bank Sampah {{ $selectedDeposit ? '#' . $selectedDeposit->id : '' }}</h3>
                        </div>
                        <button 
                            type="button" 
                            @click="detailModalOpen = false; $wire.closeDetail()" 
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Asynchronous Skeleton Loader while fetching selectedDeposit --}}
                    <div wire:loading.flex wire:target="openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-16 bg-slate-100 rounded-xl"></div>
                            <div class="h-16 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openDetail">
                        @if($selectedDeposit)
                            <div class="p-5 space-y-4 text-xs">
                                {{-- Kartu Penyetor --}}
                                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200/60 space-y-2">
                                    <div>
                                        <span class="text-slate-400 font-medium">Keluarga Penyetor:</span>
                                        <p class="font-bold text-slate-800 text-[13px]">
                                            {{ $selectedDeposit->household && $selectedDeposit->household->headOfHousehold ? $selectedDeposit->household->headOfHousehold->nama : 'Warga RT 15' }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 font-mono">
                                            No. KK: {{ $selectedDeposit->household ? $selectedDeposit->household->no_kk : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 font-medium">Alamat Rumah:</span>
                                        <p class="text-slate-700">
                                            {{ $selectedDeposit->household ? $selectedDeposit->household->alamat : '-' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Kartu Komoditas & Timbangan --}}
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-blue-50/50 rounded-xl p-3 border border-blue-100">
                                        <span class="text-slate-500 font-medium">Jenis Komoditas:</span>
                                        <p class="font-black text-[#0B3A85] text-[13px] mt-0.5">
                                            {{ $selectedDeposit->wasteType ? $selectedDeposit->wasteType->nama : '-' }}
                                        </p>
                                        <span class="text-[10px] uppercase font-bold text-slate-400">
                                            Kategori {{ $selectedDeposit->wasteType ? $selectedDeposit->wasteType->kategori : '-' }}
                                        </span>
                                    </div>

                                    <div class="bg-emerald-50/50 rounded-xl p-3 border border-emerald-100">
                                        <span class="text-slate-500 font-medium">Berat Timbangan:</span>
                                        <p class="font-black text-emerald-700 text-[13px] mt-0.5">
                                            {{ number_format($selectedDeposit->berat_kg, 2, ',', '.') }} {{ $selectedDeposit->wasteType->satuan ?? 'kg' }}
                                        </p>
                                        <span class="text-[10px] text-slate-400">
                                            @ Rp {{ number_format($selectedDeposit->harga_saat_setor, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Total Nilai Tabungan --}}
                                <div class="p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 text-center">
                                    <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider block">Nilai Masuk Buku Tabungan</span>
                                    <div class="text-2xl font-black text-emerald-700 mt-1">
                                        Rp {{ number_format($selectedDeposit->nilai, 0, ',', '.') }}
                                    </div>
                                </div>

                                {{-- Meta Info --}}
                                <div class="border-t border-slate-100 pt-3 space-y-1.5 text-[11px] text-slate-500">
                                    <div class="flex justify-between">
                                        <span>Waktu Transaksi:</span>
                                        <span class="font-bold text-slate-700">{{ $selectedDeposit->created_at->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Petugas Penimbang:</span>
                                        <span class="font-bold text-slate-700">{{ $selectedDeposit->pencatat ? $selectedDeposit->pencatat->name : 'Pengurus RT' }}</span>
                                    </div>
                                    @if($selectedDeposit->catatan)
                                        <div class="pt-1.5">
                                            <span class="font-medium text-slate-400 block">Catatan Khusus:</span>
                                            <p class="text-slate-700 italic mt-0.5">{{ $selectedDeposit->catatan }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-slate-50 px-5 py-3.5 flex justify-end gap-2 border-t border-slate-100">
                                <button 
                                    type="button" 
                                    @click="detailModalOpen = false; $wire.closeDetail()" 
                                    class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 text-xs transition-colors cursor-pointer"
                                >
                                    Tutup
                                </button>
                            </div>
                        @else
                            <div class="p-6 flex-col space-y-4 animate-pulse">
                                <div class="h-20 bg-slate-100 rounded-xl"></div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="h-16 bg-slate-100 rounded-xl"></div>
                                    <div class="h-16 bg-slate-100 rounded-xl"></div>
                                </div>
                                <div class="h-20 bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL FORM PENIMBANGAN (TAMBAH / UBAH) (ALPINE.JS X-SHOW INSTANT OPEN)
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
                    <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-blue-200">Pencatatan Penimbangan</span>
                            <h3 class="text-base font-black">{{ $isEditing ? 'Koreksi Data Penimbangan' : 'Catat Setoran Sampah Baru' }}</h3>
                        </div>
                        <button 
                            type="button" 
                            @click="formModalOpen = false; $wire.closeForm()" 
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Form Skeleton Loading on openEdit --}}
                    <div wire:loading.flex wire:target="openEdit" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-10 w-full bg-slate-100 rounded-xl"></div>
                        <div class="h-10 w-full bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-10 w-full bg-slate-100 rounded-xl"></div>
                            <div class="h-10 w-full bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-16 w-full bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openEdit">
                        <form wire:submit.prevent="saveDeposit" class="p-5 space-y-3.5 text-xs">
                            {{-- Pilih Keluarga Penyetor --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Keluarga Penyetor (KK) <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    wire:model="form_household_id" 
                                    class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                >
                                    <option value="">-- Pilih Kartu Keluarga Penyetor --</option>
                                    @foreach($householdList as $hh)
                                        <option value="{{ $hh->id }}">
                                            KK No. {{ $hh->no_kk }} - {{ $hh->headOfHousehold ? $hh->headOfHousehold->nama : 'Tanpa Kepala KK' }} ({{ $hh->alamat }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('form_household_id') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                            </div>

                            {{-- Pilih Jenis Komoditas Sampah --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Jenis Komoditas Sampah <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    wire:model.live="form_waste_type_id" 
                                    class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                >
                                    <option value="">-- Pilih Jenis Komoditas Sampah --</option>
                                    @foreach($wasteTypeList as $wt)
                                        <option value="{{ $wt->id }}">
                                            {{ $wt->nama }} (Kategori: {{ ucfirst($wt->kategori) }} - Rp {{ number_format($wt->harga_per_kg, 0, ',', '.') }}/{{ $wt->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('form_waste_type_id') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                            </div>

                            {{-- Berat Timbangan & Harga Satuan --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Berat Timbangan (Kg) <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        step="any" 
                                        wire:model.live.debounce.300ms="form_berat_kg" 
                                        placeholder="Contoh: 1 atau 2.5"
                                        class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800 font-bold"
                                    >
                                    @error('form_berat_kg') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Harga Patokan (Rp/Kg) <span class="text-[11px] font-normal text-slate-400">(Terkunci / Sesuai Master)</span>
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="number" 
                                            wire:model="form_harga_saat_setor" 
                                            readonly 
                                            tabindex="-1"
                                            class="w-full p-2.5 pr-8 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 font-bold text-xs cursor-not-allowed select-none focus:outline-none"
                                            title="Harga patokan otomatis terkunci sesuai data master jenis komoditas sampah"
                                        >
                                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('form_harga_saat_setor') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Kalkulasi Nilai Tabungan Otomatis --}}
                            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                                <div>
                                    <span class="text-emerald-800 font-bold text-[11px] block">Estimasi Nilai Tabungan:</span>
                                    <span class="text-[10px] text-emerald-600">Dihitung otomatis (Berat x Harga)</span>
                                </div>
                                <div class="text-lg font-black text-emerald-700 font-mono">
                                    Rp {{ number_format((float) $form_nilai, 0, ',', '.') }}
                                </div>
                            </div>

                            {{-- Catatan Penimbangan --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Catatan / Keterangan Penimbangan
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="form_catatan" 
                                    placeholder="Contoh: Botol bersih tanpa tutup, kardus kering"
                                    class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                >
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
                                    {{ $isEditing ? 'Simpan Koreksi' : 'Catat Penimbangan & Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL KONFIRMASI HAPUS (ALPINE JS)
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
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
                @click="closeDeleteConfirm()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-md border border-slate-200 p-6 text-center"
                    @click.stop
                >
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>

                    <h4 class="text-base font-black text-slate-800">Hapus Data Penimbangan?</h4>
                    <p class="text-xs text-slate-500 mt-1.5">
                        Apakah Anda yakin ingin membatalkan & menghapus data <span class="font-bold text-slate-800" x-text="deleteDepositLabel"></span>? Nilai tabungan keluarga penyetor akan otomatis disesuaikan kembali.
                    </p>

                    <div class="flex items-center justify-center gap-2 mt-5">
                        <button 
                            type="button" 
                            @click="closeDeleteConfirm()" 
                            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            @click="executeDelete()" 
                            class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-colors shadow-xs"
                        >
                            Ya, Hapus Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================================
         8. JAVASCRIPT STANDAR: REAL-TIME SEARCH & FILTER RESET INJECTION
         ==================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initDepositSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initDepositSearch();
        });

        function initDepositSearch() {
            const searchInput = document.getElementById('search-deposit-input');
            const clearBtn = document.getElementById('search-deposit-clear-btn');
            const typeSelect = document.getElementById('filter-deposit-type');
            const resetContainer = document.getElementById('reset-filter-deposit-container');
            const tableBody = document.getElementById('deposits-table-body');

            if (!searchInput) return;

            const urlParams = new URLSearchParams(window.location.search);
            const hasServerFilter = urlParams.has('search') || urlParams.has('type');

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const typeVal = typeSelect ? typeSelect.value : '';

                // Tampilkan/sembunyikan tombol clear
                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                // Injeksi elemen tombol pembersih pencarian secara dinamis (warna merah menyala & soft background)
                const hasActiveFilter = term.length > 0 || (typeVal !== '');
                let resetBtn = document.getElementById('btn-reset-filter-deposit');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-deposit';
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
                    const rows = tableBody.querySelectorAll('tr.deposit-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowType = row.dataset.wasteType;

                        let matchesSearch = term === '' || content.includes(term);
                        let matchesType = (!typeVal) ? true : (rowType === typeVal);

                        if (matchesSearch && matchesType) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('deposit-client-empty-row');
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
                    const btn = e.target.closest('#btn-reset-filter-deposit');
                    if (!btn) return;

                    searchInput.value = '';
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
                                    comp.set('wasteTypeFilter', '');
                                }
                            }
                        }
                    }

                    if (hasServerFilter) {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            if (searchInput.value.trim().length > 0 || (typeSelect && typeSelect.value !== '')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
