@extends('layouts.citizen')

@section('title', 'Layanan Surat Pengantar | SIM-LiBas RT 15')

@section('content')
<!-- Header Konten: Judul Riwayat & Tombol Tambah Pengajuan -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-primary-900 tracking-tight">
            Riwayat Permohonan Surat
        </h1>
        <p class="text-xs text-text-muted mt-0.5">
            Daftar permohonan surat pengantar keluarga terdaftar di RT 15 Pematang Sulur
        </p>
    </div>

    <!-- Tombol Tambah Pengajuan (Buka Popup Modal) -->
    <button type="button" 
            onclick="openSuratModal()" 
            class="sim-btn-primary text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 cursor-pointer active:scale-95 transition shrink-0 self-start sm:self-auto">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Ajukan Surat Baru</span>
    </button>
</div>

<!-- Tampilan Riwayat Surat (Tanpa Card Pembungkus) -->
<div class="mb-4 flex items-center justify-between">
    <span class="text-xs font-bold text-text-muted uppercase tracking-wider">Daftar Permohonan Surat</span>
    <span class="text-xs text-text-muted font-semibold bg-white px-2.5 py-1 rounded-full border border-slate-200 shadow-2xs">
        {{ $letters->total() }} Permohonan
    </span>
</div>

    @if($letters->isEmpty())
        <div class="py-16 px-6 text-center text-text-muted">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-primary-700 flex items-center justify-center mx-auto mb-3 border border-blue-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-primary-900">Belum Ada Riwayat Surat</p>
            <p class="text-xs text-text-muted mt-1 max-w-sm mx-auto">
                Anda belum pernah mengajukan surat pengantar. Klik tombol di bawah untuk membuat pengajuan baru.
            </p>
            <button type="button" 
                    onclick="openSuratModal()" 
                    class="mt-4 sim-btn-primary text-xs font-bold px-4 py-2 rounded-xl shadow-xs">
                + Buat Pengajuan Pertama
            </button>
        </div>
    @else
        <div class="space-y-3">
            @foreach($letters as $letter)
                @php
                    $letterData = [
                        'id' => $letter->id,
                        'title' => $letter->letterType?->nama ?? 'Surat Pengantar',
                        'code' => $letter->letterType?->kode ?? '',
                        'applicant' => $letter->resident?->nama ?? 'Warga RT 15',
                        'nik' => $letter->resident?->nik ?? '-',
                        'date' => $letter->created_at->format('d M Y, H:i') . ' WIB',
                        'status' => $letter->status,
                        'status_label' => match($letter->status) {
                            'disetujui' => 'Disetujui',
                            'diajukan' => 'Menunggu Verifikasi',
                            'diperiksa' => 'Sedang Diproses',
                            'ditolak' => 'Ditolak',
                            default => ucfirst($letter->status),
                        },
                        'keperluan' => $letter->keperluan,
                        'nomor_surat' => $letter->nomor_surat ?? '-',
                        'alasan_tolak' => $letter->alasan_tolak ?? '',
                        'verify_url' => route('public.verify-letter', $letter->qr_token ?? $letter->id),
                        'print_url' => route('letters.print', $letter->id),
                    ];
                @endphp

                <div onclick='openSuratDetailModal(@json($letterData))' 
                     class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:border-primary-400 hover:shadow-md transition-all duration-200 p-4 sm:p-5 flex items-center justify-between gap-4 cursor-pointer group active:scale-[0.99]">
                    <!-- Kiri: Info Esensial -->
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-primary-700 transition truncate">
                                {{ $letter->letterType?->nama ?? 'Surat Pengantar' }}
                            </h3>
                            @if($letter->letterType?->kode)
                                <span class="text-[10px] font-bold text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md uppercase shrink-0">
                                    {{ $letter->letterType->kode }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 truncate">
                            <span class="font-medium text-slate-700 truncate">{{ $letter->resident?->nama ?? 'Warga RT 15' }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="shrink-0">{{ $letter->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Kanan: Status Badge & Chevron -->
                    <div class="flex items-center gap-3 shrink-0">
                        @if($letter->status === 'disetujui')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Disetujui</span>
                            </span>
                        @elseif($letter->status === 'diajukan')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>Menunggu</span>
                            </span>
                        @elseif($letter->status === 'diperiksa')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-primary-700 border border-blue-200">
                                <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                                <span>Diproses</span>
                            </span>
                        @elseif($letter->status === 'ditolak')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span>Ditolak</span>
                            </span>
                        @endif

                        <svg class="w-4 h-4 text-slate-300 group-hover:text-primary-600 group-hover:translate-x-0.5 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $letters->links() }}
        </div>
    @endif

<!-- 3. POPUP MODAL FORM PENGAJUAN SURAT -->
<div id="modal-surat" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop-blur bg-slate-900/50 backdrop-blur-md transition-all duration-200" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl shadow-2xl border border-border-soft max-w-lg w-full max-h-[90vh] overflow-y-auto no-scrollbar transform transition-all" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="p-5 sm:p-6 pb-3 sm:pb-4 border-b border-border-soft flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-primary-900">Buat Pengajuan Surat Baru</h3>
                <p class="text-xs text-text-muted mt-0.5">Surat pengantar resmi RT 15 Pematang Sulur</p>
            </div>
            <button type="button" onclick="closeSuratModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Form Body -->
        <form action="{{ route('warga.surat.store') }}" method="POST" class="p-5 sm:p-6 space-y-3.5">
            @csrf

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <span class="font-bold block text-rose-950 mb-0.5">Mohon lengkapi isian form:</span>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Pilih Jenis Surat <span class="text-red-500">*</span>
                </label>
                <select name="letter_type_id" required class="w-full bg-bg-soft border @error('letter_type_id') border-rose-400 ring-1 ring-rose-400 @else border-border-soft @enderror text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Pilih Jenis Surat Pengantar --</option>
                    @foreach($letterTypes as $type)
                        <option value="{{ $type->id }}" {{ old('letter_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->nama }} ({{ $type->kode }})
                        </option>
                    @endforeach
                </select>
                @error('letter_type_id')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Warga Pemohon <span class="text-red-500">*</span>
                </label>
                <select name="resident_id" required class="w-full bg-bg-soft border @error('resident_id') border-rose-400 ring-1 ring-rose-400 @else border-border-soft @enderror text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @if($isFamilyHeadOrSpouse && $household && $household->residents->isNotEmpty())
                        @foreach($household->residents as $res)
                            <option value="{{ $res->id }}" {{ old('resident_id', $resident?->id) == $res->id ? 'selected' : '' }}>
                                {{ $res->nama }} ({{ ucwords(str_replace('_', ' ', $res->hubungan_keluarga)) }})
                            </option>
                        @endforeach
                    @elseif($resident)
                        <option value="{{ $resident->id }}" selected>
                            {{ $resident->nama }} ({{ ucwords(str_replace('_', ' ', $resident->hubungan_keluarga ?? 'Pemohon')) }})
                        </option>
                    @endif
                </select>
                @if(!$isFamilyHeadOrSpouse)
                    <p class="text-[11px] text-text-muted mt-1">Sebagai anggota keluarga, permohonan diajukan atas nama Anda sendiri.</p>
                @endif
                @error('resident_id')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Keperluan Pengajuan <span class="text-red-500">*</span>
                </label>
                <textarea name="keperluan" rows="2" required placeholder="Contoh: Pengurusan perpanjangan KTP elektronik di Kantor Kelurahan Pematang Sulur..." class="w-full bg-bg-soft border @error('keperluan') border-rose-400 ring-1 ring-rose-400 @else border-border-soft @enderror text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 min-h-[60px]">{{ old('keperluan') }}</textarea>
                @error('keperluan')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50/80 border border-blue-100 p-3 rounded-xl text-xs text-blue-900 leading-relaxed">
                Surat akan diverifikasi dan disetujui langsung oleh Admin Pengurus RT 15 lengkap dengan QR Code resmi untuk validasi instan.
            </div>

            <!-- Modal Footer -->
            <div class="pt-2 border-t border-border-soft flex items-center justify-end gap-3">
                <button type="button" onclick="closeSuratModal()" class="px-4 py-2 rounded-xl border border-border-soft text-text-muted hover:bg-slate-50 text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="sim-btn-primary px-5 py-2 text-xs font-bold shadow cursor-pointer">
                    Kirim Permohonan Surat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 4. POPUP MODAL DETAIL RIWAYAT PERMOHONAN SURAT -->
<div id="modal-detail-surat" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop-blur bg-slate-900/50 backdrop-blur-md transition-all duration-200" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl shadow-2xl border border-border-soft max-w-lg w-full max-h-[90vh] overflow-y-auto no-scrollbar transform transition-all" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="p-6 pb-4 border-b border-border-soft flex items-center justify-between">
            <div>
                <h3 id="surat-detail-title" class="text-base font-extrabold text-primary-900">Detail Permohonan Surat</h3>
                <p id="surat-detail-date" class="text-xs text-text-muted mt-0.5"></p>
            </div>
            <button type="button" onclick="closeSuratDetailModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <!-- Status Alert / Banner -->
            <div id="surat-detail-status-box" class="p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3"></div>

            <!-- Detail Grid -->
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-3 text-xs">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Jenis Surat:</span>
                    <span id="surat-detail-type" class="font-bold text-slate-800 text-right"></span>
                </div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Warga Pemohon:</span>
                    <span id="surat-detail-applicant" class="font-bold text-slate-800 text-right"></span>
                </div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">NIK Pemohon:</span>
                    <span id="surat-detail-nik" class="font-mono font-bold text-slate-800 text-right"></span>
                </div>
                <div id="surat-detail-nomor-row" class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Nomor Surat:</span>
                    <span id="surat-detail-nomor" class="font-mono font-extrabold text-primary-900 bg-white px-2 py-0.5 rounded border border-slate-200 text-right"></span>
                </div>
                <div>
                    <span class="text-slate-500 font-medium block mb-1">Keperluan Pengajuan:</span>
                    <p id="surat-detail-keperluan" class="text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 leading-relaxed font-medium"></p>
                </div>
            </div>

            <!-- Action Buttons for Disetujui -->
            <div id="surat-detail-actions" class="hidden flex flex-col sm:flex-row items-center gap-2.5 pt-2">
                <a id="surat-detail-qr-btn" href="#" target="_blank" class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold inline-flex items-center justify-center gap-2 text-xs transition border border-slate-200 shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>Cek Validasi QR</span>
                </a>
                <a id="surat-detail-print-btn" href="#" target="_blank" class="w-full sm:w-1/2 sim-btn-primary px-4 py-2.5 rounded-xl text-white font-bold inline-flex items-center justify-center gap-2 text-xs shadow-xs transition hover:opacity-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Surat PDF</span>
                </a>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 bg-slate-50 border-t border-border-soft flex items-center justify-end rounded-b-3xl">
            <button type="button" onclick="closeSuratDetailModal()" class="px-5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openSuratModal() {
        const modal = document.getElementById('modal-surat');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeSuratModal() {
        const modal = document.getElementById('modal-surat');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openSuratDetailModal(data) {
        const modal = document.getElementById('modal-detail-surat');
        if (!modal) return;

        document.getElementById('surat-detail-title').textContent = data.title;
        document.getElementById('surat-detail-date').textContent = 'Diajukan pada ' + data.date;
        document.getElementById('surat-detail-type').textContent = data.title + (data.code ? ' (' + data.code + ')' : '');
        document.getElementById('surat-detail-applicant').textContent = data.applicant;
        document.getElementById('surat-detail-nik').textContent = data.nik;
        document.getElementById('surat-detail-keperluan').textContent = data.keperluan;

        const nomorRow = document.getElementById('surat-detail-nomor-row');
        const nomorEl = document.getElementById('surat-detail-nomor');
        const statusBox = document.getElementById('surat-detail-status-box');
        const actionsBox = document.getElementById('surat-detail-actions');

        if (data.status === 'disetujui') {
            nomorRow.classList.remove('hidden');
            nomorEl.textContent = data.nomor_surat;
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-emerald-50/70 border-emerald-200 text-emerald-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><span class="font-bold block text-emerald-950">Permohonan Telah Disetujui</span>Nomor surat telah diterbitkan secara resmi dan siap dicetak atau diunduh sebagai dokumen PDF resmi RT 15.</div>';
            actionsBox.classList.remove('hidden');
            document.getElementById('surat-detail-qr-btn').href = data.verify_url;
            document.getElementById('surat-detail-print-btn').href = data.print_url;
        } else if (data.status === 'diajukan') {
            nomorRow.classList.add('hidden');
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-amber-50/70 border-amber-200 text-amber-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><span class="font-bold block text-amber-950">Menunggu Verifikasi Pengurus</span>Permohonan Anda telah tersimpan di sistem dan sedang dalam antrean pemeriksaan oleh Ketua RT 15 (Estimasi: 1×24 jam).</div>';
            actionsBox.classList.add('hidden');
        } else if (data.status === 'diperiksa') {
            nomorRow.classList.add('hidden');
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-blue-50/70 border-blue-200 text-blue-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-primary-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg><div><span class="font-bold block text-blue-950">Sedang Diproses</span>Berkas sedang diperiksa validitasnya dan dipersiapkan nomor surat pengantar oleh sekretariat RT 15.</div>';
            actionsBox.classList.add('hidden');
        } else if (data.status === 'ditolak') {
            nomorRow.classList.add('hidden');
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-rose-50/80 border-rose-200 text-rose-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><div><span class="font-bold block text-rose-950">Permohonan Ditolak</span>' + (data.alasan_tolak || 'Data atau berkas belum lengkap sesuai ketentuan RT.') + '<p class="mt-1 text-rose-700 font-semibold">Silakan buat pengajuan baru dengan melengkapi data yang dibutuhkan.</p></div>';
            actionsBox.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeSuratDetailModal() {
        const modal = document.getElementById('modal-detail-surat');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal-surat');
        const detailModal = document.getElementById('modal-detail-surat');

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeSuratModal();
                }
            });
        }

        if (detailModal) {
            detailModal.addEventListener('click', function (e) {
                if (e.target === detailModal) {
                    closeSuratDetailModal();
                }
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (modal && !modal.classList.contains('hidden')) {
                    closeSuratModal();
                }
                if (detailModal && !detailModal.classList.contains('hidden')) {
                    closeSuratDetailModal();
                }
            }
        });

        @if($errors->any() || request()->query('action') === 'create')
            openSuratModal();
        @endif
    });
</script>
@endsection
