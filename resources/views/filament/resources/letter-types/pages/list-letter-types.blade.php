<x-filament-panels::page>
    <div 
        x-data="{
            letterTypeModalOpen: @entangle('letterTypeModalOpen'),
            deleteConfirmModalOpen: false,
            deleteTypeId: null,
            deleteTypeInfo: '',
            confirmDelete(id, info) {
                this.deleteTypeId = id;
                this.deleteTypeInfo = info;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteTypeId = null;
                this.deleteTypeInfo = '';
            },
            executeDelete() {
                if (this.deleteTypeId) {
                    const id = this.deleteTypeId;
                    this.closeDeleteConfirm();
                    $wire.deleteType(id);
                }
            },
        }" 
        @close-letter-type-modal.window="letterTypeModalOpen = false"
        @open-letter-type-modal.window="letterTypeModalOpen = true"
        @keydown.escape.window="
            closeDeleteConfirm();
            letterTypeModalOpen = false; 
            if ($wire.letterTypeModalOpen) $wire.closeModal();
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
                        title="Master Jenis Surat"
                        category="LAYANAN ADMINISTRASI RT 15"
                        description="Modul konfigurasi master jenis surat pengantar RT 15, mengatur kode singkatan nomor surat, deskripsi kegunaan, dan daftar dokumen persyaratan yang wajib dilampirkan pemohon."
                        :steps="[
                            ['title' => 'Pencarian Jenis Surat', 'desc' => 'Cari berdasarkan nama format surat (misal: Pengantar SKCK, Keterangan Domisili, Keterangan Tidak Mampu, dll).'],
                            ['title' => 'Tambah Format Surat Baru', 'desc' => 'Klik tombol biru \'+ Tambah Format Surat\' untuk mendefinisikan kode surat, nama layanan, dan daftar syarat berkas.'],
                            ['title' => 'Kelola Format & Syarat', 'desc' => 'Gunakan tombol \'Lihat\' untuk cek rincian syarat, \'Ubah\' untuk memperbarui persyaratan, atau \'Hapus\' jika format dinonaktifkan.'],
                            ['title' => 'Ekspor Katalog Surat', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh daftar jenis format dan persyaratan surat dalam bentuk PDF atau Excel.']
                        ]"
                        role="Sekretaris RT"
                        :tips="[
                            'Kode format surat (misal: PM, SKCK, DOM) digunakan untuk membentuk penomoran otomatis surat resmi.',
                            'Persyaratan berkas yang diatur di sini akan otomatis menjadi panduan checklist unggahan warga saat mengajukan surat di portal.',
                            'Format surat yang dinonaktifkan tidak akan lagi dapat dipilih oleh warga di portal pengajuan surat.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Master Jenis Surat
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Tambah) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="letter-types" />

                {{-- Tombol Primer: Tambah Jenis Surat --}}
                <button 
                    type="button" 
                    @click="letterTypeModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Jenis Surat</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. SEARCH REAL-TIME CLIENT-SIDE & SERVER-SIDE (ATURAN STANDAR)
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center sm:justify-between gap-2.5">
            <div class="relative w-full sm:max-w-md">
                <div class="relative flex items-center">
                    {{-- Ikon Kaca Pembesar di Kiri --}}
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#94A3B8]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    {{-- Input dengan atribut name="search" dan value="{{ request('search') }}" sesuai standar --}}
                    <input 
                        type="text" 
                        id="search-types-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama jenis surat, kode singkatan, deskripsi, atau format penomoran..." 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    {{-- Tombol Hapus Input --}}
                    <button 
                        type="button" 
                        id="search-types-clear-btn"
                        style="display: none;"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#94A3B8] hover:text-[#0F1F3D] transition-colors cursor-pointer"
                        title="Hapus pencarian"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Container Tombol Reset Filter: Hanya dirender saat parameter URL aktif secara SSR, atau dinamis via DOM JS --}}
            <div id="reset-filter-types-container" class="shrink-0 flex items-center">
                @if(request()->anyFilled(['search']))
                    <a 
                        href="{{ url()->current() }}" 
                        id="btn-reset-filter-types" 
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
             3. TABEL DATA MASTER JENIS SURAT (HEADER DEEP NAVY #0B3A85)
             ==================================================================== --}}
        <div class="relative bg-white rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden">
            {{-- Standard Loading Indicator --}}
            <x-table-loading-indicator 
                target="search, previousPage, nextPage, gotoPage" 
                text="Memperbarui data format surat..." 
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left border-collapse">
                    {{-- Header Tabel Deep Navy --}}
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-3 text-center min-w-[110px] whitespace-nowrap">KODE</th>
                            <th class="py-2.5 px-3 min-w-[260px] whitespace-nowrap">NAMA JENIS SURAT & DESKRIPSI</th>
                            <th class="py-2.5 px-3 min-w-[240px] whitespace-nowrap">FORMAT PENOMORAN RESMI</th>
                            <th class="py-2.5 px-3 min-w-[220px]">PERSYARATAN BERKAS</th>
                            <th class="py-2.5 px-3 text-center min-w-[90px] whitespace-nowrap">STATUS</th>
                            <th class="py-2.5 px-3 text-center min-w-[100px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>

                    {{-- Isi Tabel Data --}}
                    <tbody 
                        id="lettertype-table-body" 
                        class="divide-y divide-[#F1F5F9] text-xs transition-opacity duration-200"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="search, previousPage, nextPage, gotoPage"
                    >
                        {{-- Row Kosong Real-time Client-Side --}}
                        <tr id="lettertype-client-empty-row" style="display: none;">
                            <td colspan="7" class="py-12 text-center text-sm text-[#5B6B82]">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-[#0F1F3D]">Tidak ada jenis surat yang cocok.</p>
                                <p class="text-xs text-[#94A3B8] mt-0.5">Tidak ditemukan jenis surat dengan kata kunci pencarian tersebut.</p>
                            </td>
                        </tr>

                        @forelse($letterTypes as $type)
                            <tr class="lettertype-data-row hover:bg-[#F8FAFC] transition-colors">
                                {{-- 1. NO --}}
                                <td class="py-2.5 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $letterTypes->firstItem() ? ($letterTypes->firstItem() + $loop->index) : $loop->iteration }}
                                </td>

                                {{-- 2. KODE --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF] whitespace-nowrap">
                                        {{ $type->kode }}
                                    </span>
                                </td>

                                {{-- 3. NAMA & DESKRIPSI --}}
                                <td class="py-2.5 px-3 min-w-[260px]">
                                    <div class="font-bold text-xs sm:text-sm text-[#0F1F3D]">
                                        {{ $type->nama }}
                                    </div>
                                    @if($type->deskripsi)
                                        <div class="text-[11px] text-[#5B6B82] mt-0.5 line-clamp-2 leading-relaxed">
                                            {{ $type->deskripsi }}
                                        </div>
                                    @endif
                                </td>

                                {{-- 4. FORMAT PENOMORAN --}}
                                <td class="py-2.5 px-3 whitespace-nowrap min-w-[240px]">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono bg-slate-50 text-slate-700 border border-slate-200 whitespace-nowrap">
                                        {{ $type->format_nomor ?: '{NO}/SP/RT.15/{ROMAN_MONTH}/{YEAR}' }}
                                    </span>
                                </td>

                                {{-- 5. PERSYARATAN BERKAS --}}
                                <td class="py-2.5 px-3 min-w-[220px]">
                                    @if(is_array($type->persyaratan) && count($type->persyaratan) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($type->persyaratan as $syarat)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-slate-100 text-slate-700 border border-slate-200">
                                                    {{ $syarat }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">-</span>
                                    @endif
                                </td>

                                {{-- 6. STATUS AKTIF (BADGE STATIS INFORMATIF) --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <span 
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold select-none {{ $type->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}"
                                        title="Status: {{ $type->is_active ? 'Aktif (Dapat dipilih warga)' : 'Nonaktif' }}"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full {{ $type->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $type->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </span>
                                </td>

                                {{-- 7. AKSI --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1 whitespace-nowrap">
                                        {{-- Tombol Ubah (Ikon Pensil) --}}
                                        <button 
                                            type="button" 
                                            @click="letterTypeModalOpen = true"
                                            wire:click="openEdit({{ $type->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Ubah Format Jenis Surat"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Hapus (Ikon Sampah) --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $type->id }}, '{{ addslashes($type->nama . ' (' . $type->kode . ')') }}')" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Hapus Jenis Surat"
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
                                        <p class="font-bold text-[#0F1F3D]">Tidak ada jenis surat yang cocok.</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Tidak ditemukan jenis surat dengan kata kunci "{{ $search }}".</p>
                                    @else
                                        <p class="font-bold text-[#0F1F3D]">Belum Ada Master Jenis Surat</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Mulai tambahkan jenis surat pengantar baru menggunakan tombol di atas.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            @if($letterTypes->hasPages())
                <div class="px-4 py-3 border-t border-[#E2E8F0] bg-slate-50/50">
                    {{ $letterTypes->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             4. MODAL POPUP FORM TAMBAH / UBAH MASTER JENIS SURAT
             ==================================================================== --}}
        @include('filament.resources.letter-types.components.form-modal')

        {{-- ====================================================================
             5. MODAL KONFIRMASI HAPUS MASTER JENIS SURAT
             ==================================================================== --}}
        <div 
            x-show="deleteConfirmModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="delete-lettertype-modal-title" 
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
                            <h3 class="text-sm font-extrabold text-[#0F1F3D]" id="delete-lettertype-modal-title">Hapus Jenis Surat?</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tindakan ini akan menghapus template format surat pengantar.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Jenis Surat Terpilih:</span>
                        <span class="font-bold text-[#0F1F3D]" x-text="deleteTypeInfo"></span>
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
                            <span>Ya, Hapus Jenis Surat</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================================
         5. SCRIPT CLIENT-SIDE REAL-TIME MULTI-FIELD SEARCH & DYNAMIC RESET FILTER
         ==================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initTypeClientSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initTypeClientSearch();
        });

        document.addEventListener('livewire:initialized', function() {
            initTypeClientSearch();
        });

        function initTypeClientSearch() {
            const searchInput = document.getElementById('search-types-input');
            const clearBtn = document.getElementById('search-types-clear-btn');
            const resetContainer = document.getElementById('reset-filter-types-container');
            const tableBody = document.getElementById('lettertype-table-body');
            const hasServerFilter = {{ request()->anyFilled(['search']) ? 'true' : 'false' }};

            if (!searchInput) return;

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();

                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                let resetBtn = document.getElementById('btn-reset-filter-types');

                if (term.length > 0) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('button');
                        resetBtn.type = 'button';
                        resetBtn.id = 'btn-reset-filter-types';
                        resetBtn.className = 'inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition-all cursor-pointer whitespace-nowrap shadow-2xs w-full sm:w-auto';
                        
                        const iconSvg = '<svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
                        resetBtn.innerHTML = iconSvg + '<span class="text-red-600 font-bold">' + 'Reset' + ' ' + 'Filter' + '</span>';

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
                    const rows = tableBody.querySelectorAll('tr.lettertype-data-row');
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

                    const emptyRow = document.getElementById('lettertype-client-empty-row');
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
