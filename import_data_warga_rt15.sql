-- =====================================================================
-- SCRIPT IMPORT DATA RIIL KARTU KELUARGA & WARGA RT 15 PEMATANG SULUR
-- Target Sistem: SIM-LiBas (Production Environment)
-- Sumber Data  : DATABASE_KAMPUNG_DIGITAL_RT15_2026.xlsx
-- Generated At : 2026-10-22 19:57:04
-- Total Data   : 19 Kartu Keluarga, 59 Warga
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;

-- ---------------------------------------------------------------------
-- 1. INSERT DATA KARTU KELUARGA (Tabel `households`)
-- ---------------------------------------------------------------------
INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK001', '1571011508160002', SHA2('1571011508160002', 256), 'KOMPLEK BTN KARYA INDAH BLOK A NO.2', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 06-04-2021 | Kode Pos: 36124 | Sumber: A02 Mohd Dody Setiawan.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK002', '1571010904120024', SHA2('1571010904120024', 256), 'KOMP. BTN KARYA INDAH BLOK A/4', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 08-09-2023 | Kode Pos: 36124 | Sumber: A04 Deasy Mudahar.pdf | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK003', '3209120904120017', SHA2('3209120904120017', 256), 'KOMPLEK BTN KARYA INDAH BLOK A NO.4', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 31-03-2021 | Kode Pos: 36124 | Sumber: A04 Rully Sondri.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK004', '15710103140002', SHA2('15710103140002', 256), 'KOMPT. BTN KARYA INDAH JL. YULIUS USMAN', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 29-04-2025 | Kode Pos: 36124 | Sumber: A05 Anderiyanto.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK005', '1571010606070047', SHA2('1571010606070047', 256), 'KOMPLEK BTN KARYA INDAH BLOK A-5', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 01-04-2024 | Kode Pos: 36124 | Sumber: A05 Magdalena Maria.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK006', '3308040403140001', SHA2('3308040403140001', 256), 'BTN KARYA INDAH BLOK A NO.06', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 10-06-2024 | Kode Pos: 36124 | Sumber: A06 Imam Agus Sutopo.pdf | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK007', '1571011609110006', SHA2('1571011609110006', 256), 'KOMPLEK BTN KARYA INDAH A/7', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 18-08-2017 | Kode Pos: 36124 | Sumber: A07 Sri Mulyani Evautari.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK008', '1571011612050029', SHA2('1571011612050029', 256), 'KOMP. BTN KARYA INDAH BLOK A. NO.0', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 02-09-2024 | Kode Pos: 36124 | Sumber: A08 Marjohan.pdf | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK009', '1571010805060015', SHA2('1571010805060015', 256), 'KOMPLEK BTN KARYA INDAH BLOK A.NO.09', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 02-05-2025 | Kode Pos: 36124 | Sumber: A09 Chandra.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK010', '1571012209210001', SHA2('1571012209210001', 256), 'KOMPLEK BTN KARYA INDAH NO.10 A', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 23-09-2021 | Kode Pos: 36124 | Sumber: A10 Martini.pdf | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK011', '1571010611060004', SHA2('1571010611060004', 256), 'KOMP. BTN JL. YULIUS USMAN BLOK A.11', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 21-10-2019 | Kode Pos: 36124 | Sumber: A11 Rd Kasim.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK012', '1571010503250001', SHA2('1571010503250001', 256), 'KOMPLEKS KARYA INDAH A12', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 11-06-2025 | Kode Pos: 36124 | Sumber: A12 Joko Susanto.jpg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK013', '1571011206070045', SHA2('1571011206070045', 256), 'KOMPLEKS KARYA INDAH A12', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 26-11-2020 | Kode Pos: 36124 | Sumber: A12 Syukur.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK014', '1571010107210001', SHA2('1571010107210001', 256), 'KOMP. BTN KARYA INDAH BLOK A NO.13', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Kode Pos: 36124 | Sumber: A13 Samsiah.pdf | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK015', '1571012309200003', SHA2('1571012309200003', 256), 'KOMP. BTN NO.13 BLOK A', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 23-09-2020 | Kode Pos: 36124 | Sumber: A13 Sarjiah.pdf | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK016', '1571012607060023', SHA2('1571012607060023', 256), 'KOMP. BTN KARYA INDAH BLOK A NO.14', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 29-07-2024 | Kode Pos: 36124 | Sumber: A14 Asnar Zuandi.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK017', '1571011811140014', SHA2('1571011811140014', 256), 'KOMP. BTN KARYA INDAH BLOK A NO.14', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 30-05-2024 | Kode Pos: 36124 | Sumber: A14 Iwan Setiawan.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK018', '1571011202150001', SHA2('1571011202150001', 256), 'KOMP. BTN KARYA INDAH BLOK C NO.14', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 09-03-2023 | Kode Pos: 36124 | Sumber: A14 Zulkifli.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK019', '1571011801120002', SHA2('1571011801120002', 256), 'KOMPLEK BTN KARYA INDAH NO. A17', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Tgl Terbit KK: 01-11-2022 | Kode Pos: 36124 | Sumber: A17 Yanriansyah Siregar.jpeg | Status: Lengkap dari scan', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

-- ---------------------------------------------------------------------
-- 2. INSERT DATA WARGA / PENDUDUK (Tabel `residents`)
-- ---------------------------------------------------------------------
INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W001', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK001' LIMIT 1), 'memiliki_kk', NULL, NULL, 'MOHD DODY SETIAWAN', 'JAMBI', '1963-04-06', 'L', 'Islam', 'D3', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W002', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK001' LIMIT 1), 'memiliki_kk', NULL, NULL, 'MAYASARI', 'JAMBI', '1985-04-24', 'P', 'Islam', 'S1', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W003', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK001' LIMIT 1), 'memiliki_kk', NULL, NULL, 'AHMAD B?', 'JAMBI', '2018-09-03', 'L', 'Islam', 'Tidak/Belum Sekolah', 'Belum/tidak Bekerja', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W004', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK001' LIMIT 1), 'memiliki_kk', NULL, NULL, 'ANNISA SYAHNUNI', 'JAMBI', '2021-05-13', 'P', 'Islam', 'Tidak/Belum Sekolah', 'Belum/tidak Bekerja', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W005', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK002' LIMIT 1), 'memiliki_kk', '1571011003680041', SHA2('1571011003680041', 256), 'EDWIN EDWARD RUMAJAR', 'MANADO', '1968-03-10', 'L', 'Islam', 'D3', 'Karyawan Swasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W006', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK002' LIMIT 1), 'memiliki_kk', '1571016901750061', SHA2('1571016901750061', 256), 'DEASY MUDAHAR', 'JAMBI', '1975-01-29', 'P', 'Islam', 'SMA/Sederajat', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W007', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK002' LIMIT 1), 'memiliki_kk', '1571011702030061', SHA2('1571011702030061', 256), 'YUWANA FARHAN RUMAJAR', 'JAMBI', '2003-02-17', 'L', 'Islam', 'SMP/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W008', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK002' LIMIT 1), 'memiliki_kk', '1571010407070081', SHA2('1571010407070081', 256), 'RAHADI GERALD RUMAJAR', 'JAMBI', '2007-07-04', 'L', 'Islam', 'SD/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W009', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK002' LIMIT 1), 'memiliki_kk', '1571016512120002', SHA2('1571016512120002', 256), 'GANIAH QUMAIRAH RUMAJAR', 'JAMBI', '2012-12-25', 'P', 'Islam', 'SD/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W010', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK002' LIMIT 1), 'memiliki_kk', '1571016905160002', SHA2('1571016905160002', 256), 'RAISA RUMAJAR', 'JAMBI', '2016-05-29', 'P', 'Islam', 'Tidak/Belum Sekolah', 'Belum/tidak Bekerja', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W011', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK002' LIMIT 1), 'memiliki_kk', '1571011409170005', SHA2('1571011409170005', 256), 'RAZZAN RAHARJA RUMAJAR', 'JAMBI', '2017-09-14', 'L', 'Islam', 'Tidak/Belum Sekolah', 'Belum/tidak Bekerja', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W012', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK003' LIMIT 1), 'memiliki_kk', NULL, NULL, 'RULLY SONDRI MUDAHAR', 'JAMBI', '1977-10-10', 'L', 'Islam', 'D3', 'Karyawan Swasta', 'Cerai Hidup', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W013', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK004' LIMIT 1), 'memiliki_kk', '1571011401740021', SHA2('1571011401740021', 256), 'ANDERIYANTO', 'JAMBI', '1974-01-14', 'L', 'Islam', 'SMA/Sederajat', 'Buruh Harian Lepas', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W014', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK004' LIMIT 1), 'memiliki_kk', '1571014707810041', SHA2('1571014707810041', 256), 'FRANSISCA PRIMA WENDA PANGGABEAN', 'JAMBI', '1981-07-07', 'P', 'Islam', 'SMA/Sederajat', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W015', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK005' LIMIT 1), 'memiliki_kk', '1571014403540001', SHA2('1571014403540001', 256), 'MAGDALENA MARIA', 'PALEMBANG', '1954-03-04', 'P', 'Kristen', 'SMA/Sederajat', 'Pensiunan', 'Cerai Mati', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W016', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK005' LIMIT 1), 'memiliki_kk', '1571010403840102', SHA2('1571010403840102', 256), 'FREDY PANDAPOTAN PANGGABEAN', 'JAMBI', '1984-03-04', 'L', 'Kristen', 'SMA/Sederajat', 'Karyawan Swasta', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W017', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK005' LIMIT 1), 'memiliki_kk', '1571010403840101', SHA2('1571010403840101', 256), 'FRENGKY PARLINDUNGAN PANGGABEAN', 'JAMBI', '1984-03-04', 'L', 'Kristen', 'SMA/Sederajat', 'Karyawan Swasta', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W018', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK006' LIMIT 1), 'memiliki_kk', '3308042302780002', SHA2('3308042302780002', 256), 'M IMAM AGUS SUTOPO', 'SEMARANG', '1978-02-23', 'L', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin Belum Tercatat', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W019', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK006' LIMIT 1), 'memiliki_kk', '3308044704720004', SHA2('3308044704720004', 256), 'SRI WILISYATI', 'MAGELANG', '1972-04-07', 'P', 'Islam', 'SMA/Sederajat', 'Anggota Lembaga Tinggi Lainnya', 'Kawin Belum Tercatat', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W020', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK006' LIMIT 1), 'memiliki_kk', '3308041901130001', SHA2('3308041901130001', 256), 'MUHAMMAD MELVIN NOHAN SADUBY', 'MAGELANG', '2013-01-19', 'L', 'Islam', 'SD/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W021', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK007' LIMIT 1), 'memiliki_kk', '1571015106520001', SHA2('1571015106520001', 256), 'SRI MULYANI EVAUTARI', 'PLAJU PALEMBANG', '1952-06-01', 'P', 'Islam', 'D3', 'Pensiunan', 'Cerai Mati', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W022', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK007' LIMIT 1), 'memiliki_kk', '1571074111990001', SHA2('1571074111990001', 256), 'MARISA FITRIA', 'JAMBI', '1999-11-09', 'P', 'Islam', 'SMP/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'cucu', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W023', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK007' LIMIT 1), 'memiliki_kk', '1571072910020001', SHA2('1571072910020001', 256), 'MOCH. ATHIRRA OKADIKSI', 'JAMBI', '2002-10-29', 'L', 'Islam', 'SD/Sederajat', 'Belum/tidak Bekerja', 'Belum Kawin', 'cucu', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W024', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK008' LIMIT 1), 'memiliki_kk', '1571011504650041', SHA2('1571011504650041', 256), 'MARJOHAN', 'KERINCI', '1965-04-15', 'L', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W025', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK008' LIMIT 1), 'memiliki_kk', '1571015604710001', SHA2('1571015604710001', 256), 'LINDA SARTIKA', 'KERINCI', '1971-04-16', 'P', 'Islam', 'SMA/Sederajat', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W026', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK008' LIMIT 1), 'memiliki_kk', '1571012107000001', SHA2('1571012107000001', 256), 'FADLAN FADILLAH JOHANDA', 'JAMBI', '2000-07-21', 'L', 'Islam', 'S1', 'Wiraswasta', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W027', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK008' LIMIT 1), 'memiliki_kk', '1571014509060041', SHA2('1571014509060041', 256), 'SITI ZAMILA WAHYUNA', 'JAMBI', '2006-09-05', 'P', 'Islam', 'SMA/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W028', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK009' LIMIT 1), 'memiliki_kk', '1571011701720001', SHA2('1571011701720001', 256), 'CHANDRA CRISMAN SENG', 'JAMBI', '1972-01-17', 'L', 'Buddha', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W029', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK009' LIMIT 1), 'memiliki_kk', '1571015605790001', SHA2('1571015605790001', 256), 'ELVY CUMINAH', 'KUALA TUNGKAL', '1979-05-19', 'P', 'Buddha', 'SMA/Sederajat', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W030', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK009' LIMIT 1), 'memiliki_kk', '1571011108010021', SHA2('1571011108010021', 256), 'DELPHIN PHILO CHANDRA', 'JAMBI', '2001-08-11', 'L', 'Buddha', 'SMA/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W031', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK009' LIMIT 1), 'memiliki_kk', '1571012308040001', SHA2('1571012308040001', 256), 'DERIONIEL PHILO CHANDRA', 'JAMBI', '2004-08-23', 'L', 'Buddha', 'SMP/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W032', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK009' LIMIT 1), 'memiliki_kk', '1571012209060001', SHA2('1571012209060001', 256), 'HAGGIE PHILO CHANDRA', 'JAMBI', '2006-09-22', 'L', 'Buddha', 'SMP/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W033', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK010' LIMIT 1), 'memiliki_kk', '1571014803480001', SHA2('1571014803480001', 256), 'MARTINI', 'BANTUL YOGYAKARTA', '1948-03-08', 'P', 'Islam', 'SD/Sederajat', 'Mengurus Rumah Tangga', 'Cerai Mati', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W034', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK010' LIMIT 1), 'memiliki_kk', NULL, NULL, 'NAMA TIDAK TERBACA', 'BANTUL YOGYAKARTA', '1972-06-10', 'P', 'Islam', 'SMP/Sederajat', 'Wiraswasta', 'Kawin Belum Tercatat', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W035', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK010' LIMIT 1), 'memiliki_kk', '1571016712990021', SHA2('1571016712990021', 256), 'RINA DANIATI', 'JAMBI', '1999-12-27', 'P', 'Islam', 'SMP/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'cucu', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W036', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK011' LIMIT 1), 'memiliki_kk', '1571011008440021', SHA2('1571011008440021', 256), 'RD. KASIM', 'JAMBI', '1944-08-10', 'L', 'Islam', 'SD/Sederajat', 'Buruh Harian Lepas', 'Cerai Mati', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W037', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK012' LIMIT 1), 'memiliki_kk', NULL, NULL, 'JOKO PURWANTO', 'KASANG PUDAK', '1990-03-06', 'L', 'Islam', 'SMA/Sederajat', 'Buruh Harian Lepas', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W038', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK012' LIMIT 1), 'memiliki_kk', '1571015211950022', SHA2('1571015211950022', 256), 'KIKI SUHARTI', 'JAMBI', '1995-11-12', 'P', 'Islam', 'S1', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W039', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK012' LIMIT 1), 'memiliki_kk', '1571013005250002', SHA2('1571013005250002', 256), 'SHAFWAN AL BARA', 'JAMBI', '2025-05-30', 'L', 'Islam', 'Tidak/Belum Sekolah', 'Belum/tidak Bekerja', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W040', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK013' LIMIT 1), 'memiliki_kk', '1571010111620021', SHA2('1571010111620021', 256), 'SYUKUR', 'JAMBI', '1962-11-01', 'L', 'Islam', 'SMA/Sederajat', 'Pensiunan', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W041', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK013' LIMIT 1), 'memiliki_kk', '1571014407720121', SHA2('1571014407720121', 256), 'KARMIYATI', 'JAMBI', '1972-07-04', 'P', 'Islam', 'SMP/Sederajat', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W042', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK013' LIMIT 1), 'memiliki_kk', NULL /* Duplikat NIK 1571015211950022 (sudah tercatat di W038) */, NULL, 'KIKI SUHARTI', 'JAMBI', '1995-11-12', 'P', 'Islam', 'S1', 'Belum/tidak Bekerja', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W043', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK013' LIMIT 1), 'memiliki_kk', '1571015809990061', SHA2('1571015809990061', 256), 'VIRA HANIF', 'JAMBI', '1999-09-18', 'P', 'Islam', 'SMA/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W044', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK013' LIMIT 1), 'memiliki_kk', '1571016704030041', SHA2('1571016704030041', 256), 'ANNISA APRILIA', 'JAMBI', '2003-04-27', 'P', 'Islam', 'SMP/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W045', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK015' LIMIT 1), 'memiliki_kk', '1503034507760008', SHA2('1503034507760008', 256), 'SARJIAH', 'JAMBI', '1976-07-05', 'P', 'Islam', 'SMA/Sederajat', 'Pedagang/wiraswasta', 'Cerai Mati', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W046', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK015' LIMIT 1), 'memiliki_kk', '1571010202090001', SHA2('1571010202090001', 256), 'MUHAMMAD ALQAFI? NUGROHO', 'JAMBI', '2008-02-20', 'L', 'Islam', 'SD/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W047', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK016' LIMIT 1), 'memiliki_kk', '1571011406550001', SHA2('1571011406550001', 256), 'ASNAR ZUANDI', 'PALEMBANG', '1955-06-14', 'L', 'Islam', 'SMP/Sederajat', 'Buruh Tani/perkebunan', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W048', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK016' LIMIT 1), 'memiliki_kk', '1571016404630001', SHA2('1571016404630001', 256), 'RUSMINAH', 'PALEMBANG', '1963-04-24', 'P', 'Islam', 'SMP/Sederajat', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W049', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK017' LIMIT 1), 'memiliki_kk', NULL, NULL, 'IWAN SETIAWAN', 'JAMBI', '1980-10-21', 'L', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W050', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK017' LIMIT 1), 'memiliki_kk', NULL, NULL, 'ELI PATMAH', 'BREBES', '1987-03-02', 'P', 'Islam', 'SMP/Sederajat', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W051', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK017' LIMIT 1), 'memiliki_kk', NULL, NULL, 'ENISHA GHANIYYA SETIAWAN', 'JAMBI', '2014-09-29', 'P', 'Islam', 'SD/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W052', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK017' LIMIT 1), 'memiliki_kk', '1571018405240001', SHA2('1571018405240001', 256), 'ALEENA NAIKA SETIAWAN', 'JAMBI', '2024-05-24', 'P', 'Islam', 'Tidak/Belum Sekolah', 'Belum/tidak Bekerja', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W053', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK018' LIMIT 1), 'memiliki_kk', '1571072610860021', SHA2('1571072610860021', 256), 'ZULKIFLI', 'JAMBI', '1986-10-26', 'L', 'Islam', 'SMA/Sederajat', 'Karyawan Swasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W054', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK018' LIMIT 1), 'memiliki_kk', '1571015408680041', SHA2('1571015408680041', 256), 'DEWI AGUSTINI', 'JAMBI', '1988-08-14', 'P', 'Islam', 'Tidak/Belum Sekolah', 'Mengurus Rumah Tangga', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W055', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK018' LIMIT 1), 'memiliki_kk', '1571016505150002', SHA2('1571016505150002', 256), 'MEISYA SHAHIA PUTRI', 'JAMBI', '2015-05-25', 'P', 'Islam', 'SD/Sederajat', 'Pelajar/mahasiswa', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W056', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK018' LIMIT 1), 'memiliki_kk', '1571014602190001', SHA2('1571014602190001', 256), 'FEBRISIA AYEESA', 'JAMBI', '2019-02-06', 'P', 'Islam', 'Tidak/Belum Sekolah', 'Belum/tidak Bekerja', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W057', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK018' LIMIT 1), 'memiliki_kk', '1571010110200001', SHA2('1571010110200001', 256), 'ZIDAN WILIANSYAH', 'JAMBI', '2020-10-01', 'L', 'Islam', 'Tidak/Belum Sekolah', 'Belum/tidak Bekerja', 'Belum Kawin', 'anak', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W058', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK019' LIMIT 1), 'memiliki_kk', NULL, NULL, 'YANRIANSYAH SIREGAR', 'JAMBI', '1972-01-01', 'L', 'Islam', 'S1', 'Karyawan Swasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `created_at`, `updated_at`)
VALUES ('W059', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK019' LIMIT 1), 'memiliki_kk', NULL, NULL, 'MANDARINA REHARA HERATIO WAROUW', 'BELING CHINA', '1970-01-11', 'P', 'Islam', 'D3', 'Karyawan Swasta', 'Kawin', 'istri', 0, 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `tanggal_lahir` = VALUES(`tanggal_lahir`), `pendidikan_terakhir` = VALUES(`pendidikan_terakhir`), `pekerjaan` = VALUES(`pekerjaan`), `updated_at` = NOW();

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- SELESAI: 19 KK dan 59 Warga Berhasil Disiapkan.
-- =====================================================================
