@props([
    'title',
    'category' => 'PANDUAN PENGURUS RT 15',
    'description' => null,
    'steps' => [],
    'role' => null,
    'tips' => [],
])

<div x-data="{ guideOpen: false }" class="inline-flex items-center">
    {{-- Tombol Ikon Tanda Seru (!) di Baris Subtitle --}}
    <button 
        type="button" 
        @click="guideOpen = true" 
        class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-blue-100/90 hover:bg-[#0B3A85] text-[#0B3A85] hover:text-white border border-blue-300/60 hover:border-[#0B3A85] transition-all duration-150 text-[10px] font-black cursor-pointer shadow-2xs hover:scale-110 active:scale-95"
        title="Buka Panduan Halaman: {{ $title }}"
        aria-label="Panduan Halaman: {{ $title }}"
    >
        !
    </button>

    {{-- MODAL POPUP PANDUAN HALAMAN --}}
    <div 
        x-show="guideOpen" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto normal-case font-normal tracking-normal text-left"
        aria-labelledby="modal-guide-title-{{ \Illuminate\Support\Str::slug($title) }}" 
        role="dialog" 
        aria-modal="true"
        @keydown.escape.window="guideOpen = false"
        style="display: none;"
    >
        {{-- Backdrop Blur --}}
        <div 
            x-show="guideOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"
            style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
            @click="guideOpen = false"
        ></div>

        {{-- Dialog Modal Wrapper --}}
        <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
            <div 
                x-show="guideOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-2xl border border-slate-200"
                @click.stop
            >
                {{-- Header Modal (Navy Standard #0B3A85) --}}
                <div class="bg-gradient-to-r from-[#0B3A85] to-[#08295E] text-white px-5 sm:px-6 py-4 flex items-center justify-between border-b border-blue-900/40">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white border border-white/20 shrink-0 shadow-inner">
                            <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold uppercase tracking-wider text-blue-200 flex items-center gap-1.5">
                                <span>PANDUAN PENGURUS RT 15</span>
                                <span>•</span>
                                <span class="text-white font-semibold">{{ $category }}</span>
                            </div>
                            <h2 class="text-base sm:text-lg font-black text-white leading-tight" id="modal-guide-title-{{ \Illuminate\Support\Str::slug($title) }}">
                                {{ $title }}
                            </h2>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        @click="guideOpen = false" 
                        class="p-1.5 rounded-lg text-blue-200 hover:text-white hover:bg-white/10 transition-colors cursor-pointer"
                        title="Tutup (Esc)"
                    >
                        <span class="sr-only">Tutup</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="p-5 sm:p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    {{-- 1. Penjelasan Ringkas / Tujuan --}}
                    @if($description)
                        <div class="bg-blue-50/70 border border-blue-200/80 rounded-xl p-3.5 flex items-start gap-3">
                            <div class="w-6 h-6 rounded-lg bg-[#0B3A85] text-white flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                                i
                            </div>
                            <div class="text-xs sm:text-[13px] text-slate-700 leading-relaxed">
                                <span class="font-bold text-[#0B3A85] block mb-0.5">Tujuan & Fungsi Halaman:</span>
                                {{ $description }}
                            </div>
                        </div>
                    @endif

                    {{-- 2. Alur Kerja Utama / Fitur --}}
                    @if(!empty($steps))
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0F1F3D] mb-2.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#0B3A85]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span>Alur Penggunaan & Fitur Utama</span>
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                @foreach($steps as $index => $step)
                                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-2.5">
                                        <div class="w-5 h-5 rounded-md bg-[#0B3A85]/10 text-[#0B3A85] flex items-center justify-center text-[11px] font-black shrink-0 mt-0.5">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-[#0F1F3D] leading-tight">
                                                {{ $step['title'] ?? '' }}
                                            </div>
                                            <div class="text-[11px] sm:text-xs text-slate-600 mt-0.5 leading-snug">
                                                {{ $step['desc'] ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 3. Peran Pengurus & Tanggung Jawab --}}
                    @if($role)
                        <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-200/80 flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800">
                                    Penanggung Jawab / Role Utama
                                </div>
                                <div class="text-xs font-bold text-emerald-950">
                                    {{ $role }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- 4. Tips Penting & Ketentuan --}}
                    @if(!empty($tips))
                        <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/80">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-amber-900 mb-1.5">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Catatan & Tips Penting:</span>
                            </div>
                            <ul class="space-y-1 text-[11px] sm:text-xs text-amber-950 pl-5 list-disc">
                                @foreach($tips as $tip)
                                    <li>{{ $tip }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Custom Slot if provided --}}
                    {{ $slot ?? '' }}
                </div>

                {{-- Footer Modal --}}
                <div class="bg-slate-50 border-t border-slate-200 px-5 sm:px-6 py-3 flex items-center justify-between">
                    <span class="text-[11px] text-slate-500 font-medium hidden sm:inline">
                        SIM-LiBas RT 15 • Panduan Pengurus
                    </span>
                    <button 
                        type="button" 
                        @click="guideOpen = false" 
                        class="ml-auto inline-flex items-center justify-center px-4 py-1.5 rounded-xl bg-[#0B3A85] text-white hover:bg-[#07275E] text-xs font-bold transition shadow-2xs hover:shadow-xs cursor-pointer"
                    >
                        Tutup Panduan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
