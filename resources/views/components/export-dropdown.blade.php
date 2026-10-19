@props([
    'resource',
    'searchVar' => '$wire.search',
    'extraParams' => [],
])

@php
    $pdfRoute = route("admin.export.pdf", ['resource' => $resource]);
    $excelRoute = route("admin.export.excel", ['resource' => $resource]);
@endphp

<div x-data="{ exportOpen: false }" @click.outside="exportOpen = false" class="relative inline-block text-left">
    <button 
        type="button" 
        @click="exportOpen = !exportOpen" 
        class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#0F1F3D] border border-[#CBD5E1] hover:bg-[#F8FAFC] hover:border-[#0B3A85] hover:text-[#0B3A85] transition-all duration-150 text-xs font-bold shadow-2xs hover:shadow-xs cursor-pointer"
        aria-haspopup="true"
        :aria-expanded="exportOpen"
    >
        <svg class="w-3.5 h-3.5 text-[#5B6B82]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span>Ekspor Data</span>
        <svg class="w-3 h-3 text-[#5B6B82] transition-transform duration-200" :class="{ 'rotate-180': exportOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Menu Dropdown Pilihan Format (PDF & Excel) --}}
    <div 
        x-show="exportOpen" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-200 ring-1 ring-black/5 p-1.5 z-50 focus:outline-none divide-y divide-slate-100"
        style="display: none;"
    >
        <div class="p-1 space-y-1">
            {{-- 1. Format PDF (Preview di tab baru) --}}
            <a 
                :href="'{{ $pdfRoute }}?' + new URLSearchParams({
                    search: {{ $searchVar }} || '',
                    @foreach($extraParams as $paramKey => $alpineExpr)
                        '{{ $paramKey }}': (typeof {{ $alpineExpr }} !== 'undefined' && typeof {{ $alpineExpr }} !== 'function' && {{ $alpineExpr }} !== null) ? {{ $alpineExpr }} : '',
                    @endforeach
                }).toString()" 
                target="_blank"
                @click="exportOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition-colors group cursor-pointer"
            >
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200 group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="text-xs font-bold text-[#0F1F3D] group-hover:text-rose-600">Format PDF</div>
                    <div class="text-[10px] text-slate-400">Preview & cetak tab baru</div>
                </div>
            </a>

            {{-- 2. Format Excel (.xlsx) (Unduh langsung / Save As) --}}
            <a 
                :href="'{{ $excelRoute }}?' + new URLSearchParams({
                    search: {{ $searchVar }} || '',
                    @foreach($extraParams as $paramKey => $alpineExpr)
                        '{{ $paramKey }}': (typeof {{ $alpineExpr }} !== 'undefined' && typeof {{ $alpineExpr }} !== 'function' && {{ $alpineExpr }} !== null) ? {{ $alpineExpr }} : '',
                    @endforeach
                }).toString()" 
                @click="exportOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition-colors group cursor-pointer"
            >
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-200 group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="text-xs font-bold text-[#0F1F3D] group-hover:text-emerald-600">Format Excel (.xlsx)</div>
                    <div class="text-[10px] text-slate-400">Download file spreadsheet</div>
                </div>
            </a>
        </div>
    </div>
</div>
