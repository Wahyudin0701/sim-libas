<x-filament-panels::page>
    <div class="w-full space-y-4">
        
        {{-- ====================================================================
             1. HEADER & TOMBOL KEMBALI
             ==================================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 pb-2 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#0B3A85] mb-0.5">
                    <span>KEPENDUDUKAN RT 15</span>
                    <x-page-guide 
                        title="Panduan Tambah Kartu Keluarga"
                        category="KEPENDUDUKAN RT 15"
                        description="Formulir pendaftaran Kartu Keluarga (KK) baru beserta Kepala Keluarga dan anggota awal. Pastikan data yang dimasukkan sesuai dengan fisik dokumen Kartu Keluarga resmi."
                        :steps="[
                            ['title' => 'Isi Informasi KK', 'desc' => 'Masukkan 16 digit Nomor KK resmi, status kepemilikan rumah, dan alamat domisili lengkap di lingkungan RT 15.'],
                            ['title' => 'Input Kepala Keluarga', 'desc' => 'Lengkapi identitas Kepala Keluarga mencakup NIK (16 digit), nama lengkap, tanggal lahir, dan pekerjaan.'],
                            ['title' => 'Akun Login & Kontak', 'desc' => 'Masukkan email dan nomor WhatsApp aktif yang akan digunakan kepala keluarga untuk login portal warga.'],
                            ['title' => 'Unggah Berkas Scan KK', 'desc' => 'Lampirkan foto scan Kartu Keluarga asli (JPG/PNG/PDF) untuk arsip verifikasi kependudukan digital.']
                        ]"
                        role="Sekretaris RT & Ketua RT"
                        :tips="[
                            'Nomor KK dan NIK tidak boleh sama dengan data warga yang sudah terdaftar di sistem.',
                            'Password login otomatis dapat digenerate atau ditentukan manual untuk diserahkan ke kepala keluarga.',
                            'Anggota keluarga lainnya dapat ditambahkan nanti melalui halaman rincian Kartu Keluarga.'
                        ]"
                    />
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                    Tambah Kartu Keluarga Baru
                </h1>
            </div>

            <div class="flex items-center gap-2">
                <a 
                    href="{{ \App\Filament\Resources\Households\HouseholdResource::getUrl('index') }}" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#0F1F3D] border border-[#CBD5E1] hover:bg-[#F8FAFC] hover:border-[#0B3A85] hover:text-[#0B3A85] transition-all duration-150 text-xs font-bold shadow-2xs hover:shadow-xs cursor-pointer group"
                    title="Kembali ke Daftar Kartu Keluarga"
                >
                    <svg class="w-3.5 h-3.5 text-[#5B6B82] group-hover:text-[#0B3A85] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        {{-- ====================================================================
             FORMULIR PENDAFTARAN
             ==================================================================== --}}
        <form wire:submit.prevent="saveHousehold" class="space-y-4">

            {{-- ============================================================
                 BAGIAN 1: INFORMASI KARTU KELUARGA (KK) & ALAMAT
                 ============================================================ --}}
            <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="px-4 py-3 sm:px-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-[#0B3A85] text-white flex items-center justify-center text-xs font-extrabold shrink-0">1</span>
                        <div>
                            <h2 class="text-sm font-extrabold text-[#0F1F3D] tracking-tight">Informasi Kartu Keluarga</h2>
                            <p class="text-[11px] text-[#5B6B82]">Nomor dokumen KK resmi dan lokasi tempat tinggal di RT 15.</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-5 space-y-3.5">
                    {{-- 1. Nomor KK --}}
                    <div>
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                            Nomor Kartu Keluarga (KK) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="no_kk"
                            maxlength="16"
                            placeholder="Contoh: 1571012345670001" 
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('no_kk') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all font-mono"
                        >
                        @error('no_kk')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @else
                            <p class="text-[11px] text-[#5B6B82] mt-1">* 16 digit angka resmi dari dokumen Kartu Keluarga.</p>
                        @enderror
                    </div>

                    {{-- 2. Alamat Rumah Lengkap & Status Hunian --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Alamat Rumah Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="alamat"
                                placeholder="Contoh: Jl. Pinang Masak No. 12 Blok A, RT 15" 
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('alamat') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all font-medium"
                            >
                            @error('alamat')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Status Hunian <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                wire:model="status_hunian"
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('status_hunian') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-sm text-[#0F1F3D] bg-white focus:outline-none focus:ring-2 transition-all cursor-pointer font-medium"
                            >
                                <option value="tetap">Tetap (Milik Sendiri)</option>
                                <option value="kontrak">Kontrak / Sewa</option>
                                <option value="kos">Kos</option>
                            </select>
                            @error('status_hunian')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- 3. Upload Foto Dokumen KK --}}
                    <div>
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                            Upload Foto Dokumen KK <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>

                        <div 
                            x-data="{ isDropping: false }"
                            @dragover.prevent="isDropping = true"
                            @dragleave.prevent="isDropping = false"
                            @drop.prevent="isDropping = false"
                            class="relative"
                        >
                            <label 
                                for="foto_kk_input"
                                class="flex flex-col items-center justify-center w-full px-6 py-6 border-2 border-dashed rounded-2xl cursor-pointer transition-all duration-200"
                                :class="isDropping ? 'border-[#0B3A85] bg-blue-50/50 scale-[1.01]' : 'border-[#CBD5E1] bg-slate-50/60 hover:bg-slate-50 hover:border-[#0B3A85]'"
                            >
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-12 h-12 mb-3 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center text-[#0B3A85]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-[#0F1F3D] mb-1">
                                        <span class="text-[#0B3A85] hover:underline">Klik untuk pilih berkas</span> atau seret foto ke sini
                                    </p>
                                    <p class="text-[11px] text-[#5B6B82]">
                                        Format: JPG, JPEG, PNG, WEBP, atau PDF &bull; Maksimal ukuran 10 MB
                                    </p>
                                </div>

                                <input 
                                    id="foto_kk_input" 
                                    type="file" 
                                    wire:model="foto_kk" 
                                    accept="image/png,image/jpeg,image/jpg,image/webp,application/pdf" 
                                    class="hidden"
                                />
                            </label>

                            {{-- Loading State Saat Upload Berlangsung --}}
                            <div wire:loading wire:target="foto_kk" class="mt-2 flex items-center gap-2 text-xs font-semibold text-[#0B3A85]">
                                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Sedang mengunggah dan memproses foto KK...</span>
                            </div>

                            {{-- Preview Foto KK yang Berhasil Diunggah --}}
                            @if ($foto_kk && !is_string($foto_kk))
                                <div class="mt-3 p-3 bg-blue-50/60 border border-blue-200/80 rounded-xl flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        @php
                                            $isImage = false;
                                            try {
                                                $isImage = str_starts_with($foto_kk->getMimeType() ?? '', 'image/');
                                            } catch (\Exception $e) {
                                                $isImage = false;
                                            }
                                        @endphp

                                        @if ($isImage)
                                            <img src="{{ $foto_kk->temporaryUrl() }}" alt="Preview Foto KK" class="w-16 h-14 object-cover rounded-lg border border-blue-200 shrink-0 shadow-2xs">
                                        @else
                                            <div class="w-16 h-14 rounded-lg bg-white border border-blue-200 flex items-center justify-center text-[#0B3A85] shrink-0">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="truncate">
                                            <p class="text-xs font-bold text-[#0F1F3D] truncate">{{ $foto_kk->getClientOriginalName() }}</p>
                                            <p class="text-[11px] text-[#5B6B82]">
                                                {{ number_format($foto_kk->getSize() / 1024, 1) }} KB &bull; <span class="text-emerald-600 font-semibold">Siap Disimpan</span>
                                            </p>
                                        </div>
                                    </div>

                                    <button 
                                        type="button" 
                                        wire:click="removeFotoKk" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer shrink-0"
                                        title="Hapus Foto Terpilih"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Batal</span>
                                    </button>
                                </div>
                            @endif

                            @error('foto_kk')
                                <p class="text-xs text-rose-500 font-medium mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 BAGIAN 2: DATA KEPALA KELUARGA (WAJIB)
                 ============================================================ --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 bg-blue-50/50 border-b border-blue-100/70 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-[#0B3A85] text-white flex items-center justify-center text-xs font-extrabold shrink-0">2</span>
                        <div>
                            <h2 class="text-sm font-extrabold text-[#0F1F3D] tracking-tight">Data Kepala Keluarga</h2>
                            <p class="text-[11px] text-[#5B6B82]">Identitas utama warga yang menjabat sebagai Kepala Keluarga.</p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-block px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-[#0B3A85] text-white uppercase tracking-wider shrink-0">
                        Wajib Diisi
                    </span>
                </div>

                <div class="p-6 space-y-4">
                    {{-- Baris 1: Nama Kepala & NIK Kepala --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Nama Lengkap Kepala Keluarga <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="kepala_nama"
                                placeholder="Contoh: Dedi Kurniawan" 
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('kepala_nama') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all font-medium"
                            >
                            @error('kepala_nama')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                NIK Kepala Keluarga (16 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="kepala_nik"
                                maxlength="16"
                                placeholder="Contoh: 1571011504780001" 
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('kepala_nik') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all font-mono"
                            >
                            @error('kepala_nik')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @else
                                <p class="text-[11px] text-[#5B6B82] mt-1">* 16 digit angka resmi dari KTP.</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris 2: Tempat Lahir & Tanggal Lahir (Tanpa Jenis Kelamin karena otomatis Laki-laki) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Tempat Lahir
                            </label>
                            <input 
                                type="text" 
                                wire:model="kepala_tempat_lahir"
                                placeholder="Contoh: Jambi" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Tanggal Lahir
                            </label>
                            <input 
                                type="date" 
                                wire:model="kepala_tanggal_lahir"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:ring-2 transition-all"
                            >
                        </div>
                    </div>

                    {{-- Baris 3: Agama, Pekerjaan, Pendidikan Terakhir --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Agama
                            </label>
                            <select 
                                wire:model="kepala_agama"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] bg-white focus:outline-none focus:ring-2 transition-all cursor-pointer"
                            >
                                <option value="Islam">Islam</option>
                                <option value="Kristen Protestan">Kristen Protestan</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Pekerjaan
                            </label>
                            <input 
                                type="text" 
                                wire:model="kepala_pekerjaan"
                                placeholder="Contoh: Karyawan Swasta / Wiraswasta" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Pendidikan Terakhir
                            </label>
                            <select 
                                wire:model="kepala_pendidikan_terakhir"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] bg-white focus:outline-none focus:ring-2 transition-all cursor-pointer"
                            >
                                <option value="SMA / SMK / Sederajat">SMA / SMK / Sederajat</option>
                                <option value="S1">S1 / Sarjana</option>
                                <option value="D3">D3 / Diploma</option>
                                <option value="SMP / Sederajat">SMP / Sederajat</option>
                                <option value="SD / Sederajat">SD / Sederajat</option>
                                <option value="S2">S2 / Magister</option>
                                <option value="S3">S3 / Doktoral</option>
                                <option value="Tidak / Belum Sekolah">Tidak / Belum Sekolah</option>
                            </select>
                        </div>
                    </div>

                    {{-- Baris 4: Nomor Telepon & Email (Tanpa Status Perkawinan karena sudah jelas Kawin) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Nomor Telepon / WhatsApp
                            </label>
                            <input 
                                type="text" 
                                wire:model="kepala_telepon"
                                placeholder="Contoh: 0812-3456-7890" 
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('kepala_telepon') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                            >
                            @error('kepala_telepon')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Alamat Email <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input 
                                type="email" 
                                wire:model="kepala_email"
                                placeholder="Contoh: kepala.keluarga@gmail.com" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                            >
                            <p class="text-[10px] text-[#5B6B82] mt-1">Jika dikosongkan, akun portal dibuat via NIK.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 BAGIAN 3: DATA ANGGOTA KELUARGA TAMBAHAN (REPEATER)
                 ============================================================ --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-[#0B3A85] text-white flex items-center justify-center text-xs font-extrabold shrink-0">3</span>
                        <div>
                            <h2 class="text-sm font-extrabold text-[#0F1F3D] tracking-tight">Anggota Keluarga Tambahan</h2>
                            <p class="text-[11px] text-[#5B6B82]">Daftarkan Istri, Anak, Orang Tua, atau famili lain di KK ini.</p>
                        </div>
                        <span class="hidden sm:inline-block ml-2 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF] shrink-0">
                            {{ count($anggota_keluarga) }} Jiwa
                        </span>
                    </div>

                    {{-- Tombol Tambah Anggota --}}
                    <button 
                        type="button" 
                        wire:click="addAnggota"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-2xs hover:shadow-xs cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Anggota</span>
                    </button>
                </div>

                <div class="p-6">
                    @if(empty($anggota_keluarga))
                        <div class="py-10 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-2.5">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-slate-700">Belum ada anggota keluarga tambahan.</p>
                            <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">
                                Klik tombol <strong>"+ Tambah Anggota"</strong> di kanan atas jika keluarga ini memiliki Istri, Anak, atau anggota keluarga lainnya.
                            </p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($anggota_keluarga as $index => $anggota)
                                <div 
                                    x-data="{ hubungan: '{{ $anggota['hubungan_keluarga'] ?? 'istri' }}' }" 
                                    class="p-5 bg-slate-50/60 rounded-2xl border border-slate-200 space-y-4" 
                                    wire:key="anggota-row-{{ $index }}"
                                >
                                    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2.5">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#0B3A85] text-white text-xs font-bold">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="text-xs font-extrabold text-[#0F1F3D]">
                                                {{ !empty($anggota['nama']) ? $anggota['nama'] : 'Anggota Keluarga #' . ($index + 1) }}
                                            </span>
                                        </div>

                                        {{-- Tombol Hapus Anggota --}}
                                        <button 
                                            type="button" 
                                            wire:click="removeAnggota({{ $index }})"
                                            class="inline-flex items-center gap-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors cursor-pointer"
                                            title="Hapus baris anggota ini"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </div>

                                    {{-- Baris 1: Hubungan, Nama Lengkap, NIK --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                        {{-- Hubungan Keluarga --}}
                                        <div class="sm:col-span-3">
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Hubungan Keluarga <span class="text-rose-500">*</span>
                                            </label>
                                            <select 
                                                wire:model="anggota_keluarga.{{ $index }}.hubungan_keluarga"
                                                x-model="hubungan"
                                                x-on:change="if (hubungan === 'istri') { $wire.set('anggota_keluarga.{{ $index }}.jenis_kelamin', 'P'); $wire.set('anggota_keluarga.{{ $index }}.status_perkawinan', 'Kawin'); }"
                                                class="w-full px-3 py-2 rounded-xl border border-[#CBD5E1] text-xs text-[#0F1F3D] bg-white cursor-pointer"
                                            >
                                                <option value="istri">Istri</option>
                                                <option value="anak">Anak</option>
                                                <option value="orang_tua">Orang Tua</option>
                                                <option value="mertua">Mertua</option>
                                                <option value="menantu">Menantu</option>
                                                <option value="cucu">Cucu</option>
                                                <option value="famili_lain">Famili Lain</option>
                                                <option value="lainnya">Lainnya</option>
                                            </select>
                                        </div>

                                        {{-- Nama Lengkap Anggota --}}
                                        <div class="sm:col-span-5">
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Nama Lengkap Anggota <span class="text-rose-500">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                wire:model="anggota_keluarga.{{ $index }}.nama"
                                                placeholder="Contoh: Siti Aminah"
                                                class="w-full px-3 py-2 rounded-xl border @error('anggota_keluarga.' . $index . '.nama') border-rose-500 @else border-[#CBD5E1] @enderror text-xs text-[#0F1F3D] placeholder-slate-400"
                                            >
                                            @error('anggota_keluarga.' . $index . '.nama')
                                                <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- NIK Anggota --}}
                                        <div class="sm:col-span-4">
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                NIK Anggota (16 Digit) <span class="text-rose-500">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                maxlength="16" 
                                                wire:model="anggota_keluarga.{{ $index }}.nik"
                                                placeholder="16 digit NIK resmi anggota"
                                                class="w-full px-3 py-2 rounded-xl border @error('anggota_keluarga.' . $index . '.nik') border-rose-500 @else border-[#CBD5E1] @enderror text-xs text-[#0F1F3D] font-mono"
                                            >
                                            @error('anggota_keluarga.' . $index . '.nik')
                                                <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Baris 2: Tempat Lahir, Tanggal Lahir, Jenis Kelamin (Disembunyikan jika Istri) --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                        {{-- Tempat Lahir (6 kolom jika Istri, 4 kolom jika bukan Istri) --}}
                                        <div :class="hubungan === 'istri' ? 'sm:col-span-6' : 'sm:col-span-4'">
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Tempat Lahir
                                            </label>
                                            <input 
                                                type="text" 
                                                wire:model="anggota_keluarga.{{ $index }}.tempat_lahir"
                                                placeholder="Contoh: Jambi"
                                                class="w-full px-3 py-2 rounded-xl border border-[#CBD5E1] text-xs text-[#0F1F3D] placeholder-slate-400"
                                            >
                                        </div>

                                        {{-- Tanggal Lahir (6 kolom jika Istri, 4 kolom jika bukan Istri) --}}
                                        <div :class="hubungan === 'istri' ? 'sm:col-span-6' : 'sm:col-span-4'">
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Tanggal Lahir
                                            </label>
                                            <input 
                                                type="date" 
                                                wire:model="anggota_keluarga.{{ $index }}.tanggal_lahir"
                                                class="w-full px-3 py-2 rounded-xl border border-[#CBD5E1] text-xs text-[#0F1F3D]"
                                            >
                                        </div>

                                        {{-- Jenis Kelamin (Disembunyikan jika Istri karena otomatis Perempuan) --}}
                                        <div class="sm:col-span-4" x-show="hubungan !== 'istri'" x-cloak>
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Jenis Kelamin <span class="text-rose-500">*</span>
                                            </label>
                                            <select 
                                                wire:model="anggota_keluarga.{{ $index }}.jenis_kelamin"
                                                class="w-full px-3 py-2 rounded-xl border border-[#CBD5E1] text-xs text-[#0F1F3D] bg-white cursor-pointer"
                                            >
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Baris 3: Agama, Pekerjaan, Pendidikan Terakhir --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        {{-- Agama --}}
                                        <div>
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Agama
                                            </label>
                                            <select 
                                                wire:model="anggota_keluarga.{{ $index }}.agama"
                                                class="w-full px-3 py-2 rounded-xl border border-[#CBD5E1] text-xs text-[#0F1F3D] bg-white cursor-pointer"
                                            >
                                                <option value="Islam">Islam</option>
                                                <option value="Kristen Protestan">Kristen Protestan</option>
                                                <option value="Katolik">Katolik</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Buddha">Buddha</option>
                                                <option value="Khonghucu">Khonghucu</option>
                                            </select>
                                        </div>

                                        {{-- Pekerjaan --}}
                                        <div>
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Pekerjaan
                                            </label>
                                            <input 
                                                type="text" 
                                                wire:model="anggota_keluarga.{{ $index }}.pekerjaan"
                                                placeholder="Contoh: Karyawan / Pelajar / IRT"
                                                class="w-full px-3 py-2 rounded-xl border border-[#CBD5E1] text-xs text-[#0F1F3D] placeholder-slate-400"
                                            >
                                        </div>

                                        {{-- Pendidikan Terakhir --}}
                                        <div>
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Pendidikan Terakhir
                                            </label>
                                            <select 
                                                wire:model="anggota_keluarga.{{ $index }}.pendidikan_terakhir"
                                                class="w-full px-3 py-2 rounded-xl border border-[#CBD5E1] text-xs text-[#0F1F3D] bg-white cursor-pointer"
                                            >
                                                <option value="SMA / SMK / Sederajat">SMA / SMK / Sederajat</option>
                                                <option value="S1">S1 / Sarjana</option>
                                                <option value="D3">D3 / Diploma</option>
                                                <option value="SMP / Sederajat">SMP / Sederajat</option>
                                                <option value="SD / Sederajat">SD / Sederajat</option>
                                                <option value="S2">S2 / Magister</option>
                                                <option value="S3">S3 / Doktoral</option>
                                                <option value="Tidak / Belum Sekolah">Tidak / Belum Sekolah</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Baris 4: Status Perkawinan & Nomor Telepon --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                        {{-- Status Perkawinan (Disembunyikan jika Istri karena otomatis Kawin) --}}
                                        <div class="sm:col-span-6" x-show="hubungan !== 'istri'" x-cloak>
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Status Perkawinan
                                            </label>
                                            <select 
                                                wire:model="anggota_keluarga.{{ $index }}.status_perkawinan"
                                                class="w-full px-3 py-2 rounded-xl border border-[#CBD5E1] text-xs text-[#0F1F3D] bg-white cursor-pointer"
                                            >
                                                <option value="Belum Kawin">Belum Kawin</option>
                                                <option value="Kawin">Kawin</option>
                                                <option value="Cerai Hidup">Cerai Hidup</option>
                                                <option value="Cerai Mati">Cerai Mati</option>
                                            </select>
                                        </div>

                                        {{-- Nomor Telepon / WA (12 kolom jika Istri, 6 kolom jika bukan Istri) --}}
                                        <div :class="hubungan === 'istri' ? 'sm:col-span-12' : 'sm:col-span-6'">
                                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                                                Nomor Telepon / WhatsApp (Opsional)
                                            </label>
                                            <input 
                                                type="text" 
                                                wire:model="anggota_keluarga.{{ $index }}.telepon"
                                                placeholder="Contoh: 0812-3456-7890"
                                                class="w-full px-3 py-2 rounded-xl border @error('anggota_keluarga.' . $index . '.telepon') border-rose-500 @else border-[#CBD5E1] @enderror text-xs text-[#0F1F3D] placeholder-slate-400"
                                            >
                                            @error('anggota_keluarga.' . $index . '.telepon')
                                                <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============================================================
                 FOOTER AKSI SIMPAN & BATAL
                 ============================================================ --}}
            <div class="flex items-center justify-end gap-3 pt-4">
                <a 
                    href="{{ \App\Filament\Resources\Households\HouseholdResource::getUrl('index') }}" 
                    class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 font-semibold text-xs transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white font-bold text-xs shadow-xs hover:shadow-md transition-all cursor-pointer disabled:opacity-50"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span wire:loading.remove wire:target="saveHousehold">Simpan Kartu Keluarga</span>
                    <span wire:loading wire:target="saveHousehold">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
