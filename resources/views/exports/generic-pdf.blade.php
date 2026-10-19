<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }} - RT 15</title>
    <style>
        @page {
            size: a4 {{ $orientation ?? 'landscape' }};
            margin: 12mm 14mm 12mm 14mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8pt;
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

        /* Banner Biru Utama */
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

        /* Tabel Data */
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
                    <div style="font-size: 14pt; font-weight: 800; color: #0B3A85; letter-spacing: 0.5px; text-transform: uppercase;">SIM-LiBas RT 15</div>
                    <div style="font-size: 7.5pt; color: #5B6B82; font-weight: 600; margin-top: 2px;">Sistem Informasi Manajemen RT 15 &bull; Kelurahan Pematang Sulur</div>
                @endif
            </td>
            <td class="meta-right" style="width: 50%;">
                <div class="instansi">KAMPUNG DIGITAL RT 15</div>
                <div class="wilayah">Kecamatan Telanaipura, Kota Jambi, Provinsi Jambi</div>
            </td>
        </tr>
    </table>

    {{-- 2. BANNER BIRU UTAMA --}}
    <div class="banner-bar">
        <table class="banner-table">
            <tr>
                <td style="width: 70%;">
                    <div class="banner-title">{{ $title }}</div>
                    <div class="banner-subtitle">{{ $subtitle ?? 'Wilayah Rukun Tetangga 15 / Rukun Warga 04' }} &bull; Dicetak: {{ $printedAt->translatedFormat('d F Y, H:i') }} WIB</div>
                </td>
                <td class="banner-system" style="width: 30%;">
                    <div>SISTEM INFORMASI RT 15</div>
                    <div style="font-size: 7pt; font-weight: normal; color: #D6E5FF; margin-top: 2px;">Dokumen Rekapitulasi Resmi</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- 3. RINGKASAN FILTER & DATA --}}
    <table class="summary-box">
        <tr>
            <td style="width: 30%;">
                <span class="summary-label">Kategori Laporan:</span><br/>
                <span class="summary-value">{{ $category }}</span>
            </td>
            <td style="width: 45%;">
                <span class="summary-label">Kriteria / Filter Data:</span><br/>
                <span class="summary-value">{{ $filterInfo }}</span>
            </td>
            <td style="width: 25%;">
                <span class="summary-label">Total Data Terdaftar:</span><br/>
                <span class="summary-value">{{ count($rows) }} Data</span>
            </td>
        </tr>
    </table>

    {{-- 4. TABEL DATA --}}
    <table class="data-table">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    @foreach($row as $cellIndex => $cellValue)
                        <td class="{{ $cellAlignments[$cellIndex] ?? 'text-left' }}">
                            {{ $cellValue }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="text-center" style="padding: 18px; color: #64748B;">
                        Tidak ada data yang memenuhi kriteria pencarian / filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(!empty($tableFooters))
            <tfoot>
                @foreach($tableFooters as $footerRow)
                    <tr style="background-color: {{ $footerRow['bg'] ?? '#F1F5F9' }};">
                        @foreach($footerRow['cells'] as $cell)
                            <td 
                                colspan="{{ $cell['colspan'] ?? 1 }}" 
                                class="{{ $cell['align'] ?? 'text-left' }}" 
                                style="padding: 5.5px 5px; font-size: 7.5pt; border: 1px solid #CBD5E1; color: {{ $cell['color'] ?? '#0F1F3D' }}; font-weight: {{ ($cell['bold'] ?? true) ? 'bold' : 'normal' }};"
                            >
                                {{ $cell['text'] }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tfoot>
        @endif
    </table>

    {{-- 5. FOOTER & PENGESAHAN --}}
    <table class="footer-table">
        <tr>
            <td style="width: 65%;">
                <div class="note-doc">
                    <strong>Catatan Sistem:</strong><br/>
                    Menampilkan <strong>{{ count($rows) }}</strong> baris data. Dokumen ini digenerate secara otomatis oleh <strong>Sistem Informasi Manajemen RT 15 (SIM-LiBas)</strong> pada {{ $printedAt->translatedFormat('d F Y, H:i:s') }} WIB atas permintaan {{ $user?->name ?? 'Pengurus RT' }}.
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
