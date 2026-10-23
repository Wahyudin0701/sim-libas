@extends('layouts.auth')

@section('title', 'Masuk Portal Warga | SIM-LiBas RT 15')

@section('content')
<div class="min-h-screen min-h-[100dvh] flex flex-col lg:flex-row">

    {{-- ======================================================== --}}
    {{-- SISI KIRI (DESKTOP): HERO BRANDING DENGAN DOMINASI BIRU  --}}
    {{-- ======================================================== --}}
    <div class="hidden lg:flex lg:w-1/2 xl:w-[52%] bg-gradient-to-br from-[#071F47] via-[#0B3A85] to-[#104F9E] text-white relative overflow-hidden flex-col justify-between p-10 xl:p-14 select-none">
        
        {{-- Subtle background geometric & wave glow --}}
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 800 800" fill="none" preserveAspectRatio="xMidYMid slice">
                <circle cx="700" cy="100" r="350" fill="white" />
                <circle cx="100" cy="700" r="250" fill="white" />
                <path d="M-100,400 Q200,200 500,500 T1000,400" stroke="white" stroke-width="2" fill="none" opacity="0.3"/>
                <path d="M-100,500 Q200,300 500,600 T1000,500" stroke="white" stroke-width="2" fill="none" opacity="0.2"/>
            </svg>
        </div>
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-accent-cyan/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-primary-500/20 blur-3xl pointer-events-none"></div>

        {{-- 1. Brand Logo Header --}}
        <div class="relative z-10">
            <a href="{{ route('public.home') }}" class="inline-flex items-center gap-3 group">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIM-LiBas" class="h-11 w-auto object-contain drop-shadow-md">
                <div>
                    <div class="text-xl font-extrabold tracking-tight leading-none text-white">
                        SIM-<span class="text-accent-cyan">LiBas</span>
                    </div>
                    <div class="text-[11px] font-semibold text-blue-200 uppercase tracking-wider mt-1">
                        RT 15 Pematang Sulur
                    </div>
                </div>
            </a>
        </div>

        {{-- 2. Hero Headline & Subtitle --}}
        <div class="relative z-10 my-auto py-8 max-w-lg">
            <h1 class="text-4xl xl:text-5xl font-extrabold tracking-tight leading-[1.18] text-white mb-5">
                Membangun <br>
                <span class="text-accent-cyan">Kampung Digital</span> <br>
                Bersama Warga
            </h1>
            <p class="text-sm xl:text-base text-blue-100/90 leading-relaxed font-normal">
                Portal pelayanan mandiri warga RT 15 Pematang Sulur: pengajuan surat pengantar online, transparansi kas & iuran, bank sampah, dan etalase UMKM warga yang mudah, cepat, dan terpercaya.
            </p>
        </div>

        {{-- 3. Bottom Stats & Copyright --}}
        <div class="relative z-10 pt-6">
            <div class="grid grid-cols-3 gap-6 pb-6 border-b border-white/15">
                <div>
                    <div class="text-2xl xl:text-3xl font-extrabold text-white tracking-tight">6+</div>
                    <div class="text-[11px] font-bold text-accent-cyan uppercase tracking-wider mt-0.5">Layanan Warga</div>
                </div>
                <div>
                    <div class="text-2xl xl:text-3xl font-extrabold text-white tracking-tight">100%</div>
                    <div class="text-[11px] font-bold text-accent-cyan uppercase tracking-wider mt-0.5">Transparan & Sah</div>
                </div>
                <div>
                    <div class="text-2xl xl:text-3xl font-extrabold text-white tracking-tight">24h</div>
                    <div class="text-[11px] font-bold text-accent-cyan uppercase tracking-wider mt-0.5">Akses Mandiri</div>
                </div>
            </div>

            <div class="pt-4 text-xs text-blue-200/80">
                &copy; {{ date('Y') }} SIM-LiBas RT 15 Pematang Sulur, Kota Jambi
            </div>
        </div>
    </div>


    {{-- ======================================================== --}}
    {{-- MOBILE HEADER: HEADER GRADIENT BIRU                      --}}
    {{-- ======================================================== --}}
    <div class="lg:hidden bg-gradient-to-br from-[#071F47] via-[#0B3A85] to-[#104F9E] text-white p-6 pt-8 pb-10 relative overflow-hidden">
        {{-- Subtle background decoration --}}
        <div class="absolute -top-10 -right-10 w-44 h-44 rounded-full bg-accent-cyan/20 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex items-center gap-3 mb-6">
            <img src="{{ asset('images/logo-icon.png') }}" alt="Logo SIM-LiBas" class="h-10 w-auto object-contain drop-shadow">
            <div>
                <div class="text-lg font-extrabold tracking-tight leading-none text-white">
                    SIM-<span class="text-accent-cyan">LiBas</span>
                </div>
                <div class="text-[10px] font-semibold text-blue-200 uppercase tracking-wider mt-0.5">
                    RT 15 Pematang Sulur
                </div>
            </div>
        </div>

        <div class="relative z-10">
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Selamat Datang</h2>
            <p class="text-xs text-blue-100 mt-1">Portal Layanan Mandiri Warga RT 15</p>
        </div>
    </div>


    {{-- ======================================================== --}}
    {{-- SISI KANAN / FORM PANEL (PUTIH, BERSIH, RAMAH LANSIA)    --}}
    {{-- ======================================================== --}}
    <div class="flex-1 flex flex-col justify-between bg-white px-6 sm:px-10 lg:px-14 xl:px-20 py-8 sm:py-10 lg:py-12 -mt-4 lg:mt-0 rounded-t-3xl lg:rounded-none shadow-2xl lg:shadow-none relative z-20">
        
        {{-- Wrapper Form Terpusat --}}
        <div class="max-w-md w-full mx-auto my-auto py-2">
            
            {{-- Header Form --}}
            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-primary-900 tracking-tight">
                    Masuk <span class="text-primary-500">Portal Warga</span>
                </h2>
                <p class="text-sm text-text-muted mt-2">
                    Silakan masukkan Nama Lengkap dan NIK Anda sesuai KTP.
                </p>
            </div>

            {{-- Alert Notifikasi Gagal --}}
            @if($errors->any())
                <div class="mb-5 flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-sm">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <div class="font-bold text-red-800 text-xs uppercase tracking-wider mb-0.5">Gagal Masuk</div>
                        @foreach($errors->all() as $error)
                            <p class="text-red-700 text-xs leading-relaxed">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form Login Utama --}}
            <form action="{{ route('citizen.login.post') }}" method="POST" class="space-y-5" novalidate>
                @csrf

                {{-- 1. Input Nama Lengkap Sesuai KTP --}}
                <div>
                    <label for="nama" class="block text-xs sm:text-sm font-bold text-primary-900 uppercase tracking-wider mb-2">
                        Nama Lengkap Sesuai KTP <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', old('login')) }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Contoh: EDWIN EDWARD RUMAJAR"
                        class="w-full px-4 py-3.5 bg-[#F0F5FA] hover:bg-[#EBF2F9] focus:bg-white border {{ $errors->has('nama') || $errors->has('login') ? 'border-red-400 bg-red-50/50' : 'border-border-soft' }} rounded-xl text-sm sm:text-base text-text-main placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150"
                    >
                </div>

                {{-- 2. Input NIK (Sebagai Kata Sandi Warga) --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="nik" class="block text-xs sm:text-sm font-bold text-primary-900 uppercase tracking-wider">
                            Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <input
                            type="password"
                            id="nik"
                            name="nik"
                            required
                            inputmode="numeric"
                            maxlength="16"
                            autocomplete="current-password"
                            placeholder="16 digit angka NIK KTP"
                            class="w-full pl-4 pr-12 py-3.5 bg-[#F0F5FA] hover:bg-[#EBF2F9] focus:bg-white border {{ $errors->has('nik') || $errors->has('password') ? 'border-red-400 bg-red-50/50' : 'border-border-soft' }} rounded-xl text-sm sm:text-base font-mono text-text-main placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150 tracking-wider"
                        >
                        {{-- Toggle Show/Hide Password NIK --}}
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-primary-700 transition focus:outline-none cursor-pointer"
                            tabindex="-1"
                            aria-label="Tampilkan atau sembunyikan angka NIK"
                            title="Klik untuk melihat atau menyembunyikan angka NIK"
                        >
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-off-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Checkbox Ingat Saya --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer group select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-border-soft text-primary-700 focus:ring-primary-500 cursor-pointer"
                        >
                        <span class="text-xs sm:text-sm text-text-muted group-hover:text-primary-900 transition">
                            Ingat saya di perangkat ini
                        </span>
                    </label>
                </div>

                {{-- Tombol Masuk Sekarang --}}
                <button
                    type="submit"
                    class="w-full bg-primary-700 hover:bg-primary-900 text-white font-bold text-base py-3.5 px-6 rounded-xl transition duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 group cursor-pointer mt-2"
                >
                    <span>Masuk ke Portal Warga</span>
                    <svg class="w-5 h-5 shrink-0 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            {{-- Hubungi Pengurus / Bantuan --}}
            <div class="mt-6 text-center">
                <p class="text-xs sm:text-sm text-text-muted">
                    Lupa NIK atau butuh bantuan?
                    <a
                        href="https://wa.me/6281274001515?text={{ urlencode('Halo Pengurus RT 15, saya warga ingin menanyakan akses Portal Warga SIM-LiBas saya.') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="font-bold text-primary-700 hover:text-primary-900 hover:underline transition inline-block ml-1"
                    >
                        Hubungi Pengurus RT
                    </a>
                </p>
            </div>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-border-soft"></div>
                </div>
            </div>

            {{-- Navigasi Pilihan (Ke Beranda & Portal Pengurus) --}}
            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold text-text-muted">
                <a
                    href="{{ route('public.home') }}"
                    class="hover:text-primary-700 transition flex items-center gap-1.5 py-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Ke Beranda</span>
                </a>

                <a
                    href="{{ url('/admin/login') }}"
                    class="hover:text-primary-700 transition flex items-center gap-1.5 py-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Portal Pengurus</span>
                </a>
            </div>
        </div>

        {{-- Footer Hak Cipta di Bawah Panel Form --}}
        <div class="pt-6 text-center text-xs text-text-muted">
            <p>&copy; {{ date('Y') }} SIM-LiBas RT 15 Pematang Sulur</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('nik');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');

        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
