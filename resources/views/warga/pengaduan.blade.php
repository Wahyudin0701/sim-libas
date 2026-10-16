@extends('layouts.citizen')

@section('title', 'Pengaduan & Aspirasi Warga | SIM-LiBas RT 15')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-primary-900">Pengaduan & Aspirasi Lingkungan</h1>
    <p class="text-xs text-text-muted mt-1">Sampaikan laporan fasilitas umum rusak, keluhan kebersihan, atau aspirasi warga secara transparan.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Formulir Lapor Pengaduan -->
    <div class="lg:col-span-5">
        <div class="sim-card p-6 bg-white sticky top-24">
            <h2 class="text-base font-extrabold text-primary-900 mb-4 flex items-center gap-2">
                <span>📢</span> Tulis Laporan Baru
            </h2>

            <form action="{{ route('warga.pengaduan.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-2">
                        Judul Laporan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" required placeholder="Contoh: Lampu jalan depan gang mawar padam..." class="w-full bg-bg-soft border border-border-soft text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-2">
                        Kategori Pengaduan <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" required class="w-full bg-bg-soft border border-border-soft text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="infrastruktur">Infrastruktur (Jalan / Lampu / Drainase)</option>
                        <option value="kebersihan">Kebersihan & Sampah Liar</option>
                        <option value="keamanan">Keamanan & Ketertiban Lingkungan</option>
                        <option value="kebisingan">Kebisingan / Hewan Peliharaan</option>
                        <option value="lainnya">Aspirasi Umum / Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-2">
                        Lokasi Kejadian
                    </label>
                    <input type="text" name="lokasi" placeholder="Contoh: Depan Rumah No. 12 Blok B" class="w-full bg-bg-soft border border-border-soft text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-primary-900 uppercase tracking-wider mb-2">
                        Deskripsi Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" rows="3" required placeholder="Jelaskan detail permasalahan yang Anda amati..." class="w-full bg-bg-soft border border-border-soft text-sm px-3.5 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                </div>

                <div class="p-3 bg-bg-soft rounded-xl">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_anonim" value="1" class="rounded border-gray-300 text-primary-700 focus:ring-primary-500">
                        <span class="text-xs font-semibold text-primary-900">🔒 Kirim sebagai Warga Anonim</span>
                    </label>
                    <p class="text-[10px] text-text-muted mt-1 ml-5">
                        Identitas nama Anda tidak akan ditampilkan kepada warga lain.
                    </p>
                </div>

                <button type="submit" class="w-full sim-btn-primary py-3 text-sm font-bold shadow">
                    Kirim Laporan Pengaduan
                </button>
            </form>
        </div>
    </div>

    <!-- Riwayat Pengaduan Warga -->
    <div class="lg:col-span-7">
        <div class="sim-card p-6 bg-white">
            <h2 class="text-base font-extrabold text-primary-900 mb-4 flex items-center justify-between">
                <span>📋 Laporan Anda Sebelumnya</span>
                <span class="text-xs text-text-muted font-normal">{{ $complaints->total() }} Laporan</span>
            </h2>

            @if($complaints->isEmpty())
                <div class="p-8 text-center text-text-muted">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Anda belum pernah membuat laporan pengaduan.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($complaints as $item)
                        <div class="p-4 rounded-2xl bg-bg-soft border border-border-soft">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-primary-700">
                                            {{ $item->kategori }}
                                        </span>
                                        @if($item->is_anonim)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-gray-200 text-gray-700">
                                                🔒 Anonim
                                            </span>
                                        @endif
                                        <span class="text-[11px] text-text-muted">{{ $item->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <h3 class="text-base font-bold text-primary-900">{{ $item->judul }}</h3>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase shrink-0
                                    {{ $item->status === 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $item->status === 'diproses' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $item->status === 'diterima' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $item->status === 'ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>

                            <p class="text-xs text-text-muted mb-3 bg-white p-3 rounded-xl border border-border-soft leading-relaxed">
                                "{{ $item->deskripsi }}"
                            </p>

                            @if($item->status === 'selesai' && $item->catatan_penanganan)
                                <div class="bg-green-50 border border-green-200 text-green-900 p-3 rounded-xl text-xs">
                                    <span class="font-bold block mb-0.5">Tindak Lanjut Pengurus:</span>
                                    <span>{{ $item->catatan_penanganan }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $complaints->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
