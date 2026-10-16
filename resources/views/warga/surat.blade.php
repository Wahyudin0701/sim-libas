@extends('layouts.citizen')

@section('title', 'Layanan Surat Pengantar | SIM-LiBas RT 15')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-primary-900">Layanan Surat Pengantar Online</h1>
    <p class="text-xs text-text-muted mt-1">Ajukan permohonan surat pengantar resmi RT 15 secara mandiri dari ponsel Anda.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Formulir Pengajuan Surat Baru -->
    <div class="lg:col-span-5">
        <div class="sim-card p-6 bg-white sticky top-24">
            <h2 class="text-base font-extrabold text-primary-900 mb-4 flex items-center gap-2">
                <span>✍️</span> Buat Pengajuan Baru
            </h2>

            <form action="{{ route('warga.surat.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-2">
                        Pilih Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select name="letter_type_id" required class="w-full bg-bg-soft border border-border-soft text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">-- Pilih Jenis Surat Pengantar --</option>
                        @foreach($letterTypes as $type)
                            <option value="{{ $type->id }}" {{ old('letter_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->nama }} ({{ $type->kode }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-2">
                        Warga Pemohon <span class="text-red-500">*</span>
                    </label>
                    <select name="resident_id" required class="w-full bg-bg-soft border border-border-soft text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @if($household && $household->residents->isNotEmpty())
                            @foreach($household->residents as $res)
                                <option value="{{ $res->id }}" {{ old('resident_id') == $res->id ? 'selected' : '' }}>
                                    {{ $res->nama }} ({{ ucwords(str_replace('_', ' ', $res->hubungan_keluarga)) }})
                                </option>
                            @endforeach
                        @elseif($resident)
                            <option value="{{ $resident->id }}">{{ $resident->nama }}</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-2">
                        Keperluan Pengajuan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" rows="3" required placeholder="Contoh: Pengurusan perpanjangan KTP elektronik di Kantor Kelurahan Pematang Sulur..." class="w-full bg-bg-soft border border-border-soft text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">{{ old('keperluan') }}</textarea>
                </div>

                <div class="bg-blue-50 p-3 rounded-xl text-[11px] text-blue-900 leading-relaxed">
                    ℹ️ Surat akan diverifikasi oleh Sekretaris dan ditandatangani digital oleh Ketua RT 15 lengkap dengan QR Code resmi.
                </div>

                <button type="submit" class="w-full sim-btn-primary py-3 text-sm font-bold shadow">
                    Kirim Permohonan Surat
                </button>
            </form>
        </div>
    </div>

    <!-- Riwayat & Pelacakan Status Surat -->
    <div class="lg:col-span-7">
        <div class="sim-card p-6 bg-white">
            <h2 class="text-base font-extrabold text-primary-900 mb-4 flex items-center justify-between">
                <span>📋 Riwayat Permohonan Keluarga</span>
                <span class="text-xs text-text-muted font-normal">{{ $letters->total() }} Berkas</span>
            </h2>

            @if($letters->isEmpty())
                <div class="p-8 text-center text-text-muted">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Belum ada riwayat permohonan surat pengantar.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($letters as $letter)
                        <div class="p-4 rounded-2xl bg-bg-soft border border-border-soft hover:border-primary-500 transition">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <span class="text-xs font-extrabold text-primary-900 block">
                                        {{ $letter->letterType?->nama ?? 'Surat Pengantar' }}
                                    </span>
                                    <span class="text-[11px] text-text-muted">
                                        Pemohon: <strong class="text-gray-800">{{ $letter->resident?->nama }}</strong> • Diajukan: {{ $letter->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase shrink-0
                                    {{ $letter->status === 'disetujui' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $letter->status === 'diajukan' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $letter->status === 'diperiksa' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $letter->status === 'ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($letter->status) }}
                                </span>
                            </div>

                            <p class="text-xs text-text-muted mb-3 bg-white p-2.5 rounded-lg border border-border-soft">
                                Keperluan: "{{ $letter->keperluan }}"
                            </p>

                            @if($letter->status === 'disetujui')
                                <div class="flex items-center justify-between text-xs pt-2 border-t border-border-soft">
                                    <span class="font-mono text-primary-900 font-bold">
                                        No: {{ $letter->nomor_surat }}
                                    </span>
                                    <a href="{{ route('public.verify-letter', $letter->qr_token ?? $letter->id) }}" target="_blank" class="text-green-700 hover:text-green-900 font-bold inline-flex items-center gap-1">
                                        🔍 Cek Validasi QR &gt;
                                    </a>
                                </div>
                            @elseif($letter->status === 'ditolak')
                                <div class="text-xs text-red-700 bg-red-50 p-2.5 rounded-lg">
                                    <strong>Alasan Penolakan:</strong> {{ $letter->alasan_tolak ?? 'Berkas tidak lengkap.' }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $letters->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
