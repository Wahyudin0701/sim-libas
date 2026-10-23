-- =====================================================================
-- SCRIPT INJEKSI DATA UMKM WARGA RT 15 PEMATANG SULUR
-- Target Sistem: SIM-LiBas (Production Environment)
-- Sumber Data  : Pendataan UMKM Warga RT 15 (8 Usaha Terverifikasi)
-- Generated At : 2026-10-23 08:49:45
-- Catatan Penting:
-- 1. Script ini idempotent (dapat dijalankan berulang tanpa duplikasi).
-- 2. Menggunakan subquery dinamis id_kk agar aman dari disparitas ID auto-increment.
-- 3. Seluruh nomor telepon/WA disimpan sebagai VARCHAR dengan angka 0 di awal utuh.
-- 4. Media sosial dan marketplace disimpan dalam JSON social_links valid.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;

-- ---------------------------------------------------------------------
-- 1. DATA KARTU KELUARGA (Tabel `households`)
-- ---------------------------------------------------------------------
INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK020', 'eyJpdiI6IkN6Z0RaZ3ZEbS8zdnJqTlNUL3pGRUE9PSIsInZhbHVlIjoiUzVmL3lIT3YvZWh5VjhDdUJpTnhrV2o2cHFiUWpUMURSS0xJVFFWVjMxcz0iLCJtYWMiOiI3YTJjMzNlNzkzYTZlMzFlOWU4ZGFjNDEyODFjMzY4MzcwOTA1MDhjZjNlMWYzNDY0NWMyY2Y0ZmQ1NDI2NGUzIiwidGFnIjoiIn0=', '4a71d9dd35d614daa7e06ca40037e42eae24555e2b80facf19f63f8d69e02721', 'Komp. BTN Karya Indah Blok E 7', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Registrasi Kartu Keluarga Warga RT 15 untuk Usaha: Pempek Lestari Sulur Berkah (Pemilik: Fitri Tresna Lestari)', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK021', 'eyJpdiI6Im5GcVl4OFJ1T1lZTThLZTNsSXpya2c9PSIsInZhbHVlIjoibFNSY3NtT2c5THVOOENURVdvcDZJSVViRDIxSStDMUZ4TnJPZ0xQQ0NmZz0iLCJtYWMiOiI4YzlkNjkxNWE1Njk5M2Q1NTQ3YjhkZTMxZDA3MGJhZGM1OGY4NzA5ODc2YmI1OGZkNjFjOGZiMWE2MjFmMTdmIiwidGFnIjoiIn0=', '8ee1b0cacc8f5c966e932d6523f092cef1528fca5be682664a58040c701fc9a5', 'Komp. BTN Karya Indah Blok A/2', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Registrasi Kartu Keluarga Warga RT 15 untuk Usaha: Little Hood (Pemilik: Indah Anggraini)', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK022', 'eyJpdiI6IjRJYVMzK3laWFN4dGRESG83b3V6MGc9PSIsInZhbHVlIjoiL0c3dmQwdjE2aEE4M2JKcWNDTlRVSmNMU2lKRFpEM2M0dUY3OXRVOGY0dz0iLCJtYWMiOiI2NjMwMzFhNjliYmU4OGU5ZWQwYTgyYzFiZWJjY2Y2ZjQ0OGY4OGNmMGQ2YjkwMjE4ZmQ1YmY3YTBlYTMwZmQ2IiwidGFnIjoiIn0=', '4bb413e249a53c4a298a1a326fe75e64d0ca3b5df793b4c9f6ecd433d0dc96aa', 'Komp. BTN Karya Indah Blok A2', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Registrasi Kartu Keluarga Warga RT 15 untuk Usaha: Kedai Rasa (Pemilik: Hj. Mariana)', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK023', 'eyJpdiI6Im9FUy9tTHJsaEpTcUsvd0RsTDhqSUE9PSIsInZhbHVlIjoiU2ZoT2FzUTZGODFiRGVEanlFUFRBeUtUK2F0aTNJcmR4bW5LOEhSRTZJMD0iLCJtYWMiOiJhZTgzNTY3MDg2ODZhMDNmZGI2ZDFkMGI4ZjkwMDFmMGJlYTRmMzYzZDhmYTc5YmE2MzFiYzc1OTg5MzA3MjJiIiwidGFnIjoiIn0=', '15e583bf472cd69b874d71df25cf7224a02ab07b10ced4c94b3b35ecec544afa', 'Komp. BTN Karya Indah RT 15 Blok C No. 01', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Registrasi Kartu Keluarga Warga RT 15 untuk Usaha: Rumah Rawit MR Nanda (Pemilik: Yanuar Surya Nanda, SE)', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK024', 'eyJpdiI6IkZUdWlYd3ByczhVT1JmS01GSFZ6UFE9PSIsInZhbHVlIjoiMjZGMWRmQWJzWWVWNTlMbzBuMDU4SXpYMm1FNUJGTE95Ym53TEZCM0FyQT0iLCJtYWMiOiI5YTI4MWYzZjNlN2I4YmExZDQ5YWNmOTJhZWQ3MzBhMmIxOTFkYWJjMDEzNTFiODI5NmQyNDU0NDMxNDI5ZmI1IiwidGFnIjoiIn0=', '8b5e380907aa14bd5b39edca8b53132e915a6a3d2d64fb94325185ce6296ab4d', 'Komp. BTN Karya Indah RT 15 Blok C No. 3, Kel. Pematang Sulur', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Registrasi Kartu Keluarga Warga RT 15 untuk Usaha: Dapoer Prima (Pemilik: Seprima Yuli Handayani)', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK025', 'eyJpdiI6IkpPczlIK1JQWGN4clpNdzk3MUhBalE9PSIsInZhbHVlIjoiR3hnTDUzYzM3ZGw0OThXQ0YwT3pNSURaQlpidWl1M2UvMW1TbmhqTjdxTT0iLCJtYWMiOiI5MzNiMzI0MWQyMTFkNmRhODQ0MDVkZWE5NGNiMDc2YmMzNzAxMTg3OWI3MmNkMWQyN2JjNWFiZGRkYWRhNWVlIiwidGFnIjoiIn0=', '5f555de9a9b834a9f06b4531bf1af524655ab42aae68d3dec7eaee4aa6a8587e', 'Ruko Green Golf Resident', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Registrasi Kartu Keluarga Warga RT 15 untuk Usaha: Jamu Kunyit Asam Semata Wayang MAP (Pemilik: Mutiah)', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

INSERT INTO `households` (`id_kk`, `no_kk`, `no_kk_hash`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `status_hunian`, `catatan`, `created_at`, `updated_at`)
VALUES ('KK026', 'eyJpdiI6Ik5Xd1pvREV3ajVPN2xxQThnNkkwOVE9PSIsInZhbHVlIjoiNVNOLy9EV1hUZFl0Wlp1YjhNQkpVQklKalEzc3EzOVZPTFp3enNJNDNNWT0iLCJtYWMiOiJlMjUzZjA1MmQ3YzJhYmMwOGJkNjZiYTdkMjE1ZDQzYmQ0YzlhZTMyMWU5NmVkMGM5MjM2MTRmNTk5MTY1NmJjIiwidGFnIjoiIn0=', '9f9db804f7c58e3eb5e9f53e92ce5f598221a321368a4daffb9d2ac060e7fa90', 'Komplek BTN Karya Indah Blok E10, RT 15, Pematang Sulur, Telanaipura, Jambi', 'RT 15 / RW 04', 'PEMATANG SULUR', 'TELANAIPURA', 'tetap', 'Registrasi Kartu Keluarga Warga RT 15 untuk Usaha: ZaTa Cake (Pemilik: Asmaryani Juwita)', NOW(), NOW())
ON DUPLICATE KEY UPDATE `alamat` = VALUES(`alamat`), `catatan` = VALUES(`catatan`), `updated_at` = NOW();

-- ---------------------------------------------------------------------
-- 2. DATA KEPALA KELUARGA / PEMILIK USAHA (Tabel `residents`)
-- ---------------------------------------------------------------------
INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `status_tempat_tinggal`, `alamat_domisili`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `telepon`, `created_at`, `updated_at`)
VALUES ('W060', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK020' LIMIT 1), 'memiliki_kk', 'tetap', 'Komp. BTN Karya Indah Blok E 7', NULL, NULL, 'Fitri Tresna Lestari', 'JAMBI', '1985-01-01', 'P', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', '085273949575', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `telepon` = VALUES(`telepon`), `alamat_domisili` = VALUES(`alamat_domisili`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `status_tempat_tinggal`, `alamat_domisili`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `telepon`, `created_at`, `updated_at`)
VALUES ('W061', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK021' LIMIT 1), 'memiliki_kk', 'tetap', 'Komp. BTN Karya Indah Blok A/2', NULL, NULL, 'Indah Anggraini', 'JAMBI', '1985-01-01', 'P', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', '082278506560', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `telepon` = VALUES(`telepon`), `alamat_domisili` = VALUES(`alamat_domisili`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `status_tempat_tinggal`, `alamat_domisili`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `telepon`, `created_at`, `updated_at`)
VALUES ('W062', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK022' LIMIT 1), 'memiliki_kk', 'tetap', 'Komp. BTN Karya Indah Blok A2', NULL, NULL, 'Hj. Mariana', 'JAMBI', '1985-01-01', 'P', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', '085266259159', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `telepon` = VALUES(`telepon`), `alamat_domisili` = VALUES(`alamat_domisili`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `status_tempat_tinggal`, `alamat_domisili`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `telepon`, `created_at`, `updated_at`)
VALUES ('W063', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK023' LIMIT 1), 'memiliki_kk', 'tetap', 'Komp. BTN Karya Indah RT 15 Blok C No. 01', NULL, NULL, 'Yanuar Surya Nanda, SE', 'JAMBI', '1985-01-01', 'L', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', '088296163644', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `telepon` = VALUES(`telepon`), `alamat_domisili` = VALUES(`alamat_domisili`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `status_tempat_tinggal`, `alamat_domisili`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `telepon`, `created_at`, `updated_at`)
VALUES ('W064', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK024' LIMIT 1), 'memiliki_kk', 'tetap', 'Komp. BTN Karya Indah RT 15 Blok C No. 3, Kel. Pematang Sulur', NULL, NULL, 'Seprima Yuli Handayani', 'JAMBI', '1985-01-01', 'P', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', '082180307997', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `telepon` = VALUES(`telepon`), `alamat_domisili` = VALUES(`alamat_domisili`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `status_tempat_tinggal`, `alamat_domisili`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `telepon`, `created_at`, `updated_at`)
VALUES ('W065', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK025' LIMIT 1), 'memiliki_kk', 'tetap', 'Ruko Green Golf Resident', NULL, NULL, 'Mutiah', 'JAMBI', '1985-01-01', 'P', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', '081288285617', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `telepon` = VALUES(`telepon`), `alamat_domisili` = VALUES(`alamat_domisili`), `updated_at` = NOW();

INSERT INTO `residents` (`id_warga`, `household_id`, `kategori_warga`, `status_tempat_tinggal`, `alamat_domisili`, `nik_encrypted`, `nik_hash`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `hubungan_keluarga`, `is_kepala_keluarga`, `status_kependudukan`, `telepon`, `created_at`, `updated_at`)
VALUES ('W066', (SELECT `id` FROM `households` WHERE `id_kk` = 'KK026' LIMIT 1), 'memiliki_kk', 'tetap', 'Komplek BTN Karya Indah Blok E10, RT 15, Pematang Sulur, Telanaipura, Jambi', NULL, NULL, 'Asmaryani Juwita', 'JAMBI', '1985-01-01', 'P', 'Islam', 'SMA/Sederajat', 'Wiraswasta', 'Kawin', 'kepala_keluarga', 1, 'aktif', '085210000341', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `telepon` = VALUES(`telepon`), `alamat_domisili` = VALUES(`alamat_domisili`), `updated_at` = NOW();

-- ---------------------------------------------------------------------
-- 3. DATA PROFIL USAHA UMKM WARGA RT 15 (Tabel `msme_profiles`)
-- ---------------------------------------------------------------------
-- UMKM: Pempek Lestari Sulur Berkah (Keluarga: KK020)
INSERT INTO `msme_profiles` (`household_id`, `nama_usaha`, `deskripsi`, `produk`, `kategori`, `foto_path`, `alamat`, `telepon`, `whatsapp`, `social_links`, `status_moderasi`, `catatan_moderasi`, `is_active`, `created_at`, `updated_at`)
VALUES (
    (SELECT `id` FROM `households` WHERE `id_kk` = 'KK020' LIMIT 1),
    'Pempek Lestari Sulur Berkah',
    'Menyediakan aneka pempek lenjer, kapal selam, kulit, dan olahan kuliner khas dengan cuko kental gula aren asli yang lezat dan higienis.',
    'Pempek, Tekwan, Model Gandum',
    'kuliner',
    NULL,
    'Komp. BTN Karya Indah Blok E 7',
    '085273949575',
    '085273949575',
    '{\"qris\":\"ada\",\"whatsapp\":\"085273949575\"}',
    'disetujui',
    'UMKM kuliner binaan warga RT 15 Pematang Sulur. Pembayaran QRIS: Tersedia.',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `household_id` = VALUES(`household_id`),
    `deskripsi` = VALUES(`deskripsi`),
    `produk` = VALUES(`produk`),
    `kategori` = VALUES(`kategori`),
    `alamat` = VALUES(`alamat`),
    `telepon` = VALUES(`telepon`),
    `whatsapp` = VALUES(`whatsapp`),
    `social_links` = VALUES(`social_links`),
    `catatan_moderasi` = VALUES(`catatan_moderasi`),
    `updated_at` = NOW();

-- UMKM: Dapur Bunda (Keluarga: KK002)
INSERT INTO `msme_profiles` (`household_id`, `nama_usaha`, `deskripsi`, `produk`, `kategori`, `foto_path`, `alamat`, `telepon`, `whatsapp`, `social_links`, `status_moderasi`, `catatan_moderasi`, `is_active`, `created_at`, `updated_at`)
VALUES (
    (SELECT `id` FROM `households` WHERE `id_kk` = 'KK002' LIMIT 1),
    'Dapur Bunda',
    'Menyajikan aneka masakan rumahan lezat, higienis, dan katering harian keluarga dengan bahan-bahan segar pilihan.',
    'Aneka Masakan Rumahan, Katering Harian',
    'kuliner',
    NULL,
    'Komp. BTN Karya Indah Blok A/4',
    '081366853339',
    '081366853339',
    '{\"qris\":\"tidak_tersedia\",\"whatsapp\":\"081366853339\"}',
    'disetujui',
    'UMKM kuliner warga RT 15 Pematang Sulur (Keluarga Bpk. Edwin Edward Rumajar / Ibu Deasy Mudahar). Pembayaran QRIS: Tidak tersedia.',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `household_id` = VALUES(`household_id`),
    `deskripsi` = VALUES(`deskripsi`),
    `produk` = VALUES(`produk`),
    `kategori` = VALUES(`kategori`),
    `alamat` = VALUES(`alamat`),
    `telepon` = VALUES(`telepon`),
    `whatsapp` = VALUES(`whatsapp`),
    `social_links` = VALUES(`social_links`),
    `catatan_moderasi` = VALUES(`catatan_moderasi`),
    `updated_at` = NOW();

-- UMKM: Little Hood (Keluarga: KK021)
INSERT INTO `msme_profiles` (`household_id`, `nama_usaha`, `deskripsi`, `produk`, `kategori`, `foto_path`, `alamat`, `telepon`, `whatsapp`, `social_links`, `status_moderasi`, `catatan_moderasi`, `is_active`, `created_at`, `updated_at`)
VALUES (
    (SELECT `id` FROM `households` WHERE `id_kk` = 'KK021' LIMIT 1),
    'Little Hood',
    'Produksi aneka produk kriya, kerajinan tangan artistik, dan cinderamata kreatif ramah lingkungan khas warga RT 15.',
    'Produk Kriya, Kerajinan Tangan Kreatif',
    'kerajinan',
    NULL,
    'Komp. BTN Karya Indah Blok A/2',
    '082278506560',
    '082278506560',
    '{\"qris\":\"ada\",\"whatsapp\":\"082278506560\"}',
    'disetujui',
    'UMKM kriya/kerajinan warga RT 15 Pematang Sulur. Akun TikTok belum diverifikasi sehingga tidak dipublikasikan. Pembayaran QRIS: Tersedia.',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `household_id` = VALUES(`household_id`),
    `deskripsi` = VALUES(`deskripsi`),
    `produk` = VALUES(`produk`),
    `kategori` = VALUES(`kategori`),
    `alamat` = VALUES(`alamat`),
    `telepon` = VALUES(`telepon`),
    `whatsapp` = VALUES(`whatsapp`),
    `social_links` = VALUES(`social_links`),
    `catatan_moderasi` = VALUES(`catatan_moderasi`),
    `updated_at` = NOW();

-- UMKM: Kedai Rasa (Keluarga: KK022)
INSERT INTO `msme_profiles` (`household_id`, `nama_usaha`, `deskripsi`, `produk`, `kategori`, `foto_path`, `alamat`, `telepon`, `whatsapp`, `social_links`, `status_moderasi`, `catatan_moderasi`, `is_active`, `created_at`, `updated_at`)
VALUES (
    (SELECT `id` FROM `households` WHERE `id_kk` = 'KK022' LIMIT 1),
    'Kedai Rasa',
    'Menghadirkan berbagai sajian makanan kedai gurih, kudapan lezat, dan minuman segar untuk santapan santai keluarga.',
    'Makanan Kedai, Minuman Segar',
    'kuliner',
    NULL,
    'Komp. BTN Karya Indah Blok A2',
    '085266259159',
    '085266259159',
    '{\"qris\":\"ada\",\"whatsapp\":\"085266259159\"}',
    'disetujui',
    'UMKM kuliner warga RT 15 Pematang Sulur. Pembayaran QRIS: Tersedia.',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `household_id` = VALUES(`household_id`),
    `deskripsi` = VALUES(`deskripsi`),
    `produk` = VALUES(`produk`),
    `kategori` = VALUES(`kategori`),
    `alamat` = VALUES(`alamat`),
    `telepon` = VALUES(`telepon`),
    `whatsapp` = VALUES(`whatsapp`),
    `social_links` = VALUES(`social_links`),
    `catatan_moderasi` = VALUES(`catatan_moderasi`),
    `updated_at` = NOW();

-- UMKM: Rumah Rawit MR Nanda (Keluarga: KK023)
INSERT INTO `msme_profiles` (`household_id`, `nama_usaha`, `deskripsi`, `produk`, `kategori`, `foto_path`, `alamat`, `telepon`, `whatsapp`, `social_links`, `status_moderasi`, `catatan_moderasi`, `is_active`, `created_at`, `updated_at`)
VALUES (
    (SELECT `id` FROM `households` WHERE `id_kk` = 'KK023' LIMIT 1),
    'Rumah Rawit MR Nanda',
    'Kuliner spesial olahan pedas mantap Rumah Rawit MR Nanda dengan racikan cabai rawit pilihan. Tersedia pemesanan langsung maupun online via GrabFood.',
    'Aneka Masakan Pedas, Olahan Ayam & Bebek Rawit',
    'kuliner',
    NULL,
    'Komp. BTN Karya Indah RT 15 Blok C No. 01',
    '088296163644',
    '088296163644',
    '{\"qris\":\"ada\",\"grabfood\":\"GrabFood\",\"whatsapp\":\"088296163644\"}',
    'disetujui',
    'UMKM kuliner warga RT 15 Pematang Sulur. Terdaftar di mitra GrabFood. Pembayaran QRIS: Tersedia.',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `household_id` = VALUES(`household_id`),
    `deskripsi` = VALUES(`deskripsi`),
    `produk` = VALUES(`produk`),
    `kategori` = VALUES(`kategori`),
    `alamat` = VALUES(`alamat`),
    `telepon` = VALUES(`telepon`),
    `whatsapp` = VALUES(`whatsapp`),
    `social_links` = VALUES(`social_links`),
    `catatan_moderasi` = VALUES(`catatan_moderasi`),
    `updated_at` = NOW();

-- UMKM: Dapoer Prima (Keluarga: KK024)
INSERT INTO `msme_profiles` (`household_id`, `nama_usaha`, `deskripsi`, `produk`, `kategori`, `foto_path`, `alamat`, `telepon`, `whatsapp`, `social_links`, `status_moderasi`, `catatan_moderasi`, `is_active`, `created_at`, `updated_at`)
VALUES (
    (SELECT `id` FROM `households` WHERE `id_kk` = 'KK024' LIMIT 1),
    'Dapoer Prima',
    'Menyediakan aneka produk makanan beku (frozen food) berkualitas dan higienis, termasuk keju mozzarella leleh istimewa untuk aneka kebutuhan memasak.',
    'Produk Frozen Food, Keju Mozzarella',
    'kuliner',
    NULL,
    'Komp. BTN Karya Indah RT 15 Blok C No. 3, Kel. Pematang Sulur',
    '082180307997',
    '082180307997',
    '{\"qris\":\"tidak_tersedia\",\"shopee\":\"seprima_prims\",\"tiktok\":\"dapoer_prima\",\"facebook\":\"Seprima Prims\",\"whatsapp\":\"082180307997\",\"instagram\":\"dapoer_prima\"}',
    'disetujui',
    'UMKM frozen food warga RT 15 Pematang Sulur. Profil media sosial lengkap: Instagram, TikTok, Facebook, dan Shopee. Pembayaran QRIS: Tidak tersedia.',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `household_id` = VALUES(`household_id`),
    `deskripsi` = VALUES(`deskripsi`),
    `produk` = VALUES(`produk`),
    `kategori` = VALUES(`kategori`),
    `alamat` = VALUES(`alamat`),
    `telepon` = VALUES(`telepon`),
    `whatsapp` = VALUES(`whatsapp`),
    `social_links` = VALUES(`social_links`),
    `catatan_moderasi` = VALUES(`catatan_moderasi`),
    `updated_at` = NOW();

-- UMKM: Jamu Kunyit Asam Semata Wayang MAP (Keluarga: KK025)
INSERT INTO `msme_profiles` (`household_id`, `nama_usaha`, `deskripsi`, `produk`, `kategori`, `foto_path`, `alamat`, `telepon`, `whatsapp`, `social_links`, `status_moderasi`, `catatan_moderasi`, `is_active`, `created_at`, `updated_at`)
VALUES (
    (SELECT `id` FROM `households` WHERE `id_kk` = 'KK025' LIMIT 1),
    'Jamu Kunyit Asam Semata Wayang MAP',
    'Minuman herbal tradisional jamu kunyit asam asli dan segar tanpa bahan pengawet kimiawi, diolah higienis untuk menjaga kebugaran dan daya tahan tubuh.',
    'Jamu Kunyit Asam Tradisional, Minuman Herbal Segar',
    'kuliner',
    NULL,
    'Ruko Green Golf Resident',
    '081288285617',
    '081288285617',
    '{\"qris\":\"ada\",\"whatsapp\":\"081288285617\"}',
    'disetujui',
    'UMKM jamu herbal tradisional warga RT 15 Pematang Sulur. Pembayaran QRIS: Tersedia.',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `household_id` = VALUES(`household_id`),
    `deskripsi` = VALUES(`deskripsi`),
    `produk` = VALUES(`produk`),
    `kategori` = VALUES(`kategori`),
    `alamat` = VALUES(`alamat`),
    `telepon` = VALUES(`telepon`),
    `whatsapp` = VALUES(`whatsapp`),
    `social_links` = VALUES(`social_links`),
    `catatan_moderasi` = VALUES(`catatan_moderasi`),
    `updated_at` = NOW();

-- UMKM: ZaTa Cake (Keluarga: KK026)
INSERT INTO `msme_profiles` (`household_id`, `nama_usaha`, `deskripsi`, `produk`, `kategori`, `foto_path`, `alamat`, `telepon`, `whatsapp`, `social_links`, `status_moderasi`, `catatan_moderasi`, `is_active`, `created_at`, `updated_at`)
VALUES (
    (SELECT `id` FROM `households` WHERE `id_kk` = 'KK026' LIMIT 1),
    'ZaTa Cake',
    'Menerima pesanan aneka snack box berkualitas, kudapan kue lezat, kue basah dan kering tradisional/modern, serta sajian katering acara.',
    'Snack Box, Kudapan, Kue Basah & Kering, Katering',
    'kuliner',
    NULL,
    'Komplek BTN Karya Indah Blok E10, RT 15, Pematang Sulur, Telanaipura, Jambi',
    '085210000341',
    '085210000341',
    '{\"qris\":\"tersedia\",\"tiktok\":\"zata cake\",\"whatsapp\":\"085210000341\",\"instagram\":\"Jajan_ala_zata\"}',
    'disetujui',
    'UMKM kue & katering warga RT 15 Pematang Sulur. Gabungan entri ZaTa Cake dengan akun Instagram (@Jajan_ala_zata) dan TikTok (@zata cake). Pembayaran QRIS: Tersedia.',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `household_id` = VALUES(`household_id`),
    `deskripsi` = VALUES(`deskripsi`),
    `produk` = VALUES(`produk`),
    `kategori` = VALUES(`kategori`),
    `alamat` = VALUES(`alamat`),
    `telepon` = VALUES(`telepon`),
    `whatsapp` = VALUES(`whatsapp`),
    `social_links` = VALUES(`social_links`),
    `catatan_moderasi` = VALUES(`catatan_moderasi`),
    `updated_at` = NOW();

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
-- =====================================================================
-- SELESAI INJEKSI DATA UMKM RT 15 PEMATANG SULUR
-- =====================================================================