@props([
    'isHouseholdView' => false,
])

<div 
    x-show="modalEditResidentOpen" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto" 
    aria-labelledby="modal-edit-resident-title" 
    role="dialog" 
    aria-modal="true"
    @keydown.escape.window="modalEditResidentOpen = false; $wire.closeEditResident()"
>
    {{-- Backdrop Blur --}}
    <div 
        x-show="modalEditResidentOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="modalEditResidentOpen = false; $wire.closeEditResident()"
    ></div>

    {{-- Dialog Wrapper --}}
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div 
            x-show="modalEditResidentOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-3xl border border-slate-200"
            @click.stop
        >
            {{-- Form livewire submit --}}
            <form wire:submit.prevent="saveResident">
                {{-- Header Modal (Navy Theme #0B3A85) --}}
                <div class="bg-gradient-to-r from-[#0B3A85] to-[#07275E] text-white px-4 sm:px-5 py-3 flex items-center justify-between border-b border-[#0B3A85]/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white border border-white/20 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h3 class="text-sm sm:text-base font-extrabold tracking-tight" id="modal-edit-resident-title">
                                    Ubah Data Warga
                                </h3>
                                <span 
                                    x-show="$wire.edit_id_warga" 
                                    x-text="$wire.edit_id_warga" 
                                    class="px-1.5 py-0.5 rounded text-[10.5px] font-mono font-bold bg-white/20 text-white border border-white/30"
                                ></span>
                            </div>
                            <p class="text-[11px] text-blue-100/90 mt-0.5">
                                Perbarui identitas kependudukan, status keluarga, dan kontak warga.
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Tutup (X) --}}
                    <button 
                        type="button" 
                        @click="modalEditResidentOpen = false; $wire.closeEditResident()" 
                        class="text-white/70 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                        title="Tutup Popup"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Skeleton Loader saat openEditResident --}}
                <div wire:loading.flex wire:target="openEditResident" class="p-6 flex-col space-y-4 animate-pulse">
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

                <div wire:loading.remove wire:target="openEditResident">
                    {{-- Body Modal --}}
                    <div class="p-4 sm:p-5 max-h-[75vh] overflow-y-auto space-y-4 text-xs text-[#0F1F3D]">
                        
                        {{-- ==========================================
                             BAGIAN 1: IDENTITAS POKOK
                             ========================================== --}}
                        <div>
                            <div class="pb-2 mb-3 border-b border-slate-100">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0B3A85]">
                                    1. Identitas Pokok Warga
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Nama Lengkap --}}
                                <div class="sm:col-span-2">
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Nama Lengkap <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="edit_nama" 
                                        placeholder="Contoh: Budi Santoso"
                                        class="w-full px-3 py-2 rounded-xl border @error('edit_nama') border-rose-400 bg-rose-50/20 @else border-slate-200 @enderror focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-semibold"
                                        required
                                    >
                                    @error('edit_nama')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- NIK --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        NIK (16 Digit) <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="edit_nik" 
                                        maxlength="16"
                                        placeholder="16 digit angka NIK"
                                        class="w-full px-3 py-2 rounded-xl border @error('edit_nik') border-rose-400 bg-rose-50/20 @else border-slate-200 @enderror focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm font-mono text-[#0F1F3D] transition-all"
                                        required
                                    >
                                    @error('edit_nik')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Jenis Kelamin --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Jenis Kelamin <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="edit_jenis_kelamin" 
                                        class="w-full px-3 py-2 rounded-xl border @error('edit_jenis_kelamin') border-rose-400 @else border-slate-200 @enderror focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                        required
                                    >
                                        <option value="L">Laki-laki (L)</option>
                                        <option value="P">Perempuan (P)</option>
                                    </select>
                                    @error('edit_jenis_kelamin')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Tempat Lahir --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Tempat Lahir
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="edit_tempat_lahir" 
                                        placeholder="Kota / Kabupaten lahir"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                    >
                                    @error('edit_tempat_lahir')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Tanggal Lahir --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Tanggal Lahir <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        wire:model="edit_tanggal_lahir" 
                                        class="w-full px-3 py-2 rounded-xl border @error('edit_tanggal_lahir') border-rose-400 @else border-slate-200 @enderror focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                        required
                                    >
                                    @error('edit_tanggal_lahir')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Agama --}}
                                <div class="sm:col-span-2">
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Agama <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="edit_agama" 
                                        class="w-full px-3 py-2 rounded-xl border @error('edit_agama') border-rose-400 @else border-slate-200 @enderror focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                        required
                                    >
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen Protestan">Kristen Protestan</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                    @error('edit_agama')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ==========================================
                             BAGIAN 2: STATUS KELUARGA & SOSIAL
                             ========================================== --}}
                        <div>
                            <div class="pb-2 mb-3 border-b border-slate-100">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0B3A85]">
                                    2. Status Hubungan Keluarga & Kependudukan
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Hubungan Keluarga --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Hubungan dalam Keluarga <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="edit_hubungan_keluarga" 
                                        class="w-full px-3 py-2 rounded-xl border @error('edit_hubungan_keluarga') border-rose-400 @else border-slate-200 @enderror focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                        required
                                    >
                                        <option value="kepala_keluarga">Kepala Keluarga</option>
                                        <option value="istri">Istri</option>
                                        <option value="anak">Anak</option>
                                        <option value="menantu">Menantu</option>
                                        <option value="cucu">Cucu</option>
                                        <option value="orang_tua">Orang Tua</option>
                                        <option value="mertua">Mertua</option>
                                        <option value="famili_lain">Famili Lain</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @error('edit_hubungan_keluarga')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Status Perkawinan --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Status Perkawinan
                                    </label>
                                    <select 
                                        wire:model="edit_status_perkawinan" 
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                    >
                                        <option value="Belum Kawin">Belum Kawin</option>
                                        <option value="Kawin">Kawin</option>
                                        <option value="Cerai Hidup">Cerai Hidup</option>
                                        <option value="Cerai Mati">Cerai Mati</option>
                                    </select>
                                    @error('edit_status_perkawinan')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Status Kependudukan --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Status Kependudukan <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="edit_status_kependudukan" 
                                        class="w-full px-3 py-2 rounded-xl border @error('edit_status_kependudukan') border-rose-400 @else border-slate-200 @enderror focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                        required
                                    >
                                        <option value="aktif">Aktif</option>
                                        <option value="pindah">Pindah</option>
                                        <option value="meninggal">Meninggal</option>
                                        <option value="lahir_baru">Lahir Baru</option>
                                    </select>
                                    @error('edit_status_kependudukan')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Pendidikan Terakhir --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Pendidikan Terakhir
                                    </label>
                                    <select 
                                        wire:model="edit_pendidikan_terakhir" 
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                    >
                                        <option value="">- Pilih Pendidikan -</option>
                                        <option value="Tidak / Belum Sekolah">Tidak / Belum Sekolah</option>
                                        <option value="SD / Sederajat">SD / Sederajat</option>
                                        <option value="SMP / Sederajat">SMP / Sederajat</option>
                                        <option value="SMA / SMK / Sederajat">SMA / SMK / Sederajat</option>
                                        <option value="D1 / D2 / D3">D1 / D2 / D3</option>
                                        <option value="D4 / S1">D4 / S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                    @error('edit_pendidikan_terakhir')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Pekerjaan --}}
                                <div class="sm:col-span-2">
                                    <label class="block font-bold text-slate-700 mb-1">
                                        Pekerjaan
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="edit_pekerjaan" 
                                        placeholder="Contoh: Karyawan Swasta, Wiraswasta, PNS, Mahasiswa"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                    >
                                    @error('edit_pekerjaan')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ==========================================
                             BAGIAN 3: KONTAK & KARTU KELUARGA / DOMISILI
                             ========================================== --}}
                        <div>
                            <div class="pb-2 mb-3 border-b border-slate-100">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0B3A85]">
                                    3. Kontak & Domisili
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Telepon / WhatsApp --}}
                                <div class="sm:col-span-2">
                                    <label class="block font-bold text-slate-700 mb-1">
                                        No. Telepon / WhatsApp
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="edit_telepon" 
                                        placeholder="Contoh: 08123456789"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm font-mono text-[#0F1F3D] transition-all"
                                    >
                                    @error('edit_telepon')
                                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Jika dipanggil dari ViewHousehold (Detail KK) --}}
                                @if($isHouseholdView)
                                    <div class="sm:col-span-2 bg-blue-50/70 rounded-xl p-3.5 border border-blue-100 flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-[#0B3A85] text-white flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-bold text-[#0B3A85]">Kartu Keluarga Terhubung</p>
                                                <p class="text-xs font-mono font-bold text-slate-800">
                                                    {{ $this->getRecord()->id_kk ? '[' . $this->getRecord()->id_kk . '] ' : '' }}{{ $this->getRecord()->no_kk }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-[11px] font-medium text-slate-500">
                                            Halaman KK Ini
                                        </span>
                                    </div>
                                @else
                                    {{-- Jika dipanggil dari ListResidents (Data Warga) --}}
                                    @php
                                        $allHouseholds = $this->householdsList;
                                    @endphp
                                    <div class="sm:col-span-2">
                                        <label class="block font-bold text-slate-700 mb-1">
                                            Kategori Kependudukan di RT 15 <span class="text-rose-500">*</span>
                                        </label>
                                        <select 
                                            wire:model.live="edit_kategori_warga" 
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                            required
                                        >
                                            <option value="memiliki_kk">Memiliki Kartu Keluarga di RT 15</option>
                                            <option value="tanpa_kk">Tanpa KK di RT 15 (Kontrak / Kos / Pendatang)</option>
                                        </select>
                                    </div>

                                    <div class="sm:col-span-2" x-show="$wire.edit_kategori_warga === 'memiliki_kk'">
                                        <label class="block font-bold text-slate-700 mb-1">
                                            Pilih Kartu Keluarga (KK)
                                        </label>
                                        <select 
                                            wire:model="edit_household_id" 
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                        >
                                            <option value="">- Belum Ditentukan -</option>
                                            @foreach($allHouseholds as $hh)
                                                <option value="{{ $hh->id }}">
                                                    [{{ $hh->id_kk ?? 'KK' }}] {{ $hh->no_kk }} - {{ $hh->kepalaKeluarga?->nama ?? $hh->alamat }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('edit_household_id')
                                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="contents" x-show="$wire.edit_kategori_warga !== 'memiliki_kk'">
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">
                                                Status Tempat Tinggal
                                            </label>
                                            <select 
                                                wire:model="edit_status_tempat_tinggal" 
                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                            >
                                                <option value="">- Pilih Status -</option>
                                                <option value="kontrak">Mengontrak Rumah</option>
                                                <option value="kos">Ngekos / Sewa Kamar</option>
                                                <option value="pendatang">Pendatang / Tinggal Sementara</option>
                                                <option value="lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">
                                                Alamat Domisili di RT 15
                                            </label>
                                            <input 
                                                type="text" 
                                                wire:model="edit_alamat_domisili" 
                                                placeholder="Contoh: Jl. Guru Muchtar No. 12"
                                                class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-[#0B3A85] focus:ring-1 focus:ring-[#0B3A85] text-sm text-[#0F1F3D] transition-all font-medium"
                                            >
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- ==========================================
                             BAGIAN 4: INFORMASI KREDENSIAL LOGIN PORTAL WARGA
                             ========================================== --}}
                        <div>
                            <div class="pb-2 mb-3 border-b border-slate-100 flex items-center justify-between">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#0B3A85] flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>4. Akses Login Portal Mandiri Warga</span>
                                </h4>
                                <span class="text-[10px] font-semibold text-slate-400">
                                    Otomatis Tersinkronisasi
                                </span>
                            </div>

                            <div class="bg-slate-50/90 rounded-xl p-3.5 border border-slate-200/80 space-y-3">
                                {{-- Status Akun Info --}}
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 pb-2.5 border-b border-slate-200/60 text-[11px]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $this->has_user_account ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                        <span class="font-bold text-slate-700">Status Akun Login:</span>
                                        <span class="{{ $this->has_user_account ? 'text-emerald-700 font-bold' : 'text-blue-700 font-semibold' }}">
                                            {{ $this->has_user_account ? 'Terdaftar & Aktif' : 'Otomatis Dibuatkan Saat Disimpan' }}
                                        </span>
                                    </div>
                                    <div class="text-slate-500 font-medium text-[10.5px]">
                                        Mekanisme: Nama KTP + NIK
                                    </div>
                                </div>

                                {{-- Kartu Kredensial --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                    <div class="p-2.5 bg-white rounded-xl border border-slate-200/80">
                                        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Username Masuk</span>
                                        <span class="font-bold text-slate-800 break-words">{{ $edit_nama ?: '(Sesuai Nama Lengkap di atas)' }}</span>
                                    </div>
                                    <div class="p-2.5 bg-white rounded-xl border border-slate-200/80">
                                        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Kata Sandi Masuk</span>
                                        <span class="font-bold font-mono text-slate-800">{{ $edit_nik ?: '(Sesuai NIK 16 digit di atas)' }}</span>
                                    </div>
                                </div>

                                <div class="p-2.5 rounded-xl bg-blue-50/60 border border-blue-100/80 text-[11px] text-slate-600 leading-relaxed flex items-start gap-2">
                                    <svg class="w-4 h-4 text-[#0B3A85] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p>
                                        Warga masuk ke portal mandiri menggunakan <strong>Nama Lengkap sesuai KTP</strong> dan <strong>16 digit NIK</strong> sebagai kata sandi. Pengurus tidak perlu mengatur kata sandi secara manual.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Footer Modal --}}
                    <div class="bg-slate-50 px-4 sm:px-5 py-2.5 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            @click="modalEditResidentOpen = false; $wire.closeEditResident()" 
                            class="px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all cursor-pointer shadow-2xs"
                        >
                            Batal
                        </button>

                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs hover:shadow-md cursor-pointer disabled:opacity-50"
                        >
                            <svg wire:loading wire:target="saveResident" class="animate-spin -ml-1 mr-1.5 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveResident">Simpan Perubahan</span>
                            <span wire:loading wire:target="saveResident">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
