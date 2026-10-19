# BUKU PANDUAN PENGGUNA SIM-LiBas
## TAHAP 5A — PANDUAN ROLE SUPER ADMIN
**Sistem Informasi Manajemen RT 15 Pematang Sulur, Kecamatan Telanaipura, Kota Jambi**

---

## DAFTAR ISI
1. [Peran & Hak Akses Penuh Super Administrator](#1-peran--hak-akses-penuh-super-administrator)
2. [Akses Masuk Panel Pengurus (/admin)](#2-akses-masuk-panel-pengurus-admin)
   - 2.1 Masuk Menggunakan Akun Administrator
   - 2.2 Hak Akses Lintas Modul (Full Privilege)
3. [Konfigurasi Identitas Wilayah & Administrasi RT 15](#3-konfigurasi-identitas-wilayah--administrasi-rt-15)
   - 3.1 Mengubah Identitas Pokok Wilayah (RT, RW, Kelurahan, Kecamatan, Kota)
   - 3.2 Memperbarui Saluran Kontak Resmi RT (Nomor WhatsApp & Email)
   - 3.3 Mengunggah Tanda Tangan Digital Ketua RT & Stempel Resmi RT
   - 3.4 Menetapkan Format Baku Penomoran Surat Pengantar
4. [Manajemen Pengguna & Otorisasi Peran (RBAC)](#4-manajemen-pengguna--otorisasi-peran-rbac)
   - 4.1 Pembuatan Akun Pengurus Baru (Ketua RT, Sekretaris, Bendahara, dll)
   - 4.2 Penugasan Role & Izin Akses (Spatie Laravel Permission)
   - 4.3 Mengaktifkan / Menangguhkan Akun Pengguna (Status Aktif vs Nonaktif)
   - 4.4 Prosedur Reset Kata Sandi Akun Warga atau Pengurus
5. [Audit Log & Pemantauan Jejak Aktivitas Sistem](#5-audit-log--pemantauan-jejak-aktivitas-sistem)
   - 5.1 Meninjau Jejak Perubahan Data Sensitif (Spatie Activitylog)
   - 5.2 Melacak Waktu, Pelaku, dan Nilai Data Sebelum vs Sesudah Diubah
6. [Daftar Periksa Slot Tangkapan Layar (Checklist)](#6-daftar-periksa-slot-tangkapan-layar-checklist)

---

## 1. Peran & Hak Akses Penuh Super Administrator

Dalam ekosistem **SIM-LiBas RT 15 Pematang Sulur**, akun **Super Administrator** memegang peranan teknis tertinggi (*Superuser / God Mode*). Super Admin bertanggung jawab atas kelangsungan operasional sistem, integritas data, keamanan akun, serta kepatuhan perlindungan data pribadi (UU PDP).

Hak istimewa Super Admin mencakup:
1. **Konfigurasi Master Sistem Wilayah**: Mengatur identitas geospasial RT 15, nama pejabat berwenang, saluran pengaduan darurat, serta berkas stempel dan tanda tangan digital.
2. **Manajemen Akun Multi-Role**: Mengontrol pembuatan, pembagian peran, dan pengaturan akses bagi seluruh jajaran pengurus (Ketua RT, Sekretaris, Bendahara, Koordinator Bank Sampah, Koordinator Keamanan, RW/Kelurahan, dan Warga).
3. **Audit Trail & Keamanan Forensik**: Memantau catatan audit log aktivitas untuk mencegah kecurangan keuangan atau manipulasi data kependudukan.
4. **Pemulihan Akses**: Membantu warga atau pengurus yang terkunci dari sistem karena lupa kata sandi.

---

## 2. Akses Masuk Panel Pengurus (/admin)

### 2.1 Masuk Menggunakan Akun Administrator
1. Buka peramban di komputer atau laptop Anda.
2. Buka alamat: **`http://localhost/admin`**.
3. Masukkan kredensial login Super Admin:
   - **Email**: `admin@simlibas.id`
   - **Kata Sandi**: `password` (atau kata sandi terbaru Anda)
4. Klik tombol **"Masuk ke Panel Pengurus"**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-ADM-001]**
> - **Halaman / Tampilan:** Formulir Masuk Panel Akun Super Admin (`/admin/login`)
> - **Elemen yang Ditampilkan:** Halaman login dengan email `admin@simlibas.id` dan kursor pada kolom kata sandi.
> - **Fokus / Aksi Pengguna:** Sesi login teknis Administrator SIM-LiBas.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-adm-001-login-super-admin.png`

---

### 2.2 Hak Akses Lintas Modul (Full Privilege)
Berbeda dari akun pengurus lainnya yang hanya melihat modul sesuai tugas pokoknya, bilah navigasi samping Super Admin membuka **seluruh grup modul tanpa batasan**:
- *Kependudukan* (KK & Warga)
- *Layanan Administrasi* (Surat Pengantar & Jenis Surat)
- *Komunikasi Warga* (Pengumuman, Agenda, Pengaduan)
- *Keamanan Lingkungan* (Jadwal Ronda, Laporan Tamu)
- *Keuangan RT* (Master Iuran, Tagihan, Pembayaran, Buku Kas)
- *Kampung Digital* (Bank Sampah, Profil UMKM)
- *Pengaturan Sistem* (Pengaturan RT & Manajemen Role)

> 📸 **[SLOT TANGKAPAN LAYAR: SS-ADM-002]**
> - **Halaman / Tampilan:** Dasbor Utama Super Administrator (`/admin`)
> - **Elemen yang Ditampilkan:** Dasbor dengan kartu sapaan *"Selamat Datang, Administrator SIM-LiBas"*, seluruh kelompok navigasi sidebar aktif, widget analitik gabungan kependudukan, keuangan, dan bank sampah.
> - **Fokus / Aksi Pengguna:** Tampilan kendali penuh sistem SIM-LiBas.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-adm-002-dashboard-super-admin.png`

---

## 3. Konfigurasi Identitas Wilayah & Administrasi RT 15

Modul **Pengaturan RT** (`/admin/rt-settings`) menjadi pusat penyesuaian identitas wilayah yang tampil di lembar cetak dokumen resmi dan portal publik:

### 3.1 Mengubah Identitas Pokok Wilayah
1. Pada menu bilah samping kiri, klik grup **Pengaturan Sistem > Pengaturan RT** (`/admin/rt-settings`).
2. Klik tombol **Ubah / Edit** pada data pengaturan RT 15.
3. Bagian **Identitas Wilayah RT**:
   - **Nama RT**: `RT 15`
   - **Nama Ketua RT**: Masukkan nama lengkap Ketua RT beserta gelar (contoh: *Bpk. H. Ahmad Sudirman, S.E.*).
   - **Alamat Sekretariat**: Alamat domisili posko pelayanan RT.
   - **Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos**: Terisi default *Pematang Sulur, Telanaipura, Kota Jambi, Jambi, 36129*.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-ADM-003]**
> - **Halaman / Tampilan:** Formulir Konfigurasi Identitas Wilayah RT 15 (`/admin/rt-settings`)
> - **Elemen yang Ditampilkan:** Form Section Identitas Wilayah RT dengan input Nama RT, Nama Ketua RT, Alamat Sekretariat, Kelurahan, Kecamatan, Kota Jambi, dan Kode Pos.
> - **Fokus / Aksi Pengguna:** Pembaruan data pokok identitas hukum wilayah RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-adm-003-form-identitas-wilayah-rt.png`

---

### 3.2 Memperbarui Saluran Kontak Resmi RT
Pada bagian **Kontak Resmi RT**:
- **Nomor Telepon / WhatsApp**: Nomor telepon layanan bantuan resmi RT 15 (nomor ini yang otomatis dituju saat warga menekan tombol bantuan atau aktivasi akun).
- **Alamat Email Resmi**: Alamat surel kedinasan RT 15.

---

### 3.3 Mengunggah Tanda Tangan Digital Ketua RT & Stempel Resmi RT
Untuk mengaktifkan otomatisasi pencetakan Surat Pengantar ber-QR Code yang sah:
1. Bagian **Format Surat & Administrasi Digital**:
   - **Tanda Tangan Digital Ketua RT**: Unggah berkas gambar tanda tangan asli Ketua RT (disarankan format PNG berlatar transparan, ukuran maksimal 2MB).
   - **Stempel Resmi RT 15**: Unggah berkas gambar cap stempel resmi RT 15 (format PNG transparan, ukuran maksimal 2MB).
2. Sistem akan menyematkan aset digital ini secara otomatis pada setiap lembar PDF surat pengantar yang telah disetujui Ketua RT.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-ADM-004]**
> - **Halaman / Tampilan:** Konfigurasi Unggah Tanda Tangan & Stempel Resmi RT
> - **Elemen yang Ditampilkan:** Form Section Administrasi Digital dengan kolom upload Tanda Tangan Digital Ketua RT, kolom upload Stempel Resmi RT 15, dan input Format Nomor Surat.
> - **Fokus / Aksi Pengguna:** Pengesahan digital surat-menyurat resmi RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-adm-004-unggah-stempel-dan-ttd-digital.png`

---

### 3.4 Menetapkan Format Baku Penomoran Surat
Pada kolom **Format Nomor Surat**:
- Default sistem: `{NO}/RT.15/PMS/{BULAN_ROMAWI}/{TAHUN}`.
- Variabel ini akan diterjemahkan secara otomatis saat persetujuan surat menjadi: `001/RT.15/PMS/X/2026`.
- Klik tombol **Simpan Perubahan** di bagian bawah halaman.

---

## 4. Manajemen Pengguna & Otorisasi Peran (RBAC)

Super Admin mengelola otorisasi akses menggunakan pustaka **Spatie Laravel-Permission** dengan prinsip hak akses terkecil (*Least Privilege*):

### 4.1 Pembuatan Akun Pengurus Baru
Jika terjadi pergantian kepengurusan RT:
1. Buka menu **Pengaturan Sistem > Manajemen Role** (`/admin/roles`).
2. Periksa daftar 8 role sistem: `super_admin`, `ketua_rt`, `sekretaris`, `bendahara`, `koordinator_bank_sampah`, `koordinator_keamanan`, `warga`, dan `rw_kelurahan`.
3. Buka menu pendaftaran akun pengguna untuk membuat kredensial pengurus baru dengan mengisi:
   - Nama Lengkap Pengurus.
   - Alamat Email (contoh: `sekretaris@simlibas.id`).
   - Nomor WhatsApp Aktif.
   - Kata Sandi Akun.
   - Penetapan Role yang sesuai dengan jabatannya.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-ADM-005]**
> - **Halaman / Tampilan:** Halaman Manajemen Role & Otorisasi Hak Akses (`/admin/roles`)
> - **Elemen yang Ditampilkan:** Tabel daftar role sistem dengan kolom Nama Role, Guard Name (`web`), serta daftar permissions/hak akses yang melekat pada masing-masing peran.
> - **Fokus / Aksi Pengguna:** Manajemen struktur otorisasi RBAC sistem.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-adm-005-tabel-manajemen-role.png`

---

### 4.2 Mengaktifkan atau Menangguhkan Akun Pengguna
- **Status Aktif**: Pengguna dapat masuk ke portal dan menggunakan seluruh layanan sesuai perannya.
- **Status Nonaktif**: Jika terdapat akun pengurus yang telah purnatugas atau warga yang telah pindah keluar kota, Super Admin dapat mengubah status akun menjadi `nonaktif`. Pengguna yang dinonaktifkan akan otomatis diblokir saat mencoba masuk.

---

### 4.3 Prosedur Reset Kata Sandi Akun Warga atau Pengurus
Jika ada warga atau pengurus yang lupa kata sandi dan tidak dapat memulihkannya secara mandiri:
1. Cari akun pengguna yang bersangkutan melalui menu kependudukan atau akun pengguna.
2. Klik tombol **Ubah / Edit**.
3. Masukkan kata sandi baru sementara (misal: `password` atau kombinasi acak aman).
4. Berikan kata sandi sementara tersebut kepada warga melalui pesan WhatsApp resmi pengurus RT 15, dan imbau warga untuk segera mengganti kata sandinya di menu *Profil Saya*.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-ADM-006]**
> - **Halaman / Tampilan:** Formulir Reset Kata Sandi Pengguna oleh Administrator
> - **Elemen yang Ditampilkan:** Form edit akun pengguna yang memuat data nama, email, status akun (Aktif/Nonaktif), penugasan role, dan kolom input Kata Sandi Baru.
> - **Fokus / Aksi Pengguna:** Pemulihan akses masuk akun warga/pengurus.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-adm-006-form-reset-password-user.png`

---

## 5. Audit Log & Pemantauan Jejak Aktivitas Sistem

Sesuai standar audit keamanan data dan **UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP)**, SIM-LiBas mencatat seluruh riwayat penambahan, pengubahan, dan penghapusan data penting menggunakan pustaka **Spatie Activitylog**.

### 5.1 Meninjau Jejak Perubahan Data Sensitif
Super Admin dapat melacak:
- Siapa pengurus yang menyetujui surat pengantar tertentu.
- Siapa bendahara yang memvalidasi kuitansi pembayaran iuran.
- Siapa petugas yang mencatat atau mengubah bobot timbangan sampah.
- Kapan data kependudukan (NIK/Nama KK) diubah.

---

### 5.2 Melacak Waktu, Pelaku, dan Nilai Data Sebelum vs Sesudah Diubah
Pada catatan riwayat audit log:
- **Pelaku (*Causer*)**: Nama pengguna dan alamat IP yang melakukan aksi.
- **Waktu (*Timestamp*)**: Tanggal dan jam aksi dilakukan hingga satuan detik.
- **Nilai Sebelum (*Old Attributes*)**: Data awal sebelum diedit.
- **Nilai Sesudah (*New Attributes*)**: Data baru setelah diedit.
Fitur ini menjamin bahwa seluruh tata kelola kas dan administrasi kependudukan di RT 15 bebas dari potensi kecurangan internal.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-ADM-007]**
> - **Halaman / Tampilan:** Tabel Audit Log Jejak Aktivitas Sistem
> - **Elemen yang Ditampilkan:** Tabel rekapitulasi audit log dengan kolom Waktu Aksi, Nama Pelaku Pengguna, Peristiwa (*Created/Updated/Deleted*), Subjek Entitas Data, dan tombol rincian perubahan.
> - **Fokus / Aksi Pengguna:** Pengawasan integritas dan keamanan data sistem SIM-LiBas.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-adm-007-tabel-audit-log-aktivitas.png`

---

## 6. Daftar Periksa Slot Tangkapan Layar (Checklist)

Gunakan tabel daftar periksa berikut saat Anda mengambil dan memasukkan foto tangkapan layar ke dalam dokumen panduan Super Admin ini:

| No | Kode Slot Tangkapan Layar | Halaman Antarmuka Terkait | Rekomendasi Nama Berkas Gambar | Status Gambar |
|:---:|---|---|---|:---:|
| 1 | `SS-ADM-001` | Halaman Masuk Akun Super Administrator | `assets/screenshots/ss-adm-001-login-super-admin.png` | [ ] Belum Dipasang |
| 2 | `SS-ADM-002` | Dasbor Kendali Penuh Super Admin | `assets/screenshots/ss-adm-002-dashboard-super-admin.png` | [ ] Belum Dipasang |
| 3 | `SS-ADM-003` | Formulir Konfigurasi Identitas Wilayah RT | `assets/screenshots/ss-adm-003-form-identitas-wilayah-rt.png` | [ ] Belum Dipasang |
| 4 | `SS-ADM-004` | Pengaturan Stempel & TTD Digital Resmi | `assets/screenshots/ss-adm-004-unggah-stempel-dan-ttd-digital.png` | [ ] Belum Dipasang |
| 5 | `SS-ADM-005` | Tabel Manajemen Role & Otorisasi RBAC | `assets/screenshots/ss-adm-005-tabel-manajemen-role.png` | [ ] Belum Dipasang |
| 6 | `SS-ADM-006` | Form Reset Kata Sandi Akun Pengguna | `assets/screenshots/ss-adm-006-form-reset-password-user.png` | [ ] Belum Dipasang |
| 7 | `SS-ADM-007` | Tabel Audit Log Jejak Aktivitas Sistem | `assets/screenshots/ss-adm-007-tabel-audit-log-aktivitas.png` | [ ] Belum Dipasang |

---
*Dokumen ini merupakan bagian dari Buku Panduan Resmi Sistem Informasi Manajemen RT 15 (SIM-LiBas).*
