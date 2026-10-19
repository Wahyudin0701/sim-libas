{{-- ====================================================================
     MODAL POPUP KONFIRMASI HAPUS DATA WARGA (ALPINE + LIVEWIRE)
     Menggantikan native browser confirm() / alert() bawaan.
     ==================================================================== --}}
<div 
    x-show="deleteConfirmModalOpen" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto" 
    aria-labelledby="modal-delete-confirm-title" 
    role="dialog" 
    aria-modal="true"
    @keydown.escape.window="closeDeleteConfirm()"
>
    {{-- Backdrop Blur --}}
    <div 
        x-show="deleteConfirmModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="closeDeleteConfirm()"
    ></div>

    {{-- Dialog Container --}}
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div 
            x-show="deleteConfirmModalOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white p-5 sm:p-6 text-left shadow-2xl transition-all w-full max-w-md border border-slate-200"
            @click.stop
        >
            {{-- Tombol Tutup X di Sudut Kanan Atas --}}
            <button 
                type="button" 
                @click="closeDeleteConfirm()" 
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer"
                title="Tutup Popup"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Ikon Peringatan Hapus --}}
            <div class="mx-auto flex h-13 w-13 items-center justify-center rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-600 mb-3.5 shadow-2xs">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            {{-- Judul & Deskripsi --}}
            <div class="text-center">
                <h3 class="text-base sm:text-lg font-extrabold text-[#0F1F3D]" id="modal-delete-confirm-title">
                    Hapus Data Warga?
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                    Apakah Anda yakin ingin menghapus data kependudukan warga 
                    <strong class="text-[#0F1F3D] font-bold" x-text="deleteResidentName"></strong>?
                </p>

                {{-- Banner Informasi --}}
                <div class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-200/80 text-[11.5px] text-amber-800 text-left flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="leading-snug">
                        Data warga ini akan dipindahkan ke tempat sampah dan dapat dipulihkan kembali oleh pengurus RT jika diperlukan.
                    </span>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-5 flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button 
                    type="button" 
                    @click="closeDeleteConfirm()" 
                    class="w-full sm:w-auto px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all cursor-pointer shadow-2xs text-center"
                >
                    Batal
                </button>

                <button 
                    type="button" 
                    @click="executeDeleteResident()" 
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all shadow-xs hover:shadow-md cursor-pointer disabled:opacity-50"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Ya, Hapus Warga</span>
                </button>
            </div>
        </div>
    </div>
</div>
