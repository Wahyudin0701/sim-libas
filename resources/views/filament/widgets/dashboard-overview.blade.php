<x-filament-widgets::widget>
    {{-- 1. Main Unified Container (Adapted from IT All Role) --}}
    <div class="bg-white rounded-2xl border border-gray-200/90 p-6 lg:p-8 shadow-xs mb-6">
        {{-- Greeting Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-gray-100">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#0B3A85]">
                    SISTEM INFORMASI MANAJEMEN RT 15 &bull; {{ $roleLabel }}
                </p>
                <div class="flex items-center gap-2.5 mt-1">
                    <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-gray-900">
                        {{ $greeting }}, {{ $user?->name ?? 'Pengurus' }}
                    </h1>
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold ring-1 ring-blue-200/60" title="Akun Pengurus Aktif">
                        &#10003;
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    Berikut ringkasan operasional administrasi, keuangan, dan layanan warga RT 15 Pematang Sulur secara keseluruhan.
                </p>
            </div>
            
            <div class="hidden sm:flex items-center gap-3 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Aktif
                </span>
                <span class="text-xs text-gray-400 font-medium">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>

        {{-- Stat Cards Row (Pastel Tiles inside Container - Adapted from IT All Role) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5 lg:gap-4 mt-6">
            {{-- Card 1: Total Kependudukan --}}
            <a href="{{ url('/admin/residents') }}" class="group block p-4 rounded-xl bg-[#EDF9F1] border border-emerald-200/70 hover:border-emerald-400 hover:shadow-xs transition duration-150 text-center">
                <div class="text-2xl lg:text-3xl font-extrabold text-[#1B7A3A] tracking-tight">
                    {{ $totalWarga }}
                </div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-600 mt-2">
                    Total Kependudukan
                </div>
                <div class="text-[11px] font-semibold text-emerald-700 mt-0.5">
                    {{ $totalWarga }} Jiwa / {{ $totalKk }} KK
                </div>
            </a>

            {{-- Card 2: Surat Berjalan --}}
            <a href="{{ url('/admin/letters') }}" class="group block p-4 rounded-xl {{ $suratBerjalan > 0 ? 'bg-[#FFFBEB] border-amber-200/70 hover:border-amber-400' : 'bg-[#EBF3FC] border-blue-200/70 hover:border-blue-400' }} border hover:shadow-xs transition duration-150 text-center">
                <div class="text-2xl lg:text-3xl font-extrabold {{ $suratBerjalan > 0 ? 'text-[#D97706]' : 'text-[#0B3A85]' }} tracking-tight">
                    {{ $suratBerjalan }}
                </div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-600 mt-2">
                    Surat Berjalan
                </div>
                <div class="text-[11px] font-semibold {{ $suratBerjalan > 0 ? 'text-amber-700' : 'text-blue-700' }} mt-0.5">
                    {{ $suratBerjalan > 0 ? 'Menunggu Verifikasi' : 'Semua Berkas Selesai' }}
                </div>
            </a>

            {{-- Card 3: Saldo Kas RT 15 --}}
            <a href="{{ url('/admin/cash-transactions') }}" class="group block p-4 rounded-xl bg-[#EDF9F1] border border-emerald-200/70 hover:border-emerald-400 hover:shadow-xs transition duration-150 text-center">
                <div class="text-lg lg:text-xl font-extrabold text-[#1B7A3A] tracking-tight truncate" title="Rp {{ number_format($saldoKas, 0, ',', '.') }}">
                    Rp {{ number_format($saldoKas, 0, ',', '.') }}
                </div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-600 mt-2">
                    Saldo Kas RT 15
                </div>
                <div class="text-[11px] font-semibold text-emerald-700 mt-0.5">
                    Kas Aktif RT
                </div>
            </a>

            {{-- Card 4: Tagihan Tertunda --}}
            <a href="{{ url('/admin/invoices') }}" class="group block p-4 rounded-xl {{ $tagihanTertunda > 0 ? 'bg-[#FFFBEB] border-amber-200/70 hover:border-amber-400' : 'bg-[#EBF3FC] border-blue-200/70 hover:border-blue-400' }} border hover:shadow-xs transition duration-150 text-center">
                <div class="text-lg lg:text-xl font-extrabold {{ $tagihanTertunda > 0 ? 'text-[#D97706]' : 'text-[#0B3A85]' }} tracking-tight truncate" title="Rp {{ number_format($tagihanTertunda, 0, ',', '.') }}">
                    Rp {{ number_format($tagihanTertunda, 0, ',', '.') }}
                </div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-600 mt-2">
                    Tagihan Iuran Tertunda
                </div>
                <div class="text-[11px] font-semibold {{ $tagihanTertunda > 0 ? 'text-amber-700' : 'text-blue-700' }} mt-0.5">
                    {{ $tagihanTertunda > 0 ? 'Belum Lunas' : 'Semua Lunas' }}
                </div>
            </a>

            {{-- Card 5: Pengaduan Warga --}}
            <a href="{{ url('/admin/complaints') }}" class="group block p-4 rounded-xl {{ $pengaduanTerbuka > 0 ? 'bg-[#FEF2F2] border-rose-200/70 hover:border-rose-400' : 'bg-[#EDF9F1] border-emerald-200/70 hover:border-emerald-400' }} border hover:shadow-xs transition duration-150 text-center">
                <div class="text-2xl lg:text-3xl font-extrabold {{ $pengaduanTerbuka > 0 ? 'text-[#DC2626]' : 'text-[#1B7A3A]' }} tracking-tight">
                    {{ $pengaduanTerbuka }}
                </div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-600 mt-2">
                    Pengaduan Warga
                </div>
                <div class="text-[11px] font-semibold {{ $pengaduanTerbuka > 0 ? 'text-rose-700' : 'text-emerald-700' }} mt-0.5">
                    {{ $pengaduanTerbuka > 0 ? 'Perlu Ditindaklanjuti' : 'Lingkungan Kondusif' }}
                </div>
            </a>

            {{-- Card 6: Bank Sampah Lestari --}}
            <a href="{{ url('/admin/waste-deposits') }}" class="group block p-4 rounded-xl bg-[#ECFDF5] border border-teal-200/70 hover:border-teal-400 hover:shadow-xs transition duration-150 text-center">
                <div class="text-2xl lg:text-3xl font-extrabold text-[#059669] tracking-tight">
                    {{ number_format($totalSampahKg, 1, ',', '.') }} <span class="text-xs font-semibold">Kg</span>
                </div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-600 mt-2">
                    Bank Sampah Lestari
                </div>
                <div class="text-[11px] font-semibold text-teal-700 mt-0.5">
                    Sampah Terpilah
                </div>
            </a>
        </div>
    </div>

    {{-- 2. Activity Logs Grid (Adapted from IT All Role bottom cards) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Card Left: Surat Pengantar Terkini --}}
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 lg:p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Surat Pengantar Terkini</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Permohonan dokumen dan surat pengantar dari warga</p>
                    </div>
                    <a href="{{ url('/admin/letters') }}" class="text-xs font-semibold text-[#0B3A85] hover:text-[#0B2F6B] transition flex items-center gap-1">
                        Lihat Semua &gt;
                    </a>
                </div>

                <div class="divide-y divide-gray-100 mt-2">
                    @forelse ($recentLetters as $letter)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-[#0B3A85] font-bold flex items-center justify-center shrink-0 text-sm">
                                    {{ strtoupper(substr($letter->resident?->nama_lengkap ?? 'W', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $letter->letterType?->nama_surat ?? 'Surat Pengantar' }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $letter->resident?->nama_lengkap ?? 'Warga RT 15' }} &bull; {{ $letter->created_at?->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold shrink-0 {{ match($letter->status) { 'selesai' => 'bg-emerald-50 text-emerald-700', 'ditolak' => 'bg-rose-50 text-rose-700', 'diperiksa' => 'bg-blue-50 text-blue-700', default => 'bg-amber-50 text-amber-700' } }}">
                                {{ ucfirst($letter->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-gray-400">
                            Belum ada permohonan surat masuk.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Card Right: Pengaduan Warga Terkini --}}
        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 lg:p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Pengaduan Warga Terkini</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Aspirasi dan laporan masalah lingkungan dari warga</p>
                    </div>
                    <a href="{{ url('/admin/complaints') }}" class="text-xs font-semibold text-[#0B3A85] hover:text-[#0B2F6B] transition flex items-center gap-1">
                        Lihat Semua &gt;
                    </a>
                </div>

                <div class="divide-y divide-gray-100 mt-2">
                    @forelse ($recentComplaints as $complaint)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-rose-50 text-rose-700 font-bold flex items-center justify-center shrink-0 text-sm">
                                    P
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $complaint->judul }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $complaint->is_anonim ? 'Warga Anonim' : ($complaint->user?->name ?? 'Warga') }} &bull; {{ ucfirst($complaint->kategori) }} &bull; {{ $complaint->created_at?->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold shrink-0 {{ match($complaint->status) { 'selesai' => 'bg-emerald-50 text-emerald-700', 'diproses' => 'bg-blue-50 text-blue-700', 'ditolak' => 'bg-rose-50 text-rose-700', default => 'bg-amber-50 text-amber-700' } }}">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-gray-400">
                            Tidak ada pengaduan aktif. Lingkungan aman dan kondusif.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
