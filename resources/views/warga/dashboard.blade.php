@extends('layouts.citizen')

@section('title', 'Beranda Warga | SIM-LiBas RT 15')

@section('content')
<!-- Sapaan Warga & Profil Singkat -->
<div class="sim-gradient rounded-3xl p-6 sm:p-8 text-white mb-6 sm:mb-8 shadow-lg relative overflow-hidden">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="text-xs uppercase tracking-wider text-blue-200 font-semibold mb-1">
                Selamat Datang di SIM-LiBas
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold">
                Halo, {{ $user->name }}
            </h1>
            <p class="text-xs text-blue-100 mt-1">
                {{ $household?->alamat ?? 'RT 15 Pematang Sulur' }}
            </p>
        </div>

        @if($household)
            <!-- Link ke Halaman Khusus Detail Kartu Keluarga (Solid White Pill Button) -->
            <a href="{{ route('warga.kartu-keluarga') }}" 
               title="Nomor Kartu Keluarga: {{ $household->masked_no_kk ?? '-' }}"
               aria-label="Nomor Kartu Keluarga"
               class="inline-flex items-center gap-2.5 bg-white text-primary-900 hover:bg-blue-50 active:scale-95 px-4 py-2.5 rounded-xl shadow-md transition group text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-white/50">
                <span>Kartu Keluarga</span>
                <span class="w-px h-3.5 bg-slate-200"></span>
                <span class="font-mono text-slate-500 font-medium">{{ $household->masked_no_kk ?? '-' }}</span>
                <svg class="w-3.5 h-3.5 text-primary-700 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif
    </div>
</div>

<!-- 3 Kartu Ringkas Utama (Tagihan, Surat, Bank Sampah) -->
{{-- Versi Desktop: 3 card lengkap (Iuran, Surat, Tabungan Sampah) seperti sebelumnya --}}
{{-- Versi Mobile: Hanya menampilkan 2 card utama (Iuran Bulan Ini & Surat Terakhir) --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
    <!-- 1. Tagihan Iuran Warga -->
    <div class="sim-card p-6 flex flex-col justify-between">
        <div>
            <div class="text-xs text-text-muted font-bold uppercase tracking-wider mb-2">
                Iuran Bulan Ini
            </div>
            <div class="text-2xl font-extrabold text-primary-900">
                Rp {{ number_format($totalTagihan, 0, ',', '.') }}
            </div>
            <p class="text-xs text-text-muted mt-2">
                @if($totalTagihan > 0)
                    <span class="text-amber-600 font-semibold inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Ada tagihan yang belum lunas
                    </span>
                @else
                    <span class="text-green-600 font-semibold inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Semua iuran telah lunas
                    </span>
                @endif
            </p>
        </div>
        <div class="mt-5 pt-4 border-t border-border-soft">
            <a href="{{ route('warga.iuran') }}" class="text-xs font-bold text-primary-700 hover:text-primary-900 inline-flex items-center gap-1 group">
                <span>Lihat Tagihan & Bayar</span>
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    <!-- 2. Status Surat Terakhir -->
    <div class="sim-card p-6 flex flex-col justify-between">
        <div>
            <div class="text-xs text-text-muted font-bold uppercase tracking-wider mb-2">
                Surat Terakhir
            </div>
            @if($latestLetter)
                <div class="text-base font-bold text-primary-900 truncate">
                    {{ $latestLetter->letterType?->nama ?? 'Surat Pengantar' }}
                </div>
                <div class="mt-2">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $latestLetter->status === 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                        Status: {{ ucfirst($latestLetter->status) }}
                    </span>
                </div>
            @else
                <div class="text-sm font-semibold text-text-muted">
                    Belum ada pengajuan surat
                </div>
                <p class="text-xs text-text-muted mt-2">Ajukan permohonan surat pengantar dari ponsel</p>
            @endif
        </div>
        <div class="mt-5 pt-4 border-t border-border-soft">
            <a href="{{ route('warga.surat') }}" class="text-xs font-bold text-primary-700 hover:text-primary-900 inline-flex items-center gap-1 group">
                <span>Ajukan / Lacak Surat</span>
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    <!-- 3. Saldo Bank Sampah (Tampil di Desktop, Tersembunyi di Mobile) -->
    <div class="hidden sm:flex sim-card p-6 flex-col justify-between">
        <div>
            <div class="text-xs text-text-muted font-bold uppercase tracking-wider mb-2">
                Tabungan Sampah
            </div>
            <div class="text-2xl font-extrabold text-green-700">
                Rp {{ number_format($saldoBankSampah, 0, ',', '.') }}
            </div>
            <p class="text-xs text-text-muted mt-2">
                Bank Sampah Pinang Masak Lestari
            </p>
        </div>
        <div class="mt-5 pt-4 border-t border-border-soft">
            <a href="{{ route('public.bank-sampah') }}" class="text-xs font-bold text-green-700 hover:text-green-900 inline-flex items-center gap-1 group">
                <span>Cek Harga Sampah</span>
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

<!-- Menu Cepat Layanan Warga -->
<div class="mb-10">
    <h2 class="text-xs font-bold uppercase tracking-wider text-text-muted mb-4">Layanan Cepat</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('warga.surat', ['action' => 'create']) }}" class="sim-card p-5 hover:border-primary-500 hover:shadow-md transition group block">
            <div class="flex items-start justify-between gap-2">
                <div class="text-sm font-bold text-primary-900 group-hover:text-primary-700 transition">Ajukan Surat</div>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-700 group-hover:translate-x-0.5 transition shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="text-xs text-text-muted mt-1.5 leading-snug">Domisili, KTP, SKCK</div>
        </a>

        <a href="{{ route('warga.iuran') }}" class="sim-card p-5 hover:border-primary-500 hover:shadow-md transition group block">
            <div class="flex items-start justify-between gap-2">
                <div class="text-sm font-bold text-primary-900 group-hover:text-primary-700 transition">Bayar Iuran</div>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-700 group-hover:translate-x-0.5 transition shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="text-xs text-text-muted mt-1.5 leading-snug">Upload Bukti Bayar</div>
        </a>

        <a href="{{ route('warga.pengaduan', ['action' => 'create']) }}" class="sim-card p-5 hover:border-primary-500 hover:shadow-md transition group block">
            <div class="flex items-start justify-between gap-2">
                <div class="text-sm font-bold text-primary-900 group-hover:text-primary-700 transition">Lapor Keluhan</div>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-700 group-hover:translate-x-0.5 transition shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="text-xs text-text-muted mt-1.5 leading-snug">Fasilitas / Lingkungan</div>
        </a>

        <a href="{{ route('public.umkm') }}" class="sim-card p-5 hover:border-primary-500 hover:shadow-md transition group block">
            <div class="flex items-start justify-between gap-2">
                <div class="text-sm font-bold text-primary-900 group-hover:text-primary-700 transition">Katalog UMKM</div>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-700 group-hover:translate-x-0.5 transition shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="text-xs text-text-muted mt-1.5 leading-snug">Belanja di Tetangga</div>
        </a>
    </div>
</div>

<!-- Informasi & Pengumuman Terbaru -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="sim-card p-6">
        <h3 class="text-base font-extrabold text-primary-900 mb-4">
            Pengumuman Terkini
        </h3>
        <div class="space-y-3">
            @forelse($pengumuman as $info)
                <a href="{{ route('public.pengumuman.detail', $info->id) }}" class="block p-3.5 rounded-xl bg-bg-soft hover:bg-blue-50/80 transition group border border-transparent hover:border-blue-100">
                    <div class="text-[10px] text-text-muted font-bold uppercase">{{ $info->created_at->format('d M Y') }}</div>
                    <div class="text-sm font-bold text-primary-900 group-hover:text-primary-700 transition mt-0.5">{{ $info->judul }}</div>
                    <div class="text-xs text-text-muted line-clamp-2 mt-1">{{ $info->isi }}</div>
                </a>
            @empty
                <p class="text-xs text-text-muted">Tidak ada pengumuman saat ini.</p>
            @endforelse
        </div>
    </div>

    <div class="sim-card p-6">
        <h3 class="text-base font-extrabold text-primary-900 mb-4">
            Agenda Kegiatan Lingkungan
        </h3>
        <div class="space-y-3">
            @forelse($agenda as $event)
                <div class="p-3.5 rounded-xl bg-bg-soft flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg sim-gradient text-white flex flex-col items-center justify-center shrink-0">
                        <span class="text-[9px] font-bold">{{ $event->tanggal_mulai->format('M') }}</span>
                        <span class="text-xs font-extrabold">{{ $event->tanggal_mulai->format('d') }}</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-primary-900">{{ $event->judul }}</div>
                        <div class="text-[11px] text-text-muted mt-0.5">
                            {{ $event->lokasi ?? 'RT 15 Pematang Sulur' }}
                            @if($event->penyelenggara)
                                • <span class="font-medium text-primary-700">{{ $event->penyelenggara }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-text-muted">Belum ada agenda dalam waktu dekat.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
