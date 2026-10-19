<x-filament-widgets::widget>
    {{-- 4 Mini KPI Stat Cards untuk Halaman Kartu Keluarga (KK) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-3.5 mb-2">
        {{-- Card 1: Total Keluarga (KK) --}}
        <div class="group block">
            <div class="h-16 sm:h-20 rounded-xl bg-[#EAF5EE] border border-[#D2EBD9] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#166534] tracking-tight">
                    {{ $totalKk }}
                </span>
            </div>
            <div class="mt-2 text-center">
                <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                    Total Keluarga (KK)
                </span>
            </div>
        </div>

        {{-- Card 2: Hunian Tetap --}}
        <div class="group block">
            <div class="h-16 sm:h-20 rounded-xl bg-[#EEF5FF] border border-[#D6E5FF] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#2563EB] tracking-tight">
                    {{ $hunianTetap }}
                </span>
            </div>
            <div class="mt-2 text-center">
                <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                    Hunian Tetap
                </span>
            </div>
        </div>

        {{-- Card 3: Kontrak & Kos --}}
        <div class="group block">
            <div class="h-16 sm:h-20 rounded-xl bg-[#FFFBEB] border border-[#FEF3C7] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#D97706] tracking-tight">
                    {{ $kontrakKos }}
                </span>
            </div>
            <div class="mt-2 text-center">
                <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                    Kontrak & Kos
                </span>
            </div>
        </div>

        {{-- Card 4: Total Jiwa Anggota --}}
        <div class="group block">
            <div class="h-16 sm:h-20 rounded-xl bg-[#F0FDF4] border border-[#DCFCE7] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#15803D] tracking-tight">
                    {{ $totalJiwa }}
                </span>
            </div>
            <div class="mt-2 text-center">
                <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                    Total Jiwa Anggota
                </span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
