<div 
    x-show="letterTypeModalOpen" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-lettertype-form-title" 
    role="dialog" 
    aria-modal="true"
    @open-letter-type-modal.window="letterTypeModalOpen = true"
    @close-letter-type-modal.window="letterTypeModalOpen = false"
    @keydown.escape.window="letterTypeModalOpen = false; $wire.closeModal()"
>
    {{-- Backdrop --}}
    <div 
        x-show="letterTypeModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="letterTypeModalOpen = false; $wire.closeModal()"
    ></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div 
            x-show="letterTypeModalOpen"
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
                    <h3 class="text-base font-extrabold text-white" id="modal-lettertype-form-title">
                        {{ $isEditing ? 'Ubah Template Jenis Surat' : 'Tambah Jenis Surat Baru' }}
                    </h3>
                </div>

                <button 
                    type="button" 
                    @click="letterTypeModalOpen = false; $wire.closeModal()" 
                    class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Skeleton Loader saat openEdit --}}
            <div wire:loading.flex wire:target="openEdit" class="p-5 flex-col space-y-4 animate-pulse">
                <div class="h-4 bg-slate-200 rounded w-1/4"></div>
                <div class="h-10 bg-slate-100 rounded-xl"></div>
                <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                <div class="h-10 bg-slate-100 rounded-xl"></div>
                <div class="h-20 bg-slate-100 rounded-xl"></div>
            </div>

            {{-- Form Body --}}
            <form wire:loading.remove wire:target="openEdit" wire:submit.prevent="save" class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                            Nama Jenis Surat <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nama" 
                            class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs"
                            placeholder="Contoh: Surat Pengantar SKCK"
                            required
                        >
                        @error('nama')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                            Kode Singkat <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="kode" 
                            class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm font-mono font-bold uppercase text-[#0B3A85] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs"
                            placeholder="SP-SKCK"
                            required
                        >
                        @error('kode')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Deskripsi / Penjelasan Peruntukan
                    </label>
                    <textarea 
                        wire:model="deskripsi" 
                        rows="2"
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs"
                        placeholder="Contoh: Pengantar permohonan penerbitan SKCK ke Polsek / Polres..."
                    ></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Format Penomoran Resmi <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="format_nomor" 
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs font-mono font-bold text-[#0B3A85] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs"
                        placeholder="{NO}/SP-SKCK/RT.15/{ROMAN_MONTH}/{YEAR}"
                        required
                    >
                    <p class="text-[10.5px] text-slate-400 mt-1">
                        Gunakan tag variabel: <code class="font-mono bg-slate-100 px-1 py-0.5 rounded">{NO}</code> (nomor urut), <code class="font-mono bg-slate-100 px-1 py-0.5 rounded">{ROMAN_MONTH}</code> (bulan romawi), <code class="font-mono bg-slate-100 px-1 py-0.5 rounded">{YEAR}</code> (tahun).
                    </p>
                    @error('format_nomor')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Persyaratan Dokumen Warga (Satu persyaratan per baris)
                    </label>
                    <textarea 
                        wire:model="persyaratan_text" 
                        rows="3"
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs font-medium text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs"
                        placeholder="Foto KTP Pemohon&#10;Foto Kartu Keluarga&#10;Pas Foto 3x4"
                    ></textarea>
                </div>

                <div class="pt-1">
                    <label class="relative flex items-center gap-2 cursor-pointer select-none">
                        <input 
                            type="checkbox" 
                            wire:model="is_active" 
                            class="w-4 h-4 rounded text-[#0B3A85] focus:ring-[#0B3A85]"
                        >
                        <span class="text-xs font-bold text-[#0F1F3D]">Aktifkan jenis surat ini</span>
                    </label>
                </div>

                {{-- Footer Actions --}}
                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button 
                        type="button" 
                        @click="letterTypeModalOpen = false; $wire.closeModal()" 
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
                        <span>{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Jenis Surat' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
