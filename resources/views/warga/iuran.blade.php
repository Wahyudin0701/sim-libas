@extends('layouts.citizen')

@section('title', 'Tagihan & Iuran Warga | SIM-LiBas RT 15')

@section('content')
<!-- Header Konten: Judul & Informasi KK -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-primary-900 tracking-tight">
            Iuran Bulanan & Tagihan
        </h1>
        <p class="text-xs text-text-muted mt-0.5">
            Daftar tagihan iuran kas dan kebersihan keluarga di RT 15 Pematang Sulur
        </p>
    </div>

    @if($household)
        <div class="inline-flex items-center gap-2 bg-white px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-600 shadow-2xs self-start sm:self-auto">
            <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>No. KK: <strong class="text-slate-800 font-bold">{{ $household->nomor_kk }}</strong></span>
        </div>
    @endif
</div>

<!-- Tampilan Daftar Tagihan (Tanpa Card Pembungkus) -->
<div class="mb-4 flex items-center justify-between">
    <span class="text-xs font-bold text-text-muted uppercase tracking-wider">Daftar Tagihan Iuran</span>
    <span class="text-xs text-text-muted font-semibold bg-white px-2.5 py-1 rounded-full border border-slate-200 shadow-2xs">
        {{ $invoices->count() }} Tagihan Terdaftar
    </span>
</div>

    @if($invoices->isEmpty())
        <div class="py-16 px-6 text-center text-text-muted">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 border border-emerald-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-primary-900">Tidak Ada Tagihan Aktif</p>
            <p class="text-xs text-text-muted mt-1 max-w-sm mx-auto">
                Keluarga Anda tidak memiliki tunggakan iuran saat ini. Semua iuran telah tercatat lunas.
            </p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($invoices as $inv)
                @php
                    $bulanNama = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ][$inv->periode_bulan] ?? 'Bulan ' . $inv->periode_bulan;
                    $pendingPayment = $inv->payments->where('status', 'pending')->first();
                    $isLunas = $inv->status === 'lunas';
                    $isPending = ! $isLunas && $pendingPayment;
                    $statusKey = $isLunas ? 'lunas' : ($isPending ? 'pending' : 'belum_lunas');
                    $statusLabel = $isLunas ? 'Lunas' : ($isPending ? 'Menunggu Validasi' : 'Belum Lunas');

                    $invoiceData = [
                        'id' => $inv->id,
                        'title' => $inv->feeType?->nama ?? 'Iuran Wajib RT',
                        'period' => $bulanNama . ' ' . $inv->periode_tahun,
                        'amount_formatted' => 'Rp ' . number_format($inv->jumlah, 0, ',', '.'),
                        'amount_raw' => (float) $inv->jumlah,
                        'status' => $statusKey,
                        'status_label' => $statusLabel,
                        'due_date' => $inv->tanggal_jatuh_tempo ? $inv->tanggal_jatuh_tempo->format('d M Y') : '-',
                        'nomor_kk' => $household?->nomor_kk ?? '-',
                        'pay_url' => route('warga.iuran.pay', $inv->id),
                        'pending_info' => $pendingPayment ? [
                            'jumlah' => 'Rp ' . number_format($pendingPayment->jumlah, 0, ',', '.'),
                            'metode' => strtoupper($pendingPayment->metode),
                            'catatan' => $pendingPayment->catatan ?? '-',
                        ] : null,
                    ];
                @endphp

                <div onclick='openIuranDetailModal(@json($invoiceData))' 
                     class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:border-primary-400 hover:shadow-md transition-all duration-200 p-4 sm:p-5 flex items-center justify-between gap-4 cursor-pointer group active:scale-[0.99]">
                    <!-- Kiri: Info Esensial -->
                    <div class="min-w-0">
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-primary-700 transition truncate">
                            {{ $inv->feeType?->nama ?? 'Iuran Wajib RT' }}
                        </h3>

                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 truncate">
                            <span class="font-medium text-slate-700">Periode: {{ $bulanNama }} {{ $inv->periode_tahun }}</span>
                        </div>
                    </div>

                    <!-- Kanan: Nominal Tagihan, Status Badge & Chevron -->
                    <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                        <div class="text-right">
                            <div class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                                Rp {{ number_format($inv->jumlah, 0, ',', '.') }}
                            </div>
                            <div class="mt-0.5">
                                @if($isLunas)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Lunas</span>
                                    </span>
                                @elseif($isPending)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-primary-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                                        <span>Menunggu</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>Belum Lunas</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <svg class="w-4 h-4 text-slate-300 group-hover:text-primary-600 group-hover:translate-x-0.5 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

<!-- POPUP MODAL DETAIL RIWAYAT IURAN & KONFIRMASI BAYAR -->
<div id="modal-detail-iuran" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop-blur bg-slate-900/50 backdrop-blur-md transition-all duration-200" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl shadow-2xl border border-border-soft max-w-lg w-full max-h-[90vh] overflow-y-auto no-scrollbar transform transition-all" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="p-6 pb-4 border-b border-border-soft flex items-center justify-between">
            <div>
                <h3 id="iuran-detail-title" class="text-base font-extrabold text-primary-900">Detail Tagihan Iuran</h3>
                <p id="iuran-detail-period" class="text-xs text-text-muted mt-0.5"></p>
            </div>
            <button type="button" onclick="closeIuranDetailModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <!-- Status Alert / Banner -->
            <div id="iuran-detail-status-box" class="p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3"></div>

            <!-- Detail Grid -->
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-3 text-xs">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Jenis Tagihan:</span>
                    <span id="iuran-detail-fee-type" class="font-bold text-slate-800 text-right"></span>
                </div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Periode Tagihan:</span>
                    <span id="iuran-detail-period-val" class="font-bold text-slate-800 text-right"></span>
                </div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">No. Kartu Keluarga:</span>
                    <span id="iuran-detail-kk" class="font-mono font-bold text-slate-800 text-right"></span>
                </div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Total Nominal Tagihan:</span>
                    <span id="iuran-detail-amount" class="text-base font-black text-primary-900 text-right"></span>
                </div>
                <div class="flex items-center justify-between pt-0.5">
                    <span class="text-slate-500 font-medium">Kas RT 15:</span>
                    <span class="font-bold text-slate-800 text-right">Bank Jambi (900-15-1515) a.n. Kas RT 15</span>
                </div>
            </div>

            <!-- Form Konfirmasi Bayar (Khusus Belum Lunas) -->
            <div id="iuran-detail-pay-form-wrapper" class="hidden pt-2 border-t border-slate-100">
                <h4 class="text-xs font-bold text-primary-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Kirim Konfirmasi Pembayaran</span>
                </h4>

                <form id="form-bayar" action="" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="jumlah" id="modal-input-jumlah" value="">

                    @if($errors->any())
                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800">
                            <span class="font-bold block mb-0.5">Gagal mengirim konfirmasi bayar:</span>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="metode" required class="w-full bg-bg-soft border @error('metode') border-rose-400 ring-1 ring-rose-400 @else border-border-soft @enderror text-xs sm:text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="transfer">Transfer Bank (Upload Bukti Transfer)</option>
                            <option value="tunai">Tunai Langsung ke Bendahara RT</option>
                        </select>
                        @error('metode')
                            <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Foto Bukti Transfer (Bila Transfer)
                        </label>
                        <input type="file" name="bukti" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-primary-700 hover:file:bg-blue-100 cursor-pointer">
                        <span class="text-[11px] text-slate-400 mt-1 block">Format file: JPG, PNG, atau JPEG (Maks. 2MB)</span>
                        @error('bukti')
                            <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Catatan Tambahan (Opsional)
                        </label>
                        <textarea name="catatan" rows="2" placeholder="Contoh: Ditransfer via m-banking atas nama Dedi Kurniawan..." class="w-full bg-bg-soft border border-border-soft text-xs sm:text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sim-btn-primary py-2.5 text-xs font-bold shadow cursor-pointer justify-center">
                            Kirim Konfirmasi Bayar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 bg-slate-50 border-t border-border-soft flex items-center justify-end rounded-b-3xl">
            <button type="button" onclick="closeIuranDetailModal()" class="px-5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openIuranDetailModal(data) {
        const modal = document.getElementById('modal-detail-iuran');
        if (!modal) return;

        document.getElementById('iuran-detail-title').textContent = data.title;
        document.getElementById('iuran-detail-period').textContent = 'Periode: ' + data.period;
        document.getElementById('iuran-detail-fee-type').textContent = data.title;
        document.getElementById('iuran-detail-period-val').textContent = data.period;
        document.getElementById('iuran-detail-kk').textContent = data.nomor_kk;
        document.getElementById('iuran-detail-amount').textContent = data.amount_formatted;

        const statusBox = document.getElementById('iuran-detail-status-box');
        const formWrapper = document.getElementById('iuran-detail-pay-form-wrapper');
        const formBayar = document.getElementById('form-bayar');
        const inputJumlah = document.getElementById('modal-input-jumlah');

        if (data.status === 'lunas') {
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-emerald-50/70 border-emerald-200 text-emerald-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><span class="font-bold block text-emerald-950">Tagihan Telah Lunas Terverifikasi</span>Pembayaran tagihan iuran periode ini telah diverifikasi dan dicatat oleh Bendahara RT 15. Terima kasih atas partisipasi dan kepedulian keluarga Anda!</div>';
            formWrapper.classList.add('hidden');
        } else if (data.status === 'pending') {
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-blue-50/70 border-blue-200 text-blue-900';
            const info = data.pending_info;
            statusBox.innerHTML = '<svg class="w-5 h-5 text-primary-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><span class="font-bold block text-blue-950">Menunggu Validasi Bendahara</span>Bukti konfirmasi pembayaran (' + (info ? info.jumlah + ' via ' + info.metode : 'transfer') + ') telah terkirim dan sedang diverifikasi oleh Bendahara RT 15.</div>';
            formWrapper.classList.add('hidden');
        } else {
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-amber-50/70 border-amber-200 text-amber-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><span class="font-bold block text-amber-950">Tagihan Belum Lunas</span>Silakan lakukan pembayaran melalui transfer bank ke rekening Kas RT 15 atau serahkan tunai langsung ke Bendahara RT, lalu isi konfirmasi di bawah ini.</div>';
            
            formBayar.action = data.pay_url;
            inputJumlah.value = data.amount_raw;
            formWrapper.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeIuranDetailModal() {
        const modal = document.getElementById('modal-detail-iuran');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Kompatibilitas jika dipanggil dari tempat lain
    function openPayModal(invoiceId, title, amountFormatted, amountRaw) {
        openIuranDetailModal({
            id: invoiceId,
            title: title,
            period: '-',
            amount_formatted: amountFormatted,
            amount_raw: amountRaw,
            status: 'belum_lunas',
            status_label: 'Belum Lunas',
            due_date: '-',
            nomor_kk: '-',
            pay_url: `/warga/iuran/${invoiceId}/bayar`,
            pending_info: null
        });
    }

    function closePayModal() {
        closeIuranDetailModal();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal-detail-iuran');
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeIuranDetailModal();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeIuranDetailModal();
                }
            });
        }
    });
</script>
@endsection
