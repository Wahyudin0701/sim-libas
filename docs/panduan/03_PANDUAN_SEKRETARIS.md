# BUKU PANDUAN PENGGUNA SIM-LiBas
## TAHAP 2B — PANDUAN ROLE SEKRETARIS RT
**Sistem Informasi Manajemen RT 15 Pematang Sulur, Kecamatan Telanaipura, Kota Jambi**

---

## DAFTAR ISI
1. [Peran & Tanggung Jawab Utama Sekretaris RT](#1-peran--tanggung-jawab-utama-sekretaris-rt)
2. [Akses Masuk & Navigasi Panel Pengurus (/admin)](#2-akses-masuk--navigasi-panel-pengurus-admin)
   - 2.1 Masuk Menggunakan Akun Sekretaris
   - 2.2 Mengenal Tampilan & Menu Operasional Sekretaris
3. [Manajemen Data Kartu Keluarga (KK)](#3-manajemen-data-kartu-keluarga-kk)
   - 3.1 Perekaman Kartu Keluarga Baru
   - 3.2 Menentukan Status Hunian & Mengunggah Foto Berkas KK Fisik
   - 3.3 Mengelola Anggota Keluarga di Bawah Naungan KK
   - 3.4 Memperbarui & Menghapus/Mengarsipkan Data KK
4. [Manajemen Data Warga & Mutasi Kependudukan](#4-manajemen-data-warga--mutasi-kependudukan)
   - 4.1 Perekaman Warga Baru (Kategori Ber-KK vs Tanpa KK / Pendatang)
   - 4.2 Pengisian Biodata Kependudukan Lengkap & Validasi NIK 16-Digit
   - 4.3 Menetapkan Kepala Keluarga & Relasi Hubungan Keluarga
   - 4.4 Pencatatan Mutasi Warga (Pindah, Wafat, dan Kelahiran Baru)
   - 4.5 Pencarian & Filter Cepat Data Kependudukan
5. [Verifikasi Berkas Surat Pengantar & Buku Register](#5-verifikasi-berkas-surat-pengantar--buku-register)
   - 5.1 Memeriksa Kelengkapan Syarat Dokumen Permohonan Warga
   - 5.2 Mengubah Status Surat Menjadi "Diperiksa" untuk Disposisi Ketua RT
   - 5.3 Pengarsipan & Pembukuan Buku Register Surat Keluar
6. [Pengelolaan Master Jenis & Templat Surat Pengantar](#6-pengelolaan-master-jenis--templat-surat-pengantar)
   - 6.1 Menambahkan Jenis Surat Pengantar Baru
   - 6.2 Menentukan Format Nomor Registrasi Otomatis ({NO}, {ROMAN_MONTH}, {YEAR})
   - 6.3 Mengatur Daftar Syarat Dokumen Warga (Tags Input)
   - 6.4 Mengatur Urutan Tampilan & Status Aktif
7. [Publikasi Pengumuman RT & Penjadwalan Agenda Warga](#7-publikasi-pengumuman-rt--penjadwalan-agenda-warga)
   - 7.1 Menerbitkan Pengumuman (Pengaturan Target Audiens & Fitur Sematkan)
   - 7.2 Menjadwalkan Kalender Kegiatan Lingkungan (Gotong Royong, Posyandu, Rapat)
8. [Penanganan Pengaduan Layanan & Administrasi](#8-penanganan-pengaduan-layanan--administrasi)
   - 8.1 Menindaklanjuti Laporan Warga Bidang Administrasi
   - 8.2 Berkoordinasi dengan Ketua RT & Koordinator Lapangan
9. [Daftar Periksa Slot Tangkapan Layar (Checklist)](#9-daftar-periksa-slot-tangkapan-layar-checklist)

---

## 1. Peran & Tanggung Jawab Utama Sekretaris RT

Dalam ekosistem **SIM-LiBas RT 15 Pematang Sulur**, Sekretaris RT bertindak sebagai **jantung operasional dan pengelola tertib administrasi lingkungan**. Peran Sekretaris berfokus pada:

1. **Tata Kelola Data Kependudukan**: Merekam dan memperbarui buku induk Kartu Keluarga (KK) serta biodata seluruh warga RT 15, termasuk warga pendatang dan anak kos.
2. **Pencatatan Mutasi Penduduk**: Mencatat dinamika kependudukan seperti peristiwa kelahiran, kematian, kepindahan keluar, atau kedatangan warga baru.
3. **Verifikasi Awal Berkas Surat**: Memastikan warga yang mengajukan surat pengantar telah melengkapi persyaratan dokumen sebelum diteruskan ke meja Ketua RT.
4. **Master Template Surat**: Mengonfigurasi jenis surat yang dilayani RT 15 beserta format penomoran otomatis dinas.
5. **Pusat Informasi & Dokumentasi**: Menerbitkan berita pengumuman resmi dan mengatur jadwal kegiatan lingkungan warga.

---

## 2. Akses Masuk & Navigasi Panel Pengurus (/admin)

### 2.1 Masuk Menggunakan Akun Sekretaris
1. Buka peramban di komputer atau laptop Anda.
2. Buka alamat: **`http://localhost/admin`**.
3. Pada formulir login pengurus:
   - **Email**: `sekretaris@simlibas.com`
   - **Kata Sandi**: `password`
4. Klik **"Masuk ke Panel Pengurus"**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-001]**
> - **Halaman / Tampilan:** Formulir Masuk Panel Pengurus Akun Sekretaris (`/admin/login`)
> - **Elemen yang Ditampilkan:** Halaman login dengan email `sekretaris@simlibas.com` terisi dan kursor pada kolom kata sandi.
> - **Fokus / Aksi Pengguna:** Sesi login awal Sekretaris RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-001-login-sekretaris.png`

---

### 2.2 Mengenal Tampilan & Menu Operasional Sekretaris
Setelah masuk, Dasbor Sekretaris akan menampilkan ringkasan data kependudukan dan surat berjalan:
- **Kependudukan**:
  - *Kartu Keluarga (KK)*: Manajemen buku KK RT 15.
  - *Data Warga*: Master biodata penduduk perorangan.
- **Layanan Administrasi**:
  - *Surat Pengantar*: Verifikasi dan arsip surat keluar.
  - *Jenis Surat*: Pengaturan template dan format nomor surat.
- **Komunikasi Warga**:
  - *Pengumuman RT*: Pembuatan siaran berita warga.
  - *Agenda Kegiatan*: Kalender jadwal acara lingkungan.
  - *Pengaduan*: Penanganan aspirasi warga.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-002]**
> - **Halaman / Tampilan:** Dasbor Panel Pengurus Sekretaris RT 15 (`/admin`)
> - **Elemen yang Ditampilkan:** Dasbor dengan kartu sapaan *"Selamat Datang, Bpk. Bambang Irawan, S.Kom. (Sekretaris RT 15)"*, widget ringkasan total warga, total KK terdaftar, antrean surat berjalan, dan pengaduan terbuka.
> - **Fokus / Aksi Pengguna:** Tampilan kerja harian Sekretaris RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-002-dashboard-sekretaris-rt.png`

---

## 3. Manajemen Data Kartu Keluarga (KK)

Kartu Keluarga adalah entitas induk tempat warga bernaung di dalam sistem.

### 3.1 Perekaman Kartu Keluarga Baru
1. Pada menu bilah samping, klik grup **Kependudukan > Kartu Keluarga (KK)** (`/admin/households`).
2. Klik tombol biru **"+ Buat Kartu Keluarga"** di sudut kanan atas.
3. Halaman formulir pendaftaran KK (`/admin/households/create`) akan terbuka:
   - **Nomor Kartu Keluarga (KK)**: Masukkan 16 digit angka resmi yang tertera pada lembar KK dari Dinas Dukcapil.
   - **Alamat Rumah**: Ketikkan alamat rumah lengkap di lingkungan RT 15 (contoh: *Jl. Pinang Masak No. 15 Blok A, RT 15*).
   - **Status Hunian**: Pilih kepemilikan hunian:
     - `Tetap (Milik Sendiri)`: Warga menetap pemilik rumah.
     - `Kontrak / Sewa`: Warga yang mengontrak satu unit rumah.
     - `Kos`: Rumah sewa per kamar.
4. Klik tombol **"Buat"** untuk menyimpan. Sistem secara otomatis akan menghasilkan kode registrasi internal unik (misal: `KK-001`).

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-003]**
> - **Halaman / Tampilan:** Formulir Tambah Kartu Keluarga Baru (`/admin/households/create`)
> - **Elemen yang Ditampilkan:** Form isian Nomor Kartu Keluarga (16 digit), textarea Alamat Rumah, dropdown Status Hunian (Tetap/Kontrak/Kos), kolom upload Foto Dokumen KK, dan tombol Buat.
> - **Fokus / Aksi Pengguna:** Perekaman berkas KK baru warga RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-003-form-tambah-kartu-keluarga.png`

---

### 3.2 Menentukan Status Hunian & Mengunggah Foto Berkas KK Fisik
Pada formulir KK:
1. Bagian **Foto Dokumen KK**: Klik area unggah berkas untuk melampirkan foto atau hasil pindai (*scan*) lembar asli Kartu Keluarga (format JPG, PNG, atau PDF hingga ukuran 5MB).
2. Foto ini berfungsi sebagai arsip cadangan digital RT apabila lembar fisik warga hilang atau rusak karena bencana.
3. **Akun Login Warga (Opsional)**: Tautkan dengan akun login pengguna yang sudah terdaftar di sistem agar kepala keluarga langsung terhubung dengan dasbor warga.

---

### 3.3 Mengelola Anggota Keluarga di Bawah Naungan KK
Setelah kartu keluarga tersimpan:
1. Buka halaman detail atau edit KK tersebut.
2. Di bagian bawah formulir, terdapat tabel relasi **Anggota Keluarga (Residents)**.
3. Sekretaris dapat langsung menambahkan anggota keluarga baru ke dalam KK ini tanpa perlu berpindah halaman dengan menekan tombol **"Tambah Anggota Keluarga"**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-004]**
> - **Halaman / Tampilan:** Tabel Relasi Anggota Keluarga pada Detail KK
> - **Elemen yang Ditampilkan:** Bagian bawah halaman edit KK yang memuat tabel relasi anggota keluarga terdaftar dengan kolom NIK, Nama Lengkap, Hubungan Keluarga, dan tombol "Tambah Anggota".
> - **Fokus / Aksi Pengguna:** Pengelolaan susunan keluarga dalam satu KK.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-004-tabel-relasi-anggota-kk.png`

---

### 3.4 Memperbarui & Menghapus/Mengarsipkan Data KK
- **Edit KK**: Klik ikon pensil untuk memperbarui alamat jika warga berpindah blok rumah atau memperbarui foto dokumen KK yang baru dicetak Dukcapil.
- **Hapus / Arsipkan KK**: Jika satu keluarga pindah domisili secara permanen keluar dari Kota Jambi, klik tombol **Hapus** untuk memindahkan data ke tempat sampah (*Soft Delete*) demi menjaga riwayat data historis iuran dan surat masa lalu.

---

## 4. Manajemen Data Warga & Mutasi Kependudukan

### 4.1 Perekaman Warga Baru
1. Buka menu **Kependudukan > Data Warga** (`/admin/residents`).
2. Klik tombol **"+ Buat Warga"** di sudut kanan atas (`/admin/residents/create`).
3. Pilih **Kategori Warga**:
   - `Memiliki KK di RT 15`: Untuk warga yang KK-nya beralamat di RT 15.
   - `Tanpa KK di RT 15 (Kontrak / Kos / Pendatang)`: Untuk warga yang KK asalnya dari luar daerah namun tinggal/kost di lingkungan RT 15.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-005]**
> - **Halaman / Tampilan:** Formulir Tambah Data Warga Baru Bagian Kategori (`/admin/residents/create`)
> - **Elemen yang Ditampilkan:** Form pendaftaran warga baru dengan pilihan Kategori Warga (Memiliki KK vs Tanpa KK), pemilihan relasi Kartu Keluarga, dan status tempat tinggal.
> - **Fokus / Aksi Pengguna:** Pengkategorian kependudukan warga RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-005-form-kategori-warga-baru.png`

---

### 4.2 Pengisian Biodata Kependudukan Lengkap & Validasi NIK
Lengkapi seluruh kolom identitas resmi:
- **NIK (16 Digit)**: Nomor Induk Kependudukan wajib diisi 16 digit angka (Sistem akan otomatis mengenkripsi dan membuat `nik_hash` sesuai UU Pelindungan Data Pribadi).
- **Nama Lengkap**: Nama lengkap beserta gelar akademik/keagamaan (jika ada).
- **Tempat & Tanggal Lahir**: Pilih tanggal lahir melalui kalender pemilih tanggal.
- **Jenis Kelamin**: Pilih *Laki-laki* atau *Perempuan*.
- **Agama, Pendidikan Terakhir, dan Pekerjaan**.
- **Status Perkawinan**: *Belum Kawin*, *Kawin*, *Cerai Hidup*, atau *Cerai Mati*.
- **Nomor Telepon / WhatsApp**: Digunakan untuk pengiriman notifikasi dan pemulihan akun.
- **Alamat Email**.

---

### 4.3 Menetapkan Kepala Keluarga & Hubungan Keluarga
- **Hubungan Keluarga**: Tentukan kedudukan dalam keluarga (*Kepala Keluarga*, *Istri*, *Anak*, *Menantu*, *Cucu*, *Orang Tua*, *Mertua*, atau *Famili Lain*).
- **Saklar Kepala Keluarga (Is Kepala Keluarga)**: Aktifkan saklar hijau ini jika warga bersangkutan bertindak sebagai Kepala Keluarga utama pada KK tersebut.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-006]**
> - **Halaman / Tampilan:** Formulir Biodata Kependudukan & Saklar Kepala Keluarga
> - **Elemen yang Ditampilkan:** Form isian NIK, Nama, Tanggal Lahir, dropdown Hubungan Keluarga, kolom Telepon/WA, dan saklar toggle hijau *"Kepala Keluarga"*.
> - **Fokus / Aksi Pengguna:** Penetapan status Kepala Keluarga pada data warga.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-006-form-biodata-warga-lengkap.png`

---

### 4.4 Pencatatan Mutasi Warga
Jika terjadi perubahan status kependudukan pada warga:
1. Buka data warga terkait, lalu ubah kolom **Status Kependudukan**:
   - `Aktif`: Warga tinggal dan beraktivitas normal di RT 15.
   - `Pindah`: Warga telah pindah domisili (arsip tetap tersimpan namun akun tidak aktif).
   - `Meninggal`: Warga telah berpulang (untuk arsip pencatatan kematian RT).
   - `Lahir Baru`: Bayi yang baru lahir dan sedang proses pendaftaran akta/KK.
2. Klik tombol **Simpan**.

---

### 4.5 Pencarian & Filter Cepat Data Kependudukan
Pada tabel utama warga (`/admin/residents`):
- Gunakan kotak **Pencarian (Search)** untuk menemukan warga secara instan cukup dengan mengetikkan nama atau nomor NIK.
- Gunakan menu **Filter** untuk menyaring warga berdasarkan:
  - Status kependudukan (Aktif, Pindah, Meninggal).
  - Jenis kelamin (L/P).
  - Status hubungan keluarga.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-007]**
> - **Halaman / Tampilan:** Tabel Data Warga dengan Kotak Pencarian & Filter Aktif
> - **Elemen yang Ditampilkan:** Tabel data warga RT 15 lengkap dengan bar pencarian di sudut kanan atas dan filter status kependudukan yang sedang menyaring warga aktif.
> - **Fokus / Aksi Pengguna:** Penelusuran data warga secara cepat oleh Sekretaris.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-007-tabel-warga-pencarian-filter.png`

---

## 5. Verifikasi Berkas Surat Pengantar & Buku Register

Sekretaris RT bertanggung jawab menyaring permohonan surat dari warga sebelum disahkan oleh Ketua RT.

### 5.1 Memeriksa Kelengkapan Syarat Dokumen
1. Buka menu **Layanan Administrasi > Surat Pengantar** (`/admin/letters`).
2. Periksa surat dengan status 🟡 **Diajukan**.
3. Klik tombol **Lihat** (ikon mata) untuk memeriksa:
   - Apakah nama warga dan NIK sesuai dengan database kependudukan RT 15.
   - Apakah maksud keperluan permohonan logis dan lengkap.
   - Apakah syarat dokumen pendukung (misal: fotokopi KTP/PBB/pengantar nikah) sudah diserahkan ke Sekretariat RT.

---

### 5.2 Mengubah Status Surat Menjadi "Diperiksa"
Jika syarat awal telah lengkap:
1. Klik tombol **Ubah / Edit** pada surat tersebut.
2. Ubah kolom status dari `diajukan` menjadi `diperiksa`.
3. Klik **Simpan**.
4. Status 🔵 **Diperiksa** menandakan bahwa berkas telah bersih dan terverifikasi oleh Sekretaris, sehingga Ketua RT dapat langsung melakukan persetujuan (*approval*) tanpa ragu.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-008]**
> - **Halaman / Tampilan:** Formulir Pemeriksaan Status Surat Pengantar (`/admin/letters/{id}/edit`)
> - **Elemen yang Ditampilkan:** Form edit surat yang menampilkan identitas pemohon, jenis surat, dan dropdown pilihan status yang diubah menjadi "Diperiksa".
> - **Fokus / Aksi Pengguna:** Verifikasi administratif berkas surat oleh Sekretaris.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-008-verifikasi-status-surat.png`

---

### 5.3 Pengarsipan & Pembukuan Buku Register Surat Keluar
Seluruh surat yang disetujui Ketua RT akan otomatis tercatat nomor registrasinya di tabel Surat Pengantar:
- Nomor urut registrasi tidak akan ganda atau terlewat karena dihitung otomatis oleh sistem per tahun berjalan.
- Sekretaris tidak perlu lagi menulis buku ekspedisi kertas manual; tabel ini berfungsi penuh sebagai **Buku Register Surat Digital RT 15** yang siap dicetak sewaktu-waktu untuk laporan ke Kantor Kelurahan Pematang Sulur.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-009]**
> - **Halaman / Tampilan:** Buku Register Digital Surat Pengantar Keluar (`/admin/letters`)
> - **Elemen yang Ditampilkan:** Tabel surat yang memuat kolom Nomor Registrasi resmi ber-badge hijau (contoh: `001/RT.15/PMS/X/2026`), nama pemohon, jenis surat, tanggal disetujui, dan nama penandatangan.
> - **Fokus / Aksi Pengguna:** Buku register surat keluar teratur rapi.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-009-buku-register-surat-keluar.png`

---

## 6. Pengelolaan Master Jenis & Templat Surat Pengantar

SIM-LiBas memungkinkan Sekretaris menambah atau mengubah format jenis surat pengantar secara dinamis tanpa bantuan programmer.

### 6.1 Menambahkan Jenis Surat Pengantar Baru
1. Buka menu **Layanan Administrasi > Jenis Surat** (`/admin/letter-types`).
2. Klik tombol **"+ Buat Jenis Surat"** di sudut kanan atas.
3. Halaman formulir master surat (`/admin/letter-types/create`) akan terbuka:
   - **Nama Jenis Surat**: Masukkan nama lengkap surat (contoh: *Surat Pengantar Keterangan Domisili Usaha*).
   - **Kode Singkat**: Masukkan kode singkatan huruf kapital (contoh: `SP-DOM-USAHA`).
   - **Deskripsi / Peruntukan**: Tuliskan panduan singkat untuk warga mengenai fungsi surat ini.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-010]**
> - **Halaman / Tampilan:** Formulir Tambah Master Jenis Surat Baru (`/admin/letter-types/create`)
> - **Elemen yang Ditampilkan:** Form isian Nama Jenis Surat, Kode Singkat, Textarea Deskripsi Peruntukan, dan Format Penomoran Resmi.
> - **Fokus / Aksi Pengguna:** Pembuatan template layanan surat baru.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-010-form-buat-jenis-surat.png`

---

### 6.2 Menentukan Format Nomor Registrasi Otomatis
Pada kolom **Format Penomoran Resmi**:
- Gunakan variabel dinamis yang disediakan sistem:
  - `{NO}`: Nomor urut surat 3 digit (001, 002, 003, dst).
  - `{ROMAN_MONTH}`: Angka romawi bulan berjalan (I, II, III, s/d XII).
  - `{YEAR}`: Tahun berjalan 4 digit (contoh: 2026).
- Format baku RT 15:
  `{NO}/SP/RT.15/{ROMAN_MONTH}/{YEAR}`
  *(Hasil otomatis saat terbit: `001/SP/RT.15/X/2026`)*.

---

### 6.3 Mengatur Daftar Syarat Dokumen Warga
Pada kolom **Persyaratan Dokumen Warga (Tags Input)**:
1. Ketikkan dokumen syarat yang wajib dipersiapkan warga, lalu tekan tombol **Enter** pada keyboard untuk setiap butir syarat:
   - Contoh: ketik `Fotokopi KTP Pemohon` [Enter], lalu ketik `Fotokopi Kartu Keluarga RT 15` [Enter], lalu ketik `Bukti Lunas PBB Terbaru` [Enter].
2. Butir-butir syarat ini akan otomatis tampil di layar ponsel warga saat memilih jenis surat tersebut di portal mandiri.

---

### 6.4 Mengatur Urutan Tampilan & Status Aktif
- **Urutan Tampilan**: Masukkan angka urutan (misal: `1` untuk surat KTP agar berada di paling atas daftar pilihan warga).
- **Status Aktif**: Nyalakan saklar hijau agar jenis surat dapat dipilih warga. Matikan saklar jika layanan surat tersebut sedang ditangguhkan sementara.
- Klik **"Buat"** untuk menyimpan.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-011]**
> - **Halaman / Tampilan:** Konfigurasi Persyaratan Dokumen & Format Penomoran Surat
> - **Elemen yang Ditampilkan:** Kotak Tags Input Persyaratan Dokumen yang telah terisi tag-tag syarat (KTP, KK, Surat Pengantar Asal), format penomoran dinas, dan saklar status aktif.
> - **Fokus / Aksi Pengguna:** Konfigurasi persyaratan surat oleh Sekretaris.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-011-pengaturan-syarat-dan-nomor-surat.png`

---

## 7. Publikasi Pengumuman RT & Penjadwalan Agenda Warga

Sekretaris bertugas memastikan informasi program kerja RT tersampaikan secara cepat ke ponsel warga.

### 7.1 Menerbitkan Pengumuman Resmi
1. Buka menu **Komunikasi Warga > Pengumuman RT** (`/admin/announcements`).
2. Klik tombol **Buat Pengumuman**.
3. Isi data pengumuman:
   - **Judul**: (Contoh: *"Jadwal Pelayanan Posyandu Balita & Lansia Bulan November"*).
   - **Isi**: Tuliskan detail waktu, lokasi, dan imbauan.
   - **Target**: Pilih `Semua` (muncul di beranda warga dan pengurus).
   - **Sematkan di Atas (Is Pinned)**: Nyalakan saklar jika pengumuman ini sangat darurat/penting.
   - **Terbitkan (Is Published)**: Nyalakan saklar agar pengumuman langsung tayang.
   - **Tanggal Tayang & Tanggal Berakhir**: Tentukan rentang waktu tayang otomatis.
4. Klik **Simpan**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-012]**
> - **Halaman / Tampilan:** Formulir Pembuatan Pengumuman RT (`/admin/announcements/create`)
> - **Elemen yang Ditampilkan:** Form isian judul pengumuman, textarea konten berita, dropdown target audiens, dan pengaturan periode tayang.
> - **Fokus / Aksi Pengguna:** Publikasi informasi resmi warga oleh Sekretaris.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-012-form-buat-pengumuman-sekretaris.png`

---

### 7.2 Menjadwalkan Kalender Kegiatan Lingkungan
1. Buka menu **Komunikasi Warga > Agenda Kegiatan** (`/admin/events`).
2. Klik tombol **Buat Agenda**.
3. Isi formulir kegiatan:
   - **Judul**: (Contoh: *"Kerja Bakti Bersama Pembersihan Saluran Air Musim Hujan"*).
   - **Kategori**: Pilih `Kerja bakti`, `Rapat`, `Arisan`, `Pengajian`, atau `Perayaan`.
   - **Lokasi**: (Contoh: *"Sepanjang Jalan Pinang Masak Blok A & B"*).
   - **Tanggal Mulai & Selesai**: Pilih waktu pelaksanaan.
   - **Penyelenggara**: Ketikkan *"Seksi Kebersihan & Sekretariat RT 15"*.
4. Klik **Simpan**. Warga akan melihat agenda ini di beranda dengan kartu tanggal kalender yang menarik.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-013]**
> - **Halaman / Tampilan:** Formulir Tambah Agenda Kegiatan Warga (`/admin/events/create`)
> - **Elemen yang Ditampilkan:** Form input agenda dengan pilihan kategori acara kerja bakti, penanggalan, lokasi titik kumpul, dan penyelenggara.
> - **Fokus / Aksi Pengguna:** Pengisian kalender sosial lingkungan.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-013-form-tambah-agenda-sekretaris.png`

---

## 8. Penanganan Pengaduan Layanan & Administrasi

### 8.1 Menindaklanjuti Laporan Warga Bidang Administrasi
Jika terdapat warga yang melaporkan keluhan terkait data kependudukan (misal: salah ketik nama di KK, NIK belum terdaftar, atau kesulitan mengajukan surat):
1. Buka menu **Komunikasi Warga > Pengaduan** (`/admin/complaints`).
2. Filter pengaduan dengan kategori `Lainnya` atau `Infrastruktur`.
3. Buka detail pengaduan, verifikasi masalahnya, dan berikan tanggapan di kolom **Catatan Penanganan** (misal: *"Data NIK Anda telah diperbaiki di sistem, silakan coba ajukan kembali surat pengantar Anda"*).
4. Ubah status menjadi **Selesai**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-014]**
> - **Halaman / Tampilan:** Tabel Penanganan Pengaduan Khusus Sekretaris RT
> - **Elemen yang Ditampilkan:** Tabel pengaduan dengan filter kategori, kolom pelapor, lokasi, dan status penanganan yang siap diperbarui.
> - **Fokus / Aksi Pengguna:** Pelayanan responsif aspirasi warga oleh Sekretaris.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-014-tabel-pengaduan-sekretaris.png`

---

### 8.2 Berkoordinasi dengan Ketua RT & Koordinator Lapangan
Sekretaris dapat mengarahkan pengaduan fisik (seperti tumpukan sampah atau lampu jalan mati) kepada Koordinator Bank Sampah atau Koordinator Keamanan melalui penugasan kolom **Penanggung Jawab**, sehingga seluruh jajaran pengurus RT 15 bekerja dalam satu koordinasi terpadu.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-SEK-015]**
> - **Halaman / Tampilan:** Modal Disposisi Pengaduan ke Koordinator Lapangan
> - **Elemen yang Ditampilkan:** Form penugasan penanggung jawab penanganan pengaduan dengan pilihan jajaran pengurus RT 15.
> - **Fokus / Aksi Pengguna:** Koordinasi lintas seksi kepengurusan RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-sek-015-disposisi-pengaduan-lapangan.png`

---

## 9. Daftar Periksa Slot Tangkapan Layar (Checklist)

Gunakan tabel daftar periksa berikut saat Anda mengambil dan memasukkan foto tangkapan layar ke dalam dokumen panduan Sekretaris RT ini:

| No | Kode Slot Tangkapan Layar | Halaman Antarmuka Terkait | Rekomendasi Nama Berkas Gambar | Status Gambar |
|:---:|---|---|---|:---:|
| 1 | `SS-SEK-001` | Formulir Masuk Panel Akun Sekretaris | `assets/screenshots/ss-sek-001-login-sekretaris.png` | [ ] Belum Dipasang |
| 2 | `SS-SEK-002` | Dasbor Panel Pengurus Sekretaris RT 15 | `assets/screenshots/ss-sek-002-dashboard-sekretaris-rt.png` | [ ] Belum Dipasang |
| 3 | `SS-SEK-003` | Formulir Tambah Kartu Keluarga Baru | `assets/screenshots/ss-sek-003-form-tambah-kartu-keluarga.png` | [ ] Belum Dipasang |
| 4 | `SS-SEK-004` | Tabel Relasi Anggota Keluarga pada KK | `assets/screenshots/ss-sek-004-tabel-relasi-anggota-kk.png` | [ ] Belum Dipasang |
| 5 | `SS-SEK-005` | Form Tambah Warga: Pilihan Kategori | `assets/screenshots/ss-sek-005-form-kategori-warga-baru.png` | [ ] Belum Dipasang |
| 6 | `SS-SEK-006` | Form Biodata Lengkap & Toggle Kepala Keluarga | `assets/screenshots/ss-sek-006-form-biodata-warga-lengkap.png` | [ ] Belum Dipasang |
| 7 | `SS-SEK-007` | Tabel Warga dengan Pencarian & Filter | `assets/screenshots/ss-sek-007-tabel-warga-pencarian-filter.png` | [ ] Belum Dipasang |
| 8 | `SS-SEK-008` | Form Verifikasi Status Surat "Diperiksa" | `assets/screenshots/ss-sek-008-verifikasi-status-surat.png` | [ ] Belum Dipasang |
| 9 | `SS-SEK-009` | Buku Register Digital Surat Keluar | `assets/screenshots/ss-sek-009-buku-register-surat-keluar.png` | [ ] Belum Dipasang |
| 10 | `SS-SEK-010` | Form Tambah Master Jenis Surat Baru | `assets/screenshots/ss-sek-010-form-buat-jenis-surat.png` | [ ] Belum Dipasang |
| 11 | `SS-SEK-011` | Konfigurasi Tags Syarat & Nomor Surat | `assets/screenshots/ss-sek-011-pengaturan-syarat-dan-nomor-surat.png` | [ ] Belum Dipasang |
| 12 | `SS-SEK-012` | Form Pembuatan Pengumuman RT Baru | `assets/screenshots/ss-sek-012-form-buat-pengumuman-sekretaris.png` | [ ] Belum Dipasang |
| 13 | `SS-SEK-013` | Form Penjadwalan Agenda Kegiatan Warga | `assets/screenshots/ss-sek-013-form-tambah-agenda-sekretaris.png` | [ ] Belum Dipasang |
| 14 | `SS-SEK-014` | Tabel Penanganan Pengaduan Warga | `assets/screenshots/ss-sek-014-tabel-pengaduan-sekretaris.png` | [ ] Belum Dipasang |
| 15 | `SS-SEK-015` | Modal Disposisi Pengaduan ke Koordinator | `assets/screenshots/ss-sek-015-disposisi-pengaduan-lapangan.png` | [ ] Belum Dipasang |

---
*Dokumen ini merupakan bagian dari Buku Panduan Resmi Sistem Informasi Manajemen RT 15 (SIM-LiBas).*
