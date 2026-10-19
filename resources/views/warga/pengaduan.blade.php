@extends('layouts.citizen')

@section('title', 'Pengaduan & Aspirasi Warga | SIM-LiBas RT 15')

@section('content')
<!-- Header Konten: Judul Riwayat & Tombol Tambah Pengaduan -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-primary-900 tracking-tight">
            Riwayat Pengaduan Lingkungan
        </h1>
        <p class="text-xs text-text-muted mt-0.5">
            Daftar laporan fasilitas dan aspirasi warga RT 15 Pematang Sulur
        </p>
    </div>

    <!-- Tombol Tambah Pengaduan (Buka Popup Modal) -->
    <button type="button" 
            onclick="openPengaduanModal()" 
            class="sim-btn-primary text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 cursor-pointer active:scale-95 transition shrink-0 self-start sm:self-auto">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tulis Laporan Baru</span>
    </button>
</div>

<!-- Tampilan Riwayat Pengaduan (Tanpa Card Pembungkus) -->
<div class="mb-4 flex items-center justify-between">
    <span class="text-xs font-bold text-text-muted uppercase tracking-wider">Daftar Laporan Pengaduan</span>
    <span class="text-xs text-text-muted font-semibold bg-white px-2.5 py-1 rounded-full border border-slate-200 shadow-2xs">
        {{ $complaints->total() }} Laporan
    </span>
</div>

    @if($complaints->isEmpty())
        <div class="py-16 px-6 text-center text-text-muted">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3 border border-amber-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-primary-900">Belum Ada Riwayat Pengaduan</p>
            <p class="text-xs text-text-muted mt-1 max-w-sm mx-auto">
                Anda belum pernah mengirim laporan fasilitas atau keluhan lingkungan.
            </p>
            <button type="button" 
                    onclick="openPengaduanModal()" 
                    class="mt-4 sim-btn-primary text-xs font-bold px-4 py-2 rounded-xl shadow-xs">
                + Tulis Laporan Pertama
            </button>
        </div>
    @else
        <div class="space-y-3">
            @foreach($complaints as $item)
                @php
                    $statusLabel = match($item->status) {
                        'selesai' => 'Selesai Ditangani',
                        'diproses' => 'Sedang Diproses',
                        'diterima' => 'Laporan Diterima',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($item->status),
                    };

                    $kategoriLabels = [
                        'infrastruktur' => 'Infrastruktur',
                        'kebersihan' => 'Kebersihan & Sampah',
                        'keamanan' => 'Keamanan & Ketertiban',
                        'kebisingan' => 'Kebisingan & Hewan',
                        'lainnya' => 'Aspirasi Umum / Lainnya',
                    ];

                    $complaintData = [
                        'id' => $item->id,
                        'judul' => $item->judul,
                        'kategori' => $kategoriLabels[$item->kategori] ?? ucfirst($item->kategori),
                        'kategori_raw' => $item->kategori,
                        'deskripsi' => $item->deskripsi,
                        'lokasi' => $item->lokasi ?? '-',
                        'is_anonim' => (bool) $item->is_anonim,
                        'status' => $item->status,
                        'status_label' => $statusLabel,
                        'tanggal' => $item->created_at->format('d M Y, H:i') . ' WIB',
                        'tanggal_short' => $item->created_at->format('d M Y'),
                        'catatan_penanganan' => $item->catatan_penanganan ?? '',
                    ];
                @endphp

                <div onclick='openPengaduanDetailModal(@json($complaintData))' 
                     class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:border-primary-400 hover:shadow-md transition-all duration-200 p-4 sm:p-5 flex items-center justify-between gap-4 cursor-pointer group active:scale-[0.99]">
                    <!-- Kiri: Info Esensial -->
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-primary-700 transition truncate">
                                {{ $item->judul }}
                            </h3>
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md uppercase shrink-0">
                                {{ $item->kategori }}
                            </span>
                            @if($item->is_anonim)
                                <span class="text-[10px] font-bold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-md uppercase inline-flex items-center gap-1 shrink-0">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Anonim
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 truncate">
                            <span class="shrink-0">{{ $item->created_at->format('d M Y') }}</span>
                            @if($item->lokasi)
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-600 truncate">Lokasi: {{ $item->lokasi }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Kanan: Status Badge & Chevron -->
                    <div class="flex items-center gap-3 shrink-0">
                        @if($item->status === 'selesai')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Selesai</span>
                            </span>
                        @elseif($item->status === 'diproses')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-primary-700 border border-blue-200">
                                <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                                <span>Diproses</span>
                            </span>
                        @elseif($item->status === 'diterima')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>Diterima</span>
                            </span>
                        @elseif($item->status === 'ditolak')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span>Ditolak</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ ucfirst($item->status) }}
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
            {{ $complaints->links() }}
        </div>
    @endif

<!-- POPUP MODAL DETAIL RIWAYAT PENGADUAN -->
<div id="modal-detail-pengaduan" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop-blur bg-slate-900/50 backdrop-blur-md transition-all duration-200" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl shadow-2xl border border-border-soft max-w-lg w-full max-h-[90vh] overflow-y-auto no-scrollbar transform transition-all" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="p-6 pb-4 border-b border-border-soft flex items-center justify-between">
            <div>
                <h3 id="pengaduan-detail-title" class="text-base font-extrabold text-primary-900 leading-snug"></h3>
                <p id="pengaduan-detail-date" class="text-xs text-text-muted mt-0.5"></p>
            </div>
            <button type="button" onclick="closePengaduanDetailModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <!-- Status Alert / Banner -->
            <div id="pengaduan-detail-status-box" class="p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3"></div>

            <!-- Detail Grid -->
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-3 text-xs">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Kategori Pengaduan:</span>
                    <span id="pengaduan-detail-kategori" class="font-bold text-slate-800 text-right"></span>
                </div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Lokasi Kejadian:</span>
                    <span id="pengaduan-detail-lokasi" class="font-bold text-slate-800 text-right"></span>
                </div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Sifat Pelaporan:</span>
                    <span id="pengaduan-detail-anonim" class="font-bold text-slate-800 text-right"></span>
                </div>
                <div>
                    <span class="text-slate-500 font-medium block mb-1">Deskripsi Lengkap Permasalahan:</span>
                    <p id="pengaduan-detail-deskripsi" class="text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 leading-relaxed font-medium whitespace-pre-line"></p>
                </div>
            </div>

            <!-- Tindak Lanjut Pengurus RT Box -->
            <div id="pengaduan-detail-tindaklanjut-box" class="hidden p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 text-xs">
                <div class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <span class="font-bold block text-emerald-950 mb-0.5">Catatan Tindak Lanjut Pengurus RT:</span>
                        <p id="pengaduan-detail-catatan" class="text-emerald-900 leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 bg-slate-50 border-t border-border-soft flex items-center justify-end rounded-b-3xl">
            <button type="button" onclick="closePengaduanDetailModal()" class="px-5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- POPUP MODAL FORM PENGADUAN BARU -->
<div id="modal-pengaduan" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 modal-backdrop-blur bg-slate-900/50 backdrop-blur-md transition-all duration-200" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl shadow-2xl border border-border-soft max-w-lg w-full max-h-[90vh] overflow-y-auto no-scrollbar transform transition-all" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="p-5 sm:p-6 pb-3 sm:pb-4 border-b border-border-soft flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-primary-900">Tulis Laporan Pengaduan Baru</h3>
                <p class="text-xs text-text-muted mt-0.5">Sampaikan aspirasi atau keluhan fasilitas lingkungan RT 15</p>
            </div>
            <button type="button" onclick="closePengaduanModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Form Body -->
        <form action="{{ route('warga.pengaduan.store') }}" method="POST" class="p-5 sm:p-6 space-y-3.5">
            @csrf

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <span class="font-bold block text-rose-950 mb-0.5">Mohon lengkapi laporan Anda:</span>
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
                    Judul Laporan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" required placeholder="Contoh: Lampu jalan depan gang mawar padam..." value="{{ old('judul') }}" class="w-full bg-bg-soft border @error('judul') border-rose-400 ring-1 ring-rose-400 @else border-border-soft @enderror text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('judul')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Kategori Pengaduan <span class="text-red-500">*</span>
                </label>
                <select name="kategori" required class="w-full bg-bg-soft border @error('kategori') border-rose-400 ring-1 ring-rose-400 @else border-border-soft @enderror text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="infrastruktur" {{ old('kategori') === 'infrastruktur' ? 'selected' : '' }}>Infrastruktur (Jalan / Lampu / Drainase)</option>
                    <option value="kebersihan" {{ old('kategori') === 'kebersihan' ? 'selected' : '' }}>Kebersihan & Sampah Liar</option>
                    <option value="keamanan" {{ old('kategori') === 'keamanan' ? 'selected' : '' }}>Keamanan & Ketertiban Lingkungan</option>
                    <option value="kebisingan" {{ old('kategori') === 'kebisingan' ? 'selected' : '' }}>Kebisingan / Hewan Peliharaan</option>
                    <option value="lainnya" {{ old('kategori') === 'lainnya' ? 'selected' : '' }}>Aspirasi Umum / Lainnya</option>
                </select>
                @error('kategori')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Lokasi Kejadian
                </label>
                <input type="text" name="lokasi" placeholder="Contoh: Depan Rumah No. 12 Blok B" value="{{ old('lokasi') }}" class="w-full bg-bg-soft border border-border-soft text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-1.5">
                    Deskripsi Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" rows="2" required placeholder="Jelaskan detail permasalahan yang Anda amati..." class="w-full bg-bg-soft border @error('deskripsi') border-rose-400 ring-1 ring-rose-400 @else border-border-soft @enderror text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 min-h-[60px]">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-3 bg-bg-soft rounded-xl border border-border-soft">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_anonim" value="1" {{ old('is_anonim') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-700 focus:ring-primary-500">
                    <span class="text-xs font-semibold text-primary-900">Kirim sebagai Warga Anonim</span>
                </label>
                <p class="text-[10px] text-text-muted mt-0.5 ml-5">
                    Identitas nama Anda tidak akan ditampilkan kepada warga lain.
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="pt-2 border-t border-border-soft flex items-center justify-end gap-3">
                <button type="button" onclick="closePengaduanModal()" class="px-4 py-2 rounded-xl border border-border-soft text-text-muted hover:bg-slate-50 text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="sim-btn-primary px-5 py-2 text-xs font-bold shadow cursor-pointer">
                    Kirim Laporan Pengaduan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPengaduanModal() {
        const modal = document.getElementById('modal-pengaduan');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closePengaduanModal() {
        const modal = document.getElementById('modal-pengaduan');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openPengaduanDetailModal(data) {
        const modal = document.getElementById('modal-detail-pengaduan');
        if (!modal) return;

        document.getElementById('pengaduan-detail-title').textContent = data.judul;
        document.getElementById('pengaduan-detail-date').textContent = 'Dikirim pada ' + data.tanggal;
        document.getElementById('pengaduan-detail-kategori').textContent = data.kategori;
        document.getElementById('pengaduan-detail-lokasi').textContent = data.lokasi || '-';
        document.getElementById('pengaduan-detail-anonim').textContent = data.is_anonim ? 'Anonim (Nama Dirahasiakan)' : 'Warga Terdaftar';
        document.getElementById('pengaduan-detail-deskripsi').textContent = data.deskripsi;

        const statusBox = document.getElementById('pengaduan-detail-status-box');
        const tindakLanjutBox = document.getElementById('pengaduan-detail-tindaklanjut-box');
        const catatanEl = document.getElementById('pengaduan-detail-catatan');

        if (data.status === 'selesai') {
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-emerald-50/70 border-emerald-200 text-emerald-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><span class="font-bold block text-emerald-950">Laporan Selesai Ditangani</span>Laporan kendala atau aspirasi lingkungan telah ditindaklanjuti dan diselesaikan tuntas oleh pengurus RT 15.</div>';
        } else if (data.status === 'diproses') {
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-blue-50/70 border-blue-200 text-blue-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-primary-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><span class="font-bold block text-blue-950">Sedang Ditindaklanjuti</span>Laporan Anda saat ini sedang dikoordinasikan dan ditangani aktif oleh pengurus lingkungan RT 15 di lapangan.</div>';
        } else if (data.status === 'diterima') {
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-amber-50/70 border-amber-200 text-amber-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><span class="font-bold block text-amber-950">Laporan Diterima Sistem</span>Laporan pengaduan telah tersimpan dan sedang menunggu tinjauan/disposisi dari koordinator bidang terkait.</div>';
        } else if (data.status === 'ditolak') {
            statusBox.className = 'p-4 rounded-2xl border text-xs leading-relaxed flex items-start gap-3 bg-rose-50/80 border-rose-200 text-rose-900';
            statusBox.innerHTML = '<svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><div><span class="font-bold block text-rose-950">Laporan Belum Dapat Diproses</span>' + (data.catatan_penanganan || 'Informasi laporan belum memenuhi kriteria penanganan RT.') + '</div>';
        }

        if (data.catatan_penanganan && data.status !== 'ditolak') {
            tindakLanjutBox.classList.remove('hidden');
            catatanEl.textContent = data.catatan_penanganan;
        } else {
            tindakLanjutBox.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closePengaduanDetailModal() {
        const modal = document.getElementById('modal-detail-pengaduan');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modalCreate = document.getElementById('modal-pengaduan');
        const modalDetail = document.getElementById('modal-detail-pengaduan');

        [modalCreate, modalDetail].forEach(modal => {
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (modalCreate && !modalCreate.classList.contains('hidden')) {
                    closePengaduanModal();
                }
                if (modalDetail && !modalDetail.classList.contains('hidden')) {
                    closePengaduanDetailModal();
                }
            }
        });

        @if($errors->any() || request()->query('action') === 'create')
            openPengaduanModal();
        @endif
    });
</script>
@endsection
