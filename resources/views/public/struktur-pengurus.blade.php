@extends('layouts.public')

@section('title', 'Bagan Struktur Organisasi RT 15 Pematang Sulur | SIM-LiBas')

@section('content')
    <!-- Hero Header Ringkas & Bersih -->
    <section class="sim-gradient text-white relative overflow-hidden py-7 sm:py-12">
        <!-- Subtle wave background -->
        <div class="absolute inset-0 opacity-10 pointer-events-none overflow-hidden">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Breadcrumbs -->
            <nav class="flex items-center justify-center gap-2 text-xs text-blue-200 mb-2">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Struktur Organisasi</span>
            </nav>

            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/15 backdrop-blur-md text-[11px] font-semibold text-gold-400 mb-2 border border-white/20">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400 animate-pulse"></span>
                Masa Bakti 2024 – 2027
            </span>

            <h1 class="text-xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white">
                Bagan Struktur Organisasi RT 15
            </h1>

            <p class="hidden sm:block text-xs sm:text-sm text-blue-100 max-w-xl mx-auto mt-1 leading-relaxed">
                Hierarki kepengurusan dan lembaga kemitraan Kampung Digital Pinang Masak Lestari, Kelurahan Pematang Sulur.
            </p>
        </div>
    </section>

    <!-- Konten Utama: Bagan Organisasi (Desktop & Mobile Pas Layar) -->
    <section class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 -mt-4 sm:-mt-6 relative z-20 pb-16">
        <div class="bg-white border border-border-soft rounded-2xl sm:rounded-3xl p-3 sm:p-8 shadow-sm">

            <!-- Bar Petunjuk & Interaksi (Hanya Tampil di Desktop/Tablet) -->
            <div class="hidden sm:flex items-center justify-between border-b border-border-soft pb-3 mb-6 text-xs text-text-muted">
                <span class="flex items-center gap-1.5 font-medium">
                    <svg class="w-4 h-4 text-primary-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Sentuh atau klik pada kotak untuk melihat rincian tugas & fungsi</span>
                </span>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-primary-700 border border-blue-100 shrink-0">
                    Bagan Resmi RT 15
                </span>
            </div>


            <!-- ================================================================= -->
            <!-- 1. BAGAN HIERARKI DESKTOP (TAMPIL DI LAYAR BESAR >= 1024px)        -->
            <!-- Tetap 100% sama dengan tampilan desktop yang disukai              -->
            <!-- ================================================================= -->
            <div class="hidden lg:block py-4">
                <div class="flex flex-col items-center">

                    <!-- LEVEL 1: KETUA RT & LEMBAGA MITRA (DIHUBUNGKAN GARIS PUTUS-PUTUS) -->
                    <div class="flex items-center justify-center relative w-full">
                        <!-- Spacer Kiri Simetris agar Ketua RT Tepat di Poros Tengah Diagram -->
                        <div class="w-72 invisible"></div>
                        <div class="w-10 invisible"></div>

                        <!-- Kotak Ketua RT (Poros Utama) -->
                        <div onclick="openOrgModal('ketua-rt')" class="w-80 bg-white border-2 border-primary-700 rounded-xl shadow-md hover:shadow-lg transition cursor-pointer overflow-hidden group shrink-0">
                            <div class="bg-primary-900 text-white text-center py-1.5 px-3">
                                <span class="text-[11px] font-bold tracking-wider uppercase text-gold-400">Ketua RT 15 Pematang Sulur</span>
                            </div>
                            <div class="p-3 flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center font-extrabold text-primary-900 text-sm shrink-0 group-hover:bg-primary-700 group-hover:text-white transition">
                                    SI
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-extrabold text-primary-900 leading-snug">Dr. Sigit Indrawijaya, SE, MSi</div>
                                    <div class="text-[11px] text-text-muted mt-0.5">Penanggung Jawab Wilayah</div>
                                </div>
                            </div>
                        </div>

                        <!-- Garis Hubung Putus-putus ke Kanan (Mitra Kolaboratif) -->
                        <div class="w-10 border-t-2 border-dashed border-primary-500 shrink-0"></div>

                        <!-- Kotak Lembaga Mitra (Kanan Sesuai Sketsa Asli) -->
                        <div class="w-72 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl p-3 text-left shadow-xs shrink-0">
                            <div class="text-[11px] font-bold text-primary-900 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-primary-700"></span>
                                <span>Lembaga & Pengurus Mitra</span>
                            </div>
                            <ul class="text-xs text-gray-700 space-y-1 pl-1">
                                <li class="cursor-pointer hover:text-primary-700 font-medium flex items-center justify-between" onclick="openOrgModal('mitra-pkk')">
                                    <span>• Pengurus PKK RT 15</span>
                                    <span class="text-[10px] text-primary-700 font-bold">Detail</span>
                                </li>
                                <li class="cursor-pointer hover:text-primary-700 font-medium flex items-center justify-between" onclick="openOrgModal('mitra-mnf')">
                                    <span>• Pengurus Masjid Nurul Fuqon (MNF)</span>
                                    <span class="text-[10px] text-primary-700 font-bold">Detail</span>
                                </li>
                                <li class="cursor-pointer hover:text-primary-700 font-medium flex items-center justify-between" onclick="openOrgModal('mitra-lam')">
                                    <span>• Pengurus Lembaga Adat Masyarakat (LAM)</span>
                                    <span class="text-[10px] text-primary-700 font-bold">Detail</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Garis Vertikal Turun ke Level 2 -->
                    <div class="w-0.5 h-8 bg-primary-700"></div>

                    <!-- LEVEL 2: SEKRETARIS & BENDAHARA -->
                    <div class="relative w-full max-w-2xl flex flex-col items-center">
                        <!-- Garis Horizontal Penghubung Sekretaris & Bendahara -->
                        <div class="w-3/5 h-0.5 bg-primary-700"></div>
                        <div class="w-full flex justify-between items-start pt-3">
                            <!-- Kotak Sekretaris -->
                            <div onclick="openOrgModal('sekretaris-rt')" class="w-64 bg-white border border-border-soft hover:border-primary-700 rounded-xl shadow-xs hover:shadow-md transition cursor-pointer overflow-hidden group">
                                <div class="bg-blue-800 text-white text-center py-1 px-3">
                                    <span class="text-[10px] font-bold tracking-wider uppercase">Sekretaris RT 15</span>
                                </div>
                                <div class="p-3 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center font-extrabold text-primary-700 text-xs shrink-0 group-hover:bg-primary-700 group-hover:text-white transition">
                                        AC
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-primary-900 leading-snug">Alexander Charles</div>
                                        <div class="text-[10px] text-text-muted">Administrasi & Surat</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kotak Bendahara -->
                            <div onclick="openOrgModal('bendahara-rt')" class="w-64 bg-white border border-border-soft hover:border-amber-600 rounded-xl shadow-xs hover:shadow-md transition cursor-pointer overflow-hidden group">
                                <div class="bg-amber-700 text-white text-center py-1 px-3">
                                    <span class="text-[10px] font-bold tracking-wider uppercase">Bendahara RT 15</span>
                                </div>
                                <div class="p-3 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-amber-50 border border-amber-200 flex items-center justify-center font-extrabold text-amber-700 text-xs shrink-0 group-hover:bg-amber-700 group-hover:text-white transition">
                                        RS
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-primary-900 leading-snug">Rully Sondri Mudahar</div>
                                        <div class="text-[10px] text-text-muted">Keuangan & Kas RT</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Garis Vertikal Turun ke Level 3 -->
                    <div class="w-0.5 h-8 bg-primary-700"></div>

                    <!-- LEVEL 3: 5 SEKSI DAN BIDANG OPERASIONAL -->
                    <div class="w-full">
                        <!-- Garis Horizontal Menghubungkan 5 Bidang -->
                        <div class="w-4/5 mx-auto h-0.5 bg-primary-700"></div>
                        <div class="grid grid-cols-5 gap-3 pt-3">
                            <!-- Bidang 1: Medsos & Dokumentasi -->
                            <div onclick="openOrgModal('bidang-medsos')" class="bg-white border border-border-soft hover:border-indigo-600 rounded-xl shadow-xs hover:shadow-md transition cursor-pointer overflow-hidden group flex flex-col justify-between">
                                <div class="bg-indigo-900 text-white text-center py-1 px-2">
                                    <span class="text-[9px] font-bold tracking-wider uppercase truncate block">Medsos & Dokumentasi</span>
                                </div>
                                <div class="p-2.5 text-center flex-1">
                                    <div class="w-9 h-9 rounded-lg bg-indigo-50 border border-indigo-200 mx-auto flex items-center justify-center font-bold text-xs text-indigo-700 mb-1.5 group-hover:bg-indigo-700 group-hover:text-white transition">
                                        YA
                                    </div>
                                    <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Yudhistira Adiwijaya</div>
                                </div>
                                <div class="bg-slate-50 py-1 text-center border-t border-border-soft/70 text-[9px] text-primary-700 font-semibold group-hover:bg-indigo-50">
                                    Detail Tugas
                                </div>
                            </div>

                            <!-- Bidang 2: Pemberdayaan Masyarakat -->
                            <div onclick="openOrgModal('bidang-pemberdayaan')" class="bg-white border border-border-soft hover:border-emerald-600 rounded-xl shadow-xs hover:shadow-md transition cursor-pointer overflow-hidden group flex flex-col justify-between">
                                <div class="bg-emerald-800 text-white text-center py-1 px-2">
                                    <span class="text-[9px] font-bold tracking-wider uppercase truncate block">Pemberdayaan Warga</span>
                                </div>
                                <div class="p-2.5 text-center flex-1">
                                    <div class="w-9 h-9 rounded-lg bg-emerald-50 border border-emerald-200 mx-auto flex items-center justify-center font-bold text-xs text-emerald-700 mb-1.5 group-hover:bg-emerald-700 group-hover:text-white transition">
                                        DK
                                    </div>
                                    <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Dwi Kuswanto</div>
                                </div>
                                <div class="bg-slate-50 py-1 text-center border-t border-border-soft/70 text-[9px] text-primary-700 font-semibold group-hover:bg-emerald-50">
                                    Detail Tugas
                                </div>
                            </div>

                            <!-- Bidang 3: Keamanan dan Ketertiban -->
                            <div onclick="openOrgModal('bidang-keamanan')" class="bg-white border border-border-soft hover:border-red-600 rounded-xl shadow-xs hover:shadow-md transition cursor-pointer overflow-hidden group flex flex-col justify-between">
                                <div class="bg-red-800 text-white text-center py-1 px-2">
                                    <span class="text-[9px] font-bold tracking-wider uppercase truncate block">Keamanan & Kamtib</span>
                                </div>
                                <div class="p-2.5 text-center flex-1">
                                    <div class="w-9 h-9 rounded-lg bg-red-50 border border-red-200 mx-auto flex items-center justify-center font-bold text-xs text-red-700 mb-1.5 group-hover:bg-red-700 group-hover:text-white transition">
                                        TM
                                    </div>
                                    <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Tony Mulyady</div>
                                </div>
                                <div class="bg-slate-50 py-1 text-center border-t border-border-soft/70 text-[9px] text-primary-700 font-semibold group-hover:bg-red-50">
                                    Detail Tugas
                                </div>
                            </div>

                            <!-- Bidang 4: Perlengkapan -->
                            <div onclick="openOrgModal('bidang-perlengkapan')" class="bg-white border border-border-soft hover:border-sky-600 rounded-xl shadow-xs hover:shadow-md transition cursor-pointer overflow-hidden group flex flex-col justify-between">
                                <div class="bg-sky-800 text-white text-center py-1 px-2">
                                    <span class="text-[9px] font-bold tracking-wider uppercase truncate block">Bidang Perlengkapan</span>
                                </div>
                                <div class="p-2.5 text-center flex-1">
                                    <div class="w-9 h-9 rounded-lg bg-sky-50 border border-sky-200 mx-auto flex items-center justify-center font-bold text-xs text-sky-700 mb-1.5 group-hover:bg-sky-700 group-hover:text-white transition">
                                        IS
                                    </div>
                                    <div class="text-[11px] font-extrabold text-primary-900 leading-tight">M. Imam Agus Sutopo</div>
                                </div>
                                <div class="bg-slate-50 py-1 text-center border-t border-border-soft/70 text-[9px] text-primary-700 font-semibold group-hover:bg-sky-50">
                                    Detail Tugas
                                </div>
                            </div>

                            <!-- Bidang 5: UMKM -->
                            <div onclick="openOrgModal('bidang-umkm')" class="bg-white border border-border-soft hover:border-amber-600 rounded-xl shadow-xs hover:shadow-md transition cursor-pointer overflow-hidden group flex flex-col justify-between">
                                <div class="bg-amber-800 text-white text-center py-1 px-2">
                                    <span class="text-[9px] font-bold tracking-wider uppercase truncate block">Bidang UMKM</span>
                                </div>
                                <div class="p-2.5 text-center flex-1">
                                    <div class="w-9 h-9 rounded-lg bg-amber-50 border border-amber-200 mx-auto flex items-center justify-center font-bold text-xs text-amber-700 mb-1.5 group-hover:bg-amber-700 group-hover:text-white transition">
                                        KR
                                    </div>
                                    <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Kresna</div>
                                </div>
                                <div class="bg-slate-50 py-1 text-center border-t border-border-soft/70 text-[9px] text-primary-700 font-semibold group-hover:bg-amber-50">
                                    Detail Tugas
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <!-- ================================================================= -->
            <!-- 2. BAGAN ORGANISASI MOBILE (PAS DI LAYAR - TANPA SCROLL SAMPING)    -->
            <!-- Desain visual bagan pohon & garis konektor yang rapi 100% pas layar-->
            <!-- ================================================================= -->
            <div class="lg:hidden flex flex-col items-center select-none w-full">

                <!-- LEVEL 1: KETUA RT -->
                <div onclick="openOrgModal('ketua-rt')" class="w-full max-w-[290px] bg-white border-2 border-primary-700 rounded-xl shadow-xs hover:shadow-sm transition cursor-pointer overflow-hidden">
                    <div class="bg-primary-900 text-white text-center py-1 px-2">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gold-400">Ketua RT 15 Pematang Sulur</span>
                    </div>
                    <div class="p-2.5 flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center font-extrabold text-primary-900 text-xs shrink-0">
                            SI
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-extrabold text-primary-900 leading-tight">Dr. Sigit Indrawijaya, SE, MSi</div>
                            <div class="text-[10px] text-text-muted mt-0.5">Penanggung Jawab Wilayah</div>
                        </div>
                    </div>
                </div>

                <!-- Garis Putus-putus ke Lembaga Mitra -->
                <div class="w-0.5 h-3 border-l-2 border-dashed border-primary-500"></div>

                <!-- LEMBAGA & PENGURUS MITRA (KOTAK PUTUS-PUTUS DI BAWAH KETUA RT) -->
                <div class="w-full max-w-[310px] bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl p-2.5 text-center shadow-2xs">
                    <div class="text-[10px] font-bold text-primary-900 uppercase tracking-wider mb-1.5 flex items-center justify-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-700"></span>
                        <span>Lembaga & Pengurus Mitra</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1 text-[10px]">
                        <div onclick="openOrgModal('mitra-pkk')" class="p-1.5 rounded bg-white border border-border-soft hover:bg-blue-50 hover:text-primary-700 cursor-pointer font-semibold text-gray-800 text-center truncate">
                            PKK RT 15
                        </div>
                        <div onclick="openOrgModal('mitra-mnf')" class="p-1.5 rounded bg-white border border-border-soft hover:bg-blue-50 hover:text-primary-700 cursor-pointer font-semibold text-gray-800 text-center truncate">
                            Masjid MNF
                        </div>
                        <div onclick="openOrgModal('mitra-lam')" class="p-1.5 rounded bg-white border border-border-soft hover:bg-blue-50 hover:text-primary-700 cursor-pointer font-semibold text-gray-800 text-center truncate">
                            Adat LAM
                        </div>
                    </div>
                </div>

                <!-- Garis Solid Turun ke Level 2 -->
                <div class="w-0.5 h-4 bg-primary-700"></div>

                <!-- LEVEL 2: SEKRETARIS & BENDAHARA (BERDAMPINGAN 2 KOLOM) -->
                <div class="relative w-full max-w-[340px] flex flex-col items-center">
                    <!-- Garis Horizontal Penghubung Sekretaris & Bendahara -->
                    <div class="w-3/5 h-0.5 bg-primary-700"></div>

                    <div class="w-full grid grid-cols-2 gap-2 pt-2">
                        <!-- Kotak Sekretaris -->
                        <div onclick="openOrgModal('sekretaris-rt')" class="bg-white border border-border-soft hover:border-primary-700 rounded-xl shadow-2xs overflow-hidden cursor-pointer flex flex-col justify-between">
                            <div class="bg-blue-800 text-white text-center py-0.5 px-1">
                                <span class="text-[9px] font-bold uppercase tracking-wider block truncate">Sekretaris RT 15</span>
                            </div>
                            <div class="p-2.5 text-center flex-1">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 border border-blue-200 mx-auto flex items-center justify-center font-extrabold text-primary-700 text-[10px] mb-1">
                                    AC
                                </div>
                                <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Alexander Charles</div>
                                <div class="text-[9px] text-text-muted mt-0.5">Administrasi & Surat</div>
                            </div>
                        </div>

                        <!-- Kotak Bendahara -->
                        <div onclick="openOrgModal('bendahara-rt')" class="bg-white border border-border-soft hover:border-amber-600 rounded-xl shadow-2xs overflow-hidden cursor-pointer flex flex-col justify-between">
                            <div class="bg-amber-700 text-white text-center py-0.5 px-1">
                                <span class="text-[9px] font-bold uppercase tracking-wider block truncate">Bendahara RT 15</span>
                            </div>
                            <div class="p-2.5 text-center flex-1">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 border border-amber-200 mx-auto flex items-center justify-center font-extrabold text-amber-700 text-[10px] mb-1">
                                    RS
                                </div>
                                <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Rully Sondri Mudahar</div>
                                <div class="text-[9px] text-text-muted mt-0.5">Keuangan & Kas RT</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Garis Solid Turun ke Level 3 -->
                <div class="w-0.5 h-4 bg-primary-700"></div>

                <!-- LEVEL 3: 5 SEKSI & BIDANG OPERASIONAL (BAGAN 2 KOLOM PAS LAYAR) -->
                <div class="relative w-full max-w-[340px] flex flex-col items-center">
                    <!-- Garis Horizontal Menghubungkan Bidang -->
                    <div class="w-4/5 h-0.5 bg-primary-700"></div>

                    <div class="w-full grid grid-cols-2 gap-2 pt-2">
                        <!-- Bidang 1: Medsos -->
                        <div onclick="openOrgModal('bidang-medsos')" class="bg-white border border-border-soft hover:border-indigo-600 rounded-xl shadow-2xs overflow-hidden cursor-pointer flex flex-col justify-between">
                            <div class="bg-indigo-900 text-white text-center py-0.5 px-1">
                                <span class="text-[9px] font-bold uppercase tracking-wider block truncate">Medsos & Dok</span>
                            </div>
                            <div class="p-2.5 text-center flex-1">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-200 mx-auto flex items-center justify-center font-bold text-[10px] text-indigo-700 mb-1">
                                    YA
                                </div>
                                <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Yudhistira Adiwijaya</div>
                            </div>
                        </div>

                        <!-- Bidang 2: Pemberdayaan -->
                        <div onclick="openOrgModal('bidang-pemberdayaan')" class="bg-white border border-border-soft hover:border-emerald-600 rounded-xl shadow-2xs overflow-hidden cursor-pointer flex flex-col justify-between">
                            <div class="bg-emerald-800 text-white text-center py-0.5 px-1">
                                <span class="text-[9px] font-bold uppercase tracking-wider block truncate">Pemberdayaan</span>
                            </div>
                            <div class="p-2.5 text-center flex-1">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-200 mx-auto flex items-center justify-center font-bold text-[10px] text-emerald-700 mb-1">
                                    DK
                                </div>
                                <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Dwi Kuswanto</div>
                            </div>
                        </div>

                        <!-- Bidang 3: Keamanan -->
                        <div onclick="openOrgModal('bidang-keamanan')" class="bg-white border border-border-soft hover:border-red-600 rounded-xl shadow-2xs overflow-hidden cursor-pointer flex flex-col justify-between">
                            <div class="bg-red-800 text-white text-center py-0.5 px-1">
                                <span class="text-[9px] font-bold uppercase tracking-wider block truncate">Keamanan & Kamtib</span>
                            </div>
                            <div class="p-2.5 text-center flex-1">
                                <div class="w-7 h-7 rounded-lg bg-red-50 border border-red-200 mx-auto flex items-center justify-center font-bold text-[10px] text-red-700 mb-1">
                                    TM
                                </div>
                                <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Tony Mulyady</div>
                            </div>
                        </div>

                        <!-- Bidang 4: Perlengkapan -->
                        <div onclick="openOrgModal('bidang-perlengkapan')" class="bg-white border border-border-soft hover:border-sky-600 rounded-xl shadow-2xs overflow-hidden cursor-pointer flex flex-col justify-between">
                            <div class="bg-sky-800 text-white text-center py-0.5 px-1">
                                <span class="text-[9px] font-bold uppercase tracking-wider block truncate">Perlengkapan</span>
                            </div>
                            <div class="p-2.5 text-center flex-1">
                                <div class="w-7 h-7 rounded-lg bg-sky-50 border border-sky-200 mx-auto flex items-center justify-center font-bold text-[10px] text-sky-700 mb-1">
                                    IS
                                </div>
                                <div class="text-[11px] font-extrabold text-primary-900 leading-tight">M. Imam Agus Sutopo</div>
                            </div>
                        </div>

                        <!-- Bidang 5: UMKM (Tepat di Tengah Bawah Kolom) -->
                        <div onclick="openOrgModal('bidang-umkm')" class="col-span-2 w-48 mx-auto bg-white border border-border-soft hover:border-amber-600 rounded-xl shadow-2xs overflow-hidden cursor-pointer flex flex-col justify-between">
                            <div class="bg-amber-800 text-white text-center py-0.5 px-1">
                                <span class="text-[9px] font-bold uppercase tracking-wider block truncate">Bidang UMKM</span>
                            </div>
                            <div class="p-2.5 text-center flex-1">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 border border-amber-200 mx-auto flex items-center justify-center font-bold text-[10px] text-amber-700 mb-1">
                                    KR
                                </div>
                                <div class="text-[11px] font-extrabold text-primary-900 leading-tight">Kresna</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ================================================================= -->
    <!-- MODAL POPUP RINCIAN TUGAS (TAMPIL SAAT KOTAK BAGAN DIKLIK)        -->
    <!-- Menjaga halaman tetap bersih & tidak penuh teks panjang           -->
    <!-- ================================================================= -->
    <div id="org-detail-modal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop Blur -->
        <div id="modal-bg" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div id="modal-panel" class="bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl border border-border-soft w-full sm:max-w-md max-h-[85vh] flex flex-col overflow-hidden">
                <!-- Header Modal -->
                <div class="p-4 sm:p-5 bg-gradient-to-r from-blue-50 to-white border-b border-border-soft flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div id="m-avatar" class="w-11 h-11 rounded-xl bg-primary-900 text-white flex items-center justify-center font-extrabold text-base shrink-0 shadow-xs">
                            SI
                        </div>
                        <div>
                            <span id="m-badge" class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-primary-100 text-primary-800">
                                Jabatan
                            </span>
                            <h3 id="m-name" class="text-sm sm:text-base font-extrabold text-primary-900 leading-snug">
                                Nama Pengurus
                            </h3>
                            <p id="m-role" class="text-[11px] text-text-muted">Jabatan</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeOrgModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Isi Modal (Tugas & Fungsi) -->
                <div class="p-4 sm:p-5 overflow-y-auto space-y-3">
                    <div class="text-[11px] font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>Tugas Pokok & Fungsi:</span>
                    </div>
                    <ul id="m-tasks" class="space-y-2 text-xs text-gray-700">
                        <!-- Disuntikkan via JavaScript -->
                    </ul>
                </div>

                <!-- Footer Modal -->
                <div class="p-3.5 bg-slate-50 border-t border-border-soft flex justify-end">
                    <button type="button" onclick="closeOrgModal()" class="w-full sm:w-auto bg-primary-700 hover:bg-primary-900 text-white font-bold text-xs px-5 py-2 rounded-xl transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data & Handler JavaScript -->
    <script>
        const orgStore = {
            'ketua-rt': {
                name: 'Dr. Sigit Indrawijaya, SE, MSi',
                role: 'Ketua RT 15 Pematang Sulur',
                initials: 'SI',
                badge: 'Ketua RT',
                tasks: [
                    'Memimpin tata kelola lingkungan, pelayanan warga, dan pelaksanaan rukun tetangga.',
                    'Penandatanganan dan legalisasi resmi berkas surat pengantar warga pada sistem SIM-LiBas.',
                    'Mengordinasikan kerja sama strategis dengan Kelurahan Pematang Sulur, Babinsa, dan kepolisian.',
                    'Membina ketenteraman, ketertiban, serta kerukunan hidup bermasyarakat antarwarga RT 15.'
                ]
            },
            'sekretaris-rt': {
                name: 'Alexander Charles',
                role: 'Sekretaris RT 15 Pematang Sulur',
                initials: 'AC',
                badge: 'Sekretaris RT',
                tasks: [
                    'Pengelolaan administrasi kependudukan dan database warga terpadu RT 15.',
                    'Verifikasi dan pengarsipan permohonan surat pengantar warga secara digital.',
                    'Penyusunan agenda musyawarah warga dan notulensi rapat berkala pengurus RT.',
                    'Mendukung tata kelola administrasi surat-menyurat resmi dengan instansi pemerintah.'
                ]
            },
            'bendahara-rt': {
                name: 'Rully Sondri Mudahar',
                role: 'Bendahara RT 15 Pematang Sulur',
                initials: 'RS',
                badge: 'Bendahara RT',
                tasks: [
                    'Pencatatan pembukuan kas lingkungan secara tertib, terbuka, dan akuntabel.',
                    'Pengelolaan iuran bulanan warga dan penerimaan donasi kegiatan sosial rukun tetangga.',
                    'Penyusunan laporan pertanggungjawaban keuangan berkala untuk transparansi warga.',
                    'Pencairan dan pengawasan anggaran operasional kegiatan lingkungan RT 15.'
                ]
            },
            'bidang-medsos': {
                name: 'Yudhistira Adiwijaya',
                role: 'Bidang Media Sosial dan Dokumentasi',
                initials: 'YA',
                badge: 'Medsos & Dokumentasi',
                tasks: [
                    'Mendokumentasikan aktivitas warga serta kegiatan sosial dan keagamaan dalam bentuk foto dan video.',
                    'Memublikasikan pengumuman dan dokumentasi kegiatan melalui media sosial RT 15.'
                ]
            },
            'bidang-pemberdayaan': {
                name: 'Dwi Kuswanto',
                role: 'Bidang Pemberdayaan Masyarakat',
                initials: 'DK',
                badge: 'Pemberdayaan Masyarakat',
                tasks: [
                    'Mendorong partisipasi warga dalam kegiatan sosial, lingkungan, dan keagamaan, seperti arisan dan gotong royong.',
                    'Memperbarui data warga yang membutuhkan bantuan secara berkala.'
                ]
            },
            'bidang-keamanan': {
                name: 'Tony Mulyady',
                role: 'Bidang Keamanan dan Ketertiban',
                initials: 'TM',
                badge: 'Keamanan & Ketertiban',
                tasks: [
                    'Melakukan upaya pencegahan dan mitigasi risiko keamanan serta ketertiban lingkungan.',
                    'Berkoordinasi dengan kelurahan, Babinsa, dan kepolisian terkait potensi gangguan keamanan.'
                ]
            },
            'bidang-perlengkapan': {
                name: 'M. Imam Agus Sutopo',
                role: 'Bidang Perlengkapan',
                initials: 'IS',
                badge: 'Perlengkapan',
                tasks: [
                    'Menginventarisasi, menjaga, dan memastikan aset RT 15 tetap aman serta terawat.',
                    'Mengupayakan pengadaan perlengkapan dan aset yang dibutuhkan.'
                ]
            },
            'bidang-umkm': {
                name: 'Kresna',
                role: 'Bidang UMKM',
                initials: 'KR',
                badge: 'Bidang UMKM',
                tasks: [
                    'Melakukan pendataan dan pembinaan UMKM warga.',
                    'Mendorong terciptanya UMKM baru dari berbagai sektor usaha.'
                ]
            },
            'mitra-pkk': {
                name: 'Pengurus Pemberdayaan Kesejahteraan Keluarga (PKK)',
                role: 'Lembaga Mitra Kolaboratif RT 15',
                initials: 'PK',
                badge: 'Lembaga Mitra',
                tasks: [
                    'Mendorong pemberdayaan perempuan dan ketahanan keluarga di lingkungan RT 15.',
                    'Pengelolaan program Posyandu balita dan lansia bersama kader kesehatan warga.',
                    'Penguatan program Dasawisma dan penghijauan pekarangan rumah tangga.'
                ]
            },
            'mitra-mnf': {
                name: 'Pengurus Masjid Nurul Fuqon (MNF)',
                role: 'Lembaga Mitra Keagamaan',
                initials: 'NF',
                badge: 'Lembaga Mitra',
                tasks: [
                    'Memakmurkan masjid dan mengoordinasikan kegiatan peribadatan jamaah warga.',
                    'Penyelenggaraan Peringatan Hari Besar Islam (PHBI) dan pengajian rutin lingkungan.',
                    'Pengelolaan zakat, infak, sedekah, dan kegiatan sosial keagamaan warga.'
                ]
            },
            'mitra-lam': {
                name: 'Pengurus Lembaga Adat Masyarakat (LAM)',
                role: 'Lembaga Mitra Adat & Kebudayaan',
                initials: 'LM',
                badge: 'Lembaga Mitra',
                tasks: [
                    'Pelestarian norma adat istiadat dan kearifan lokal Melayu Jambi di lingkungan warga.',
                    'Mediasi musyawarah mufakat dalam memelihara kerukunan antartetangga.',
                    'Pendampingan adat pada prosesi sosial kemasyarakatan warga RT 15.'
                ]
            }
        };

        function openOrgModal(id) {
            const data = orgStore[id];
            if (!data) return;

            document.getElementById('m-avatar').textContent = data.initials;
            document.getElementById('m-name').textContent = data.name;
            document.getElementById('m-role').textContent = data.role;
            document.getElementById('m-badge').textContent = data.badge;

            const tasksList = document.getElementById('m-tasks');
            tasksList.innerHTML = '';
            data.tasks.forEach(t => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-2';
                li.innerHTML = '<span class="text-primary-700 font-bold shrink-0">•</span><span>' + t + '</span>';
                tasksList.appendChild(li);
            });

            document.getElementById('org-detail-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeOrgModal() {
            document.getElementById('org-detail-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const bg = document.getElementById('modal-bg');
            if (bg) bg.addEventListener('click', closeOrgModal);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeOrgModal();
            });
        });
    </script>
@endsection
