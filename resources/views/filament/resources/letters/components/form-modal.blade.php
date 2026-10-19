<div 
    x-show="letterModalOpen" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-letter-form-title" 
    role="dialog" 
    aria-modal="true"
    @open-letter-form-modal.window="letterModalOpen = true"
    @close-letter-form-modal.window="letterModalOpen = false"
    @keydown.escape.window="letterModalOpen = false; $wire.closeLetterModal()"
>
    {{-- Backdrop --}}
    <div 
        x-show="letterModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="letterModalOpen = false; $wire.closeLetterModal()"
    ></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div 
            x-show="letterModalOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-xl border border-slate-200"
            @click.stop
        >
            {{-- Header Modal (Navy #0B3A85) --}}
            <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-base font-extrabold text-white" id="modal-letter-form-title">
                        {{ $isEditingLetter ? 'Ubah Data Surat Pengantar' : 'Buat Surat Pengantar Baru' }}
                    </h3>
                </div>

                <button 
                    type="button" 
                    @click="letterModalOpen = false; $wire.closeLetterModal()" 
                    class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Skeleton Loader saat openEditLetter --}}
            <div wire:loading.flex wire:target="openEditLetter" class="p-5 flex-col space-y-4 animate-pulse">
                <div class="h-4 bg-slate-200 rounded w-1/4"></div>
                <div class="h-10 bg-slate-100 rounded-xl"></div>
                <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                <div class="h-10 bg-slate-100 rounded-xl"></div>
                <div class="h-20 bg-slate-100 rounded-xl"></div>
            </div>

            {{-- Form Body --}}
            <form wire:loading.remove wire:target="openEditLetter" wire:submit.prevent="saveLetter" class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
                {{-- 1. Pilihan Pemohon --}}
                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Warga Pemohon <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        wire:model="form_resident_id" 
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs cursor-pointer"
                        required
                    >
                        <option value="">-- Pilih Warga Pemohon --</option>
                        @foreach($residents as $res)
                            <option value="{{ $res->id }}">
                                {{ $res->nama }} (NIK: {{ $res->masked_nik }}) {{ $res->id_warga ? '[' . $res->id_warga . ']' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('form_resident_id')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Pilihan Jenis Surat --}}
                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Jenis Surat Pengantar <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        wire:model="form_letter_type_id" 
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs cursor-pointer"
                        required
                    >
                        <option value="">-- Pilih Jenis Surat --</option>
                        @foreach($letterTypes as $type)
                            <option value="{{ $type->id }}">
                                [{{ $type->kode }}] {{ $type->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('form_letter_type_id')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3. Keperluan --}}
                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Keperluan Permohonan <span class="text-rose-500">*</span>
                    </label>
                    <textarea 
                        wire:model="form_keperluan" 
                        rows="3"
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs"
                        placeholder="Contoh: Persyaratan berkas untuk pengurusan SKCK melamar pekerjaan di instansi pemerintah..."
                        required
                    ></textarea>
                    @error('form_keperluan')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 4. Status & Nomor Surat --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                            Status Permohonan <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            wire:model="form_status" 
                            class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs cursor-pointer"
                            required
                        >
                            <option value="diajukan">Diajukan</option>
                            <option value="diperiksa">Diperiksa</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                            Nomor Resmi Surat (Opsional)
                        </label>
                        <input 
                            type="text" 
                            wire:model="form_nomor_surat" 
                            class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs font-mono font-bold text-[#0B3A85] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs"
                            placeholder="Diterbitkan otomatis saat disetujui"
                        >
                    </div>
                </div>

                {{-- 5. Catatan Pengurus --}}
                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Catatan Pengurus RT (Opsional)
                    </label>
                    <textarea 
                        wire:model="form_catatan_pengurus" 
                        rows="2"
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs"
                        placeholder="Catatan verifikasi atau pesan internal untuk pengurus..."
                    ></textarea>
                </div>

                {{-- Footer Actions --}}
                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button 
                        type="button" 
                        @click="letterModalOpen = false; $wire.closeLetterModal()" 
                        class="px-4 py-2 rounded-xl bg-white text-slate-700 border border-slate-300 hover:bg-slate-100 text-xs font-bold transition-all cursor-pointer"
                    >
                        Batal
                    </button>

                    <button 
                        type="submit" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ $isEditingLetter ? 'Simpan Perubahan' : 'Buat Surat Pengantar' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
