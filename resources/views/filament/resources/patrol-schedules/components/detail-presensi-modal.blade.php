{{-- ====================================================================
     MODAL DETAIL, PRESENSI & LAPORAN RONDA MALAM
     Mencatat kehadiran personil aktual, warga pengganti, dan laporan kejadian
     ==================================================================== --}}
<div 
    x-show="detailModalOpen" 
    x-cloak 
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto" 
    aria-labelledby="presensi-modal-title" 
    role="dialog" 
    aria-modal="true"
>
    {{-- Backdrop Blur --}}
    <div 
        x-show="detailModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="detailModalOpen = false; $wire.closeDetail()"
    ></div>

    {{-- Dialog Container --}}
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div 
            x-show="detailModalOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all my-6 w-full sm:max-w-3xl border border-slate-200 flex flex-col max-h-[92vh]" 
            @click.stop
        >
            {{-- Header Modal (Navy #0B3A85) --}}
            <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="p-1.5 rounded-lg bg-white/10 text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                            Keamanan Lingkungan RT 15
                        </div>
                        <h3 class="text-sm sm:text-base font-extrabold text-white leading-tight" id="presensi-modal-title">
                            Presensi & Pelaksanaan Ronda Malam
                        </h3>
                    </div>
                </div>
                <button 
                    type="button" 
                    @click="detailModalOpen = false; $wire.closeDetail()" 
                    class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" 
                    title="Tutup Modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Asynchronous Skeleton Loader while Livewire fetches selectedSchedule --}}
            <div wire:loading.flex wire:target="openPresensi,openDetail" class="p-6 flex-col space-y-4 animate-pulse">
                <div class="grid grid-cols-3 gap-3">
                    <div class="h-16 bg-slate-100 rounded-xl"></div>
                    <div class="h-16 bg-slate-100 rounded-xl"></div>
                    <div class="h-16 bg-slate-100 rounded-xl"></div>
                </div>
                <div class="h-14 bg-slate-100 rounded-xl"></div>
                <div class="h-4 w-40 bg-slate-200 rounded"></div>
                <div class="h-32 bg-slate-100 rounded-xl"></div>
            </div>

            {{-- Modal Body Content --}}
            <div wire:loading.remove wire:target="openPresensi,openDetail" class="overflow-y-auto p-4 sm:p-5 space-y-4 text-xs text-slate-700">
                @if($selectedSchedule)
                    {{-- 1. Kartu Informasi Jadwal & Status Pelaksanaan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Regu Bertugas</div>
                            <div class="text-xs sm:text-sm font-black text-[#0B3A85] mt-0.5 truncate">
                                {{ $selectedSchedule->regu }}
                            </div>
                            <div class="text-[10px] text-slate-500 mt-0.5">
                                Pos: {{ $selectedSchedule->pos_lokasi ?: 'Pos Utama RT 15' }}
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Tanggal Pelaksanaan</div>
                            <input 
                                type="date" 
                                wire:model.live="detail_tanggal" 
                                class="mt-1 w-full text-xs font-bold rounded-lg border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-1"
                            >
                            @if(!empty($detail_tanggal))
                                <div class="text-[10px] text-[#0B3A85] font-semibold mt-1">
                                    {{ \Carbon\Carbon::parse($detail_tanggal)->translatedFormat('l, d F Y') }}
                                </div>
                            @endif
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Status Pelaksanaan:
                                </label>
                                <select 
                                    wire:model.live="detail_status"
                                    class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-1.5"
                                >
                                    <option value="berlangsung">⚡ Berlangsung (Malam Ini)</option>
                                    <option value="selesai">✓ Selesai</option>
                                    <option value="dibatalkan">✕ Dibatalkan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Form Alasan Pembatalan (Jika Status Dibatalkan) --}}
                    @if($detail_status === 'dibatalkan')
                        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 space-y-1.5 transition-all">
                            <label class="block font-bold text-xs">
                                Alasan Pembatalan Ronda Malam: <span class="text-rose-600">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="detail_alasan_batal"
                                placeholder="Contoh: Cuaca ekstrem hujan badai lebat / banjir permukiman"
                                class="w-full text-xs rounded-xl border-rose-300 bg-white text-slate-800 focus:border-rose-500 focus:ring focus:ring-rose-200 py-2 px-3"
                            >
                            @error('detail_alasan_batal')
                                <span class="text-rose-600 text-[11px] font-semibold">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    {{-- 2. Presensi Personil Ronda --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <h4 class="font-extrabold text-sm text-[#0F1F3D]">
                                    Daftar Presensi Personil Ronda
                                </h4>
                                <p class="text-[11px] text-slate-500">
                                    Tandai kehadiran masing-masing petugas atau tambahkan personil pengganti jika ada yang berhalangan.
                                </p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button 
                                    type="button" 
                                    wire:click="markAllHadir"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 transition-colors cursor-pointer"
                                    title="Tandai seluruh personil hadir"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Tandai Semua Hadir</span>
                                </button>

                                <button 
                                    type="button" 
                                    wire:click="toggleAddMemberForm"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#0B3A85] text-xs font-bold border border-blue-200 transition-colors cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>{{ $showAddMemberForm ? 'Tutup Form' : '+ Tambah Pengganti' }}</span>
                                </button>
                            </div>
                        </div>

                        {{-- Form Penambahan Personil Pengganti --}}
                        @if($showAddMemberForm)
                            <div class="p-3.5 rounded-xl bg-blue-50/50 border border-blue-200 space-y-3 transition-all">
                                <div class="font-bold text-xs text-[#0B3A85] flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    <span>Tambah Petugas Ronda Pengganti / Tambahan</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                    <div class="sm:col-span-2">
                                        <label class="block font-bold text-[11px] text-slate-700 mb-1">
                                            Warga yang Hadir Bertugas: <span class="text-rose-500">*</span>
                                        </label>
                                        <select 
                                            wire:model="new_resident_id" 
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-1.5"
                                        >
                                            <option value="">-- Pilih Warga RT 15 --</option>
                                            @foreach($allResidents as $res)
                                                <option value="{{ $res->id }}">{{ $res->nama }} ({{ $res->nik }})</option>
                                            @endforeach
                                        </select>
                                        @error('new_resident_id')
                                            <span class="text-rose-600 text-[10px] mt-0.5 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block font-bold text-[11px] text-slate-700 mb-1">Status Kehadiran:</label>
                                        <select 
                                            wire:model="new_status_kehadiran" 
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-1.5"
                                        >
                                            <option value="hadir">Hadir</option>
                                            <option value="izin">Izin</option>
                                            <option value="sakit">Sakit</option>
                                            <option value="alpa">Alpa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <div>
                                        <label class="block font-bold text-[11px] text-slate-700 mb-1">Menggantikan Siapa? (Opsional):</label>
                                        <select 
                                            wire:model="new_menggantikan_resident_id" 
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-1.5"
                                        >
                                            <option value="">-- Bukan Pengganti (Personil Tambahan) --</option>
                                            @foreach($presensi_members as $orig)
                                                @if(!$orig['is_pengganti'])
                                                    <option value="{{ $orig['resident_id'] }}">Menggantikan: {{ $orig['nama'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-[11px] text-slate-700 mb-1">Keterangan / Alasan:</label>
                                        <input 
                                            type="text" 
                                            wire:model="new_keterangan" 
                                            placeholder="Contoh: Menggantikan karena tugas dinas"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-1.5 px-2.5"
                                        >
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2 pt-1">
                                    <button 
                                        type="button" 
                                        wire:click="resetNewMemberForm"
                                        class="px-2.5 py-1.5 rounded-lg border border-slate-300 text-slate-600 text-xs font-bold hover:bg-slate-100 transition-colors cursor-pointer"
                                    >
                                        Batal
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="addMemberToSchedule"
                                        class="px-3 py-1.5 rounded-lg bg-[#0B3A85] text-white text-xs font-bold hover:bg-[#07275E] transition-colors cursor-pointer"
                                    >
                                        Tambahkan ke Daftar
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- Tabel Presensi Interaktif --}}
                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-[11px] font-bold text-slate-600 uppercase border-b border-slate-200">
                                    <tr>
                                        <th class="py-2.5 px-3">Nama Petugas Ronda</th>
                                        <th class="py-2.5 px-3 text-center">Status Kehadiran</th>
                                        <th class="py-2.5 px-3">Keterangan / Pengganti</th>
                                        <th class="py-2.5 px-2.5 text-center w-12">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    @forelse($presensi_members as $index => $m)
                                        <tr class="hover:bg-slate-50/70 transition-colors {{ $m['status_kehadiran'] === 'hadir' ? 'bg-emerald-50/20' : ($m['status_kehadiran'] === 'alpa' ? 'bg-rose-50/20' : '') }}">
                                            <td class="py-2 px-3">
                                                <div class="font-extrabold text-slate-900 flex items-center gap-1.5">
                                                    <span>{{ $m['nama'] }}</span>
                                                    @if($m['is_pengganti'])
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                            Pengganti
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] text-slate-400">NIK: {{ $m['nik'] }}</div>
                                            </td>

                                            <td class="py-2 px-3 text-center">
                                                <div class="inline-flex rounded-lg border border-slate-200 p-0.5 bg-slate-50">
                                                    @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpa' => 'Alpa'] as $val => $lbl)
                                                        <button 
                                                            type="button"
                                                            wire:click="setMemberStatus({{ $index }}, '{{ $val }}')"
                                                            class="px-2 py-1 text-[11px] font-bold rounded-md transition-all cursor-pointer {{ $m['status_kehadiran'] === $val ? ($val === 'hadir' ? 'bg-emerald-600 text-white shadow-2xs' : ($val === 'alpa' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-amber-500 text-white shadow-2xs')) : 'text-slate-600 hover:text-slate-900' }}"
                                                        >
                                                            {{ $lbl }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </td>

                                            <td class="py-2 px-3">
                                                @if($m['is_pengganti'] && $m['menggantikan_nama'])
                                                    <div class="text-[10px] text-amber-700 font-semibold mb-1">
                                                        Menggantikan: {{ $m['menggantikan_nama'] }}
                                                    </div>
                                                @endif
                                                <input 
                                                    type="text" 
                                                    wire:model.defer="presensi_members.{{ $index }}.keterangan"
                                                    placeholder="Catatan izin / mutasi hadir..."
                                                    class="w-full text-[11px] rounded-lg border-slate-200 py-1 px-2 text-slate-700 focus:border-[#0B3A85] focus:ring-0"
                                                >
                                            </td>

                                            <td class="py-2 px-2.5 text-center">
                                                <button 
                                                    type="button" 
                                                    wire:click="removeMemberFromSchedule({{ $index }})"
                                                    class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                                    title="Hapus personil dari daftar malam ini"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-slate-400">
                                                Belum ada personil yang ditugaskan pada malam ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 3. Laporan Kejadian Malam Ronda --}}
                    <div>
                        <label class="block font-extrabold text-sm text-[#0F1F3D] mb-1">
                            Laporan Kejadian / Evaluasi Ronda Malam
                        </label>
                        <p class="text-[11px] text-slate-500 mb-1.5">
                            Catat hasil pemantauan lingkungan, portal gang, tamu yang menginap, atau insiden keamanan yang terjadi.
                        </p>
                        <textarea 
                            wire:model="detail_laporan_kejadian" 
                            rows="3" 
                            placeholder="Contoh: Patroli gang perumahan aman terkendali. Pintu gerbang utama dikunci pukul 23:00 WIB. Terdapat 1 tamu menginap di Blok C No. 4 dan telah tercatat tertib."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-[#0B3A85] focus:ring focus:ring-[#0B3A85]/20 py-2 px-3 text-slate-800"
                        ></textarea>
                    </div>
                @endif
            </div>

            {{-- Footer Modal --}}
            <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 flex items-center justify-between shrink-0">
                <button 
                    type="button" 
                    @click="detailModalOpen = false; $wire.closeDetail()" 
                    class="px-3.5 py-2 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors cursor-pointer"
                >
                    Tutup
                </button>

                <button 
                    type="button" 
                    wire:click="savePresensiAndReport"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs hover:shadow-md cursor-pointer flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Presensi & Laporan</span>
                </button>
            </div>
        </div>
    </div>
</div>
