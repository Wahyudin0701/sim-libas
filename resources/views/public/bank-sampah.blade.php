@extends('layouts.public')

@section('title', 'Bank Sampah Pinang Masak Lestari | RT 15 Pematang Sulur')

@section('content')
    <!-- Hero Header Bank Sampah (Selaras dengan Direktori Layanan) -->
    <section class="sim-gradient text-white relative overflow-hidden py-16 sm:py-20">
        <!-- Subtle background shapes -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" preserveAspectRatio="none">
                <path d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,192C960,203,1056,181,1152,160C1248,139,1344,117,1392,106.7L1440,96L1440,400L1392,400C1344,400,1248,400,1152,400C1056,400,960,400,864,400C768,400,672,400,576,400C480,400,384,400,288,400C192,400,96,400,48,400L0,400Z" fill="white"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Breadcrumbs -->
            <nav class="flex items-center justify-center gap-2 text-xs text-blue-200 mb-6">
                <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Bank Sampah</span>
            </nav>

            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-gold-400 mb-4 border border-white/20">
                Ekonomi Sirkular & Pelestarian Lingkungan
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-4">
                Bank Sampah Pinang Masak Lestari
            </h1>
            <p class="text-base sm:text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Wujudkan lingkungan RT 15 yang bersih, asri, dan berdaya. Pilah sampah anorganik dari rumah, setorkan secara berkala, dan konversikan menjadi saldo tabungan bernilai rupiah.
            </p>
        </div>
    </section>

    <!-- Konten Utama: Metrik, Tabel Harga, Alur Setor & CTA -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 pb-16">
        <!-- 3 Kartu Metrik Dampak Lingkungan -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <!-- Kartu 1: Total Sampah -->
            <div class="sim-card p-6 flex items-center gap-5 hover:-translate-y-1 hover:shadow-xl transition duration-300 bg-white border border-border-soft rounded-2xl shadow-sm">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-text-muted font-medium block">Total Sampah Terkumpul</span>
                    <span class="text-2xl font-extrabold text-emerald-700 tracking-tight">{{ number_format($totalKg, 1, ',', '.') }} Kg</span>
                    <span class="text-[11px] text-text-muted block mt-0.5">Sampah Anorganik Terpilah</span>
                </div>
            </div>

            <!-- Kartu 2: Keluarga Partisipan -->
            <div class="sim-card p-6 flex items-center gap-5 hover:-translate-y-1 hover:shadow-xl transition duration-300 bg-white border border-border-soft rounded-2xl shadow-sm">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-primary-700 border border-blue-100 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-text-muted font-medium block">Keluarga Partisipan</span>
                    <span class="text-2xl font-extrabold text-primary-900 tracking-tight">{{ $totalPenyetor }} KK</span>
                    <span class="text-[11px] text-text-muted block mt-0.5">Warga Terdaftar Aktif</span>
                </div>
            </div>

            <!-- Kartu 3: Jadwal Penimbangan -->
            <div class="sim-card p-6 flex items-center gap-5 hover:-translate-y-1 hover:shadow-xl transition duration-300 bg-white border border-border-soft rounded-2xl shadow-sm">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-gold-700 border border-gold-200 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-text-muted font-medium block">Jadwal Penimbangan</span>
                    <span class="text-lg font-bold text-primary-900">Minggu Ke-2 & Ke-4</span>
                    <span class="text-[11px] text-text-muted block mt-0.5">Pukul 08.00 - 11.00 WIB (Pos RT 15)</span>
                </div>
            </div>
        </div>

        <!-- Tabel Master Harga Sampah Hari Ini -->
        <div class="sim-card bg-white border border-border-soft rounded-2xl shadow-sm overflow-hidden mb-12">
            <div class="p-6 bg-white border-b border-border-soft flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded border border-emerald-200">
                            Katalog Nilai Ekonomi
                        </span>
                    </div>
                    <h2 class="text-xl font-extrabold text-primary-900">Daftar Harga Beli Sampah Terkini</h2>
                    <p class="text-xs sm:text-sm text-text-muted mt-1">Harga resmi per kilogram yang berlaku di Bank Sampah Pinang Masak Lestari RT 15</p>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Dapat Berubah Mengikuti Pasar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-bg-soft text-text-muted text-xs uppercase tracking-wider border-b border-border-soft">
                            <th class="py-4 px-6 font-semibold">Jenis Sampah</th>
                            <th class="py-4 px-6 font-semibold">Kategori</th>
                            <th class="py-4 px-6 font-semibold">Satuan</th>
                            <th class="py-4 px-6 font-semibold text-right">Harga Beli / Satuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft text-sm">
                        @forelse($wasteTypes as $type)
                            @php
                                $badgeClass = match(strtolower($type->kategori ?? '')) {
                                    'plastik' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'kertas', 'kardus' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'logam', 'besi' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'kaca' => 'bg-cyan-50 text-cyan-800 border-cyan-200',
                                    'organik' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                            @endphp
                            <tr class="hover:bg-blue-50/40 transition duration-150">
                                <td class="py-4 px-6 font-bold text-primary-900 flex items-center gap-2.5">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <span>{{ $type->nama }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                        {{ ucfirst($type->kategori) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-text-muted text-xs font-medium">{{ $type->satuan }}</td>
                                <td class="py-4 px-6 text-right font-extrabold text-emerald-700 text-base">
                                    Rp {{ number_format($type->harga_per_kg, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-text-muted">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <span class="text-sm">Belum ada data harga sampah yang dimasukkan pengurus.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Catatan Khusus Sampah -->
            <div class="p-4 sm:p-5 bg-bg-soft/70 border-t border-border-soft flex items-start gap-3 text-xs text-text-muted">
                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="leading-relaxed">
                    <strong>Ketentuan Setor:</strong> Pastikan sampah anorganik yang disetor dalam keadaan bersih, kering, dan dipadatkan/diikat rapi agar mempercepat proses penimbangan di lokasi.
                </span>
            </div>
        </div>

        <!-- Panduan 4 Langkah Menabung Sampah -->
        <div class="mb-14">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200 inline-block mb-3">
                    Alur Praktis Warga
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-primary-900">
                    Cara Menabung di Bank Sampah RT 15
                </h3>
                <p class="text-xs sm:text-sm text-text-muted mt-2">
                    Ikuti langkah mudah berikut untuk mengubah sampah rumah tangga menjadi tabungan bernilai rupiah.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Step 1 -->
                <div class="sim-card p-6 bg-white border border-border-soft rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-primary-700 text-white font-extrabold flex items-center justify-center text-sm mb-4">
                        01
                    </div>
                    <h4 class="text-base font-bold text-primary-900 mb-2">Pilah dari Rumah</h4>
                    <p class="text-xs text-text-muted leading-relaxed">
                        Pisahkan sampah anorganik kering (kardus, botol plastik, kaleng/besi, minyak jelantah) ke wadah terpisah dari sampah basah.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="sim-card p-6 bg-white border border-border-soft rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-primary-700 text-white font-extrabold flex items-center justify-center text-sm mb-4">
                        02
                    </div>
                    <h4 class="text-base font-bold text-primary-900 mb-2">Bawa ke Posko</h4>
                    <p class="text-xs text-text-muted leading-relaxed">
                        Kunjungi posko penimbangan Bank Sampah di Pos RT 15 setiap Minggu ke-2 dan ke-4 pukul 08.00 - 11.00 WIB.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="sim-card p-6 bg-white border border-border-soft rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-primary-700 text-white font-extrabold flex items-center justify-center text-sm mb-4">
                        03
                    </div>
                    <h4 class="text-base font-bold text-primary-900 mb-2">Penimbangan Digital</h4>
                    <p class="text-xs text-text-muted leading-relaxed">
                        Petugas menimbang sampah di hadapan Anda dan menginput beratnya ke sistem SIM-LiBas sesuai harga berlaku.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="sim-card p-6 bg-white border border-border-soft rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-extrabold flex items-center justify-center text-sm mb-4">
                        04
                    </div>
                    <h4 class="text-base font-bold text-primary-900 mb-2">Saldo Masuk & Cair</h4>
                    <p class="text-xs text-text-muted leading-relaxed">
                        Saldo otomatis tercatat di akun Portal Warga Anda. Bisa ditarik tunai atau dipotongkan langsung ke tagihan iuran RT.
                    </p>
                </div>
            </div>
        </div>

        <!-- Banner Informasi & CTA Masuk Portal Warga -->
        <div class="bg-white border border-border-soft rounded-3xl p-8 sm:p-12 shadow-sm flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <span class="text-xs font-bold uppercase tracking-wider text-gold-700 mb-2 block">Tabungan Digital Warga</span>
                <h3 class="text-2xl font-bold text-primary-900 mb-3">
                    Ingin Memantau Saldo Bank Sampah Anda?
                </h3>
                <p class="text-sm text-text-muted leading-relaxed">
                    Setiap keluarga warga terdaftar dapat melihat riwayat setoran sampah, berat kilogram terakumulasi, dan saldo tabungan terkini langsung melalui akun Portal Warga SIM-LiBas.
                </p>
            </div>
            <a href="{{ route('citizen.login') }}" class="sim-btn-gold text-sm px-6 py-3.5 rounded-xl transition shadow-sm hover:shadow shrink-0 flex items-center gap-2">
                <span>Masuk Portal Warga</span>
            </a>
        </div>
    </section>
@endsection
