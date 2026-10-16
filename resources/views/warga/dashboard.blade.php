@extends('layouts.citizen')

@section('title', 'Beranda Warga | SIM-LiBas RT 15')

@section('content')
<!-- Sapaan Warga & Profil Singkat -->
<div class="sim-gradient rounded-3xl p-6 sm:p-8 text-white mb-8 shadow-lg relative overflow-hidden">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="text-xs uppercase tracking-wider text-blue-200 font-semibold mb-1">
                Selamat Datang di SIM-LiBas
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold">
                Halo, {{ $user->name }}
            </h1>
            <p class="text-xs text-blue-100 mt-1">
                {{ $household?->alamat ?? 'RT 15 Pematang Sulur' }} • Status: Warga Aktif
            </p>
        </div>

        @if($household)
            <div class="bg-white/15 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/20 text-xs text-right">
                <span class="text-blue-200 block">Nomor Kartu Keluarga</span>
                <span class="font-mono font-bold text-white">{{ $household->no_kk ? substr($household->no_kk, 0, 4) . '********' . substr($household->no_kk, -4) : '-' }}</span>
            </div>
        @endif
    </div>
</div>

<!-- 3 Kartu Ringkas Utama (Tagihan, Surat, Bank Sampah) -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <!-- 1. Tagihan Iuran Warga -->
    <div class="sim-card p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-text-muted font-semibold uppercase tracking-wider">Iuran Bulan Ini</span>
                <span class="w-8 h-8 rounded-lg bg-amber-100 text-gold-700 flex items-center justify-center font-bold text-xs">
                    💳
                </span>
            </div>
            <div class="text-2xl font-extrabold text-primary-900">
                Rp {{ number_format($totalTagihan, 0, ',', '.') }}
            </div>
            <p class="text-xs text-text-muted mt-1">
                @if($totalTagihan > 0)
                    <span class="text-amber-600 font-semibold">Ada tagihan yang belum lunas</span>
                @else
                    <span class="text-green-600 font-semibold">Semua iuran telah lunas</span>
                @endif
            </p>
        </div>
        <div class="mt-4 pt-4 border-t border-border-soft">
            <a href="{{ route('warga.iuran') }}" class="text-xs font-bold text-primary-700 hover:text-primary-900 inline-flex items-center">
                Lihat Tagihan & Bayar &gt;
            </a>
        </div>
    </div>

    <!-- 2. Status Surat Terakhir -->
    <div class="sim-card p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-text-muted font-semibold uppercase tracking-wider">Surat Terakhir</span>
                <span class="w-8 h-8 rounded-lg bg-blue-100 text-primary-700 flex items-center justify-center font-bold text-xs">
                    📄
                </span>
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
                <p class="text-xs text-text-muted mt-1">Ajukan permohonan surat pengantar dari ponsel</p>
            @endif
        </div>
        <div class="mt-4 pt-4 border-t border-border-soft">
            <a href="{{ route('warga.surat') }}" class="text-xs font-bold text-primary-700 hover:text-primary-900 inline-flex items-center">
                Ajukan / Lacak Surat &gt;
            </a>
        </div>
    </div>

    <!-- 3. Saldo Bank Sampah -->
    <div class="sim-card p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-text-muted font-semibold uppercase tracking-wider">Tabungan Sampah</span>
                <span class="w-8 h-8 rounded-lg bg-green-100 text-green-700 flex items-center justify-center font-bold text-xs">
                    🍃
                </span>
            </div>
            <div class="text-2xl font-extrabold text-green-700">
                Rp {{ number_format($saldoBankSampah, 0, ',', '.') }}
            </div>
            <p class="text-xs text-text-muted mt-1">
                Bank Sampah Pinang Masak Lestari
            </p>
        </div>
        <div class="mt-4 pt-4 border-t border-border-soft">
            <a href="{{ route('public.bank-sampah') }}" class="text-xs font-bold text-green-700 hover:text-green-900 inline-flex items-center">
                Cek Harga Sampah &gt;
            </a>
        </div>
    </div>
</div>

<!-- Menu Cepat Layanan Warga -->
<div class="mb-10">
    <h2 class="text-sm font-bold uppercase tracking-wider text-text-muted mb-4">Layanan Cepat</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('warga.surat') }}" class="sim-card p-5 text-center hover:border-primary-500 transition">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-primary-700 flex items-center justify-center mx-auto mb-2 text-2xl">
                ✍️
            </div>
            <div class="text-xs font-bold text-primary-900">Ajukan Surat</div>
            <div class="text-[10px] text-text-muted mt-0.5">Domisili, KTP, SKCK</div>
        </a>

        <a href="{{ route('warga.iuran') }}" class="sim-card p-5 text-center hover:border-primary-500 transition">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-gold-700 flex items-center justify-center mx-auto mb-2 text-2xl">
                💳
            </div>
            <div class="text-xs font-bold text-primary-900">Bayar Iuran</div>
            <div class="text-[10px] text-text-muted mt-0.5">Upload Bukti Bayar</div>
        </a>

        <a href="{{ route('warga.pengaduan') }}" class="sim-card p-5 text-center hover:border-primary-500 transition">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-2 text-2xl">
                📢
            </div>
            <div class="text-xs font-bold text-primary-900">Lapor Keluhan</div>
            <div class="text-[10px] text-text-muted mt-0.5">Fasilitas / Lingkungan</div>
        </a>

        <a href="{{ route('public.umkm') }}" class="sim-card p-5 text-center hover:border-primary-500 transition">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-700 flex items-center justify-center mx-auto mb-2 text-2xl">
                🛍️
            </div>
            <div class="text-xs font-bold text-primary-900">Katalog UMKM</div>
            <div class="text-[10px] text-text-muted mt-0.5">Belanja di Tetangga</div>
        </a>
    </div>
</div>

<!-- Informasi & Pengumuman Terbaru -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="sim-card p-6">
        <h3 class="text-base font-extrabold text-primary-900 mb-4 flex items-center gap-2">
            <span>📢</span> Pengumuman Terkini
        </h3>
        <div class="space-y-3">
            @forelse($pengumuman as $info)
                <div class="p-3.5 rounded-xl bg-bg-soft">
                    <div class="text-[10px] text-text-muted font-bold uppercase">{{ $info->created_at->format('d M Y') }}</div>
                    <div class="text-sm font-bold text-primary-900 mt-0.5">{{ $info->judul }}</div>
                    <div class="text-xs text-text-muted line-clamp-2 mt-1">{{ $info->isi }}</div>
                </div>
            @empty
                <p class="text-xs text-text-muted">Tidak ada pengumuman saat ini.</p>
            @endforelse
        </div>
    </div>

    <div class="sim-card p-6">
        <h3 class="text-base font-extrabold text-primary-900 mb-4 flex items-center gap-2">
            <span>📅</span> Agenda Kegiatan Lingkungan
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
                        <div class="text-[10px] text-text-muted mt-0.5">📍 {{ $event->lokasi ?? 'RT 15' }}</div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-text-muted">Belum ada agenda dalam waktu dekat.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
