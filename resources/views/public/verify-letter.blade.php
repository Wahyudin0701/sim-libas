@extends('layouts.public')

@section('title', 'Verifikasi Keaslian Surat Pengantar | SIM-LiBas RT 15')

@section('content')
<div class="py-16 max-w-3xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-primary-900">Verifikasi Keaslian Dokumen</h1>
        <p class="text-sm text-text-muted mt-2">Sistem Verifikasi Digital Surat Pengantar RT 15 Pematang Sulur</p>
    </div>

    @if($letter && $letter->status === 'disetujui')
        <!-- KARTU STATUS SAH (HIJAU) -->
        <div class="sim-card p-8 border-2 border-green-500 bg-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-green-500 text-white font-bold text-xs uppercase tracking-widest px-8 py-1 rotate-45 translate-x-8 translate-y-4">
                ASLI
            </div>

            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="w-16 h-16 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 uppercase tracking-wide">
                        STATUS: RESMI & TERVERIFIKASI SAH
                    </span>
                    <h2 class="text-xl font-extrabold text-primary-900 mt-1">Dokumen Asli Rukun Tetangga 15</h2>
                </div>
            </div>

            <!-- Detail Dokumen -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
                <div class="bg-bg-soft p-4 rounded-xl">
                    <span class="text-xs text-text-muted block">Nomor Surat Resmi</span>
                    <span class="font-extrabold text-primary-900 text-base font-mono">{{ $letter->nomor_surat }}</span>
                </div>
                <div class="bg-bg-soft p-4 rounded-xl">
                    <span class="text-xs text-text-muted block">Jenis Surat</span>
                    <span class="font-bold text-primary-900">{{ $letter->letterType?->nama ?? 'Surat Pengantar RT' }}</span>
                </div>
                <div class="bg-bg-soft p-4 rounded-xl">
                    <span class="text-xs text-text-muted block">Nama Warga Pemohon</span>
                    <span class="font-bold text-primary-900">{{ $letter->resident?->nama ?? '-' }}</span>
                </div>
                <div class="bg-bg-soft p-4 rounded-xl">
                    <span class="text-xs text-text-muted block">Tanggal Diterbitkan</span>
                    <span class="font-bold text-primary-900">{{ $letter->tanggal_disetujui ? $letter->tanggal_disetujui->format('d F Y') : '-' }}</span>
                </div>
                <div class="bg-bg-soft p-4 rounded-xl sm:col-span-2">
                    <span class="text-xs text-text-muted block">Penandatangan / Pejabat Berwenang</span>
                    <span class="font-bold text-primary-900">{{ $setting->nama_ketua_rt ?? 'Ketua RT 15 Pematang Sulur' }}</span>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-xs text-blue-900 flex items-start gap-3">
                <svg class="w-5 h-5 text-primary-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <strong>Pemberitahuan Pelindungan Data Pribadi (UU PDP No. 27/2022):</strong>
                    Nomor Induk Kependudukan (NIK) sengaja tidak ditampilkan pada laman publik verifikasi untuk menjaga privasi warga.
                </div>
            </div>
        </div>
    @else
        <!-- KARTU STATUS TIDAK DITEMUKAN / TIDAK VALID (MERAH) -->
        <div class="sim-card p-8 border-2 border-red-500 bg-white shadow-xl text-center">
            <div class="w-20 h-20 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>

            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 uppercase tracking-wide mb-2">
                DOKUMEN TIDAK DITEMUKAN / TIDAK SAH
            </span>

            <h2 class="text-2xl font-extrabold text-red-700 mb-3">Surat Tidak Terdaftar di Basis Data RT 15</h2>

            <p class="text-sm text-text-muted max-w-md mx-auto mb-6 leading-relaxed">
                Kode token atau nomor surat yang Anda masukkan <code class="bg-gray-100 text-red-600 px-2 py-0.5 rounded font-mono">{{ $token }}</code> tidak ditemukan atau belum disetujui secara resmi oleh Ketua RT 15 Pematang Sulur.
            </p>

            <a href="{{ route('public.home') }}" class="sim-btn-primary text-sm">
                Kembali ke Beranda
            </a>
        </div>
    @endif
</div>
@endsection
