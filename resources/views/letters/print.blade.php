<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengantar - {{ $letter->nomor_surat ?? 'Draft' }} | RT 15 Pematang Sulur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Times+New+Roman&display=swap" rel="stylesheet">
    
    <style>
        /* Standar Kertas Cetak Surat Resmi (A4) */
        @page {
            size: A4;
            margin: 15mm 20mm 15mm 20mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px 0;
            line-height: 1.5;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .no-print-bar {
            font-family: 'Plus Jakarta Sans', sans-serif;
            max-width: 210mm;
            margin: 0 auto 16px auto;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .sheet {
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 20mm 20mm 15mm 20mm;
            box-sizing: border-box;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Kop Surat Resmi */
        .kop-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            text-align: center;
            padding-bottom: 10px;
        }

        .kop-logo {
            position: absolute;
            left: 0;
            top: 2px;
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        .kop-text {
            width: 100%;
        }

        .kop-instansi {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.25;
        }

        .kop-rt {
            font-size: 16pt;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 3px 0 2px 0;
            line-height: 1.2;
        }

        .kop-alamat {
            font-size: 9pt;
            margin: 0;
            color: #222;
            line-height: 1.35;
        }

        .kop-border {
            border-top: 2.5px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 8px;
            margin-bottom: 22px;
        }

        /* Judul Surat */
        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
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
            font-size: 11pt;
            margin-top: 4px;
            font-weight: normal;
        }

        /* Konten Surat */
        .isi-surat {
            font-size: 11.5pt;
            text-align: justify;
        }

        .isi-surat p {
            margin: 0 0 10px 0;
            text-indent: 32px;
        }

        .tabel-identitas {
            width: 100%;
            margin: 12px 0 16px 20px;
            border-collapse: collapse;
            font-size: 11.5pt;
        }

        .tabel-identitas td {
            padding: 3px 6px;
            vertical-align: top;
        }

        .tabel-identitas td.label {
            width: 28%;
        }

        .tabel-identitas td.separator {
            width: 3%;
            text-align: center;
        }

        .tabel-identitas td.value {
            width: 69%;
            font-weight: 600;
        }

        .kotak-keperluan {
            background-color: #fafafa;
            border-left: 3px solid #000;
            padding: 8px 14px;
            margin: 8px 0 14px 20px;
            font-style: italic;
            font-size: 11pt;
        }

        /* Tanda Tangan & QR Code */
        .ttd-section {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            page-break-inside: avoid;
        }

        .qr-box {
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 55%;
            border: 1px dashed #94a3b8;
            padding: 8px 12px;
            border-radius: 8px;
            background: #ffffff;
        }

        .qr-code svg {
            width: 85px;
            height: 85px;
        }

        .qr-info {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 7.5pt;
            color: #334155;
            line-height: 1.35;
        }

        .qr-info strong {
            color: #0f172a;
            display: block;
            margin-bottom: 2px;
        }

        .ttd-box {
            text-align: center;
            width: 210px;
        }

        .ttd-box .tempat-tgl {
            font-size: 11pt;
            margin-bottom: 6px;
        }

        .ttd-box .jabatan {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 65px;
        }

        .ttd-box .nama-pejabat {
            font-size: 11.5pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .ttd-box .nip-rt {
            font-size: 9.5pt;
            margin-top: 2px;
        }

        /* Gaya Khusus Mode Print */
        @media print {
            body {
                background: none;
                padding: 0;
            }

            .no-print-bar {
                display: none !important;
            }

            .sheet {
                box-shadow: none;
                padding: 0;
                margin: 0;
                width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>
<body>

    {{-- BAR AKSI ATAS (TIDAK IKUT DICETAK) --}}
    <div class="no-print-bar">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #16a34a;"></span>
            <span style="font-size: 13px; font-weight: 700; color: #0f172a;">
                Pratinjau Lembar Surat Resmi (A4)
            </span>
            <span style="font-size: 11px; padding: 2px 8px; border-radius: 6px; background: #f1f5f9; color: #475569; font-weight: 600;">
                No: {{ $letter->nomor_surat ?? 'DRAFT' }}
            </span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            <button onclick="window.close()" style="padding: 7px 14px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 12px; font-weight: 600; color: #334155; cursor: pointer;">
                Tutup
            </button>
            <button onclick="window.print()" style="padding: 7px 18px; background: #0B3A85; border: none; border-radius: 8px; font-size: 12px; font-weight: 700; color: #ffffff; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(11, 58, 133, 0.2);">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    {{-- LEMBAR KERTAS SURAT RESMI --}}
    <div class="sheet">

        {{-- 1. KOP SURAT RESMI --}}
        <div class="kop-wrapper">
            <img src="{{ asset('images/logo-icon.png') }}" alt="Logo RT 15" class="kop-logo" onerror="this.style.display='none'">
            <div class="kop-text">
                <div class="kop-instansi">
                    PEMERINTAH KOTA JAMBI<br>
                    KECAMATAN {{ strtoupper($setting->kecamatan ?? 'TELANAIPURA') }} &bull; KELURAHAN {{ strtoupper($setting->kelurahan ?? 'PEMATANG SULUR') }}
                </div>
                <div class="kop-rt">
                    RUKUN TETANGGA 15
                </div>
                <div class="kop-alamat">
                    Sekretariat: {{ $setting->alamat ?? 'Kampung Digital Pinang Masak Lestari, RT 15' }}<br>
                    Kota Jambi, Provinsi Jambi {{ $setting->kode_pos ? '- ' . $setting->kode_pos : '' }} &bull; Kontak: {{ $setting->telepon ?? '0812-7400-1515' }}
                </div>
            </div>
        </div>

        <div class="kop-border"></div>

        {{-- 2. JUDUL & NOMOR SURAT --}}
        <div class="judul-surat">
            <h1>{{ strtoupper($letter->letterType->nama ?? 'SURAT PENGANTAR') }}</h1>
            <div class="nomor">
                Nomor: <strong>{{ $letter->nomor_surat ?? '... / RT.15 / PMS / ... / ' . date('Y') }}</strong>
            </div>
        </div>

        {{-- 3. ISI SURAT PENGANTAR --}}
        <div class="isi-surat">
            <p>
                Yang bertanda tangan di bawah ini Ketua Rukun Tetangga (RT) 15 Kelurahan {{ $setting->kelurahan ?? 'Pematang Sulur' }}, Kecamatan {{ $setting->kecamatan ?? 'Telanaipura' }}, Kota Jambi, dengan ini menerangkan bahwa:
            </p>

            <table class="tabel-identitas">
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
                    <td class="value">{{ $letter->resident?->household?->no_kk ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="value">
                        {{ $letter->resident?->tempat_lahir ?? 'Jambi' }}, 
                        {{ $letter->resident?->tanggal_lahir ? $letter->resident->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td class="value">{{ ($letter->resident?->jenis_kelamin ?? 'L') === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td class="label">Agama</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $letter->resident?->agama ?? 'Islam' }}</td>
                </tr>
                <tr>
                    <td class="label">Pekerjaan</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $letter->resident?->pekerjaan ?: 'Wiraswasta' }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat / Tempat Tinggal</td>
                    <td class="separator">:</td>
                    <td class="value">
                        {{ $letter->resident?->household?->alamat ?? 'RT 15 Pematang Sulur' }}, 
                        Kel. {{ $setting->kelurahan ?? 'Pematang Sulur' }}, Kec. {{ $setting->kecamatan ?? 'Telanaipura' }}, Kota Jambi.
                    </td>
                </tr>
            </table>

            <p>
                Berdasarkan data kependudukan dan pencatatan kami, orang yang namanya tersebut di atas adalah benar-benar warga yang berdomisili sah dan bertempat tinggal di lingkungan RT 15 Kelurahan {{ $setting->kelurahan ?? 'Pematang Sulur' }}, Kecamatan {{ $setting->kecamatan ?? 'Telanaipura' }}, Kota Jambi.
            </p>

            <p style="margin-bottom: 4px;">
                Surat keterangan/pengantar ini diberikan kepada yang bersangkutan untuk keperluan:
            </p>
            <div class="kotak-keperluan">
                "{{ $letter->keperluan }}"
            </div>

            <p>
                Demikian surat pengantar ini dibuat dengan sebenarnya dan dengan penuh rasa tanggung jawab agar dapat dipergunakan sebagaimana mestinya oleh pihak yang berkepentingan.
            </p>
        </div>

        {{-- 4. PENGESAHAN & QR CODE --}}
        <div class="ttd-section">
            {{-- QR Code Validasi UU PDP --}}
            <div class="qr-box">
                <div class="qr-code">
                    {!! $qrCodeSvg !!}
                </div>
                <div class="qr-info">
                    <strong>DOKUMEN RESMI DIGITAL</strong>
                    Pindai QR Code untuk verifikasi keaslian surat ini secara online melalui Portal SIM-LiBas RT 15.<br>
                    <span style="color: #64748b; font-size: 6.5pt;">Token: {{ substr($letter->qr_token ?? 'TOKEN', 0, 16) }}...</span>
                </div>
            </div>

            {{-- Tanda Tangan Ketua RT --}}
            <div class="ttd-box">
                <div class="tempat-tgl">
                    Jambi, {{ $letter->tanggal_disetujui ? $letter->tanggal_disetujui->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                </div>
                <div class="jabatan">
                    Ketua RT 15 Pematang Sulur
                </div>
                <div class="nama-pejabat">
                    {{ $setting->nama_ketua_rt ?? 'H. Ahmad Sudirman, S.E.' }}
                </div>
                <div class="nip-rt">
                    Pengurus RT 15 Telanaipura
                </div>
            </div>
        </div>

    </div>

</body>
</html>
