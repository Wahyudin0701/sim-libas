@extends('layouts.citizen')

@section('title', 'Profil Akun Warga | SIM-LiBas RT 15')

@section('content')
<!-- Tombol Kembali ke Beranda (Desktop only, di mobile sudah ada di topbar) -->
<div class="mb-6 hidden md:flex items-center">
    <a href="{{ route('warga.dashboard') }}" 
       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-border-soft hover:bg-slate-50 hover:border-slate-300 text-xs font-semibold text-primary-900 shadow-sm transition active:scale-95 group focus:outline-none focus:ring-2 focus:ring-primary-500/20">
        <svg class="w-4 h-4 text-text-muted group-hover:text-primary-900 group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Beranda</span>
    </a>
</div>

<!-- 1. KARTU HEADER RINGKASAN PROFIL -->
<div class="sim-card p-6 mb-6 bg-white">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 pb-6 border-b border-border-soft">
        <div class="flex items-center gap-4 min-w-0">
            @php
                $words = preg_split('/\s+/', trim($user->name));
                $initials = '';
                if (count($words) >= 2) {
                    $initials = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
                } else {
                    $initials = strtoupper(mb_substr($user->name, 0, 2));
                }
            @endphp
            <div class="w-16 h-16 rounded-2xl bg-[#1B7A3A] text-white font-extrabold text-xl flex items-center justify-center shrink-0 shadow-md ring-4 ring-emerald-500/15">
                {{ $initials }}
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-primary-900 tracking-tight truncate">
                        {{ $user->name }}
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        Warga Aktif
                    </span>
                </div>
                <p class="text-xs text-text-muted mt-1">
                    {{ $resident?->is_kepala_keluarga ? 'Kepala Keluarga' : ucwords(str_replace('_', ' ', $resident?->hubungan_keluarga ?? 'Anggota Keluarga')) }}
                    • {{ $household?->alamat ?? 'RT 15 Pematang Sulur' }}
                </p>
            </div>
        </div>

        @if($household)
            <a href="{{ route('warga.kartu-keluarga') }}" 
               class="inline-flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-primary-900 border border-blue-200 text-xs font-bold px-3.5 py-2 rounded-xl transition shadow-2xs shrink-0">
                <span>Lihat Kartu Keluarga</span>
            </a>
        @endif
    </div>

    <!-- Quick Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-5 text-center sm:text-left">
        <div>
            <span class="text-[10px] text-text-muted font-bold uppercase tracking-wider block">ID Warga</span>
            <span class="font-mono text-sm font-bold text-primary-900 mt-0.5 block">{{ $resident?->id_warga ?? 'W-' . str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div>
            <span class="text-[10px] text-text-muted font-bold uppercase tracking-wider block">No. Kartu Keluarga</span>
            <span class="font-mono text-sm font-bold text-primary-900 mt-0.5 block">{{ $household?->masked_no_kk ?? '-' }}</span>
        </div>
        <div>
            <span class="text-[10px] text-text-muted font-bold uppercase tracking-wider block">NIK Terdaftar</span>
            <span class="font-mono text-sm font-bold text-primary-900 mt-0.5 block">{{ $resident?->masked_nik ?? '-' }}</span>
        </div>
        <div>
            <span class="text-[10px] text-text-muted font-bold uppercase tracking-wider block">Status Hubungan</span>
            <span class="text-sm font-bold text-primary-900 mt-0.5 block">{{ $resident?->is_kepala_keluarga ? 'Kepala Keluarga' : ucwords(str_replace('_', ' ', $resident?->hubungan_keluarga ?? 'Warga')) }}</span>
        </div>
    </div>
</div>

<!-- 2. GRID 2 KOLOM: AKUN LOGIN & DATA KEPENDUDUKAN -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
    <!-- Kolom Kiri: Informasi Akun Login Portal -->
    <div class="lg:col-span-5 space-y-6">
        <div class="sim-card p-6 bg-white">
            <div class="flex items-center gap-2.5 pb-4 border-b border-border-soft mb-4">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-primary-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-primary-900">Kredensial Akun Portal</h2>
                    <p class="text-[11px] text-text-muted">Akses masuk dan keamanan akun</p>
                </div>
            </div>

            <div class="space-y-4 text-xs">
                <div>
                    <span class="text-text-muted font-semibold block">Username Masuk Portal</span>
                    <span class="text-primary-900 font-bold block mt-0.5">{{ $resident?->nama ?? $user->name }} <span class="text-slate-400 font-normal">(Nama Sesuai KTP)</span></span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Kata Sandi Masuk Portal</span>
                    <span class="font-mono text-primary-900 font-bold block mt-0.5">{{ $resident?->masked_nik ?? '16 Digit NIK KTP Anda' }}</span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Nomor Telepon / WhatsApp</span>
                    <span class="font-mono text-primary-900 font-bold block mt-0.5">{{ $user->phone ?? $resident?->telepon ?? 'Belum tercatat' }}</span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Hak Akses Sistem</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-primary-700 border border-blue-100 mt-1">
                        Portal Layanan Mandiri Warga RT 15
                    </span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Tanggal Pendaftaran Akun</span>
                    <span class="text-primary-900 font-semibold block mt-0.5">{{ $user->created_at?->format('d F Y') ?? '-' }}</span>
                </div>
            </div>

            <!-- Tombol Aksi Kontak di Bawah Card -->
            <div class="pt-4 mt-4 border-t border-border-soft space-y-2">
                <button type="button" onclick="openPhoneModal()" class="w-full px-3 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-200 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs active:scale-98">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span>Perbarui Nomor Telepon / WA</span>
                </button>
                <a href="https://wa.me/6281274001515?text={{ urlencode('Halo Pengurus RT 15, saya ingin mengonfirmasi perubahan data akun/NIK saya.') }}" target="_blank" rel="noopener noreferrer" class="w-full px-3 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-primary-900 border border-blue-200 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs active:scale-98">
                    <svg class="w-4 h-4 text-[#0B3A85] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Bantuan Kredensial / Hubungi Pengurus</span>
                </a>
            </div>
        </div>

        <!-- Bantuan & Keamanan Akun -->
        <div class="sim-card p-5 bg-gradient-to-br from-slate-50 to-blue-50/40 border border-blue-100">
            <h3 class="text-xs font-bold text-primary-900 mb-1 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Perlindungan Data Pribadi
            </h3>
            <p class="text-[11px] text-text-muted leading-relaxed">
                Nomor Induk Kependudukan (NIK) dan Nomor Kartu Keluarga (KK) dilindungi enkripsi standar UU No. 27 Tahun 2022 (UU PDP). Kredensial login Anda tersinkronisasi otomatis dengan data resmi kependudukan RT 15.
            </p>
        </div>
    </div>

    <!-- Kolom Kanan: Data Kependudukan Terverifikasi -->
    <div class="lg:col-span-7">
        <div class="sim-card p-6 bg-white">
            <div class="flex items-center gap-2.5 pb-4 border-b border-border-soft mb-4">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-[#1B7A3A] flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-primary-900">Data Kependudukan Resmi</h2>
                    <p class="text-[11px] text-text-muted">Data terdaftar di buku kependudukan RT 15 Pematang Sulur</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-xs">
                <div>
                    <span class="text-text-muted font-semibold block">Nama Lengkap (KTP)</span>
                    <span class="text-primary-900 font-bold block mt-0.5">{{ $resident?->nama ?? $user->name }}</span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Nomor Induk Kependudukan (NIK)</span>
                    <span class="font-mono text-primary-900 font-bold block mt-0.5">{{ $resident?->masked_nik ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Jenis Kelamin</span>
                    <span class="text-primary-900 font-semibold block mt-0.5">
                        {{ $resident?->jenis_kelamin === 'L' ? 'Laki-laki' : ($resident?->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
                    </span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Tempat & Tanggal Lahir</span>
                    <span class="text-primary-900 font-semibold block mt-0.5">
                        {{ $resident?->tempat_lahir ?? '-' }}, {{ $resident?->tanggal_lahir?->format('d F Y') ?? '-' }}
                    </span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Agama</span>
                    <span class="text-primary-900 font-semibold block mt-0.5">{{ $resident?->agama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Pekerjaan</span>
                    <span class="text-primary-900 font-semibold block mt-0.5">{{ $resident?->pekerjaan ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Pendidikan Terakhir</span>
                    <span class="text-primary-900 font-semibold block mt-0.5">{{ $resident?->pendidikan_terakhir ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Status Perkawinan</span>
                    <span class="text-primary-900 font-semibold block mt-0.5">{{ $resident?->status_perkawinan ?? '-' }}</span>
                </div>
                <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                    <span class="text-text-muted font-semibold block">Alamat Domisili di RT 15</span>
                    <span class="text-primary-900 font-semibold block mt-0.5 leading-relaxed">
                        {{ $resident?->alamat_domisili ?? $household?->alamat ?? 'RT 15 Pematang Sulur, Telanaipura, Kota Jambi' }}
                    </span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Status Tempat Tinggal</span>
                    <span class="text-primary-900 font-semibold block mt-0.5">
                        {{ ucwords(str_replace('_', ' ', $resident?->status_tempat_tinggal ?? $household?->status_kepemilikan ?? 'Milik Sendiri')) }}
                    </span>
                </div>
                <div>
                    <span class="text-text-muted font-semibold block">Kategori Warga</span>
                    <span class="text-primary-900 font-semibold block mt-0.5">
                        {{ ucwords(str_replace('_', ' ', $resident?->kategori_warga ?? 'Warga Tetap')) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. POPUP MODAL GANTI ALAMAT EMAIL -->
<div id="modal-email" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop-blur bg-slate-900/50 backdrop-blur-md transition-all duration-200" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl shadow-2xl border border-border-soft max-w-md w-full max-h-[90vh] overflow-y-auto no-scrollbar transform transition-all" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="p-6 pb-4 border-b border-border-soft flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-primary-900">Ganti Alamat Email</h3>
                <p class="text-xs text-text-muted mt-0.5">Perbarui email untuk akses login dan notifikasi warga</p>
            </div>
            <button type="button" onclick="closeEmailModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Form Body -->
        <form action="{{ route('warga.profile.email') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                    Alamat Email Saat Ini
                </label>
                <input type="text" value="{{ $user->email }}" disabled class="w-full bg-slate-100 border border-slate-200 text-slate-600 text-sm px-3.5 py-2.5 rounded-xl cursor-not-allowed font-mono">
            </div>

            <div>
                <label for="new_email" class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Alamat Email Baru <span class="text-red-500">*</span>
                </label>
                <input type="email" id="new_email" name="email" value="{{ old('email') }}" required placeholder="contoh: nama.warga@gmail.com" class="w-full bg-bg-soft border @error('email') border-red-400 ring-2 ring-red-100 @else border-border-soft @enderror text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('email')
                    <p class="text-red-600 text-xs mt-1 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="current_password_email" class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Konfirmasi Kata Sandi Akun <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="current_password_email" name="current_password" required placeholder="Masukkan kata sandi saat ini" class="w-full bg-bg-soft border @error('current_password') border-red-400 ring-2 ring-red-100 @else border-border-soft @enderror text-sm px-3.5 py-2.5 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <button type="button" onclick="togglePasswordVisibility('current_password_email', 'eye-icon-email')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg id="eye-icon-email" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-red-600 text-xs mt-1 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
                <p class="text-[11px] text-text-muted mt-1">Dibutuhkan untuk memverifikasi bahwa perubahan dilakukan oleh pemilik akun.</p>
            </div>

            <!-- Modal Footer -->
            <div class="pt-3 border-t border-border-soft flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeEmailModal()" class="px-4 py-2.5 rounded-xl border border-border-soft text-text-muted hover:bg-slate-50 text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="sim-btn-primary px-5 py-2.5 text-xs font-bold shadow cursor-pointer">
                    Simpan Email Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 4. POPUP MODAL GANTI KATA SANDI -->
<div id="modal-password" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop-blur bg-slate-900/50 backdrop-blur-md transition-all duration-200" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl shadow-2xl border border-border-soft max-w-md w-full max-h-[90vh] overflow-y-auto no-scrollbar transform transition-all" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="p-6 pb-4 border-b border-border-soft flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-primary-900">Ganti Kata Sandi</h3>
                <p class="text-xs text-text-muted mt-0.5">Perbarui kata sandi untuk mengamankan akses akun Anda</p>
            </div>
            <button type="button" onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Form Body -->
        <form action="{{ route('warga.profile.password') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password_pass" class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Kata Sandi Saat Ini <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="current_password_pass" name="current_password" required placeholder="Masukkan kata sandi lama Anda" class="w-full bg-bg-soft border @error('current_password_password') border-red-400 ring-2 ring-red-100 @else border-border-soft @enderror text-sm px-3.5 py-2.5 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <button type="button" onclick="togglePasswordVisibility('current_password_pass', 'eye-icon-pass-curr')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg id="eye-icon-pass-curr" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('current_password_password')
                    <p class="text-red-600 text-xs mt-1 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="new_password" class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Kata Sandi Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="new_password" name="password" required minlength="8" placeholder="Minimal 8 karakter" class="w-full bg-bg-soft border @error('password') border-red-400 ring-2 ring-red-100 @else border-border-soft @enderror text-sm px-3.5 py-2.5 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <button type="button" onclick="togglePasswordVisibility('new_password', 'eye-icon-pass-new')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg id="eye-icon-pass-new" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-600 text-xs mt-1 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" placeholder="Ulangi kata sandi baru" class="w-full bg-bg-soft border border-border-soft text-sm px-3.5 py-2.5 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-pass-conf')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg id="eye-icon-pass-conf" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-3 border-t border-border-soft flex items-center justify-end gap-2.5">
                <button type="button" onclick="closePasswordModal()" class="px-4 py-2.5 rounded-xl border border-border-soft text-text-muted hover:bg-slate-50 text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="sim-btn-primary px-5 py-2.5 text-xs font-bold shadow cursor-pointer">
                    Simpan Kata Sandi Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 5. POPUP MODAL GANTI NOMOR TELEPON -->
<div id="modal-phone" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop-blur bg-slate-900/50 backdrop-blur-md transition-all duration-200" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl shadow-2xl border border-border-soft max-w-md w-full max-h-[90vh] overflow-y-auto no-scrollbar transform transition-all" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="p-6 pb-4 border-b border-border-soft flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-primary-900">Ganti Nomor HP / WhatsApp</h3>
                <p class="text-xs text-text-muted mt-0.5">Perbarui nomor kontak aktif untuk koordinasi dan layanan warga</p>
            </div>
            <button type="button" onclick="closePhoneModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Form Body -->
        <form action="{{ route('warga.profile.phone') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                    Nomor Telepon Saat Ini
                </label>
                <input type="text" value="{{ $user->phone ?? $resident?->telepon ?? 'Belum ada nomor' }}" disabled class="w-full bg-slate-100 border border-slate-200 text-slate-600 text-sm px-3.5 py-2.5 rounded-xl cursor-not-allowed font-mono">
            </div>

            <div>
                <label for="new_phone" class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Nomor Telepon / WhatsApp Baru <span class="text-red-500">*</span>
                </label>
                <input type="tel" id="new_phone" name="phone" value="{{ old('phone', $user->phone ?? $resident?->telepon) }}" required placeholder="contoh: 081234567890" class="w-full bg-bg-soft border @error('phone') border-red-400 ring-2 ring-red-100 @else border-border-soft @enderror text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 font-mono">
                @error('phone')
                    <p class="text-red-600 text-xs mt-1 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
                <p class="text-[11px] text-text-muted mt-1">Nomor ini digunakan oleh Pengurus RT untuk koordinasi surat, iuran, dan informasi darurat.</p>
            </div>

            <!-- Modal Footer -->
            <div class="pt-3 border-t border-border-soft flex items-center justify-end gap-2.5">
                <button type="button" onclick="closePhoneModal()" class="px-4 py-2.5 rounded-xl border border-border-soft text-text-muted hover:bg-slate-50 text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="sim-btn-primary px-5 py-2.5 text-xs font-bold shadow cursor-pointer">
                    Simpan Nomor Telepon
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEmailModal() {
        const modal = document.getElementById('modal-email');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                const input = document.getElementById('new_email');
                if (input) input.focus();
            }, 50);
        }
    }

    function closeEmailModal() {
        const modal = document.getElementById('modal-email');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openPasswordModal() {
        const modal = document.getElementById('modal-password');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                const input = document.getElementById('current_password_pass');
                if (input) input.focus();
            }, 50);
        }
    }

    function closePasswordModal() {
        const modal = document.getElementById('modal-password');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openPhoneModal() {
        const modal = document.getElementById('modal-phone');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                const input = document.getElementById('new_phone');
                if (input) {
                    input.focus();
                    input.select();
                }
            }, 50);
        }
    }

    function closePhoneModal() {
        const modal = document.getElementById('modal-phone');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const emailModal = document.getElementById('modal-email');
        if (emailModal) {
            emailModal.addEventListener('click', function (e) {
                if (e.target === emailModal) closeEmailModal();
            });
        }

        const passwordModal = document.getElementById('modal-password');
        if (passwordModal) {
            passwordModal.addEventListener('click', function (e) {
                if (e.target === passwordModal) closePasswordModal();
            });
        }

        const phoneModal = document.getElementById('modal-phone');
        if (phoneModal) {
            phoneModal.addEventListener('click', function (e) {
                if (e.target === phoneModal) closePhoneModal();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (emailModal && !emailModal.classList.contains('hidden')) closeEmailModal();
                if (passwordModal && !passwordModal.classList.contains('hidden')) closePasswordModal();
                if (phoneModal && !phoneModal.classList.contains('hidden')) closePhoneModal();
            }
        });

        @if(session('error_modal') === 'modal-email' || $errors->has('email') || $errors->has('current_password'))
            openEmailModal();
        @elseif(session('error_modal') === 'modal-password' || $errors->has('password') || $errors->has('current_password_password'))
            openPasswordModal();
        @elseif(session('error_modal') === 'modal-phone' || $errors->has('phone'))
            openPhoneModal();
        @endif
    });
</script>
@endsection
