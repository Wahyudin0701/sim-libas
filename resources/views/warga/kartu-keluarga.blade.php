@extends('layouts.citizen')

@section('title', 'Detail Kartu Keluarga | SIM-LiBas RT 15')

@section('content')
<!-- Tombol Kembali ke Beranda (Desktop only, karena di mobile sudah ada di topbar) -->
<div class="mb-6 hidden md:flex items-center">
    <a href="{{ route('warga.dashboard') }}" 
       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-border-soft hover:bg-slate-50 hover:border-slate-300 text-xs font-semibold text-primary-900 shadow-sm transition active:scale-95 group focus:outline-none focus:ring-2 focus:ring-primary-500/20">
        <svg class="w-4 h-4 text-text-muted group-hover:text-primary-900 group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Beranda</span>
    </a>
</div>

@if(!$household)
    <!-- State jika Warga Belum Terhubung dengan Data Kartu Keluarga -->
    <div class="sim-card p-10 text-center bg-white">
        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3 border border-amber-200">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h2 class="text-base font-bold text-primary-900 mb-1">Data Kartu Keluarga Belum Terhubung</h2>
        <p class="text-xs text-text-muted max-w-md mx-auto mb-5 leading-relaxed">
            Akun Anda belum terhubung dengan nomor Kartu Keluarga resmi di sistem RT 15. Silakan hubungi pengurus RT untuk verifikasi data kependudukan keluarga Anda.
        </p>
        <a href="{{ route('warga.dashboard') }}" class="sim-btn-primary text-xs py-2 px-5">
            Kembali ke Beranda
        </a>
    </div>
@else
    <!-- Judul Halaman Sederhana -->
    <div class="mb-5">
        <h1 class="text-xl sm:text-2xl font-extrabold text-primary-900 tracking-tight">
            Kartu Keluarga: {{ $household->kepalaKeluarga?->nama ?? $household->user?->name ?? 'Warga RT 15' }}
        </h1>
    </div>

    <!-- 1. KARTU RINGKASAN TUNGGAL (Semua Info KK Terpadu Rapi) -->
    <div class="sim-card p-5 sm:p-6 mb-6 bg-white">
        <!-- Bagian Atas: Nomor KK & Status -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-border-soft">
            <div>
                <span class="text-[11px] font-bold text-text-muted uppercase tracking-wider block">
                    Nomor Kartu Keluarga (16 Digit)
                </span>
                <div class="flex items-center gap-2.5 mt-1">
                    <span class="font-mono font-extrabold text-xl sm:text-2xl text-primary-900 tracking-wide">
                        {{ $household->no_kk }}
                    </span>
                    <button type="button" 
                            id="btn-copy-kk"
                            onclick="copyKkNumber('{{ $household->no_kk }}')" 
                            class="px-2.5 py-1 text-xs font-bold text-primary-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition cursor-pointer">
                        Salin
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Status Tempat Tinggal: {{ ucfirst($household->status_hunian ?? 'Tetap') }}</span>
                </span>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $household->id_kk }}
                </span>
            </div>
        </div>

        <!-- Bagian Bawah: Grid Info Ringkas (3 Kolom Bersih) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-xs">
            <div>
                <span class="text-text-muted text-[11px] font-medium block">Kepala Keluarga</span>
                <span class="font-bold text-gray-900 text-sm mt-0.5 block">
                    {{ $household->kepalaKeluarga?->nama ?? $household->user?->name ?? '-' }}
                </span>
            </div>

            <div>
                <span class="text-text-muted text-[11px] font-medium block">Jumlah Anggota Keluarga</span>
                <span class="font-bold text-gray-900 text-sm mt-0.5 block">
                    {{ $household->residents->count() }} Jiwa Terdaftar
                </span>
            </div>

            <div>
                <span class="text-text-muted text-[11px] font-medium block">Alamat Domisili</span>
                <span class="font-medium text-gray-900 mt-0.5 block leading-relaxed">
                    {{ $household->alamat ?? '-' }}, {{ $household->rt_rw ?? 'RT 15 / RW 04' }}, Kel. {{ $household->kelurahan ?? 'Pematang Sulur' }}
                </span>
            </div>
        </div>
    </div>

    <!-- 2. KARTU DAFTAR ANGGOTA KELUARGA (Fokus Utama Dokumen KK) -->
    <div class="sim-card p-5 sm:p-6 mb-6 bg-white">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-border-soft">
            <h2 class="text-sm font-extrabold text-primary-900 uppercase tracking-wider">
                Daftar Anggota Keluarga Terdaftar
            </h2>
            <span class="text-xs text-text-muted font-semibold bg-bg-soft px-2.5 py-1 rounded-full border border-border-soft">
                {{ $household->residents->count() }} Anggota
            </span>
        </div>

        <div class="overflow-x-auto rounded-xl border border-border-soft">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-soft text-text-muted uppercase tracking-wider border-b border-border-soft text-[10px] font-bold">
                    <tr>
                        <th class="py-2.5 px-3 text-center w-8">No</th>
                        <th class="py-2.5 px-3">Nama Lengkap & NIK</th>
                        <th class="py-2.5 px-3">Hubungan</th>
                        <th class="py-2.5 px-3 text-center">L/P</th>
                        <th class="py-2.5 px-3">Tempat, Tgl Lahir</th>
                        <th class="py-2.5 px-3">Pekerjaan</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft bg-white font-medium">
                    @forelse($household->residents as $index => $res)
                        <tr class="hover:bg-slate-50/70 transition {{ $res->is_kepala_keluarga ? 'bg-blue-50/20' : '' }}">
                            <td class="py-3 px-3 text-center font-bold text-text-muted">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-bold text-primary-900 text-sm flex items-center gap-1.5">
                                    <span>{{ $res->nama }}</span>
                                    @if($res->is_kepala_keluarga)
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase bg-primary-100 text-primary-700">
                                            Kepala KK
                                        </span>
                                    @endif
                                </div>
                                <div class="font-mono text-xs text-text-muted mt-0.5">
                                    NIK: <span class="font-semibold text-gray-800">{{ $res->nik }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-700 border border-gray-200 capitalize">
                                    {{ str_replace('_', ' ', $res->hubungan_keluarga ?? '-') }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center font-bold text-gray-700">
                                {{ $res->jenis_kelamin }}
                            </td>
                            <td class="py-3 px-3 text-gray-700">
                                <div>{{ $res->tempat_lahir ?? '-' }}</div>
                                <div class="text-text-muted text-[11px]">{{ $res->tanggal_lahir ? $res->tanggal_lahir->format('d M Y') : '-' }}</div>
                            </td>
                            <td class="py-3 px-3 text-gray-700">
                                <div class="font-semibold text-gray-900">{{ $res->pekerjaan ?? '-' }}</div>
                                <div class="text-text-muted text-[11px]">{{ $res->pendidikan_terakhir ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($res->status_kependudukan ?? 'Aktif') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-text-muted">
                                Belum ada data anggota keluarga yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Lampiran Berkas Fisik KK (Bila Tersedia) -->
    @if($household->foto_kk_url)
        <div class="sim-card p-5 mb-6 bg-white">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-bold text-primary-900 uppercase tracking-wider">
                    Salinan Dokumen Fisik Kartu Keluarga
                </h2>
                <a href="{{ $household->foto_kk_url }}" target="_blank" class="text-xs font-semibold text-primary-700 hover:text-primary-900 inline-flex items-center gap-1">
                    <span>Lihat Ukuran Penuh</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
            <div class="max-w-md rounded-xl overflow-hidden border border-border-soft bg-bg-soft p-1.5">
                <img src="{{ $household->foto_kk_url }}" alt="Salinan Berkas Kartu Keluarga" class="w-full h-auto rounded-lg object-contain">
            </div>
        </div>
    @endif

    <!-- Catatan Sederhana di Bawah -->
    <div class="text-center py-2">
        <p class="text-xs text-text-muted">
            Ada perubahan data anggota keluarga? Silakan ajukan melalui 
            <a href="{{ route('warga.surat') }}" class="text-primary-700 font-bold hover:underline">Layanan Surat Pengantar</a> 
            atau hubungi pengurus RT 15 Pematang Sulur.
        </p>
    </div>
@endif
@endsection

@push('scripts')
<script>
    function copyKkNumber(noKk) {
        if (!noKk) return;
        navigator.clipboard.writeText(noKk).then(function() {
            const btn = document.getElementById('btn-copy-kk');
            if (btn) {
                const origText = btn.textContent;
                btn.textContent = 'Tersalin!';
                btn.classList.add('bg-green-100', 'text-green-800');
                setTimeout(function() {
                    btn.textContent = origText;
                    btn.classList.remove('bg-green-100', 'text-green-800');
                }, 2000);
            }
        }).catch(function(err) {
            console.error('Gagal menyalin no KK:', err);
        });
    }
</script>
@endpush
