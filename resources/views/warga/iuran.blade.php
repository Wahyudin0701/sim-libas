@extends('layouts.citizen')

@section('title', 'Tagihan & Iuran Warga | SIM-LiBas RT 15')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-primary-900">Tagihan & Iuran Lingkungan RT 15</h1>
    <p class="text-xs text-text-muted mt-1">Transparansi pembayaran iuran wajib RT, kebersihan, dan kas sosial warga.</p>
</div>

<div class="space-y-6">
    @forelse($invoices as $inv)
        <div class="sim-card p-6 bg-white {{ $inv->status === 'lunas' ? 'border-green-200' : 'border-amber-200' }}">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border-soft">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $inv->status === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $inv->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                        <span class="text-xs font-semibold text-text-muted">
                            Periode: {{ date('F', mktime(0, 0, 0, $inv->periode_bulan, 10)) }} {{ $inv->periode_tahun }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-primary-900">{{ $inv->feeType?->nama ?? 'Iuran Wajib RT' }}</h3>
                </div>

                <div class="text-right">
                    <span class="text-xs text-text-muted block">Total Tagihan</span>
                    <span class="text-2xl font-extrabold text-primary-900">
                        Rp {{ number_format($inv->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Bagian Pembayaran atau Bukti -->
            <div class="pt-4">
                @if($inv->status === 'lunas')
                    <div class="flex items-center gap-2 text-xs text-green-700 font-semibold bg-green-50 p-3 rounded-xl">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Pembayaran tagihan ini telah divalidasi oleh Bendahara RT 15. Terima kasih atas partisipasi Anda!
                    </div>
                @else
                    @php
                        $pendingPayment = $inv->payments->where('status', 'pending')->first();
                    @endphp

                    @if($pendingPayment)
                        <div class="bg-blue-50 border border-blue-200 text-blue-900 p-3.5 rounded-xl text-xs flex items-center justify-between">
                            <div>
                                <span class="font-bold block">Bukti Transfer Berhasil Dikirim!</span>
                                <span class="text-text-muted">Menunggu validasi bendahara RT (Rp {{ number_format($pendingPayment->jumlah, 0, ',', '.') }} via {{ strtoupper($pendingPayment->metode) }}).</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-blue-200 text-blue-800">
                                Diproses
                            </span>
                        </div>
                    @else
                        <!-- Form Upload Bukti Transfer -->
                        <div class="bg-bg-soft p-4 rounded-2xl">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-primary-900 mb-3">
                                Konfirmasi Pembayaran Tagihan
                            </h4>
                            <form action="{{ route('warga.iuran.pay', $inv->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                                @csrf
                                <input type="hidden" name="jumlah" value="{{ $inv->jumlah }}">
                                <div class="sm:col-span-3">
                                    <label class="block text-[11px] font-bold text-text-muted mb-1">Metode</label>
                                    <select name="metode" required class="w-full bg-white border border-border-soft text-xs px-3 py-2 rounded-xl">
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="tunai">Tunai ke Bendahara</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-5">
                                    <label class="block text-[11px] font-bold text-text-muted mb-1">Foto Bukti Transfer (Bila Transfer)</label>
                                    <input type="file" name="bukti" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-primary-700 hover:file:bg-blue-100">
                                </div>
                                <div class="sm:col-span-4">
                                    <button type="submit" class="w-full sim-btn-primary text-xs py-2.5 shadow">
                                        Kirim Konfirmasi Bayar
                                    </button>
                                </div>
                            </form>
                            <div class="mt-3 text-[11px] text-text-muted">
                                🏦 Rekening Kas RT 15: <strong>Bank Jambi (900-15-1515)</strong> a.n. Kas RT 15 Pematang Sulur
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <div class="sim-card p-12 text-center text-text-muted">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-lg font-bold text-primary-900 mb-1">Tidak Ada Tagihan Aktif</h3>
            <p class="text-xs">Keluarga Anda tidak memiliki tunggakan iuran saat ini.</p>
        </div>
    @endforelse
</div>
@endsection
