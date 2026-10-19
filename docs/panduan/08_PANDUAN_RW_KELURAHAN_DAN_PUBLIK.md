# BUKU PANDUAN PENGGUNA SIM-LIBAS
## PANDUAN ROLE RW / KELURAHAN & PORTAL PUBLIK
**Sistem Informasi Manajemen RT 15 Kelurahan Pematang Sulur**  
*Inisiatif Kampung Digital Pinang Masak Lestari*

---

| Dokumen | Panduan Role RW / Kelurahan & Akses Portal Publik |
| :--- | :--- |
| **Kode Dokumen** | `DOC-SIMLIBAS-MAN-08` |
| **Versi** | 1.0 (Rilis Resmi) |
| **Tanggal Pembaruan** | Oktober 2026 |
| **Sasaran Pengguna** | Ketua RW 04, Aparatur Kelurahan Pematang Sulur, Instansi Verifikator Surat, Pelanggan UMKM, & Tamu Publik |
| **Pintu Masuk Sistem** | Panel Monitoring: `http://localhost/admin` (Akun RW/Kelurahan) <br> Portal Publik: `http://localhost/` (Tanpa Login) |

---

## DAFTAR ISI
1. [BAB 1: PENDAHULUAN & RUANG LINGKUP AKSES](#bab-1-pendahuluan--ruang-lingkup-akses)
2. [BAB 2: BAGIAN A — AKSES MONITORING RW & KELURAHAN (PANEL ADMIN)](#bab-2-bagian-a--akses-monitoring-rw--kelurahan-panel-admin)
   - 2.1 [Login & Hak Akses Terbatas (Prinsip UU PDP No. 27/2022)](#21-login--hak-akses-terbatas-prinsip-uu-pdp-no-272022)
   - 2.2 [Dashboard Eksekutif Wilayah](#22-dashboard-eksekutif-wilayah)
   - 2.3 [Pemantauan Transparansi Kas Keuangan RT](#23-pemantauan-transparansi-kas-keuangan-rt)
   - 2.4 [Pemantauan Dampak Lingkungan Bank Sampah](#24-pemantauan-dampak-lingkungan-bank-sampah)
3. [BAB 3: BAGIAN B — PORTAL INFORMASI PUBLIK (TANPA LOGIN)](#bab-3-bagian-b--portal-informasi-publik-tanpa-login)
   - 3.1 [Beranda Publik SIM-LiBas RT 15](#31-beranda-publik-sim-libas-rt-15)
   - 3.2 [Direktori & Standar Pelayanan Warga](#32-direktori--standar-pelayanan-warga)
   - 3.3 [Katalog & Etalase UMKM Warga](#33-katalog--etalase-umkm-warga)
   - 3.4 [Profil & Daftar Harga Bank Sampah Pinang Masak Lestari](#34-profil--daftar-harga-bank-sampah-pinang-masak-lestari)
   - 3.5 [Sekretariat, Peta Lokasi, & Kontak Darurat](#35-sekretariat-peta-lokasi--kontak-darurat)
4. [BAB 4: FITUR VERIFIKASI KEASLIAN SURAT VIA QR CODE](#bab-4-fitur-verifikasi-keaslian-surat-via-qr-code)
   - 4.1 [Latar Belakang & Mekanisme Pengamanan Anti-Pemalsuan](#41-latar-belakang--mekanisme-pengamanan-anti-pemalsuan)
   - 4.2 [Tata Cara Pemindaian QR Code oleh Instansi Eksternal](#42-tata-cara-pemindaian-qr-code-oleh-instansi-eksternal)
   - 4.3 [Interpretasi Hasil Verifikasi Surat](#43-interpretasi-hasil-verifikasi-surat)
5. [BAB 5: DAFTAR CHECKLIST TANGKAPAN LAYAR (SCREENSHOT)](#bab-5-daftar-checklist-tangkapan-layar-screenshot)

---

## BAB 1: PENDAHULUAN & RUANG LINGKUP AKSES

Sistem Informasi Manajemen RT 15 Pematang Sulur (**SIM-LiBas**) dirancang dengan prinsip keterbukaan informasi publik dan akuntabilitas tata kelola lingkungan, sekaligus menjunjung tinggi kepatuhan hukum terhadap **Undang-Undang Perlindungan Data Pribadi (UU PDP No. 27 Tahun 2022)**.

Buku Panduan ini mencakup 2 (dua) segmen audiens penting:
1. **Pihak Pembina Wilayah (Ketua RW 04 & Pihak Kelurahan Pematang Sulur)**: Diberikan akun khusus pengawas (*Supervisor/Auditor Role*) untuk memantau kinerja operasional, transparansi keuangan kas, dan capaian reduksi sampah di tingkat RT tanpa memiliki kewenangan menyunting data atau melihat data privasi kependudukan (NIK/identitas keluarga).
2. **Masyarakat Publik, Tamu Wilayah, & Instansi Pemeriksa**: Seluruh pihak luar yang ingin mengetahui profil lingkungan RT 15, mengecek persyaratan permohonan surat, berbelanja produk lokal UMKM, melihat harga beli bank sampah, maupun memverifikasi keabsahan surat pengantar resmi RT 15 secara mandiri langsung melalui peramban web tanpa memerlukan akun login.

---

## BAB 2: BAGIAN A — AKSES MONITORING RW & KELURAHAN (PANEL ADMIN)

### 2.1 Login & Hak Akses Terbatas (Prinsip UU PDP No. 27/2022)

Pihak Rukun Warga (RW 04) maupun Kelurahan Pematang Sulur dapat diberikan akses masuk ke Panel Kontrol Pengurus melalui akun berhak akses `rw_kelurahan`.

1. Buka peramban web dan tuju alamat: `http://localhost/admin` (atau domain server aktif).
2. Masukkan **Alamat Email** dan **Kata Sandi** resmi yang telah didaftarkan oleh Super Admin.
3. Klik tombol **Masuk (Sign In)**.

> [!IMPORTANT]
> **Kebijakan Pembatasan Akses Privasi Warga (UU PDP No. 27/2022):**  
> Demi melindungi kerahasiaan Nomor Induk Kependudukan (NIK), susunan Kartu Keluarga, nomor kontak pribadi, dan dokumen kependudukan warga dari penyalahgunaan data, akun role `rw_kelurahan` secara otomatis **TIDAK DIBERIKAN AKSES** ke menu Data Warga (*Residents*), Data Kartu Keluarga (*Households*), Detail Pengajuan Surat Warga, maupun Riwayat Pengaduan Internal. Menu yang terbuka murni berupa ringkasan statistik makro, audit kas umum, dan volume bank sampah.

---

### 2.2 Dashboard Eksekutif Wilayah

Setelah berhasil login, pimpinan RW/Kelurahan akan disambut oleh Dashboard Eksekutif terintegrasi.

```
+---------------------------------------------------------------------------------------+
|  SIM-LiBas Admin Panel   [Bell Notif]  [Pimpinan RW 04 - Kelurahan Pematang Sulur] v |
+---------------------------------------------------------------------------------------+
| Navigasi:                                                                             |
|  [Dashboard]                                                                          |
|  Keuangan RT                                                                          |
|   - Transaksi Kas RT                                                                  |
|  Bank Sampah                                                                          |
|   - Setoran Sampah                                                                    |
+---------------------------------------------------------------------------------------+
| WIDGET RINGKASAN MONITORING WILAYAH RT 15:                                            |
|                                                                                       |
|  [ Saldo Kas RT Terkini ]    [ Total Pemasukan Kas ]     [ Total Pengeluaran Kas ]    |
|  Rp 14.850.000               Rp 22.400.000               Rp 7.550.000                 |
|  Status: Sehat & Likuid      Tahun Anggaran Berjalan     Audit Terbuka                |
|                                                                                       |
|  [ Total Sampah Terkelola ]  [ Partisipasi Rumah Tangga ]                             |
|  1.428,50 Kg                 48 KK Terdaftar Menabung                                 |
|  Reduksi Sampah Organik/Anor Efisiensi Program 78%                                    |
+---------------------------------------------------------------------------------------+
```

> 📸 **[SLOT TANGKAPAN LAYAR: SS-PUB-008]**  
> - **Halaman/Tampilan:** Panel Admin Filament (`/admin`) pada Akun Role RW / Kelurahan  
> - **Elemen yang Ditampilkan:** Tampilan beranda dashboard pengawas yang menampilkan widget saldo kas RT, statistik total timbangan sampah terkelola, dan menu navigasi samping yang ramping/terbatas.  
> - **Fokus/Aksi Pengguna:** Menunjukkan transparansi pembinaan wilayah dan kepatuhan privasi (menu warga disembunyikan).  
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/SS-PUB-008-dashboard-monitoring-rw.png`

---

### 2.3 Pemantauan Transparansi Kas Keuangan RT

Pihak pembina wilayah dapat memantau secara berkala seluruh aliran dana kas RT yang dikelola oleh Bendahara RT.

1. Klik menu **Keuangan RT** > **Transaksi Kas RT** pada bilah navigasi samping.
2. Sistem menyajikan tabel buku kas umum:
   - Tanggal pencatatan transaksi.
   - Kategori kas (Iuran Warga, Donasi/Sumbangan, Biaya Operasional Kebersihan, Keamanan, Perawatan Lampu Jalan, dll.).
   - Jenis aliran (Pemasukan bertanda hijau / Pengeluaran bertanda merah).
   - Nominal rupiah.
   - Keterangan peruntukan dana.
   - Status bukti nota fisik/digital.
3. **Pencarian & Penyaringan**: Gunakan bilah filter untuk meninjau pembukuan per bulan atau kategori kegiatan tertentu guna persiapan rapat koordinasi tingkat RW atau musrenbang kelurahan.

> [!NOTE]
> Akun RW/Kelurahan berstatus *Read-Only* (Hanya Baca). Tombol *Buat Baru (Create)*, *Ubah (Edit)*, dan *Hapus (Delete)* dinonaktifkan demi menjamin integritas pembukuan bendahara.

---

### 2.4 Pemantauan Dampak Lingkungan Bank Sampah

Sebagai tolok ukur keberhasilan program Kampung Ramah Lingkungan (*Clean & Green Village*):

1. Klik menu **Bank Sampah** > **Setoran Sampah** pada navigasi samping.
2. Tinjau riwayat penerimaan timbangan sampah warga:
   - Tanggal penimbangan.
   - Jenis kategori sampah (Kardus, Kertas Arsip, Plastik Tebal/Kresek, Kaleng Logam, Minyak Jelantah).
   - Berat timbangan (Kg).
   - Nilai konversi rupiah ke kas tabungan warga.
3. Pimpinan kelurahan dapat memanfaatkan data kumulatif ini untuk pelaporan ke Dinas Lingkungan Hidup (DLH) Kota Jambi terkait keberhasilan program pengurangan timbulan sampah dari sumbernya (*Zero Waste to Landfill*).

---

## BAB 3: BAGIAN B — PORTAL INFORMASI PUBLIK (TANPA LOGIN)

Masyarakat umum, tamu wilayah, calon warga baru, maupun mitra dapat langsung membuka situs SIM-LiBas RT 15 melalui peramban web telepon pintar maupun komputer tanpa harus melakukan login.

### 3.1 Beranda Publik SIM-LiBas RT 15

Buka alamat situs utama: `http://localhost/`

```
+---------------------------------------------------------------------------------------+
|  [Logo RT 15] SIM-LIBAS   Beranda  Layanan  UMKM  Bank Sampah  Kontak    [Masuk Warga]|
+---------------------------------------------------------------------------------------+
| HERO SECTION:                                                                         |
| "Selamat Datang di Portal Resmi RT 15 Kelurahan Pematang Sulur"                       |
| Inisiatif Kampung Digital Pinang Masak Lestari: Guyub, Transparan, & Mandiri.         |
| [Jelajahi Layanan Warga]  [Katalog UMKM Kami]                                         |
+---------------------------------------------------------------------------------------+
| METRIK CAPAIAN WILAYAH:                                                               |
|  [ 124 KK Terdata ]  [ 412 Jiwa Warga ]  [ 1.428 Kg Sampah ]  [ 18 Pelaku UMKM ]      |
+---------------------------------------------------------------------------------------+
| PENGUMUMAN TERBARU & AGENDA KEGIATAN:                                                 |
| - Pengumuman Kerja Bakti Massal Sambut HUT RI 15                                      |
| - Jadwal Fogging Nyamuk DBD Minggu Depan                                              |
| - Pendaftaran Lomba Senam Lansia Sehat                                                |
+---------------------------------------------------------------------------------------+
```

> 📸 **[SLOT TANGKAPAN LAYAR: SS-PUB-001]**  
> - **Halaman/Tampilan:** Beranda Depan Portal Publik (`/`)  
> - **Elemen yang Ditampilkan:** Header navigasi publik, spanduk (hero section) Kampung Digital, widget 4 angka statistik wilayah, serta kartu pengumuman dan agenda warga terbaru.  
> - **Fokus/Aksi Pengguna:** Tampilan visual selamat datang masyarakat publik dan keterbukaan informasi lingkungan.  
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/SS-PUB-001-beranda-portal-publik.png`

---

### 3.2 Direktori & Standar Pelayanan Warga

Masyarakat yang membutuhkan informasi tata cara kepengurusan surat keterangan dapat mengakses menu **Layanan**:

1. Klik tautan navigasi **Layanan** di bagian atas beranda atau buka `http://localhost/layanan`.
2. Halaman menampilkan **Katalog Jenis Layanan Administrasi Resmi RT 15**:
   - Surat Pengantar Umum (KTP / KK Baru).
   - Surat Keterangan Domisili Warga / Usaha.
   - Surat Keterangan Tidak Mampu (SKTM).
   - Surat Pengantar Nikah (Model N1-N4).
   - Surat Keterangan Kematian / Kelahiran.
   - Surat Keterangan Izin Usaha Mikro.
3. Klik pada salah satu kartu layanan untuk membuka rincian (**Detail Layanan** di `/layanan/{slug}`):
   - Dokumen persyaratan yang wajib disiapkan (KTP asli, fotokopi KK, buku nikah, dsb.).
   - Standar Operasional Prosedur (SOP) dan estimasi waktu penerbitan (1x24 jam).
   - Tombol cepat **Ajukan Surat Sekarang** (mengarahkan warga login ke portal mandiri).

> 📸 **[SLOT TANGKAPAN LAYAR: SS-PUB-002]**  
> - **Halaman/Tampilan:** Direktori Katalog Layanan Warga (`/layanan`)  
> - **Elemen yang Ditampilkan:** Grid kartu layanan administrasi surat pengantar RT, ikon kategori, deskripsi singkat, dan perkiraan durasi proses.  
> - **Fokus/Aksi Pengguna:** Panduan warga sebelum mengajukan permohonan surat.  
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/SS-PUB-002-katalog-layanan-warga.png`

> 📸 **[SLOT TANGKAPAN LAYAR: SS-PUB-003]**  
> - **Halaman/Tampilan:** Halaman Rincian Layanan (`/layanan/{slug}`)  
> - **Elemen yang Ditampilkan:** Kotak rincian persyaratan berkas, alur tahapan verifikasi RT, dan tombol tautan login mandiri warga.  
> - **Fokus/Aksi Pengguna:** Memastikan kelengkapan berkas pemohon sebelum mengajukan surat secara online.  
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/SS-PUB-003-detail-prosedur-layanan.png`

---

### 3.3 Katalog & Etalase UMKM Warga

Portal publik SIM-LiBas berfungsi sebagai etalase digital gratis untuk mempromosikan usaha mikro, kecil, dan menengah milik warga RT 15.

1. Klik menu navigasi **UMKM** atau kunjungi alamat `http://localhost/katalog-umkm`.
2. Pengunjung disajikan etalase interaktif produk lokal:
   - **Pencarian Nama Usaha / Produk**: Masukkan kata kunci (misal: "Kue Basah", "Pempek", "Laundri", "Jahit Baju").
   - **Penyaring Kategori**: Filter berdasarkan kategori Kuliner, Jasa, Busana, Kerajinan, atau Warung Sembako.
3. Setiap kartu produk menampilkan:
   - Foto produk asli berkualitas tinggi.
   - Nama toko / pemilik usaha.
   - Kisaran harga.
   - Tombol hijau **Hubungi via WhatsApp**: Pengunjung yang tertarik dapat langsung mengklik tombol ini untuk terhubung ke nomor WhatsApp pemilik usaha tanpa perantara.

```
+---------------------------------------------------------------------------------------+
|  KATALOG UMKM WARGA RT 15 PEMATANG SULUR                                              |
|  [Cari Produk / Usaha...     ] [Semua Kategori v] [Tombol Cari]                       |
+---------------------------------------------------------------------------------------+
|  [Foto Produk]                 [Foto Produk]                 [Foto Produk]            |
|  Pempek Mak Cik Pinang         Dapur Kue Ibu Sari            Jahit Barokah Taylor     |
|  Kategori: Kuliner             Kategori: Kuliner             Kategori: Jasa Pakaian   |
|  Harga: Rp 2.000 / pcs         Harga: Rp 25.000 / kotak      Harga: Mulai Rp 40.000   |
|  [ Chat WhatsApp Pemilik ]     [ Chat WhatsApp Pemilik ]     [ Chat WhatsApp Pemilik ]|
+---------------------------------------------------------------------------------------+
```

> 📸 **[SLOT TANGKAPAN LAYAR: SS-PUB-004]**  
> - **Halaman/Tampilan:** Halaman Katalog UMKM Publik (`/katalog-umkm`)  
> - **Elemen yang Ditampilkan:** Filter kategori, bilah pencarian, kartu produk etalase UMKM dengan foto, label kategori, harga, dan tombol call-to-action WhatsApp.  
> - **Fokus/Aksi Pengguna:** Memperlihatkan promosi ekonomi kreatif warga RT 15 kepada masyarakat umum.  
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/SS-PUB-004-katalog-etalase-umkm.png`

---

### 3.4 Profil & Daftar Harga Bank Sampah Pinang Masak Lestari

Masyarakat dapat mempelajari program sirkular ekonomi dan pemilahan sampah mandiri:

1. Klik menu navigasi **Bank Sampah** atau tuju alamat `http://localhost/bank-sampah`.
2. Halaman memuat:
   - **Total Capaian Lingkungan**: Total kilogram timbulan sampah rumah tangga yang berhasil dicegah mencemari TPA dan total nasabah terdaftar.
   - **Tabel Daftar Harga Beli Sampah Terkini**: Tabel transparansi patokan harga per kilogram (Plastik PET, Kertas Bekas, Kardus/Box, Gelas Mineral, Besi/Tembaga, Minyak Jelantah).
   - **Jadwal Buka Penimbangan**: Informasi hari operasional timbang sampah (misal: Setiap Hari Minggu, Pukul 08.00 - 11.30 WIB di Balai RT).
   - **Infografis Panduan Pemilahan**: Edukasi mencuci botol plastik, meratakan kardus, dan menyaring minyak jelantah sebelum dibawa ke lokasi timbang.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-PUB-005]**  
> - **Halaman/Tampilan:** Halaman Bank Sampah Publik (`/bank-sampah`)  
> - **Elemen yang Ditampilkan:** Banner edukasi lingkungan, widget capaian kilogram sampah tereduksi, tabel daftar harga beli sampah terpilah terkini, dan panduan tata cara penimbangan.  
> - **Fokus/Aksi Pengguna:** Sosialisasi program ramah lingkungan kepada warga dan pihak luar.  
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/SS-PUB-005-profil-bank-sampah-publik.png`

---

### 3.5 Sekretariat, Peta Lokasi, & Kontak Darurat

Tamu atau masyarakat yang memerlukan komunikasi fisik maupun nomor darurat:

1. Klik menu **Kontak** atau tuju alamat `http://localhost/kontak`.
2. Halaman menyajikan:
   - **Alamat Sekretariat**: Lokasi Balai Pertemuan RT 15 Jl. Pinang Masak RT 15 Kel. Pematang Sulur, Kec. Telanaipura, Kota Jambi.
   - **Jam Pelayanan Kantor**: Waktu operasional tatap muka pengurus RT.
   - **Peta Lokasi Interaktif**: Tautan peta digital untuk memudahkan navigasi ke Balai RT.
   - **Daftar Nomor Penting & Darurat**:
     * Babinsa Kelurahan Pematang Sulur.
     * Bhabinkamtibmas Polsek Telanaipura.
     * Posko Damkar Kota Jambi.
     * Layanan Gawat Darurat Medis / Ambulans 119.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-PUB-006]**  
> - **Halaman/Tampilan:** Halaman Kontak & Alamat Posko RT 15 (`/kontak`)  
> - **Elemen yang Ditampilkan:** Peta lokasi sekretariat RT 15, alamat lengkap posko balai warga, jam piket pengurus, dan daftar nomor darurat terpadu.  
> - **Fokus/Aksi Pengguna:** Memberikan saluran pertolongan cepat dan transparansi alamat fisik sekretariat.  
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/SS-PUB-006-kontak-dan-lokasi-rt.png`

---

## BAB 4: FITUR VERIFIKASI KEASLIAN SURAT VIA QR CODE

### 4.1 Latar Belakang & Mekanisme Pengamanan Anti-Pemalsuan

Di era modern, pemalsuan dokumen fisik cap dan tanda tangan basah menjadi kerentanan serius bagi instansi pemerintah dan perbankan. SIM-LiBas memecahkan masalah ini melalui fitur **Verifikasi Keaslian Surat Pengantar Berbasis QR Code Otomatis (FR-PUB-03)**.

Setiap lembar fisik Surat Pengantar yang diterbitkan oleh Ketua RT 15 dilengkapi dengan kode matriks dua dimensi (**QR Code unik**) yang dihasilkan dari token kriptografi acak saat surat disetujui secara digital.

---

### 4.2 Tata Cara Pemindaian QR Code oleh Instansi Eksternal

Bagi petugas instansi pemeriksa (Kelurahan Pematang Sulur, Petugas Kantor Camat Telanaipura, Pegawai Bank, Petugas Disdukcapil, BPJS, atau KUA):

1. Ambil ponsel pintar atau pemindai kamera (QR Scanner / Google Lens).
2. Arahkan kamera ponsel tepat ke **QR Code** yang tercetak di sudut bawah lembar surat pengantar warga.
3. Kamera akan mendeteksi tautan resmi sistem:
   `http://simlibas.com/verifikasi-surat/TOKEN_UNIK`
4. Ketuk tautan tersebut. Peramban web akan langsung membuka halaman verifikasi tanpa memerlukan instalasi aplikasi tambahan atau login akun.

---

### 4.3 Interpretasi Hasil Verifikasi Surat

Halaman verifikasi publik dirancang memberikan kepastian hukum yang instan:

#### A. Dokumen Asli, Resmi, & Terdaftar
Bila dokumen tersebut valid dan sah tercatat di database RT 15, layar akan menampilkan:
- **Badge Status Keaslian**: Lingkaran tanda centang hijau dengan teks **"DOKUMEN ASLI & TERVERIFIKASI RESMI"**.
- **Nomor Surat Resmi**: Sesuai dengan yang tertera pada lembar kertas fisik.
- **Jenis Pengantar**: Keterangan keperluan (misal: *Surat Keterangan Pengantar Pembuatan KTP*).
- **Nama Warga Pemohon**: Ditampilkan nama lengkap warga yang bersangkutan.
- **Tanggal & Waktu Pengesahan**: Waktu pasti ketika Ketua RT membubuhkan persetujuan digital.
- **Pejabat Penandatangan**: Nama Ketua RT 15 dan masa bakti pengurus.

```
+---------------------------------------------------------------------------------------+
|                 SISTEM VERIFIKASI KEABSAHAN DOKUMEN ELEKTRONIK                        |
|                  RT 15 KELURAHAN PEMATANG SULUR - KOTA JAMBI                          |
+---------------------------------------------------------------------------------------+
|                                                                                       |
|                         [ CENTANG HIJAU BESAR ]                                       |
|                       DOKUMEN ASLI & RESMI TERDAFTAR                                  |
|               Surat ini tercatat sah dalam pangkalan data digital RT 15               |
|                                                                                       |
|   Detail Pengesahan Dokumen:                                                          |
|   ---------------------------------------------------------------------------------   |
|   Nomor Registrasi Surat : 470 / 015 / PEM / X / 2026                                 |
|   Jenis Pelayanan        : Surat Pengantar KTP / Domisili                             |
|   Nama Pemohon           : Bpk. Dedi Kurniawan                                        |
|   Tanggal Persetujuan    : 18 Oktober 2026, Pukul 14:20 WIB                           |
|   Disahkan Oleh          : Bpk. H. Ahmad Sudirman, S.E. (Ketua RT 15)                 |
|   Status Keberlakuan     : Berlaku Sah untuk Instansi Terkait                         |
|                                                                                       |
|   [!] PENTING - KEPATUHAN PRIVASI (UU PDP NO. 27 TAHUN 2022):                         |
|   Sistem dengan sengaja TIDAK MENAMPILKAN Nomor Induk Kependudukan (NIK), alamat      |
|   lengkap, dan data keluarga pada halaman publik ini demi melindungi privasi warga.   |
+---------------------------------------------------------------------------------------+
```

#### B. Dokumen Palsu atau Tidak Terdaftar
Jika QR Code dimanipulasi, token diubah, atau surat tidak pernah dikeluarkan oleh pengurus RT 15:
- Layar akan menampilkan tanda silang merah dengan tulisan **"PERINGATAN: DOKUMEN TIDAK DITEMUKAN ATAU TIDAK VALID"**.
- Menyarankan petugas instansi untuk mengonfirmasi langsung ke Sekretariat RT 15 guna mencegah penipuan dokumen.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-PUB-007]**  
> - **Halaman/Tampilan:** Halaman Publik Hasil Verifikasi Surat via QR Code (`/verifikasi-surat/{token}`)  
> - **Elemen yang Ditampilkan:** Logo RT 15, lencana hijau verifikasi resmi, nomor surat, nama pemohon, tanggal validasi, tanda tangan digital Ketua RT, serta klausul kepatuhan UU PDP.  
> - **Fokus/Aksi Pengguna:** Membuktikan kemudahan instansi eksternal memeriksa otentisitas dokumen RT 15.  
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/SS-PUB-007-verifikasi-keaslian-surat-qr.png`

---

## BAB 5: DAFTAR CHECKLIST TANGKAPAN LAYAR (SCREENSHOT)

Bagi tim administrator dan dokumentasi, berikut adalah panduan daftar tangkapan layar yang harus diambil untuk melengkapi berkas Buku Panduan Role RW / Kelurahan & Portal Publik ini:

| ID Tangkapan Layar | Alamat URL / Akses Menu | Deskripsi Tampilan yang Harus Difoto | Rekomendasi Nama Berkas Gambar | Status |
| :--- | :--- | :--- | :--- | :---: |
| **SS-PUB-001** | `/` (Beranda Publik) | Spanduk selamat datang, 4 metrik capaian wilayah, kartu pengumuman, dan agenda terkini | `SS-PUB-001-beranda-portal-publik.png` | `[ ]` |
| **SS-PUB-002** | `/layanan` | Halaman katalog seluruh layanan surat pengantar resmi yang disediakan RT 15 | `SS-PUB-002-katalog-layanan-warga.png` | `[ ]` |
| **SS-PUB-003** | `/layanan/{slug}` | Detail salah satu layanan (misal: KTP), kotak persyaratan berkas, dan tombol ajukan | `SS-PUB-003-detail-prosedur-layanan.png` | `[ ]` |
| **SS-PUB-004** | `/katalog-umkm` | Etalase etalase UMKM warga dengan foto produk, kategori, dan tombol WhatsApp | `SS-PUB-004-katalog-etalase-umkm.png` | `[ ]` |
| **SS-PUB-005** | `/bank-sampah` | Profil Bank Sampah Pinang Masak Lestari, capaian kg sampah, dan tabel daftar harga | `SS-PUB-005-profil-bank-sampah-publik.png` | `[ ]` |
| **SS-PUB-006** | `/kontak` | Denah peta balai pertemuan, alamat sekretariat RT 15, jam buka, dan kontak darurat | `SS-PUB-006-kontak-dan-lokasi-rt.png` | `[ ]` |
| **SS-PUB-007** | `/verifikasi-surat/{token}` | Halaman hasil pemindaian QR Code dengan lencana hijau dokumen sah dan nomor surat | `SS-PUB-007-verifikasi-keaslian-surat-qr.png` | `[ ]` |
| **SS-PUB-008** | `/admin` (Akun `rw_kelurahan`) | Dashboard monitoring pembina wilayah, widget saldo kas, volume sampah, menu terbatas | `SS-PUB-008-dashboard-monitoring-rw.png` | `[ ]` |

---
*Dokumen ini merupakan bagian dari Buku Panduan Pengguna Resmi Sistem Informasi Manajemen RT 15 Pematang Sulur (SIM-LiBas).*
