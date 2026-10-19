<x-filament-panels::page>
    <div 
        x-data="{
            detailResidentModalOpen: @entangle('detailResidentModalOpen'),
            imagePreviewModalOpen: false,
            previewImageUrl: null,
            modalDokumenOpen: @entangle('editDokumenModalOpen'),
            modalKepalaOpen: @entangle('editKepalaModalOpen'),
            modalAkunOpen: @entangle('editAkunModalOpen'),
            modalEditResidentOpen: @entangle('editResidentModalOpen'),
            deleteConfirmModalOpen: false,
            deleteResidentId: null,
            deleteResidentName: '',
            openImagePreview(url) {
                this.previewImageUrl = url;
                this.imagePreviewModalOpen = true;
            },
            closeImagePreview() {
                this.imagePreviewModalOpen = false;
                this.previewImageUrl = null;
            },
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
        @close-dokumen-modal.window="modalDokumenOpen = false"
        @close-kepala-modal.window="modalKepalaOpen = false"
        @close-akun-modal.window="modalAkunOpen = false"
        @close-edit-resident-modal.window="modalEditResidentOpen = false"
        @open-dokumen-modal.window="modalDokumenOpen = true"
        @open-kepala-modal.window="modalKepalaOpen = true"
        @open-akun-modal.window="modalAkunOpen = true"
        @open-edit-resident-modal.window="modalEditResidentOpen = true"
        @close-detail-resident-modal.window="detailResidentModalOpen = false"
        @open-detail-resident-modal.window="detailResidentModalOpen = true"
        @keydown.escape.window="closeImagePreview(); closeDeleteConfirm(); detailResidentModalOpen = false; modalDokumenOpen = false; modalKepalaOpen = false; modalAkunOpen = false; modalEditResidentOpen = false; if ($wire.detailResidentModalOpen) $wire.closeDetailResident(); if ($wire.editDokumenModalOpen) $wire.closeEditDokumen(); if ($wire.editKepalaModalOpen) $wire.closeEditKepala(); if ($wire.editAkunModalOpen) $wire.closeEditAkun(); if ($wire.editResidentModalOpen) $wire.closeEditResident();" 
        class="w-full space-y-4"
    >
        {{-- ====================================================================
             1. HEADER DETAIL KARTU KELUARGA
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-2 border-b border-slate-200">
            <div>
                {{-- Kategori Subtitle & Panduan Halaman --}}
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <a href="{{ url('/admin/households') }}" class="hover:underline">
                        Data Kartu Keluarga
                    </a>
                    <x-page-guide 
                        title="Detail Kartu Keluarga & Anggota"
                        category="KEPENDUDUKAN RT 15"
                        description="Halaman rincian lengkap satu Kartu Keluarga (KK). Menampilkan data kepala keluarga, susunan seluruh anggota keluarga, alamat rumah, nomor kontak, serta berkas scan dokumen KK resmi."
                        :steps="[
                            ['title' => 'Kelola Anggota Keluarga', 'desc' => 'Klik \'+ Tambah Anggota\' di tabel anggota untuk mendaftarkan anggota keluarga baru ke dalam KK ini.'],
                            ['title' => 'Ubah Kepala Keluarga', 'desc' => 'Klik tombol \'Ubah Kepala KK\' pada kartu kepala keluarga jika terjadi pergantian kepala rumah tangga.'],
                            ['title' => 'Unggah Dokumen KK', 'desc' => 'Klik \'Kelola Dokumen\' untuk memperbarui atau mengunggah scan foto berkas Kartu Keluarga asli.'],
                            ['title' => 'Aksi Individu Anggota', 'desc' => 'Gunakan tombol pada tabel anggota untuk melihat detail data warga, mengedit identitas, atau menghapus relasi anggota.']
                        ]"
                        role="Sekretaris RT & Ketua RT"
                        :tips="[
                            'Satu Kartu Keluarga hanya boleh memiliki satu Kepala Keluarga yang aktif.',
                            'Warga yang dihapus dari KK ini akan tetap tersimpan di database warga namun tanpa ikatan nomor KK.',
                            'Periksa keabsahan berkas KK asli sebelum memverifikasi akun login kepala keluarga.'
                        ]"
                    />
                </div>

                {{-- Judul Halaman & Status Badge --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if($household->id_kk)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-extrabold bg-[#0B3A85] text-white shadow-2xs">
                            {{ $household->id_kk }}
                        </span>
                    @endif
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                        KK: {{ $household->masked_no_kk }}
                    </h1>
                </div>
            </div>

            {{-- Tombol Aksi di Header (Kembali & Ubah) --}}
            <div class="flex items-center gap-2 shrink-0">
                <a 
                    href="{{ url('/admin/households') }}" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-[#0F1F3D] border border-[#CBD5E1] hover:bg-[#F8FAFC] hover:border-[#0B3A85] hover:text-[#0B3A85] transition-all text-xs font-semibold shadow-2xs cursor-pointer group"
                    title="Kembali ke Daftar Kartu Keluarga"
                >
                    <svg class="w-3.5 h-3.5 text-[#5B6B82] group-hover:text-[#0B3A85] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali</span>
                </a>

                <a 
                    href="{{ url('/admin/households/' . $household->id . '/edit') }}" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs hover:shadow-md cursor-pointer"
                >
                    Ubah Data KK
                </a>
            </div>
        </div>

        {{-- ====================================================================
             2. GRID KARTU INFORMASI DATA KK (VERTICAL ON TABLET/MOBILE, 3-COLS ON DESKTOP)
             ==================================================================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-3.5">
            {{-- KARTU 1: INFORMASI DOKUMEN KARTU KELUARGA --}}
            <div class="bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-2 mb-1 border-b border-slate-100 gap-2">
                        <h3 class="font-bold text-[#0F1F3D] text-xs sm:text-sm">Dokumen Kartu Keluarga</h3>
                        <button 
                            type="button" 
                            @click="modalDokumenOpen = true"
                            wire:click="openEditDokumen"
                            class="inline-flex items-center justify-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold text-[#0B3A85] bg-blue-50 border border-blue-200 hover:bg-[#0B3A85] hover:text-white transition-all cursor-pointer shadow-2xs shrink-0"
                            title="Ubah Dokumen Kartu Keluarga"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Ubah</span>
                        </button>
                    </div>

                    <dl class="divide-y divide-slate-100 text-xs">
                        {{-- Nomor KK Resmi --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Nomor KK</dt>
                            <dd class="flex items-center gap-1.5">
                                <span class="font-mono font-bold text-slate-900 select-all">{{ $household->no_kk }}</span>
                                <button 
                                    type="button" 
                                    onclick="navigator.clipboard.writeText('{{ $household->no_kk }}'); alert('Nomor KK berhasil disalin ke clipboard!');" 
                                    class="px-1.5 py-0.5 rounded text-[10px] font-semibold text-[#0B3A85] bg-blue-50 hover:bg-blue-100 transition-colors cursor-pointer" 
                                    title="Salin Nomor KK"
                                >
                                    Salin
                                </button>
                            </dd>
                        </div>

                        {{-- Alamat Rumah --}}
                        <div class="py-1.5 flex items-start justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Alamat</dt>
                            <dd class="font-semibold text-slate-900 text-right">
                                {{ $household->alamat ?? '-' }}
                            </dd>
                        </div>

                        {{-- RT / RW --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">RT / RW</dt>
                            <dd class="font-semibold text-slate-900 text-right">
                                {{ $household->rt_rw ?? 'RT 15 / RW 04' }}
                            </dd>
                        </div>

                        {{-- Kelurahan / Kecamatan --}}
                        <div class="py-1.5 flex items-start justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Wilayah</dt>
                            <dd class="font-semibold text-slate-800 text-right">
                                {{ $household->kelurahan ?? 'Pematang Sulur' }}, {{ $household->kecamatan ?? 'Telanaipura' }}
                            </dd>
                        </div>

                        {{-- Status Hunian --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Status Hunian</dt>
                            <dd class="text-right">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10.5px] font-bold {{ $household->status_hunian === 'tetap' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    Rumah {{ ucfirst($household->status_hunian ?? 'Tetap') }}
                                </span>
                            </dd>
                        </div>

                        {{-- File Kartu Keluarga --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">File Kartu Keluarga</dt>
                            <dd class="text-right">
                                @if($household->foto_kk)
                                    <div class="inline-flex items-center gap-1 justify-end">
                                        <button 
                                            type="button" 
                                            @click="openImagePreview('{{ $household->foto_kk_url }}')"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold text-[#0B3A85] bg-blue-50 border border-blue-200 hover:bg-[#0B3A85] hover:text-white transition-all cursor-pointer shadow-2xs"
                                            title="Lihat Berkas Foto Kartu Keluarga"
                                        >
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>Lihat File</span>
                                        </button>
                                        <a 
                                            href="{{ $household->foto_kk_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer" 
                                            class="p-0.5 rounded text-slate-400 hover:text-[#0B3A85] hover:bg-slate-100 transition-colors"
                                            title="Buka Berkas di Tab Baru"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <span class="font-bold text-slate-400 text-xs">-</span>
                                @endif
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>

            {{-- KARTU 2: IDENTITAS KEPALA KELUARGA --}}
            <div class="bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-2 mb-1 border-b border-slate-100 gap-2">
                        <h3 class="font-bold text-[#0F1F3D] text-xs sm:text-sm">Kepala Keluarga</h3>
                        <button 
                            type="button" 
                            @click="modalKepalaOpen = true"
                            wire:click="openEditKepala"
                            class="inline-flex items-center justify-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold text-[#0B3A85] bg-blue-50 border border-blue-200 hover:bg-[#0B3A85] hover:text-white transition-all cursor-pointer shadow-2xs shrink-0"
                            title="Ubah Data Kepala Keluarga"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Ubah</span>
                        </button>
                    </div>

                    <dl class="divide-y divide-slate-100 text-xs">
                        {{-- Nama Lengkap --}}
                        <div class="py-1.5 flex items-start justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Nama Lengkap</dt>
                            <dd class="font-extrabold text-[#0F1F3D] text-right">
                                {{ $household->kepalaKeluarga?->nama ?? '- Belum Ditentukan -' }}
                            </dd>
                        </div>

                        {{-- NIK --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">NIK</dt>
                            <dd class="font-mono font-bold text-slate-900 text-right select-all">
                                {{ $household->kepalaKeluarga?->nik ?? '-' }}
                            </dd>
                        </div>

                        {{-- Kontak WhatsApp / Telepon --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Telepon / WA</dt>
                            <dd class="text-right">
                                @if($household->kepalaKeluarga?->telepon)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $household->kepalaKeluarga->telepon);
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $waNumber = '62' . substr($cleanPhone, 1);
                                        } else {
                                            $waNumber = $cleanPhone;
                                        }
                                    @endphp
                                    <a 
                                        href="https://wa.me/{{ $waNumber }}" 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1 text-[#1B7A3A] font-bold hover:underline whitespace-nowrap"
                                    >
                                        <span>{{ $household->kepalaKeluarga->telepon }}</span>
                                        <span class="text-[10px] text-slate-400 font-normal shrink-0">(Chat WA)</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Belum ada telepon</span>
                                @endif
                            </dd>
                        </div>

                        {{-- Tempat, Tanggal Lahir & Usia --}}
                        <div class="py-1.5 flex items-start justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">TTL & Usia</dt>
                            <dd class="font-medium text-slate-800 text-right">
                                {{ $household->kepalaKeluarga?->tempat_lahir ?? 'Jambi' }}, 
                                {{ $household->kepalaKeluarga?->tanggal_lahir ? $household->kepalaKeluarga->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                                @if($household->kepalaKeluarga?->tanggal_lahir)
                                    <span class="text-slate-400 text-[10.5px]">({{ $household->kepalaKeluarga->tanggal_lahir->age }} thn)</span>
                                @endif
                            </dd>
                        </div>

                        {{-- Pekerjaan --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Pekerjaan</dt>
                            <dd class="font-medium text-slate-800 text-right">
                                {{ $household->kepalaKeluarga?->pekerjaan ?? '-' }}
                            </dd>
                        </div>

                        {{-- Agama --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Agama</dt>
                            <dd class="font-medium text-slate-800 text-right">
                                {{ $household->kepalaKeluarga?->agama ?? 'Islam' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- KARTU 3: RINGKASAN DATA KEPENDUDUKAN KK --}}
            @php
                $resList = $household->residents;
                $totalJiwa = $resList->count();
                $totalLaki = $resList->where('jenis_kelamin', 'L')->count();
                $totalPerempuan = $resList->where('jenis_kelamin', 'P')->count();
                $totalIstri = $resList->where('hubungan_keluarga', 'istri')->count();
                $totalAnak = $resList->where('hubungan_keluarga', 'anak')->count();
                $totalFamili = $resList->whereNotIn('hubungan_keluarga', ['kepala_keluarga', 'istri', 'anak'])->count();
            @endphp
            <div class="bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                <div>
                    {{-- Header Ringkasan: Murni ringkasan data KK, tanpa tombol ubah --}}
                    <div class="flex items-center justify-between pb-2 mb-1 border-b border-slate-100 gap-2">
                        <div class="flex items-center gap-1.5">
                            <h3 class="font-bold text-[#0F1F3D] text-xs sm:text-sm">Ringkasan Kependudukan</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-slate-100 text-slate-600 border border-slate-200/80">
                            Statistik KK
                        </span>
                    </div>

                    <dl class="divide-y divide-slate-100 text-xs">
                        {{-- 1. Total Anggota --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Total Jiwa</dt>
                            <dd class="font-extrabold text-[#0B3A85] text-right">
                                {{ $totalJiwa }} Jiwa Terdaftar
                            </dd>
                        </div>

                        {{-- 2. Komposisi Gender --}}
                        <div class="py-1.5 flex items-center justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Komposisi Gender</dt>
                            <dd class="font-semibold text-slate-800 text-right">
                                <span class="text-[#0B3A85] font-bold">{{ $totalLaki }} L</span>
                                <span class="text-slate-300 mx-1">&bull;</span>
                                <span class="text-[#E11D48] font-bold">{{ $totalPerempuan }} P</span>
                            </dd>
                        </div>

                        {{-- 3. Struktur Hubungan Keluarga --}}
                        <div class="py-1.5 flex items-start justify-between gap-2.5">
                            <dt class="text-slate-500 font-medium shrink-0 text-[11.5px]">Struktur Keluarga</dt>
                            <dd class="font-medium text-slate-800 text-right">
                                <span>1 Kepala</span>
                                @if($totalIstri > 0)
                                    <span class="text-slate-300 mx-1">&bull;</span>
                                    <span>{{ $totalIstri }} Istri</span>
                                @endif
                                @if($totalAnak > 0)
                                    <span class="text-slate-300 mx-1">&bull;</span>
                                    <span>{{ $totalAnak }} Anak</span>
                                @endif
                                @if($totalFamili > 0)
                                    <span class="text-slate-300 mx-1">&bull;</span>
                                    <span>{{ $totalFamili }} Famili</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             3. TABEL DAFTAR ANGGOTA KELUARGA
             ==================================================================== --}}
        <div class="bg-white rounded-xl shadow-2xs border border-slate-200/90 overflow-hidden">
            {{-- Header Bagian Tabel --}}
            <div class="px-4 py-2.5 sm:px-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 border-b border-slate-100">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-[#0F1F3D] tracking-tight">
                        Daftar Anggota Keluarga
                    </h2>
                    <p class="text-[11px] sm:text-xs text-[#5B6B82] flex flex-wrap items-center gap-1">
                        <span>Seluruh jiwa yang terdaftar di dalam Kartu Keluarga ini.</span>
                        <span class="inline-flex items-center gap-1 text-[#0B3A85] font-semibold sm:hidden">
                            <span>&bull; Geser tabel ke kanan &rarr;</span>
                        </span>
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF]">
                        Total: {{ $household->residents->count() }} Jiwa Terdaftar
                    </span>
                </div>
            </div>

            {{-- Tabel Anggota Keluarga --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1010px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0B3A85] text-white text-[10.5px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3 text-center w-12 min-w-[48px] whitespace-nowrap">NO</th>
                            <th class="py-2.5 px-3 text-center min-w-[100px] whitespace-nowrap">ID WARGA</th>
                            <th class="py-2.5 px-3 min-w-[200px] whitespace-nowrap">NAMA LENGKAP</th>
                            <th class="py-2.5 px-3 text-center min-w-[140px] whitespace-nowrap">STATUS KELUARGA</th>
                            <th class="py-2.5 px-3 text-center w-14 min-w-[50px] whitespace-nowrap">L/P</th>
                            <th class="py-2.5 px-3 min-w-[195px] whitespace-nowrap">NO. TELEPON / WA</th>
                            <th class="py-2.5 px-3 text-center min-w-[90px] whitespace-nowrap">STATUS</th>
                            <th class="py-2.5 px-3 text-center min-w-[80px] whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9] text-xs">
                        @forelse($residents as $resident)
                            <tr class="hover:bg-[#F8FAFC] transition-colors {{ $resident->is_kepala_keluarga ? 'bg-blue-50/20' : '' }}">
                                {{-- 1. NO --}}
                                <td class="py-2.5 px-3 text-center text-xs font-semibold text-[#5B6B82] whitespace-nowrap">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- 2. ID WARGA --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF] whitespace-nowrap">
                                        {{ $resident->id_warga ?? '-' }}
                                    </span>
                                </td>

                                {{-- 3. NAMA LENGKAP --}}
                                <td class="py-2.5 px-3 min-w-[200px]">
                                    <div class="font-bold text-xs sm:text-sm text-[#0F1F3D] whitespace-nowrap">
                                        {{ $resident->nama }}
                                    </div>
                                    <div class="text-[10.5px] font-mono text-[#5B6B82] mt-0.5 select-all whitespace-nowrap">
                                        NIK: {{ $resident->masked_nik ?? '-' }}
                                    </div>
                                </td>

                                {{-- 4. STATUS KELUARGA --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @php
                                        $relLabel = ucwords(str_replace('_', ' ', $resident->hubungan_keluarga ?? ($resident->is_kepala_keluarga ? 'Kepala Keluarga' : '-')));
                                        $isKepala = $resident->is_kepala_keluarga || strtolower($resident->hubungan_keluarga ?? '') === 'kepala_keluarga';
                                    @endphp
                                    @if($isKepala)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF]">
                                            Kepala Keluarga
                                        </span>
                                    @elseif(strtolower($resident->hubungan_keluarga ?? '') === 'istri')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Istri
                                        </span>
                                    @elseif(strtolower($resident->hubungan_keluarga ?? '') === 'anak')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                            Anak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $relLabel }}
                                        </span>
                                    @endif
                                </td>

                                {{-- 4. L/P --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
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

                                {{-- 5. NO. TELEPON / WA --}}
                                <td class="py-2.5 px-3 text-xs whitespace-nowrap min-w-[195px]">
                                    @if($resident->telepon && $resident->wa_url)
                                        <a 
                                            href="{{ $resident->wa_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1 text-[#1B7A3A] font-bold hover:underline whitespace-nowrap cursor-pointer"
                                            title="Klik untuk Chat WhatsApp ke {{ $resident->nama }} ({{ $resident->telepon }})"
                                        >
                                            <span class="select-all font-mono">{{ $resident->telepon }}</span>
                                            <span class="text-[10px] text-slate-400 font-normal shrink-0">(Chat WA)</span>
                                        </a>
                                    @elseif($resident->telepon)
                                        <span class="select-all font-mono font-medium text-slate-700 whitespace-nowrap">{{ $resident->telepon }}</span>
                                    @else
                                        <span class="text-slate-400 font-sans italic">-</span>
                                    @endif
                                </td>

                                {{-- 6. STATUS --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($resident->status_kependudukan === 'aktif')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ ucfirst($resident->status_kependudukan ?? 'Aktif') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- 7. AKSI --}}
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
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
                                <td colspan="8" class="py-8 text-center text-xs text-[#5B6B82]">
                                    <p class="font-bold text-[#0F1F3D]">Belum Ada Anggota Keluarga</p>
                                    <p class="text-[11px] text-[#94A3B8] mt-0.5">Gunakan tombol "Ubah Data KK" untuk menambahkan anggota keluarga.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Tabel --}}
            <div class="py-2.5 px-4 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between text-xs text-[#5B6B82]">
                <div>
                    Menampilkan <strong>{{ $residents->count() }}</strong> jiwa terdaftar di Kartu Keluarga ini.
                </div>
            </div>
        </div>
        {{-- ====================================================================
             4. MODAL POPUP DETAIL ANGGOTA KELUARGA
             ==================================================================== --}}
        @include('filament.resources.residents.components.detail-modal')

        {{-- ====================================================================
             5. MODAL POPUP EDIT DOKUMEN KARTU KELUARGA
             ==================================================================== --}}
        <div 
            x-show="modalDokumenOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-dokumen-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="modalDokumenOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="modalDokumenOpen = false; $wire.closeEditDokumen()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="modalDokumenOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-xl border border-slate-200"
                    @click.stop
                >
                    {{-- Header Modal --}}
                    <div class="bg-[#0B3A85] text-white px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white border border-white/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-white leading-tight" id="modal-dokumen-title">
                                    Ubah Dokumen Kartu Keluarga
                                </h3>
                                <p class="text-xs text-blue-200 mt-0.5">
                                    Perbarui nomor KK, alamat, status hunian, dan foto fisik KK.
                                </p>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="modalDokumenOpen = false; $wire.closeEditDokumen()" 
                            class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                            title="Tutup dialog"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Skeleton Loader saat openEditDokumen --}}
                    <div wire:loading.flex wire:target="openEditDokumen" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-4 bg-slate-200 rounded w-1/4"></div>
                        <div class="h-10 bg-slate-100 rounded-xl"></div>
                        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                        <div class="h-10 bg-slate-100 rounded-xl"></div>
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openEditDokumen">
                        {{-- Form Body --}}
                        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        {{-- Nomor Kartu Keluarga --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nomor Kartu Keluarga (16 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="dokumen_no_kk" 
                                maxlength="16"
                                placeholder="Contoh: 1571012345678901"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 font-mono focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                            />
                            @error('dokumen_no_kk')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat Rumah --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Alamat Tempat Tinggal <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                wire:model="dokumen_alamat" 
                                rows="2"
                                placeholder="Contoh: Jl. Lintas Jambi No. 45"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                            ></textarea>
                            @error('dokumen_alamat')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Baris RT/RW dan Status Hunian --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    RT / RW
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="dokumen_rt_rw" 
                                    placeholder="Contoh: RT 15 / RW 04"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                />
                                @error('dokumen_rt_rw')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Status Kepemilikan Hunian <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    wire:model="dokumen_status_hunian" 
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                >
                                    <option value="tetap">Rumah Milik Sendiri (Tetap)</option>
                                    <option value="kontrak">Rumah Sewa / Kontrak</option>
                                    <option value="kos">Tempat Kos</option>
                                </select>
                                @error('dokumen_status_hunian')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Foto Dokumen KK --}}
                        <div class="pt-2 border-t border-slate-100">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Lampiran Foto / Scan Kartu Keluarga
                            </label>

                            @if($existing_foto_kk && !$delete_foto_kk)
                                <div class="mb-3 p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5 overflow-hidden">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-[#0B3A85] shrink-0 font-bold text-xs">
                                            KK
                                        </div>
                                        <div class="truncate">
                                            <div class="text-xs font-bold text-slate-800">Dokumen KK Saat Ini</div>
                                            <a href="{{ $existing_foto_kk_url }}" target="_blank" class="text-[11px] text-[#0B3A85] hover:underline">
                                                Lihat Foto &rarr;
                                            </a>
                                        </div>
                                    </div>
                                    <button 
                                        type="button" 
                                        wire:click="removeExistingFotoKk"
                                        class="px-2.5 py-1 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-lg border border-rose-200 transition-colors shrink-0"
                                    >
                                        Hapus Foto
                                    </button>
                                </div>
                            @endif

                            @if($dokumen_foto_kk)
                                <div class="mb-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5 overflow-hidden">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 shrink-0 font-bold text-xs">
                                            BARU
                                        </div>
                                        <div class="truncate">
                                            <div class="text-xs font-bold text-emerald-900">File Baru Siap Diunggah</div>
                                            <div class="text-[11px] text-emerald-700">{{ is_object($dokumen_foto_kk) && method_exists($dokumen_foto_kk, 'getClientOriginalName') ? $dokumen_foto_kk->getClientOriginalName() : 'Berkas foto baru' }}</div>
                                        </div>
                                    </div>
                                    <button 
                                        type="button" 
                                        wire:click="removeDokumenFotoKk"
                                        class="px-2.5 py-1 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-lg border border-rose-200 transition-colors shrink-0"
                                    >
                                        Batal
                                    </button>
                                </div>
                            @endif

                            <div class="mt-1">
                                <input 
                                    type="file" 
                                    wire:model="dokumen_foto_kk" 
                                    accept="image/*,application/pdf"
                                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#0B3A85] hover:file:bg-blue-100 cursor-pointer"
                                />
                                <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP, PDF (Maksimal 10 MB).</p>
                                <div wire:loading wire:target="dokumen_foto_kk" class="text-xs text-blue-600 font-semibold mt-1 flex items-center gap-1.5">
                                    <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Sedang mengunggah berkas...</span>
                                </div>
                            </div>
                            @error('dokumen_foto_kk')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button 
                            type="button" 
                            @click="modalDokumenOpen = false; $wire.closeEditDokumen()" 
                            class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            wire:click="saveDokumen" 
                            wire:loading.attr="disabled"
                            wire:target="dokumen_foto_kk,saveDokumen"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs hover:shadow-md cursor-pointer disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="dokumen_foto_kk,saveDokumen">Simpan Perubahan</span>
                            <span wire:loading wire:target="dokumen_foto_kk">Mengunggah File...</span>
                            <span wire:loading wire:target="saveDokumen">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>

        {{-- ====================================================================
             6. MODAL POPUP EDIT KEPALA KELUARGA
             ==================================================================== --}}
        <div 
            x-show="modalKepalaOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-kepala-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="modalKepalaOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="modalKepalaOpen = false; $wire.closeEditKepala()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="modalKepalaOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-2xl border border-slate-200"
                    @click.stop
                >
                    {{-- Header Modal --}}
                    <div class="bg-[#0B3A85] text-white px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white border border-white/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-white leading-tight" id="modal-kepala-title">
                                    Ubah Identitas Kepala Keluarga
                                </h3>
                                <p class="text-xs text-blue-200 mt-0.5">
                                    Perbarui biodata dan profil kepala keluarga penanggung jawab KK.
                                </p>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="modalKepalaOpen = false; $wire.closeEditKepala()" 
                            class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                            title="Tutup dialog"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Skeleton Loader saat openEditKepala --}}
                    <div wire:loading.flex wire:target="openEditKepala" class="p-6 flex-col space-y-4 animate-pulse">
                        <div class="h-4 bg-slate-200 rounded w-1/4"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                            <div class="h-10 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="h-20 bg-slate-100 rounded-xl"></div>
                    </div>

                    <div wire:loading.remove wire:target="openEditKepala">
                        {{-- Form Body --}}
                        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="kepala_nama" 
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 font-semibold focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                />
                                @error('kepala_nama')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- NIK --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    NIK (16 Digit) <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="kepala_nik" 
                                    maxlength="16"
                                    placeholder="Contoh: 1571012304850001"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 font-mono focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                />
                                @error('kepala_nik')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tempat & Tanggal Lahir --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Tempat Lahir
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="kepala_tempat_lahir" 
                                    placeholder="Contoh: Jambi"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                />
                                @error('kepala_tempat_lahir')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Tanggal Lahir
                                </label>
                                <input 
                                    type="date" 
                                    wire:model="kepala_tanggal_lahir" 
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                />
                                @error('kepala_tanggal_lahir')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Agama & Pendidikan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Agama
                                </label>
                                <select 
                                    wire:model="kepala_agama" 
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                >
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen Protestan</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                                @error('kepala_agama')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Pendidikan Terakhir
                                </label>
                                <select 
                                    wire:model="kepala_pendidikan_terakhir" 
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                >
                                    <option value="Tidak / Belum Sekolah">Tidak / Belum Sekolah</option>
                                    <option value="SD / Sederajat">SD / Sederajat</option>
                                    <option value="SMP / Sederajat">SMP / Sederajat</option>
                                    <option value="SMA / SMK / Sederajat">SMA / SMK / Sederajat</option>
                                    <option value="Diploma (D1 - D3)">Diploma (D1 - D3)</option>
                                    <option value="Sarjana (S1)">Sarjana (S1)</option>
                                    <option value="Magister (S2)">Magister (S2)</option>
                                    <option value="Doktor (S3)">Doktor (S3)</option>
                                </select>
                                @error('kepala_pendidikan_terakhir')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Pekerjaan --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Pekerjaan
                            </label>
                            <input 
                                type="text" 
                                wire:model="kepala_pekerjaan" 
                                placeholder="Contoh: Karyawan Swasta, Wiraswasta, PNS, Petani, dll"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                            />
                            @error('kepala_pekerjaan')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kontak Telepon & Email --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nomor Telepon / WhatsApp
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="kepala_telepon" 
                                    placeholder="Contoh: 081234567890"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                />
                                @error('kepala_telepon')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Alamat Email (Opsional)
                                </label>
                                <input 
                                    type="email" 
                                    wire:model="kepala_email" 
                                    placeholder="Contoh: budi@gmail.com"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                />
                                @error('kepala_email')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Kata Sandi Akun Login Portal --}}
                        <div x-data="{ showModalKepalaPass: false }" class="p-3 bg-blue-50/50 rounded-xl border border-blue-100">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-slate-700">
                                    Kata Sandi Akun Portal <span class="text-slate-400 font-normal">(Opsional)</span>
                                </label>
                                <button 
                                    type="button" 
                                    wire:click="$set('kepala_password', 'password')"
                                    class="text-[10px] font-bold text-[#0B3A85] hover:underline cursor-pointer"
                                >
                                    Reset "password"
                                </button>
                            </div>
                            <div class="relative">
                                <input 
                                    :type="showModalKepalaPass ? 'text' : 'password'" 
                                    wire:model="kepala_password" 
                                    placeholder="Kosongkan jika tidak ingin mengubah kata sandi"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 font-mono focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors pr-10"
                                />
                                <button 
                                    type="button" 
                                    @click="showModalKepalaPass = !showModalKepalaPass"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                    title="Lihat / Sembunyikan Kata Sandi"
                                >
                                    <svg x-show="!showModalKepalaPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showModalKepalaPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </button>
                            </div>
                            @error('kepala_password')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @else
                                <p class="text-[10px] text-slate-400 mt-1">* Kosongkan jika tidak ingin diubah. Minimal 6 karakter.</p>
                            @enderror
                        </div>

                        {{-- Keterangan Otomatis --}}
                        <div class="bg-blue-50/70 border border-blue-200/70 rounded-xl p-3 text-xs text-[#0B3A85] flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Status Perkawinan otomatis <strong>Kawin</strong> dan Jenis Kelamin <strong>Laki-laki</strong> sesuai peran Kepala Keluarga.</span>
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button 
                            type="button" 
                            @click="modalKepalaOpen = false; $wire.closeEditKepala()" 
                            class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            wire:click="saveKepala" 
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs hover:shadow-md cursor-pointer disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="saveKepala">Simpan Perubahan</span>
                            <span wire:loading wire:target="saveKepala">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>

        {{-- ====================================================================
             7. MODAL POPUP EDIT AKUN PORTAL WARGA
             ==================================================================== --}}
        <div 
            x-show="modalAkunOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-akun-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="modalAkunOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="modalAkunOpen = false; $wire.closeEditAkun()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="modalAkunOpen"
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
                    <div class="bg-[#0B3A85] text-white px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white border border-white/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-white leading-tight" id="modal-akun-title">
                                    Ubah Akun Portal Warga
                                </h3>
                                <p class="text-xs text-blue-200 mt-0.5">
                                    Kelola kredensial akun login warga untuk aplikasi mandiri.
                                </p>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="modalAkunOpen = false; $wire.closeEditAkun()" 
                            class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                            title="Tutup dialog"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Form Body --}}
                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        {{-- Nama Akun --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Pengguna Akun <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="akun_name" 
                                placeholder="Contoh: Budi Santoso"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 font-semibold focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                            />
                            @error('akun_name')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email Login Portal --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Alamat Email Login <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                wire:model="akun_email" 
                                placeholder="Contoh: budi@warga.simlibas.id"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 font-mono focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                            />
                            @error('akun_email')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor HP & Status Akun --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nomor Telepon / WhatsApp
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="akun_phone" 
                                    placeholder="Contoh: 081234567890"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                />
                                @error('akun_phone')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Status Akun <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    wire:model="akun_status" 
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                                >
                                    <option value="aktif">Aktif (Dapat Login)</option>
                                    <option value="nonaktif">Nonaktif (Dibekukan)</option>
                                </select>
                                @error('akun_status')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Ubah Password (Opsional) --}}
                        <div class="pt-2 border-t border-slate-100">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Password Baru (Opsional)
                            </label>
                            <input 
                                type="password" 
                                wire:model="akun_password" 
                                placeholder="Masukkan password baru (minimal 6 karakter)"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0B3A85] focus:outline-none focus:ring-1 focus:ring-[#0B3A85] transition-colors"
                            />
                            <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika tidak ingin mereset password akun warga.</p>
                            @error('akun_password')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button 
                            type="button" 
                            @click="modalAkunOpen = false; $wire.closeEditAkun()" 
                            class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            wire:click="saveAkun" 
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs hover:shadow-md cursor-pointer disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="saveAkun">Simpan Akun</span>
                            <span wire:loading wire:target="saveAkun">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             8. MODAL POPUP PREVIEW FOTO / BERKAS KARTU KELUARGA
             ==================================================================== --}}
        <div 
            x-show="imagePreviewModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-preview-title" 
            role="dialog" 
            aria-modal="true"
        >
            {{-- Backdrop Blur --}}
            <div 
                x-show="imagePreviewModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity cursor-pointer"
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click="closeImagePreview()"
            ></div>

            {{-- Dialog Container --}}
            <div class="flex min-h-full items-center justify-center p-3 sm:p-6 text-center">
                <div 
                    x-show="imagePreviewModalOpen"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-4xl border border-slate-200"
                    @click.stop
                >
                    {{-- Header Modal --}}
                    <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white border border-white/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-white leading-tight" id="modal-preview-title">
                                    Berkas Kartu Keluarga (KK)
                                </h3>
                                <p class="text-[11px] text-blue-200">
                                    Nomor KK: {{ $household->no_kk }} &bull; {{ $household->kepalaKeluarga?->nama ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a 
                                :href="previewImageUrl" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-colors border border-white/20"
                                title="Buka berkas di jendela / tab baru"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                <span>Buka Full</span>
                            </a>
                            <button 
                                type="button" 
                                @click="closeImagePreview()" 
                                class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                                title="Tutup dialog"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- File/Image Container --}}
                    <div class="p-4 sm:p-6 bg-slate-900/90 flex items-center justify-center max-h-[75vh] overflow-auto">
                        <template x-if="previewImageUrl && !previewImageUrl.toLowerCase().includes('.pdf')">
                            <img 
                                :src="previewImageUrl" 
                                alt="Berkas Kartu Keluarga" 
                                class="max-h-[68vh] w-auto max-w-full rounded-xl object-contain shadow-md border border-slate-700"
                            />
                        </template>
                        <template x-if="previewImageUrl && previewImageUrl.toLowerCase().includes('.pdf')">
                            <div class="w-full h-[68vh] flex flex-col items-center">
                                <iframe 
                                    :src="previewImageUrl" 
                                    class="w-full h-full rounded-xl border border-slate-700 bg-white shadow-md"
                                ></iframe>
                            </div>
                        </template>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="bg-white px-5 py-3 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
                        <span>Klik di luar gambar atau tekan Escape untuk menutup.</span>
                        <button 
                            type="button" 
                            @click="closeImagePreview()" 
                            class="px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-all cursor-pointer"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================================
             9. MODAL POPUP EDIT DATA WARGA (ANGGOTA KELUARGA)
             ==================================================================== --}}
        @include('filament.resources.residents.components.edit-modal', ['isHouseholdView' => true])

        {{-- ====================================================================
             10. MODAL POPUP KONFIRMASI HAPUS DATA WARGA
             ==================================================================== --}}
        @include('filament.resources.residents.components.delete-modal')
    </div>
</x-filament-panels::page>
