<x-filament-panels::page>
    <div class="w-full space-y-4">
        {{-- ====================================================================
             1. HEADER KONTEN (DOMINAN BIRU UTAMA #0B3A85)
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                {{-- Kategori Subtitle (Biru Utama) & Tombol Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>KEPENDUDUKAN RT 15</span>
                    <x-page-guide 
                        title="Kartu Keluarga (KK)"
                        category="KEPENDUDUKAN RT 15"
                        description="Modul pengelolaan data Kartu Keluarga (KK) RT 15 untuk memantau kepala keluarga, susunan anggota keluarga, status tempat tinggal, dan validitas nomor KK resmi."
                        :steps="[
                            ['title' => 'Cari & Filter Data', 'desc' => 'Gunakan kolom pencarian untuk mencari Nomor KK, ID KK, nama Kepala Keluarga, atau alamat rumah.'],
                            ['title' => 'Tambah Kartu Keluarga', 'desc' => 'Klik tombol biru \'+ Tambah KK\' di kanan atas untuk mendaftarkan data keluarga baru ke sistem.'],
                            ['title' => 'Detail & Kelola Anggota', 'desc' => 'Klik tombol biru \'Lihat\' pada tabel untuk membuka rincian KK, menambah anggota keluarga, atau mengunggah scan KK.'],
                            ['title' => 'Ekspor Laporan', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh daftar KK ke dalam berkas PDF cetak atau format Excel spreadsheet.']
                        ]"
                        role="Sekretaris RT & Ketua RT"
                        :tips="[
                            'Nomor Kartu Keluarga wajib terdiri dari 16 digit angka resmi dari Disdukcapil.',
                            'Setiap Kartu Keluarga wajib memiliki satu Kepala Keluarga yang berstatus aktif.',
                            'Mengubah atau menghapus data KK akan mempengaruhi relasi data warga yang terdaftar di dalamnya.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Kartu Keluarga (KK)
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Tambah) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Dropdown: Ekspor Data (PDF & Excel) --}}
                <div x-data="{ exportOpen: false }" @click.outside="exportOpen = false" class="relative inline-block text-left">
                    <button 
                        type="button" 
                        @click="exportOpen = !exportOpen" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#0F1F3D] border border-[#CBD5E1] hover:bg-[#F8FAFC] hover:border-[#0B3A85] hover:text-[#0B3A85] transition-all duration-150 text-xs font-bold shadow-2xs hover:shadow-xs cursor-pointer"
                        aria-haspopup="true"
                        :aria-expanded="exportOpen"
                    >
                        <svg class="w-3.5 h-3.5 text-[#5B6B82]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Ekspor Data</span>
                        <svg class="w-3 h-3 text-[#5B6B82] transition-transform duration-200" :class="{ 'rotate-180': exportOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Menu Dropdown Pilihan Format --}}
                    <div 
                        x-show="exportOpen" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-200 ring-1 ring-black/5 p-1.5 z-50 focus:outline-none divide-y divide-slate-100"
                        style="display: none;"
                    >
                        <div class="p-1 space-y-1">
                            {{-- 1. Format PDF (Preview di tab baru) --}}
                            <a 
                                :href="'{{ route('admin.households.export.pdf') }}?search=' + encodeURIComponent($wire.search || '') + '&anggota=' + encodeURIComponent($wire.anggotaFilter || '')" 
                                target="_blank"
                                @click="exportOpen = false"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition-colors group cursor-pointer"
                            >
                                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="text-xs font-bold text-[#0F1F3D] group-hover:text-rose-600">Format PDF</div>
                                    <div class="text-[10px] text-slate-400">Preview & cetak tab baru</div>
                                </div>
                            </a>

                            {{-- 2. Format Excel (.xlsx) (Unduh langsung / Save As) --}}
                            <a 
                                :href="'{{ route('admin.households.export.excel') }}?search=' + encodeURIComponent($wire.search || '') + '&anggota=' + encodeURIComponent($wire.anggotaFilter || '')" 
                                @click="exportOpen = false"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition-colors group cursor-pointer"
                            >
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-200 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="text-xs font-bold text-[#0F1F3D] group-hover:text-emerald-600">Format Excel (.xlsx)</div>
                                    <div class="text-[10px] text-slate-400">Download file spreadsheet</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tombol Primer: Tambah Kartu Keluarga (Halaman Khusus) --}}
                <a 
                    href="{{ \App\Filament\Resources\Households\HouseholdResource::getUrl('create') }}" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Kartu Keluarga</span>
                </a>
            </div>
        </div>

        {{-- ====================================================================
             3. FITUR SEARCH REAL-TIME LIVEWIRE & FILTER JUMLAH ANGGOTA
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center sm:justify-between gap-2.5">
            {{-- Search Bar (Kiri) --}}
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
                        placeholder="Cari ID KK, nomor KK, nama kepala keluarga, no telepon, atau alamat rumah..." 
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
            <div class="flex items-center gap-2.5 sm:justify-end shrink-0">
                {{-- Filter Dropdown: Jumlah Anggota --}}
                <div class="w-full sm:w-56">
                    <div class="relative flex items-center">
                        <select 
                            wire:model.live="anggotaFilter" 
                            class="w-full py-2 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium"
                        >
                            <option value="">Semua Jumlah Anggota</option>
                            <option value="1">1 Jiwa (Tunggal)</option>
                            <option value="2">2 Jiwa</option>
                            <option value="3">3 Jiwa</option>
                            <option value="4">4 Jiwa</option>
                            <option value="5">5 Jiwa</option>
                            <option value="6+">6 Jiwa atau Lebih</option>
                            <option value="0">0 Jiwa (Kosong)</option>
                        </select>
                    </div>
                </div>

                {{-- Tombol Reset Filter jika ada filter aktif --}}
                @if(filled($search) || filled($anggotaFilter))
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
             4. TABEL DATA KARTU KELUARGA (HEADER DEEP NAVY SESUAI REFERENSI)
             ==================================================================== --}}
        <div class="bg-white rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="anggotaFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                text="Memperbarui data kartu keluarga..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1290px] text-left border-collapse">
                    {{-- Header Tabel Deep Navy --}}
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-3 text-center min-w-[130px] whitespace-nowrap">ID KARTU KELUARGA</th>
                            <th class="py-2.5 px-3 min-w-[170px] whitespace-nowrap">NOMOR KK</th>
                            <th class="py-2.5 px-3 min-w-[200px] whitespace-nowrap">KEPALA KELUARGA</th>
                            <th class="py-2.5 px-3 min-w-[195px] whitespace-nowrap">NO. TELEPON / WA</th>
                            <th class="py-2.5 px-3 min-w-[240px] whitespace-nowrap">ALAMAT RUMAH</th>
                            <th class="py-2.5 px-3 text-center min-w-[100px] whitespace-nowrap">FOTO KK</th>
                            <th class="py-2.5 px-3 text-center min-w-[130px] whitespace-nowrap">JUMLAH ANGGOTA</th>
                            <th class="py-2.5 px-3 text-center min-w-[90px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>

                    {{-- Isi Tabel Data --}}
                    <tbody 
                        id="household-table-body" 
                        class="divide-y divide-[#F1F5F9] text-xs transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="anggotaFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                    >
                        @forelse($households as $household)
                            @php
                                $kepala = $household->kepalaKeluarga ?? $household->residents->firstWhere('is_kepala_keluarga', true) ?? $household->residents->first();
                                $cleanPhone = preg_replace('/[^0-9]/', '', $kepala?->telepon ?? '');
                                $waNumber = !empty($cleanPhone) ? (str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : $cleanPhone) : '';
                            @endphp
                            <tr class="household-row hover:bg-[#F8FAFC] transition-colors">
                                {{-- 1. NO --}}
                                <td class="py-2.5 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $households->firstItem() ? ($households->firstItem() + $loop->index) : $loop->iteration }}
                                </td>

                                {{-- 2. ID KARTU KELUARGA --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF] whitespace-nowrap">
                                        {{ $household->id_kk ?? 'KK' . sprintf('%03d', $household->id) }}
                                    </span>
                                </td>

                                {{-- 3. NOMOR KK --}}
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <span class="font-mono text-xs font-bold text-[#0F1F3D] tracking-wider select-all whitespace-nowrap">
                                        {{ $household->masked_no_kk }}
                                    </span>
                                </td>

                                {{-- 4. KEPALA KELUARGA ([nama] [NIK : ...]) --}}
                                <td class="py-2.5 px-3 min-w-[200px]">
                                    <div class="font-bold text-xs sm:text-sm text-[#0F1F3D] flex items-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 text-[#0B3A85] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="whitespace-nowrap">{{ $kepala?->nama ?? '- Belum Ditentukan -' }}</span>
                                    </div>
                                    <div class="text-[10.5px] font-mono text-[#5B6B82] mt-0.5 ml-5 select-all whitespace-nowrap">
                                        NIK: {{ $kepala?->masked_nik ?? '-' }}
                                    </div>
                                </td>

                                {{-- 5. NO. TELEPON / WA --}}
                                <td class="py-2.5 px-3 text-xs whitespace-nowrap min-w-[195px]">
                                    @if($kepala?->telepon)
                                        @if(!empty($waNumber))
                                            <a 
                                                href="https://wa.me/{{ $waNumber }}" 
                                                target="_blank" 
                                                rel="noopener noreferrer" 
                                                class="inline-flex items-center gap-1 text-[#1B7A3A] font-bold hover:underline whitespace-nowrap" 
                                                title="Chat WhatsApp ke {{ $kepala->nama }} ({{ $kepala->telepon }})"
                                            >
                                                <span class="select-all font-mono">{{ $kepala->telepon }}</span>
                                                <span class="text-[10px] text-slate-400 font-normal shrink-0">(Chat WA)</span>
                                            </a>
                                        @else
                                            <span class="select-all font-mono font-medium text-slate-700 whitespace-nowrap">{{ $kepala->telepon }}</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 font-sans italic">-</span>
                                    @endif
                                </td>

                                {{-- 6. ALAMAT RUMAH --}}
                                <td class="py-2.5 px-3 text-xs text-[#5B6B82] min-w-[240px]">
                                    <div class="font-medium text-[#0F1F3D] leading-snug">{{ $household->alamat ?? '-' }}</div>
                                    <div class="text-[10.5px] text-slate-400 mt-0.5 whitespace-nowrap">{{ $household->rt_rw ?? 'RT 15 / RW 04' }}</div>
                                </td>

                                {{-- 7. FOTO KK --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($household->foto_kk_url)
                                        <a 
                                            href="{{ $household->foto_kk_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer" 
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 hover:border-emerald-300 transition-all shadow-2xs whitespace-nowrap"
                                            title="Buka Foto Fisik Kartu Keluarga"
                                        >
                                            <img src="{{ $household->foto_kk_url }}" alt="Foto KK" class="w-4.5 h-3 object-cover rounded shrink-0 border border-emerald-300" />
                                            <span class="text-[11px] font-bold">Ada</span>
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-[10.5px] font-medium bg-slate-100 text-slate-400 border border-slate-200 whitespace-nowrap" title="Foto fisik KK belum diunggah">
                                            Belum Ada
                                        </span>
                                    @endif
                                </td>

                                {{-- 8. JUMLAH ANGGOTA --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <a 
                                        href="{{ url('/admin/households/' . $household->id) }}" 
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF] hover:bg-[#DBEAFE] transition-all shadow-2xs hover:scale-105 whitespace-nowrap" 
                                        title="Buka Halaman Kartu Keluarga ({{ $household->residents_count }} Anggota Terdaftar)"
                                    >
                                        <span>{{ $household->residents_count }} Jiwa</span>
                                        <svg class="w-3 h-3 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>

                                {{-- 9. AKSI (EDIT) --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center whitespace-nowrap">
                                        {{-- Tombol Edit (Ikon Pensil) --}}
                                        <a 
                                            href="{{ url('/admin/households/' . $household->id . '/edit') }}" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer" 
                                            title="Ubah Data Kartu Keluarga"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-sm text-[#5B6B82]">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if(filled($search))
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            @endif
                                        </svg>
                                    </div>
                                    @if(filled($search) || filled($anggotaFilter))
                                        <p class="font-bold text-[#0F1F3D]">Tidak ada data kartu keluarga yang cocok.</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">
                                            @if(filled($search) && filled($anggotaFilter))
                                                Tidak ditemukan data dengan kata kunci "{{ $search }}" dan filter jumlah anggota terpilih.
                                            @elseif(filled($search))
                                                Tidak ditemukan kartu keluarga dengan kata kunci "{{ $search }}".
                                            @else
                                                Tidak ada kartu keluarga dengan kriteria jumlah anggota tersebut.
                                            @endif
                                        </p>
                                    @else
                                        <p class="font-bold text-[#0F1F3D]">Belum Ada Data Kartu Keluarga</p>
                                        <p class="text-xs text-[#94A3B8] mt-0.5">Mulai tambahkan kartu keluarga baru menggunakan tombol di atas.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            @if($households->hasPages())
                <div class="px-4 py-3 border-t border-[#E2E8F0] bg-slate-50/50">
                    {{ $households->links() }}
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
