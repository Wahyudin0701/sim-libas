<x-filament-panels::page>
    <div 
        x-data="{
            detailModalOpen: @entangle('detailModalOpen'),
            formModalOpen: @entangle('formModalOpen'),
        }"
        @keydown.escape.window="
            if (detailModalOpen) { detailModalOpen = false; $wire.closeDetail(); }
            if (formModalOpen) { formModalOpen = false; $wire.closeForm(); }
        "
        class="w-full space-y-4"
    >
        {{-- ====================================================================
             1. HEADER HALAMAN: SUBTITLE, PANDUAN (!), JUDUL, DAN TOMBOL AKSI
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                {{-- Kategori Subtitle & Tombol Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>PENGATURAN SISTEM</span>
                    <x-page-guide 
                        title="Pengaturan Sistem"
                        category="PENGATURAN SISTEM"
                        description="Pusat konfigurasi master identitas wilayah RT, pimpinan kepengurusan, kontak resmi, format penomoran surat administrasi warga, serta kelengkapan legalitas tanda tangan dan stempel digital RT 15 Pematang Sulur."
                        :steps="[
                            ['title' => 'Identitas Wilayah RT', 'desc' => 'Periksa dan kelola nama RT, alamat sekretariat domisili, kelurahan, kecamatan, kota, hingga kode pos resmi RT 15.'],
                            ['title' => 'Pimpinan & Kontak Resmi', 'desc' => 'Kelola nama Ketua RT yang menjabat, nomor telepon WhatsApp resmi, dan alamat email surat-menyurat RT.'],
                            ['title' => 'Administrasi Persuratan', 'desc' => 'Atur pola template format nomor surat pengantar warga otomatis ({NO}, {BULAN_ROMAWI}, {TAHUN}).'],
                            ['title' => 'Tanda Tangan & Stempel Digital', 'desc' => 'Unggah atau perbarui berkas stempel resmi dan tanda tangan digital Ketua RT untuk penerbitan surat pengantar otomatis.']
                        ]"
                        role="Super Admin (Administrator Sistem)"
                        :tips="[
                            'Perubahan nama RT, ketua RT, dan alamat otomatis diselaraskan pada kop seluruh surat pengantar dan laporan PDF.',
                            'Gunakan berkas stempel dan tanda tangan berlatar belakang transparan (PNG) dengan resolusi tajam agar hasil cetak surat rapi.',
                            'Format nomor surat mendukung variabel dinamis: {NO}, {BULAN_ROMAWI}, dan {TAHUN}.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman --}}
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-[#0F1F3D] tracking-tight">
                        Pengaturan Sistem
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi Primer di Header: Ubah Pengaturan RT --}}
            <div class="flex items-center gap-2 shrink-0">
                <button 
                    type="button" 
                    @click="formModalOpen = true"
                    wire:click="openEdit" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all duration-150 shadow-xs hover:shadow-md cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Ubah Pengaturan RT</span>
                </button>
            </div>
        </div>

        {{-- ====================================================================
             2. KPI OVERVIEW CARDS (4 KARTU STATISTIK & STATUS SISTEM)
             ==================================================================== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3">
            {{-- Kartu 1: Wilayah Administrasi --}}
            <div class="bg-white rounded-xl p-3 sm:p-3.5 border border-[#E2E8F0] shadow-2xs flex items-center gap-2.5 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-[#0B3A85]/10 text-[#0B3A85] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Wilayah Administrasi</span>
                    <h4 class="text-xs sm:text-sm font-extrabold text-[#0F1F3D] truncate">{{ $primarySetting->nama_rt ?? 'RT 15 Pematang Sulur' }}</h4>
                    <p class="text-[10px] sm:text-[10.5px] text-slate-500 truncate mt-0.5">Kel. {{ $primarySetting->kelurahan ?? 'Pematang Sulur' }}</p>
                </div>
            </div>

            {{-- Kartu 2: Ketua RT & Pimpinan --}}
            <div class="bg-white rounded-xl p-3 sm:p-3.5 border border-[#E2E8F0] shadow-2xs flex items-center gap-2.5 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-[#1B7A3A]/10 text-[#1B7A3A] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Ketua RT 15</span>
                    <h4 class="text-xs sm:text-sm font-extrabold text-[#0F1F3D] truncate">{{ $primarySetting->nama_ketua_rt ?? 'Bpk. H. Ahmad Sudirman, S.E.' }}</h4>
                    <p class="text-[10px] sm:text-[10.5px] text-emerald-600 font-semibold truncate mt-0.5">Pimpinan Aktif</p>
                </div>
            </div>

            {{-- Kartu 3: Kontak Resmi --}}
            <div class="bg-white rounded-xl p-3 sm:p-3.5 border border-[#E2E8F0] shadow-2xs flex items-center gap-2.5 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-[#19A7E8]/10 text-[#19A7E8] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Kontak & WhatsApp</span>
                    <h4 class="text-xs sm:text-sm font-mono font-bold text-[#0F1F3D] truncate">{{ $primarySetting->telepon ?? '0812-7400-1515' }}</h4>
                    <p class="text-[10px] sm:text-[10.5px] text-slate-500 truncate mt-0.5">WhatsApp RT</p>
                </div>
            </div>

            {{-- Kartu 4: Legalitas Persuratan --}}
            <div class="bg-white rounded-xl p-3 sm:p-3.5 border border-[#E2E8F0] shadow-2xs flex items-center gap-2.5 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-[#F2A900]/10 text-[#B45309] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 block truncate">Legalitas Persuratan</span>
                    <h4 class="text-xs sm:text-sm font-extrabold text-[#0F1F3D] truncate">Format Surat</h4>
                    <div class="flex items-center gap-1 mt-0.5">
                        <span class="inline-flex items-center px-1 sm:px-1.5 py-0.2 rounded text-[8.5px] sm:text-[9.5px] font-bold {{ $primarySetting->tanda_tangan_path ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            TTD {{ $primarySetting->tanda_tangan_path ? '✓' : '✗' }}
                        </span>
                        <span class="inline-flex items-center px-1 sm:px-1.5 py-0.2 rounded text-[8.5px] sm:text-[9.5px] font-bold {{ $primarySetting->stempel_path ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            Cap {{ $primarySetting->stempel_path ? '✓' : '✗' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             3. TABEL PENGATURAN SISTEM (HEADER DEEP NAVY #0B3A85)
             ==================================================================== --}}
        <div class="bg-white rounded-none md:rounded-xl shadow-2xs border border-[#E2E8F0] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full md:min-w-[1050px] text-left border-collapse">
                    {{-- Header Tabel Deep Navy Standar SIM-LiBas --}}
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="hidden md:table-cell py-2.5 px-3 text-center w-12 min-w-[48px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-3 sm:px-4 min-w-[140px] md:min-w-[220px]">IDENTITAS WILAYAH RT</th>
                            <th class="py-2.5 px-3 sm:px-4 min-w-[130px] md:min-w-[180px]">KETUA RT</th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[200px]">ALAMAT DOMISILI / SEKRETARIAT</th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[180px] whitespace-nowrap">NO. TELEPON / WA & EMAIL</th>
                            <th class="hidden md:table-cell py-2.5 px-3 min-w-[190px] whitespace-nowrap">FORMAT SURAT & LEGALITAS</th>
                            <th class="py-2.5 px-2 sm:px-3 text-center w-16 sm:w-20 md:min-w-[110px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#F1F5F9] text-xs">
                        @forelse($settings as $index => $item)
                            <tr class="hover:bg-[#F8FAFC] transition-colors">
                                {{-- 1. NO --}}
                                <td class="hidden md:table-cell py-3 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $settings->firstItem() ? ($settings->firstItem() + $index) : ($index + 1) }}
                                </td>

                                {{-- 2. IDENTITAS WILAYAH RT --}}
                                <td class="py-3 px-3 sm:px-4">
                                    <div class="font-bold text-xs sm:text-sm text-[#0F1F3D] hover:text-[#0B3A85] cursor-pointer transition-colors" wire:click="openDetail({{ $item->id }})">
                                        {{ $item->nama_rt }}
                                    </div>
                                    <div class="text-[10px] sm:text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>Kel. {{ $item->kelurahan }}, Kec. {{ $item->kecamatan }}</span>
                                    </div>
                                    <div class="text-[9.5px] sm:text-[10px] text-slate-400 mt-0.5">
                                        {{ $item->kota }}, {{ $item->provinsi }} {{ $item->kode_pos ? "({$item->kode_pos})" : '' }}
                                    </div>
                                </td>

                                {{-- 3. KETUA RT --}}
                                <td class="py-3 px-3 sm:px-4">
                                    <div class="font-bold text-xs sm:text-[13px] text-slate-800">
                                        {{ $item->nama_ketua_rt }}
                                    </div>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center gap-1 px-1.5 sm:px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Pimpinan RT 15
                                        </span>
                                    </div>
                                </td>

                                {{-- 4. ALAMAT SEKRETARIAT --}}
                                <td class="hidden md:table-cell py-3 px-3 min-w-[200px]">
                                    <div class="text-xs text-slate-700 leading-relaxed line-clamp-2" title="{{ $item->alamat }}">
                                        {{ $item->alamat ?: '-' }}
                                    </div>
                                </td>

                                {{-- 5. NO. TELEPON / WA & EMAIL (SELARAS DENGAN HALAMAN LAIN) --}}
                                <td class="hidden md:table-cell py-3 px-3 min-w-[180px] whitespace-nowrap">
                                    @php
                                        $cleanWa = preg_replace('/[^0-9]/', '', (string) $item->telepon);
                                        if (str_starts_with($cleanWa, '0')) {
                                            $cleanWa = '62' . substr($cleanWa, 1);
                                        }
                                    @endphp
                                    @if($item->telepon)
                                        @if($cleanWa)
                                            <a 
                                                href="https://wa.me/{{ $cleanWa }}" 
                                                target="_blank" 
                                                rel="noopener noreferrer" 
                                                class="inline-flex items-center gap-1 text-[#1B7A3A] font-bold hover:underline whitespace-nowrap text-xs" 
                                                title="Chat WhatsApp Resmi RT 15 ({{ $item->telepon }})"
                                            >
                                                <span class="select-all font-mono">{{ $item->telepon }}</span>
                                                <span class="text-[10px] text-slate-400 font-normal shrink-0">(Chat WA)</span>
                                            </a>
                                        @else
                                            <span class="select-all font-mono font-medium text-slate-700 whitespace-nowrap text-xs">{{ $item->telepon }}</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 font-sans italic text-xs">-</span>
                                    @endif

                                    @if($item->email)
                                        <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1 truncate max-w-[200px]">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="truncate">{{ $item->email }}</span>
                                        </div>
                                    @endif
                                </td>

                                {{-- 6. FORMAT SURAT & LEGALITAS --}}
                                <td class="hidden md:table-cell py-3 px-3 min-w-[190px]">
                                    <div class="font-mono text-[11px] text-[#0B3A85] font-semibold bg-blue-50/70 border border-blue-100 px-2 py-0.5 rounded truncate max-w-[210px]" title="{{ $item->format_nomor_surat }}">
                                        {{ $item->format_nomor_surat }}
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        @if($item->tanda_tangan_path)
                                            <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                                                <svg class="w-2.5 h-2.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                TTD Digital
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-[10px] font-medium text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">
                                                TTD Belum Ada
                                            </span>
                                        @endif

                                        @if($item->stempel_path)
                                            <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                                                <svg class="w-2.5 h-2.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                Stempel Resmi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-[10px] font-medium text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">
                                                Stempel Belum Ada
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- 7. AKSI (LIHAT DETAIL & EDIT) --}}
                                <td class="py-3 px-2 sm:px-3 text-center whitespace-nowrap">
                                    <div class="inline-flex flex-col md:flex-row items-center justify-center gap-1 md:gap-1.5">
                                        {{-- 1. Lihat Detail (Mata #0B3A85) --}}
                                        <button 
                                            type="button" 
                                            wire:click="openDetail({{ $item->id }})" 
                                            class="w-7 h-7 rounded-lg bg-blue-50 text-[#0B3A85] hover:bg-[#0B3A85] hover:text-white transition-colors flex items-center justify-center cursor-pointer shadow-2xs" 
                                            title="Lihat Detail Konfigurasi RT"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- 2. Ubah / Edit (Pensil #1B7A3A) --}}
                                        <button 
                                            type="button" 
                                            wire:click="openEdit({{ $item->id }})" 
                                            class="w-7 h-7 rounded-lg bg-emerald-50 text-[#1B7A3A] hover:bg-[#1B7A3A] hover:text-white transition-colors flex items-center justify-center cursor-pointer shadow-2xs" 
                                            title="Ubah Pengaturan RT"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <p class="font-bold text-[#0F1F3D]">Tidak ditemukan konfigurasi yang cocok.</p>
                                    <p class="text-xs text-[#94A3B8] mt-0.5">Silakan reset pencarian untuk menampilkan data pengaturan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            @if($settings->hasPages())
                <div class="p-3.5 border-t border-slate-100 bg-[#F8FAFC]">
                    {{ $settings->links() }}
                </div>
            @endif
        </div>

        {{-- ====================================================================
             5. KARTU RINGKASAN LEGALITAS DIGITAL & INFORMASI SISTEM SIM-LIBAS
             ==================================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 pt-1">
            {{-- Panel 1: Legalitas Surat & Berkas Digital RT 15 --}}
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-2xs p-4 sm:p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#0B3A85]/10 text-[#0B3A85] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-[#0F1F3D] text-xs sm:text-sm">Legalitas Administrasi & Surat Resmi</h3>
                            <p class="text-[10.5px] text-slate-400">Berkas digital untuk penerbitan surat pengantar otomatis</p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        wire:click="openEdit" 
                        class="text-[11px] font-bold text-[#0B3A85] hover:underline cursor-pointer"
                    >
                        Kelola Berkas
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3.5 pt-3.5">
                    {{-- Pratinjau Tanda Tangan --}}
                    <div class="border border-slate-200 rounded-xl p-3 bg-slate-50/50 flex flex-col items-center justify-center text-center min-h-[140px]">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Tanda Tangan Digital Ketua RT</span>
                        @if($primarySetting->tanda_tangan_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($primarySetting->tanda_tangan_path))
                            <img 
                                src="{{ asset('storage/' . $primarySetting->tanda_tangan_path) }}" 
                                alt="Tanda Tangan Ketua RT" 
                                class="max-h-16 max-w-full object-contain mb-2"
                            >
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                Berkas Terverifikasi
                            </span>
                        @else
                            <div class="w-10 h-10 rounded-full bg-slate-200/70 text-slate-400 flex items-center justify-center mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </div>
                            <p class="text-[10.5px] text-slate-500 font-medium">Belum ada tanda tangan</p>
                            <p class="text-[9.5px] text-slate-400 mt-0.5">Unggah berkas format PNG transparan</p>
                        @endif
                    </div>

                    {{-- Pratinjau Stempel Resmi --}}
                    <div class="border border-slate-200 rounded-xl p-3 bg-slate-50/50 flex flex-col items-center justify-center text-center min-h-[140px]">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Stempel Resmi RT 15</span>
                        @if($primarySetting->stempel_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($primarySetting->stempel_path))
                            <img 
                                src="{{ asset('storage/' . $primarySetting->stempel_path) }}" 
                                alt="Stempel Resmi RT 15" 
                                class="max-h-16 max-w-full object-contain mb-2"
                            >
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                Berkas Terverifikasi
                            </span>
                        @else
                            <div class="w-10 h-10 rounded-full bg-slate-200/70 text-slate-400 flex items-center justify-center mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <p class="text-[10.5px] text-slate-500 font-medium">Belum ada stempel</p>
                            <p class="text-[9.5px] text-slate-400 mt-0.5">Unggah cap resmi format PNG</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panel 2: Spesifikasi & Informasi Sistem SIM-LiBas --}}
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-2xs p-4 sm:p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#1B7A3A]/10 text-[#1B7A3A] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-[#0F1F3D] text-xs sm:text-sm">Status & Spesifikasi Sistem</h3>
                            <p class="text-[10.5px] text-slate-400">Informasi lingkungan server dan aplikasi SIM-LiBas</p>
                        </div>
                    </div>

                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Sistem Normal
                    </span>
                </div>

                <div class="space-y-2.5 pt-3.5 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Aplikasi:</span>
                        <span class="font-bold text-[#0F1F3D]">SIM-LiBas RT 15 Pematang Sulur</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Versi Rilis:</span>
                        <span class="font-mono font-bold text-[#0B3A85]">v1.0.0 Production</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Framework & Engine:</span>
                        <span class="text-slate-700 font-medium">Laravel {{ app()->version() }} • PHP {{ PHP_VERSION }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Waktu Server:</span>
                        <span class="font-mono text-slate-700">{{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <span class="text-slate-500 font-medium">Hak Akses Modul:</span>
                        <span class="text-slate-700 font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded text-[10.5px]">Khusus Super Admin</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             6. MODAL DETAIL PENGATURAN SISTEM (ALPINE.JS X-SHOW INSTANT OPEN)
             ==================================================================== --}}
        <div 
            x-show="detailModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
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
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer"
                @click="detailModalOpen = false; $wire.closeDetail()"
            ></div>

            <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
                <div 
                    x-show="detailModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-2xl border border-slate-200 max-h-[90vh] flex flex-col"
                    @click.stop
                >
                    {{-- Header Modal Deep Navy --}}
                    <div class="bg-[#0B3A85] px-6 py-4 flex items-center justify-between text-white shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-sm sm:text-base leading-tight">Detail Pengaturan Sistem RT</h3>
                                <p class="text-[11px] text-blue-200">Konfigurasi resmi identitas wilayah & legalitas digital RT 15</p>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="detailModalOpen = false; $wire.closeDetail()" 
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Konten Modal Detail --}}
                    @if($selectedSetting)
                        <div class="p-6 space-y-4 text-xs text-slate-700 overflow-y-auto flex-1">
                            {{-- Info Banner --}}
                            <div class="bg-[#F8FAFC] border border-slate-200 rounded-xl p-4 space-y-3">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Nama Wilayah RT:</span>
                                    <h4 class="text-base font-extrabold text-[#0F1F3D] mt-0.5">{{ $selectedSetting->nama_rt }}</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-3 pt-2.5 border-t border-slate-200">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Ketua RT:</span>
                                        <p class="font-bold text-slate-800 text-xs mt-0.5">{{ $selectedSetting->nama_ketua_rt }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Telepon / WhatsApp:</span>
                                        <p class="font-mono font-bold text-[#1B7A3A] text-xs mt-0.5">{{ $selectedSetting->telepon ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Domisili & Alamat Sekretariat --}}
                            <div class="space-y-2">
                                <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wider block">Alamat Domisili Sekretariat</span>
                                <div class="p-3 bg-white border border-slate-200 rounded-xl font-medium text-slate-700 leading-relaxed">
                                    {{ $selectedSetting->alamat ?: 'Belum ditentukan.' }}
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-[11px]">
                                    <div class="p-2 bg-slate-50 rounded-lg border border-slate-200/60">
                                        <span class="text-slate-400 block text-[9.5px]">Kelurahan:</span>
                                        <span class="font-bold text-slate-800">{{ $selectedSetting->kelurahan }}</span>
                                    </div>
                                    <div class="p-2 bg-slate-50 rounded-lg border border-slate-200/60">
                                        <span class="text-slate-400 block text-[9.5px]">Kecamatan:</span>
                                        <span class="font-bold text-slate-800">{{ $selectedSetting->kecamatan }}</span>
                                    </div>
                                    <div class="p-2 bg-slate-50 rounded-lg border border-slate-200/60">
                                        <span class="text-slate-400 block text-[9.5px]">Kota:</span>
                                        <span class="font-bold text-slate-800">{{ $selectedSetting->kota }}</span>
                                    </div>
                                    <div class="p-2 bg-slate-50 rounded-lg border border-slate-200/60">
                                        <span class="text-slate-400 block text-[9.5px]">Kode Pos:</span>
                                        <span class="font-mono font-bold text-slate-800">{{ $selectedSetting->kode_pos ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Format Nomor Surat & Legalitas --}}
                            <div class="space-y-2 pt-1">
                                <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wider block">Format Penomoran Surat Resmi</span>
                                <div class="p-3 bg-blue-50/60 border border-blue-200 rounded-xl font-mono font-bold text-[#0B3A85]">
                                    {{ $selectedSetting->format_nomor_surat }}
                                </div>
                            </div>

                            {{-- Pratinjau TTD & Stempel --}}
                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <div class="p-3 border border-slate-200 rounded-xl text-center bg-slate-50">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Tanda Tangan Digital</span>
                                    @if($selectedSetting->tanda_tangan_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($selectedSetting->tanda_tangan_path))
                                        <img src="{{ asset('storage/' . $selectedSetting->tanda_tangan_path) }}" alt="TTD" class="max-h-16 mx-auto object-contain">
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Belum diunggah</span>
                                    @endif
                                </div>
                                <div class="p-3 border border-slate-200 rounded-xl text-center bg-slate-50">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Stempel Resmi</span>
                                    @if($selectedSetting->stempel_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($selectedSetting->stempel_path))
                                        <img src="{{ asset('storage/' . $selectedSetting->stempel_path) }}" alt="Stempel" class="max-h-16 mx-auto object-contain">
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Belum diunggah</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Footer Modal Detail --}}
                        <div class="bg-slate-50 px-6 py-3 flex justify-end gap-2 border-t border-slate-100 shrink-0">
                            <button 
                                type="button" 
                                @click="detailModalOpen = false; $wire.closeDetail()" 
                                class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs transition-colors cursor-pointer"
                            >
                                Tutup
                            </button>
                            <button 
                                type="button" 
                                @click="detailModalOpen = false; $wire.closeDetail(); $wire.openEdit({{ $selectedSetting->id }})" 
                                class="px-4 py-2 rounded-xl bg-[#0B3A85] text-white font-bold hover:bg-[#07275E] text-xs transition-colors cursor-pointer"
                            >
                                Ubah Pengaturan
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ====================================================================
             7. MODAL FORM UBAH PENGATURAN RT (LIVEWIRE MODAL FORM)
             ==================================================================== --}}
        <div 
            x-show="formModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
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
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer"
                @click="formModalOpen = false; $wire.closeForm()"
            ></div>

            <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
                <div 
                    x-show="formModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-2xl border border-slate-200 max-h-[90vh] flex flex-col"
                    @click.stop
                >
                    {{-- Header Modal Form Deep Navy --}}
                    <div class="bg-[#0B3A85] px-6 py-4 flex items-center justify-between text-white shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-sm sm:text-base leading-tight">Ubah Pengaturan Sistem RT</h3>
                                <p class="text-[11px] text-blue-200">Perbarui identitas wilayah, kontak, format surat, dan berkas digital</p>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="formModalOpen = false; $wire.closeForm()" 
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Form Body --}}
                    <form wire:submit.prevent="saveSetting" class="flex flex-col flex-1 overflow-hidden">
                        <div class="p-6 space-y-4 overflow-y-auto text-xs flex-1">
                        {{-- Section: Identitas Wilayah RT --}}
                        <div class="space-y-3">
                            <h4 class="font-bold text-[#0B3A85] uppercase tracking-wider text-[11px] flex items-center gap-1.5 pb-1 border-b border-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>1. Identitas Wilayah RT</span>
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-700 text-xs mb-1">
                                        Nama RT <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="nama_rt" 
                                        placeholder="Contoh: Rukun Tetangga 15" 
                                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('nama_rt') <p class="text-rose-600 text-[10.5px] mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 text-xs mb-1">
                                        Nama Ketua RT <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="nama_ketua_rt" 
                                        placeholder="Nama lengkap Ketua RT dan gelar" 
                                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                    >
                                    @error('nama_ketua_rt') <p class="text-rose-600 text-[10.5px] mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">
                                    Alamat Domisili / Sekretariat RT <span class="text-rose-500">*</span>
                                </label>
                                <textarea 
                                    rows="2" 
                                    wire:model="alamat" 
                                    placeholder="Alamat lengkap domisili sekretariat RT 15..." 
                                    class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10"
                                ></textarea>
                                @error('alamat') <p class="text-rose-600 text-[10.5px] mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                <div>
                                    <label class="block font-bold text-slate-700 text-[11px] mb-1">Kelurahan <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="kelurahan" class="w-full px-2.5 py-1.5 border border-[#DDE5F0] rounded-lg text-xs">
                                    @error('kelurahan') <p class="text-rose-600 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 text-[11px] mb-1">Kecamatan <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="kecamatan" class="w-full px-2.5 py-1.5 border border-[#DDE5F0] rounded-lg text-xs">
                                    @error('kecamatan') <p class="text-rose-600 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 text-[11px] mb-1">Kota / Kab. <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="kota" class="w-full px-2.5 py-1.5 border border-[#DDE5F0] rounded-lg text-xs">
                                    @error('kota') <p class="text-rose-600 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 text-[11px] mb-1">Kode Pos</label>
                                    <input type="text" wire:model="kode_pos" class="w-full px-2.5 py-1.5 border border-[#DDE5F0] rounded-lg text-xs font-mono">
                                    @error('kode_pos') <p class="text-rose-600 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section: Kontak & Komunikasi Resmi --}}
                        <div class="space-y-3 pt-2">
                            <h4 class="font-bold text-[#0B3A85] uppercase tracking-wider text-[11px] flex items-center gap-1.5 pb-1 border-b border-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>2. Kontak Resmi RT 15</span>
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-slate-700 text-xs mb-1">
                                        Nomor Telepon / WhatsApp
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="telepon" 
                                        placeholder="Contoh: 0812-7400-1515" 
                                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] font-mono focus:outline-none focus:border-[#0B3A85]"
                                    >
                                    @error('telepon') <p class="text-rose-600 text-[10.5px] mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 text-xs mb-1">
                                        Alamat Email Resmi
                                    </label>
                                    <input 
                                        type="email" 
                                        wire:model="email" 
                                        placeholder="Contoh: rt15pematangsulur@gmail.com" 
                                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85]"
                                    >
                                    @error('email') <p class="text-rose-600 text-[10.5px] mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section: Format Persuratan & Legalitas Digital --}}
                        <div class="space-y-3 pt-2">
                            <h4 class="font-bold text-[#0B3A85] uppercase tracking-wider text-[11px] flex items-center gap-1.5 pb-1 border-b border-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>3. Format Surat & Berkas Digital</span>
                            </h4>

                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">
                                    Format Penomoran Surat Resmi <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="format_nomor_surat" 
                                    placeholder="{NO}/RT.15/PMS/{BULAN_ROMAWI}/{TAHUN}" 
                                    class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] font-mono focus:outline-none focus:border-[#0B3A85]"
                                >
                                <p class="text-[10px] text-slate-400 mt-1">Variabel otomatis yang didukung: <span class="font-mono text-[#0B3A85] font-semibold">{NO}</span>, <span class="font-mono text-[#0B3A85] font-semibold">{BULAN_ROMAWI}</span>, <span class="font-mono text-[#0B3A85] font-semibold">{TAHUN}</span></p>
                                @error('format_nomor_surat') <p class="text-rose-600 text-[10.5px] mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                                {{-- Upload Tanda Tangan --}}
                                <div class="border border-slate-200 rounded-xl p-3 bg-slate-50/50">
                                    <label class="block font-bold text-slate-700 text-[11px] mb-1">Tanda Tangan Digital Ketua RT</label>
                                    @if($existing_tanda_tangan && \Illuminate\Support\Facades\Storage::disk('public')->exists($existing_tanda_tangan))
                                        <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-slate-200 mb-2">
                                            <img src="{{ asset('storage/' . $existing_tanda_tangan) }}" alt="TTD" class="max-h-10 object-contain">
                                            <button 
                                                type="button" 
                                                wire:click="removeTtd" 
                                                class="text-rose-600 hover:text-rose-800 text-[11px] font-bold cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    @endif
                                    <input 
                                        type="file" 
                                        wire:model="tanda_tangan" 
                                        accept="image/png,image/jpeg,image/webp" 
                                        class="w-full text-[11px] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10.5px] file:font-bold file:bg-[#0B3A85] file:text-white cursor-pointer"
                                    >
                                    <p class="text-[9.5px] text-slate-400 mt-1">PNG transparan dianjurkan. Maks 2MB.</p>
                                    @error('tanda_tangan') <p class="text-rose-600 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                </div>

                                {{-- Upload Stempel --}}
                                <div class="border border-slate-200 rounded-xl p-3 bg-slate-50/50">
                                    <label class="block font-bold text-slate-700 text-[11px] mb-1">Stempel Resmi RT 15</label>
                                    @if($existing_stempel && \Illuminate\Support\Facades\Storage::disk('public')->exists($existing_stempel))
                                        <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-slate-200 mb-2">
                                            <img src="{{ asset('storage/' . $existing_stempel) }}" alt="Stempel" class="max-h-10 object-contain">
                                            <button 
                                                type="button" 
                                                wire:click="removeStempel" 
                                                class="text-rose-600 hover:text-rose-800 text-[11px] font-bold cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    @endif
                                    <input 
                                        type="file" 
                                        wire:model="stempel" 
                                        accept="image/png,image/jpeg,image/webp" 
                                        class="w-full text-[11px] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10.5px] file:font-bold file:bg-[#0B3A85] file:text-white cursor-pointer"
                                    >
                                    <p class="text-[9.5px] text-slate-400 mt-1">PNG transparan dianjurkan. Maks 2MB.</p>
                                    @error('stempel') <p class="text-rose-600 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        </div>

                        {{-- Footer Form Modal --}}
                        <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-2 shrink-0">
                            <button 
                                type="button" 
                                @click="formModalOpen = false; $wire.closeForm()" 
                                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors cursor-pointer"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                wire:loading.attr="disabled"
                                class="px-5 py-2 rounded-xl bg-[#0B3A85] text-white font-bold hover:bg-[#07275E] text-xs transition-all shadow-xs flex items-center gap-1.5 cursor-pointer"
                            >
                                <span wire:loading.remove wire:target="saveSetting">Simpan Perubahan</span>
                                <span wire:loading wire:target="saveSetting">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
