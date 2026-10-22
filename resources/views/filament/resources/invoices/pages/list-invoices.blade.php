<x-filament-panels::page>
    <div 
        x-data="{
            detailModalOpen: @entangle('detailModalOpen'),
            payModalOpen: @entangle('payModalOpen'),
            createModalOpen: @entangle('createModalOpen'),
            deleteConfirmModalOpen: false,
            deleteInvoiceId: null,
            deleteInvoiceInfo: '',
            confirmDelete(id, info) {
                this.deleteInvoiceId = id;
                this.deleteInvoiceInfo = info;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteInvoiceId = null;
                this.deleteInvoiceInfo = '';
            },
            executeDelete() {
                if (this.deleteInvoiceId) {
                    const id = this.deleteInvoiceId;
                    this.closeDeleteConfirm();
                    $wire.deleteInvoice(id);
                }
            },
        }"
        @keydown.escape.window="
            closeDeleteConfirm();
            if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); } 
            if (payModalOpen) { payModalOpen = false; $wire.closePay(); } 
            if (createModalOpen) { createModalOpen = false; $wire.closeCreate(); }
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
                        title="Tagihan Iuran Warga"
                        category="KEUANGAN RT 15"
                        description="Modul penerbitan dan pengelolaan tagihan iuran rutin warga RT 15 (kebersihan lingkungan, poskamling, kas sosial) serta pemantauan status pembayaran dan tunggakan per Kartu Keluarga."
                        :steps="[
                            ['title' => 'Cari & Filter Tagihan', 'desc' => 'Cari berdasarkan nama Kepala Keluarga / No. KK dan saring status pembayaran (Belum Bayar, Menunggu Konfirmasi, Lunas).'],
                            ['title' => 'Terbitkan Tagihan Baru', 'desc' => 'Klik tombol biru \'Terbitkan Tagihan\' untuk menerbitkan tagihan massal bulanan ke seluruh KK atau secara individu.'],
                            ['title' => 'Catat Bayar / Kwitansi', 'desc' => 'Gunakan tombol aksi \'Bayar Tunai\' untuk mencatat setoran langsung, atau \'Lihat\' untuk membuka rincian invoice dan mencetak kwitansi.'],
                            ['title' => 'Ekspor Laporan Tagihan', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh rekap tagihan dan tunggakan iuran dalam format PDF atau Excel.']
                        ]"
                        role="Bendahara RT"
                        :tips="[
                            'Penerbitan tagihan bulanan disarankan dilakukan pada awal bulan (tanggal 1-5).',
                            'Tagihan yang telah dibayar tunai oleh warga akan otomatis memperbarui saldo kas dan menerbitkan kwitansi sah.',
                            'Warga dapat melihat rincian tagihan mereka secara transparan di menu Iuran portal warga.'
                        ]"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Tagihan Iuran Warga
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="invoices" :extraParams="['status' => '$wire.statusFilter', 'fee_type_id' => '$wire.feeTypeFilter']" />

                {{-- Tombol Primer: Terbitkan Tagihan Baru --}}
                <button 
                    type="button" 
                    @click="createModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#0B2F6B] text-white text-xs font-bold transition-all shadow-2xs hover:shadow-sm cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Terbitkan Tagihan</span>
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
                        id="search-invoice-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari ... " 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-invoice-clear-btn" 
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
                    {{-- Filter Pilihan Status Tagihan (Dropdown Pengganti Tab) --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-invoice-status"
                            name="status"
                            wire:model.live="statusFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Status ({{ $totalTagihan }})</option>
                            <option value="belum_bayar" {{ $statusFilter === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar ({{ $countBelumBayar }})</option>
                            <option value="sebagian" {{ $statusFilter === 'sebagian' ? 'selected' : '' }}>Sebagian ({{ $countSebagian }})</option>
                            <option value="lunas" {{ $statusFilter === 'lunas' ? 'selected' : '' }}>Lunas ({{ $countLunas }})</option>
                        </select>
                    </div>

                    {{-- Filter Pilihan Jenis Iuran --}}
                    <div class="w-full sm:w-52">
                        <select 
                            id="filter-invoice-feetype"
                            name="fee_type"
                            wire:model.live="feeTypeFilter" 
                            class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                        >
                            <option value="">Semua Jenis Iuran</option>
                            @foreach($feeTypes as $ft)
                                <option value="{{ $ft->id }}" {{ (request('fee_type') == $ft->id || $feeTypeFilter == $ft->id) ? 'selected' : '' }}>
                                    {{ $ft->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-invoice-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || ($statusFilter !== 'semua' && filled($statusFilter)) 
                            || filled($feeTypeFilter) 
                            || request()->anyFilled(['search', 'fee_type']) 
                            || (request()->has('status') && request('status') !== 'semua' && request('status') !== '');
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter-invoice" 
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
             4. TABEL DATA TAGIHAN (HEADER DEEP NAVY #0B3A85)
             ==================================================================== --}}
        <div class="relative bg-white rounded-none md:rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden">
            {{-- Standard Loading Indicator --}}
            <x-table-loading-indicator 
                target="statusFilter, feeTypeFilter, search, resetFilters, previousPage, nextPage, gotoPage" 
                text="Memperbarui data tagihan iuran..." 
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-full md:min-w-[1100px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="hidden md:table-cell py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-2.5 sm:px-3 md:min-w-[240px]"><span class="md:hidden">KODE KK</span><span class="hidden md:inline">KARTU KELUARGA / KEPALA</span></th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[200px] whitespace-nowrap">JENIS IURAN</th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[130px] whitespace-nowrap">PERIODE</th>
                            <th class="hidden md:table-cell py-2.5 px-3 text-right min-w-[130px] whitespace-nowrap">NOMINAL</th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[125px] whitespace-nowrap">JATUH TEMPO</th>
                            <th class="py-2.5 px-2 sm:px-3 text-center min-w-[90px] md:min-w-[110px] whitespace-nowrap">STATUS</th>
                            <th class="py-2.5 px-2 sm:px-3 text-center w-24 sm:w-28 md:w-auto md:min-w-[110px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>

                    <tbody 
                        id="invoice-table-body" 
                        class="divide-y divide-[#F1F5F9] text-xs transition-opacity duration-200"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="statusFilter, feeTypeFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                    >
                        <tr id="invoice-client-empty-row" style="display: none;">
                            <td colspan="8" class="py-12 text-center text-sm text-[#5B6B82]">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-[#0F1F3D]">Tidak ada tagihan yang cocok.</p>
                                <p class="text-xs text-[#94A3B8] mt-0.5">Coba sesuaikan kata kunci pencarian Anda.</p>
                            </td>
                        </tr>

                        @forelse($invoices as $inv)
                            <tr 
                                class="invoice-data-row hover:bg-[#F8FAFC] transition-colors"
                                data-status="{{ $inv->status }}"
                                data-fee-type="{{ $inv->fee_type_id }}"
                            >
                                {{-- 1. NO (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $invoices->firstItem() ? ($invoices->firstItem() + $loop->index) : $loop->iteration }}
                                </td>

                                {{-- 2. KARTU KELUARGA / KODE KK (Mobile & Desktop) --}}
                                <td class="py-2.5 px-2.5 sm:px-3">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-mono text-[11px] font-bold text-[#0B3A85] bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200 shrink-0">
                                            {{ $inv->household?->id_kk ?? '-' }}
                                        </span>
                                        <span class="font-bold text-xs text-[#0F1F3D]">
                                            {{ $inv->household?->kepalaKeluarga?->nama ?? 'Kepala KK Belum Ditentukan' }}
                                        </span>
                                    </div>
                                    <div class="text-[10.5px] text-[#5B6B82] mt-0.5 line-clamp-1">
                                        {{ $inv->household?->alamat ?? '-' }}
                                    </div>
                                </td>

                                {{-- 3. JENIS IURAN (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 whitespace-nowrap">
                                    <span class="font-semibold text-xs text-slate-800">
                                        {{ $inv->feeType?->nama ?? '-' }}
                                    </span>
                                </td>

                                {{-- 4. PERIODE (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 whitespace-nowrap font-medium text-slate-700">
                                    {{ $inv->periode_label }}
                                </td>

                                {{-- 5. NOMINAL (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-right whitespace-nowrap font-mono font-bold text-slate-900">
                                    Rp {{ number_format($inv->jumlah, 0, ',', '.') }}
                                </td>

                                {{-- 6. JATUH TEMPO (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 whitespace-nowrap">
                                    @if($inv->tanggal_jatuh_tempo)
                                        <span class="text-xs text-slate-700 font-medium">
                                            {{ \Carbon\Carbon::parse($inv->tanggal_jatuh_tempo)->translatedFormat('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">-</span>
                                    @endif
                                </td>

                                {{-- 7. STATUS (Mobile & Desktop) --}}
                                <td class="py-2.5 px-2 sm:px-3 text-center whitespace-nowrap">
                                    @if($inv->status === 'lunas')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                            Lunas
                                        </span>
                                    @elseif($inv->status === 'sebagian')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-sky-50 text-sky-700 border border-sky-200 whitespace-nowrap">
                                            Sebagian
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                            Belum Bayar
                                        </span>
                                    @endif
                                </td>

                                {{-- 8. AKSI (Mobile & Desktop) --}}
                                <td class="py-2.5 px-2 sm:px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1 whitespace-nowrap">
                                        {{-- Tombol Detail Tagihan (Mata Navy) --}}
                                        <button 
                                            type="button" 
                                            @click="detailModalOpen = true"
                                            wire:click="openDetail({{ $inv->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer" 
                                            title="Lihat Rincian Tagihan & Riwayat Pembayaran"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Cepat Bayar (Hanya jika belum lunas) --}}
                                        @if($inv->status !== 'lunas')
                                            <button 
                                                type="button" 
                                                @click="payModalOpen = true"
                                                wire:click="openPay({{ $inv->id }})" 
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 hover:border-emerald-400 transition-all shadow-2xs cursor-pointer" 
                                                title="Catat Pembayaran Langsung (Tunai / Transfer)"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- Tombol Hapus Tagihan --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $inv->id }}, '{{ addslashes(($inv->feeType?->nama ?? 'Tagihan') . ' - ' . ($inv->household?->nama_kepala_keluarga ?? 'KK') . ' (' . $inv->periode_formatted . ')') }}')" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Hapus Tagihan"
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
                                    @if(filled($search))
                                        <p class="font-bold text-[#0F1F3D]">Tidak ada tagihan yang cocok.</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Tidak ditemukan tagihan dengan kata kunci "{{ $search }}".</p>
                                    @else
                                        <p class="font-bold text-[#0F1F3D]">Belum Ada Tagihan Iuran</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Mulai terbitkan lembar tagihan iuran baru menggunakan tombol di atas.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
                <div class="p-3 border-t border-[#E2E8F0] bg-[#F8FAFC]">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL TAGIHAN & RIWAYAT PEMBAYARAN (ALPINE.JS X-SHOW INSTANT OPEN)
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
                    <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold">Rincian Lembar Tagihan Iuran</h3>
                                <p class="text-[11px] text-white/70">
                                    {{ $selectedInvoice ? 'ID Tagihan #' . $selectedInvoice->id . ' • ' . $selectedInvoice->periode_label : 'Tagihan Iuran RT 15' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="detailModalOpen = false; $wire.closeDetail()" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Asynchronous Skeleton Loader while fetching selectedInvoice --}}
                    <div wire:loading.flex wire:target="openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                        <div class="h-24 bg-slate-100 rounded-xl"></div>
                        <div class="h-16 bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openDetail">
                        @if($selectedInvoice)
                            <div class="p-5 space-y-4 text-xs">
                                {{-- Kartu Info Status & Nominal --}}
                                <div class="p-4 rounded-xl {{ $selectedInvoice->status === 'lunas' ? 'bg-emerald-50 border border-emerald-200' : 'bg-amber-50 border border-amber-200' }} text-center">
                                    <span class="text-[11px] font-bold uppercase tracking-wider {{ $selectedInvoice->status === 'lunas' ? 'text-emerald-700' : 'text-amber-700' }}">
                                        Status: {{ strtoupper(str_replace('_', ' ', $selectedInvoice->status)) }}
                                    </span>
                                    <div class="text-2xl font-black mt-0.5 {{ $selectedInvoice->status === 'lunas' ? 'text-emerald-700' : 'text-amber-800' }}">
                                        Rp {{ number_format($selectedInvoice->jumlah, 0, ',', '.') }}
                                    </div>
                                    <span class="inline-block mt-1 text-[11px] font-semibold text-slate-500">
                                        {{ $selectedInvoice->feeType?->nama }} (Periode {{ $selectedInvoice->periode_label }})
                                    </span>
                                </div>

                                {{-- Info KK --}}
                                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500 font-medium">Nomor ID KK:</span>
                                        <span class="font-bold text-[#0B3A85] font-mono">{{ $selectedInvoice->household?->id_kk }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500 font-medium">Kepala Keluarga:</span>
                                        <span class="font-bold text-slate-900">{{ $selectedInvoice->household?->kepalaKeluarga?->nama ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500 font-medium">Alamat Rumah:</span>
                                        <span class="font-medium text-slate-700 text-right">{{ $selectedInvoice->household?->alamat ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500 font-medium">Jatuh Tempo:</span>
                                        <span class="font-semibold text-slate-800">{{ $selectedInvoice->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($selectedInvoice->tanggal_jatuh_tempo)->translatedFormat('d F Y') : '-' }}</span>
                                    </div>
                                </div>

                                {{-- Riwayat Pembayaran --}}
                                <div>
                                    <span class="text-[11px] font-bold text-slate-700 block mb-2">Riwayat Pembayaran Tercatat:</span>
                                    @if($selectedInvoice->payments->isNotEmpty())
                                        <div class="space-y-2">
                                            @foreach($selectedInvoice->payments as $p)
                                                <div class="p-3 bg-white border border-slate-200 rounded-xl flex items-center justify-between">
                                                    <div>
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="font-bold text-slate-800 font-mono">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</span>
                                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-bold uppercase {{ $p->status === 'valid' ? 'bg-emerald-100 text-emerald-800' : ($p->status === 'pending' ? 'bg-sky-100 text-sky-800' : 'bg-rose-100 text-rose-800') }}">
                                                                {{ $p->status }}
                                                            </span>
                                                        </div>
                                                        <div class="text-[10.5px] text-slate-500 mt-0.5">
                                                            Metode: <span class="font-semibold uppercase">{{ $p->metode }}</span> • {{ \Carbon\Carbon::parse($p->tanggal_bayar)->translatedFormat('d M Y') }}
                                                        </div>
                                                        @if($p->catatan)
                                                            <div class="text-[10px] text-slate-400 italic mt-0.5">{{ $p->catatan }}</div>
                                                        @endif
                                                    </div>
                                                    @if($p->validator)
                                                        <div class="text-right text-[10px] text-slate-400">
                                                            Divalidasi: <span class="font-semibold text-slate-700">{{ $p->validator->name }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-slate-400 italic bg-slate-50 p-3 rounded-xl text-center">Belum ada riwayat pembayaran yang dicatat untuk tagihan ini.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button type="button" @click="detailModalOpen = false; $wire.closeDetail()" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-all cursor-pointer">
                                    Tutup
                                </button>
                            </div>
                        @else
                            <div class="p-6 flex-col space-y-4 animate-pulse">
                                <div class="h-20 bg-slate-100 rounded-xl"></div>
                                <div class="h-24 bg-slate-100 rounded-xl"></div>
                                <div class="h-16 bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL CATAT PEMBAYARAN CEPAT (TERIMA TUNAI / TRANSFER) (ALPINE.JS X-SHOW INSTANT OPEN)
             ==================================================================== --}}
        <div 
            x-show="payModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="payModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="payModalOpen = false; $wire.closePay()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="payModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-md border border-slate-200" 
                    @click.stop
                >
                    <div class="bg-emerald-700 text-white px-5 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold">Catat Pembayaran Iuran</h3>
                                <p class="text-[11px] text-white/80">
                                    {{ $payInvoice ? ($payInvoice->feeType?->nama . ' • ' . $payInvoice->periode_label) : 'Pembayaran Iuran RT 15' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="payModalOpen = false; $wire.closePay()" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Asynchronous Skeleton Loader while fetching payInvoice --}}
                    <div wire:loading.flex wire:target="openPay" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-16 bg-slate-100 rounded-xl"></div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-10 w-full bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openPay">
                        @if($payInvoice)
                            <form wire:submit.prevent="confirmPayment" class="p-5 space-y-3.5 text-xs">
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                    <p class="font-bold text-slate-800">{{ $payInvoice->household?->kepalaKeluarga?->nama ?? '-' }} ({{ $payInvoice->household?->id_kk }})</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Total Tagihan: <span class="font-bold text-emerald-700 font-mono">Rp {{ number_format($payInvoice->jumlah, 0, ',', '.') }}</span></p>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Metode Pembayaran *</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer {{ $pay_metode === 'tunai' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-bold' : 'bg-white border-slate-200 text-slate-600' }}">
                                            <input type="radio" wire:model.live="pay_metode" value="tunai" class="text-emerald-600 focus:ring-emerald-500">
                                            <span>Tunai (Cash)</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer {{ $pay_metode === 'transfer' ? 'bg-blue-50 border-blue-300 text-blue-800 font-bold' : 'bg-white border-slate-200 text-slate-600' }}">
                                            <input type="radio" wire:model.live="pay_metode" value="transfer" class="text-blue-600 focus:ring-blue-500">
                                            <span>Transfer Bank</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Jumlah Diterima (Rp) *</label>
                                        <input type="number" wire:model="pay_jumlah" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:border-emerald-600">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Tanggal Bayar *</label>
                                        <input type="date" wire:model="pay_tanggal" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-emerald-600">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Catatan Kwitansi</label>
                                    <input type="text" wire:model="pay_catatan" placeholder="Keterangan bukti pembayaran..." class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-emerald-600">
                                </div>

                                <div class="pt-2 flex items-center justify-end gap-2">
                                    <button type="button" @click="payModalOpen = false; $wire.closePay()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-2xs cursor-pointer">
                                        Konfirmasi Lunas
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="p-6 flex-col space-y-4 animate-pulse">
                                <div class="h-16 bg-slate-100 rounded-xl"></div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="h-10 bg-slate-100 rounded-xl"></div>
                                    <div class="h-10 bg-slate-100 rounded-xl"></div>
                                </div>
                                <div class="h-10 w-full bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL TERBITKAN TAGIHAN BARU (TUNGGAL / MASSAL) (ALPINE.JS X-SHOW INSTANT OPEN)
             ==================================================================== --}}
        <div 
            x-show="createModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="createModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="createModalOpen = false; $wire.closeCreate()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="createModalOpen"
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
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold">Terbitkan Tagihan Iuran</h3>
                                <p class="text-[11px] text-white/70">Pilih opsi penerbitan tunggal atau massal</p>
                            </div>
                        </div>
                        <button type="button" @click="createModalOpen = false; $wire.closeCreate()" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveInvoice" class="p-5 space-y-3.5 text-xs">
                        {{-- Mode Terbit --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Metode Penerbitan *</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer {{ $create_mode === 'single' ? 'bg-blue-50 border-blue-300 text-[#0B3A85] font-bold' : 'bg-white border-slate-200 text-slate-600' }}">
                                    <input type="radio" wire:model.live="create_mode" value="single" class="text-[#0B3A85] focus:ring-[#0B3A85]">
                                    <span>1 Kartu Keluarga</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer {{ $create_mode === 'all_households' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-bold' : 'bg-white border-slate-200 text-slate-600' }}">
                                    <input type="radio" wire:model.live="create_mode" value="all_households" class="text-emerald-600 focus:ring-emerald-500">
                                    <span>Massal (Semua KK)</span>
                                </label>
                            </div>
                        </div>

                        {{-- Pilih KK jika Single --}}
                        @if($create_mode === 'single')
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Pilih Kartu Keluarga *</label>
                                <select wire:model="create_household_id" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]">
                                    @foreach($households as $hh)
                                        <option value="{{ $hh->id }}">[{{ $hh->id_kk }}] {{ $hh->kepalaKeluarga?->nama ?? 'Tanpa Kepala' }} - {{ $hh->alamat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800">
                                <p class="font-bold">Mode Penerbitan Massal Aktif</p>
                                <p class="text-[10.5px] mt-0.5">Tagihan akan diterbitkan serentak untuk seluruh Kartu Keluarga terdaftar di RT 15 yang belum memiliki tagihan pada periode tersebut.</p>
                            </div>
                        @endif

                        {{-- Jenis Iuran --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Jenis Iuran *</label>
                            <select wire:model="create_fee_type_id" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]">
                                @foreach($feeTypes as $ft)
                                    <option value="{{ $ft->id }}">{{ $ft->nama }} - Rp {{ number_format($ft->nominal, 0, ',', '.') }} ({{ $ft->periode }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Periode Bulan & Tahun --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Periode Bulan *</label>
                                <select wire:model="create_bulan" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}">{{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Periode Tahun *</label>
                                <input type="number" wire:model="create_tahun" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:outline-none focus:border-[#0B3A85]">
                            </div>
                        </div>

                        {{-- Jatuh Tempo --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Tanggal Jatuh Tempo</label>
                            <input type="date" wire:model="create_jatuh_tempo" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85]">
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2">
                            <button type="button" @click="createModalOpen = false; $wire.closeCreate()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0B3A85] hover:bg-[#0B2F6B] text-white text-xs font-bold transition-all shadow-2xs cursor-pointer">
                                Terbitkan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             5. MODAL KONFIRMASI HAPUS TAGIHAN
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="delete-invoice-modal-title" 
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-invoice-modal-title">Hapus Lembar Tagihan?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini akan membatalkan dan menghapus catatan tagihan iuran ini.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Tagihan Terpilih:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteInvoiceInfo"></span>
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
                            <span>Ya, Hapus Tagihan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Real-Time Client Search --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initInvoiceClientSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initInvoiceClientSearch();
        });

        document.addEventListener('livewire:initialized', function() {
            initInvoiceClientSearch();
        });

        function initInvoiceClientSearch() {
            const searchInput = document.getElementById('search-invoice-input');
            const clearBtn = document.getElementById('search-invoice-clear-btn');
            const statusSelect = document.getElementById('filter-invoice-status');
            const feeTypeSelect = document.getElementById('filter-invoice-feetype');
            const resetContainer = document.getElementById('reset-filter-invoice-container');
            const tableBody = document.getElementById('invoice-table-body');
            const hasServerFilter = {{ (request()->anyFilled(['search', 'fee_type']) || (request()->has('status') && request('status') !== 'semua' && request('status') !== '')) ? 'true' : 'false' }};

            if (!searchInput) return;

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const statusVal = statusSelect ? statusSelect.value : 'semua';
                const feeTypeVal = feeTypeSelect ? feeTypeSelect.value : '';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || (statusVal !== 'semua' && statusVal !== '') || feeTypeVal !== '';
                let resetBtn = document.getElementById('btn-reset-filter-invoice');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-invoice';
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
                    const rows = tableBody.querySelectorAll('tr.invoice-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowStatus = row.dataset.status || '';
                        const rowFeeType = row.dataset.feeType || '';

                        const matchTerm = term === '' || content.includes(term);
                        const matchStatus = statusVal === 'semua' || statusVal === '' || rowStatus === statusVal;
                        const matchFeeType = feeTypeVal === '' || rowFeeType === feeTypeVal;

                        if (matchTerm && matchStatus && matchFeeType) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('invoice-client-empty-row');
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
                if (feeTypeSelect) feeTypeSelect.addEventListener('change', applyFilter);

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
                    const btn = e.target.closest('#btn-reset-filter-invoice');
                    if (!btn) return;

                    searchInput.value = '';
                    if (statusSelect) statusSelect.value = 'semua';
                    if (feeTypeSelect) feeTypeSelect.value = '';
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
                                    comp.set('feeTypeFilter', '');
                                }
                            }
                        }
                    }

                    if (hasServerFilter) {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            if (searchInput.value.trim().length > 0 || (statusSelect && statusSelect.value !== 'semua') || (feeTypeSelect && feeTypeSelect.value !== '')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
