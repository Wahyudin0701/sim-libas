{{-- ====================================================================
     1. MODAL SETUJUI SURAT PENGANTAR
     ==================================================================== --}}
<div 
    x-show="approveLetterModalOpen" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-approve-title" 
    role="dialog" 
    aria-modal="true"
    @open-approve-letter-modal.window="approveLetterModalOpen = true"
    @close-approve-letter-modal.window="approveLetterModalOpen = false"
    @keydown.escape.window="approveLetterModalOpen = false; $wire.closeApproveLetter()"
>
    {{-- Backdrop --}}
    <div 
        x-show="approveLetterModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="approveLetterModalOpen = false; $wire.closeApproveLetter()"
    ></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div 
            x-show="approveLetterModalOpen"
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
            <div class="bg-emerald-600 text-white px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-base font-extrabold text-white" id="modal-approve-title">
                        Persetujuan & Penerbitan Surat
                    </h3>
                </div>

                <button 
                    type="button" 
                    @click="approveLetterModalOpen = false; $wire.closeApproveLetter()" 
                    class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Skeleton Loader saat openApproveLetter --}}
            <div wire:loading.flex wire:target="openApproveLetter" class="p-5 flex-col space-y-3 animate-pulse">
                <div class="h-10 bg-slate-100 rounded-xl"></div>
                <div class="h-14 bg-slate-100 rounded-xl"></div>
                <div class="h-9 bg-slate-100 rounded-xl"></div>
            </div>

            {{-- Body Modal --}}
            <form wire:loading.remove wire:target="openApproveLetter" wire:submit.prevent="confirmApproveLetter" class="p-5 space-y-4">
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200/80 text-xs text-emerald-900 leading-relaxed">
                    Surat pengantar ini akan disetujui secara resmi. Nomor surat di bawah dihasilkan secara otomatis sesuai format baku RT 15, dan Anda dapat menyesuaikannya bila diperlukan.
                </div>

                @if($approveLetter)
                    <div class="text-xs bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-1">
                        <div><span class="text-slate-400">Pemohon:</span> <span class="font-bold text-[#0F1F3D]">{{ $approveLetter->resident?->nama }}</span></div>
                        <div><span class="text-slate-400">Jenis Surat:</span> <span class="font-medium text-[#0F1F3D]">{{ $approveLetter->letterType?->nama }}</span></div>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Nomor Resmi Surat Pengantar <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="approve_nomor_surat" 
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm font-mono font-bold text-[#0B3A85] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 shadow-2xs"
                        placeholder="Contoh: 001/SP-SKCK/RT.15/X/2026"
                        required
                    >
                    @error('approve_nomor_surat')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Catatan Tambahan Pengurus (Opsional)
                    </label>
                    <textarea 
                        wire:model="approve_catatan" 
                        rows="2"
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 shadow-2xs"
                        placeholder="Catatan verifikasi atau pesan untuk pemohon..."
                    ></textarea>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button 
                        type="button" 
                        @click="approveLetterModalOpen = false; $wire.closeApproveLetter()" 
                        class="px-4 py-2 rounded-xl bg-white text-slate-700 border border-slate-300 hover:bg-slate-100 text-xs font-bold transition-all cursor-pointer"
                    >
                        Batal
                    </button>

                    <button 
                        type="submit" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Setujui & Terbitkan Nomor</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ====================================================================
     2. MODAL TOLAK SURAT PENGANTAR
     ==================================================================== --}}
<div 
    x-show="rejectLetterModalOpen" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-reject-title" 
    role="dialog" 
    aria-modal="true"
    @open-reject-letter-modal.window="rejectLetterModalOpen = true"
    @close-reject-letter-modal.window="rejectLetterModalOpen = false"
    @keydown.escape.window="rejectLetterModalOpen = false; $wire.closeRejectLetter()"
>
    {{-- Backdrop --}}
    <div 
        x-show="rejectLetterModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="rejectLetterModalOpen = false; $wire.closeRejectLetter()"
    ></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div 
            x-show="rejectLetterModalOpen"
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
            <div class="bg-rose-600 text-white px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h3 class="text-base font-extrabold text-white" id="modal-reject-title">
                        Tolak Permohonan Surat
                    </h3>
                </div>

                <button 
                    type="button" 
                    @click="rejectLetterModalOpen = false; $wire.closeRejectLetter()" 
                    class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Skeleton Loader saat openRejectLetter --}}
            <div wire:loading.flex wire:target="openRejectLetter" class="p-5 flex-col space-y-3 animate-pulse">
                <div class="h-10 bg-slate-100 rounded-xl"></div>
                <div class="h-14 bg-slate-100 rounded-xl"></div>
                <div class="h-16 bg-slate-100 rounded-xl"></div>
            </div>

            {{-- Body Modal --}}
            <form wire:loading.remove wire:target="openRejectLetter" wire:submit.prevent="confirmRejectLetter" class="p-5 space-y-4">
                <div class="p-3 bg-rose-50 rounded-xl border border-rose-200/80 text-xs text-rose-900 leading-relaxed">
                    Mohon sertakan alasan penolakan secara jelas agar warga pemohon dapat melengkapi berkas atau memperbaiki data pengajuannya.
                </div>

                @if($rejectLetter)
                    <div class="text-xs bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-1">
                        <div><span class="text-slate-400">Pemohon:</span> <span class="font-bold text-[#0F1F3D]">{{ $rejectLetter->resident?->nama }}</span></div>
                        <div><span class="text-slate-400">Jenis Surat:</span> <span class="font-medium text-[#0F1F3D]">{{ $rejectLetter->letterType?->nama }}</span></div>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea 
                        wire:model="reject_alasan" 
                        rows="3"
                        class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs text-[#0F1F3D] focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-500/10 shadow-2xs"
                        placeholder="Contoh: Berkas lampiran foto Kartu Keluarga buram dan tidak terbaca. Mohon unggah ulang foto yang jelas..."
                        required
                    ></textarea>
                    @error('reject_alasan')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button 
                        type="button" 
                        @click="rejectLetterModalOpen = false; $wire.closeRejectLetter()" 
                        class="px-4 py-2 rounded-xl bg-white text-slate-700 border border-slate-300 hover:bg-slate-100 text-xs font-bold transition-all cursor-pointer"
                    >
                        Batal
                    </button>

                    <button 
                        type="submit" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all shadow-xs cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Tolak Permohonan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
