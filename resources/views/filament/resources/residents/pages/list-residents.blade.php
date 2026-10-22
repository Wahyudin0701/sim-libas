<x-filament-panels::page>
    <div 
        x-data="{
            detailResidentModalOpen: @entangle('detailResidentModalOpen'),
            modalEditResidentOpen: @entangle('editResidentModalOpen'),
            deleteConfirmModalOpen: false,
            deleteResidentId: null,
            deleteResidentName: '',
            confirmDeleteResident(id, name) {
                this.deleteResidentId = id;
                this.deleteResidentName = name;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteResidentId = null;
                this.deleteResidentName = '';
            },
            executeDeleteResident() {
                if (this.deleteResidentId) {
                    const id = this.deleteResidentId;
                    this.closeDeleteConfirm();
                    this.$wire.deleteResident(id);
                }
            },
        }" 
        @close-edit-resident-modal.window="modalEditResidentOpen = false"
        @open-edit-resident-modal.window="modalEditResidentOpen = true"
        @close-detail-resident-modal.window="detailResidentModalOpen = false"
        @open-detail-resident-modal.window="detailResidentModalOpen = true"
        @keydown.escape.window="closeDeleteConfirm(); modalEditResidentOpen = false; detailResidentModalOpen = false; if ($wire.editResidentModalOpen) $wire.closeEditResident(); if ($wire.detailResidentModalOpen) $wire.closeDetailResident();" 
        class="w-full space-y-4"
    >
        {{-- ====================================================================
             1. HEADER KONTEN (DOMINAN BIRU UTAMA #0B3A85)
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                {{-- Kategori Subtitle (Biru Utama) & Tombol Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>KEPENDUDUKAN RT 15</span>
                    <x-page-guide 
                        title="Data Warga RT 15"
                        category="KEPENDUDUKAN RT 15"
                        description="Modul master data seluruh individu warga RT 15. Digunakan untuk pencatatan NIK, identitas kependudukan, status domisili, nomor kontak, serta hubungan keluarga dalam Kartu Keluarga."
                        :steps="[
                            ['title' => 'Cari & Filter Cepat', 'desc' => 'Gunakan kotak pencarian (Nama, NIK, No. HP) dan dropdown filter Status (Aktif/Pindah/Meninggal) serta Jenis Kelamin.'],
                            ['title' => 'Pendaftaran Warga Baru', 'desc' => 'Klik tombol biru \'+ Tambah Warga\' di pojok kanan atas untuk mengisi formulir pendaftaran warga baru.'],
                            ['title' => 'Aksi Data Lengkap', 'desc' => 'Gunakan tombol pada tabel: \'Lihat\' untuk popup detail, \'Ubah\' untuk memperbarui identitas, dan \'Hapus\' untuk menghapus data.'],
                            ['title' => 'Ekspor Laporan Sensus', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh laporan kependudukan sesuai filter aktif dalam bentuk PDF atau Excel.']
                        ]"
                        role="Sekretaris RT & Ketua RT"
                        :tips="[
                            'Nomor Induk Kependudukan (NIK) wajib 16 digit dan unik untuk setiap individu warga.',
                            'Warga yang pindah domisili atau meninggal dunia disarankan diubah statusnya (bukan langsung dihapus) untuk menjaga rekam jejak kependudukan.',
                            'Data nomor telepon (WhatsApp) warga digunakan untuk notifikasi pengumuman dan surat pengantar.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Data Warga
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Tambah) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="residents" :extraParams="['status' => '$wire.statusFilter', 'gender' => '$wire.genderFilter']" />

                {{-- Tombol Primer: Tambah Warga --}}
                <a 
                    href="{{ \App\Filament\Resources\Residents\ResidentResource::getUrl('create') }}" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Warga</span>
                </a>
            </div>
        </div>

        {{-- ====================================================================
             2. FITUR SEARCH & FILTER REAL-TIME LIVEWIRE
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            {{-- Search Input (Kiri) --}}
            <div class="relative w-full sm:max-w-md">
                <div class="relative flex items-center">
                    {{-- Ikon Kaca Pembesar di Kiri --}}
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#94A3B8]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    {{-- Input Real-Time Search --}}
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari ... " 
                        class="w-full pl-9 pr-9 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    {{-- Tombol Clear Input (Muncul saat ada teks) --}}
                    @if(filled($search))
                        <button 
                            type="button" 
                            wire:click="$set('search', '')" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#94A3B8] hover:text-[#0F1F3D] transition-colors cursor-pointer"
                            title="Hapus pencarian"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Filter & Reset (Kanan) --}}
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 sm:justify-end shrink-0 w-full sm:w-auto">
                {{-- Filter Dropdown: Status Kependudukan --}}
                <div class="w-full sm:w-44">
                    <select 
                        wire:model.live="statusFilter" 
                        class="w-full py-2 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium"
                    >
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="pindah">Pindah</option>
                        <option value="meninggal">Meninggal</option>
                        <option value="lahir_baru">Lahir Baru</option>
                    </select>
                </div>

                {{-- Filter Dropdown: Jenis Kelamin --}}
                <div class="w-full sm:w-44">
                    <select 
                        wire:model.live="genderFilter" 
                        class="w-full py-2 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium"
                    >
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>

                {{-- Tombol Reset Filter jika ada filter aktif --}}
                @if(filled($search) || (filled($statusFilter) && $statusFilter !== 'semua') || (filled($genderFilter) && $genderFilter !== 'semua'))
                    <div class="shrink-0 flex items-center">
                        <button 
                            type="button" 
                            wire:click="resetFilters" 
                            class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold transition-all cursor-pointer whitespace-nowrap shadow-2xs w-full sm:w-auto"
                            title="Reset semua filter pencarian"
                        >
                            <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="text-red-600 font-bold">Reset Filter</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- ====================================================================
             3. TABEL DATA WARGA (HEADER DEEP NAVY SESUAI HALAMAN KK)
             ==================================================================== --}}
        <div class="bg-white rounded-none md:rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="statusFilter, genderFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                text="Memperbarui data kependudukan..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-full md:min-w-[1120px] text-left border-collapse">
                    {{-- Header Tabel Deep Navy --}}
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="hidden md:table-cell py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-2.5 sm:px-3 text-center whitespace-nowrap w-24 md:w-auto md:min-w-[100px]">ID WARGA</th>
                            <th class="py-2.5 px-3 whitespace-nowrap md:min-w-[200px]">NAMA LENGKAP</th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[140px] whitespace-nowrap">KARTU KELUARGA</th>
                            <th class="hidden md:table-cell py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">L/P</th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[195px] whitespace-nowrap">NO. TELEPON / WA</th>
                            <th class="hidden md:table-cell py-2.5 px-3 text-center min-w-[90px] whitespace-nowrap">STATUS</th>
                            <th class="py-2.5 px-2 sm:px-3 text-center whitespace-nowrap w-28 md:w-auto md:min-w-[80px]">AKSI</th>
                        </tr>
                    </thead>

                    {{-- Isi Tabel Data --}}
                    <tbody 
                        id="resident-table-body" 
                        class="divide-y divide-[#F1F5F9] text-xs transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="statusFilter, genderFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                    >
                        @forelse($residents as $resident)
                            <tr class="resident-row hover:bg-[#F8FAFC] transition-colors {{ $resident->is_kepala_keluarga ? 'bg-blue-50/20' : '' }}">
                                {{-- 1. NO (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $residents->firstItem() ? ($residents->firstItem() + $loop->index) : $loop->iteration }}
                                </td>

                                {{-- 2. ID WARGA --}}
                                <td class="py-2.5 px-2.5 sm:px-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded text-[10.5px] sm:text-[11px] font-mono font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF] whitespace-nowrap">
                                        {{ $resident->id_warga ?? '-' }}
                                    </span>
                                </td>

                                {{-- 3. NAMA LENGKAP & NIK --}}
                                <td class="py-2.5 px-3 md:min-w-[200px]">
                                    <div class="font-bold text-xs sm:text-sm text-[#0F1F3D] whitespace-nowrap">
                                        {{ $resident->nama }}
                                    </div>
                                    <div class="text-[10px] sm:text-[10.5px] font-mono text-[#5B6B82] mt-0.5 select-all whitespace-nowrap">
                                        NIK: {{ $resident->masked_nik ?? '-' }}
                                    </div>
                                </td>

                                {{-- 4. KARTU KELUARGA (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-xs whitespace-nowrap min-w-[140px]">
                                    @if($resident->household)
                                        <a 
                                            href="{{ url('/admin/households/' . $resident->household_id) }}" 
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-mono font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs group whitespace-nowrap"
                                            title="Buka Detail Kartu Keluarga"
                                        >
                                            <span>{{ $resident->household->id_kk ?? 'KK' }}</span>
                                            <svg class="w-3 h-3 text-[#0B3A85] group-hover:text-white group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                        {{-- Hidden text agar pencarian real-time tetap bisa mendeteksi nama jalan/RT --}}
                                        <span class="hidden">{{ $resident->household->alamat ?? '' }} {{ $resident->household->rt_rw ?? '' }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10.5px] font-medium bg-slate-100 text-slate-500 border border-slate-200 whitespace-nowrap">
                                            Tanpa KK
                                        </span>
                                    @endif
                                </td>

                                {{-- 5. L/P (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($resident->jenis_kelamin === 'L')
                                        <span class="inline-flex items-center justify-center w-5.5 h-5.5 rounded text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200" title="Laki-laki">
                                            L
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-5.5 h-5.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200" title="Perempuan">
                                            P
                                        </span>
                                    @endif
                                </td>

                                {{-- 6. NO. TELEPON / WA (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-xs whitespace-nowrap min-w-[195px]">
                                    @if($resident->telepon)
                                        @if($resident->wa_url)
                                            <a 
                                                href="{{ $resident->wa_url }}" 
                                                target="_blank" 
                                                rel="noopener noreferrer" 
                                                class="inline-flex items-center gap-1 text-[#1B7A3A] font-bold hover:underline whitespace-nowrap" 
                                                title="Chat WhatsApp ke {{ $resident->nama }} ({{ $resident->telepon }})"
                                            >
                                                <span class="select-all font-mono">{{ $resident->telepon }}</span>
                                                <span class="text-[10px] text-slate-400 font-normal shrink-0">(Chat WA)</span>
                                            </a>
                                        @else
                                            <span class="select-all font-mono font-medium text-slate-700 whitespace-nowrap">{{ $resident->telepon }}</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 font-sans italic">-</span>
                                    @endif
                                </td>

                                {{-- 7. STATUS KEPENDUDUKAN (Hanya Desktop) --}}
                                <td class="hidden md:table-cell py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($resident->status_kependudukan === 'aktif')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 text-slate-700 border border-slate-200 whitespace-nowrap">
                                            {{ ucfirst($resident->status_kependudukan ?? 'Aktif') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- 8. AKSI --}}
                                <td class="py-2.5 px-2 sm:px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1 whitespace-nowrap">
                                        {{-- Tombol Detail Warga (Ikon Mata) --}}
                                        <button 
                                            type="button" 
                                            @click="detailResidentModalOpen = true"
                                            wire:click="openDetailResident({{ $resident->id }})"
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-[#0B3A85]/30 bg-[#EBF3FC] text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white hover:border-[#0B3A85] transition-all shadow-2xs cursor-pointer" 
                                            title="Lihat Detail Warga: {{ $resident->nama }}"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Ubah Data (Ikon Pensil) --}}
                                        <button 
                                            type="button" 
                                            @click="modalEditResidentOpen = true"
                                            wire:click="openEditResident({{ $resident->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Ubah Data Warga: {{ $resident->nama }}"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Tombol Hapus Data (Ikon Sampah) --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDeleteResident({{ $resident->id }}, '{{ addslashes($resident->nama) }}')" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Hapus Data Warga: {{ $resident->nama }}"
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
                                            @if(filled($search) || (filled($statusFilter) && $statusFilter !== 'semua') || (filled($genderFilter) && $genderFilter !== 'semua'))
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            @endif
                                        </svg>
                                    </div>
                                    @if(filled($search) || (filled($statusFilter) && $statusFilter !== 'semua') || (filled($genderFilter) && $genderFilter !== 'semua'))
                                        <p class="font-bold text-[#0F1F3D]">Tidak ada data warga yang cocok.</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Tidak ditemukan data warga dengan kriteria pencarian/filter saat ini.</p>
                                    @else
                                        <p class="font-bold text-[#0F1F3D]">Belum Ada Data Warga</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Mulai tambahkan warga baru menggunakan tombol di atas.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            @if($residents->hasPages())
                <div class="px-4 py-3 border-t border-[#E2E8F0] bg-slate-50/50">
                    {{ $residents->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             4. MODAL POPUP DETAIL WARGA
             ==================================================================== --}}
        @include('filament.resources.residents.components.detail-modal')

        {{-- ====================================================================
             6. MODAL POPUP EDIT DATA WARGA
             ==================================================================== --}}
        @include('filament.resources.residents.components.edit-modal', ['isHouseholdView' => false])

        {{-- ====================================================================
             7. MODAL POPUP KONFIRMASI HAPUS DATA WARGA
             ==================================================================== --}}
        @include('filament.resources.residents.components.delete-modal')
    </div>
</x-filament-panels::page>
