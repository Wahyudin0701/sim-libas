<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Pengantar - {{ $letter->nomor_surat ?? 'Draft' }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 18mm 20mm 15mm 20mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.45;
            color: #000000;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .kop-logo {
            width: 75px;
            height: auto;
        }

        .kop-instansi {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.25;
            margin: 0;
        }

        .kop-rt {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin: 2px 0;
            letter-spacing: 0.5px;
        }

        .kop-alamat {
            font-size: 8.5pt;
            color: #222222;
            line-height: 1.35;
            margin: 0;
        }

        .kop-line {
            border-top: 2.5px solid #000000;
            border-bottom: 1px solid #000000;
            height: 2px;
            margin-top: 6px;
            margin-bottom: 18px;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 18px;
        }

        .judul-surat h1 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .judul-surat .nomor {
            font-size: 10.5pt;
            margin-top: 4px;
        }

        p.paragraf {
            text-align: justify;
            text-indent: 32px;
            margin: 0 0 9px 0;
        }

        .table-identitas {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 12px 20px;
            font-size: 11pt;
        }

        .table-identitas td {
            padding: 2.5px 4px;
            vertical-align: top;
        }

        .table-identitas td.label {
            width: 180px;
        }

        .table-identitas td.separator {
            width: 15px;
            text-align: center;
        }

        .table-identitas td.value {
            font-weight: bold;
        }

        .box-keperluan {
            width: 95%;
            margin: 4px 0 12px 20px;
            border-collapse: collapse;
        }

        .box-keperluan td {
            background-color: #f8fafc;
            border-left: 3px solid #000000;
            padding: 8px 12px;
            font-style: italic;
            font-size: 10.5pt;
            line-height: 1.4;
        }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .qr-card {
            border: 1px dashed #94a3b8;
            padding: 6px 10px;
            width: 95%;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        .qr-card td {
            vertical-align: middle;
        }

        .qr-info-text {
            font-family: sans-serif;
            font-size: 7pt;
            color: #334155;
            line-height: 1.35;
            padding-left: 8px;
        }

        .qr-info-text strong {
            color: #0f172a;
            font-size: 7.5pt;
            display: block;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI -->
    <table class="header-table">
        <tr>
            <td style="width: 75px; text-align: left;">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" class="kop-logo" alt="Logo RT 15">
                @endif
            </td>
            <td style="text-align: center; padding-right: {{ !empty($logoBase64) ? '75px' : '0' }};">
                <div class="kop-instansi">
                    PEMERINTAH KOTA JAMBI<br>
                    KECAMATAN {{ strtoupper($setting->kecamatan ?? 'TELANAIPURA') }} &bull; KELURAHAN {{ strtoupper($setting->kelurahan ?? 'PEMATANG SULUR') }}
                </div>
                <div class="kop-rt">
                    RUKUN TETANGGA 15
                </div>
                <div class="kop-alamat">
                    Sekretariat: {{ $setting->alamat_sekretariat ?? 'Kampung Digital Pinang Masak Lestari, RT 15' }}<br>
                    Kota Jambi, Provinsi Jambi &bull; Kontak: {{ $setting->kontak_pengurus ?? '0812-7400-1515' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="kop-line"></div>

    <!-- JUDUL SURAT RESMI -->
    <div class="judul-surat">
        <h1>{{ $letter->letterType?->nama ?? 'SURAT PENGANTAR' }}</h1>
        <div class="nomor">
            Nomor: <strong>{{ $letter->nomor_surat ?? '...../RT.15/PMS/' . date('m/Y') }}</strong>
        </div>
    </div>

    <!-- PEMBUKA SURAT -->
    <p class="paragraf">
        Yang bertanda tangan di bawah ini Ketua Rukun Tetangga (RT) 15 Kelurahan {{ ucwords(strtolower($setting->kelurahan ?? 'Pematang Sulur')) }}, Kecamatan {{ ucwords(strtolower($setting->kecamatan ?? 'Telanaipura')) }}, Kota Jambi, dengan ini menerangkan bahwa:
    </p>

    <!-- DATA IDENTITAS WARGA -->
    <table class="table-identitas">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="separator">:</td>
            <td class="value">{{ strtoupper($letter->resident?->nama ?? '-') }}</td>
        </tr>
        <tr>
            <td class="label">NIK (No. KTP)</td>
            <td class="separator">:</td>
            <td class="value">{{ $letter->resident?->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Kartu Keluarga</td>
            <td class="separator">:</td>
            <td style="font-weight: 500;">{{ $letter->resident?->household?->nomor_kk ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td class="separator">:</td>
            <td style="font-weight: 500;">
                {{ $letter->resident?->tempat_lahir ?? 'Jambi' }}, 
                {{ $letter->resident?->tanggal_lahir ? $letter->resident->tanggal_lahir->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td class="separator">:</td>
            <td style="font-weight: 500;">
                {{ $letter->resident?->jenis_kelamin === 'L' ? 'Laki-laki' : ($letter->resident?->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
            </td>
        </tr>
        <tr>
            <td class="label">Agama</td>
            <td class="separator">:</td>
            <td style="font-weight: 500;">{{ ucfirst($letter->resident?->agama ?? 'Islam') }}</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td class="separator">:</td>
            <td style="font-weight: 500;">{{ $letter->resident?->pekerjaan ?? 'Wiraswasta' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat / Tempat Tinggal</td>
            <td class="separator">:</td>
            <td style="font-weight: 500;">
                {{ $letter->resident?->household?->alamat ?? 'RT 15 Kel. Pematang Sulur, Kec. Telanaipura, Kota Jambi.' }}
            </td>
        </tr>
    </table>

    <!-- KETERANGAN DOMISILI & KEPERLUAN -->
    <p class="paragraf">
        Berdasarkan data kependudukan dan pencatatan kami, orang yang namanya tersebut di atas adalah benar-benar warga yang berdomisili sah dan bertempat tinggal di lingkungan RT 15 Kelurahan {{ ucwords(strtolower($setting->kelurahan ?? 'Pematang Sulur')) }}, Kecamatan {{ ucwords(strtolower($setting->kecamatan ?? 'Telanaipura')) }}, Kota Jambi.
    </p>

    <p class="paragraf" style="margin-bottom: 4px;">
        Surat keterangan/pengantar ini diberikan kepada yang bersangkutan untuk keperluan:
    </p>

    <table class="box-keperluan">
        <tr>
            <td>
                &ldquo;{{ $letter->keperluan }}&rdquo;
            </td>
        </tr>
    </table>

    <p class="paragraf">
        Demikian surat pengantar ini kami buat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya oleh pihak yang berkepentingan.
    </p>

    <!-- TANDA TANGAN & QR CODE ELEKTRONIK -->
    <table class="ttd-table">
        <tr>
            <!-- Validasi QR Code Digital -->
            <td style="width: 58%; vertical-align: bottom; padding-right: 15px;">
                <table class="qr-card">
                    <tr>
                        <td style="width: 78px; text-align: center;">
                            @if(!empty($qrCodeBase64))
                                <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" width="72" height="72" alt="QR Validasi">
                            @endif
                        </td>
                        <td class="qr-info-text">
                            <strong>DOKUMEN RESMI TANDATANGAN ELEKTRONIK</strong>
                            Surat ini sah dan diterbitkan secara digital oleh Pengurus RT 15 Pematang Sulur.<br>
                            Scan QR Code untuk verifikasi keaslian dokumen resmi di SIM-LiBas.
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Tanda Tangan Ketua RT -->
            <td style="width: 42%; vertical-align: top; text-align: center;">
                <div style="font-size: 10.5pt; margin-bottom: 4px;">
                    {{ $setting->kota ?? 'Jambi' }}, {{ $letter->tanggal_disetujui ? $letter->tanggal_disetujui->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                </div>
                <div style="font-size: 10.5pt; font-weight: bold; margin-bottom: 55px;">
                    Ketua Rukun Tetangga 15
                </div>
                <div style="font-size: 11pt; font-weight: bold; text-decoration: underline;">
                    {{ $setting->nama_ketua_rt ?? 'Dedi Kurniawan, S.Kom' }}
                </div>
                <div style="font-size: 9pt; color: #444444; margin-top: 2px;">
                    ID Pengurus: {{ $setting->nomor_sk_rt ?? 'SK-RT15/PMS/2024' }}
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
