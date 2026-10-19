# BUKU PANDUAN PENGGUNA SIM-LiBas
## TAHAP 3 — PANDUAN ROLE BENDAHARA RT
**Sistem Informasi Manajemen RT 15 Pematang Sulur, Kecamatan Telanaipura, Kota Jambi**

---

## DAFTAR ISI
1. [Peran & Wewenang Bendahara dalam SIM-LiBas](#1-peran--wewenang-bendahara-dalam-sim-libas)
2. [Akses Masuk & Dasbor Keuangan RT (/admin)](#2-akses-masuk--dasbor-keuangan-rt-admin)
   - 2.1 Masuk Menggunakan Akun Bendahara
   - 2.2 Mengenal Tampilan & Indikator Finansial Dasbor
3. [Pengelolaan Master Jenis Iuran Warga](#3-pengelolaan-master-jenis-iuran-warga)
   - 3.1 Menambahkan Jenis Iuran Baru (Kebersihan, Keamanan, Kas Sosial)
   - 3.2 Menentukan Nominal Standar & Siklus Periode (Bulanan/Insidental)
   - 3.3 Mengaktifkan atau Menangguhkan Jenis Iuran
4. [Penerbitan Tagihan Iuran (Invoices)](#4-penerbitan-tagihan-iuran-invoices)
   - 4.1 Pembuatan Tagihan Massal Otomatis ke Seluruh KK RT 15
   - 4.2 Pembuatan Tagihan Khusus / Individu Per Kartu Keluarga
   - 4.3 Menetapkan Periode Bulan, Tahun, dan Tanggal Jatuh Tempo
   - 4.4 Memantau Status Tagihan (Belum Bayar, Sebagian, Lunas)
5. [Validasi Bukti Pembayaran Iuran Warga](#5-validasi-bukti-pembayaran-iuran-warga)
   - 5.1 Meninjau Antrean Bukti Transfer yang Diunggah Warga
   - 5.2 Memeriksa Keaslian Foto Struk / Screenshot Bukti Transfer
   - 5.3 Aksi "Validasi Lunas" (Otomatis Catat Kas Masuk & Notifikasi Warga)
   - 5.4 Aksi "Tolak Pembayaran" Disertai Alasan Penolakan
6. [Pencatatan Pembayaran Cepat (Tunai di Meja Bendahara)](#6-pencatatan-pembayaran-cepat-tunai-di-meja-bendahara)
   - 6.1 Fitur Penerimaan Bayar Tunai Langsung Tanpa Unggah Foto
   - 6.2 Konfirmasi Pelunasan Seketika
7. [Pembukuan Buku Kas Umum RT 15](#7-pembukuan-buku-kas-umum-rt-15)
   - 7.1 Memahami Struktur Buku Kas Digital
   - 7.2 Pencatatan Pemasukan Kas Non-Iuran (Donasi, Sumbangan, Bantuan)
   - 7.3 Pencatatan Pengeluaran Kas Operasional & Lampiran Nota/Kwitansi
   - 7.4 Koreksi & Audit Bukti Transaksi Pengeluaran
8. [Laporan Keuangan & Ekspor Data (PDF / Excel)](#8-laporan-keuangan--ekspor-data-pdf--excel)
   - 8.1 Menghitung Saldo Kas Akhir Otomatis
   - 8.2 Menyaring Transaksi Berdasarkan Kategori & Rentang Tanggal
   - 8.3 Ekspor Buku Kas ke Format PDF Resmi & Lembar Kerja Excel
   - 8.4 Ekspor Rekapitulasi Tagihan Iuran Warga ke Format CSV
9. [Daftar Periksa Slot Tangkapan Layar (Checklist)](#9-daftar-periksa-slot-tangkapan-layar-checklist)

---

## 1. Peran & Wewenang Bendahara dalam SIM-LiBas

Di lingkungan **RT 15 Pematang Sulur**, Bendahara RT memegang peranan krusial sebagai **pengawal akuntabilitas finansial dan transparansi dana masyarakat**. Di dalam sistem SIM-LiBas, Bendahara RT memiliki wewenang penuh dalam:

1. **Master Tarif Iuran**: Menentukan jenis, nominal, dan siklus tagihan wajib atau insidental bagi warga.
2. **Penerbitan Tagihan Terjadwal**: Menerbitkan tagihan bulanan kepada seluruh Kepala Keluarga secara massal (*1-click billing*).
3. **Validasi Bukti Transfer Warga**: Memverifikasi bukti transfer dana perbankan yang diunggah warga dari aplikasi mandiri.
4. **Pembukuan Terintegrasi Otomatis**: Setiap pembayaran yang divalidasi akan langsung dibukukan sebagai pemasukan ke Buku Kas Umum tanpa perlu entri ganda manual.
5. **Pencatatan Belanja & Bukti Nota**: Mendokumentasikan pengeluaran operasional lingkungan (pembelian lampu jalan, honor satpam, konsumsi kerja bakti) lengkap dengan foto fisik nota/kwitansi.
6. **Pelaporan Finansial Siap Cetak**: Menyediakan laporan arus kas dan rekap tagihan yang siap diunduh ke PDF atau Excel untuk rapat pertanggungjawaban kas RT.

---

## 2. Akses Masuk & Dasbor Keuangan RT (/admin)

### 2.1 Masuk Menggunakan Akun Bendahara
1. Buka peramban di komputer bendahara atau ponsel.
2. Buka alamat: **`http://localhost/admin`**.
3. Masukkan kredensial login resmi Bendahara RT 15:
   - **Email**: `bendahara@simlibas.com`
   - **Kata Sandi**: `password` (atau kata sandi terbaru Anda)
4. Klik tombol **"Masuk ke Panel Pengurus"**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-001]**
> - **Halaman / Tampilan:** Formulir Masuk Panel Akun Bendahara RT 15 (`/admin/login`)
> - **Elemen yang Ditampilkan:** Halaman login dengan email `bendahara@simlibas.com` dan kursor pada kolom kata sandi.
> - **Fokus / Aksi Pengguna:** Sesi login resmi Bendahara RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-001-login-bendahara.png`

---

### 2.2 Mengenal Tampilan & Indikator Finansial Dasbor
Setelah masuk, dasbor panel pengurus akan menyajikan kartu-kartu metrik finansial terkini:
- Sapaan resmi: *"Selamat Datang, Ibu Hj. Nurhayati, S.E. (Bendahara RT 15)"*.
- **Pemasukan Kas**: Total akumulasi dana kas masuk.
- **Pengeluaran Kas**: Total penggunaan kas lingkungan.
- **Saldo Bersih Kas RT**: Selisih saldo kas yang tersedia saat ini secara *real-time*.
- **Tagihan Tertunda**: Akumulasi rupiah tagihan warga yang belum terbayar.
- **Tabel Transaksi Terkini**: Daftar 4 transaksi kas terakhir dan 4 konfirmasi pembayaran terbaru dari warga.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-002]**
> - **Halaman / Tampilan:** Dasbor Finansial Bendahara RT 15 (`/admin`)
> - **Elemen yang Ditampilkan:** Dasbor dengan kartu ringkasan saldo kas RT, total pemasukan, total pengeluaran, tagihan tertunda, dan tabel mutasi kas terbaru.
> - **Fokus / Aksi Pengguna:** Pemantauan arus kas lingkungan secara cepat.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-002-dashboard-finansial-bendahara.png`

---

## 3. Pengelolaan Master Jenis Iuran Warga

Master iuran mengatur rincian biaya yang dibebankan kepada warga secara periodik.

### 3.1 Menambahkan Jenis Iuran Baru
1. Pada menu bilah samping kiri, klik grup **Keuangan RT > Master Jenis Iuran** (`/admin/fee-types`).
2. Klik tombol **"+ Buat Jenis Iuran"** di kanan atas (`/admin/fee-types/create`).
3. Isi data iuran:
   - **Nama Jenis Iuran**: Masukkan nama resmi (contoh: *Iuran Keamanan & Kebersihan*, *Iuran Kas Kematian / Sosial*, atau *Iuran Peringatan 17 Agustus*).
   - **Deskripsi**: Tuliskan peruntukan dana iuran secara jelas agar transparan bagi warga.
   - **Nominal (Rupiah)**: Masukkan nominal angka murni tanpa titik (contoh: ketik `50000` untuk Rp 50.000).

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-003]**
> - **Halaman / Tampilan:** Formulir Tambah Master Jenis Iuran (`/admin/fee-types/create`)
> - **Elemen yang Ditampilkan:** Form isian Nama Jenis Iuran, Textarea Deskripsi, Input Nominal (Rp), Dropdown Pilihan Periode, dan Saklar Toggle Status Aktif.
> - **Fokus / Aksi Pengguna:** Pembuatan master pos tarif iuran baru warga.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-003-form-tambah-jenis-iuran.png`

---

### 3.2 Menentukan Siklus Periode & Status Aktif
- **Periode**:
  - `Bulanan`: Ditagihkan secara rutin setiap bulan (contoh: iuran sampah/satpam).
  - `Tahunan`: Ditagihkan sekali setahun (contoh: iuran kas tahunan).
  - `Insidental`: Ditagihkan sewaktu-waktu sesuai kebutuhan musyawarah warga (contoh: sumbangan pembangunan gapura/tenda RT).
- **Status Aktif**: Pastikan saklar hijau menyala agar jenis iuran ini dapat dipilih saat menerbitkan tagihan.
- Klik **"Buat"** untuk menyimpan.

---

## 4. Penerbitan Tagihan Iuran (Invoices)

Modul ini digunakan Bendahara untuk menerbitkan tagihan kepada warga RT 15.

### 4.1 Pembuatan Tagihan Massal ke Seluruh KK (1-Click Bulk Billing)
Pada awal setiap bulan baru, Bendahara dapat menerbitkan tagihan ke seluruh warga sekaligus:
1. Buka menu **Keuangan RT > Tagihan Iuran** (`/admin/invoices`).
2. Klik tombol biru **"+ Terbitkan Tagihan Baru"**.
3. Jendela modal penerbitan tagihan akan terbuka:
   - Pada pilihan **Mode Penerbitan**, pilih: **`Massal (Seluruh Kartu Keluarga RT 15)`**.
   - **Pilih Jenis Iuran**: Pilih jenis iuran dari *dropdown* (misal: *Iuran Wajib RT*).
   - **Periode Bulan & Tahun**: Pilih bulan berjalan (1 s/d 12) dan tahun (contoh: *Bulan 11, Tahun 2026*).
   - **Tanggal Jatuh Tempo**: Tentukan batas akhir pembayaran (misal tanggal 10 atau akhir bulan).
4. Klik tombol **"Terbitkan Tagihan Sekarang"**.
5. **Kecerdasan Sistem**:
   - Sistem secara otomatis membuat tagihan untuk setiap KK yang terdaftar.
   - Jika suatu KK sudah memiliki tagihan pada bulan dan jenis iuran tersebut, sistem akan melewatinya secara cerdas sehingga **tidak akan terjadi tagihan ganda**.
   - Notifikasi sukses akan muncul: *"Berhasil menerbitkan 4 tagihan iuran massal"*.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-004]**
> - **Halaman / Tampilan:** Modal Penerbitan Tagihan Massal Seluruh KK (`/admin/invoices`)
> - **Elemen yang Ditampilkan:** Modal dialog penerbitan tagihan dengan radio button mode "Massal (Seluruh KK)", dropdown jenis iuran, pemilih bulan dan tahun, input tanggal jatuh tempo, dan tombol "Terbitkan Tagihan".
> - **Fokus / Aksi Pengguna:** Penerbitan tagihan kolektif bulanan RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-004-modal-terbitkan-tagihan-massal.png`

---

### 4.2 Pembuatan Tagihan Khusus / Individu Per KK
Jika ingin menagih hanya kepada satu keluarga tertentu (misal iuran insidental perbaikan pagar khusus):
1. Buka kembali modal **"+ Terbitkan Tagihan Baru"**.
2. Pilih mode: **`Individu (Pilih 1 KK Tertentu)`**.
3. Pilih nama Kepala Keluarga / Nomor KK yang dituju.
4. Tentukan jenis iuran, periode, dan tanggal jatuh tempo.
5. Klik **"Terbitkan Tagihan"**.

---

### 4.3 Memantau Status Tagihan Warga
Halaman utama Tagihan Iuran dilengkapi dengan 4 kartu ringkasan dan filter status:
- 🟡 **Belum Bayar**: Warga belum melakukan pembayaran sama sekali.
- 🔵 **Sebagian**: Warga baru membayar sebagian nominal.
- 🟢 **Lunas**: Tagihan telah selesai dibayar dan diverifikasi.
- Gunakan tombol filter cepat di atas tabel (*Semua*, *Belum Bayar*, *Lunas*) untuk melihat siapa saja warga yang belum melunasi iuran bulan ini.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-005]**
> - **Halaman / Tampilan:** Tabel Tagihan Iuran Warga dengan Kartu KPI & Filter Status
> - **Elemen yang Ditampilkan:** Header halaman dengan tombol ekspor, kartu metrik (Total Tagihan, Belum Bayar, Lunas, Nominal Tunggakan), bar filter status, dan tabel daftar tagihan dengan identitas KK dan nominal.
> - **Fokus / Aksi Pengguna:** Pemantauan status tagihan warga RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-005-tabel-tagihan-dan-filter-status.png`

---

## 5. Validasi Bukti Pembayaran Iuran Warga

Saat warga membayar iuran via transfer bank melalui portal mandiri, data akan masuk ke modul **Validasi Pembayaran**.

### 5.1 Meninjau Antrean Bukti Transfer
1. Buka menu **Keuangan RT > Validasi Pembayaran** (`/admin/payments`).
2. Filter status: pilih **Status: Menunggu Validasi** (status `pending`).
3. Baris tabel akan menyajikan:
   - Nama Kepala Keluarga dan alamat rumah pemohon.
   - Jenis Iuran dan nominal rupiah yang dibayarkan.
   - Metode pembayaran (`TRANSFER` atau `TUNAI`).
   - Pratinjau gambar mini (*thumbnail*) bukti transfer.
   - Tanggal bayar warga.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-006]**
> - **Halaman / Tampilan:** Tabel Antrean Konfirmasi Pembayaran Warga (`/admin/payments`)
> - **Elemen yang Ditampilkan:** Daftar transaksi pembayaran dengan kolom Warga/KK, Jenis Iuran, Jumlah Rupiah, Metode, Gambar Thumbnail Bukti Transfer, Tanggal, dan Badge Status Kuning "Pending".
> - **Fokus / Aksi Pengguna:** Pemeriksaan daftar bukti transfer yang masuk.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-006-tabel-antrean-validasi-pembayaran.png`

---

### 5.2 Memeriksa Keaslian Foto Bukti Transfer
1. Klik pada gambar mini bukti transfer atau klik tombol **Ubah / Edit** pada baris transaksi.
2. Periksa foto struk/screenshot mobile banking secara teliti:
   - Apakah nominal transfer sesuai dengan nominal tagihan iuran?
   - Apakah rekening tujuan adalah **Bank Jambi 900-15-1515 a.n. Kas RT 15**?
   - Apakah tanggal dan jam transaksi transfer valid?
   - Periksa mutasi rekening koran di aplikasi m-banking resmi kas RT untuk memastikan dana benar-benar telah masuk.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-007]**
> - **Halaman / Tampilan:** Modal Pratinjau Foto Bukti Transfer Warga
> - **Elemen yang Ditampilkan:** Tampilan foto struk transfer m-banking ukuran penuh yang diunggah warga, memuat nama bank pengirim, nomor rekening tujuan, nominal, dan nomor referensi bank.
> - **Fokus / Aksi Pengguna:** Verifikasi keaslian bukti transfer sebelum pengesahan.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-007-pratinjau-bukti-transfer-warga.png`

---

### 5.3 Prosedur Validasi Lunas (Auto-Posting ke Buku Kas)
Jika dana telah terverifikasi masuk ke rekening RT 15:
1. Pada baris tabel pembayaran, klik tombol hijau **"Validasi Lunas"** (ikon lencana centang).
2. Kotak dialog konfirmasi akan muncul:
   > *"Apakah pembayaran ini sudah diverifikasi dan masuk kas RT? Tagihan warga akan otomatis berstatus Lunas dan dicatat di Buku Kas."*
3. Klik tombol konfirmasi **"Validasi Lunas"**.
4. **Keunggulan Otomasi SIM-LiBas**:
   - Status pembayaran seketika menjadi 🟢 **Valid (Lunas)**.
   - Status tagihan terkait pada modul *Invoices* otomatis diperbarui menjadi 🟢 **Lunas**.
   - **Otomatis Masuk Buku Kas**: Sistem seketika membuat catatan transaksi pemasukan di Buku Kas Umum RT dengan rincian kategori *"Iuran Warga"*, nominal rupiah yang sesuai, dan menyematkan foto bukti transfer sebagai arsip kas.
   - Sistem secara otomatis mengirimkan notifikasi ke dasbor warga bersangkutan bahwa pembayarannya telah sah diterima Bendahara.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-008]**
> - **Halaman / Tampilan:** Dialog Konfirmasi Validasi Pembayaran Lunas
> - **Elemen yang Ditampilkan:** Modal dialog konfirmasi bertuliskan *"Konfirmasi Validasi Pembayaran"* dengan tombol hijau aksi "Validasi Lunas" dan tombol "Batal".
> - **Fokus / Aksi Pengguna:** Pengesahan pembayaran transfer warga menjadi lunas.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-008-konfirmasi-validasi-lunas.png`

---

### 5.4 Prosedur Menolak Pembayaran yang Tidak Valid
Jika bukti transfer buram, palsu, salah nominal, atau dana tidak masuk ke rekening RT:
1. Klik tombol merah **"Tolak"** (ikon silang lingkaran).
2. Pada formulir penolakan, **wajib mengisi alasan penolakan** pada kolom textarea (contoh: *"Dana belum masuk ke mutasi Bank Jambi kas RT, mohon periksa nomor rekening tujuan Anda"* atau *"Foto struk terpotong tidak menampilkan nomor referensi"*).
3. Klik tombol **"Tolak Pembayaran"**.
4. Status pembayaran berubah menjadi 🔴 **Ditolak**, dan warga akan menerima pemberitahuan alasan tersebut agar dapat mengunggah bukti yang benar.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-009]**
> - **Halaman / Tampilan:** Modal Penolakan Bukti Pembayaran dengan Alasan
> - **Elemen yang Ditampilkan:** Form isian textarea Alasan Penolakan dan tombol merah konfirmasi penolakan pembayaran.
> - **Fokus / Aksi Pengguna:** Penolakan bukti transfer yang tidak memenuhi syarat validitas kas.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-009-modal-tolak-pembayaran-iuran.png`

---

## 6. Pencatatan Pembayaran Cepat (Tunai di Meja Bendahara)

Seringkali warga (khususnya warga lanjut usia) membayar iuran secara tunai langsung ke rumah Bendahara RT. SIM-LiBas menyediakan fitur pelunasan instan:

### 6.1 Fitur Penerimaan Bayar Tunai Langsung
1. Buka menu **Keuangan RT > Tagihan Iuran** (`/admin/invoices`).
2. Cari nama warga atau nomor KK yang datang membayar pada kolom pencarian.
3. Pada baris tagihannya, klik tombol hijau **"Bayar"** (atau buka modal detail tagihan lalu klik tombol *"Catat Pembayaran Langsung"*).
4. Jendela sembul **Catat Pembayaran Cepat** akan muncul:
   - **Metode Pembayaran**: Terpilih otomatis `Tunai`.
   - **Jumlah Diterima (Rp)**: Terisi otomatis sesuai nominal tagihan (dapat disesuaikan jika bayar bertahap).
   - **Tanggal Terima**: Terisi tanggal hari ini secara otomatis.
   - **Catatan**: Terisi otomatis *"Diterima tunai oleh pengurus RT 15"*.
5. Klik tombol biru **"Konfirmasi Pembayaran Lunas"**.
6. Tagihan warga seketika berstatus 🟢 **Lunas**, bukti kwitansi tercatat, dan dana tunai otomatis dibukukan ke saldo kas RT 15.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-010]**
> - **Halaman / Tampilan:** Modal Pencatatan Bayar Tunai Cepat di Meja Bendahara
> - **Elemen yang Ditampilkan:** Modal dialog dengan judul *"Catat Pembayaran Tagihan"*, rincian KK warga, pilihan metode bayar Tunai, input nominal diterima, tanggal transaksi, catatan penerimaan, dan tombol konfirmasi.
> - **Fokus / Aksi Pengguna:** Pelunasan iuran warga yang membayar uang tunai fisik.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-010-modal-catat-bayar-tunai.png`

---

## 7. Pembukuan Buku Kas Umum RT 15

Buku Kas Umum (`/admin/cash-transactions`) adalah buku besar pencatatan seluruh mutasi finansial lingkungan.

### 7.1 Memahami Struktur Buku Kas Digital
Halaman Buku Kas menampilkan 3 kartu analitik di bagian atas:
1. 🟢 **Total Pemasukan Kas**: Total akumulasi dana yang masuk (iuran + non-iuran).
2. 🔴 **Total Pengeluaran Kas**: Total dana yang telah dibelanjakan untuk keperluan RT.
3. 🔵 **Saldo Kas RT Saat Ini**: Posisi uang kas riil RT 15 saat ini.

Di bawahnya terdapat tabel transaksi lengkap dengan penanda warna:
- Angka berwarna hijau diawali tanda `+`: Transaksi kas masuk.
- Angka berwarna merah diawali tanda `-`: Transaksi kas keluar.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-011]**
> - **Halaman / Tampilan:** Halaman Buku Kas Umum RT 15 (`/admin/cash-transactions`)
> - **Elemen yang Ditampilkan:** 3 Kartu statistik (Total Pemasukan hijau, Total Pengeluaran merah, Saldo Kas biru), bilah pencarian & filter transaksi, serta tabel buku kas dengan indikator warna nominal nominal.
> - **Fokus / Aksi Pengguna:** Pembukuan umum keuangan RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-011-buku-kas-umum-rt.png`

---

### 7.2 Pencatatan Pemasukan Kas Non-Iuran
Untuk mencatat uang masuk di luar iuran warga (contoh: sumbangan donatur warga, dana bantuan kelurahan, atau hasil bagi hasil bank sampah):
1. Pada halaman Buku Kas, klik tombol biru **"+ Catat Transaksi Kas"**.
2. Pada formulir transaksi:
   - **Jenis Transaksi**: Pilih **`Masuk`**.
   - **Kategori**: Pilih atau ketikkan kategori (contoh: *Donasi Warga*, *Kas Sosial*, atau *Bantuan Pemerintah*).
   - **Tanggal Transaksi**: Tentukan tanggal dana diterima.
   - **Nominal (Rupiah)**: Ketikkan jumlah uang masuk.
   - **Keterangan / Deskripsi**: Jelaskan asal-usul dana (contoh: *"Sumbangan sukarela perbaikan jalan dari Bpk. H. Sudirman"*).
   - **Foto Bukti**: Lampirkan foto kwitansi tanda terima dana.
3. Klik **"Simpan Transaksi"**.

---

### 7.3 Pencatatan Pengeluaran Kas & Lampiran Nota/Kwitansi
Setiap rupiah yang dikeluarkan dari kas RT **wajib dicatat beserta bukti nota fisiknya**:
1. Klik tombol **"+ Catat Transaksi Kas"**.
2. Pada formulir:
   - **Jenis Transaksi**: Pilih **`Keluar`**.
   - **Kategori**: Pilih kategori belanja:
     - `Operasional Lingkungan` (pembelian sapu, cat gapura, perlengkapan pos kamling).
     - `Penerangan Jalan & Listrik` (pembelian bohlam LED PJU, token listrik pos kamling).
     - `Honor Keamanan & Petugas` (uang lelah satpam malam / petugas kebersihan).
     - `Konsumsi Kegiatan` (snack gotong royong / konsumsi musyawarah RT).
     - `Santunan / Sosial Kematian` (uang duka warga tertimpa musibah).
   - **Tanggal Belanja**: Tanggal terjadinya pengeluaran.
   - **Nominal Belanja (Rp)**: Total nilai nota belanja.
   - **Keterangan**: Deskripsikan barang yang dibeli (contoh: *"Beli 4 buah bohlam lampu LED 30 Watt Philips untuk penerangan Gang Melati dan Gang Mawar"*).
   - **Foto Nota / Bukti Fisik**: Unggah foto struk belanja dari toko/bon warung.
3. Klik **"Simpan Transaksi"**. Saldo kas RT akan otomatis terpotong secara akurat.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-012]**
> - **Halaman / Tampilan:** Formulir Pencatatan Transaksi Pengeluaran Kas RT
> - **Elemen yang Ditampilkan:** Modal dialog input transaksi kas dengan pilihan jenis "Keluar", dropdown kategori pengeluaran, pemilih tanggal, input nominal rupiah, textarea keterangan belanja, dan kolom upload foto nota.
> - **Fokus / Aksi Pengguna:** Pencatatan belanja lingkungan dengan bukti nota fisik.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-012-form-catat-pengeluaran-kas.png`

---

### 7.4 Koreksi & Audit Bukti Transaksi
- **Melihat Foto Nota**: Klik tombol **Lihat** (ikon mata) pada baris transaksi buku kas untuk membuka rincian belanja dan memperbesar foto nota/kwitansi yang dilampirkan.
- **Mengubah Catatan**: Klik tombol **Ubah** jika ada kesalahan ketik pada uraian belanja.
- **Menghapus Transaksi**: Klik tombol **Hapus** jika terjadi pembatalan transaksi (data akan dipindahkan ke riwayat arsip terhapus untuk mencegah kecurangan keuangan).

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-013]**
> - **Halaman / Tampilan:** Modal Rincian Transaksi Kas & Lampiran Foto Nota Belanja
> - **Elemen yang Ditampilkan:** Modal detail transaksi kas yang menampilkan nominal, tanggal, kategori, nama pencatat, uraian lengkap, dan pratinjau gambar foto nota fisik toko.
> - **Fokus / Aksi Pengguna:** Audit bukti fisik pertanggungjawaban kas RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-013-modal-detail-transaksi-nota.png`

---

## 8. Laporan Keuangan & Ekspor Data (PDF / Excel)

Bendahara dapat membuat laporan pertanggungjawaban keuangan secara instan tanpa perlu menyusun pembukuan manual di akhir bulan:

### 8.1 Menyaring Transaksi Berdasarkan Kategori & Rentang Tanggal
Pada halaman Buku Kas Umum:
1. Gunakan kolom **Jenis Filter**: Pilih hanya *Pemasukan* atau hanya *Pengeluaran*.
2. Gunakan pemilih tanggal **Rentang Tanggal**: Tentukan tanggal awal (*Start Date*) dan tanggal akhir (*End Date*) buku kas yang ingin dilaporkan (misal: periode 1 Oktober 2026 s/d 31 Oktober 2026).
3. Tabel akan otomatis menampilkan mutasi kas pada rentang tanggal tersebut beserta rekap saldo subtotalnya.

---

### 8.2 Ekspor Buku Kas ke Format PDF Resmi & Excel
1. Di sudut kanan atas halaman Buku Kas, klik tombol **"Ekspor Data"**.
2. Menu dropdown akan menyajikan pilihan format:
   - 📄 **Ekspor ke PDF Resmi**: Menghasilkan dokumen laporan kas rapi berukuran A4 lengkap dengan kop surat RT 15, tabel arus kas masuk-keluar, saldo akhir kas, serta kolom tanda tangan Bendahara dan Ketua RT 15. Dokumen ini siap dicetak untuk rapat bulanan warga.
   - 📊 **Ekspor ke Excel (XLSX)**: Mengunduh data tabel ke dalam lembar kerja Microsoft Excel untuk keperluan pengolahan pembukuan lebih lanjut.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-014]**
> - **Halaman / Tampilan:** Menu Dropdown Ekspor Laporan Keuangan RT (PDF & Excel)
> - **Elemen yang Ditampilkan:** Tombol dropdown "Ekspor Data" yang menampilkan pilihan ikon dokumen PDF dan ikon spreadsheet Excel pada bilah atas halaman Buku Kas.
> - **Fokus / Aksi Pengguna:** Pengunduhan laporan keuangan resmi RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-014-dropdown-ekspor-buku-kas.png`

---

### 8.3 Ekspor Rekapitulasi Tagihan Iuran Warga ke Format CSV
Untuk memantau keluarga mana saja yang telah membayar atau menunggak iuran:
1. Buka menu **Keuangan RT > Tagihan Iuran** (`/admin/invoices`).
2. Terapkan filter yang diinginkan (misal: filter *Status: Belum Bayar*).
3. Klik tombol **"Ekspor Data"** di bagian atas tabel.
4. Sistem akan mengunduh berkas tabel data:
   `tagihan-iuran-rt15-[TANGGAL].csv`
5. Berkas CSV tersebut memuat kolom: Nomor Urut, ID KK, Nama Kepala Keluarga, Alamat Rumah, Jenis Iuran, Periode Bulan & Tahun, Nominal Tagihan, Status Lunas, dan Nomor Telepon WhatsApp warga untuk memudahkan pengurus mengirim pesan pengingat iuran ramah ke warga.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BND-015]**
> - **Halaman / Tampilan:** Lembar Dokumen Laporan Arus Kas RT 15 (Hasil Unduhan PDF)
> - **Elemen yang Ditampilkan:** Dokumen PDF resmi siap cetak yang memuat kop surat RT 15, judul *"Laporan Arus Kas dan Keuangan RT 15 Pematang Sulur"*, tabel mutasi kas masuk dan keluar, total saldo kas akhir, serta kolom tanda tangan pengesahan Bendahara dan Ketua RT.
> - **Fokus / Aksi Pengguna:** Tampilan utuh lembar laporan pertanggungjawaban kas bulanan.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bnd-015-lembar-laporan-keuangan-pdf.png`

---

## 9. Daftar Periksa Slot Tangkapan Layar (Checklist)

Gunakan tabel daftar periksa berikut saat Anda mengambil dan memasukkan foto tangkapan layar ke dalam dokumen panduan Bendahara RT ini:

| No | Kode Slot Tangkapan Layar | Halaman Antarmuka Terkait | Rekomendasi Nama Berkas Gambar | Status Gambar |
|:---:|---|---|---|:---:|
| 1 | `SS-BND-001` | Halaman Masuk Akun Bendahara | `assets/screenshots/ss-bnd-001-login-bendahara.png` | [ ] Belum Dipasang |
| 2 | `SS-BND-002` | Dasbor Finansial Bendahara RT 15 | `assets/screenshots/ss-bnd-002-dashboard-finansial-bendahara.png` | [ ] Belum Dipasang |
| 3 | `SS-BND-003` | Formulir Tambah Master Jenis Iuran | `assets/screenshots/ss-bnd-003-form-tambah-jenis-iuran.png` | [ ] Belum Dipasang |
| 4 | `SS-BND-004` | Modal Penerbitan Tagihan Massal Seluruh KK | `assets/screenshots/ss-bnd-004-modal-terbitkan-tagihan-massal.png` | [ ] Belum Dipasang |
| 5 | `SS-BND-005` | Tabel Tagihan Iuran dengan KPI & Filter | `assets/screenshots/ss-bnd-005-tabel-tagihan-dan-filter-status.png` | [ ] Belum Dipasang |
| 6 | `SS-BND-006` | Tabel Antrean Bukti Pembayaran Warga | `assets/screenshots/ss-bnd-006-tabel-antrean-validasi-pembayaran.png` | [ ] Belum Dipasang |
| 7 | `SS-BND-007` | Pratinjau Foto Struk Bukti Transfer | `assets/screenshots/ss-bnd-007-pratinjau-bukti-transfer-warga.png` | [ ] Belum Dipasang |
| 8 | `SS-BND-008` | Dialog Konfirmasi Validasi Pembayaran Lunas | `assets/screenshots/ss-bnd-008-konfirmasi-validasi-lunas.png` | [ ] Belum Dipasang |
| 9 | `SS-BND-009` | Modal Penolakan Bukti Transfer dengan Alasan | `assets/screenshots/ss-bnd-009-modal-tolak-pembayaran-iuran.png` | [ ] Belum Dipasang |
| 10 | `SS-BND-010` | Modal Catat Pembayaran Tunai di Meja Bendahara | `assets/screenshots/ss-bnd-010-modal-catat-bayar-tunai.png` | [ ] Belum Dipasang |
| 11 | `SS-BND-011` | Halaman Utama Buku Kas Umum RT 15 | `assets/screenshots/ss-bnd-011-buku-kas-umum-rt.png` | [ ] Belum Dipasang |
| 12 | `SS-BND-012` | Form Input Pengeluaran Kas & Bukti Nota | `assets/screenshots/ss-bnd-012-form-catat-pengeluaran-kas.png` | [ ] Belum Dipasang |
| 13 | `SS-BND-013` | Modal Detail Transaksi & Pratinjau Nota Fisik | `assets/screenshots/ss-bnd-013-modal-detail-transaksi-nota.png` | [ ] Belum Dipasang |
| 14 | `SS-BND-014` | Dropdown Ekspor Laporan Keuangan (PDF/Excel) | `assets/screenshots/ss-bnd-014-dropdown-ekspor-buku-kas.png` | [ ] Belum Dipasang |
| 15 | `SS-BND-015` | Lembar Cetak Laporan Arus Kas RT Resmi (PDF) | `assets/screenshots/ss-bnd-015-lembar-laporan-keuangan-pdf.png` | [ ] Belum Dipasang |

---
*Dokumen ini merupakan bagian dari Buku Panduan Resmi Sistem Informasi Manajemen RT 15 (SIM-LiBas).*
