<x-filament-widgets::widget>
    {{-- ====================================================================
         1. SAMBUTAN PENGURUS (IDENTITAS VISUAL HOMEPAGE SIM-LIBAS - TANPA CARD)
         ==================================================================== --}}
    <div class="sim-welcome-header mb-4 lg:mb-5 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-3 sm:gap-4">
        <div>
            {{-- Role Pengurus & Panduan Dashboard --}}
            <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#1B7A3A] mb-0.5">
                <span>{{ $roleLabel }}</span>
                <x-page-guide 
                    title="Dashboard Utama SIM-LiBas"
                    category="PANEL PENGURUS RT 15"
                    description="Pusat kendali dan ringkasan eksekutif operasional RT 15. Menampilkan indikator kinerja utama (KPI), status kependudukan, keuangan kas, laporan keamanan, dan antrean layanan warga terkini."
                    :steps="[
                        ['title' => 'Pantau Indikator Utama (KPI)', 'desc' => 'Perhatikan ringkasan kartu statistik di bagian atas untuk melihat total warga, saldo kas, tagihan tertunda, dan aduan aktif.'],
                        ['title' => 'Gunakan Tombol Aksi Cepat', 'desc' => 'Manfaatkan tombol shortcut di pojok kanan atas untuk langsung menuju modul operasional harian sesuai peran Anda.'],
                        ['title' => 'Tindak Lanjuti Antrean Layanan', 'desc' => 'Periksa daftar pengajuan surat, konfirmasi pembayaran, dan laporan tamu yang memerlukan persetujuan segera.'],
                        ['title' => 'Navigasi Menu Sidebar', 'desc' => 'Gunakan bilah menu di sebelah kiri untuk mengakses modul lengkap sesuai kewenangan dan peran pengurus Anda.']
                    ]"
                    role="{{ $roleLabel }}"
                    :tips="[
                        'Informasi pada dashboard diperbarui secara real-time berdasarkan aktivitas warga di portal.',
                        'Setiap peran pengurus memiliki tampilan ringkasan dan tombol aksi yang disesuaikan dengan tanggung jawabnya.',
                        'Gunakan ikon tanda seru (!) di setiap halaman menu untuk melihat panduan khusus modul tersebut.'
                    ]"
                />
            </div>

            {{-- Sapaan Personal & Nama --}}
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F1F3D] tracking-tight leading-tight">
                {{ $greeting }}, {{ $user?->name ?? 'Pengurus RT 15' }}
            </h1>

            {{-- Deskripsi Singkat Operasional Lingkungan Sesuai Role --}}
            <p class="text-xs sm:text-[13px] text-[#5B6B82] mt-0.5 leading-relaxed">
                @if ($isKoordinatorBankSampah)
                    Panel operasional penimbangan, setoran, dan nasabah Bank Sampah Pinang Masak Lestari.
                @elseif ($isKoordinatorKeamanan)
                    Panel pengawasan keamanan lingkungan, poskamling, dan pencatatan tamu RT 15.
                @elseif ($isBendahara)
                    Panel pembukuan kas, penerbitan tagihan, dan rekonsiliasi iuran RT 15.
                @elseif ($isSekretaris)
                    Panel tata kelola administrasi surat, kependudukan, dan agenda warga RT 15.
                @else
                    Panel kendali pelayanan warga dan tata kelola lingkungan RT 15.
                @endif
            </p>
        </div>

        {{-- Kolom Kanan: Tombol Aksi Cepat Sesuai Hak Akses Role --}}
        <div class="flex items-center gap-2 shrink-0 pt-1 lg:pt-0">
            @if ($isKoordinatorBankSampah)
                <a href="{{ url('/admin/waste-types') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#334A6E] border border-[#CBD5E1] hover:bg-[#EBF3FC] hover:text-[#0B3A85] hover:border-[#0B3A85] transition-all duration-150 shadow-2xs hover:shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    <span>Master Harga Sampah</span>
                </a>
                <a href="{{ url('/admin/waste-deposits/create') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-[#0B3A85] text-white hover:bg-[#07275E] hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Setoran Sampah</span>
                </a>
            @elseif ($isKoordinatorKeamanan)
                <a href="{{ url('/admin/guest-reports') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#334A6E] border border-[#CBD5E1] hover:bg-[#EBF3FC] hover:text-[#0B3A85] hover:border-[#0B3A85] transition-all duration-150 shadow-2xs hover:shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>Lapor Tamu</span>
                </a>
                <a href="{{ url('/admin/patrol-schedules/create') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-[#0B3A85] text-white hover:bg-[#07275E] hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Jadwal Ronda Baru</span>
                </a>
            @elseif ($isBendahara)
                <a href="{{ url('/admin/invoices') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#334A6E] border border-[#CBD5E1] hover:bg-[#EBF3FC] hover:text-[#0B3A85] hover:border-[#0B3A85] transition-all duration-150 shadow-2xs hover:shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                    <span>Tagihan Iuran</span>
                </a>
                <a href="{{ url('/admin/cash-transactions/create') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-[#0B3A85] text-white hover:bg-[#07275E] hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Kas RT</span>
                </a>
            @elseif ($isSekretaris)
                <a href="{{ url('/admin/announcements/create') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#334A6E] border border-[#CBD5E1] hover:bg-[#EBF3FC] hover:text-[#0B3A85] hover:border-[#0B3A85] transition-all duration-150 shadow-2xs hover:shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>Buat Pengumuman</span>
                </a>
                <a href="{{ url('/admin/letters') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-[#0B3A85] text-white hover:bg-[#07275E] hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Verifikasi Surat</span>
                </a>
            @else
                <a href="{{ url('/admin/letters') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white text-[#334A6E] border border-[#CBD5E1] hover:bg-[#EBF3FC] hover:text-[#0B3A85] hover:border-[#0B3A85] transition-all duration-150 shadow-2xs hover:shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Verifikasi Surat</span>
                </a>
                <a href="{{ url('/admin/cash-transactions/create') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-[#0B3A85] text-white hover:bg-[#07275E] hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Kas RT</span>
                </a>
            @endif
        </div>
    </div>

    {{-- ====================================================================
         2. KARTU STATISTIK UTAMA (MENAMPILKAN SESUAI ROLE & HAK AKSES)
         ==================================================================== --}}
    @if ($isKoordinatorBankSampah)
        {{-- Tampilan Kartu Khusus Koordinator Bank Sampah --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-3.5 mb-5 lg:mb-6">
            {{-- Card 1: Total Sampah --}}
            <a href="{{ url('/admin/waste-deposits') }}" title="Total Sampah Terkumpul: {{ number_format($totalSampahKg, 1, ',', '.') }} Kg" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#FFF9EA] border border-[#FDEBBD] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-xl sm:text-2xl font-extrabold text-[#D97706] tracking-tight">
                        {{ (float)$totalSampahKg == (int)$totalSampahKg ? number_format($totalSampahKg, 0, ',', '.') : number_format($totalSampahKg, 1, ',', '.') }} <span class="text-[11px] sm:text-xs font-bold opacity-75">Kg</span>
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Total Sampah
                    </span>
                </div>
            </a>

            {{-- Card 2: Total Transaksi Setoran --}}
            <a href="{{ url('/admin/waste-deposits') }}" title="Total Transaksi Setoran: {{ $totalTransaksiSampah }} Transaksi" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EAF5EE] border border-[#D2EBD9] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#166534] tracking-tight">
                        {{ $totalTransaksiSampah }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Total Setoran
                    </span>
                </div>
            </a>

            {{-- Card 3: Jenis Sampah Aktif --}}
            <a href="{{ url('/admin/waste-types') }}" title="Jenis Sampah Diterima: {{ $jenisSampahCount }} Kategori" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EEF5FF] border border-[#D6E5FF] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#2563EB] tracking-tight">
                        {{ $jenisSampahCount }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Kategori Sampah
                    </span>
                </div>
            </a>

            {{-- Card 4: Nilai Perputaran Tabungan --}}
            <a href="{{ url('/admin/waste-deposits') }}" title="Nilai Tabungan Sampah Warga: Rp {{ number_format($totalNilaiSampahRp, 0, ',', '.') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#ECFDF5] border border-[#D1FAE5] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200 px-2">
                    <span class="text-sm sm:text-base lg:text-sm xl:text-base font-extrabold text-[#059669] tracking-tight truncate">
                        Rp {{ number_format($totalNilaiSampahRp, 0, ',', '.') }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Tabungan Sampah
                    </span>
                </div>
            </a>
        </div>
    @elseif ($isKoordinatorKeamanan)
        {{-- Tampilan Kartu Khusus Koordinator Keamanan --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-3.5 mb-5 lg:mb-6">
            <a href="{{ url('/admin/patrol-schedules') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EEF5FF] border border-[#D6E5FF] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#2563EB] tracking-tight">
                        {{ $totalJadwalRonda }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Jadwal Ronda
                    </span>
                </div>
            </a>

            <a href="{{ url('/admin/guest-reports') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EAF5EE] border border-[#D2EBD9] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#166534] tracking-tight">
                        {{ $totalLaporTamu }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Lapor Tamu
                    </span>
                </div>
            </a>

            <a href="{{ url('/admin/patrol-schedules') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#ECFDF5] border border-[#D1FAE5] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-base sm:text-lg font-extrabold text-[#059669] tracking-tight">
                        Kondusif
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Status Wilayah
                    </span>
                </div>
            </a>

            <a href="{{ url('/admin/complaints') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#FEF2F2] border border-[#FEE2E2] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#DC2626] tracking-tight">
                        {{ $pengaduanTerbuka }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Pengaduan Aktif
                    </span>
                </div>
            </a>
        </div>
    @elseif ($isBendahara)
        {{-- Tampilan Kartu Khusus Bendahara --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-3.5 mb-5 lg:mb-6">
            <a href="{{ url('/admin/cash-transactions') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#ECFDF5] border border-[#D1FAE5] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200 px-2">
                    <span class="text-sm sm:text-base lg:text-sm xl:text-base font-extrabold text-[#059669] tracking-tight truncate">
                        Rp {{ number_format($saldoKas, 0, ',', '.') }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Saldo Kas RT
                    </span>
                </div>
            </a>

            <a href="{{ url('/admin/cash-transactions') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EAF5EE] border border-[#D2EBD9] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200 px-2">
                    <span class="text-sm sm:text-base lg:text-sm xl:text-base font-extrabold text-[#166534] tracking-tight truncate">
                        Rp {{ number_format($pemasukan, 0, ',', '.') }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Total Pemasukan
                    </span>
                </div>
            </a>

            <a href="{{ url('/admin/cash-transactions') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#FEF2F2] border border-[#FEE2E2] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200 px-2">
                    <span class="text-sm sm:text-base lg:text-sm xl:text-base font-extrabold text-[#DC2626] tracking-tight truncate">
                        Rp {{ number_format($pengeluaran, 0, ',', '.') }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Total Pengeluaran
                    </span>
                </div>
            </a>

            <a href="{{ url('/admin/invoices') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EFF6FF] border border-[#DBEAFE] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200 px-2">
                    <span class="text-sm sm:text-base lg:text-sm xl:text-base font-extrabold text-[#1D4ED8] tracking-tight truncate">
                        Rp {{ number_format($tagihanTertunda, 0, ',', '.') }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Tagihan Tertunda
                    </span>
                </div>
            </a>
        </div>
    @elseif ($isSekretaris)
        {{-- Tampilan Kartu Khusus Sekretaris --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-3.5 mb-5 lg:mb-6">
            <a href="{{ url('/admin/residents') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EAF5EE] border border-[#D2EBD9] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#166534] tracking-tight">
                        {{ $totalWarga }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Total Warga
                    </span>
                </div>
            </a>

            <a href="{{ url('/admin/households') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EEF5FF] border border-[#D6E5FF] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#2563EB] tracking-tight">
                        {{ $totalKk }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Kartu Keluarga
                    </span>
                </div>
            </a>

            <a href="{{ url('/admin/letters') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EEF5FF] border border-[#D6E5FF] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#2563EB] tracking-tight">
                        {{ $suratBerjalan }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Surat Berjalan
                    </span>
                </div>
            </a>

            <a href="{{ url('/admin/complaints') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#FEF2F2] border border-[#FEE2E2] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#DC2626] tracking-tight">
                        {{ $pengaduanTerbuka }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Pengaduan Warga
                    </span>
                </div>
            </a>
        </div>
    @else
        {{-- Tampilan Kartu Lengkap 6 Metrik (Ketua RT & Super Admin) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-3.5 mb-5 lg:mb-6">
            {{-- Card 1: Total Kependudukan --}}
            <a href="{{ url('/admin/residents') }}" title="Total Kependudukan: {{ $totalWarga }} Jiwa ({{ $totalKk }} KK)" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EAF5EE] border border-[#D2EBD9] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#166534] tracking-tight">
                        {{ $totalWarga }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Total Kependudukan
                    </span>
                </div>
            </a>

            {{-- Card 2: Surat Berjalan --}}
            <a href="{{ url('/admin/letters') }}" title="Surat Berjalan: {{ $suratBerjalan }} Berkas" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EEF5FF] border border-[#D6E5FF] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#2563EB] tracking-tight">
                        {{ $suratBerjalan }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Surat Berjalan
                    </span>
                </div>
            </a>

            {{-- Card 3: Saldo Kas RT 15 --}}
            <a href="{{ url('/admin/cash-transactions') }}" title="Saldo Kas RT 15: Rp {{ number_format($saldoKas, 0, ',', '.') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#ECFDF5] border border-[#D1FAE5] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200 px-2">
                    <span class="text-sm sm:text-base lg:text-sm xl:text-base font-extrabold text-[#059669] tracking-tight truncate">
                        Rp {{ number_format($saldoKas, 0, ',', '.') }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Saldo Kas RT
                    </span>
                </div>
            </a>

            {{-- Card 4: Tagihan Iuran Tertunda --}}
            <a href="{{ url('/admin/invoices') }}" title="Tagihan Iuran Tertunda: Rp {{ number_format($tagihanTertunda, 0, ',', '.') }}" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#EFF6FF] border border-[#DBEAFE] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200 px-2">
                    <span class="text-sm sm:text-base lg:text-sm xl:text-base font-extrabold text-[#1D4ED8] tracking-tight truncate">
                        Rp {{ number_format($tagihanTertunda, 0, ',', '.') }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Tagihan Iuran
                    </span>
                </div>
            </a>

            {{-- Card 5: Pengaduan Warga --}}
            <a href="{{ url('/admin/complaints') }}" title="Pengaduan Warga: {{ $pengaduanTerbuka }} Laporan" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#FEF2F2] border border-[#FEE2E2] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#DC2626] tracking-tight">
                        {{ $pengaduanTerbuka }}
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Pengaduan Warga
                    </span>
                </div>
            </a>

            {{-- Card 6: Bank Sampah Lestari --}}
            <a href="{{ url('/admin/waste-deposits') }}" title="Bank Sampah Lestari: {{ number_format($totalSampahKg, 1, ',', '.') }} Kg" class="group block">
                <div class="h-16 sm:h-20 rounded-xl bg-[#FFF9EA] border border-[#FDEBBD] flex items-center justify-center shadow-2xs group-hover:shadow-md group-hover:scale-[1.02] transition-all duration-200">
                    <span class="text-xl sm:text-2xl font-extrabold text-[#D97706] tracking-tight">
                        {{ (float)$totalSampahKg == (int)$totalSampahKg ? number_format($totalSampahKg, 0, ',', '.') : number_format($totalSampahKg, 1, ',', '.') }} <span class="text-[11px] sm:text-xs font-bold opacity-75">Kg</span>
                    </span>
                </div>
                <div class="mt-2 text-center">
                    <span class="text-[10.5px] sm:text-[11px] font-bold uppercase tracking-wider text-[#5B6B82] group-hover:text-[#0F1F3D] transition-colors">
                        Bank Sampah
                    </span>
                </div>
            </a>
        </div>
    @endif

    {{-- ====================================================================
         3. RINGKASAN AKTIVITAS TERBARU (DISESUAIKAN BERDASARKAN ROLE)
         ==================================================================== --}}
    @if ($isKoordinatorBankSampah)
        {{-- Aktivitas Bank Sampah: Setoran Terkini & Katalog Harga --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 mb-5">
            {{-- Panel Kiri: Setoran Sampah Terkini --}}
            <div class="bg-white rounded-xl border border-[#DDE5F0] p-4 sm:p-5 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-[#EEF2F6]">
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-[#0F1F3D]">Setoran Sampah Terkini</h2>
                            <p class="text-[11px] sm:text-xs text-[#5B6B82] mt-0.5">Penimbangan dan transaksi setoran nasabah Bank Sampah</p>
                        </div>
                        <a href="{{ url('/admin/waste-deposits') }}" class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-[#0B3A85] hover:text-[#0B2F6B] hover:underline transition">
                            <span>Lihat Semua &gt;</span>
                        </a>
                    </div>

                    <div class="divide-y divide-[#F1F5F9] mt-1.5">
                        @forelse ($recentWasteDeposits as $deposit)
                            <div class="py-2.5 flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-[#D97706] font-bold flex items-center justify-center shrink-0 text-xs border border-amber-100 group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-[#0F1F3D] group-hover:text-[#0B3A85] transition-colors truncate">
                                            {{ $deposit->household?->kepalaKeluarga?->nama_lengkap ?? 'Keluarga RT 15' }}
                                        </p>
                                        <p class="text-[11px] text-[#5B6B82] truncate mt-0.5">
                                            {{ $deposit->wasteType?->nama ?? 'Sampah' }} &bull; {{ $deposit->berat_kg }} kg &bull; {{ $deposit->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold shrink-0 bg-[#EDF9F1] text-[#1B7A3A] border border-[#D1FAE5]">
                                    Rp {{ number_format($deposit->nilai, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-[#5B6B82] flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span>Belum ada transaksi setoran sampah tercatat.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Panel Kanan: Master Harga Sampah Aktif --}}
            <div class="bg-white rounded-xl border border-[#DDE5F0] p-4 sm:p-5 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-[#EEF2F6]">
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-[#0F1F3D]">Katalog Harga Sampah</h2>
                            <p class="text-[11px] sm:text-xs text-[#5B6B82] mt-0.5">Daftar harga beli per kilogram Bank Sampah Pinang Masak</p>
                        </div>
                        <a href="{{ url('/admin/waste-types') }}" class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-[#0B3A85] hover:text-[#0B2F6B] hover:underline transition">
                            <span>Kelola &gt;</span>
                        </a>
                    </div>

                    <div class="divide-y divide-[#F1F5F9] mt-1.5">
                        @forelse ($recentWasteTypes as $type)
                            <div class="py-2.5 flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#059669] font-bold flex items-center justify-center shrink-0 text-xs border border-emerald-100 group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-[#0F1F3D] group-hover:text-[#0B3A85] transition-colors truncate">
                                            {{ $type->nama }}
                                        </p>
                                        <p class="text-[11px] text-[#5B6B82] truncate mt-0.5">
                                            Kategori: <span class="capitalize">{{ $type->kategori }}</span>
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold shrink-0 bg-[#EBF3FC] text-[#0B3A85] border border-[#DBEAFE]">
                                    Rp {{ number_format($type->harga_per_kg, 0, ',', '.') }} / {{ $type->satuan ?? 'kg' }}
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-[#5B6B82] flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                                <span>Belum ada master harga sampah aktif.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @elseif ($isKoordinatorKeamanan)
        {{-- Aktivitas Keamanan: Lapor Tamu & Jadwal Ronda --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 mb-5">
            {{-- Panel Kiri: Lapor Tamu Terkini --}}
            <div class="bg-white rounded-xl border border-[#DDE5F0] p-4 sm:p-5 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-[#EEF2F6]">
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-[#0F1F3D]">Laporan Tamu 1x24 Jam</h2>
                            <p class="text-[11px] sm:text-xs text-[#5B6B82] mt-0.5">Catatan pendatang dan tamu luar menginap di RT 15</p>
                        </div>
                        <a href="{{ url('/admin/guest-reports') }}" class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-[#0B3A85] hover:text-[#0B2F6B] hover:underline transition">
                            <span>Lihat Semua &gt;</span>
                        </a>
                    </div>

                    <div class="divide-y divide-[#F1F5F9] mt-1.5">
                        @forelse ($recentGuestReports as $guest)
                            <div class="py-2.5 flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0B3A85] font-bold flex items-center justify-center shrink-0 text-xs border border-blue-100 group-hover:scale-105 transition-transform">
                                        {{ strtoupper(substr($guest->nama_tamu, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-[#0F1F3D] group-hover:text-[#0B3A85] transition-colors truncate">
                                            {{ $guest->nama_tamu }}
                                        </p>
                                        <p class="text-[11px] text-[#5B6B82] truncate mt-0.5">
                                            Asal: {{ $guest->asal }} &bull; Datang: {{ $guest->tanggal_datang?->format('d M Y') ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold shrink-0 bg-[#EBF3FC] text-[#0B3A85] border border-[#DBEAFE]">
                                    {{ $guest->tujuan ?? 'Kunjungan' }}
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-[#5B6B82] flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                <span>Belum ada laporan tamu menginap aktif.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Panel Kanan: Jadwal Ronda Terkini --}}
            <div class="bg-white rounded-xl border border-[#DDE5F0] p-4 sm:p-5 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-[#EEF2F6]">
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-[#0F1F3D]">Jadwal Ronda Terkini</h2>
                            <p class="text-[11px] sm:text-xs text-[#5B6B82] mt-0.5">Agenda regu poskamling dan ronda malam lingkungan</p>
                        </div>
                        <a href="{{ url('/admin/patrol-schedules') }}" class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-[#0B3A85] hover:text-[#0B2F6B] hover:underline transition">
                            <span>Kelola &gt;</span>
                        </a>
                    </div>

                    <div class="divide-y divide-[#F1F5F9] mt-1.5">
                        @forelse ($recentPatrolSchedules as $patrol)
                            <div class="py-2.5 flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#059669] font-bold flex items-center justify-center shrink-0 text-xs border border-emerald-100 group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-[#0F1F3D] group-hover:text-[#0B3A85] transition-colors truncate">
                                            {{ $patrol->regu }}
                                        </p>
                                        <p class="text-[11px] text-[#5B6B82] truncate mt-0.5">
                                            {{ $patrol->tanggal?->format('d M Y') ?? '-' }} &bull; {{ $patrol->waktu_mulai }} - {{ $patrol->waktu_selesai }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold shrink-0 bg-[#EDF9F1] text-[#1B7A3A] border border-[#D1FAE5]">
                                    Aktif
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-[#5B6B82] flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Belum ada jadwal ronda yang diagendakan.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @elseif ($isBendahara)
        {{-- Aktivitas Keuangan: Buku Kas & Pembayaran Iuran --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 mb-5">
            {{-- Panel Kiri: Buku Kas Terkini --}}
            <div class="bg-white rounded-xl border border-[#DDE5F0] p-4 sm:p-5 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-[#EEF2F6]">
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-[#0F1F3D]">Buku Kas Terkini</h2>
                            <p class="text-[11px] sm:text-xs text-[#5B6B82] mt-0.5">Pencatatan mutasi kas masuk dan kas keluar RT 15</p>
                        </div>
                        <a href="{{ url('/admin/cash-transactions') }}" class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-[#0B3A85] hover:text-[#0B2F6B] hover:underline transition">
                            <span>Lihat Semua &gt;</span>
                        </a>
                    </div>

                    <div class="divide-y divide-[#F1F5F9] mt-1.5">
                        @forelse ($recentCashTransactions as $tx)
                            <div class="py-2.5 flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg {{ $tx->jenis === 'masuk' ? 'bg-emerald-50 text-[#059669] border-emerald-100' : 'bg-rose-50 text-[#DC2626] border-rose-100' }} font-bold flex items-center justify-center shrink-0 text-xs border group-hover:scale-105 transition-transform">
                                        {{ $tx->jenis === 'masuk' ? '+' : '-' }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-[#0F1F3D] group-hover:text-[#0B3A85] transition-colors truncate">
                                            {{ $tx->keterangan }}
                                        </p>
                                        <p class="text-[11px] text-[#5B6B82] truncate mt-0.5">
                                            {{ $tx->kategori ?? 'Umum' }} &bull; {{ $tx->tanggal?->format('d M Y') ?? $tx->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold shrink-0 {{ $tx->jenis === 'masuk' ? 'bg-[#EDF9F1] text-[#1B7A3A] border border-[#D1FAE5]' : 'bg-[#FEF2F2] text-[#DC2626] border border-[#FEE2E2]' }}">
                                    {{ $tx->jenis === 'masuk' ? '+' : '-' }} Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-[#5B6B82] flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Belum ada transaksi kas tercatat.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Panel Kanan: Validasi Pembayaran Terkini --}}
            <div class="bg-white rounded-xl border border-[#DDE5F0] p-4 sm:p-5 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-[#EEF2F6]">
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-[#0F1F3D]">Pembayaran Iuran Masuk</h2>
                            <p class="text-[11px] sm:text-xs text-[#5B6B82] mt-0.5">Bukti transfer dan setoran iuran warga RT 15</p>
                        </div>
                        <a href="{{ url('/admin/payments') }}" class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-[#0B3A85] hover:text-[#0B2F6B] hover:underline transition">
                            <span>Validasi &gt;</span>
                        </a>
                    </div>

                    <div class="divide-y divide-[#F1F5F9] mt-1.5">
                        @forelse ($recentPayments as $payment)
                            <div class="py-2.5 flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0B3A85] font-bold flex items-center justify-center shrink-0 text-xs border border-blue-100 group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-[#0F1F3D] group-hover:text-[#0B3A85] transition-colors truncate">
                                            {{ $payment->invoice?->household?->kepalaKeluarga?->nama_lengkap ?? 'Keluarga RT 15' }}
                                        </p>
                                        <p class="text-[11px] text-[#5B6B82] truncate mt-0.5">
                                            {{ $payment->metode ?? 'Transfer' }} &bull; {{ $payment->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold shrink-0 {{ $payment->status === 'verified' ? 'bg-[#EDF9F1] text-[#1B7A3A] border border-[#D1FAE5]' : 'bg-[#FFFBEB] text-[#D97706] border border-[#FEF3C7]' }}">
                                    Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-[#5B6B82] flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                                <span>Belum ada pembayaran iuran baru yang menunggu validasi.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Aktivitas Standar: Surat Pengantar & Pengaduan Warga (Ketua RT, Sekretaris, Super Admin) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 mb-5">
            {{-- Panel Kiri: Surat Pengantar Terkini --}}
            <div class="bg-white rounded-xl border border-[#DDE5F0] p-4 sm:p-5 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-[#EEF2F6]">
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-[#0F1F3D]">Surat Pengantar Terkini</h2>
                            <p class="text-[11px] sm:text-xs text-[#5B6B82] mt-0.5">Permohonan dokumen administratif terbaru dari warga RT 15</p>
                        </div>
                        <a href="{{ url('/admin/letters') }}" class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-[#0B3A85] hover:text-[#0B2F6B] hover:underline transition">
                            <span>Lihat Semua &gt;</span>
                        </a>
                    </div>

                    <div class="divide-y divide-[#F1F5F9] mt-1.5">
                        @forelse ($recentLetters as $letter)
                            <div class="py-2.5 flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0B3A85] font-bold flex items-center justify-center shrink-0 text-xs border border-blue-100 group-hover:scale-105 transition-transform">
                                        {{ strtoupper(substr($letter->resident?->nama_lengkap ?? 'W', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-[#0F1F3D] group-hover:text-[#0B3A85] transition-colors truncate">
                                            {{ $letter->letterType?->nama_surat ?? 'Surat Pengantar' }}
                                        </p>
                                        <p class="text-[11px] text-[#5B6B82] truncate mt-0.5">
                                            {{ $letter->resident?->nama_lengkap ?? 'Warga RT 15' }} &bull; {{ $letter->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold shrink-0 {{ match($letter->status) { 'selesai' => 'bg-[#EDF9F1] text-[#1B7A3A] border border-[#D1FAE5]', 'ditolak' => 'bg-[#FEF2F2] text-[#DC2626] border border-[#FEE2E2]', 'diperiksa' => 'bg-[#EBF3FC] text-[#0B3A85] border border-[#DBEAFE]', default => 'bg-[#FFFBEB] text-[#D97706] border border-[#FEF3C7]' } }}">
                                    {{ ucfirst($letter->status) }}
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-[#5B6B82] flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Belum ada permohonan surat masuk baru.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Panel Kanan: Pengaduan Warga Terkini --}}
            <div class="bg-white rounded-xl border border-[#DDE5F0] p-4 sm:p-5 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-[#EEF2F6]">
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-[#0F1F3D]">Pengaduan Warga Terkini</h2>
                            <p class="text-[11px] sm:text-xs text-[#5B6B82] mt-0.5">Laporan lingkungan dan aspirasi aktif warga RT 15</p>
                        </div>
                        <a href="{{ url('/admin/complaints') }}" class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-[#0B3A85] hover:text-[#0B2F6B] hover:underline transition">
                            <span>Lihat Semua &gt;</span>
                        </a>
                    </div>

                    <div class="divide-y divide-[#F1F5F9] mt-1.5">
                        @forelse ($recentComplaints as $complaint)
                            <div class="py-2.5 flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-[#DC2626] font-bold flex items-center justify-center shrink-0 text-xs border border-rose-100 group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-[#0F1F3D] group-hover:text-[#0B3A85] transition-colors truncate">
                                            {{ $complaint->judul }}
                                        </p>
                                        <p class="text-[11px] text-[#5B6B82] truncate mt-0.5">
                                            {{ $complaint->is_anonim ? 'Warga Anonim' : ($complaint->user?->name ?? 'Warga RT 15') }} &bull; {{ ucfirst($complaint->kategori) }} &bull; {{ $complaint->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold shrink-0 {{ match($complaint->status) { 'selesai' => 'bg-[#EDF9F1] text-[#1B7A3A] border border-[#D1FAE5]', 'diproses' => 'bg-[#EBF3FC] text-[#0B3A85] border border-[#DBEAFE]', 'ditolak' => 'bg-[#FEF2F2] text-[#DC2626] border border-[#FEE2E2]', default => 'bg-[#FFFBEB] text-[#D97706] border border-[#FEF3C7]' } }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-[#5B6B82] flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Lingkungan aman dan kondusif. Tidak ada pengaduan aktif.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-widgets::widget>
