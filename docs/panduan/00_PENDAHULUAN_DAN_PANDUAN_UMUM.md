# BUKU PANDUAN PENGGUNA INDUK (MASTER USER MANUAL)
## SISTEM INFORMASI MANAJEMEN RT 15 PEMATANG SULUR (SIM-LIBAS)
*Inisiatif Transformasi Kampung Digital Pinang Masak Lestari*  
*Kelurahan Pematang Sulur, Kecamatan Telanaipura, Kota Jambi*

---

| Dokumen | Pendahuluan, Arsitektur Sistem, Matriks Hak Akses, & Indeks Panduan |
| :--- | :--- |
| **Kode Dokumen** | `DOC-SIMLIBAS-MAN-00` |
| **Versi** | 1.0 (Rilis Resmi Master) |
| **Tahun Terbit** | Oktober 2026 |
| **Klasifikasi** | Terbuka untuk Seluruh Pengurus RT, Warga, Mitra, dan Pembina Wilayah |
| **Direktori Berkas** | `c:\laragon\www\sim-libas\docs\panduan\` |

---

## DAFTAR ISI
1. [BAB 1: GAMBARAN UMUM SISTEM SIM-LIBAS](#bab-1-gambaran-umum-sistem-sim-libas)
   - 1.1 [Latar Belakang & Visi Kampung Digital](#11-latar-belakang--visi-kampung-digital)
   - 1.2 [Pilar Modul Utama Sistem](#12-pilar-modul-utama-sistem)
   - 1.3 [Kepatuhan Hukum Perlindungan Data Pribadi (UU PDP No. 27/2022)](#13-kepatuhan-hukum-perlindungan-data-pribadi-uu-pdp-no-272022)
2. [BAB 2: ARSITEKTUR AKSES & DUA PINTU MASUK (DUAL-GATE ARCHITECTURE)](#bab-2-arsitektur-akses--dua-pintu-masuk-dual-gate-architecture)
   - 2.1 [Portal Layanan Mandiri Warga (`/masuk` -> `/warga`)](#21-portal-layanan-mandiri-warga-masuk---warga)
   - 2.2 [Panel Kontrol Operasional Pengurus (`/admin`)](#22-panel-kontrol-operasional-pengurus-admin)
   - 2.3 [Portal Informasi Publik & Verifikasi Dokumen (`/`)](#23-portal-informasi-publik--verifikasi-dokumen-)
3. [BAB 3: MATRIKS PERAN & HAK AKSES PENGGUNA (ROLE MATRIX)](#bab-3-matriks-peran--hak-akses-pengguna-role-matrix)
4. [BAB 4: KREDENSIAL AKUN CONTOH / UJI COBA RESMI (SEEDER)](#bab-4-kredensial-akun-contoh--uji-coba-resmi-seeder)
5. [BAB 5: PETUNJUK PENYIAPAN TANGKAPAN LAYAR (SCREENSHOT GUIDE)](#bab-5-petunjuk-penyiapan-tangkapan-layar-screenshot-guide)
   - 5.1 [Struktur Folder & Penamaan Berkas Gambar](#51-struktur-folder--penamaan-berkas-gambar)
   - 5.2 [Tata Cara Pengambilan Tangkapan Layar yang Baik](#52-tata-cara-pengambilan-tangkapan-layar-yang-baik)
   - 5.3 [Rekapitulasi Total Slot Tangkapan Layar Sistem](#53-rekapitulasi-total-slot-tangkapan-layar-sistem)
6. [BAB 6: INDEKS BUKU PANDUAN PER ROLE (MASTER INDEX)](#bab-6-indeks-buku-panduan-per-role-master-index)

---

## BAB 1: GAMBARAN UMUM SISTEM SIM-LIBAS

### 1.1 Latar Belakang & Visi Kampung Digital

**SIM-LiBas (Sistem Informasi Manajemen Lingkungan Berbasis Aspirasi & Sinergi)** merupakan platform digital terpadu yang dibangun untuk memodernisasi tata kelola administrasi, keuangan, pelayanan publik, dan kepedulian lingkungan di lingkungan **RT 15 Kelurahan Pematang Sulur, Kecamatan Telanaipura, Kota Jambi**.

Mengusung visi **"Kampung Digital Pinang Masak Lestari: Guyub, Transparan, Mandiri, & Berdaya"**, SIM-LiBas menghapus sekat-sekat birokrasi manual tingkat rukun tetangga, menggantikan buku kas kertas yang rawan hilang, mempercepat pengurusan dokumen kependudukan, serta memberdayakan ekonomi warga melalui digitalisasi bank sampah dan etalase promosi UMKM lokal.

```
                  +----------------------------------------------+
                  |         SIM-LIBAS RT 15 PEMATANG SULUR       |
                  |     Inisiatif Kampung Digital Pinang Masak   |
                  +----------------------------------------------+
                                         |
     +-------------------+---------------+-------------------+-------------------+
     |                   |                                   |                   |
[Pelayanan Warga]  [Tata Kelola Kas]                  [Bank Sampah]       [Ronda & UMKM]
- Pengantar Surat  - Iuran Bulanan                     - Tabungan Sampah   - Jadwal Ronda
- Pengaduan Online - Kas RT Transparan                - Konversi Rupiah   - Lapor Tamu 1x24 Jam
- Cek KK Mandiri   - Pembayaran Qris/Transfer         - Laporan DLH       - Etalase Dagang
```

---

### 1.2 Pilar Modul Utama Sistem

1. **Modul Kependudukan Terpadu**: Pengelolaan data Kartu Keluarga (KK), status hunian (Rumah Milik/Sewa/Kontrak), dan profil penduduk dengan pengelompokan keluarga.
2. **Modul Pelayanan Surat Mandiri & QR Code Otomatis**: Pengajuan surat pengantar secara mandiri oleh warga dengan persetujuan digital berjenjang dan verifikasi anti-pemalsuan instan berbasis QR Code.
3. **Modul Pengaduan & Saluran Aspirasi Warga**: Pelaporan masalah lingkungan (fasilitas rusak, lampu padam, kebersihan) dengan unggah bukti foto dan pelacakan status penanganan (*open, in progress, resolved*).
4. **Modul Keuangan & Kas RT Transparan**: Manajemen tagihan iuran rutin warga, verifikasi bukti pembayaran transfer/tunai, serta pencatatan buku kas umum real-time.
5. **Modul Bank Sampah Sirkular (Pinang Masak Lestari)**: Penimbangan sampah terpilah warga, konversi harga otomatis ke saldo buku tabungan, dan penarikan kas tabungan sampah.
6. **Modul Keamanan Lingkungan & Ronda Malam**: Penyusunan jadwal ronda bergilir, rekap absensi pos ronda, dan pelaporan tamu menginap 1x24 jam.
7. **Modul Pemberdayaan UMKM Warga**: Etalase promosi produk dagang dan jasa warga yang langsung terhubung ke aplikasi WhatsApp pedagang tanpa potongan komisi.

---

### 1.3 Kepatuhan Hukum Perlindungan Data Pribadi (UU PDP No. 27/2022)

Sebagai sistem berbasis teknologi yang mengelola data warga, SIM-LiBas menerapkan prinsip-prinsip perlindungan data yang ketat:

- **Enkripsi Kata Sandi**: Seluruh kata sandi disimpan menggunakan algoritma *Bcrypt Hashing* yang aman.
- **Pemisahan Hak Akses Bertingkat (Role-Based Access Control / RBAC)**: Hanya Ketua RT, Sekretaris, dan Administrator berwenang yang dapat melihat data identitas kependudukan lengkap.
- **Sensor Data Sensitif pada Portal Publik**: Pada halaman verifikasi surat berbasis QR Code publik (`/verifikasi-surat/{token}`), Nomor Induk Kependudukan (NIK 16 digit), tanggal lahir, dan alamat lengkap sengaja disembunyikan guna mencegah pencurian identitas.
- **Akses Pengawas Non-Identitas**: Role pengawas wilayah (RW/Kelurahan) hanya disajikan data agregat statistik makro, buku kas umum, dan volume sampah tanpa menampilkan rekam jejak pribadi warga.

---

## BAB 2: ARSITEKTUR AKSES & DUA PINTU MASUK (DUAL-GATE ARCHITECTURE)

SIM-LiBas memisahkan antarmuka pengguna menjadi dua portal login yang terisolasi secara aman:

```
                          [ PENGUNJUNG SISTEM ]
                                    |
      +-----------------------------+-----------------------------+
      |                                                           |
[ PORTAL WARGA ]                                            [ PANEL ADMIN ]
URL: /masuk atau /login                                     URL: /admin
Guard: Web (Role Warga)                                     Guard: Web (Role Pengurus)
Autentikasi: NIK / No HP / Email                            Autentikasi: Email + Password
Tampilan: Antarmuka Blade Responsif Mobile                  Tampilan: Dasbor Filament v3
Pengguna: Warga Kepala Keluarga & Anggota                   Pengguna: RT, Bendahara, Koor, Admin
```

---

### 2.1 Portal Layanan Mandiri Warga (`/masuk` -> `/warga`)

- **Tautan Masuk**: `http://localhost/masuk`
- **Sasaran Pengguna**: Seluruh kepala keluarga dan anggota warga RT 15 yang terdaftar di pangkalan data.
- **Metode Masuk Fleksibel**: Warga dapat masuk menggunakan:
  1. **Nomor Induk Kependudukan (NIK 16 Digit)** (Sangat ramah bagi warga lansia atau yang jarang menggunakan email).
  2. **Nomor Telepon / WhatsApp Aktif** (Format `08xxxxxxxxxx`).
  3. **Alamat Email Terdaftar**.
- **Fitur Utama**: Cek susunan KK, ajukan surat pengantar resmi, bayar iuran bulanan, unggah bukti bayar, kirim pengaduan berfoto, lapor tamu 1x24 jam, cek saldo bank sampah, dan kelola etalase UMKM sendiri.

---

### 2.2 Panel Kontrol Operasional Pengurus (`/admin`)

- **Tautan Masuk**: `http://localhost/admin`
- **Sasaran Pengguna**: Struktur organisasi pengurus RT 15 (Super Admin, Ketua RT, Sekretaris, Bendahara, Koordinator Bank Sampah, Koordinator Keamanan, dan Pembina RW/Kelurahan).
- **Teknologi**: Dibangun menggunakan *FilamentPHP v3* dengan komponen tabel dinamis, formulir interaktif, filter multifaset, dan fitur ekspor dokumen (PDF & Excel).
- **Keamanan**: Dilengkapi pemeriksaan ketat `canAccessPanel()`, riwayat audit, dan otorisasi kebijakan per tabel (*Policy Authorization*).

---

### 2.3 Portal Informasi Publik & Verifikasi Dokumen (`/`)

- **Tautan Akses**: `http://localhost/` (Dapat diakses langsung tanpa perlu memiliki akun login).
- **Sasaran Pengguna**: Tamu wilayah, masyarakat umum, pencari produk UMKM, dan instansi eksternal verifikator dokumen.
- **Rute yang Tersedia**:
  * `/` : Beranda profil Kampung Digital, statistik wilayah, pengumuman, dan agenda.
  * `/layanan` & `/layanan/{slug}` : Direktori katalog dan persyaratan surat pengantar RT.
  * `/katalog-umkm` : Etalase produk warga dan tombol WhatsApp pesanan langsung.
  * `/bank-sampah` : Profil lingkungan dan patokan harga beli sampah terpilah terkini.
  * `/kontak` : Peta lokasi balai RT 15, jadwal piket, dan kontak darurat wilayah.
  * `/verifikasi-surat/{token}` : Pindai QR Code untuk memeriksa keaslian surat fisik RT 15 secara real-time.

---

## BAB 3: MATRIKS PERAN & HAK AKSES PENGGUNA (ROLE MATRIX)

Sistem SIM-LiBas menerapkan pemisahan tugas (*Segregation of Duties*) yang ketat ke dalam 8 (delapan) peran pengguna:

| No | Nama Peran (*Role*) | Pintu Masuk (*Gate*) | Guard & Model | Ringkasan Tanggung Jawab & Hak Akses Fitur |
| :-: | :--- | :--- | :--- | :--- |
| **1** | **Warga** (`warga`) | `/masuk` & `/warga` | `web` (`User`) | Layanan mandiri warga: Cek KK, permohonan surat pengantar, bayar iuran bulanan, kirim aduan lingkungan, lapor tamu 1x24 jam, pantau saldo tabungan sampah, dan kelola profil UMKM pribadi. |
| **2** | **Ketua RT** (`ketua_rt`) | `/admin` | `web` (`User`) | Pimpinan wilayah: Dashboard eksekutif, persetujuan/penolakan surat pengantar dengan paraf digital QR Code, disposisi pengaduan warga, pemantauan kas RT, dan pengawasan ronda malam. |
| **3** | **Sekretaris RT** (`sekretaris`) | `/admin` | `web` (`User`) | Tata kelola ketatausahaan: Verifikasi berkas warga & Kartu Keluarga (KK), penomoran surat otomatis, penerbitan pengumuman/agenda, penerbitan lembar cetak fisik surat, dan ekspor data kependudukan. |
| **4** | **Bendahara RT** (`bendahara`) | `/admin` | `web` (`User`) | Tata kelola keuangan: Pengaturan jenis iuran rutin/insidental, penerbitan invoice massal KK, verifikasi konfirmasi bukti bayar warga, pencatatan transaksi kas masuk/keluar, dan laporan pembukuan kas bulanan. |
| **5** | **Koor. Bank Sampah** (`koordinator_bank_sampah`) | `/admin` | `web` (`User`) | Unit sirkular ekonomi: Master data jenis & tarif beli sampah, pencatatan timbangan setoran sampah nasabah KK, pengelolaan buku tabungan sampah warga, dan pencairan saldo tunai. |
| **6** | **Koor. Keamanan** (`koordinator_keamanan`) | `/admin` | `web` (`User`) | Unit ketertiban lingkungan: Pengaturan jadwal ronda malam bergilir kepala keluarga, rekapitulasi kehadiran pos ronda, verifikasi laporan tamu menginap 1x24 jam, dan koordinasi darurat kamtibmas. |
| **7** | **Super Admin** (`super_admin`) | `/admin` | `web` (`User`) | Administrator teknis sistem: Manajemen akun dan hak akses pengguna (*User & Roles*), reset kata sandi, pengaturan profil RT 15 (logo, kop surat, masa jabatan), rekap jejak audit aktivitas (*Activity Log*), dan pemeliharaan server. |
| **8** | **RW / Kelurahan** (`rw_kelurahan`) | `/admin` | `web` (`User`) | Pembina wilayah & auditor (*Read-Only*): Dashboard monitoring eksekutif, audit keterbukaan kas keuangan RT 15, dan pemantauan capaian reduksi sampah Bank Sampah Pinang Masak Lestari (tanpa akses data privasi kependudukan). |

---

## BAB 4: KREDENSIAL AKUN CONTOH / UJI COBA RESMI (SEEDER)

Untuk keperluan pelatihan, uji coba fungsi, atau demonstrasi sistem di lingkungan pengembangan lokal (*Localhost / Staging*), telah disediakan akun uji coba standar yang diisi melalui basis data *seeder*:

> [!NOTE]
> Seluruh akun uji coba bawaan di bawah ini menggunakan kata sandi standar yang sama: **`password`**

| Peran (*Role*) | Pintu Masuk URL | Alamat Email Login | Kredensial Alternatif | Nama Pengguna / Pejabat |
| :--- | :--- | :--- | :--- | :--- |
| **Super Admin** | `/admin` | `admin@simlibas.id` | - | Administrator SIM-LiBas |
| **Ketua RT 15** | `/admin` | `ketua.rt@simlibas.id` | - | Bpk. H. Ahmad Sudirman, S.E. |
| **Sekretaris RT 15** | `/admin` | `sekretaris@simlibas.id` | - | Bpk. Bambang Irawan, S.Kom. |
| **Bendahara RT 15** | `/admin` | `bendahara@simlibas.id` | - | Ibu Hj. Nurhayati, S.E. |
| **Koor. Bank Sampah** | `/admin` | `banksampah@simlibas.id` | - | Bpk. Joko Wahyudi |
| **Koor. Keamanan** | `/admin` | `keamanan@simlibas.id` | - | Bpk. Hendra Saputra |
| **Warga 1 (KK Tetap & UMKM)** | `/masuk` | `warga.dedi@simlibas.id` | NIK: `1571011504780001`<br>HP: `0813-6677-8899` | Bpk. Dedi Kurniawan |
| **Warga 2 (KK Kontrak/Kost)** | `/masuk` | `warga.agus@simlibas.id` | NIK: `1571012008850002`<br>HP: `0813-7788-9900` | Bpk. Agus Setiawan |
| **RW / Kelurahan** | `/admin` | *(Dibuat via Admin User)* | Role: `rw_kelurahan` | Tim Pembina Wilayah |

---

## BAB 5: PETUNJUK PENYIAPAN TANGKAPAN LAYAR (SCREENSHOT GUIDE)

### 5.1 Struktur Folder & Penamaan Berkas Gambar

Seluruh buku panduan di dalam seri dokumentasi ini telah dilengkapi dengan penanda visual khusus berupa **Slot Tangkapan Layar Terstruktur**.

Semua berkas gambar tangkapan layar wajib disimpan pada direktori aset bersama berikut:
```
c:\laragon\www\sim-libas\docs\panduan\assets\screenshots\
```

Format penamaan berkas gambar telah distandarisasi menggunakan format:
```
SS-[KODE_ROLE]-[NOMOR_URUT]-[nama-fitur-deskriptif].png
```
*Contoh*: `SS-WRG-001-form-login-portal-warga.png`, `SS-KRT-003-modal-persetujuan-surat.png`, `SS-BND-006-verifikasi-bukti-transfer.png`.

---

### 5.2 Tata Cara Pengambilan Tangkapan Layar yang Baik

1. **Gunakan Resolusi Layar Standar**: Disarankan menggunakan resolusi monitor Full HD (1920x1080 piksel) dengan perbesaran (*browser zoom*) 100%.
2. **Kerapian Data Sampel**: Pastikan data yang tampil pada layar merupakan data percontohan yang rapi, tidak mengandung kata-kata kasar atau informasi rahasia perorangan yang nyata.
3. **Sorotan Elemen (Kotak/Panah Merah)**: Apabila slot tangkapan layar meminta fokus pada tombol tertentu (misal tombol "Setujui", "Konfirmasi", atau "Cetak"), disarankan menambahkan kotak pembatas berwarna merah atau oranye menggunakan aplikasi pengedit gambar ringan (seperti Greenshot, Snipping Tool, atau Lightshot).
4. **Format & Kompresi Berkas**: Simpan gambar dalam format `.png` atau `.jpg` yang tajam dan terbaca dengan ukuran file optimal (antara 150 KB hingga 800 KB per gambar).

---

### 5.3 Rekapitulasi Total Slot Tangkapan Layar Sistem

Terdapat total **96 (sembilan puluh enam) slot tangkapan layar** yang tersebar di 8 buku panduan:

```
+------------------------------------------------------------------------------------------+
| REKAPITULASI SLOT TANGKAPAN LAYAR (SCREENSHOT SLOTS) BUKU PANDUAN SIM-LIBAS             |
+------------------------------------------------------------------------------------------+
| No | Dokumen Panduan Role               | Kode Slot   | Rentang ID      | Jumlah Slot    |
+----+------------------------------------+-------------+-----------------+----------------+
| 1  | Panduan Role Warga Mandiri         | SS-WRG      | 001 s/d 018     | 18 Slot        |
| 2  | Panduan Role Ketua RT              | SS-KRT      | 001 s/d 015     | 15 Slot        |
| 3  | Panduan Role Sekretaris RT         | SS-SEK      | 001 s/d 015     | 15 Slot        |
| 4  | Panduan Role Bendahara RT          | SS-BND      | 001 s/d 015     | 15 Slot        |
| 5  | Panduan Role Koor. Bank Sampah     | SS-BS       | 001 s/d 010     | 10 Slot        |
| 6  | Panduan Role Koor. Keamanan        | SS-KMN      | 001 s/d 008     | 8 Slot         |
| 7  | Panduan Role Super Admin           | SS-ADM      | 001 s/d 007     | 7 Slot         |
| 8  | Panduan Role RW/Kelurahan & Publik | SS-PUB      | 001 s/d 008     | 8 Slot         |
+----+------------------------------------+-------------+-----------------+----------------+
|    | TOTAL KESELURUHAN SLOT TANGKAPAN LAYAR TERSEDIA  |                 | 96 Slot Gambar |
+----+------------------------------------+-------------+-----------------+----------------+
```

---

## BAB 6: INDEKS BUKU PANDUAN PER ROLE (MASTER INDEX)

Silakan klik tautan dokumen di bawah ini untuk membuka dan membaca buku panduan teknis yang spesifik untuk masing-masing peran pengguna:

```
docs/panduan/
├── 00_PENDAHULUAN_DAN_PANDUAN_UMUM.md   <-- (Dokumen Ini: Master Index & Overview)
├── 01_PANDUAN_WARGA.md                  <-- Panduan Lengkap Warga (Portal Mandiri)
├── 02_PANDUAN_KETUA_RT.md               <-- Panduan Eksekutif & Validasi Surat Ketua RT
├── 03_PANDUAN_SEKRETARIS.md             <-- Panduan Kependudukan & Ketatausahaan Sekretaris
├── 04_PANDUAN_BENDAHARA.md              <-- Panduan Kas & Verifikasi Iuran Bendahara
├── 05_PANDUAN_KOORDINATOR_BANK_SAMPAH.md<-- Panduan Timbangan & Buku Tabungan Bank Sampah
├── 06_PANDUAN_KOORDINATOR_KEAMANAN.md   <-- Panduan Jadwal Ronda & Tamu 1x24 Jam Keamanan
├── 07_PANDUAN_SUPER_ADMIN.md            <-- Panduan Konfigurasi Sistem & User Super Admin
└── 08_PANDUAN_RW_KELURAHAN_DAN_PUBLIK.md<-- Panduan Monitoring RW & Akses Portal Publik
```

### Tabel Rincian Buku Panduan:

1. 📄 **[Buku Panduan 01: Role Warga (Portal Layanan Mandiri)](file:///c:/laragon/www/sim-libas/docs/panduan/01_PANDUAN_WARGA.md)**  
   *Kode: `DOC-SIMLIBAS-MAN-01` | 10 Bab | 18 Slot Screenshot*  
   Membahas: Login NIK/HP, cek susunan KK, permohonan surat pengantar, bayar tagihan iuran bulanan, unggah bukti transfer, pengaduan fasilitas warga berfoto, lapor tamu 1x24 jam, cek saldo bank sampah, dan pembuatan toko UMKM online.

2. 📄 **[Buku Panduan 02: Role Ketua RT](file:///c:/laragon/www/sim-libas/docs/panduan/02_PANDUAN_KETUA_RT.md)**  
   *Kode: `DOC-SIMLIBAS-MAN-02` | 9 Bab | 15 Slot Screenshot*  
   Membahas: Dasbor kepemimpinan RT, tinjauan dan persetujuan surat pengantar secara elektronik, disposisi penanganan pengaduan warga, pemantauan status kas RT, pengawasan operasional bank sampah dan ronda malam.

3. 📄 **[Buku Panduan 03: Role Sekretaris RT](file:///c:/laragon/www/sim-libas/docs/panduan/03_PANDUAN_SEKRETARIS.md)**  
   *Kode: `DOC-SIMLIBAS-MAN-03` | 9 Bab | 15 Slot Screenshot*  
   Membahas: Manajemen data master Kartu Keluarga (KK) dan warga, verifikasi nomor surat keluar, penerbitan lembar cetak fisik berkop resmi dan ber-QR Code, penyiaran pengumuman & kalender agenda warga, serta ekspor data ke format Excel dan PDF.

4. 📄 **[Buku Panduan 04: Role Bendahara RT](file:///c:/laragon/www/sim-libas/docs/panduan/04_PANDUAN_BENDAHARA.md)**  
   *Kode: `DOC-SIMLIBAS-MAN-04` | 9 Bab | 15 Slot Screenshot*  
   Membahas: Penataan jenis tarif iuran lingkungan, *generate* invoice tagihan otomatis per KK, verifikasi konfirmasi bukti bayar warga, pencatatan buku kas masuk/keluar, penerbitan kwitansi sah, serta laporan pembukuan kas bulanan.

5. 📄 **[Buku Panduan 05: Role Koordinator Bank Sampah](file:///c:/laragon/www/sim-libas/docs/panduan/05_PANDUAN_KOORDINATOR_BANK_SAMPAH.md)**  
   *Kode: `DOC-SIMLIBAS-MAN-05` | 8 Bab | 10 Slot Screenshot*  
   Membahas: Manajemen master jenis dan tarif beli sampah per kg, pencatatan timbangan setoran sampah nasabah KK, buku tabungan sampah digital, verifikasi penarikan saldo kas tabungan, dan pelaporan dampak ekologis ke Dinas Lingkungan Hidup.

6. 📄 **[Buku Panduan 06: Role Koordinator Keamanan](file:///c:/laragon/www/sim-libas/docs/panduan/06_PANDUAN_KOORDINATOR_KEAMANAN.md)**  
   *Kode: `DOC-SIMLIBAS-MAN-06` | 6 Bab | 8 Slot Screenshot*  
   Membahas: Penyusunan jadwal ronda malam bergilir pos kamling per kepala keluarga, rekap absensi kehadiran pos ronda malam, tindak lanjut verifikasi tamu menginap 1x24 jam berfoto, dan koordinasi darurat keamanan lingkungan.

7. 📄 **[Buku Panduan 07: Role Super Admin](file:///c:/laragon/www/sim-libas/docs/panduan/07_PANDUAN_SUPER_ADMIN.md)**  
   *Kode: `DOC-SIMLIBAS-MAN-07` | 6 Bab | 7 Slot Screenshot*  
   Membahas: Manajemen akun pengguna (*Users & Roles*), reset kata sandi, pengaturan umum profil RT 15 (logo, masa bakti, kop surat), monitoring rekam jejak sistem (*Activity Logs*), serta pemeliharaan dan cadangan basis data (*backup*).

8. 📄 **[Buku Panduan 08: Role RW / Kelurahan & Akses Portal Publik](file:///c:/laragon/www/sim-libas/docs/panduan/08_PANDUAN_RW_KELURAHAN_DAN_PUBLIK.md)**  
   *Kode: `DOC-SIMLIBAS-MAN-08` | 5 Bab | 8 Slot Screenshot*  
   Membahas: Dasbor monitoring pimpinan wilayah tingkat RW/Kelurahan (*view-only*), transparansi kas dan dampak sampah (kepatuhan UU PDP No. 27/2022), penjelajahan beranda publik, direktori layanan, etalase UMKM, serta verifikasi keaslian surat fisik via pemindaian QR Code.

---
*Dokumen ini merupakan Dokumen Induk Resmi Buku Panduan Pengguna Sistem Informasi Manajemen RT 15 Pematang Sulur (SIM-LiBas). Hak Cipta dilindungi undang-undang.*
