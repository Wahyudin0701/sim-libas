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
                        title="Panduan Tambah Data Warga"
                        category="KEPENDUDUKAN RT 15"
                        description="Formulir pencatatan individu warga baru RT 15. Dapat dihubungkan ke salah satu Kartu Keluarga (KK) yang sudah ada atau didaftarkan sebagai warga mandiri/kos/kontrak sementara."
                        :steps="[
                            ['title' => 'Input Identitas Utama', 'desc' => 'Masukkan 16 digit NIK resmi, nama lengkap sesuai KTP, tempat tanggal lahir, jenis kelamin, dan agama.'],
                            ['title' => 'Pilih Kartu Keluarga', 'desc' => 'Pilih Kartu Keluarga yang menaungi warga ini serta tentukan hubungan dalam keluarga (Istri, Anak, Famili Lain).'],
                            ['title' => 'Status & Pekerjaan', 'desc' => 'Tentukan status kependudukan (Aktif/Pindah/Meninggal), status pernikahan, pendidikan terakhir, dan profesi.'],
                            ['title' => 'Kontak & Dokumen', 'desc' => 'Lengkapi nomor HP/WhatsApp aktif serta unggah foto KTP warga untuk arsip verifikasi kependudukan.']
                        ]"
                        role="Sekretaris RT & Ketua RT"
                        :tips="[
                            'Satu NIK hanya boleh terdaftar sekali dalam sistem kependudukan RT 15.',
                            'Jika warga berstatus Kepala Keluarga, pastikan KK yang dipilih belum memiliki kepala keluarga aktif lainnya.',
                            'Nomor HP warga akan digunakan untuk pengiriman pengumuman penting RT dan notifikasi surat.'
                        ]"
                    />
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight">
                    Tambah Data Warga Baru
                </h1>
            </div>

            <div class="flex items-center gap-2">
                <a 
                    href="{{ \App\Filament\Resources\Residents\ResidentResource::getUrl('index') }}" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#0F1F3D] border border-[#CBD5E1] hover:bg-[#F8FAFC] hover:border-[#0B3A85] hover:text-[#0B3A85] transition-all duration-150 text-xs font-bold shadow-2xs hover:shadow-xs cursor-pointer group"
                    title="Kembali ke Daftar Data Warga"
                >
                    <svg class="w-3.5 h-3.5 text-[#5B6B82] group-hover:text-[#0B3A85] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        {{-- ====================================================================
             FORMULIR PENDAFTARAN WARGA
             ==================================================================== --}}
        <form wire:submit.prevent="saveResident" class="space-y-4">

            {{-- ============================================================
                 BAGIAN 1: KATEGORI KEPENDUDUKAN & STATUS TEMPAT TINGGAL
                 ============================================================ --}}
            <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="px-4 py-3 sm:px-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-[#0B3A85] text-white flex items-center justify-center text-xs font-extrabold shrink-0">1</span>
                        <div>
                            <h2 class="text-sm font-extrabold text-[#0F1F3D] tracking-tight">Kategori Kependudukan & Tempat Tinggal</h2>
                            <p class="text-[11px] text-[#5B6B82]">Tentukan apakah warga terdaftar dalam Kartu Keluarga RT 15 atau bertempat tinggal tanpa KK (kontrak, kos, pendatang).</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-5 space-y-4">
                    {{-- 1. Pilihan Kategori Warga (Radio Cards) --}}
                    <div>
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-2.5">
                            Kategori Warga <span class="text-rose-500">*</span>
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            {{-- Opsi A: Memiliki KK di RT 15 --}}
                            <label 
                                class="relative flex items-start p-4 rounded-xl border cursor-pointer transition-all {{ $kategori_warga === 'memiliki_kk' ? 'border-[#0B3A85] bg-[#F0F6FF]/70 ring-2 ring-[#0B3A85]/15' : 'border-[#CBD5E1] bg-white hover:bg-slate-50/70 hover:border-slate-400' }}"
                            >
                                <input 
                                    type="radio" 
                                    name="kategori_warga" 
                                    value="memiliki_kk" 
                                    wire:model.live="kategori_warga"
                                    class="mt-0.5 text-[#0B3A85] focus:ring-[#0B3A85] focus:ring-offset-0"
                                >
                                <div class="ml-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-[#0F1F3D]">Memiliki KK di RT 15</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#EBF3FC] text-[#0B3A85]">Keluarga Tetap</span>
                                    </div>
                                    <p class="text-[11px] text-[#5B6B82] mt-1 leading-relaxed">
                                        Warga merupakan anggota dari salah satu Kartu Keluarga (KK) resmi yang telah terdaftar di lingkungan RT 15.
                                    </p>
                                </div>
                            </label>

                            {{-- Opsi B: Tanpa KK di RT 15 --}}
                            <label 
                                class="relative flex items-start p-4 rounded-xl border cursor-pointer transition-all {{ $kategori_warga === 'tanpa_kk' ? 'border-[#0B3A85] bg-[#F0F6FF]/70 ring-2 ring-[#0B3A85]/15' : 'border-[#CBD5E1] bg-white hover:bg-slate-50/70 hover:border-slate-400' }}"
                            >
                                <input 
                                    type="radio" 
                                    name="kategori_warga" 
                                    value="tanpa_kk" 
                                    wire:model.live="kategori_warga"
                                    class="mt-0.5 text-[#0B3A85] focus:ring-[#0B3A85] focus:ring-offset-0"
                                >
                                <div class="ml-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-[#0F1F3D]">Tanpa KK di RT 15</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Kontrak / Kos / Pendatang</span>
                                    </div>
                                    <p class="text-[11px] text-[#5B6B82] mt-1 leading-relaxed">
                                        Warga bertempat tinggal di wilayah RT 15 sebagai penyewa rumah (kontrakan), anak kos, atau pendatang sementara.
                                    </p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 2. FORM KONDISIONAL BERDASARKAN KATEGORI --}}
                    @if ($kategori_warga === 'memiliki_kk')
                        <div class="p-4 sm:p-5 rounded-xl bg-slate-50/80 border border-slate-200/80 space-y-4">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0B3A85]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>Pilih Kartu Keluarga & Posisi Hubungan</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Pilih KK Terdaftar --}}
                                <div>
                                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                        Kartu Keluarga (KK) Terdaftar <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model.live="household_id"
                                        class="w-full px-3.5 py-2.5 rounded-xl border @error('household_id') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] bg-white focus:outline-none focus:ring-2 transition-all cursor-pointer"
                                    >
                                        <option value="">-- Pilih Kartu Keluarga --</option>
                                        @foreach ($this->households as $hh)
                                            <option value="{{ $hh->id }}">
                                                [{{ $hh->id_kk }}] No. KK: {{ $hh->no_kk }} - Kepala: {{ $hh->kepalaKeluarga?->nama ?? 'Belum ada' }} ({{ $hh->alamat }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('household_id')
                                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                                    @else
                                        <p class="text-[11px] text-[#5B6B82] mt-1">Pilih KK di RT 15 untuk otomatis menghubungkan warga ke KK ini.</p>
                                    @enderror
                                </div>

                                {{-- Hubungan Dalam Keluarga --}}
                                <div>
                                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                        Hubungan Dalam Keluarga <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model.live="hubungan_keluarga"
                                        class="w-full px-3.5 py-2.5 rounded-xl border @error('hubungan_keluarga') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] bg-white focus:outline-none focus:ring-2 transition-all cursor-pointer"
                                    >
                                        <option value="istri">Istri</option>
                                        <option value="anak">Anak</option>
                                        <option value="menantu">Menantu</option>
                                        <option value="cucu">Cucu</option>
                                        <option value="orang_tua">Orang Tua</option>
                                        <option value="mertua">Mertua</option>
                                        <option value="famili_lain">Famili Lain</option>
                                        <option value="kepala_keluarga">Kepala Keluarga</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @error('hubungan_keluarga')
                                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                                    @else
                                        <p class="text-[11px] text-[#5B6B82] mt-1">Pilih status hubungan keluarga terhadap Kepala Keluarga.</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Preview Kartu Keluarga Terpilih --}}
                            @if ($this->selectedHousehold)
                                <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-200/90 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-[#0B3A85] text-white">
                                                {{ $this->selectedHousehold->id_kk }}
                                            </span>
                                            <span class="font-mono font-bold text-[#0F1F3D]">
                                                No. KK: {{ $this->selectedHousehold->no_kk }}
                                            </span>
                                        </div>
                                        <div class="text-[#0F1F3D] font-medium">
                                            Kepala Keluarga: <span class="font-bold text-[#0B3A85]">{{ $this->selectedHousehold->kepalaKeluarga?->nama ?? 'Belum terdaftar' }}</span>
                                        </div>
                                        <div class="text-[11px] text-[#5B6B82]">
                                            Alamat: {{ $this->selectedHousehold->alamat }} ({{ $this->selectedHousehold->rt_rw ?? 'RT 15 / RW 04' }})
                                        </div>
                                    </div>
                                    <div class="shrink-0 sm:text-right">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-blue-200 text-[#0B3A85] font-bold text-xs shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            {{ $this->selectedHousehold->residents->count() }} Jiwa Terdaftar
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Formulir untuk Warga Tanpa KK (Kontrak / Kos / Pendatang) --}}
                        <div class="p-4 sm:p-5 rounded-xl bg-amber-50/60 border border-amber-200/80 space-y-4">
                            <div class="flex items-center gap-2 text-xs font-bold text-amber-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Informasi Tempat Tinggal & Domisili di RT 15</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Status Tempat Tinggal --}}
                                <div>
                                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                        Status Tempat Tinggal <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="status_tempat_tinggal"
                                        class="w-full px-3.5 py-2.5 rounded-xl border @error('status_tempat_tinggal') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] bg-white focus:outline-none focus:ring-2 transition-all cursor-pointer"
                                    >
                                        <option value="kontrak">Mengontrak Rumah</option>
                                        <option value="kos">Ngekos / Sewa Kamar</option>
                                        <option value="pendatang">Pendatang / Tinggal Sementara</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @error('status_tempat_tinggal')
                                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Alamat Domisili di RT 15 --}}
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                        Alamat Lengkap Domisili di RT 15 <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="alamat_domisili"
                                        placeholder="Contoh: Jl. Aster No. 15 (Kontrakan / Kos Ibu Hj. Aminah), RT 15" 
                                        class="w-full px-3.5 py-2.5 rounded-xl border @error('alamat_domisili') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                                    >
                                    @error('alamat_domisili')
                                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                                    @else
                                        <p class="text-[11px] text-[#5B6B82] mt-1">Alamat rumah sewa, kos-kosan, atau tempat tinggal sementara di RT 15.</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============================================================
                 BAGIAN 2: DATA IDENTITAS & BIODATA WARGA
                 ============================================================ --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-[#0B3A85] text-white flex items-center justify-center text-xs font-extrabold shrink-0">2</span>
                        <div>
                            <h2 class="text-sm font-extrabold text-[#0F1F3D] tracking-tight">Data Identitas & Biodata Warga</h2>
                            <p class="text-[11px] text-[#5B6B82]">Informasi kependudukan lengkap sesuai dokumen resmi (KTP).</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    {{-- Baris 1: Nama Lengkap & NIK --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="nama"
                                placeholder="Contoh: Muhammad Farhan Pratama" 
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('nama') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all font-medium"
                            >
                            @error('nama')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                NIK (16 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="nik"
                                maxlength="16"
                                placeholder="Contoh: 1571012508900003" 
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('nik') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all font-mono"
                            >
                            @error('nik')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @else
                                <p class="text-[11px] text-[#5B6B82] mt-1">* 16 digit angka resmi dari KTP.</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris 2: Tempat Lahir, Tanggal Lahir, Jenis Kelamin --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Tempat Lahir
                            </label>
                            <input 
                                type="text" 
                                wire:model="tempat_lahir"
                                placeholder="Contoh: Jambi" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Tanggal Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                wire:model="tanggal_lahir"
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('tanggal_lahir') border-rose-500 focus:ring-rose-200 @else border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 @enderror text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:ring-2 transition-all"
                            >
                            @error('tanggal_lahir')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Jenis Kelamin <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border cursor-pointer text-xs font-bold transition-all {{ $jenis_kelamin === 'L' ? 'bg-[#0B3A85] text-white border-[#0B3A85]' : 'bg-white text-[#0F1F3D] border-[#CBD5E1] hover:bg-slate-50' }}">
                                    <input type="radio" wire:model="jenis_kelamin" value="L" class="sr-only">
                                    <span>Laki-laki</span>
                                </label>
                                <label class="flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border cursor-pointer text-xs font-bold transition-all {{ $jenis_kelamin === 'P' ? 'bg-[#0B3A85] text-white border-[#0B3A85]' : 'bg-white text-[#0F1F3D] border-[#CBD5E1] hover:bg-slate-50' }}">
                                    <input type="radio" wire:model="jenis_kelamin" value="P" class="sr-only">
                                    <span>Perempuan</span>
                                </label>
                            </div>
                            @error('jenis_kelamin')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris 3: Agama, Pekerjaan, Pendidikan Terakhir --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Agama
                            </label>
                            <select 
                                wire:model="agama"
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
                                wire:model="pekerjaan"
                                placeholder="Contoh: Karyawan Swasta" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Pendidikan Terakhir
                            </label>
                            <select 
                                wire:model="pendidikan_terakhir"
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

                    {{-- Baris 4: Status Perkawinan, Telepon, Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Status Perkawinan
                            </label>
                            <select 
                                wire:model="status_perkawinan"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] bg-white focus:outline-none focus:ring-2 transition-all cursor-pointer"
                            >
                                <option value="Belum Kawin">Belum Kawin</option>
                                <option value="Kawin">Kawin</option>
                                <option value="Cerai Hidup">Cerai Hidup</option>
                                <option value="Cerai Mati">Cerai Mati</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Nomor Telepon / WhatsApp
                            </label>
                            <input 
                                type="text" 
                                wire:model="telepon"
                                placeholder="Contoh: 0812-3456-7890" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                                Alamat Email <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input 
                                type="email" 
                                wire:model="email"
                                placeholder="Contoh: warga@gmail.com" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] placeholder-slate-400 focus:outline-none focus:ring-2 transition-all"
                            >
                            <p class="text-[10px] text-[#5B6B82] mt-1">Jika dikosongkan, akun portal dibuat otomatis via NIK.</p>
                        </div>
                    </div>

                    {{-- Baris 5: Status Kependudukan --}}
                    <div>
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-1.5">
                            Status Kependudukan <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            wire:model="status_kependudukan"
                            class="w-full sm:w-1/3 px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10 text-xs sm:text-sm text-[#0F1F3D] bg-white focus:outline-none focus:ring-2 transition-all cursor-pointer"
                        >
                            <option value="aktif">Aktif (Tinggal di RT 15)</option>
                            <option value="pindah">Pindah Domisili</option>
                            <option value="meninggal">Meninggal Dunia</option>
                            <option value="lahir_baru">Lahir Baru</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 BAGIAN 3: DOKUMEN & FOTO PROFIL (OPSIONAL)
                 ============================================================ --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-[#0B3A85] text-white flex items-center justify-center text-xs font-extrabold shrink-0">3</span>
                        <div>
                            <h2 class="text-sm font-extrabold text-[#0F1F3D] tracking-tight">Foto Profil / Dokumen Warga</h2>
                            <p class="text-[11px] text-[#5B6B82]">Unggah pas foto warga atau foto KTP jika tersedia (JPG, JPEG, PNG, atau WEBP maks 5 MB).</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div 
                        x-data="{ isDropping: false }"
                        @dragover.prevent="isDropping = true"
                        @dragleave.prevent="isDropping = false"
                        @drop.prevent="isDropping = false"
                        class="relative"
                    >
                        <label 
                            for="foto_warga_input"
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
                                    Format: JPG, JPEG, PNG, atau WEBP &bull; Maksimal ukuran 5 MB
                                </p>
                            </div>

                            <input 
                                id="foto_warga_input" 
                                type="file" 
                                wire:model="foto_warga" 
                                accept="image/png,image/jpeg,image/jpg,image/webp" 
                                class="hidden"
                            />
                        </label>

                        {{-- Loading State Saat Upload Berlangsung --}}
                        <div wire:loading wire:target="foto_warga" class="mt-2 flex items-center gap-2 text-xs font-semibold text-[#0B3A85]">
                            <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Sedang mengunggah foto...</span>
                        </div>

                        {{-- Preview Foto Warga yang Berhasil Diunggah --}}
                        @if ($foto_warga && !is_string($foto_warga))
                            <div class="mt-3 p-3 bg-blue-50/60 border border-blue-200/80 rounded-xl flex items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    @php
                                        $isImage = false;
                                        try {
                                            $isImage = str_starts_with($foto_warga->getMimeType() ?? '', 'image/');
                                        } catch (\Exception $e) {
                                            $isImage = false;
                                        }
                                    @endphp

                                    @if ($isImage)
                                        <img src="{{ $foto_warga->temporaryUrl() }}" alt="Preview Foto Warga" class="w-14 h-14 object-cover rounded-lg border border-blue-200 shrink-0 shadow-2xs">
                                    @else
                                        <div class="w-14 h-14 rounded-lg bg-white border border-blue-200 flex items-center justify-center text-[#0B3A85] shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="truncate">
                                        <p class="text-xs font-bold text-[#0F1F3D] truncate">{{ $foto_warga->getClientOriginalName() }}</p>
                                        <p class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Siap disimpan
                                        </p>
                                    </div>
                                </div>

                                <button 
                                    type="button" 
                                    wire:click="removeFotoWarga" 
                                    class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                    title="Hapus Foto"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 FOOTER AKSI SIMPAN & BATAL
                 ============================================================ --}}
            <div class="flex items-center justify-end gap-3 pt-4">
                <a 
                    href="{{ \App\Filament\Resources\Residents\ResidentResource::getUrl('index') }}" 
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
                    <span wire:loading.remove wire:target="saveResident">Simpan Data Warga</span>
                    <span wire:loading wire:target="saveResident">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
