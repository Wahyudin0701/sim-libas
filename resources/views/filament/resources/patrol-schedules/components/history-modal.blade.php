{{-- ====================================================================
     MODAL RIWAYAT PELAKSANAAN RONDA
     Menampilkan catatan riwayat ronda aktual yang pernah tercatat
     ==================================================================== --}}
<div 
    x-show="historyModalOpen" 
    x-cloak 
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto" 
    aria-labelledby="history-modal-title" 
    role="dialog" 
    aria-modal="true"
>
    {{-- Backdrop Blur --}}
    <div 
        x-show="historyModalOpen"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer" 
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        @click="historyModalOpen = false; $wire.closeHistory()"
    ></div>

    {{-- Dialog Container --}}
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div 
            x-show="historyModalOpen"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                            Riwayat Pelaksanaan & Evaluasi
                        </div>
                        <h3 class="text-sm sm:text-base font-extrabold text-white leading-tight" id="history-modal-title">
                            Riwayat Ronda: {{ $historySchedule?->regu }} ({{ $historySchedule?->nama_hari }})
                        </h3>
                    </div>
                </div>
                <button 
                    type="button" 
                    @click="historyModalOpen = false; $wire.closeHistory()" 
                    class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" 
                    title="Tutup Modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body Modal --}}
            <div class="overflow-y-auto p-4 sm:p-5 space-y-4 text-xs text-slate-700">
                @if($historySchedule)
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                        <div>
                            <span class="font-extrabold text-[#0B3A85] text-sm">{{ $historySchedule->regu }}</span>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Pos: {{ $historySchedule->pos_lokasi }} &bull; Waktu: {{ $historySchedule->waktu_mulai }} - {{ $historySchedule->waktu_selesai }} WIB
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-100 text-[#0B3A85]">
                            {{ $historySchedule->executions->count() }} Kali Pelaksanaan
                        </span>
                    </div>

                    @if($historySchedule->executions->isEmpty())
                        <div class="py-12 text-center text-slate-400">
                            <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-bold text-slate-600 text-sm">Belum Ada Riwayat Pelaksanaan</p>
                            <p class="text-xs text-slate-400 mt-0.5">Presensi pelaksanaan ronda yang tersimpan pada tanggal aktual akan otomatis muncul di sini.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($historySchedule->executions as $exec)
                                <div class="p-4 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all shadow-2xs space-y-2.5">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full {{ $exec->status === 'selesai' ? 'bg-emerald-500' : ($exec->status === 'dibatalkan' ? 'bg-rose-500' : 'bg-blue-500 animate-pulse') }}"></span>
                                            <span class="font-black text-sm text-[#0F1F3D]">
                                                {{ \Carbon\Carbon::parse($exec->tanggal)->translatedFormat('l, d F Y') }}
                                            </span>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-extrabold uppercase {{ $exec->status === 'selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($exec->status === 'dibatalkan' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                                            {{ $exec->status }}
                                        </span>
                                    </div>

                                    @if($exec->status === 'dibatalkan' && $exec->alasan_batal)
                                        <div class="p-2.5 rounded-lg bg-rose-50 text-rose-800 text-[11px] border border-rose-200">
                                            <span class="font-bold">Alasan Dibatalkan:</span> {{ $exec->alasan_batal }}
                                        </div>
                                    @endif

                                    @if($exec->laporan_kejadian)
                                        <div class="p-2.5 rounded-lg bg-slate-50 text-slate-700 text-[11px] border border-slate-200">
                                            <span class="font-bold text-slate-900">Laporan Kejadian:</span> {{ $exec->laporan_kejadian }}
                                        </div>
                                    @endif

                                    {{-- Daftar Kehadiran --}}
                                    <div>
                                        <div class="text-[10.5px] font-bold text-slate-400 uppercase mb-1">Kehadiran Petugas:</div>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($exec->members as $em)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-semibold border {{ $em->status_kehadiran === 'hadir' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($em->status_kehadiran === 'izin' ? 'bg-amber-50 text-amber-800 border-amber-200' : ($em->status_kehadiran === 'sakit' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-rose-50 text-rose-800 border-rose-200')) }}">
                                                    <span>{{ $em->resident?->nama ?? 'Warga' }}</span>
                                                    @if($em->is_pengganti)
                                                        <span class="text-[9px] bg-amber-200 text-amber-900 px-1 rounded font-bold">Pengganti</span>
                                                    @endif
                                                    <span class="text-[9.5px] opacity-75">({{ ucfirst($em->status_kehadiran) }})</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            {{-- Footer Modal --}}
            <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 flex justify-end shrink-0">
                <button 
                    type="button" 
                    @click="historyModalOpen = false; $wire.closeHistory()" 
                    class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold transition-colors cursor-pointer"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
