@props([
    'livewire' => null,
])

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="min-h-screen min-h-[100dvh] flex flex-col lg:flex-row bg-white overflow-x-hidden">

        {{-- ======================================================== --}}
        {{-- SISI KIRI (DESKTOP): FORM LOGIN PENGURUS (PUTIH BERSIH)  --}}
        {{-- ======================================================== --}}
        <div class="flex-1 flex flex-col justify-between bg-white px-6 sm:px-10 lg:px-12 xl:px-16 py-8 sm:py-10 lg:py-12 -mt-4 lg:mt-0 rounded-t-3xl lg:rounded-none shadow-2xl lg:shadow-none relative z-20 order-2 lg:order-1">
            
            {{-- Wrapper Form Terpusat --}}
            <div class="max-w-md w-full mx-auto my-auto py-2">
                {{ $slot }}
            </div>

            {{-- Footer Hak Cipta di Bawah Panel Form --}}
            <div class="pt-6 text-center text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} SIM-LiBas RT 15 Pematang Sulur.</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Semua hak dilindungi.</p>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- SISI KANAN (DESKTOP): KETERANGAN & HERO BRANDING (BIRU)  --}}
        {{-- ======================================================== --}}
        <div class="hidden lg:flex lg:w-1/2 xl:w-[50%] bg-gradient-to-br from-[#071F47] via-[#0B3A85] to-[#104F9E] text-white relative overflow-hidden flex-col justify-between p-10 xl:p-14 select-none order-1 lg:order-2">
            
            {{-- Subtle background geometric & wave glow --}}
            <div class="absolute inset-0 opacity-15 pointer-events-none">
                <svg class="w-full h-full" viewBox="0 0 800 800" fill="none" preserveAspectRatio="xMidYMid slice">
                    <circle cx="700" cy="100" r="350" fill="white" />
                    <circle cx="100" cy="700" r="250" fill="white" />
                    <path d="M-100,400 Q200,200 500,500 T1000,400" stroke="white" stroke-width="2" fill="none" opacity="0.3"/>
                    <path d="M-100,500 Q200,300 500,600 T1000,500" stroke="white" stroke-width="2" fill="none" opacity="0.2"/>
                </svg>
            </div>
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-[#19A7E8]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[#1C7ED6]/20 blur-3xl pointer-events-none"></div>

            {{-- 1. Brand Logo Header (Top Right dari sisi kanan) --}}
            <div class="relative z-10 flex justify-end">
                <a href="{{ route('public.home') }}" class="inline-flex items-center gap-3 group">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIM-LiBas" class="h-11 w-auto object-contain drop-shadow-md">
                    <div>
                        <div class="text-xl font-extrabold tracking-tight leading-none text-white">
                            SIM-<span class="text-[#19A7E8]">LiBas</span>
                        </div>
                        <div class="text-[11px] font-semibold text-blue-200 uppercase tracking-wider mt-1">
                            RT 15 Pematang Sulur
                        </div>
                    </div>
                </a>
            </div>

            {{-- 2. Hero Headline & Subtitle (Center - Horizontal Justify-End & Text-Right) --}}
            <div class="relative z-10 my-auto py-8 w-full flex justify-end">
                <div class="max-w-lg flex flex-col items-end text-right">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs border border-white/20 text-[11px] font-bold text-blue-100 uppercase tracking-wider mb-4 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-[#19A7E8] animate-pulse"></span>
                        <span>Portal Khusus Pengurus</span>
                    </div>
                    <h1 class="text-3xl xl:text-4xl 2xl:text-5xl font-extrabold tracking-tight leading-[1.2] text-white mb-4">
                        Pusat Kendali <br>
                        <span class="text-[#19A7E8]">Tata Kelola RT</span> <br>
                        Modern & Terpadu
                    </h1>
                    <p class="text-xs xl:text-sm text-blue-100/90 leading-relaxed font-normal">
                        Sistem manajemen terintegrasi untuk jajaran pengurus RT 15 Pematang Sulur dalam mengelola data kependudukan, verifikasi surat pengantar, pembukuan kas & iuran, serta pengawasan pelayanan warga secara transparan dan akuntabel.
                    </p>
                </div>
            </div>

            {{-- 3. Bottom Stats & Copyright --}}
            <div class="relative z-10 pt-6">
                <div class="grid grid-cols-3 gap-6 pb-6 border-b border-white/15">
                    <div>
                        <div class="text-2xl xl:text-3xl font-extrabold text-white tracking-tight">100%</div>
                        <div class="text-[11px] font-bold text-[#19A7E8] uppercase tracking-wider mt-0.5">Transparan & Sah</div>
                    </div>
                    <div>
                        <div class="text-2xl xl:text-3xl font-extrabold text-white tracking-tight">Realtime</div>
                        <div class="text-[11px] font-bold text-[#19A7E8] uppercase tracking-wider mt-0.5">Kas & Dokumen</div>
                    </div>
                    <div>
                        <div class="text-2xl xl:text-3xl font-extrabold text-white tracking-tight">Terpadu</div>
                        <div class="text-[11px] font-bold text-[#19A7E8] uppercase tracking-wider mt-0.5">Akses Pengurus</div>
                    </div>
                </div>

                <div class="pt-4 text-xs text-blue-200/80">
                    &copy; {{ date('Y') }} SIM-LiBas RT 15 Pematang Sulur, Kota Jambi
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- MOBILE HEADER: HEADER GRADIENT BIRU (TAMPIL DI HP)        --}}
        {{-- ======================================================== --}}
        <div class="lg:hidden bg-gradient-to-br from-[#071F47] via-[#0B3A85] to-[#104F9E] text-white p-6 pt-8 pb-10 relative overflow-hidden order-1">
            <div class="absolute -top-10 -right-10 w-44 h-44 rounded-full bg-[#19A7E8]/20 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex items-center gap-3 mb-5">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIM-LiBas" class="h-10 w-auto object-contain drop-shadow">
                <div>
                    <div class="text-lg font-extrabold tracking-tight leading-none text-white">
                        SIM-<span class="text-[#19A7E8]">LiBas</span>
                    </div>
                    <div class="text-[10px] font-semibold text-blue-200 uppercase tracking-wider mt-0.5">
                        RT 15 Pematang Sulur
                    </div>
                </div>
            </div>

            <div class="relative z-10">
                <h2 class="text-2xl font-extrabold text-white tracking-tight">Portal Pengurus RT 15</h2>
                <p class="text-xs text-blue-100 mt-1">Panel Kendali Administrasi & Pelayanan Warga</p>
            </div>
        </div>

    </div>
</x-filament-panels::layout.base>
