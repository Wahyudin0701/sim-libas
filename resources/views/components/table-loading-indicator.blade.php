@props([
    'target' => 'statusFilter, targetFilter, kategoriFilter, jenisFilter, periodeFilter, waktuFilter, feeTypeFilter, genderFilter, anggotaFilter, search, resetFilters, setStatus, setPeriode, setWaktu, startDate, endDate, previousPage, nextPage, gotoPage',
    'text' => 'Memperbarui data tabel...',
])

{{-- Accent Bar Animasi di Bagian Atas Tabel --}}
<div 
    wire:loading 
    wire:target="{{ $target }}"
    class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-[#0B3A85] via-blue-400 to-[#0B3A85] animate-pulse z-30 pointer-events-none"
></div>

{{-- Overlay Loading State Non-Disruptive (Menjaga Struktur & Ukuran Tabel Tetap Utuh) --}}
<div 
    wire:loading.flex 
    wire:target="{{ $target }}"
    class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-20 items-center justify-center transition-all duration-200"
    style="display: none;"
>
    <div class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-white shadow-lg border border-[#DDE5F0] text-xs font-bold text-[#0B3A85]">
        <svg class="w-4 h-4 animate-spin text-[#0B3A85] shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
        <span class="tracking-wide">{{ $text }}</span>
    </div>
</div>
