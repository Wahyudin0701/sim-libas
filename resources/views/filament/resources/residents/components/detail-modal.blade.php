@php
    $res = $detailResident ?? $resident ?? null;
@endphp

<div 
    x-show="detailResidentModalOpen" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-detail-title" 
    role="dialog" 
    aria-modal="true"
    @open-detail-resident-modal.window="detailResidentModalOpen = true"
    @close-detail-resident-modal.window="detailResidentModalOpen = false"
    @keydown.escape.window="detailResidentModalOpen = false; $wire.closeDetailResident()"
>
    {{-- Backdrop Blur --}}
    <div 
        x-show="detailResidentModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="detailResidentModalOpen = false; $wire.closeDetailResident()"
    ></div>

    {{-- Dialog Container --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div 
            x-show="detailResidentModalOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-xl border border-slate-200"
            @click.stop
        >
            {{-- Skeleton Loader saat openDetailResident --}}
            <div wire:loading.flex wire:target="openDetailResident" class="p-6 flex-col space-y-4 animate-pulse">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="h-6 w-16 bg-slate-200 rounded-lg"></div>
                        <div class="h-6 w-40 bg-slate-200 rounded"></div>
                    </div>
                </div>
                <div class="h-4 bg-slate-100 rounded w-1/2"></div>
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="h-12 bg-slate-100 rounded-xl"></div>
                    <div class="h-12 bg-slate-100 rounded-xl"></div>
                </div>
                <div class="h-24 bg-slate-100 rounded-xl"></div>
            </div>

            <div wire:loading.remove wire:target="openDetailResident">
                @if($res)
                {{-- Header Modal (Navy Theme #0B3A85) --}}
                <div class="bg-[#0B3A85] text-white px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-lg bg-white/10 text-white font-mono font-extrabold text-[11px] border border-white/20">
                            {{ $res->id_warga ?? 'W-' }}
                        </span>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                Detail Data Warga
                            </div>
                            <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="modal-detail-title">
                                {{ $res->nama }}
                            </h3>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        @click="detailResidentModalOpen = false; $wire.closeDetailResident()" 
                        class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                        title="Tutup dialog"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="p-4 sm:p-5 space-y-3 max-h-[75vh] overflow-y-auto">
                    {{-- Badges Ringkas --}}
                    <div class="flex flex-wrap items-center gap-1.5 pb-2.5 border-b border-slate-100">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $res->status_kependudukan === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                            Status: <span class="ml-1">{{ ucfirst($res->status_kependudukan ?? 'Aktif') }}</span>
                        </span>

                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF]">
                            {{ ucwords(str_replace('_', ' ', $res->hubungan_keluarga ?? '-')) }}
                        </span>

                        @if($res->is_kepala_keluarga && $res->hubungan_keluarga !== 'kepala_keluarga')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                Kepala Keluarga
                            </span>
                        @endif

                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-50 text-slate-600 border border-slate-200">
                            {{ $res->jenis_kelamin === 'L' ? 'Laki-laki' : ($res->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
                        </span>
                    </div>

                    {{-- Informasi Kependudukan --}}
                    <div>
                        <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Informasi Kependudukan</h4>
                        <dl class="divide-y divide-slate-100 text-xs bg-slate-50/70 rounded-xl p-3 border border-slate-200/80">
                            {{-- NIK --}}
                            <div class="py-1.5 flex items-center justify-between gap-3">
                                <dt class="text-slate-500 font-medium shrink-0">NIK</dt>
                                <dd class="flex items-center gap-1.5">
                                    <span class="font-mono font-bold text-slate-900 select-all">{{ $res->nik ?? '-' }}</span>
                                    @if($res->nik)
                                        <button 
                                            type="button" 
                                            onclick="navigator.clipboard.writeText('{{ $res->nik }}'); alert('NIK berhasil disalin ke clipboard!');" 
                                            class="px-1.5 py-0.5 rounded text-[10px] font-bold text-[#0B3A85] bg-blue-50 hover:bg-blue-100 border border-blue-200 cursor-pointer"
                                            title="Salin NIK"
                                        >
                                            Salin
                                        </button>
                                    @endif
                                </dd>
                            </div>

                            {{-- Tempat, Tanggal Lahir & Usia --}}
                            <div class="py-1.5 flex items-center justify-between gap-3">
                                <dt class="text-slate-500 font-medium shrink-0">TTL & Usia</dt>
                                <dd class="font-semibold text-slate-800 text-right">
                                    {{ $res->tempat_lahir ?? 'Jambi' }}, {{ $res->tanggal_lahir ? $res->tanggal_lahir->translatedFormat('d F Y') : '-' }} 
                                    @if($res->tanggal_lahir)
                                        <span class="text-slate-500 font-normal">({{ $res->tanggal_lahir->age }} Tahun)</span>
                                    @endif
                                </dd>
                            </div>

                            {{-- Agama --}}
                            <div class="py-1.5 flex items-center justify-between gap-3">
                                <dt class="text-slate-500 font-medium shrink-0">Agama</dt>
                                <dd class="font-semibold text-slate-800 text-right">{{ $res->agama ?? 'Islam' }}</dd>
                            </div>

                            {{-- Status Perkawinan --}}
                            <div class="py-1.5 flex items-center justify-between gap-3">
                                <dt class="text-slate-500 font-medium shrink-0">Status Kawin</dt>
                                <dd class="font-semibold text-slate-800 text-right">{{ ucfirst($res->status_perkawinan ?? 'Kawin') }}</dd>
                            </div>

                            {{-- Pekerjaan --}}
                            <div class="py-1.5 flex items-center justify-between gap-3">
                                <dt class="text-slate-500 font-medium shrink-0">Pekerjaan</dt>
                                <dd class="font-semibold text-slate-800 text-right">{{ $res->pekerjaan ?: '-' }}</dd>
                            </div>

                            {{-- Pendidikan Terakhir --}}
                            <div class="py-1.5 flex items-center justify-between gap-3">
                                <dt class="text-slate-500 font-medium shrink-0">Pendidikan Terakhir</dt>
                                <dd class="font-semibold text-slate-800 text-right">{{ $res->pendidikan_terakhir ?: '-' }}</dd>
                            </div>

                            {{-- Kontak Telepon / WhatsApp --}}
                            <div class="py-1.5 flex items-center justify-between gap-3">
                                <dt class="text-slate-500 font-medium shrink-0">Telepon / WhatsApp</dt>
                                <dd class="text-right">
                                    @if($res->wa_url)
                                        <a href="{{ $res->wa_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-bold text-emerald-700 hover:underline">
                                            <span>{{ $res->telepon }}</span>
                                            <span class="text-[10px] text-emerald-600 font-normal">(Chat WA)</span>
                                        </a>
                                    @elseif($res->telepon)
                                        <span class="text-slate-700 font-mono font-medium">{{ $res->telepon }}</span>
                                    @else
                                        <span class="text-slate-400 italic">-</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Kartu Keluarga Terdaftar --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kartu Keluarga Terdaftar</h4>
                            @if($res->household)
                                <span class="text-[11px] font-mono font-bold text-[#0B3A85]">{{ $res->household->id_kk }}</span>
                            @endif
                        </div>

                        @if($res->household)
                            <div class="bg-blue-50/50 rounded-xl p-3 border border-blue-100 text-xs space-y-1.5">
                                <div class="flex items-start justify-between gap-3">
                                    <span class="text-slate-500 font-medium shrink-0">Nomor KK:</span>
                                    <span class="font-mono font-bold text-slate-900 select-all">{{ $res->household->no_kk }}</span>
                                </div>
                                <div class="flex items-start justify-between gap-3">
                                    <span class="text-slate-500 font-medium shrink-0">Alamat Tempat Tinggal:</span>
                                    <span class="font-semibold text-slate-900 text-right">{{ $res->household->alamat ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-slate-500 font-medium shrink-0">RT / RW & Wilayah:</span>
                                    <span class="font-medium text-slate-700 text-right">
                                        {{ $res->household->rt_rw ?? 'RT 15 / RW 04' }} &bull; {{ $res->household->kelurahan ?? 'Pematang Sulur' }}, {{ $res->household->kecamatan ?? 'Telanaipura' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-slate-500 font-medium shrink-0">Status Rumah:</span>
                                    <span class="font-bold text-slate-700">Rumah {{ ucfirst($res->household->status_hunian ?? 'Tetap') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="p-3 bg-amber-50/80 rounded-xl border border-amber-200 text-xs space-y-1.5">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-amber-800 font-medium shrink-0">Kategori Tempat Tinggal:</span>
                                    <span class="font-bold text-amber-900">{{ $res->status_tempat_tinggal ? 'Tanpa KK (' . ucfirst($res->status_tempat_tinggal) . ')' : 'Tanpa KK di RT 15' }}</span>
                                </div>
                                <div class="flex items-start justify-between gap-3">
                                    <span class="text-amber-800 font-medium shrink-0">Alamat Domisili di RT 15:</span>
                                    <span class="font-semibold text-amber-950 text-right">{{ $res->alamat_domisili ?: 'Tidak dicantumkan' }}</span>
                                </div>
                                <p class="text-[10.5px] text-amber-700/90 pt-1 border-t border-amber-200/60">
                                    Warga berdomisili di lingkungan RT 15 tanpa terhubung ke buku Kartu Keluarga setempat.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="bg-slate-50 px-5 py-2.5 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button 
                        type="button"
                        @click="detailResidentModalOpen = false; modalEditResidentOpen = true"
                        wire:click="openEditResident({{ $res->id }})" 
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#0B3A85] transition-colors cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Ubah Data</span>
                    </button>

                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            @click="detailResidentModalOpen = false; $wire.closeDetailResident()" 
                            class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-all cursor-pointer"
                        >
                            Tutup
                        </button>

                        @if($res->household_id)
                            <a 
                                href="{{ url('/admin/households/' . $res->household_id) }}" 
                                class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs hover:shadow-md cursor-pointer"
                            >
                                <span>Kartu Keluarga</span>
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-8 text-center text-slate-400">
                    <div class="animate-pulse flex flex-col items-center gap-2">
                        <div class="w-10 h-10 bg-slate-200 rounded-full"></div>
                        <div class="h-4 bg-slate-200 rounded w-32"></div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
