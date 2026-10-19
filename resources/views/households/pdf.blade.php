<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Kartu Keluarga - RT 15</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 12mm 14mm 12mm 14mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            line-height: 1.35;
            color: #0F1F3D;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .header-top {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-top td {
            vertical-align: middle;
        }

        .brand-title {
            font-size: 14pt;
            font-weight: 800;
            color: #0B3A85;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .brand-subtitle {
            font-size: 7.5pt;
            color: #5B6B82;
            font-weight: 600;
            margin: 2px 0 0 0;
            letter-spacing: 0.2px;
        }

        .meta-right {
            text-align: right;
        }

        .meta-right .instansi {
            font-size: 11pt;
            font-weight: 700;
            color: #0F1F3D;
            margin: 0;
            text-transform: uppercase;
        }

        .meta-right .wilayah {
            font-size: 7.5pt;
            color: #5B6B82;
            margin: 2px 0 0 0;
        }

        /* Banner Biru Utama Sesuai Referensi */
        .banner-bar {
            background-color: #0B3A85;
            color: #ffffff;
            padding: 9px 12px;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .banner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .banner-title {
            font-size: 11.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            color: #ffffff;
        }

        .banner-subtitle {
            font-size: 7.5pt;
            color: #D6E5FF;
            margin-top: 2px;
        }

        .banner-system {
            text-align: right;
            font-size: 8pt;
            font-weight: 600;
            color: #EBF3FC;
        }

        /* Ringkasan Filter & Statistik */
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 4px;
        }

        .summary-box td {
            padding: 5px 8px;
            font-size: 7.5pt;
            vertical-align: top;
        }

        .summary-label {
            color: #64748B;
            font-weight: 600;
        }

        .summary-value {
            color: #0F1F3D;
            font-weight: bold;
        }

        /* Tabel Data Kartu Keluarga */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .data-table thead th {
            background-color: #0B3A85;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 6px 5px;
            border: 1px solid #0B3A85;
            text-align: center;
        }

        .data-table tbody td {
            padding: 5.5px 5px;
            font-size: 7.5pt;
            border: 1px solid #CBD5E1;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #F8FAFC;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        .badge-id {
            font-weight: bold;
            color: #0B3A85;
        }

        .badge-anggota {
            font-weight: bold;
            color: #0B3A85;
        }

        .badge-ada {
            color: #047857;
            font-weight: 600;
        }

        .badge-belum {
            color: #94A3B8;
        }

        /* Footer Pengesahan & Tanda Tangan */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .footer-table td {
            vertical-align: top;
            font-size: 7.5pt;
        }

        .note-doc {
            color: #64748B;
            line-height: 1.4;
            max-width: 480px;
        }

        .signature-box {
            text-align: center;
            width: 220px;
            float: right;
        }

        .signature-space {
            height: 48px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0F1F3D;
        }
    </style>
</head>
<body>
    {{-- 1. KOP SURAT ATAS --}}
    <table class="header-top">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" style="height: 40px; width: auto; max-width: 220px; display: block;" alt="Logo SIM-LiBas">
                @else
                    <div class="brand-title">SIM-LiBas RT 15</div>
                    <div class="brand-subtitle">Sistem Informasi Manajemen RT 15 &bull; Kelurahan Pematang Sulur</div>
                @endif
            </td>
            <td class="meta-right" style="width: 50%;">
                <div class="instansi">KAMPUNG DIGITAL RT 15</div>
                <div class="wilayah">Kecamatan Telanaipura, Kota Jambi, Provinsi Jambi</div>
            </td>
        </tr>
    </table>

    {{-- 2. BANNER BIRU UTAMA SESUAI REFERENSI --}}
    <div class="banner-bar">
        <table class="banner-table">
            <tr>
                <td style="width: 70%;">
                    <div class="banner-title">Laporan Data Kartu Keluarga (KK)</div>
                    <div class="banner-subtitle">Wilayah Rukun Tetangga 15 / Rukun Warga 04 &bull; Dicetak: {{ $printedAt->translatedFormat('d F Y, H:i') }} WIB</div>
                </td>
                <td class="banner-system" style="width: 30%;">
                    <div>SISTEM KEPENDUDUKAN RT 15</div>
                    <div style="font-size: 7pt; font-weight: normal; color: #D6E5FF; margin-top: 2px;">Dokumen Rekapitulasi Resmi</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- 3. RINGKASAN FILTER & DATA --}}
    <table class="summary-box">
        <tr>
            <td style="width: 25%;">
                <span class="summary-label">Kategori Laporan:</span><br/>
                <span class="summary-value">Kependudukan & Kartu Keluarga</span>
            </td>
            <td style="width: 35%;">
                <span class="summary-label">Kriteria / Filter Data:</span><br/>
                <span class="summary-value">{{ $filterInfo }}</span>
            </td>
            <td style="width: 20%;">
                <span class="summary-label">Total Kartu Keluarga:</span><br/>
                <span class="summary-value">{{ $totalKk }} KK</span>
            </td>
            <td style="width: 20%;">
                <span class="summary-label">Total Jiwa Terdaftar:</span><br/>
                <span class="summary-value">{{ $totalJiwa }} Jiwa</span>
            </td>
        </tr>
    </table>

    {{-- 4. TABEL DATA KARTU KELUARGA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 26px;">NO</th>
                <th style="width: 60px;">ID KK</th>
                <th style="width: 110px;">NOMOR KK</th>
                <th style="width: 140px;">KEPALA KELUARGA</th>
                <th style="width: 110px;">NIK KEPALA</th>
                <th style="width: 95px;">NO. TELEPON / WA</th>
                <th>ALAMAT RUMAH</th>
                <th style="width: 60px;">FOTO KK</th>
                <th style="width: 80px;">JUMLAH ANGGOTA</th>
            </tr>
        </thead>
        <tbody>
            @forelse($households as $index => $household)
                @php
                    $kepala = $household->kepalaKeluarga;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center badge-id">{{ $household->id_kk ?? '-' }}</td>
                    <td class="text-center font-mono" style="letter-spacing: 0.3px;">{{ $household->no_kk ?? '-' }}</td>
                    <td class="text-left font-bold">{{ $kepala?->nama ?? '-' }}</td>
                    <td class="text-center font-mono" style="letter-spacing: 0.3px;">{{ $kepala?->nik ?? '-' }}</td>
                    <td class="text-center">{{ $kepala?->telepon ?? '-' }}</td>
                    <td class="text-left">{{ $household->alamat ?? '-' }}</td>
                    <td class="text-center">
                        @if($household->foto_kk)
                            <span class="badge-ada">&#10003; Ada</span>
                        @else
                            <span class="badge-belum">Belum Ada</span>
                        @endif
                    </td>
                    <td class="text-center badge-anggota">{{ $household->residents_count }} Jiwa</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 18px; color: #64748B;">
                        Tidak ada data kartu keluarga yang memenuhi kriteria pencarian / filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 5. FOOTER & PENGESAHAN --}}
    <table class="footer-table">
        <tr>
            <td style="width: 65%;">
                <div class="note-doc">
                    <strong>Catatan Sistem:</strong><br/>
                    Menampilkan <strong>{{ $totalKk }}</strong> data Kartu Keluarga. Dokumen ini digenerate secara otomatis oleh <strong>Sistem Informasi Manajemen RT 15 (SIM-LiBas)</strong> pada {{ $printedAt->translatedFormat('d F Y, H:i:s') }} WIB atas permintaan {{ $user?->name ?? 'Pengurus RT' }}.
                </div>
            </td>
            <td style="width: 35%;">
                <div class="signature-box">
                    <div>Pematang Sulur, {{ $printedAt->translatedFormat('d F Y') }}</div>
                    <div style="font-weight: 600; margin-top: 2px;">Ketua RT 15</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $setting->nama_ketua_rt ?? 'Dedi Kurniawan' }}</div>
                    <div style="font-size: 7pt; color: #64748B; margin-top: 1px;">Pengurus RT 15 Pematang Sulur</div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
