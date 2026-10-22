@php
    $let = $detailLetter;
@endphp

<div 
    x-show="detailLetterModalOpen" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-letter-detail-title" 
    role="dialog" 
    aria-modal="true"
    @open-detail-letter-modal.window="detailLetterModalOpen = true"
    @close-detail-letter-modal.window="detailLetterModalOpen = false"
    @keydown.escape.window="detailLetterModalOpen = false; $wire.closeDetailLetter()"
>
    {{-- Backdrop Blur --}}
    <div 
        x-show="detailLetterModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="detailLetterModalOpen = false; $wire.closeDetailLetter()"
    ></div>

    {{-- Dialog Container --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div 
            x-show="detailLetterModalOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-2xl border border-slate-200"
            @click.stop
        >
            {{-- Skeleton Loader saat openDetailLetter --}}
            <div wire:loading.flex wire:target="openDetailLetter" class="p-6 flex-col space-y-4 animate-pulse">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="h-6 w-12 bg-slate-200 rounded-lg"></div>
                        <div class="h-6 w-48 bg-slate-200 rounded"></div>
                    </div>
                </div>
                <div class="h-4 bg-slate-100 rounded w-1/2"></div>
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="h-12 bg-slate-100 rounded-xl"></div>
                    <div class="h-12 bg-slate-100 rounded-xl"></div>
                </div>
                <div class="h-24 bg-slate-100 rounded-xl"></div>
            </div>

            <div wire:loading.remove wire:target="openDetailLetter">
                @if($let)
                {{-- Header Modal (Navy Theme #0B3A85) --}}
                <div class="bg-[#0B3A85] text-white px-5 py-3.5 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-lg bg-white/10 text-white font-mono font-extrabold text-[11px] border border-white/20">
                            {{ $let->letterType?->kode ?? 'SP' }}
                        </span>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                                Detail Surat Pengantar RT 15
                            </div>
                            <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight" id="modal-letter-detail-title">
                                {{ $let->letterType?->nama ?? 'Surat Pengantar' }}
                            </h3>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        @click="detailLetterModalOpen = false; $wire.closeDetailLetter()" 
                        class="text-white/70 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition-colors cursor-pointer"
                        title="Tutup dialog"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="p-4 sm:p-5 space-y-4 max-h-[75vh] overflow-y-auto">
                    {{-- Status Banner & Nomor Surat --}}
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nomor Resmi Surat</div>
                            <div class="text-sm font-mono font-extrabold text-[#0B3A85] mt-0.5">
                                {{ $let->nomor_surat ?? '(Belum Diterbitkan / Menunggu Persetujuan)' }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-500">Status:</span>
                            @if($let->status === 'diajukan')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Diajukan
                                </span>
                            @elseif($let->status === 'diperiksa')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    Diperiksa
                                </span>
                            @elseif($let->status === 'disetujui')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Disetujui
                                </span>
                            @elseif($let->status === 'ditolak')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ ucfirst($let->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- 1. INFORMASI PEMOHON --}}
                    <div class="bg-white rounded-xl border border-slate-200 p-3.5 space-y-2.5">
                        <div class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                            <span class="text-xs font-extrabold text-[#0F1F3D] uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Data Warga Pemohon
                            </span>
                            @if($let->resident?->id_warga)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-[#EBF3FC] text-[#0B3A85] border border-[#D6E5FF]">
                                    {{ $let->resident->id_warga }}
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-slate-400 block text-[10.5px]">Nama Lengkap</span>
                                <span class="font-bold text-[#0F1F3D] text-sm">{{ $let->resident?->nama ?? '-' }}</span>
                            </div>

                            <div>
                                <span class="text-slate-400 block text-[10.5px]">Nomor Induk Kependudukan (NIK)</span>
                                <span class="font-mono font-bold text-slate-700 select-all">{{ $let->resident?->masked_nik ?? '-' }}</span>
                            </div>

                            <div>
                                <span class="text-slate-400 block text-[10.5px]">Kartu Keluarga</span>
                                @if($let->resident?->household)
                                    <a 
                                        href="{{ url('/admin/households/' . $let->resident->household_id) }}" 
                                        class="inline-flex items-center gap-1 text-[#0B3A85] font-bold hover:underline"
                                    >
                                        <span>{{ $let->resident->household->id_kk }} ({{ $let->resident->household->nomor_kk }})</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-slate-500 italic">Tanpa KK / Pendatang</span>
                                @endif
                            </div>

                            <div>
                                <span class="text-slate-400 block text-[10.5px]">No. Telepon / WhatsApp</span>
                                @if($let->resident?->telepon)
                                    <a 
                                        href="{{ $let->resident->wa_url }}" 
                                        target="_blank" 
                                        rel="noopener noreferrer" 
                                        class="inline-flex items-center gap-1 text-[#1B7A3A] font-bold hover:underline"
                                    >
                                        <span class="font-mono">{{ $let->resident->telepon }}</span>
                                        <span class="text-[10px] text-slate-400 font-normal">(Chat WA)</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </div>

                            <div class="sm:col-span-2">
                                <span class="text-slate-400 block text-[10.5px]">Alamat Tempat Tinggal</span>
                                <span class="text-slate-700 font-medium">
                                    {{ $let->resident?->household?->alamat ?? $let->resident?->alamat_domisili ?? 'Lingkungan RT 15 Pematang Sulur' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. INFORMASI PERMOHONAN & KEPERLUAN --}}
                    <div class="bg-white rounded-xl border border-slate-200 p-3.5 space-y-2.5">
                        <div class="pb-1.5 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-extrabold text-[#0F1F3D] uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Keperluan Permohonan
                            </span>

                            <span class="text-[11px] text-slate-400">
                                Diajukan: {{ $let->created_at?->format('d/m/Y H:i') }} WIB
                            </span>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 text-xs font-medium text-[#0F1F3D] leading-relaxed">
                            {{ $let->keperluan }}
                        </div>

                        @if($let->catatan_pengurus)
                            <div>
                                <span class="text-slate-400 block text-[10.5px]">Catatan Pengurus RT</span>
                                <p class="text-xs text-slate-700 italic bg-amber-50/50 p-2.5 rounded-lg border border-amber-200/50 mt-0.5">
                                    {{ $let->catatan_pengurus }}
                                </p>
                            </div>
                        @endif

                        @if($let->alasan_tolak && $let->status === 'ditolak')
                            <div>
                                <span class="text-rose-500 block text-[10.5px] font-bold">Alasan Penolakan</span>
                                <p class="text-xs text-rose-700 bg-rose-50 p-2.5 rounded-lg border border-rose-200 mt-0.5">
                                    {{ $let->alasan_tolak }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- 3. INFORMASI PENGESAHAN --}}
                    @if($let->status === 'disetujui')
                        <div class="bg-emerald-50/50 rounded-xl border border-emerald-200 p-3.5 space-y-2">
                            <span class="text-xs font-extrabold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Pengesahan Resmi RT 15
                            </span>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                                <div>
                                    <span class="text-emerald-700/70 block text-[10.5px]">Disetujui Oleh</span>
                                    <span class="font-bold text-emerald-950">{{ $let->approver?->name ?? 'Ketua RT 15' }}</span>
                                </div>

                                <div>
                                    <span class="text-emerald-700/70 block text-[10.5px]">Tanggal Disetujui</span>
                                    <span class="font-bold text-emerald-950">{{ $let->tanggal_disetujui?->format('d F Y, H:i') ?? '-' }} WIB</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer Modal Action Buttons --}}
                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between gap-2">
                    <button 
                        type="button" 
                        @click="detailLetterModalOpen = false; $wire.closeDetailLetter()" 
                        class="px-4 py-2 rounded-xl bg-white text-slate-700 border border-slate-300 hover:bg-slate-100 text-xs font-bold transition-all cursor-pointer"
                    >
                        Tutup
                    </button>

                    <div class="flex items-center gap-2">
                        @if(in_array($let->status, ['diajukan', 'diperiksa']))
                            <button 
                                type="button" 
                                @click="detailLetterModalOpen = false; rejectLetterModalOpen = true"
                                wire:click="openRejectLetter({{ $let->id }})" 
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-300 hover:bg-rose-100 text-xs font-bold transition-all cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Tolak</span>
                            </button>

                            <button 
                                type="button" 
                                @click="detailLetterModalOpen = false; approveLetterModalOpen = true"
                                wire:click="openApproveLetter({{ $let->id }})" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Setujui & Terbitkan</span>
                            </button>
                        @endif

                        @if($let->status === 'disetujui')
                            <a 
                                href="{{ route('letters.print', $let->id) }}" 
                                target="_blank" 
                                rel="noopener noreferrer" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0B3A85] hover:bg-[#07275E] text-white text-xs font-bold transition-all shadow-xs cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                <span>Cetak Lembar Surat</span>
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
