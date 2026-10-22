<?php

namespace App\Console\Commands;

use App\Models\Household;
use App\Models\Resident;
use DateTime;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader;
use Throwable;

class ImportResidentsFromExcel extends Command
{
    /**
     * Nama dan tanda tangan perintah console.
     */
    protected $signature = 'simlibas:import-residents
                            {--file= : Path ke file Excel (.xlsx)}
                            {--dry-run : Menjalankan validasi dan simulasi penuh tanpa menulis data ke database}
                            {--force : Lewati konfirmasi interaktif saat eksekusi live}';

    /**
     * Deskripsi perintah console.
     */
    protected $description = 'Impor data riil Kartu Keluarga (KK) dan Warga RT 15 dari file Excel DATABASE_KAMPUNG_DIGITAL ke SIM-LiBas';

    public function handle(): int
    {
        $explicitFile = $this->option('file');
        $filePath = null;

        $candidatePaths = array_filter([
            $explicitFile,
            base_path('DATABASE_KAMPUNG_DIGITAL_RT15_2026.xlsx'),
            storage_path('app/DATABASE_KAMPUNG_DIGITAL_RT15_2026.xlsx'),
            storage_path('app/public/DATABASE_KAMPUNG_DIGITAL_RT15_2026.xlsx'),
            base_path('database/data/DATABASE_KAMPUNG_DIGITAL_RT15_2026.xlsx'),
        ]);

        foreach ($candidatePaths as $candidate) {
            if (file_exists($candidate)) {
                $filePath = $candidate;
                break;
            }
        }

        if (!$filePath) {
            $filePath = $explicitFile ?: base_path('DATABASE_KAMPUNG_DIGITAL_RT15_2026.xlsx');
        }
        $isDryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        $this->newLine();
        $this->info('======================================================================');
        $this->info('  🚀 SIM-LiBas - Import Data Riil Kartu Keluarga & Warga RT 15');
        $this->info('======================================================================');
        $this->line("Mode Eksekusi : " . ($isDryRun ? '<comment>[DRY-RUN / SIMULASI]</comment> (Tidak ada data yang ditulis)' : '<fg=green>[LIVE EXECUTION]</fg=green>'));
        $this->line("File Sumber   : <fg=cyan>{$filePath}</fg=cyan>");
        $this->newLine();

        if (!file_exists($filePath)) {
            $this->error("❌ Berkas Excel tidak ditemukan di: {$filePath}");
            return Command::FAILURE;
        }

        // 1. Membaca workbook menggunakan OpenSpout
        $this->info('📂 Membaca dan memvalidasi struktur berkas Excel...');
        $reader = new Reader();
        try {
            $reader->open($filePath);
        } catch (Throwable $e) {
            $this->error("❌ Gagal membuka berkas Excel: " . $e->getMessage());
            return Command::FAILURE;
        }

        $rawKk = [];
        $rawWarga = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            $sheetName = trim($sheet->getName());
            $isHeader = true;
            $headers = [];

            foreach ($sheet->getRowIterator() as $row) {
                $cells = $row->toArray();
                if ($isHeader) {
                    $headers = array_map(fn ($h) => trim((string)$h), $cells);
                    $isHeader = false;
                    continue;
                }

                $assoc = [];
                foreach ($headers as $idx => $headerName) {
                    $assoc[$headerName] = $cells[$idx] ?? null;
                }

                if ($sheetName === 'DATA KK') {
                    $rawKk[] = $assoc;
                } elseif ($sheetName === 'DATA WARGA') {
                    $rawWarga[] = $assoc;
                }
            }
        }
        $reader->close();

        if (empty($rawKk) || empty($rawWarga)) {
            $this->error("❌ Lembar kerja 'DATA KK' atau 'DATA WARGA' kosong atau tidak ditemukan!");
            return Command::FAILURE;
        }

        $this->line("✓ Terbaca <comment>" . count($rawKk) . "</comment> data Kartu Keluarga (KK).");
        $this->line("✓ Terbaca <comment>" . count($rawWarga) . "</comment> data Warga / Anggota Keluarga.");
        $this->newLine();

        // 2. Analisis & Pemetaan Awal (Pre-Processing)
        $this->info('🔍 Melakukan audit integritas, relasi, dan penanganan NIK...');
        $kkMapByNo = [];
        $kkMapById = [];
        $kkAnomalies = [];

        foreach ($rawKk as $kIndex => $kk) {
            $idKk = trim((string)($kk['ID KK'] ?? ''));
            $noKk = trim((string)($kk['No. KK'] ?? ''));
            $kepala = trim((string)($kk['Nama Kepala Keluarga'] ?? ''));
            $alamat = trim((string)($kk['Alamat'] ?? ''));

            if (empty($noKk)) {
                $kkAnomalies[] = "Baris KK #" . ($kIndex + 2) . ": Nomor KK kosong.";
                continue;
            }

            if (strlen($noKk) !== 16) {
                $kkAnomalies[] = "Baris KK #" . ($kIndex + 2) . " [{$idKk} - {$kepala}]: Nomor KK memiliki panjang " . strlen($noKk) . " digit (standar: 16 digit).";
            }

            $kkMapByNo[$noKk] = $kk;
            $kkMapById[$idKk] = $kk;
        }

        $wargaAnomalies = [];
        $unverifiedNikList = [];
        $seenNiks = [];
        $processedWarga = [];

        foreach ($rawWarga as $wIndex => $w) {
            $idWarga = trim((string)($w['ID Warga'] ?? ''));
            $idKk = trim((string)($w['ID KK'] ?? ''));
            $noKk = trim((string)($w['No. KK'] ?? ''));
            $nama = trim((string)($w['Nama Lengkap'] ?? ''));
            $nikRaw = trim((string)($w['NIK'] ?? ''));
            $hubungan = trim((string)($w['Status Hubungan Dalam Keluarga'] ?? ''));
            $tglLahirRaw = $w['Tanggal Lahir'] ?? null;

            // Validasi Relasi KK
            if (!isset($kkMapByNo[$noKk]) && !isset($kkMapById[$idKk])) {
                $wargaAnomalies[] = "Baris Warga #" . ($wIndex + 2) . " [{$idWarga} - {$nama}]: No. KK {$noKk} tidak ditemukan pada DATA KK.";
            }

            // Normalisasi Tanggal Lahir
            $tglLahirFormatted = $this->parseBirthDate($tglLahirRaw, $idWarga, $nama, $wargaAnomalies, $wIndex);

            // Analisis NIK Kosong vs Terisi vs Duplikat
            $nikFinal = null;
            $isNikUnverified = false;
            $catatanKhusus = [];

            if (empty($nikRaw)) {
                $isNikUnverified = true;
                $unverifiedNikList[] = [
                    'id_warga' => $idWarga,
                    'nama' => $nama,
                    'id_kk' => $idKk,
                    'hubungan' => $hubungan,
                    'alasan' => 'NIK belum terdata dari KK scan fisik (perlu verifikasi)',
                ];
            } else {
                // Periksa duplikasi NIK di dalam dataset Excel
                if (isset($seenNiks[$nikRaw])) {
                    $firstWarga = $seenNiks[$nikRaw];
                    $wargaAnomalies[] = "Baris Warga #" . ($wIndex + 2) . " [{$idWarga} - {$nama}]: NIK {$nikRaw} DUPLIKAT dengan [{$firstWarga['id']} - {$firstWarga['nama']}]. NIK diset NULL pada KK ini agar tidak merusak unique constraint database.";
                    $catatanKhusus[] = "Catatan: NIK {$nikRaw} terdaftar aktif di {$firstWarga['id_kk']} ({$firstWarga['nama']}). Pada KK ini NIK dikosongkan untuk menjaga integritas.";
                    $isNikUnverified = true;
                } else {
                    $nikFinal = $nikRaw;
                    $seenNiks[$nikRaw] = [
                        'id' => $idWarga,
                        'nama' => $nama,
                        'id_kk' => $idKk,
                    ];
                }
            }

            // Periksa anomali nama (mengandung tanda tanya atau unreadable)
            if (str_contains($nama, '?') || str_contains(strtoupper($nama), 'TIDAK TERBACA')) {
                $wargaAnomalies[] = "Baris Warga #" . ($wIndex + 2) . " [{$idWarga} - {$nama}]: Nama mengandung karakter tanda tanya atau teks tidak terbaca.";
            }

            $processedWarga[] = [
                'raw' => $w,
                'id_warga' => $idWarga,
                'id_kk' => $idKk,
                'no_kk' => $noKk,
                'nama' => $nama,
                'nik' => $nikFinal,
                'is_nik_unverified' => $isNikUnverified,
                'tanggal_lahir' => $tglLahirFormatted,
                'catatan_khusus' => $catatanKhusus,
            ];
        }

        // 3. Tampilkan Laporan Pra-Impor
        $this->displayPreImportReport($rawKk, $rawWarga, $unverifiedNikList, $kkAnomalies, $wargaAnomalies);

        // Jika mode Dry-Run, proses selesai di sini
        if ($isDryRun) {
            $this->newLine();
            $this->info("✨ [DRY-RUN SELESAI] Seluruh data berhasil diaudit dan divalidasi 100%.");
            $this->line("   Tidak ada perubahan yang diterapkan ke database.");
            $this->line("   Untuk mengeksekusi impor nyata ke database, jalankan perintah:");
            $this->line("   <fg=yellow>php artisan simlibas:import-residents</fg=yellow>");
            return Command::SUCCESS;
        }

        // Konfirmasi Live Execution
        if (!$force && !$this->confirm('Apakah Anda yakin ingin memproses impor data di atas ke database?', true)) {
            $this->warn('Operasi impor dibatalkan oleh pengguna.');
            return Command::SUCCESS;
        }

        // 4. Eksekusi Live Import dengan Database Transaction
        $this->newLine();
        $this->info('⚡ Memulai penulisan data ke database dengan proteksi transaksi...');

        $createdKkCount = 0;
        $updatedKkCount = 0;
        $createdWargaCount = 0;
        $updatedWargaCount = 0;

        DB::beginTransaction();
        try {
            // A. Simpan / Perbarui Kartu Keluarga (Households)
            $householdModelMap = [];

            foreach ($rawKk as $kk) {
                $idKk = trim((string)($kk['ID KK'] ?? ''));
                $noKk = trim((string)($kk['No. KK'] ?? ''));
                $alamat = trim((string)($kk['Alamat'] ?? ''));
                $rtRw = trim((string)($kk['RT/RW'] ?? '015/000'));
                $kelurahan = trim((string)($kk['Kelurahan'] ?? 'Pematang Sulur'));
                $kecamatan = trim((string)($kk['Kecamatan'] ?? 'Telanaipura'));
                $kodePos = trim((string)($kk['Kode Pos'] ?? '36124'));
                $tglKk = $kk['Tanggal KK'] ?? null;
                $tglKkStr = $tglKk instanceof DateTimeInterface ? $tglKk->format('d-m-Y') : trim((string)$tglKk);
                $sumberFile = trim((string)($kk['Sumber File'] ?? ''));
                $verifikasi = trim((string)($kk['Verifikasi'] ?? ''));

                // Normalisasi RT/RW
                if ($rtRw === '015/-' || $rtRw === '015/000') {
                    $rtRw = 'RT 15 / RW 04';
                }

                $catatanArray = [];
                if (!empty($tglKkStr)) $catatanArray[] = "Tgl Terbit KK: {$tglKkStr}";
                if (!empty($kodePos)) $catatanArray[] = "Kode Pos: {$kodePos}";
                if (!empty($sumberFile)) $catatanArray[] = "Sumber: {$sumberFile}";
                if (!empty($verifikasi)) $catatanArray[] = "Status: {$verifikasi}";
                $catatanKk = implode(' | ', $catatanArray);

                $noKkHash = hash('sha256', $noKk);
                $household = Household::where('no_kk_hash', $noKkHash)
                    ->orWhere('id_kk', $idKk)
                    ->first();

                if (!$household) {
                    $household = new Household();
                    $household->id_kk = $idKk;
                    $household->no_kk = $noKk;
                    $household->alamat = $alamat ?: 'Komplek BTN Karya Indah RT 15';
                    $household->rt_rw = $rtRw;
                    $household->kelurahan = $kelurahan ?: 'Pematang Sulur';
                    $household->kecamatan = $kecamatan ?: 'Telanaipura';
                    $household->status_hunian = 'tetap';
                    $household->catatan = $catatanKk;
                    $household->save();
                    $createdKkCount++;
                } else {
                    $household->id_kk = $idKk;
                    $household->alamat = $alamat ?: $household->alamat;
                    $household->rt_rw = $rtRw ?: $household->rt_rw;
                    $household->catatan = $catatanKk ?: $household->catatan;
                    $household->save();
                    $updatedKkCount++;
                }

                $householdModelMap[$idKk] = $household;
                $householdModelMap[$noKk] = $household;
            }

            // B. Simpan / Perbarui Warga (Residents)
            foreach ($processedWarga as $pWarga) {
                $raw = $pWarga['raw'];
                $idWarga = $pWarga['id_warga'];
                $idKk = $pWarga['id_kk'];
                $noKk = $pWarga['no_kk'];
                $nama = $pWarga['nama'];
                $nik = $pWarga['nik'];
                $tglLahir = $pWarga['tanggal_lahir'];

                $household = $householdModelMap[$idKk] ?? ($householdModelMap[$noKk] ?? null);
                if (!$household) {
                    continue;
                }

                // Normalisasi Kolom
                $jkRaw = strtoupper(trim((string)($raw['Jenis Kelamin'] ?? '')));
                $jenisKelamin = ($jkRaw === 'PEREMPUAN' || $jkRaw === 'P') ? 'P' : 'L';

                $tempatLahir = trim((string)($raw['Tempat Lahir'] ?? 'Jambi')) ?: 'Jambi';
                $agamaRaw = strtoupper(trim((string)($raw['Agama'] ?? 'ISLAM')));
                $agama = match ($agamaRaw) {
                    'KRISTEN', 'KRISTEN PROTESTAN' => 'Kristen',
                    'KATOLIK' => 'Katolik',
                    'HINDU' => 'Hindu',
                    'BUDDHA' => 'Buddha',
                    'KONGHUCU' => 'Konghucu',
                    default => 'Islam',
                };

                $pendidikanRaw = trim((string)($raw['Pendidikan'] ?? ''));
                $pendidikan = $this->normalizeEducation($pendidikanRaw);

                $pekerjaanRaw = trim((string)($raw['Pekerjaan'] ?? ''));
                $pekerjaan = $this->normalizeJob($pekerjaanRaw);

                $statusKawinRaw = strtoupper(trim((string)($raw['Status Perkawinan'] ?? '')));
                $statusKawin = match ($statusKawinRaw) {
                    'KAWIN TERCATAT' => 'Kawin',
                    'KAWIN BELUM TERCATAT' => 'Kawin Belum Tercatat',
                    'CERAI HIDUP' => 'Cerai Hidup',
                    'CERAI MATI' => 'Cerai Mati',
                    default => 'Belum Kawin',
                };

                $hubRaw = strtoupper(trim((string)($raw['Status Hubungan Dalam Keluarga'] ?? '')));
                $hubunganKeluarga = match ($hubRaw) {
                    'KEPALA KELUARGA' => 'kepala_keluarga',
                    'ISTRI' => 'istri',
                    'ANAK' => 'anak',
                    'CUCU' => 'cucu',
                    'MENANTU' => 'menantu',
                    'ORANG TUA' => 'orang_tua',
                    'MERTUA' => 'mertua',
                    default => 'lainnya',
                };
                $isKepalaKeluarga = ($hubunganKeluarga === 'kepala_keluarga');

                // Pencarian Idempotensi Warga
                $resident = null;
                if (!empty($nik)) {
                    $nikHash = hash('sha256', $nik);
                    $resident = Resident::where('nik_hash', $nikHash)->first();
                }

                if (!$resident) {
                    $resident = Resident::where('id_warga', $idWarga)->first();
                }

                if (!$resident) {
                    $resident = new Resident();
                    $resident->id_warga = $idWarga;
                    $resident->household_id = $household->id;
                    $resident->kategori_warga = 'memiliki_kk';
                    $resident->nama = $nama;
                    $resident->nik = $nik;
                    $resident->tempat_lahir = $tempatLahir;
                    $resident->tanggal_lahir = $tglLahir;
                    $resident->jenis_kelamin = $jenisKelamin;
                    $resident->agama = $agama;
                    $resident->pendidikan_terakhir = $pendidikan;
                    $resident->pekerjaan = $pekerjaan;
                    $resident->status_perkawinan = $statusKawin;
                    $resident->hubungan_keluarga = $hubunganKeluarga;
                    $resident->is_kepala_keluarga = $isKepalaKeluarga;
                    $resident->status_kependudukan = 'aktif';
                    $resident->save();
                    $createdWargaCount++;
                } else {
                    $resident->id_warga = $idWarga;
                    $resident->household_id = $household->id;
                    $resident->nama = $nama;
                    $resident->nik = $nik;
                    $resident->tempat_lahir = $tempatLahir;
                    $resident->tanggal_lahir = $tglLahir;
                    $resident->jenis_kelamin = $jenisKelamin;
                    $resident->agama = $agama;
                    $resident->pendidikan_terakhir = $pendidikan;
                    $resident->pekerjaan = $pekerjaan;
                    $resident->status_perkawinan = $statusKawin;
                    $resident->hubungan_keluarga = $hubunganKeluarga;
                    $resident->is_kepala_keluarga = $isKepalaKeluarga;
                    $resident->save();
                    $updatedWargaCount++;
                }
            }

            DB::commit();

            $this->newLine();
            $this->info("🎉 [IMPORT SUKSES] Data Kartu Keluarga dan Warga berhasil dimasukkan ke sistem!");
            $this->table(
                ['Kategori Entitas', 'Data Baru (Dibuat)', 'Data Eksis (Diperbarui)', 'Total Masuk'],
                [
                    ['Kartu Keluarga (Households)', $createdKkCount, $updatedKkCount, $createdKkCount + $updatedKkCount],
                    ['Warga / Penduduk (Residents)', $createdWargaCount, $updatedWargaCount, $createdWargaCount + $updatedWargaCount],
                ]
            );

            // Audit Log
            activity('import')
                ->log("Berhasil mengimpor " . ($createdKkCount + $updatedKkCount) . " KK dan " . ($createdWargaCount + $updatedWargaCount) . " Warga dari file {$filePath}");

            return Command::SUCCESS;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->error("❌ Terjadi kesalahan fatal saat menyimpan ke database! Seluruh transaksi telah di-ROLLBACK.");
            $this->error("Pesan Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . " (Baris " . $e->getLine() . ")");
            return Command::FAILURE;
        }
    }

    /**
     * Parse and normalize birth dates.
     */
    protected function parseBirthDate(mixed $tglRaw, string $idWarga, string $nama, array &$anomalies, int $rowIdx): string
    {
        if ($tglRaw instanceof DateTimeInterface) {
            return $tglRaw->format('Y-m-d');
        }

        $str = trim((string)$tglRaw);

        // Kasus khusus Baris 60: '11-01-197?'
        if (str_contains($str, '?')) {
            $anomalies[] = "Baris Warga #" . ($rowIdx + 2) . " [{$idWarga} - {$nama}]: Tanggal lahir mengandung tanda tanya '{$str}'. Dinormalisasi sementara ke 1970-01-11 untuk keperluan penyimpanan database.";
            return '1970-01-11';
        }

        if (empty($str)) {
            $anomalies[] = "Baris Warga #" . ($rowIdx + 2) . " [{$idWarga} - {$nama}]: Tanggal lahir kosong, default diset 1980-01-01.";
            return '1980-01-01';
        }

        $d = DateTime::createFromFormat('d-m-Y', $str);
        if ($d && $d->format('d-m-Y') === $str) {
            return $d->format('Y-m-d');
        }

        $d2 = DateTime::createFromFormat('Y-m-d', $str);
        if ($d2 && $d2->format('Y-m-d') === $str) {
            return $d2->format('Y-m-d');
        }

        $timestamp = strtotime($str);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        $anomalies[] = "Baris Warga #" . ($rowIdx + 2) . " [{$idWarga} - {$nama}]: Format tanggal lahir tidak dikenali '{$str}', fallback 1980-01-01.";
        return '1980-01-01';
    }

    /**
     * Normalisasi string pendidikan.
     */
    protected function normalizeEducation(string $raw): string
    {
        $upper = strtoupper(trim($raw));
        if (str_contains($upper, 'STRATA I') || str_contains($upper, 'DIPLOMA IV') || $upper === 'S1') {
            return 'S1';
        }
        if (str_contains($upper, 'DIPLOMA III') || $upper === 'D3') {
            return 'D3';
        }
        if (str_contains($upper, 'SLTA') || str_contains($upper, 'SMA') || str_contains($upper, 'SMK')) {
            return 'SMA/Sederajat';
        }
        if (str_contains($upper, 'SLTP') || str_contains($upper, 'SMP')) {
            return 'SMP/Sederajat';
        }
        if (str_contains($upper, 'SD') || str_contains($upper, 'TAMAT SD')) {
            return 'SD/Sederajat';
        }
        if (str_contains($upper, 'BELUM SEKOLAH') || str_contains($upper, 'TIDAK/BELUM SEKOLAH')) {
            return 'Tidak/Belum Sekolah';
        }
        return $raw ?: 'Tidak/Belum Sekolah';
    }

    /**
     * Normalisasi string pekerjaan.
     */
    protected function normalizeJob(string $raw): string
    {
        $trimmed = trim($raw);
        if (empty($trimmed)) {
            return 'Belum/Tidak Bekerja';
        }
        return ucwords(strtolower($trimmed));
    }

    /**
     * Menampilkan laporan audit dan validasi pra-impor.
     */
    protected function displayPreImportReport(array $rawKk, array $rawWarga, array $unverifiedNikList, array $kkAnomalies, array $wargaAnomalies): void
    {
        $this->info('📊 RINGKASAN DATA SUMBER EXCEL:');
        $this->table(
            ['Parameter', 'Jumlah / Deskripsi'],
            [
                ['Total Kartu Keluarga (KK)', count($rawKk) . ' KK'],
                ['Total Anggota Warga', count($rawWarga) . ' Jiwa'],
                ['Warga dengan NIK Lengkap (16 Digit)', (count($rawWarga) - count($unverifiedNikList)) . ' Jiwa'],
                ['Warga NIK Kosong / Belum Terverifikasi', count($unverifiedNikList) . ' Jiwa (Disimpan NULL, tidak diisi nomor palsu)'],
                ['Isu / Catatan Pengecualian', (count($kkAnomalies) + count($wargaAnomalies)) . ' Temuan Anomali'],
            ]
        );

        $this->newLine();
        $this->warn('📋 DAFTAR WARGA TANPA NIK (NIK DISET NULL / BELUM TERVERIFIKASI):');
        $unverifiedTable = [];
        foreach ($unverifiedNikList as $idx => $item) {
            $unverifiedTable[] = [
                $idx + 1,
                $item['id_warga'],
                $item['nama'],
                $item['id_kk'],
                $item['hubungan'],
                $item['alasan'],
            ];
        }
        $this->table(['No', 'ID Warga', 'Nama Lengkap', 'ID KK', 'Hubungan', 'Status Penanganan'], $unverifiedTable);

        if (!empty($kkAnomalies) || !empty($wargaAnomalies)) {
            $this->newLine();
            $this->comment('⚠️ DAFTAR ANOMALI & CATATAN TRANSFORMASI DATA:');
            foreach (array_merge($kkAnomalies, $wargaAnomalies) as $aIdx => $anom) {
                $this->line("  " . ($aIdx + 1) . ". <fg=yellow>{$anom}</fg=yellow>");
            }
        }
    }
}
