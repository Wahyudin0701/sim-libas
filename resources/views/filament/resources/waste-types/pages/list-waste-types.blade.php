<x-filament-panels::page>
    <div 
        x-data="{
            deleteConfirmModalOpen: false,
            deleteTypeId: null,
            deleteTypeTitle: '',
            formModalOpen: @entangle('formModalOpen'),
            confirmDelete(id, title) {
                this.deleteTypeId = id;
                this.deleteTypeTitle = title;
                this.deleteConfirmModalOpen = true;
            },
            closeDeleteConfirm() {
                this.deleteConfirmModalOpen = false;
                this.deleteTypeId = null;
                this.deleteTypeTitle = '';
            },
            executeDelete() {
                if (this.deleteTypeId) {
                    const id = this.deleteTypeId;
                    this.closeDeleteConfirm();
                    $wire.deleteWasteType(id);
                }
            }
        }"
        @keydown.escape.window="closeDeleteConfirm(); if (formModalOpen) { formModalOpen = false; $wire.closeForm(); }"
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
                        title="Master Harga Sampah"
                        category="BANK SAMPAH RT 15"
                        description="Modul katalog komoditas daur ulang Bank Sampah Lestari RT 15 untuk mengatur jenis sampah (plastik, kertas/karton, logam, kaca), satuan ukuran, dan patokan harga beli per kilogram."
                        :steps="[
                            ['title' => 'Pencarian & Filter Kategori', 'desc' => 'Cari nama komoditas atau saring menurut kategori material sampah (Organik, Anorganik, B3, Daur Ulang).'],
                            ['title' => 'Tambah Komoditas Baru', 'desc' => 'Klik tombol biru \'+ Tambah Komoditas\' untuk mendaftarkan nama material, kode, satuan timbangan, dan tarif harga.'],
                            ['title' => 'Kelola Penyesuaian Tarif', 'desc' => 'Gunakan tombol \'Lihat\' untuk info komoditas, \'Ubah\' untuk memperbarui harga beli pasar terkini, atau \'Hapus\' jika komoditas dinonaktifkan.'],
                            ['title' => 'Ekspor Daftar Tarif', 'desc' => 'Klik tombol \'Ekspor Data\' untuk mengunduh brosur daftar harga beli bank sampah dalam bentuk PDF atau Excel.']
                        ]"
                        role="Pengurus Bank Sampah Lestari RT 15"
                        :tips="[
                            'Perubahan harga pada halaman ini hanya berlaku untuk transaksi setoran baru ke depan.',
                            'Transaksi penimbangan lama tidak akan terpengaruh untuk menjaga integritas pembukuan kas bank sampah.',
                            'Daftar harga yang aktif dapat dilihat langsung oleh warga pada menu Bank Sampah di portal warga.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        Master Harga Sampah
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Ekspor & Tambah Komoditas) --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Dropdown Ekspor Data (PDF & Excel) --}}
                <x-export-dropdown resource="waste-types" :extraParams="['kategori' => '$wire.kategoriFilter']" />

                {{-- Tombol Primer: Tambah Komoditas --}}
                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openCreate" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Komoditas</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. SEARCH BAR & DROPDOWN FILTER KATEGORI & RESET FILTER (STANDAR RESMI)
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
                        id="search-type-input"
                        name="search"
                        value="{{ request('search') }}"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari ... " 
                        class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all"
                        autocomplete="off"
                    >

                    <button 
                        type="button" 
                        id="search-type-clear-btn" 
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
                {{-- Filter Dropdown Kategori Komoditas Sampah --}}
                <div class="w-full sm:w-56">
                    <select 
                        id="filter-type-kategori"
                        name="kategori"
                        wire:model.live="kategoriFilter" 
                        class="w-full py-2.5 px-3 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-all cursor-pointer font-medium truncate"
                    >
                        <option value="semua" {{ $kategoriFilter === 'semua' ? 'selected' : '' }}>Semua Kategori ({{ $countSemua }})</option>
                        <option value="anorganik" {{ $kategoriFilter === 'anorganik' ? 'selected' : '' }}>Anorganik ({{ $countAnorganik }})</option>
                        <option value="organik" {{ $kategoriFilter === 'organik' ? 'selected' : '' }}>Organik ({{ $countOrganik }})</option>
                        <option value="b3" {{ $kategoriFilter === 'b3' ? 'selected' : '' }}>B3 & Elektronik ({{ $countB3 }})</option>
                        <option value="lainnya" {{ $kategoriFilter === 'lainnya' ? 'selected' : '' }}>Lainnya ({{ $countLainnya }})</option>
                    </select>
                </div>

                {{-- Container Tombol Reset Filter: SSR Kondisional & Injeksi DOM Bersih JS (Berwarna Merah) --}}
                <div id="reset-filter-type-container" class="shrink-0 flex items-center justify-center">
                    @php
                        $hasActiveFilters = filled($search) 
                            || ($kategoriFilter !== 'semua' && filled($kategoriFilter)) 
                            || request()->anyFilled(['search']) 
                            || (request()->has('kategori') && request('kategori') !== 'semua' && request('kategori') !== '');
                    @endphp
                    @if($hasActiveFilters)
                        <a 
                            href="{{ url()->current() }}" 
                            wire:click.prevent="resetFilters"
                            id="btn-reset-filter-type" 
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
             4. TABEL DATA MASTER HARGA (DEEP NAVY #0B3A85 TABLE HEADER)
             ==================================================================== --}}
        <div class="bg-white rounded-none md:rounded-2xl border border-slate-200 overflow-hidden shadow-xs relative">
            {{-- Indikator Loading Standar SIM-LiBas saat Filter atau Navigasi Tabel --}}
            <x-table-loading-indicator 
                target="kategoriFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                text="Memperbarui data harga komoditas..."
            />

            <div class="overflow-x-auto">
                <table class="w-full min-w-full md:min-w-[1050px] text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white">
                            <th class="hidden md:table-cell py-3 px-4 font-bold text-center w-12 shrink-0">NO</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[120px] whitespace-nowrap">Status</th>
                            <th class="py-3 px-3 sm:px-4 font-bold md:min-w-[260px]">Nama Komoditas Sampah</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold min-w-[130px] whitespace-nowrap">Kategori</th>
                            <th class="py-3 px-3 sm:px-4 font-bold text-right min-w-[110px] md:min-w-[140px] whitespace-nowrap">Patokan Harga Beli</th>
                            <th class="hidden md:table-cell py-3 px-4 font-bold text-center min-w-[130px] whitespace-nowrap">Total Terkumpul</th>
                            <th class="py-3 px-2 sm:px-4 font-bold text-center w-16 sm:w-24 md:w-36 md:min-w-[140px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody 
                        id="types-table-body" 
                        class="divide-y divide-slate-100 transition-opacity duration-150"
                        wire:loading.class="opacity-40 pointer-events-none"
                        wire:target="kategoriFilter, search, resetFilters, previousPage, nextPage, gotoPage"
                    >
                        @forelse($wasteTypes as $index => $item)
                            <tr 
                                class="hover:bg-slate-50/80 transition-colors type-data-row"
                                data-kategori="{{ $item->kategori }}"
                            >
                                {{-- Kolom Nomor Urut --}}
                                <td class="hidden md:table-cell py-3.5 px-4 text-center font-mono font-semibold text-slate-500 text-xs whitespace-nowrap">
                                    {{ $wasteTypes->firstItem() ? ($wasteTypes->firstItem() + $index) : ($index + 1) }}
                                </td>

                                {{-- Kolom Status --}}
                                <td class="hidden md:table-cell py-3.5 px-4 whitespace-nowrap">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Menerima
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            Ditutup
                                        </span>
                                    @endif
                                </td>

                                {{-- Kolom Nama Komoditas --}}
                                <td class="py-3.5 px-3 sm:px-4">
                                    <div class="font-extrabold text-[#0F1F3D] text-[12px] sm:text-[13px]">
                                        {{ $item->nama }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        ID Komoditas: #{{ $item->id }}
                                    </div>
                                </td>

                                {{-- Kolom Kategori --}}
                                <td class="hidden md:table-cell py-3.5 px-4 whitespace-nowrap">
                                    @php
                                        $badgeClass = match($item->kategori) {
                                            'organik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'anorganik' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'b3' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $badgeClass }}">
                                        {{ $item->kategori }}
                                    </span>
                                </td>

                                {{-- Kolom Patokan Harga Beli --}}
                                <td class="py-3.5 px-3 sm:px-4 text-right whitespace-nowrap">
                                    <div class="font-black text-emerald-600 text-[12.5px] sm:text-[13.5px]">
                                        Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        per {{ $item->satuan }}
                                    </div>
                                </td>

                                {{-- Kolom Total Terkumpul --}}
                                <td class="hidden md:table-cell py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="font-bold text-slate-800">
                                        {{ number_format($item->deposits_sum_berat_kg ?? 0, 1, ',', '.') }} {{ $item->satuan }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $item->deposits_count ?? 0 }} kali setoran
                                    </div>
                                </td>

                                {{-- Kolom Aksi dengan Tombol Berwarna Konsisten (Standar SIM-LiBas) --}}
                                <td class="py-3.5 px-2 sm:px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex flex-col md:flex-row items-center justify-center gap-1 md:gap-1.5 whitespace-nowrap">
                                        {{-- Toggle Aktif/Nonaktif (Teal jika aktif, Abu-abu jika nonaktif) --}}
                                        <button 
                                            type="button" 
                                            wire:click="toggleActive({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border {{ $item->is_active ? 'border-teal-300 bg-teal-50 text-teal-600 hover:bg-teal-100 hover:text-teal-700 hover:border-teal-400' : 'border-slate-200 bg-slate-50 text-slate-400 hover:bg-teal-50 hover:text-teal-600 hover:border-teal-300' }} transition-all shadow-2xs cursor-pointer"
                                            title="{{ $item->is_active ? 'Tutup Penerimaan Komoditas' : 'Buka Penerimaan Komoditas' }}"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Ubah Modal (Kuning/Amber) --}}
                                        <button 
                                            type="button" 
                                            @click="formModalOpen = true"
                                            wire:click="openEdit({{ $item->id }})" 
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-amber-300 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 hover:border-amber-400 transition-all shadow-2xs cursor-pointer"
                                            title="Ubah Harga & Pengaturan"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Hapus Modal (Merah/Rose) --}}
                                        <button 
                                            type="button" 
                                            @click="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-lg border border-rose-300 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-400 transition-all shadow-2xs cursor-pointer"
                                            title="Hapus Komoditas"
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
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <p class="text-sm font-semibold text-slate-600">Belum ada komoditas sampah terdaftar</p>
                                        <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Tambah Komoditas" untuk menambahkan daftar harga baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Baris Khusus Hasil Filter Kosong (Client-Side JS) --}}
                        <tr id="type-client-empty-row" style="display: none;">
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-slate-600">Tidak ada komoditas sampah yang cocok dengan kata kunci Anda</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Coba cari dengan nama atau kategori sampah lainnya.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if($wasteTypes->hasPages())
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $wasteTypes->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. MODAL FORM KOMODITAS (TAMBAH / UBAH) (ALPINE.JS X-SHOW INSTANT OPEN)
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
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-md border border-slate-200" 
                    @click.stop
                >
                    <div class="bg-[#0B3A85] text-white px-5 py-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-blue-200">Katalog Komoditas</span>
                            <h3 class="text-base font-black">{{ $isEditing ? 'Ubah Harga & Komoditas' : 'Tambah Komoditas Sampah Baru' }}</h3>
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
                        <div class="h-6 w-3/4 bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openEdit">
                        <form wire:submit.prevent="saveWasteType" class="p-5 space-y-3.5 text-xs">
                            {{-- Nama Komoditas --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Nama Komoditas Sampah <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="form_nama" 
                                    placeholder="Contoh: Botol Plastik PET Bersih"
                                    class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800 font-bold"
                                >
                                @error('form_nama') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">
                                    Kategori Komoditas <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    wire:model="form_kategori" 
                                    class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                >
                                    <option value="anorganik">Anorganik (Plastik, Kertas, Logam, Kaca)</option>
                                    <option value="organik">Organik (Kompos, Sisa Organik)</option>
                                    <option value="b3">B3 & Elektronik (Baterai, Lampu, E-Waste)</option>
                                    <option value="lainnya">Lainnya / Khusus</option>
                                </select>
                                @error('form_kategori') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                            </div>

                            {{-- Harga & Satuan --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Harga Beli (Rp) <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        step="50" 
                                        wire:model="form_harga_per_kg" 
                                        placeholder="Contoh: 3500"
                                        class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800 font-bold"
                                    >
                                    @error('form_harga_per_kg') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Satuan Timbangan <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="form_satuan" 
                                        class="w-full p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#0B3A85] text-xs text-slate-800"
                                    >
                                        <option value="kg">kg (Kilogram)</option>
                                        <option value="liter">liter</option>
                                        <option value="pcs">pcs / buah</option>
                                    </select>
                                    @error('form_satuan') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Checkbox Aktif --}}
                            <div class="pt-1">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="form_is_active" class="rounded border-slate-300 text-[#0B3A85] focus:ring-[#0B3A85]">
                                    <span class="font-bold text-slate-700">Aktif menerima setoran komoditas ini dari warga</span>
                                </label>
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
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Komoditas' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL KONFIRMASI HAPUS (ALPINE JS)
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

                    <h4 class="text-base font-black text-slate-800">Hapus Komoditas Sampah?</h4>
                    <p class="text-xs text-slate-500 mt-1.5">
                        Apakah Anda yakin ingin menghapus komoditas <span class="font-bold text-slate-800" x-text="deleteTypeTitle"></span>? Komoditas yang telah memiliki catatan setoran transaksi warga tidak dapat dihapus.
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
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================================
         7. JAVASCRIPT STANDAR: REAL-TIME SEARCH & FILTER RESET INJECTION
         ==================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initTypeSearch();
        });

        document.addEventListener('livewire:navigated', function() {
            initTypeSearch();
        });

        function initTypeSearch() {
            const searchInput = document.getElementById('search-type-input');
            const clearBtn = document.getElementById('search-type-clear-btn');
            const kategoriSelect = document.getElementById('filter-type-kategori');
            const resetContainer = document.getElementById('reset-filter-type-container');
            const tableBody = document.getElementById('types-table-body');

            if (!searchInput) return;

            const urlParams = new URLSearchParams(window.location.search);
            const hasServerFilter = urlParams.has('search') || (urlParams.has('kategori') && urlParams.get('kategori') !== 'semua');

            function applyFilter() {
                const term = searchInput.value.toLowerCase().trim();
                const kategoriVal = kategoriSelect ? kategoriSelect.value : 'semua';

                // Tampilkan/sembunyikan tombol clear
                if (clearBtn) {
                    clearBtn.style.display = term.length > 0 ? 'flex' : 'none';
                }

                // Injeksi elemen tombol pembersih pencarian secara dinamis (warna merah menyala & soft background)
                const hasActiveFilter = term.length > 0 || (kategoriVal !== 'semua' && kategoriVal !== '');
                let resetBtn = document.getElementById('btn-reset-filter-type');

                if (hasActiveFilter) {
                    if (!resetBtn && resetContainer) {
                        resetBtn = document.createElement('a');
                        resetBtn.href = window.location.pathname;
                        resetBtn.id = 'btn-reset-filter-type';
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
                    const rows = tableBody.querySelectorAll('tr.type-data-row');
                    let matched = 0;

                    rows.forEach(function(row) {
                        const content = row.textContent.toLowerCase();
                        const rowKat = row.dataset.kategori;

                        let matchesSearch = term === '' || content.includes(term);
                        let matchesKat = (kategoriVal === 'semua' || !kategoriVal) ? true : (rowKat === kategoriVal);

                        if (matchesSearch && matchesKat) {
                            row.style.display = '';
                            matched++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = document.getElementById('type-client-empty-row');
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
                if (kategoriSelect) kategoriSelect.addEventListener('change', applyFilter);

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
                    const btn = e.target.closest('#btn-reset-filter-type');
                    if (!btn) return;

                    searchInput.value = '';
                    if (kategoriSelect) kategoriSelect.value = 'semua';
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
                                    comp.set('kategoriFilter', 'semua');
                                }
                            }
                        }
                    }

                    if (hasServerFilter) {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            if (searchInput.value.trim().length > 0 || (kategoriSelect && kategoriSelect.value !== 'semua')) {
                applyFilter();
            }
        }
    </script>
</x-filament-panels::page>
