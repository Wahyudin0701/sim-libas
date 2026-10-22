<x-filament-panels::page>
    <div 
        x-data="{
            validateModalOpen: @entangle('validateModalOpen'),
            deleteConfirmModalOpen: false,
            deletePaymentId: null,
            deletePaymentInfo: '',
            actionModalOpen: false,
            actionType: 'valid',
            actionPaymentId: null,
            actionPaymentInfo: '',
            actionCatatan: '',
            openActionConfirm(id, info, type) {
                this.actionPaymentId = id;
                this.actionPaymentInfo = info;
                this.actionType = type;
                this.actionCatatan = '';
                this.actionModalOpen = true;
            },
            closeActionConfirm() {
                this.actionModalOpen = false;
                this.actionPaymentId = null;
                this.actionPaymentInfo = '';
                this.actionCatatan = '';
            },
            submitAction() {
                if (this.actionCatatan.trim().length === 0) return;
                const id = this.actionPaymentId;
                const catatan = this.actionCatatan.trim();
                const type = this.actionType;
                this.actionModalOpen = false;
                if (type === 'valid') {
                    $wire.approvePayment(id, catatan);
                } else {
                    $wire.rejectPayment(id, catatan);
                }
            },
            confirmDelete(id, info) {
                this.deletePaymentId = id;
                this.deletePaymentInfo = info;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deletePaymentId = null;
                this.deletePaymentInfo = '';
            },
            executeDelete() {
                if (this.deletePaymentId) {
                    const id = this.deletePaymentId;
                    this.closeDeleteConfirm();
                    $wire.deletePayment(id);
                }
            },
        }"
        @keydown.escape.window="
            if (actionModalOpen) {
                closeActionConfirm();
            } else if (deleteConfirmModalOpen) {
                closeDeleteConfirm();
            } else if (validateModalOpen) {
                validateModalOpen = false;
                $wire.closeValidateModal();
            }
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
                        title="Validasi Pembayaran Iuran"
                        category="KEUANGAN RT 15"
                        description="Modul rekonsiliasi dan verifikasi bukti transfer pembayaran iuran yang diunggah warga, mencocokkan nominal dengan rekening bank RT 15, dan menyetujui pelunasan tagihan."
                        :steps="[
                            ['title' => 'Saring Bukti Masuk', 'desc' => 'Gunakan filter Status Pembayaran (Menunggu Konfirmasi, Diterima, Ditolak) dan metode bayar (Transfer Bank, QRIS, Tunai).'],
                            ['title' => 'Periksa Bukti Transfer', 'desc' => 'Klik tombol aksi \'Lihat\' untuk membuka pratinjau gambar bukti transfer bank/struk yang dikirim warga.'],
                            ['title' => 'Konfirmasi atau Tolak', 'desc' => 'Klik \'Setujui\' jika mutasi bank valid (tagihan otomatis lunas & masuk buku kas), atau \'Tolak\' jika mutasi tidak ditemukan.'],
                            ['title' => 'Ekspor Rekap Pembayaran', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh log riwayat mutasi pembayaran warga dalam format PDF atau Excel.']
                        ]"
                        role="Bendahara RT"
                        :tips="[
                            'Selalu cocokkan nomor referensi transfer dan nominal uang di mutasi m-banking sebelum menekan tombol persetujuan.',
                            'Pembayaran yang disetujui akan otomatis dicatat sebagai transaksi kas masuk di Buku Kas RT.',
                            'Jika pembayaran ditolak, sertakan alasan penolakan yang jelas agar warga dapat mengunggah bukti perbaikan.'
                        ]"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Validasi Pembayaran
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Dropdown Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="payments" :extraParams="['status' => '$wire.statusFilter']" />
            </div>
        </div>

        {{-- ====================================================================
             2. SEARCH BAR & DROPDOWN FILTER & RESET FILTER (STANDAR RESMI)
             - Mobile: Search Bar di atas, filter di bawahnya
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
                        id="search-payment-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari ... " 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-payment-clear-btn" 
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
                {{-- Filter Pilihan Status Validasi (Dropdown Pengganti Tab) --}}
                <div class="w-full sm:w-56">
                    <select 
                        id="filter-payment-status"
                        name="status"
                        wire:model.live="statusFilter" 
                        class="w-full py-2.5 px-2.5 sm:px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                    >
                        <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Pembayaran ({{ $totalPayments }})</option>
                        <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Menunggu Validasi ({{ $countPending }})</option>
                        <option value="valid" {{ $statusFilter === 'valid' ? 'selected' : '' }}>Valid ({{ $countValid }})</option>
                        <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak ({{ $countDitolak }})</option>
                    </select>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-payment-container" class="shrink-0 flex items-center justify-center">
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
                            id="btn-reset-filter-payment" 
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
             4. TABEL VALIDASI PEMBAYARAN (HEADER DEEP NAVY #0B3A85)
             ==================================================================== --}}
        <div class="relative bg-white rounded-none md:rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden">
            {{-- Standard Loading Indicator --}}
            <x-table-loading-indicator 
                target="statusFilter, search, resetFilters, previousPage, nextPage, gotoPage" 
                text="Memperbarui data validasi pembayaran..." 
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-full md:min-w-[1100px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="hidden md:table-cell py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">NO</th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[125px] whitespace-nowrap">TANGGAL BAYAR</th>
                            <th class="py-2.5 px-2.5 sm:px-3 md:min-w-[220px]"><span class="md:hidden">KODE KK</span><span class="hidden md:inline">KARTU KELUARGA / WARGA</span></th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[200px]">TAGIHAN & PERIODE</th>
                            <th class="hidden md:table-cell py-2.5 px-3 text-right min-w-[130px] whitespace-nowrap">JUMLAH (RP)</th>
                            <th class="hidden md:table-cell py-2.5 px-3 text-center min-w-[100px] whitespace-nowrap">METODE</th>
                            <th class="hidden md:table-cell py-2.5 px-3 text-center min-w-[95px] whitespace-nowrap">BUKTI</th>
                            <th class="py-2.5 px-2 sm:px-3 text-center min-w-[85px] md:min-w-[110px] whitespace-nowrap">STATUS</th>
                            <th class="py-2.5 px-2 sm:px-3 text-center w-14 sm:w-20 md:w-auto md:min-w-[110px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>

                    <tbody 
                        id="payment-table-body" 
                        class="divide-y divide-[#F1F5F9] text-xs transition-opacity duration-200"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="statusFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                    >
                        <tr id="payment-client-empty-row" style="display: none;">
                            <td colspan="9" class="py-12 text-center text-sm text-[#5B6B82]">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-[#0F1F3D]">Tidak ada catatan pembayaran yang cocok.</p>
                                <p class="text-xs text-[#94A3B8] mt-0.5">Coba sesuaikan kata kunci pencarian Anda.</p>
                            </td>
                        </tr>

                        @forelse($payments as $p)
                            <tr 
                                class="payment-data-row transition-colors {{ $p->status === 'pending' ? 'bg-amber-50/70 hover:bg-amber-100/70' : 'hover:bg-[#F8FAFC]' }}"
                                data-status="{{ $p->status }}"
                            >
                                {{-- 1. NO (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap {{ $p->status === 'pending' ? 'border-l-4 border-l-amber-500' : '' }}">
                                    {{ $payments->firstItem() ? ($payments->firstItem() + $loop->index) : $loop->iteration }}
                                </td>

                                {{-- 2. TANGGAL BAYAR (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 whitespace-nowrap">
                                    <div class="font-bold text-xs text-[#0F1F3D]">
                                        {{ \Carbon\Carbon::parse($p->tanggal_bayar)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        {{ $p->created_at?->diffForHumans() }}
                                    </div>
                                </td>

                                {{-- 3. KARTU KELUARGA / KODE KK (Mobile & Desktop) --}}
                                <td class="py-2.5 px-2.5 sm:px-3 {{ $p->status === 'pending' ? 'border-l-4 border-l-amber-500 md:border-l-0' : '' }}">
                                    <div class="flex items-center gap-1.5 flex-wrap cursor-pointer hover:text-[#0B3A85]" @click="validateModalOpen = true" wire:click="openDetail({{ $p->id }})" title="Klik untuk melihat detail pembayaran">
                                        <span class="font-mono text-[11px] font-bold text-[#0B3A85] bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200 shrink-0">
                                            {{ $p->invoice?->household?->id_kk ?? '-' }}
                                        </span>
                                        <span class="font-bold text-xs text-[#0F1F3D] hover:underline">
                                            {{ $p->invoice?->household?->kepalaKeluarga?->nama ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="hidden md:block text-[10.5px] text-[#5B6B82] mt-0.5 line-clamp-1">
                                        {{ $p->invoice?->household?->alamat ?? '-' }}
                                    </div>
                                </td>

                                {{-- 4. TAGIHAN & PERIODE (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3">
                                    <div class="font-semibold text-xs text-slate-800">
                                        {{ $p->invoice?->feeType?->nama ?? '-' }}
                                    </div>
                                    <div class="text-[10.5px] text-slate-500 mt-0.5">
                                        Periode: <span class="font-medium text-slate-700">{{ $p->invoice?->periode_label ?? '-' }}</span>
                                    </div>
                                </td>

                                {{-- 5. JUMLAH BAYAR (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-right whitespace-nowrap font-mono font-bold text-slate-900">
                                    Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                                </td>

                                {{-- 6. METODE (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($p->metode === 'transfer')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase">
                                            Transfer
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                            Tunai
                                        </span>
                                    @endif
                                </td>

                                {{-- 7. BUKTI TRANSFER (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($p->bukti_path)
                                        <a 
                                            href="{{ \Illuminate\Support\Facades\Storage::url($p->bukti_path) }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer" 
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-blue-50 text-[#0B3A85] border border-blue-200 hover:bg-blue-100 transition-colors"
                                            title="Lihat lampiran bukti transfer"
                                        >
                                            <svg class="w-3 h-3 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            <span>Foto</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-slate-400 font-sans italic">-</span>
                                    @endif
                                </td>

                                {{-- 8. STATUS VALIDASI (Mobile & Desktop) --}}
                                <td class="py-2.5 px-2 sm:px-3 text-center whitespace-nowrap">
                                    @if($p->status === 'valid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                            Valid
                                        </span>
                                    @elseif($p->status === 'ditolak')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 sm:px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-100 text-amber-800 border border-amber-200 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                {{-- 9. AKSI (Mobile & Desktop) --}}
                                <td class="py-2.5 px-2 sm:px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1 whitespace-nowrap">
                                        <button 
                                            type="button" 
                                            @click="validateModalOpen = true"
                                            wire:click="openDetail({{ $p->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer" 
                                            title="Lihat Detail & Validasi Pembayaran"
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
                            <tr>
                                <td colspan="9" class="py-12 text-center text-sm text-[#5B6B82]">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    @if(filled($search))
                                        <p class="font-bold text-[#0F1F3D]">Tidak ada catatan pembayaran yang cocok.</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Tidak ditemukan data dengan kata kunci "{{ $search }}".</p>
                                    @else
                                        <p class="font-bold text-[#0F1F3D]">Belum Ada Catatan Pembayaran</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Pembayaran yang masuk dari warga akan terdaftar di halaman ini.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="p-3 border-t border-[#E2E8F0] bg-[#F8FAFC]">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL DETAIL PEMBAYARAN (ALPINE.JS X-SHOW INSTANT OPEN)
             ==================================================================== --}}
        <div 
            x-show="validateModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="validateModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="validateModalOpen = false; $wire.closeValidateModal()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="validateModalOpen"
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
                                <h3 class="text-sm font-bold">
                                    Rincian Pembayaran Iuran
                                </h3>
                                <p class="text-[11px] text-white/80">
                                    {{ $selectedPayment ? 'ID Pembayaran #' . $selectedPayment->id . ' • ' . ($selectedPayment->invoice?->feeType?->nama ?? 'Iuran') : 'Validasi Pembayaran RT 15' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="validateModalOpen = false; $wire.closeValidateModal()" class="text-white/70 hover:text-white transition-colors cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Skeleton Loader while fetching selectedPayment --}}
                    <div wire:loading.flex wire:target="openDetail, openApprove, openReject" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-28 bg-slate-100 rounded-xl"></div>
                        <div class="h-28 bg-slate-100 rounded-xl"></div>
                        <div class="h-14 bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openDetail, openApprove, openReject">
                        @if($selectedPayment)
                            <div class="p-5 space-y-4 text-xs">
                                {{-- Status Banner --}}
                                <div class="flex items-center justify-between p-3 rounded-xl {{ $selectedPayment->status === 'valid' ? 'bg-emerald-50 border border-emerald-200' : ($selectedPayment->status === 'ditolak' ? 'bg-rose-50 border border-rose-200' : 'bg-amber-50 border border-amber-200') }}">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] font-bold text-slate-600">Status:</span>
                                        @if($selectedPayment->status === 'valid')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Valid (Lunas)
                                            </span>
                                        @elseif($selectedPayment->status === 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Menunggu Validasi Pengurus
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] font-mono text-slate-500">
                                        #{{ $selectedPayment->id }}
                                    </div>
                                </div>

                                {{-- Ringkasan Rincian --}}
                                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Warga / Kepala KK:</span>
                                        <span class="font-bold text-slate-900 text-right">
                                            {{ $selectedPayment->invoice?->household?->kepalaKeluarga?->nama ?? '-' }}
                                            <span class="font-mono text-[#0B3A85]">({{ $selectedPayment->invoice?->household?->id_kk ?? '-' }})</span>
                                        </span>
                                    </div>
                                    @if($selectedPayment->invoice?->household?->alamat)
                                        <div class="flex items-center justify-between text-[11px]">
                                            <span class="text-slate-500">Alamat:</span>
                                            <span class="text-slate-700 text-right">{{ $selectedPayment->invoice?->household?->alamat }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Tagihan Iuran:</span>
                                        <span class="font-semibold text-slate-800 text-right">{{ $selectedPayment->invoice?->feeType?->nama ?? 'Iuran' }} ({{ $selectedPayment->invoice?->periode_label ?? '-' }})</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Jumlah Bayar:</span>
                                        <span class="font-mono font-bold text-base text-emerald-700">Rp {{ number_format($selectedPayment->jumlah, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Metode Bayar:</span>
                                        <span class="font-bold uppercase text-slate-800">{{ $selectedPayment->metode }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Tanggal Bayar:</span>
                                        <span class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($selectedPayment->tanggal_bayar)->translatedFormat('d F Y') }}</span>
                                    </div>
                                </div>

                                {{-- Lampiran Bukti Foto Transfer jika ada --}}
                                @if($selectedPayment->bukti_path)
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[11px] font-bold text-slate-700">Foto Bukti Transfer:</span>
                                            <a 
                                                href="{{ \Illuminate\Support\Facades\Storage::url($selectedPayment->bukti_path) }}" 
                                                target="_blank" 
                                                rel="noopener noreferrer" 
                                                class="text-[11px] font-semibold text-[#0B3A85] hover:underline inline-flex items-center gap-1"
                                            >
                                                <span>Buka Foto Asli</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-100 flex items-center justify-center max-h-56">
                                            <img 
                                                src="{{ \Illuminate\Support\Facades\Storage::url($selectedPayment->bukti_path) }}" 
                                                alt="Bukti Transfer"
                                                class="w-full h-auto object-contain max-h-56"
                                            >
                                        </div>
                                    </div>
                                @endif

                                {{-- Catatan dari Warga jika ada --}}
                                @if($selectedPayment->catatan)
                                    <div>
                                        <span class="text-[11px] font-bold text-slate-700 block mb-1">Catatan dari Warga:</span>
                                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-xs leading-relaxed">
                                            {{ $selectedPayment->catatan }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Catatan Validasi Pengurus Tersimpan jika status 'valid' atau 'ditolak' --}}
                                @if(in_array($selectedPayment->status, ['valid', 'ditolak']) && $selectedPayment->catatan_validasi)
                                    <div class="p-3.5 rounded-xl {{ $selectedPayment->status === 'valid' ? 'bg-emerald-50/70 border border-emerald-200' : 'bg-rose-50/70 border border-rose-200' }}">
                                        <div class="text-[11px] font-bold {{ $selectedPayment->status === 'valid' ? 'text-emerald-800' : 'text-rose-800' }} uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            @if($selectedPayment->status === 'valid')
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Catatan Validasi Pengurus</span>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                <span>Alasan / Catatan Penolakan</span>
                                            @endif
                                        </div>
                                        <p class="text-xs {{ $selectedPayment->status === 'valid' ? 'text-emerald-900' : 'text-rose-900' }} leading-relaxed">
                                            {{ $selectedPayment->catatan_validasi }}
                                        </p>
                                        <div class="text-[10.5px] {{ $selectedPayment->status === 'valid' ? 'text-emerald-700' : 'text-rose-700' }} font-medium mt-2 flex items-center justify-between">
                                            <span>Oleh: {{ $selectedPayment->validator?->name ?? 'Pengurus RT' }}</span>
                                            @if($selectedPayment->tanggal_validasi)
                                                <span>{{ \Carbon\Carbon::parse($selectedPayment->tanggal_validasi)->format('d M Y, H:i') }} WIB</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Modal Footer --}}
                            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap">
                                <div>
                                    @if($selectedPayment->status === 'pending')
                                        {{-- Tombol Setujui dan Tolak hanya untuk status pending --}}
                                        <div class="flex items-center gap-2">
                                            <button 
                                                type="button" 
                                                @click="openActionConfirm({{ $selectedPayment->id }}, '{{ addslashes(($selectedPayment->invoice?->household?->kepalaKeluarga?->nama ?? 'KK') . ' - ' . ($selectedPayment->invoice?->feeType?->nama ?? 'Iuran') . ' (Rp ' . number_format($selectedPayment->jumlah, 0, ',', '.') . ')') }}', 'valid')"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-all shadow-xs hover:shadow-md cursor-pointer"
                                                title="Setujui pembayaran dan nyatakan tagihan lunas"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Setujui</span>
                                            </button>

                                            <button 
                                                type="button" 
                                                @click="openActionConfirm({{ $selectedPayment->id }}, '{{ addslashes(($selectedPayment->invoice?->household?->kepalaKeluarga?->nama ?? 'KK') . ' - ' . ($selectedPayment->invoice?->feeType?->nama ?? 'Iuran') . ' (Rp ' . number_format($selectedPayment->jumlah, 0, ',', '.') . ')') }}', 'ditolak')"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white transition-all shadow-xs hover:shadow-md cursor-pointer"
                                                title="Tolak bukti pembayaran ini"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                <span>Tolak</span>
                                            </button>
                                        </div>
                                    @elseif(in_array($selectedPayment->status, ['valid', 'ditolak']))
                                        {{-- Tombol Hapus hanya untuk status final (valid atau ditolak) --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $selectedPayment->id }}, '{{ addslashes(($selectedPayment->invoice?->household?->kepalaKeluarga?->nama ?? 'KK') . ' - ' . ($selectedPayment->invoice?->feeType?->nama ?? 'Iuran') . ' (Rp ' . number_format($selectedPayment->jumlah, 0, ',', '.') . ')') }}')"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition-colors cursor-pointer"
                                            title="Hapus rekaman pembayaran ini"
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
                                    @click="validateModalOpen = false; $wire.closeValidateModal()" 
                                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer"
                                >
                                    Tutup
                                </button>
                            </div>
                        @else
                            <div class="p-6 flex-col space-y-4 animate-pulse">
                                <div class="h-28 bg-slate-100 rounded-xl"></div>
                                <div class="h-28 bg-slate-100 rounded-xl"></div>
                                <div class="h-14 bg-slate-100 rounded-xl"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL KONFIRMASI VALIDASI / TOLAK PEMBAYARAN (ALPINE.JS Z-[60])
             ==================================================================== --}}
        <div 
            x-show="actionModalOpen" 
            x-cloak 
            style="display: none;"
            class="fixed inset-0 z-[60] overflow-y-auto" 
            aria-labelledby="action-payment-modal-title" 
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
                            :class="actionType === 'valid' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'"
                        >
                            {{-- Icon Setujui --}}
                            <svg x-show="actionType === 'valid'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{-- Icon Tolak --}}
                            <svg x-show="actionType === 'ditolak'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 
                                class="text-sm sm:text-base font-extrabold text-[#0F1F3D]" 
                                id="action-payment-modal-title"
                                x-text="actionType === 'valid' ? 'Konfirmasi Persetujuan Pembayaran' : 'Konfirmasi Penolakan Pembayaran'"
                            ></h3>
                            <p 
                                class="text-xs text-slate-500 mt-0.5 leading-relaxed"
                                x-text="actionType === 'valid' ? 'Pastikan dana telah masuk ke rekening kas RT 15 sebelum menyetujui pembayaran ini. Tagihan warga akan otomatis dinyatakan lunas.' : 'Pembayaran tidak dapat diverifikasi atau tidak valid. Berikan penjelasan kepada warga pelapor.'"
                            ></p>
                        </div>
                    </div>

                    {{-- Info Pembayaran Terpilih --}}
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Pembayaran Terpilih</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="actionPaymentInfo"></span>
                    </div>

                    {{-- Textarea Catatan / Respon Pengurus --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-700">
                                <span x-text="actionType === 'valid' ? 'Catatan Validasi / Respon Pengurus' : 'Alasan Penolakan Pembayaran'"></span>
                                <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <span class="text-[10.5px] font-semibold text-rose-500">Wajib diisi</span>
                        </div>
                        <textarea 
                            x-model="actionCatatan" 
                            rows="3" 
                            :placeholder="actionType === 'valid' ? 'Tuliskan catatan validasi (misal: Bukti pembayaran valid dan dana telah diterima kas RT)...' : 'Tuliskan alasan penolakan (misal: Bukti transfer tidak valid atau dana belum masuk rekening RT)...'"
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
                                : (actionType === 'valid' ? 'bg-emerald-600 hover:bg-emerald-700 text-white cursor-pointer hover:shadow-md' : 'bg-rose-600 hover:bg-rose-700 text-white cursor-pointer hover:shadow-md')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs"
                        >
                            <span x-show="actionType === 'valid'" class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Konfirmasi Setujui</span>
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

        {{-- ====================================================================
             7. MODAL KONFIRMASI HAPUS CATATAN PEMBAYARAN (ALPINE.JS Z-[60])
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
            x-cloak
            style="display: none;"
            class="fixed inset-0 z-[60] overflow-y-auto" 
            aria-labelledby="delete-payment-modal-title" 
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-payment-modal-title">Hapus Catatan Pembayaran?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini akan menghapus riwayat pembayaran dan status tagihan terkait akan disesuaikan.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Pembayaran Terpilih:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deletePaymentInfo"></span>
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
                            <span>Ya, Hapus Pembayaran</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Real-Time Client Search --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initPaymentClientSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initPaymentClientSearch();
        });

        document.addEventListener('livewire:initialized', function() {
            initPaymentClientSearch();
        });

        function initPaymentClientSearch() {
            const searchInput = document.getElementById('search-payment-input');
            const clearBtn = document.getElementById('search-payment-clear-btn');
            const statusSelect = document.getElementById('filter-payment-status');
            const resetContainer = document.getElementById('reset-filter-payment-container');
            const tableBody = document.getElementById('payment-table-body');
            const hasServerFilter = {{ (request()->filled('search') || (request()->has('status') && request('status') !== 'semua' && request('status') !== '')) ? 'true' : 'false' }};

            if (!searchInput) return;

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const statusVal = statusSelect ? statusSelect.value : 'semua';

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                const hasActiveFilter = term.length > 0 || (statusVal !== 'semua' && statusVal !== '');
                let resetBtn = document.getElementById('btn-reset-filter-payment');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-payment';
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
                    const rows = tableBody.querySelectorAll('tr.payment-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowStatus = row.dataset.status || '';

                        const matchTerm = term === '' || content.includes(term);
                        const matchStatus = statusVal === 'semua' || statusVal === '' || rowStatus === statusVal;

                        if (matchTerm && matchStatus) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('payment-client-empty-row');
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
                    const btn = e.target.closest('#btn-reset-filter-payment');
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
