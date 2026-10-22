@extends('layouts.public')

@section('title', 'Kontak & Sekretariat RT 15 Pematang Sulur | SIM-LiBas')

@section('content')
    <!-- Hero Header Kontak & Sekretariat (Selaras dengan Halaman SIM-LiBas Lainnya) -->
    <section class="sim-gradient text-white relative overflow-hidden py-10 sm:py-20">
        <!-- Background subtle wave shapes -->
        <div class="absolute inset-0 opacity-10 pointer-events-none overflow-hidden">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Breadcrumbs -->
            <nav class="flex items-center justify-center gap-2 text-xs text-blue-200 mb-3 sm:mb-6">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Kontak RT</span>
            </nav>

            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-gold-400 mb-3 sm:mb-4 border border-white/20">
                Pusat Komunikasi & Sekretariat Warga
            </span>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-2 sm:mb-4">
                Kontak & Sekretariat RT 15
            </h1>
            <p class="hidden sm:block text-base sm:text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Pintu komunikasi resmi warga RT 15 Pematang Sulur. Dapatkan informasi administrasi kependudukan, koordinasi lingkungan, atau kunjungi sekretariat rukun tetangga kami.
            </p>
        </div>
    </section>

    <!-- Konten Utama: 3 Kartu Aksi Teratas (Floating -mt-8) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-8 relative z-20 pb-16">
        <!-- 3 Kartu Kontak Utama (Compact Horizontal di Mobile, Grid Vertikal di Desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-6 mb-8 sm:mb-12">
            <!-- Kartu 1: WhatsApp Hotline -->
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $setting->telepon ?? '081274001515');
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
            @endphp
            <div class="sim-card p-3.5 sm:p-6 bg-white border border-border-soft rounded-2xl shadow-sm hover:shadow-md transition">
                <!-- Tampilan Mobile: Ringkas dengan Button Lebar Penuh di Bawah -->
                <div class="md:hidden flex flex-col justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.059-1.146-.084-.3-.095-.69-.234-1.196-.452-2.134-.925-3.522-3.088-3.627-3.232-.107-.143-.868-1.157-.868-2.207 0-1.05.549-1.567.747-1.776.195-.21.424-.263.566-.263.141 0 .283.003.407.01.131.007.307-.05.48.366.179.429.612 1.492.666 1.601.053.109.089.237.018.378-.07.143-.106.233-.213.357-.107.126-.225.28-.322.376-.107.106-.219.221-.094.437.125.215.556.917 1.194 1.485.822.732 1.516.96 1.731 1.067.215.108.341.09.468-.054.127-.144.544-.633.69-.85.144-.216.291-.18.49-.107.2.072 1.267.598 1.486.707.218.109.363.163.418.254.054.093.054.536-.09 1.482zM12 2C6.477 2 2 6.477 2 12c0 1.891.526 3.66 1.439 5.174L2 22l4.981-1.306A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.167c-1.637 0-3.161-.482-4.439-1.314l-.318-.207-2.957.776.79-2.883-.227-.361A8.125 8.125 0 013.833 12c0-4.503 3.664-8.167 8.167-8.167 4.503 0 8.167 3.664 8.167 8.167 0 4.503-3.664 8.167-8.167 8.167z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-[10px] font-bold text-text-muted uppercase tracking-wider">Telepon & WA</span>
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            </div>
                            <div class="text-sm font-extrabold text-primary-900 leading-tight">
                                {{ $setting->telepon ?? '0812-7400-1515' }}
                            </div>
                            <div class="text-[11px] text-text-muted truncate">
                                {{ $setting->nama_ketua_rt ?? 'Bpk. H. Ahmad Sudirman' }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-border-soft/70">
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo Pengurus RT 15 Pematang Sulur, saya ingin berkonsultasi mengenai layanan warga...') }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition shadow-xs">
                            <span>Chat WhatsApp Resmi</span>
                        </a>
                    </div>
                </div>

                <!-- Tampilan Desktop: Card Vertikal Lengkap -->
                <div class="hidden md:flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.059-1.146-.084-.3-.095-.69-.234-1.196-.452-2.134-.925-3.522-3.088-3.627-3.232-.107-.143-.868-1.157-.868-2.207 0-1.05.549-1.567.747-1.776.195-.21.424-.263.566-.263.141 0 .283.003.407.01.131.007.307-.05.48.366.179.429.612 1.492.666 1.601.053.109.089.237.018.378-.07.143-.106.233-.213.357-.107.126-.225.28-.322.376-.107.106-.219.221-.094.437.125.215.556.917 1.194 1.485.822.732 1.516.96 1.731 1.067.215.108.341.09.468-.054.127-.144.544-.633.69-.85.144-.216.291-.18.49-.107.2.072 1.267.598 1.486.707.218.109.363.163.418.254.054.093.054.536-.09 1.482zM12 2C6.477 2 2 6.477 2 12c0 1.891.526 3.66 1.439 5.174L2 22l4.981-1.306A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.167c-1.637 0-3.161-.482-4.439-1.314l-.318-.207-2.957.776.79-2.883-.227-.361A8.125 8.125 0 013.833 12c0-4.503 3.664-8.167 8.167-8.167 4.503 0 8.167 3.664 8.167 8.167 0 4.503-3.664 8.167-8.167 8.167z"/>
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Telepon Aktif
                            </span>
                        </div>
                        <div class="text-xs font-bold text-text-muted uppercase tracking-wider mb-1">WhatsApp & Telepon Resmi</div>
                        <div class="text-xl font-extrabold text-primary-900 mb-1">
                            {{ $setting->telepon ?? '0812-7400-1515' }}
                        </div>
                        <p class="text-xs text-text-muted leading-relaxed">
                            {{ $setting->nama_ketua_rt ?? 'Bpk. H. Ahmad Sudirman, S.E.' }} (Ketua RT 15 Pematang Sulur).
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-border-soft">
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo Pengurus RT 15 Pematang Sulur, saya ingin berkonsultasi mengenai layanan warga...') }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition shadow-sm hover:shadow">
                            <span>Chat WhatsApp Resmi</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kartu 2: Alamat Sekretariat -->
            <div class="sim-card p-3.5 sm:p-6 bg-white border border-border-soft rounded-2xl shadow-sm hover:shadow-md transition">
                <!-- Tampilan Mobile: Ringkas dengan Button Lebar Penuh di Bawah -->
                <div class="md:hidden flex flex-col justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary-700 border border-blue-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-[10px] font-bold text-text-muted uppercase tracking-wider">Sekretariat</span>
                                <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-blue-50 text-primary-700 border border-blue-100">
                                    Posko RT 15
                                </span>
                            </div>
                            <div class="text-sm font-extrabold text-primary-900 leading-tight">
                                {{ $setting->alamat ?? 'Kampung Digital Pinang Masak Lestari, RT 15' }}
                            </div>
                            <div class="text-[11px] text-text-muted truncate">
                                Kel. {{ $setting->kelurahan ?? 'Pematang Sulur' }}, Kota Jambi
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-border-soft/70">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(($setting->alamat ?? 'Jl. Pinang Masak RT 15') . ' Pematang Sulur Telanaipura Kota Jambi') }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center bg-primary-700 hover:bg-primary-800 active:bg-primary-900 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition shadow-xs">
                            <span>Petunjuk Arah Google Maps</span>
                        </a>
                    </div>
                </div>

                <!-- Tampilan Desktop: Card Vertikal Lengkap -->
                <div class="hidden md:flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-primary-700 border border-blue-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-primary-700 border border-blue-100">
                                Sekretariat RT
                            </span>
                        </div>
                        <div class="text-xs font-bold text-text-muted uppercase tracking-wider mb-1">Alamat Balai & Sekretariat</div>
                        <div class="text-base font-extrabold text-primary-900 mb-1 leading-snug">
                            {{ $setting->alamat ?? 'Jl. Pinang Masak, RT 15' }}
                        </div>
                        <p class="text-xs text-text-muted leading-relaxed">
                            Kel. {{ $setting->kelurahan ?? 'Pematang Sulur' }}, Kec. {{ $setting->kecamatan ?? 'Telanaipura' }}, {{ $setting->kota ?? 'Kota Jambi' }} {{ $setting->kode_pos ?? '36129' }}.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-border-soft">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(($setting->alamat ?? 'Jl. Pinang Masak RT 15') . ' Pematang Sulur Telanaipura Kota Jambi') }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 bg-primary-700 hover:bg-primary-900 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition shadow-sm hover:shadow">
                            <span>Petunjuk Arah Google Maps</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kartu 3: Email & Saluran Mandiri -->
            <div class="sim-card p-3.5 sm:p-6 bg-white border border-border-soft rounded-2xl shadow-sm hover:shadow-md transition">
                <!-- Tampilan Mobile: Ringkas dengan Button Lebar Penuh di Bawah -->
                <div class="md:hidden flex flex-col justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-gold-700 border border-amber-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-[10px] font-bold text-text-muted uppercase tracking-wider">Portal Mandiri</span>
                                <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-amber-50 text-gold-700 border border-amber-200">
                                    24 Jam
                                </span>
                            </div>
                            <div class="text-sm font-extrabold text-primary-900 leading-tight">
                                Layanan Mandiri Warga
                            </div>
                            <div class="text-[11px] text-text-muted truncate">
                                {{ $setting->email ?? 'rt15pematangsulur@gmail.com' }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-border-soft/70">
                        <a href="{{ route('citizen.login') }}" class="w-full inline-flex items-center justify-center bg-gold-500 hover:bg-gold-600 active:bg-gold-700 text-primary-900 font-bold text-xs py-2.5 px-4 rounded-xl transition shadow-xs">
                            <span>Buka Portal Mandiri Warga</span>
                        </a>
                    </div>
                </div>

                <!-- Tampilan Desktop: Card Vertikal Lengkap -->
                <div class="hidden md:flex flex-col justify-between h-full">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-gold-700 border border-amber-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-gold-700 border border-amber-200">
                                24 Jam Mandiri
                            </span>
                        </div>
                        <div class="text-xs font-bold text-text-muted uppercase tracking-wider mb-1">Email & Portal Digital</div>
                        <div class="text-base font-extrabold text-primary-900 mb-1 truncate">
                            {{ $setting->email ?? 'rt15pematangsulur@gmail.com' }}
                        </div>
                        <p class="text-xs text-text-muted leading-relaxed">
                            Ajukan surat pengantar resmi, cek iuran, dan sampaikan pengaduan secara mandiri melalui Portal Warga.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-border-soft">
                        <a href="{{ route('citizen.login') }}" class="w-full inline-flex items-center justify-center gap-2 bg-gold-500 hover:bg-gold-700 text-primary-900 font-bold text-xs py-2.5 px-4 rounded-xl transition shadow-sm hover:shadow">
                            <span>Buka Portal Mandiri Warga</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Dua Kolom: Form Pesan Cepat + Jadwal & Info Pengurus -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Kiri (7/12): Form Pesan Cepat -->
            <div class="lg:col-span-7 space-y-8">
                <!-- Card Form Pesan / Pertanyaan Cepat -->
                <div class="bg-white border border-border-soft rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-primary-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-primary-900">Kirim Pesan ke Pengurus RT</h3>
                        </div>
                    </div>

                    <form id="contact-fast-form" class="mt-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nama" class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="nama" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="telepon" class="block text-xs font-bold text-gray-700 mb-1">Nomor WhatsApp / HP <span class="text-red-500">*</span></label>
                                <input type="tel" id="telepon" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="status_warga" class="block text-xs font-bold text-gray-700 mb-1">Status Anda</label>
                                <select id="status_warga" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                                    <option value="Warga RT 15">Warga Tetap RT 15</option>
                                    <option value="Warga Domisili / Kontrak">Warga Domisili / Mengontrak</option>
                                    <option value="Tamu / Masyarakat Umum">Tamu / Warga Luar RT 15</option>
                                    <option value="Instansi / Lembaga">Instansi / Lembaga / Mitra</option>
                                </select>
                            </div>
                            <div>
                                <label for="kategori" class="block text-xs font-bold text-gray-700 mb-1">Topik Keperluan</label>
                                <select id="kategori" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                                    <option value="Administrasi & Surat Pengantar">Administrasi & Surat Pengantar</option>
                                    <option value="Iuran & Kas Lingkungan">Iuran & Kas Lingkungan</option>
                                    <option value="Bank Sampah Pinang Masak">Bank Sampah Pinang Masak</option>
                                    <option value="Keamanan & Pos Ronda">Keamanan, Pos Ronda & Ketertiban</option>
                                    <option value="Katalog UMKM Warga">Dukungan / Pendaftaran UMKM</option>
                                    <option value="Lainnya">Pertanyaan Umum Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="pesan" class="block text-xs font-bold text-gray-700 mb-1">Isi Pesan / Pertanyaan <span class="text-red-500">*</span></label>
                            <textarea id="pesan" rows="4" required placeholder="Tuliskan secara singkat maksud atau pertanyaan Anda kepada pengurus RT 15..." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent leading-relaxed"></textarea>
                        </div>

                        <div class="pt-2 space-y-2.5">
                            <div class="flex items-center gap-1.5 text-[11px] text-text-muted">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Perlindungan Data Pribadi (UU PDP)</span>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center bg-primary-700 hover:bg-primary-800 active:bg-primary-900 text-white font-bold text-xs sm:text-sm py-2.5 px-4 rounded-xl transition shadow-sm hover:shadow">
                                <span>Kirim via WhatsApp</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan (5/12): Jam Layanan, Etika Bertamu, Kontak Darurat Kota Jambi -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Card Jam Pelayanan & Panduan Bertamu -->
                <div class="bg-white border border-border-soft rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-border-soft mb-4">
                        <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h4 class="font-bold text-primary-900 text-base">Jam Layanan & Etika Bertamu</h4>
                    </div>

                    <!-- Jadwal Offline -->
                    <div class="mb-4">
                        <div class="text-xs font-bold text-primary-900 mb-1 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Layanan Offline di Kediaman Ketua RT:
                        </div>
                        <ul class="text-xs text-text-muted space-y-1.5 pl-4 border-l-2 border-blue-100 ml-1">
                            <li><strong class="text-gray-700">Senin – Jumat:</strong> 16.00 – 20.30 WIB (disarankan sore/malam)</li>
                            <li><strong class="text-gray-700">Sabtu:</strong> 08.30 – 17.00 WIB</li>
                            <li><strong class="text-gray-700">Minggu / Hari Libur:</strong> Urusan darurat / konfirmasi via WA</li>
                        </ul>
                    </div>

                    <!-- Jadwal Online -->
                    <div class="mb-5">
                        <div class="text-xs font-bold text-emerald-700 mb-1 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Layanan Online Mandiri (SIM-LiBas):
                        </div>
                        <p class="text-xs text-text-muted pl-4 border-l-2 border-emerald-200 ml-1 leading-relaxed">
                            Aktif <strong>24 Jam</strong> setiap hari untuk pengajuan surat pengantar, pengecekan kas/iuran, pengaduan lingkungan, dan katalog UMKM.
                        </p>
                    </div>

                    <!-- Tata Tertib Bertamu -->
                    <div class="p-3.5 rounded-xl bg-amber-50/80 border border-amber-200 text-xs text-amber-900">
                        <div class="font-bold mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gold-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Wajib Lapor 1x24 Jam
                        </div>
                        <p class="text-[11px] text-amber-800 leading-snug">
                            Bagi tamu yang menginap lebih dari 1x24 jam di lingkungan RT 15, pemilik rumah wajib melaporkan identitas tamu kepada Ketua RT demi keamanan bersama.
                        </p>
                    </div>
                </div>

                <!-- Card Kontak Darurat Terpadu Kota Jambi -->
                <div class="bg-white border border-border-soft rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-border-soft mb-4">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h4 class="font-bold text-primary-900 text-base">Kontak Darurat Terpadu</h4>
                            <p class="text-[11px] text-text-muted">Nomor penting wilayah Telanaipura & Kota Jambi</p>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <div class="text-xs font-bold text-primary-900">Bhabinkamtibmas Telanaipura</div>
                                <div class="text-[10px] text-text-muted">Polsek Telanaipura</div>
                            </div>
                            <a href="tel:081366220015" class="text-xs font-bold text-primary-700 bg-blue-50 px-2.5 py-1 rounded-lg hover:bg-primary-700 hover:text-white transition">
                                0813-6622-0015
                            </a>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <div class="text-xs font-bold text-primary-900">Babinsa Pematang Sulur</div>
                                <div class="text-[10px] text-text-muted">Koramil Telanaipura</div>
                            </div>
                            <a href="tel:085267110015" class="text-xs font-bold text-primary-700 bg-blue-50 px-2.5 py-1 rounded-lg hover:bg-primary-700 hover:text-white transition">
                                0852-6711-0015
                            </a>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <div class="text-xs font-bold text-primary-900">Damkar Kota Jambi (Pos Telanaipura)</div>
                                <div class="text-[10px] text-text-muted">Penanganan Kebakaran & Evakuasi</div>
                            </div>
                            <a href="tel:074141113" class="text-xs font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-lg hover:bg-red-600 hover:text-white transition">
                                (0741) 41113
                            </a>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <div class="text-xs font-bold text-primary-900">Puskesmas Telanaipura II</div>
                                <div class="text-[10px] text-text-muted">Pelayanan Kesehatan Warga</div>
                            </div>
                            <a href="tel:074160123" class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg hover:bg-emerald-700 hover:text-white transition">
                                (0741) 60123
                            </a>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <div class="text-xs font-bold text-primary-900">Layanan Darurat Kepolisian RI</div>
                                <div class="text-[10px] text-text-muted">Bebas Pulsa 24 Jam</div>
                            </div>
                            <a href="tel:110" class="text-xs font-bold text-slate-800 bg-slate-200 px-3 py-1 rounded-lg hover:bg-slate-800 hover:text-white transition">
                                110
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Peta Lokasi / Denah Wilayah -->
                <div class="bg-white border border-border-soft rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between pb-3 border-b border-border-soft mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <h4 class="font-bold text-primary-900 text-base">Peta Wilayah RT 15</h4>
                        </div>
                        <span class="text-[10px] font-semibold text-text-muted">Pematang Sulur</span>
                    </div>

                    <!-- Embed Peta OpenStreetMap Interaktif Lingkungan Telanaipura -->
                    <div class="rounded-xl overflow-hidden border border-border-soft bg-slate-100 h-48 relative">
                        <iframe 
                            class="w-full h-full border-0"
                            loading="lazy"
                            title="Peta Lokasi RT 15 Pematang Sulur"
                            src="https://www.openstreetmap.org/export/embed.html?bbox=103.5650%2C-1.6150%2C103.5950%2C-1.5850&amp;layer=mapnik&amp;marker=-1.6000%2C103.5800">
                        </iframe>
                    </div>

                    <div class="mt-3 flex items-center justify-between text-xs">
                        <span class="text-text-muted text-[11px]">Pinang Masak Lestari, RT 15</span>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(($setting->alamat ?? 'Jl. Pinang Masak RT 15') . ' Pematang Sulur Telanaipura Kota Jambi') }}" target="_blank" rel="noopener noreferrer" class="font-bold text-primary-700 hover:underline">
                            Buka Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Bawah: Ajakan Pemanfaatan Portal Digital Mandiri -->
        <div class="mt-12 bg-gradient-to-r from-primary-900 via-primary-800 to-blue-900 rounded-2xl sm:rounded-3xl p-6 sm:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl border border-white/10">
            <div class="max-w-2xl">
                <span class="px-3 py-1 rounded-full bg-gold-500/20 text-gold-400 text-xs font-semibold border border-gold-500/30 inline-block mb-3">
                    Layanan Mandiri Warga RT 15
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                    Urus Surat & Cek Iuran Lebih Praktis dari Rumah
                </h3>
                <p class="text-blue-100 text-xs sm:text-sm mt-2 leading-relaxed">
                    Tidak perlu menunggu jam bertamu. Masuk ke Portal Warga SIM-LiBas untuk mengajukan surat pengantar resmi ber-QR Code, membayar iuran bulanan, atau menyampaikan pengaduan lingkungan.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('citizen.login') }}" class="sim-btn-gold text-xs sm:text-sm px-6 py-3 rounded-xl transition shadow-sm hover:shadow flex items-center gap-2">
                    <span>Masuk ke Portal Warga</span>
                </a>
                <a href="{{ route('public.layanan.index') }}" class="bg-white/15 hover:bg-white/25 text-white font-semibold text-xs sm:text-sm px-5 py-3 rounded-xl backdrop-blur-md border border-white/20 transition">
                    Lihat 6 Layanan Warga
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('contact-fast-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const nama = document.getElementById('nama').value.trim();
                const telepon = document.getElementById('telepon').value.trim();
                const status = document.getElementById('status_warga').value;
                const kategori = document.getElementById('kategori').value;
                const pesan = document.getElementById('pesan').value.trim();

                const adminPhone = '{{ $cleanPhone ?? "6281274001515" }}';

                let text = `*PESAN DARI PORTAL PUBLIK SIM-LiBas RT 15*\n\n`;
                text += `*Nama:* ${nama}\n`;
                text += `*No. HP/WA:* ${telepon}\n`;
                text += `*Status:* ${status}\n`;
                text += `*Topik:* ${kategori}\n\n`;
                text += `*Isi Pesan:*\n${pesan}\n\n`;
                text += `_Dikirim melalui Halaman Kontak SIM-LiBas RT 15 Pematang Sulur_`;

                const waUrl = `https://wa.me/${adminPhone}?text=${encodeURIComponent(text)}`;
                window.open(waUrl, '_blank');
            });
        }
    });
</script>
@endpush
