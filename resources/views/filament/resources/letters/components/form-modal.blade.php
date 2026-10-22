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
            <form 
                x-data="{ currentStatus: @entangle('form_status').live }" 
                wire:loading.remove 
                wire:target="openEditLetter" 
                wire:submit.prevent="saveLetter" 
                class="p-5 space-y-4 max-h-[75vh] overflow-y-auto"
            >
                {{-- 1. Pilihan Pemohon dengan Mekanisme Autocomplete / Typeahead --}}
                <div 
                    x-data="residentAutocomplete(
                        @js($residentOptions ?? []), 
                        @entangle('form_resident_id').live, 
                        @entangle('form_resident_query').live
                    )"
                    @click.outside="isOpen = false"
                    class="relative"
                >
                    <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                        Warga Pemohon <span class="text-rose-500">*</span>
                    </label>

                    {{-- Input Pencarian Autocomplete / Typeahead Bebas --}}
                    <div class="relative flex items-center">
                        <input 
                            x-ref="residentInput"
                            type="text" 
                            x-model="query"
                            @input="onInput"
                            @focus="onFocus"
                            @keydown="onKeyDown"
                            placeholder="Ketik nama atau ID warga (contoh: Budi / W001)..."
                            class="w-full pl-9 pr-9 py-2 bg-white border rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs transition-colors"
                            :class="{ 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/10': {{ $errors->has('form_resident_id') ? 'true' : 'false' }}, 'border-[#DDE5F0]': !{{ $errors->has('form_resident_id') ? 'true' : 'false' }} }"
                            autocomplete="off"
                        />

                        {{-- Ikon Search --}}
                        <div class="absolute left-3 text-slate-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        {{-- Tombol Hapus Pencarian --}}
                        <button 
                            type="button" 
                            x-show="query && query.length > 0" 
                            @click="clearSelection" 
                            class="absolute right-2.5 p-1 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer"
                            title="Hapus pencarian warga"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Dropdown Rekomendasi Warga yang Cocok --}}
                    <div 
                        x-show="isOpen" 
                        x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto z-50 divide-y divide-slate-100"
                    >
                        <template x-for="(res, index) in filteredResidents" :key="res.id">
                            <div 
                                @click="selectResident(res)"
                                @mouseenter="highlightedIndex = index"
                                :class="{ 'bg-blue-50/70': highlightedIndex === index, 'bg-[#EBF3FC]/60 font-semibold': selectedId === res.id }"
                                class="px-3.5 py-2 hover:bg-blue-50/70 cursor-pointer transition-colors flex items-center justify-between gap-2"
                            >
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-bold text-xs sm:text-sm text-[#0F1F3D]" x-text="res.nama"></span>
                                        <template x-if="res.id_warga">
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-mono font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF]" x-text="res.id_warga"></span>
                                        </template>
                                    </div>
                                    <div class="text-[10.5px] font-mono text-[#5B6B82] mt-0.5">
                                        NIK: <span x-text="res.masked_nik"></span>
                                    </div>
                                </div>
                                <template x-if="selectedId === res.id">
                                    <span class="inline-flex items-center gap-1 text-[10.5px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Terpilih
                                    </span>
                                </template>
                            </div>
                        </template>

                        <div x-show="filteredResidents.length === 0" class="px-4 py-3 text-center text-xs text-slate-500">
                            <span class="font-semibold text-slate-700 block">Warga tidak ditemukan</span>
                            <span class="text-[11px] text-slate-400">Silakan periksa kembali nama atau ID warga yang diketik.</span>
                        </div>
                    </div>

                    {{-- Pesan Validasi Error --}}
                    @error('form_resident_id')
                        <p class="text-rose-500 text-[11px] mt-1 font-medium">{{ $message }}</p>
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

                {{-- 4. Status Permohonan & Nomor Resmi Surat --}}
                <div class="grid grid-cols-1 gap-3" :class="currentStatus === 'disetujui' ? 'sm:grid-cols-2' : ''">
                    <div>
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                            Status Permohonan <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            wire:model.live="form_status" 
                            x-model="currentStatus"
                            class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs cursor-pointer"
                            required
                        >
                            <option value="diajukan">Diajukan</option>
                            <option value="diperiksa">Diperiksa</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                        @error('form_status')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Surat: Hanya tampil ketika status 'disetujui' dan wajib diisi --}}
                    <div x-show="currentStatus === 'disetujui'" x-cloak>
                        <label class="block text-xs font-bold text-[#0F1F3D] mb-1">
                            Nomor Resmi Surat <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="form_nomor_surat" 
                            class="w-full px-3 py-2 bg-white border border-[#DDE5F0] rounded-xl text-xs sm:text-sm font-mono font-bold text-[#0B3A85] focus:outline-none focus:border-[#0B3A85] focus:ring-2 focus:ring-[#0B3A85]/10 shadow-2xs"
                            placeholder="Contoh: 470/015/RT15/X/2026"
                        >
                        @error('form_nomor_surat')
                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 5. Catatan Pengurus / Alasan Tolak: Disembunyikan ketika status 'diajukan' dan 'diperiksa'. Tampil ketika 'ditolak' (wajib) atau 'disetujui' (opsional) --}}
                <div x-show="currentStatus === 'disetujui' || currentStatus === 'ditolak'" x-cloak>
                    <label class="block text-xs font-bold mb-1">
                        <span x-show="currentStatus === 'ditolak'" class="text-rose-600 font-bold">
                            Catatan Pengurus / Alasan Penolakan <span class="text-rose-500">*</span>
                        </span>
                        <span x-show="currentStatus === 'disetujui'" class="text-[#0F1F3D]">
                            Catatan Pengurus RT (Opsional)
                        </span>
                    </label>
                    <textarea 
                        wire:model="form_catatan_pengurus" 
                        rows="2"
                        class="w-full px-3 py-2 bg-white border rounded-xl text-xs sm:text-sm text-[#0F1F3D] focus:outline-none focus:ring-2 shadow-2xs transition-colors"
                        :class="currentStatus === 'ditolak' ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/10' : 'border-[#DDE5F0] focus:border-[#0B3A85] focus:ring-[#0B3A85]/10'"
                        :placeholder="currentStatus === 'ditolak' ? 'Tuliskan catatan atau alasan penolakan permohonan surat...' : 'Catatan verifikasi atau pesan internal untuk pengurus...'"
                    ></textarea>
                    <p x-show="currentStatus === 'ditolak'" class="text-[11px] text-rose-500 mt-1">
                        Catatan penolakan ini akan disimpan dan dapat dibaca oleh warga pemohon.
                    </p>
                    @error('form_catatan_pengurus')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
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

<script>
    window.residentAutocomplete = function(residentsData, initialId, initialQuery) {
        return {
            residents: Array.isArray(residentsData) ? residentsData : [],
            selectedId: initialId || null,
            query: initialQuery || '',
            isOpen: false,
            highlightedIndex: -1,

            init() {
                if (this.selectedId && !this.query) {
                    const found = this.residents.find(r => r.id === this.selectedId);
                    if (found) {
                        this.query = found.nama + (found.id_warga ? ` [${found.id_warga}]` : '');
                    }
                }

                this.$watch('selectedId', (val) => {
                    if (val) {
                        const found = this.residents.find(r => r.id === val);
                        if (found) {
                            const expected = found.nama + (found.id_warga ? ` [${found.id_warga}]` : '');
                            if (this.query !== expected) {
                                this.query = expected;
                            }
                        }
                    }
                });
            },

            get filteredResidents() {
                if (!this.query || this.query.trim() === '') {
                    return this.residents.slice(0, 10);
                }
                const q = this.query.toLowerCase().trim();
                return this.residents.filter(r => {
                    const namaMatch = (r.nama || '').toLowerCase().includes(q);
                    const idMatch = (r.id_warga || '').toLowerCase().includes(q);
                    const nikMatch = (r.masked_nik || '').toLowerCase().includes(q);
                    return namaMatch || idMatch || nikMatch;
                }).slice(0, 15);
            },

            onFocus() {
                this.isOpen = true;
            },

            selectResident(resident) {
                this.selectedId = resident.id;
                this.query = resident.nama + (resident.id_warga ? ` [${resident.id_warga}]` : '');
                this.isOpen = false;
                this.highlightedIndex = -1;
                if (this.$wire) {
                    this.$wire.set('form_resident_id', resident.id);
                    this.$wire.set('form_resident_query', this.query);
                }
            },

            clearSelection() {
                this.selectedId = null;
                this.query = '';
                this.isOpen = true;
                this.highlightedIndex = -1;
                if (this.$wire) {
                    this.$wire.set('form_resident_id', null);
                    this.$wire.set('form_resident_query', '');
                }
                this.$nextTick(() => {
                    this.$refs.residentInput?.focus();
                });
            },

            onInput() {
                this.isOpen = true;
                this.highlightedIndex = -1;
                const trimmed = (this.query || '').trim().toLowerCase();
                const cleanQuery = trimmed.replace(/\[.*?\]/g, '').trim();

                const exact = this.residents.find(r => 
                    (r.id_warga && r.id_warga.toLowerCase() === trimmed) ||
                    (r.nama && r.nama.toLowerCase() === trimmed) ||
                    (r.nama && r.nama.toLowerCase() === cleanQuery)
                );

                if (exact) {
                    this.selectedId = exact.id;
                    if (this.$wire) this.$wire.set('form_resident_id', exact.id);
                } else {
                    this.selectedId = null;
                    if (this.$wire) this.$wire.set('form_resident_id', null);
                }

                if (this.$wire) {
                    this.$wire.set('form_resident_query', this.query);
                }
            },

            onKeyDown(e) {
                if (!this.isOpen && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
                    this.isOpen = true;
                    return;
                }
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (this.highlightedIndex < this.filteredResidents.length - 1) {
                        this.highlightedIndex++;
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (this.highlightedIndex > 0) {
                        this.highlightedIndex--;
                    }
                } else if (e.key === 'Enter') {
                    if (this.isOpen && this.highlightedIndex >= 0 && this.highlightedIndex < this.filteredResidents.length) {
                        e.preventDefault();
                        this.selectResident(this.filteredResidents[this.highlightedIndex]);
                    }
                } else if (e.key === 'Escape') {
                    this.isOpen = false;
                }
            }
        };
    };
</script>
