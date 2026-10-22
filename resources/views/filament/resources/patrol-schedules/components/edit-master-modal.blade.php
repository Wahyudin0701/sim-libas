{{-- ====================================================================
     MODAL EDIT MASTER JADWAL RONDA (Pengaturan Regu & Petugas Tetap)
     ==================================================================== --}}
<div 
    x-show="editMasterModalOpen" 
    x-cloak 
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto" 
    aria-labelledby="edit-master-modal-title" 
    role="dialog" 
    aria-modal="true"
>
    {{-- Backdrop Blur --}}
    <div 
        x-show="editMasterModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="editMasterModalOpen = false; $wire.closeEditMaster()"
    ></div>

    {{-- Dialog Container --}}
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div 
            x-show="editMasterModalOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all my-6 w-full sm:max-w-2xl border border-slate-200 flex flex-col max-h-[92vh]" 
            @click.stop
        >
            {{-- Header Modal (Navy #0B3A85) --}}
            <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="p-1.5 rounded-lg bg-white/10 text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                            Master Jadwal Ronda Mingguan
                        </div>
                        <h3 class="text-sm sm:text-base font-extrabold text-white leading-tight" id="edit-master-modal-title">
                            Kelola Master Jadwal & Petugas (Hari {{ $form_nama_hari }})
                        </h3>
                    </div>
                </div>
                <button 
                    type="button" 
                    @click="editMasterModalOpen = false; $wire.closeEditMaster()" 
                    class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" 
                    title="Tutup Modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Body --}}
            <form wire:submit="saveMasterSchedule" class="flex flex-col flex-1 overflow-hidden">
                <div class="overflow-y-auto p-4 sm:p-5 space-y-4 text-xs text-slate-700 flex-1">
                    {{-- Informasi Hari & Siklus --}}
                    <div class="p-3 rounded-xl bg-blue-50/60 border border-blue-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#0B3A85] text-white font-extrabold text-xs">
                                {{ $form_hari_ke }}
                            </span>
                            <div>
                                <span class="font-extrabold text-sm text-[#0F1F3D]">Hari {{ $form_nama_hari }}</span>
                                <p class="text-[11px] text-slate-500">Master jadwal ini berulang otomatis setiap hari {{ $form_nama_hari }}.</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            7 Hari Tetap
                        </span>
                    </div>

                    {{-- Nama Regu & Pos --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">
                                Nama Regu Ronda: <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="form_regu" 
                                placeholder="Contoh: Regu A - Garuda (Senin Malam)"
                                class="w-full text-xs rounded-xl border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-2 px-3"
                            >
                            @error('form_regu')
                                <span class="text-rose-600 text-[11px] mt-0.5 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">
                                Pos Jaga / Lokasi: <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="form_pos_lokasi" 
                                placeholder="Contoh: Pos Utama RT 15 / Pos Gerbang"
                                class="w-full text-xs rounded-xl border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-2 px-3"
                            >
                            @error('form_pos_lokasi')
                                <span class="text-rose-600 text-[11px] mt-0.5 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Jam Jaga Mulai & Selesai --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">
                                Jam Mulai Tugas: <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="time" 
                                wire:model="form_waktu_mulai" 
                                class="w-full text-xs rounded-xl border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-2 px-3"
                            >
                            @error('form_waktu_mulai')
                                <span class="text-rose-600 text-[11px] mt-0.5 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">
                                Jam Selesai Tugas: <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="time" 
                                wire:model="form_waktu_selesai" 
                                class="w-full text-xs rounded-xl border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-2 px-3"
                            >
                            @error('form_waktu_selesai')
                                <span class="text-rose-600 text-[11px] mt-0.5 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Catatan / Instruksi Rutin --}}
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            Catatan / Instruksi Khusus Ronda:
                        </label>
                        <textarea 
                            wire:model="form_catatan" 
                            rows="2"
                            placeholder="Contoh: Patroli keliling gang setiap 1 jam sekali dan pencatatan tamu menginap."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-2 px-3"
                        ></textarea>
                    </div>

                    {{-- Pemilihan Personil Tetap --}}
                    <div class="pt-2 border-t border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <label class="block font-bold text-slate-800 text-xs">
                                    Pilih Personil Tetap (Warga RT 15): <span class="text-rose-500">*</span>
                                </label>
                                <p class="text-[11px] text-slate-500">Centang warga yang bertugas rutin setiap hari {{ $form_nama_hari }}.</p>
                            </div>
                            <span class="text-[11px] font-bold text-[#0B3A85] bg-blue-50 px-2 py-0.5 rounded-md">
                                {{ count($form_resident_ids) }} Dipilih
                            </span>
                        </div>

                        <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-2.5 space-y-1.5 bg-slate-50/50">
                            @foreach($allResidents as $res)
                                <label class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs">
                                    <input 
                                        type="checkbox" 
                                        value="{{ $res->id }}"
                                        wire:model.live="form_resident_ids"
                                        class="rounded text-[#0B3A85] focus:ring-[#0B3A85]/20 border-slate-300"
                                    >
                                    <span class="font-bold text-slate-800">{{ $res->nama }}</span>
                                    <span class="text-slate-400 text-[11px]">({{ $res->nik }})</span>
                                </label>
                            @endforeach
                        </div>
                        @error('form_resident_ids')
                            <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Pemilihan Ketua Regu --}}
                    @if(!empty($form_resident_ids))
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">
                                Tentukan Ketua Regu: <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                wire:model="form_ketua_resident_id" 
                                class="w-full text-xs font-bold rounded-xl border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-2 px-3"
                            >
                                <option value="">-- Pilih Ketua Regu --</option>
                                @foreach($allResidents->whereIn('id', $form_resident_ids) as $rOption)
                                    <option value="{{ $rOption->id }}">⭐ {{ $rOption->nama }}</option>
                                @endforeach
                            </select>
                            @error('form_ketua_resident_id')
                                <span class="text-rose-600 text-[11px] mt-0.5 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>

                {{-- Footer Modal --}}
                <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 flex items-center justify-between shrink-0">
                    <p class="text-[11px] text-slate-400 hidden sm:block">
                        * Perubahan master berlaku untuk minggu-minggu berikutnya tanpa mengubah riwayat lampau.
                    </p>
                    <div class="flex items-center gap-2 ml-auto">
                        <button 
                            type="button" 
                            @click="editMasterModalOpen = false; $wire.closeEditMaster()"
                            class="px-3.5 py-2 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs hover:shadow-md cursor-pointer flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Master Jadwal</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
