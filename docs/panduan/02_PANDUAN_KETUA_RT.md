# BUKU PANDUAN PENGGUNA SIM-LiBas
## TAHAP 2A — PANDUAN ROLE KETUA RT
**Sistem Informasi Manajemen RT 15 Pematang Sulur, Kecamatan Telanaipura, Kota Jambi**

---

## DAFTAR ISI
1. [Peran & Wewenang Ketua RT dalam SIM-LiBas](#1-peran--wewenang-ketua-rt-dalam-sim-libas)
2. [Akses Masuk & Navigasi Panel Pengurus (/admin)](#2-akses-masuk--navigasi-panel-pengurus-admin)
   - 2.1 Masuk ke Panel Pengurus
   - 2.2 Mengenal Tata Letak Dashboard Eksekutif Ketua RT
   - 2.3 Pusat Widget & Indikator Kinerja Wilayah
3. [Manajemen & Disposisi Surat Pengantar RT Online](#3-manajemen--disposisi-surat-pengantar-rt-online)
   - 3.1 Memeriksa Antrean Surat Masuk (Status Diajukan & Diperiksa)
   - 3.2 Meninjau Berkas Identitas & Keperluan Pemohon
   - 3.3 Prosedur Menyetujui Surat (Auto-Generate Nomor Surat & QR Code)
   - 3.4 Prosedur Menolak Surat dengan Catatan Alasan
   - 3.5 Mencetak Lembar Resmi Surat Pengantar Ber-QR Code
4. [Pengawasan Kependudukan & Dinamika Warga](#4-pengawasan-kependudukan--dinamika-warga)
   - 4.1 Memantau Data Kartu Keluarga (KK) & Demografi Warga
   - 4.2 Memantau Mutasi Kependudukan (Lahir, Pindah, Wafat, Pendatang)
5. [Disposisi & Penyelesaian Pengaduan Warga](#5-disposisi--penyelesaian-pengaduan-warga)
   - 5.1 Meninjau Laporan Keluhan Lingkungan (Termasuk Laporan Anonim)
   - 5.2 Menugaskan Penanggung Jawab Tindak Lanjut
   - 5.3 Memperbarui Progres & Mengisi Catatan Penyelesaian
6. [Moderasi Etalase UMKM Warga RT 15](#6-moderasi-etalase-umkm-warga-rt-15)
   - 6.1 Memeriksa Pengajuan Profil Usaha Rumahan Warga
   - 6.2 Menyetujui atau Menolak Tayang di Katalog Publik
7. [Penerbitan Pengumuman Resmi & Agenda RT](#7-penerbitan-pengumuman-resmi--agenda-rt)
   - 7.1 Menerbitkan Maklumat / Pengumuman Penting RT
   - 7.2 Menjadwalkan Agenda Rapat Warga & Kegiatan Lingkungan
8. [Pengawasan Keuangan Kas RT & Keamanan Wilayah](#8-pengawasan-keuangan-kas-rt--keamanan-wilayah)
   - 8.1 Memantau Laporan Buku Kas Umum & Saldo Akhir
   - 8.2 Memantau Jadwal Ronda Siskamling & Buku Laporan Tamu
9. [Daftar Periksa Slot Tangkapan Layar (Checklist)](#9-daftar-periksa-slot-tangkapan-layar-checklist)

---

## 1. Peran & Wewenang Ketua RT dalam SIM-LiBas

Sebagai pemimpin lingkungan di **RT 15 Pematang Sulur**, Ketua RT memegang peranan eksekutif tertinggi dalam pengambilan keputusan administratif dan pelayanan masyarakat. Di dalam sistem SIM-LiBas, Ketua RT memiliki kewenangan khusus:

1. **Otoritas Tunggal Persetujuan Surat Pengantar**: Menyetujui penerbitan surat pengantar resmi yang secara otomatis membubuhkan nomor registrasi dinas, stempel, dan tanda tangan digital ber-QR Code sah.
2. **Disposisi & Pengawasan Aduan Masyarakat**: Mengarahkan penanganan keluhan fasilitas/keamanan kepada koordinator terkait dan memantau status penyelesaiannya.
3. **Pengawasan Transparansi Kas & Iuran**: Memantau realisasi arus kas masuk/keluar dan ketercapaian pembayaran iuran warga tanpa intervensi teknis pembukuan langsung.
4. **Moderasi & Validasi UMKM Lingkungan**: Menjamin bahwa usaha warga yang tampil pada Katalog UMKM publik merupakan usaha riil warga RT 15.
5. **Kebijakan & Komunikasi Publik**: Menerbitkan pengumuman penting, maklumat kamtibmas, dan agenda musyawarah warga.

---

## 2. Akses Masuk & Navigasi Panel Pengurus (/admin)

### 2.1 Masuk ke Panel Pengurus
1. Buka peramban (*web browser*) di komputer atau ponsel Anda.
2. Masukkan alamat: **`http://localhost/admin`** (atau `/admin/login`).
3. Anda akan melihat halaman masuk **Portal Pengurus RT 15**:
   - **Alamat Email Pengurus**: Masukkan `ketua.rt@simlibas.id`
   - **Kata Sandi Akun**: Masukkan kata sandi akun Anda (default: `password`)
4. Klik tombol **"Masuk ke Panel Pengurus"**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-001]**
> - **Halaman / Tampilan:** Halaman Masuk Panel Pengurus (`/admin/login`)
> - **Elemen yang Ditampilkan:** Formulir login Filament panel berwarna putih bersih beraksen biru tua `#0B3A85`, judul *"Portal Pengurus RT 15"*, input email pengurus, input kata sandi, dan tombol masuk.
> - **Fokus / Aksi Pengguna:** Halaman siap diisi kredensial akun Ketua RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-001-login-panel-pengurus.png`

---

### 2.2 Mengenal Tata Letak Dashboard Eksekutif Ketua RT
Setelah berhasil masuk, Anda akan diarahkan ke Dasbor Utama (`/admin`). Antarmuka terdiri dari:
- **Bilah Samping Kiri (Sidebar)**: Menu navigasi yang dikelompokkan ke dalam grup:
  - *Kependudukan* (Kartu Keluarga & Warga)
  - *Layanan Administrasi* (Surat Pengantar & Jenis Surat)
  - *Komunikasi Warga* (Pengumuman, Agenda, Pengaduan)
  - *Keamanan Lingkungan* (Jadwal Ronda, Laporan Tamu)
  - *Keuangan RT* (Tagihan, Pembayaran, Buku Kas)
  - *Kampung Digital* (Bank Sampah, Profil UMKM)
- **Bilah Atas (Topbar)**: Menampilkan lonceng notifikasi pengajuan surat baru dan menu avatar profil Ketua RT.
- **Area Kerja Utama**: Menampilkan sapaan dinas waktu nyata (Pagi/Siang/Sore/Malam), kartu metrik statistik, dan ringkasan surat masuk.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-002]**
> - **Halaman / Tampilan:** Dasbor Utama Eksekutif Ketua RT (`/admin`)
> - **Elemen yang Ditampilkan:** Bilah samping kiri dengan kelompok navigasi aktif, kartu sambutan *"Selamat Datang, Bpk. H. Ahmad Sudirman, S.E. (Ketua RT 15)"*, widget ringkasan statistik warga, kas RT, dan surat berjalan.
> - **Fokus / Aksi Pengguna:** Tampilan utuh dasbor pimpinan RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-002-dashboard-eksekutif-ketua-rt.png`

---

### 2.3 Pusat Widget & Indikator Kinerja Wilayah
Pada dasbor, Ketua RT disajikan metrik penting:
- **Total Warga Aktif & Total KK**: Jumlah jiwa dan kepala keluarga yang tercatat di RT 15.
- **Surat Menunggu**: Jumlah permohonan surat warga yang butuh disposisi/persetujuan.
- **Saldo Kas RT**: Posisi kas terkini (Pemasukan dikurangi Pengeluaran).
- **Pengaduan Terbuka**: Jumlah keluhan warga yang berstatus *Diterima* atau *Diproses*.
- **Total Setoran Bank Sampah (kg)**: Capaian program lingkungan pemilahan sampah warga.

---

## 3. Manajemen & Disposisi Surat Pengantar RT Online

Menu ini merupakan tugas utama operasional Ketua RT guna memberikan pelayanan surat-menyurat yang cepat dan akurat.

### 3.1 Memeriksa Antrean Surat Masuk
1. Pada bilah samping kiri, klik grup **Layanan Administrasi**, lalu pilih **Surat Pengantar** (URL: `/admin/letters`).
2. Halaman tabel daftar surat akan tampil.
3. Gunakan filter status di sudut kanan atas tabel:
   - Pilih filter **Status: Diajukan** (untuk melihat surat baru dari warga).
   - Pilih filter **Status: Diperiksa** (surat yang berkasnya telah diverifikasi oleh Sekretaris).

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-003]**
> - **Halaman / Tampilan:** Tabel Antrean Surat Pengantar Warga (`/admin/letters`)
> - **Elemen yang Ditampilkan:** Tabel daftar surat dengan kolom Nomor Surat, Jenis Surat, Nama Pemohon (beserta NIK), Keperluan, Badge Status warna-warni, Tanggal Pengajuan, serta tombol-tombol aksi pada baris tabel.
> - **Fokus / Aksi Pengguna:** Daftar permohonan surat yang memerlukan persetujuan Ketua RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-003-tabel-antrean-surat-pengantar.png`

---

### 3.2 Meninjau Berkas Identitas & Keperluan Pemohon
Sebelum menyetujui permohonan surat:
1. Klik tombol **Lihat** (ikon mata) pada baris surat yang ingin diperiksa.
2. Jendela modal rincian akan menampilkan:
   - Nama lengkap pemohon dan anggota keluarga terkait.
   - NIK dan nomor Kartu Keluarga pemohon.
   - Jenis surat yang diminta (misal: *Surat Pengantar SKCK*).
   - Penjelasan keperluan yang ditulis oleh warga.
3. Pastikan permohonan tersebut sesuai dengan kondisi riil warga bersangkutan di lingkungan RT 15.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-004]**
> - **Halaman / Tampilan:** Modal Pratinjau Rincian Surat Pengantar Warga
> - **Elemen yang Ditampilkan:** Modal dialog yang menyajikan detail identitas pemohon, kartu keluarga asal, maksud pengajuan, dan riwayat status pengajuan.
> - **Fokus / Aksi Pengguna:** Pemeriksaan detail sebelum menentukan keputusan persetujuan.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-004-modal-detail-tinjau-surat.png`

---

### 3.3 Prosedur Menyetujui Surat (Approval)
Jika berkas dan keperluan warga telah valid:
1. Pada baris surat bersangkutan (atau di dalam modal rincian), klik tombol hijau **"Setujui"** (ikon centang lingkaran).
2. Kotak dialog konfirmasi akan muncul:
   > *"Apakah Anda yakin ingin menyetujui surat pengantar ini? Nomor surat resmi akan diterbitkan secara otomatis."*
3. Klik tombol konfirmasi **"Setujui"**.
4. **Otomatisasi Sistem**:
   - Sistem menerbitkan nomor register dinas resmi dengan format baku:
     `{NOMOR}/RT.15/PMS/{BULAN_ROMAWI}/{TAHUN}` (contoh: `005/RT.15/PMS/X/2026`).
   - Status surat seketika berubah menjadi 🟢 **Disetujui**.
   - Nama Ketua RT dan waktu persetujuan dicatat sebagai penandatangan sah.
   - Token verifikasi QR Code terenkripsi secara otomatis disematkan ke dokumen.
   - Warga pemohon seketika menerima notifikasi di ponselnya bahwa surat telah selesai dan siap diunduh.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-005]**
> - **Halaman / Tampilan:** Dialog Konfirmasi Persetujuan Surat Pengantar
> - **Elemen yang Ditampilkan:** Modal konfirmasi bertuliskan *"Persetujuan Surat Pengantar"* dengan tombol konfirmasi hijau "Setujui" dan tombol "Batal".
> - **Fokus / Aksi Pengguna:** Penekanan tombol persetujuan surat.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-005-konfirmasi-setujui-surat.png`

---

### 3.4 Prosedur Menolak Surat dengan Catatan Alasan
Jika permohonan warga tidak memenuhi syarat (misal: data KTP belum diperbarui, warga tidak berdomisili nyata, atau keperluan tidak jelas):
1. Klik tombol merah **"Tolak"** (ikon silang lingkaran).
2. Jendela formulir penolakan akan terbuka.
3. **Wajib Mengisi Alasan Penolakan**:
   - Ketikkan alasan penolakan secara jelas dan edukatif pada kolom textarea (contoh: *"Mohon maaf, permohonan ditolak karena masa berlaku surat keterangan domisili lama Anda telah habis. Silakan lampirkan bukti pembayaran PBB tahun berjalan terlebih dahulu"*).
4. Klik tombol **"Tolak Surat"**.
5. Status surat berubah menjadi 🔴 **Ditolak**, dan alasan tersebut akan langsung dibaca oleh warga di aplikasi mandirinya agar warga dapat memperbaiki permohonannya.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-006]**
> - **Halaman / Tampilan:** Modal Penolakan Surat dengan Alasan Wajib
> - **Elemen yang Ditampilkan:** Formulir modal dengan judul *"Tolak Permohonan Surat"*, kolom teks isian *"Alasan Penolakan"*, dan tombol merah konfirmasi penolakan.
> - **Fokus / Aksi Pengguna:** Pengisian alasan penolakan yang transparan.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-006-modal-alasan-tolak-surat.png`

---

### 3.5 Mencetak Lembar Resmi Surat Pengantar Ber-QR Code
Untuk keperluan warga yang membutuhkan lembar cetak fisik (misalnya warga lanjut usia atau berkas fisik untuk kantor polisi):
1. Pada baris surat yang telah berstatus *Disetujui*, klik tombol biru **"Cetak"** (ikon printer).
2. Lembar resmi surat pengantar format PDF ukuran A4 akan terbuka di tab baru.
3. Anda dapat langsung mencetaknya ke mesin printer atau menyimpannya sebagai berkas PDF. Lembar surat telah memuat:
   - Kop Surat RT 15 Kelurahan Pematang Sulur.
   - Nomor Register Surat Otomatis.
   - Tanda Tangan & Cap Digital Ketua RT 15.
   - **QR Code Keaslian Dokumen**: Pihak kelurahan atau instansi tujuan cukup memindai QR Code tersebut untuk membuktikan keabsahan surat tanpa perlu verifikasi tanda tangan basah ulang.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-007]**
> - **Halaman / Tampilan:** Lembar Pratinjau Dokumen Cetak Surat Pengantar Resmi
> - **Elemen yang Ditampilkan:** Dokumen PDF resmi siap cetak yang memuat kop surat RT 15, data identitas warga, nomor surat resmi dinas, tanda tangan digital Ketua RT, stempel RT, dan QR Code verifikasi keaslian.
> - **Fokus / Aksi Pengguna:** Tampilan utuh dokumen dinas resmi RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-007-pratinjau-cetak-surat-ketua-rt.png`

---

## 4. Pengawasan Kependudukan & Dinamika Warga

### 4.1 Memantau Data Kartu Keluarga & Demografi Warga
1. Buka menu **Kependudukan > Kartu Keluarga (KK)** (`/admin/households`).
2. Ketua RT dapat melihat seluruh kepala keluarga yang terdaftar, alamat blok rumah, status hunian (tetap/kontrak/kos), serta lampiran foto fisik KK warga.
3. Buka menu **Kependudukan > Data Warga** (`/admin/residents`) untuk melihat sebaran demografi usia, jenis kelamin, pendidikan, dan pekerjaan warga RT 15.

---

### 4.2 Memantau Mutasi Kependudukan
Gunakan filter status kependudukan pada tabel warga untuk memantau:
- Warga berstatus **Aktif** (warga domisili riil saat ini).
- Warga berstatus **Pindah** (keluar dari lingkungan RT 15).
- Warga berstatus **Meninggal** (pencatatan data kematian lingkungan).
- Warga berstatus **Lahir Baru** (penambahan generasi baru di RT 15).

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-008]**
> - **Halaman / Tampilan:** Tabel Rekapitulasi Data Kependudukan Warga (`/admin/residents`)
> - **Elemen yang Ditampilkan:** Daftar warga dengan kolom ID Warga, NIK (sensor privasi), Nama Lengkap, No. KK asal, Hubungan Keluarga, Pekerjaan, dan Status Kependudukan.
> - **Fokus / Aksi Pengguna:** Pemantauan demografi warga RT 15 oleh Ketua RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-008-tabel-data-kependudukan-rt.png`

---

## 5. Disposisi & Penyelesaian Pengaduan Warga

Sistem SIM-LiBas memungkinkan warga menyampaikan aspirasi dan keluhan secara terstruktur.

### 5.1 Meninjau Laporan Keluhan Lingkungan
1. Buka menu **Komunikasi Warga > Pengaduan** (`/admin/complaints`).
2. Tabel memuat seluruh laporan warga, lengkap dengan kategori (Infrastruktur, Kebersihan, Keamanan, Kebisingan, Lainnya), tingkat urgensi, serta status pelaporan anonim.
   > [!NOTE]
   > Jika warga mengirim laporan dengan opsi **Anonim / Rahasia**, nama warga akan disamarkan dari portal umum, namun Ketua RT tetap dapat melihat pelapor demi menjamin keabsahan informasi dan menghindari laporan palsu.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-009]**
> - **Halaman / Tampilan:** Tabel Manajemen Pengaduan & Aspirasi Warga (`/admin/complaints`)
> - **Elemen yang Ditampilkan:** Daftar pengaduan masuk dengan kolom Judul Aduan, Kategori Masalah, Pelapor (dengan penanda badge Anonim), Lokasi Kejadian, dan Lencana Status Penanganan.
> - **Fokus / Aksi Pengguna:** Pemeriksaan daftar keluhan fasilitas lingkungan warga.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-009-tabel-pengaduan-warga-rt.png`

---

### 5.2 Menugaskan Penanggung Jawab Tindak Lanjut
1. Klik tombol **Ubah / Edit** pada laporan yang perlu ditindaklanjuti.
2. Pada formulir pengaduan:
   - Ubah status dari `diterima` menjadi `diproses`.
   - Pada kolom **Penanggung Jawab**, pilih koordinator terkait (misalnya: *Bpk. Hendra Saputra* untuk keamanan atau *Bpk. Joko Wahyudi* untuk masalah sampah).
3. Klik **Simpan Perubahan**.

---

### 5.3 Memperbarui Progres & Mengisi Catatan Penyelesaian
Setelah masalah diselesaikan di lapangan (misalnya lampu jalan yang padam sudah diperbaiki):
1. Buka kembali laporan pengaduan tersebut.
2. Ubah status menjadi **Selesai**.
3. Isi kolom **Catatan Penanganan** dengan penjelasan nyata (contoh: *"Lampu penerangan jalan gang mawar telah diganti dengan bohlam LED 30 Watt baru pada hari Minggu pukul 09.00 WIB oleh tim ronda"*).
4. Klik **Simpan Perubahan**. Warga pelapor akan menerima notifikasi bahwa laporannya telah tuntas ditangani.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-010]**
> - **Halaman / Tampilan:** Formulir Tindak Lanjut & Catatan Penyelesaian Pengaduan
> - **Elemen yang Ditampilkan:** Form edit pengaduan dengan pilihan status "Selesai", dropdown penugasan Penanggung Jawab, dan kotak teks textarea "Catatan Penanganan".
> - **Fokus / Aksi Pengguna:** Penyelesaian laporan keluhan warga secara transparan.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-010-form-penyelesaian-pengaduan.png`

---

## 6. Moderasi Etalase UMKM Warga RT 15

Untuk mendukung perputaran roda ekonomi lokal warga tanpa merusak ketertiban lingkungan:

### 6.1 Memeriksa Pengajuan Profil Usaha
1. Buka menu **Kampung Digital > Profil UMKM** (`/admin/msme-profiles`).
2. Setiap warga yang mendaftarkan usaha rumahannya akan masuk ke dalam tabel dengan status **Pending**.
3. Klik tombol **Ubah / Edit** untuk memeriksa kelayakan:
   - Nama Usaha (misal: *Pempek & Tekwan Pinang Masak 15*).
   - Kategori Produk (Kuliner, Kerajinan, Jasa, Sembako).
   - Nomor WhatsApp usaha dan alamat rumah di RT 15.
   - Foto produk yang dilampirkan.

---

### 6.2 Menyetujui atau Menolak Tayang di Katalog Publik
1. Pada bagian **Status Moderasi**:
   - Pilih **Disetujui** jika usaha benar milik warga RT 15 dan memenuhi norma lingkungan.
   - Pilih **Ditolak** jika data usaha tidak valid atau fiktif.
2. Tuliskan **Catatan Moderasi** (contoh: *"Usaha kuliner warga aktif, makanan higienis dan terdaftar"*).
3. Pastikan tombol saklar **Status Aktif** dalam keadaan menyala.
4. Klik **Simpan Perubahan**. Usaha yang disetujui akan seketika tampil di laman publik **Katalog UMKM (`/katalog-umkm`)** sehingga dapat dipesan oleh masyarakat luas.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-011]**
> - **Halaman / Tampilan:** Formulir Moderasi Usaha UMKM Warga (`/admin/msme-profiles`)
> - **Elemen yang Ditampilkan:** Form pendaftaran usaha warga yang memuat nama usaha, deskripsi produk, nomor kontak WhatsApp, dropdown pilihan status moderasi (Pending/Disetujui/Ditolak), dan catatan moderasi.
> - **Fokus / Aksi Pengguna:** Persetujuan etalase UMKM warga oleh Ketua RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-011-moderasi-umkm-warga.png`

---

## 7. Penerbitan Pengumuman Resmi & Agenda RT

### 7.1 Menerbitkan Maklumat / Pengumuman Penting RT
Ketua RT dapat mempublikasikan pengumuman penting yang langsung muncul di dasbor seluruh warga:
1. Buka menu **Komunikasi Warga > Pengumuman RT** (`/admin/announcements`).
2. Klik tombol **Buat Pengumuman**.
3. Isi data pengumuman:
   - **Judul**: (Contoh: *"Maklumat Keamanan Lingkungan Menjelang Hari Raya Idul Fitri"*).
   - **Isi Pengumuman**: Tuliskan imbauan lengkap.
   - **Target Audiens**: Pilih *"Semua"*, *"Warga"*, atau *"Pengurus"*.
   - **Sematkan di Atas (Is Pinned)**: Aktifkan saklar ini agar pengumuman selalu berada di urutan teratas beranda warga.
   - **Tanggal Tayang & Tanggal Berakhir**: Tentukan periode pengumuman aktif.
4. Klik tombol **Simpan**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-012]**
> - **Halaman / Tampilan:** Formulir Penerbitan Pengumuman Resmi RT (`/admin/announcements/create`)
> - **Elemen yang Ditampilkan:** Form isian judul pengumuman, textarea konten isi maklumat, pilihan target audiens, saklar sematkan (*pinned*), saklar terbitkan (*published*), dan pemilihan tanggal tayang.
> - **Fokus / Aksi Pengguna:** Pembuatan pengumuman resmi pimpinan RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-012-form-buat-pengumuman-rt.png`

---

### 7.2 Menjadwalkan Agenda Rapat Warga & Kegiatan Lingkungan
1. Buka menu **Komunikasi Warga > Agenda Kegiatan** (`/admin/events`).
2. Klik tombol **Buat Agenda**.
3. Masukkan rincian kegiatan:
   - **Judul Kegiatan**: (Contoh: *"Musyawarah Warga Pemilihan Panitia Qurban"*).
   - **Kategori**: Pilih *Rapat*, *Kerja Bakti*, *Arisan*, *Pengajian*, atau *Perayaan*.
   - **Lokasi Acara**: (Contoh: *"Balai Pertemuan Warga RT 15"*).
   - **Tanggal & Waktu Mulai / Selesai**.
   - **Penyelenggara**: (Contoh: *"Pengurus RT 15"*).
4. Klik **Simpan**. Agenda otomatis tampil di kalender kegiatan beranda warga.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-013]**
> - **Halaman / Tampilan:** Formulir Penjadwalan Agenda Warga (`/admin/events/create`)
> - **Elemen yang Ditampilkan:** Form input judul agenda, dropdown kategori acara, input lokasi, pemilih tanggal-waktu mulai dan selesai, serta nama penyelenggara kegiatan.
> - **Fokus / Aksi Pengguna:** Penjadwalan musyawarah atau kegiatan sosial warga.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-013-form-buat-agenda-rt.png`

---

## 8. Pengawasan Keuangan Kas RT & Keamanan Wilayah

### 8.1 Memantau Laporan Buku Kas Umum & Saldo Akhir
Ketua RT memiliki wewenang pengawasan anggaran:
1. Buka menu **Keuangan RT > Transaksi Kas RT** (`/admin/cash-transactions`).
2. Periksa arus kas masuk (iuran warga, bantuan sosial, donasi) dan arus kas keluar (honor satpam, perbaikan jalan, listrik pos kamling).
3. Buka menu **Keuangan RT > Tagihan Iuran** (`/admin/invoices`) untuk memantau persentase KK yang sudah lunas dan yang masih menunggak tanpa perlu menghitung manual.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-014]**
> - **Halaman / Tampilan:** Buku Kas Umum & Transaksi Keuangan RT (`/admin/cash-transactions`)
> - **Elemen yang Ditampilkan:** Tabel transaksi kas dengan kolom Tanggal, Kategori, Jenis Arus Kas (Masuk/Keluar), Nominal Rupiah, Deskripsi Pengeluaran, dan Bukti Nota Belanja.
> - **Fokus / Aksi Pengguna:** Pengawasan transparansi keuangan RT oleh Ketua RT.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-014-buku-kas-keuangan-rt.png`

---

### 8.2 Memantau Jadwal Ronda Siskamling & Buku Laporan Tamu
1. Buka menu **Keamanan Lingkungan > Jadwal Ronda** (`/admin/patrol-schedules`) untuk memastikan jadwal giliran jaga pos kamling warga berjalan merata setiap malamnya.
2. Buka menu **Keamanan Lingkungan > Laporan Tamu** (`/admin/guest-reports`) untuk memantau tamu asing atau kerabat luar kota yang menginap lebih dari 1x24 jam di rumah warga demi stabilitas keamanan lingkungan RT 15.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-KRT-015]**
> - **Halaman / Tampilan:** Tabel Pemantauan Laporan Tamu Menginap 1x24 Jam (`/admin/guest-reports`)
> - **Elemen yang Ditampilkan:** Rekapitulasi tamu lingkungan yang memuat Nama Tamu, Tuan Rumah (KK Warga), Tanggal Tiba, Lama Menginap, Maksud Kunjungan, dan Nomor Kontak.
> - **Fokus / Aksi Pengguna:** Pengawasan stabilitas keamanan lingkungan warga.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-krt-015-pemantauan-buku-tamu-rt.png`

---

## 9. Daftar Periksa Slot Tangkapan Layar (Checklist)

Gunakan tabel daftar periksa berikut saat Anda mengambil dan memasukkan foto tangkapan layar ke dalam dokumen panduan Ketua RT ini:

| No | Kode Slot Tangkapan Layar | Halaman Antarmuka Terkait | Rekomendasi Nama Berkas Gambar | Status Gambar |
|:---:|---|---|---|:---:|
| 1 | `SS-KRT-001` | Halaman Masuk Panel Pengurus | `assets/screenshots/ss-krt-001-login-panel-pengurus.png` | [ ] Belum Dipasang |
| 2 | `SS-KRT-002` | Dasbor Utama Eksekutif Ketua RT | `assets/screenshots/ss-krt-002-dashboard-eksekutif-ketua-rt.png` | [ ] Belum Dipasang |
| 3 | `SS-KRT-003` | Tabel Antrean Surat Pengantar Warga | `assets/screenshots/ss-krt-003-tabel-antrean-surat-pengantar.png` | [ ] Belum Dipasang |
| 4 | `SS-KRT-004` | Modal Pratinjau Rincian Surat Pemohon | `assets/screenshots/ss-krt-004-modal-detail-tinjau-surat.png` | [ ] Belum Dipasang |
| 5 | `SS-KRT-005` | Dialog Konfirmasi Persetujuan Surat | `assets/screenshots/ss-krt-005-konfirmasi-setujui-surat.png` | [ ] Belum Dipasang |
| 6 | `SS-KRT-006` | Modal Penolakan Surat dengan Alasan Wajib | `assets/screenshots/ss-krt-006-modal-alasan-tolak-surat.png` | [ ] Belum Dipasang |
| 7 | `SS-KRT-007` | Lembar Cetak Surat Pengantar Ber-QR Code | `assets/screenshots/ss-krt-007-pratinjau-cetak-surat-ketua-rt.png` | [ ] Belum Dipasang |
| 8 | `SS-KRT-008` | Tabel Rekapitulasi Kependudukan Warga | `assets/screenshots/ss-krt-008-tabel-data-kependudukan-rt.png` | [ ] Belum Dipasang |
| 9 | `SS-KRT-009` | Tabel Pengaduan & Aspirasi Masuk | `assets/screenshots/ss-krt-009-tabel-pengaduan-warga-rt.png` | [ ] Belum Dipasang |
| 10 | `SS-KRT-010` | Form Disposisi & Catatan Penanganan Aduan | `assets/screenshots/ss-krt-010-form-penyelesaian-pengaduan.png` | [ ] Belum Dipasang |
| 11 | `SS-KRT-011` | Form Moderasi Usaha UMKM Warga | `assets/screenshots/ss-krt-011-moderasi-umkm-warga.png` | [ ] Belum Dipasang |
| 12 | `SS-KRT-012` | Form Penerbitan Pengumuman Resmi RT | `assets/screenshots/ss-krt-012-form-buat-pengumuman-rt.png` | [ ] Belum Dipasang |
| 13 | `SS-KRT-013` | Form Penjadwalan Agenda Kegiatan Warga | `assets/screenshots/ss-krt-013-form-buat-agenda-rt.png` | [ ] Belum Dipasang |
| 14 | `SS-KRT-014` | Buku Kas Umum & Transaksi Keuangan RT | `assets/screenshots/ss-krt-014-buku-kas-keuangan-rt.png` | [ ] Belum Dipasang |
| 15 | `SS-KRT-015` | Tabel Pemantauan Tamu Menginap 1x24 Jam | `assets/screenshots/ss-krt-015-pemantauan-buku-tamu-rt.png` | [ ] Belum Dipasang |

---
*Dokumen ini merupakan bagian dari Buku Panduan Resmi Sistem Informasi Manajemen RT 15 (SIM-LiBas).*
