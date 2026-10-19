<x-filament-panels::page>
    <div 
        x-data="{
            feeTypeModalOpen: @entangle('feeTypeModalOpen'),
            deleteConfirmModalOpen: false,
            deleteFeeTypeId: null,
            deleteFeeTypeName: '',
            confirmDelete(id, name) {
                this.deleteFeeTypeId = id;
                this.deleteFeeTypeName = name;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteFeeTypeId = null;
                this.deleteFeeTypeName = '';
            },
            executeDelete() {
                if (this.deleteFeeTypeId) {
                    const id = this.deleteFeeTypeId;
                    this.closeDeleteConfirm();
                    $wire.deleteFeeType(id);
                }
            },
        }"
        @keydown.escape.window="
            closeDeleteConfirm();
            if (feeTypeModalOpen) { feeTypeModalOpen = false; $wire.closeFormModal(); }
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
                        title="Master Jenis Iuran"
                        category="KEUANGAN RT 15"
                        description="Modul konfigurasi master pos iuran warga RT 15, mengatur nominal tarif wajib bulanan (sampah, poskamling, kas RT) atau iuran insidental (PHBN, pembangunan fasum)."
                        :steps="[
                            ['title' => 'Pencarian & Status Pos', 'desc' => 'Cari pos iuran berdasarkan nama iuran atau periode penarikan (Bulanan/Tahunan/Insidental).'],
                            ['title' => 'Tambah Pos Iuran Baru', 'desc' => 'Klik tombol biru \'+ Tambah Jenis Iuran\' untuk menetapkan nama pos, besaran nominal (Rp), dan periode penagihan.'],
                            ['title' => 'Kelola & Penyesuaian Tarif', 'desc' => 'Gunakan tombol \'Lihat\' untuk info pos, \'Ubah\' untuk penyesuaian tarif hasil musyawarah warga, atau \'Hapus\' jika pos tidak lagi ditarik.'],
                            ['title' => 'Ekspor Daftar Pos Iuran', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh daftar struktur pos iuran resmi RT 15 dalam format PDF atau Excel.']
                        ]"
                        role="Bendahara RT & Ketua RT"
                        :tips="[
                            'Besaran nominal iuran wajib disesuaikan dengan hasil keputusan musyawarah warga RT 15.',
                            'Pos iuran yang dinonaktifkan tidak akan muncul lagi pada opsi penerbitan invoice tagihan bulanan baru.',
                            'Tagihan lama yang sudah terbit menggunakan pos ini tidak akan berubah nominalnya.'
                        ]"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Master Jenis Iuran
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="fee-types" />

                {{-- Tombol Primer: Tambah Jenis Iuran --}}
                <button 
                    type="button" 
                    @click="feeTypeModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#0B2F6B] text-white text-xs font-bold transition-all shadow-2xs hover:shadow-sm cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Jenis Iuran</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. SEARCH BAR & RESET FILTER (STANDAR RESMI)
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center sm:justify-between gap-2.5">
            <div class="relative w-full sm:max-w-md">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#94A3B8]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <input 
                        type="text" 
                        id="search-feetype-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama jenis iuran, deskripsi, nominal, atau periode..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-feetype-clear-btn" 
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

            <div id="reset-filter-feetype-container" class="shrink-0 flex items-center">
                @if(request()->anyFilled(['search']))
                    <a 
                        href="{{ url()->current() }}" 
                        id="btn-reset-filter-feetype" 
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

        {{-- ====================================================================
             3. TABEL DATA MASTER JENIS IURAN (HEADER DEEP NAVY #0B3A85)
             ==================================================================== --}}
        <div class="relative bg-white rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden">
            {{-- Standard Loading Indicator --}}
            <x-table-loading-indicator 
                target="search, previousPage, nextPage, gotoPage" 
                text="Memperbarui data master iuran..." 
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-3 min-w-[260px]">NAMA JENIS IURAN & DESKRIPSI</th>
                            <th class="py-2.5 px-3 text-right min-w-[150px] whitespace-nowrap">BESARAN NOMINAL</th>
                            <th class="py-2.5 px-3 text-center min-w-[120px] whitespace-nowrap">PERIODE</th>
                            <th class="py-2.5 px-3 text-center min-w-[130px] whitespace-nowrap">TAGIHAN TERBIT</th>
                            <th class="py-2.5 px-3 text-center min-w-[100px] whitespace-nowrap">STATUS</th>
                            <th class="py-2.5 px-3 text-center min-w-[100px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>

                    <tbody 
                        id="feetype-table-body" 
                        class="divide-y divide-[#F1F5F9] text-xs transition-opacity duration-200"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="search, previousPage, nextPage, gotoPage"
                    >
                        <tr id="feetype-client-empty-row" style="display: none;">
                            <td colspan="7" class="py-12 text-center text-sm text-[#5B6B82]">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-[#0F1F3D]">Tidak ada jenis iuran yang cocok.</p>
                                <p class="text-xs text-[#94A3B8] mt-0.5">Coba sesuaikan kata kunci pencarian Anda.</p>
                            </td>
                        </tr>

                        @forelse($feeTypes as $ft)
                            <tr class="feetype-data-row hover:bg-[#F8FAFC] transition-colors">
                                {{-- 1. NO --}}
                                <td class="py-2.5 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $feeTypes->firstItem() ? ($feeTypes->firstItem() + $loop->index) : $loop->iteration }}
                                </td>

                                {{-- 2. NAMA & DESKRIPSI --}}
                                <td class="py-2.5 px-3">
                                    <div class="font-bold text-xs text-[#0F1F3D]">
                                        {{ $ft->nama }}
                                    </div>
                                    <div class="text-[10.5px] text-[#5B6B82] mt-0.5 leading-relaxed">
                                        {{ $ft->deskripsi ?? 'Tanpa deskripsi tambahan' }}
                                    </div>
                                </td>

                                {{-- 3. NOMINAL --}}
                                <td class="py-2.5 px-3 text-right whitespace-nowrap font-mono font-bold text-slate-900 text-xs">
                                    Rp {{ number_format($ft->nominal, 0, ',', '.') }}
                                </td>

                                {{-- 4. PERIODE --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 capitalize">
                                        {{ $ft->periode }}
                                    </span>
                                </td>

                                {{-- 5. TAGIHAN TERBIT --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 text-[#0B3A85] border border-blue-200">
                                        {{ $ft->invoices_count }} Tagihan
                                    </span>
                                </td>

                                {{-- 6. STATUS AKTIF (BADGE STATIS INFORMATIF) --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <span 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10.5px] font-bold select-none {{ $ft->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}"
                                        title="Status: {{ $ft->is_active ? 'Aktif' : 'Nonaktif' }}"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full {{ $ft->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $ft->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </span>
                                </td>

                                {{-- 7. AKSI --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1 whitespace-nowrap">
                                        {{-- Tombol Ubah (Pensil Amber Soft Hover) --}}
                                        <button 
                                            type="button" 
                                            @click="feeTypeModalOpen = true"
                                            wire:click="openEdit({{ $ft->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Ubah Data Jenis Iuran"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Hapus (Sampah Rose Soft Hover) --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $ft->id }}, '{{ addslashes($ft->nama) }}')" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Hapus Jenis Iuran"
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
                                <td colspan="7" class="py-12 text-center text-sm text-[#5B6B82]">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    @if(filled($search))
                                        <p class="font-bold text-[#0F1F3D]">Tidak ada jenis iuran yang cocok.</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Tidak ditemukan jenis iuran dengan kata kunci "{{ $search }}".</p>
                                    @else
                                        <p class="font-bold text-[#0F1F3D]">Belum Ada Master Jenis Iuran</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Mulai tambahkan jenis iuran baru menggunakan tombol di atas.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($feeTypes->hasPages())
                <div class="p-3 border-t border-[#E2E8F0] bg-[#F8FAFC]">
                    {{ $feeTypes->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             4. MODAL FORM: TAMBAH / UBAH MASTER JENIS IURAN
             ==================================================================== --}}
        <div 
            x-show="feeTypeModalOpen"
            x-cloak
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="feeTypeModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="feeTypeModalOpen = false; $wire.closeFormModal()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="feeTypeModalOpen"
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
                                <h3 class="text-sm font-bold">
                                    {{ $isEditing ? 'Ubah Data Jenis Iuran' : 'Tambah Jenis Iuran Baru' }}
                                </h3>
                                <p class="text-[11px] text-white/70">Master tarif & pos iuran warga RT 15</p>
                            </div>
                        </div>
                        <button type="button" @click="feeTypeModalOpen = false; $wire.closeFormModal()" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Skeleton Loader saat openEdit --}}
                    <div wire:loading.flex wire:target="openEdit" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                        <div class="h-9 bg-slate-100 rounded-xl w-full"></div>
                        <div class="h-4 bg-slate-200 rounded w-1/4"></div>
                        <div class="h-16 bg-slate-100 rounded-xl w-full"></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-9 bg-slate-100 rounded-xl"></div>
                            <div class="h-9 bg-slate-100 rounded-xl"></div>
                        </div>
                    </div>

                    <form wire:loading.remove wire:target="openEdit" wire:submit.prevent="save" class="p-5 space-y-3.5 text-xs">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Jenis Iuran *</label>
                            <input 
                                type="text" 
                                wire:model="nama" 
                                placeholder="Contoh: Iuran Kebersihan & Pengangkutan Sampah" 
                                class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                            >
                            @error('nama') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Deskripsi / Penjelasan Pos Iuran</label>
                            <textarea 
                                wire:model="deskripsi" 
                                rows="2" 
                                placeholder="Jelaskan tujuan dan alokasi dana iuran ini..." 
                                class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                            ></textarea>
                            @error('deskripsi') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Besaran Nominal (Rp) *</label>
                                <input 
                                    type="number" 
                                    step="any" 
                                    min="0" 
                                    wire:model="nominal" 
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                @error('nominal') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Siklus Periode *</label>
                                <select 
                                    wire:model="periode" 
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                >
                                    <option value="bulanan">Bulanan</option>
                                    <option value="tahunan">Tahunan</option>
                                    <option value="sekali">Sekali Bayar</option>
                                    <option value="kondisional">Kondisional</option>
                                </select>
                                @error('periode') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" id="check-is-active" wire:model="is_active" class="rounded text-[#0B3A85] focus:ring-[#0B3A85]">
                            <label for="check-is-active" class="text-xs font-bold text-slate-700 cursor-pointer">
                                Status Aktif (Siap Diterbitkan pada Tagihan)
                            </label>
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2">
                            <button 
                                type="button" 
                                @click="feeTypeModalOpen = false; $wire.closeFormModal()" 
                                class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-[#0B3A85] hover:bg-[#0B2F6B] text-white text-xs font-bold transition-all shadow-2xs cursor-pointer"
                            >
                                {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Jenis Iuran' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             5. MODAL KONFIRMASI HAPUS JENIS IURAN
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="delete-feetype-modal-title" 
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-feetype-modal-title">Hapus Jenis Iuran?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini akan menghapus master pos iuran dari sistem RT.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Jenis Iuran Terpilih:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteFeeTypeName"></span>
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
                            <span>Ya, Hapus Jenis Iuran</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Real-Time Client Search --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initFeeTypeClientSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initFeeTypeClientSearch();
        });

        document.addEventListener('livewire:initialized', function() {
            initFeeTypeClientSearch();
        });

        function initFeeTypeClientSearch() {
            const searchInput = document.getElementById('search-feetype-input');
            const clearBtn = document.getElementById('search-feetype-clear-btn');
            const resetContainer = document.getElementById('reset-filter-feetype-container');
            const tableBody = document.getElementById('feetype-table-body');
            const hasServerFilter = {{ request()->anyFilled(['search']) ? 'true' : 'false' }};

            if (!searchInput) return;

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                let resetBtn = document.getElementById('btn-reset-filter-feetype');

                if (term.length > 0) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('button');
                        resetBtn.type = 'button';
                        resetBtn.id = 'btn-reset-filter-feetype';
                        resetBtn.className = 'inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition-all cursor-pointer whitespace-nowrap shadow-2xs w-full sm:w-auto';
                        
                        const iconSvg = '<svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
                        resetBtn.innerHTML = iconSvg + '<span class="text-red-600 font-bold">' + ['Reset', 'Filter'].join(' ') + '</span>';

                        resetBtn.addEventListener('click', function() {
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
                            if (!hasServerFilter) {
                                resetBtn.remove();
                            }
                        });

                        resetContainer.appendChild(resetBtn);
                    }
                } else {
                    if (!hasServerFilter && resetBtn) {
                        resetBtn.remove();
                    }
                }

                if (tableBody) {
                    const rows = tableBody.querySelectorAll('tr.feetype-data-row');
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

                    const emptyRow = document.getElementById('feetype-client-empty-row');
                    if (emptyRow) {
                        if (matched === 0 && rows.length > 0) {
                            emptyRow.style.display = '';
                        } else {
                            emptyRow.style.display = 'none';
                        }
                    }
                }
            }

            searchInput.addEventListener('input', applyFilter);

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

            if (searchInput.value.trim().length > 0) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
