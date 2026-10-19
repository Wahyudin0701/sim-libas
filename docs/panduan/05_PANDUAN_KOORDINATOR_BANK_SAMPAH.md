# BUKU PANDUAN PENGGUNA SIM-LiBas
## TAHAP 4A — PANDUAN ROLE KOORDINATOR BANK SAMPAH
**Sistem Informasi Manajemen RT 15 Pematang Sulur, Kecamatan Telanaipura, Kota Jambi**

---

## DAFTAR ISI
1. [Mengenal Bank Sampah Pinang Masak Lestari RT 15](#1-mengenal-bank-sampah-pinang-masak-lestari-rt-15)
2. [Akses Masuk & Dasbor Bank Sampah (/admin)](#2-akses-masuk--dasbor-bank-sampah-admin)
   - 2.1 Masuk Menggunakan Akun Koordinator Bank Sampah
   - 2.2 Mengenal Tampilan & Indikator Kinerja Pengelolaan Sampah
3. [Pengelolaan Master Jenis & Harga Beli Sampah](#3-pengelolaan-master-jenis--harga-beli-sampah)
   - 3.1 Daftar Komoditas Sampah Daur Ulang yang Diterima
   - 3.2 Menambah Jenis Komoditas Sampah Baru
   - 3.3 Memperbarui Fluktuasi Harga Beli per Kilogram
   - 3.4 Mengatur Kategori (Anorganik, Organik, B3, Lainnya) & Status Aktif
4. [Pencatatan Transaksi Penimbangan Setoran Sampah](#4-pencatatan-transaksi-penimbangan-setoran-sampah)
   - 4.1 Prosedur Penimbangan Sampah Warga di Posko
   - 4.2 Langkah Penginputan Formulir Penimbangan Baru
   - 4.3 Otomatisasi Perhitungan Nilai Rupiah Setoran
   - 4.4 Otomatisasi Akumulasi Saldo Dompet Warga (WasteWallet)
   - 4.5 Cetak & Bagikan Nota / Struk Penimbangan Digital
5. [Manajemen Tabungan & Mutasi Saldo Warga](#5-manajemen-tabungan--mutasi-saldo-warga)
   - 5.1 Memantau Buku Tabungan Sampah Tiap Keluarga
   - 5.2 Riwayat Mutasi Transaksi Tabungan Lingkungan
   - 5.3 Prosedur Pencairan Saldo Tunai & Konversi untuk Iuran RT
6. [Pemantauan Metrik Dampak Lingkungan Lingkup RT 15](#6-pemantauan-metrik-dampak-lingkungan-lingkup-rt-15)
   - 6.1 Total Tonase Sampah yang Dialihkan dari TPA (kg)
   - 6.2 Estimasi Reduksi Emisi Jejak Karbon
7. [Ekspor Laporan Transaksi & Tabungan (PDF / Excel)](#7-ekspor-laporan-transaksi--tabungan-pdf--excel)
   - 7.1 Ekspor Rekapitulasi Penimbangan ke Lembar Kerja Excel
   - 7.2 Ekspor Laporan Rekap Buku Tabungan Nasabah ke PDF Resmi
8. [Daftar Periksa Slot Tangkapan Layar (Checklist)](#8-daftar-periksa-slot-tangkapan-layar-checklist)

---

## 1. Mengenal Bank Sampah Pinang Masak Lestari RT 15

Sebagai bagian penting dari program percontohan **Kampung Digital RT 15 Pematang Sulur**, **Bank Sampah Pinang Masak Lestari** hadir untuk mengubah sampah anorganik rumah tangga menjadi berkah ekonomi yang nyata.

Koordinator Bank Sampah bertindak sebagai pengelola teknis operasional penimbangan dan pembukuan tabungan lingkungan warga. Sistem SIM-LiBas menghadirkan digitalisasi menyeluruh bagi bank sampah:
- **Timbangan Presisi Berbasis Digital**: Setiap komoditas yang ditimbang langsung terkonversi otomatis ke nominal rupiah berdasarkan harga beli per kg terkini.
- **Dompet Sampah Virtual (*WasteWallet*)**: Setiap Kepala Keluarga memiliki rekening buku tabungan sampah digital yang saldo rupiahnya dapat dipantau langsung dari layar ponsel warga di portal mandiri.
- **Konversi Multi-Guna**: Hasil tabungan sampah dapat dicairkan tunai atau langsung dipotongkan untuk melunasi tagihan iuran bulanan RT 15.
- **Indikator Dampak Lingkungan**: Menghitung secara otomatis kontribusi warga RT 15 dalam mengurangi beban sampah ke Tempat Pembuangan Akhir (TPA) Talang Gulo Kota Jambi.

---

## 2. Akses Masuk & Dasbor Bank Sampah (/admin)

### 2.1 Masuk Menggunakan Akun Koordinator
1. Buka peramban di komputer posko penimbangan atau ponsel pintar Anda.
2. Masukkan alamat: **`http://localhost/admin`**.
3. Masukkan kredensial login resmi Koordinator Bank Sampah:
   - **Email**: `banksampah@simlibas.com`
   - **Kata Sandi**: `password` (atau kata sandi baru Anda)
4. Klik tombol **"Masuk ke Panel Pengurus"**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-001]**
> - **Halaman / Tampilan:** Formulir Masuk Panel Akun Koordinator Bank Sampah (`/admin/login`)
> - **Elemen yang Ditampilkan:** Halaman login dengan email `banksampah@simlibas.com` dan kursor pada kolom kata sandi.
> - **Fokus / Aksi Pengguna:** Sesi login petugas Bank Sampah Pinang Masak Lestari.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-001-login-bank-sampah.png`

---

### 2.2 Mengenal Tampilan & Indikator Kinerja Pengelolaan Sampah
Setelah berhasil masuk, Dasbor Khusus Bank Sampah akan menyajikan ringkasan metrik ekologis:
- Sapaan kerja: *"Selamat Datang, Bpk. Joko Wahyudi (Koordinator Bank Sampah)"*.
- **Total Timbulan Terkelola (kg)**: Akumulasi bobot sampah terpilah yang berhasil dikumpulkan warga RT 15.
- **Total Nilai Rupiah Setoran**: Nilai perputaran ekonomi sirkular yang dinikmati nasabah warga.
- **Total Transaksi Setoran**: Jumlah aktivitas penimbangan yang telah tercatat di sistem.
- **Tabel Setoran Terkini**: Daftar 4 transaksi penimbangan sampah terakhir warga.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-002]**
> - **Halaman / Tampilan:** Dasbor Panel Pengurus Koordinator Bank Sampah (`/admin`)
> - **Elemen yang Ditampilkan:** Dasbor dengan kartu metrik total kilogram sampah terkelola, total nilai rupiah setoran, jumlah komoditas aktif, dan tabel penimbangan terbaru warga.
> - **Fokus / Aksi Pengguna:** Pemantauan statistik operasional bank sampah.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-002-dashboard-bank-sampah.png`

---

## 3. Pengelolaan Master Jenis & Harga Beli Sampah

Harga komoditas daur ulang bersifat dinamis mengikuti harga pasar pengepul di Kota Jambi. Koordinator dapat memperbarui harga kapan saja.

### 3.1 Daftar Komoditas Sampah yang Diterima
Bank Sampah Pinang Masak Lestari menerima berbagai jenis komoditas terpilah:
1. **Kardus / Karton Coklat**: Kotak kardus tebal bekas perabot/makanan (bersih dan kering).
2. **Botol Plastik PET Bersih (Mineral Bening)**: Botol air mineral bening yang sudah dilepas label dan tutupnya.
3. **Besi Tua / Logam Padat**: Pagar sisa renovasi, seng tebal, kaleng kental manis, peralatan dapur rusak.
4. **Minyak Jelantah (Minyak Goreng Bekas)**: Minyak bekas memasak yang disaring bersih dalam botol/jeriken.
5. **Kertas Dupleks / HVS / Koran**: Kertas buku tulis lama, majalah, atau koran bekas.

---

### 3.2 Menambah Jenis Komoditas Sampah Baru
1. Pada menu bilah samping kiri, klik grup **Kampung Digital > Master Jenis Sampah** (`/admin/waste-types`).
2. Klik tombol **"+ Buat Jenis Sampah"** di kanan atas (`/admin/waste-types/create`).
3. Isi data komoditas:
   - **Nama Komoditas**: Masukkan nama jelas (contoh: *Aluminium Bekas Kaleng Minuman*).
   - **Kategori**: Pilih `Anorganik`, `Organik`, `B3`, atau `Lainnya`.
   - **Harga per Kg (Rp)**: Masukkan harga beli dari warga dalam angka murni tanpa titik (contoh: ketik `12000` untuk Rp 12.000/kg).
   - **Satuan**: Terisi otomatis `kg` (atau `liter` untuk minyak jelantah).
   - **Status Aktif**: Pastikan saklar hijau menyala agar komoditas ini dapat dipilih pada saat penimbangan.
4. Klik **"Buat"** untuk menyimpan.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-003]**
> - **Halaman / Tampilan:** Formulir Tambah Master Jenis Sampah Baru (`/admin/waste-types/create`)
> - **Elemen yang Ditampilkan:** Form isian Nama Komoditas Sampah, Dropdown Kategori (Anorganik/Organik/B3), Input Harga Beli per Kg, Satuan timbang, dan Saklar Status Aktif.
> - **Fokus / Aksi Pengguna:** Penambahan pos komoditas daur ulang baru.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-003-form-tambah-jenis-sampah.png`

---

### 3.3 Memperbarui Fluktuasi Harga Beli per Kilogram
Jika harga jual ke pengepul naik atau turun:
1. Pada tabel Master Jenis Sampah (`/admin/waste-types`), klik tombol **Ubah / Edit** pada komoditas yang harganya berubah.
2. Perbarui angka pada kolom **Harga per Kg**.
3. Klik **Simpan Perubahan**.
4. **Catatan Transaksi**: Pembaruan harga ini hanya berlaku untuk penimbangan baru berikutnya; transaksi penimbangan masa lalu tidak akan berubah nilainya demi menjaga integritas pembukuan saldo nasabah.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-004]**
> - **Halaman / Tampilan:** Tabel Master Jenis Sampah & Daftar Harga Beli Terkini (`/admin/waste-types`)
> - **Elemen yang Ditampilkan:** Tabel komoditas sampah dengan kolom Nama Jenis, Kategori, Harga Beli per Kg (Rupiah), Satuan, Status Saklar Aktif, dan tombol aksi Ubah.
> - **Fokus / Aksi Pengguna:** Pemantauan dan penyesuaian tarif harga beli sampah RT 15.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-004-tabel-master-harga-sampah.png`

---

## 4. Pencatatan Transaksi Penimbangan Setoran Sampah

### 4.1 Prosedur Penimbangan Sampah Warga di Posko
Setiap jadwal penimbangan bank sampah (misal: hari Minggu pagi di Posko RT 15):
1. Warga membawa sampah terpilah dari rumah masing-masing.
2. Petugas memastikan sampah dalam keadaan bersih dan tidak bercampur sampah basah/organik dapur yang membusuk.
3. Timbang berat sampah di timbangan gantung atau timbangan duduk digital, lalu catat bobot kilogramnya.

---

### 4.2 Langkah Penginputan Formulir Penimbangan Baru
1. Buka menu **Kampung Digital > Setoran Bank Sampah** (`/admin/waste-deposits`).
2. Klik tombol biru **"+ Catat Penimbangan"** di sudut kanan atas.
3. Jendela sembul formulir penimbangan akan muncul di layar:
   - **Keluarga / Nasabah**: Pilih nomor Kartu Keluarga atau nama Kepala Keluarga yang menyetor (contoh: *[KK-001] Bpk. Dedi Kurniawan*).
   - **Jenis Sampah**: Pilih komoditas yang disetorkan (misal: *Kardus / Karton Coklat*).
   - **Berat (kg)**: Masukkan angka berat timbangan (dapat menggunakan angka desimal dengan koma/titik, contoh: `8.5` untuk 8,5 kilogram).
   - **Catatan (Opsional)**: Tuliskan kondisi barang (contoh: *"Kardus bekas kardus kulkas dan TV warga"*).
4. Klik tombol biru **"Simpan Data Penimbangan"**.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-005]**
> - **Halaman / Tampilan:** Modal Formulir Pencatatan Penimbangan Sampah Baru (`/admin/waste-deposits`)
> - **Elemen yang Ditampilkan:** Modal dialog input penimbangan yang memuat dropdown pemilihan KK nasabah, dropdown jenis komoditas sampah, input berat (kg), kalkulasi nilai rupiah otomatis, dan catatan kondisi sampah.
> - **Fokus / Aksi Pengguna:** Penginputan data timbangan sampah warga di posko.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-005-modal-catat-penimbangan-sampah.png`

---

### 4.3 Otomatisasi Perhitungan Nilai Rupiah & Akumulasi Saldo Warga
Begitu tombol simpan ditekan, SIM-LiBas mengeksekusi otomatisasi perbankan sampah secara seketika:
1. **Perhitungan Nilai Rupiah**:
   `Total Nilai (Rp) = Berat (kg) × Harga Komoditas Saat Setor (Rp/kg)`
   *(Contoh: 8,5 kg × Rp 1.500 = Rp 12.750)*.
2. **Akumulasi Saldo Dompet Warga**:
   Sistem langsung menambahkan Rp 12.750 ke dalam rekening dompet virtual (*WasteWallet*) keluarga Bpk. Dedi Kurniawan.
3. **Pencatatan Buku Mutasi**:
   Sistem mencatat transaksi mutasi kredit pada tabel `WasteWalletTransaction` lengkap dengan saldo sebelum, nilai setoran, dan saldo sesudah transaksi.
4. **Pembaruan Portal Mandiri**:
   Saat Bpk. Dedi membuka aplikasi SIM-LiBas di ponselnya, saldo tabungan sampah di beranda akunnya telah bertambah secara *real-time*.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-006]**
> - **Halaman / Tampilan:** Tabel Riwayat Setoran Penimbangan Sampah Warga (`/admin/waste-deposits`)
> - **Elemen yang Ditampilkan:** Tabel transaksi penimbangan lengkap dengan kolom Tanggal Timbang, Nasabah (No. KK & Nama Kepala Keluarga), Komoditas, Berat (kg), Harga Saat Setor, Total Nilai Rupiah, dan Nama Petugas Pencatat.
> - **Fokus / Aksi Pengguna:** Riwayat data penimbangan sampah lingkungan.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-006-tabel-riwayat-setoran-sampah.png`

---

### 4.4 Cetak & Bagikan Nota / Struk Penimbangan Digital
Untuk memberikan tanda bukti setoran kepada warga:
1. Pada baris transaksi penimbangan yang baru dicatat, klik tombol **Lihat** (ikon mata).
2. Jendela modal rincian setoran akan tampil menyerupai struk resmi Bank Sampah Pinang Masak Lestari:
   - Nama Nasabah & Nomor KK.
   - Jenis Sampah & Bobot Timbangan.
   - Nilai Perolehan Rupiah.
   - Total Saldo Akumulasi Tabungan Keluarga Terkini.
3. Anda dapat menunjukkan layar ini ke warga atau mencetaknya ke printer struk posko.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-007]**
> - **Halaman / Tampilan:** Modal Rincian Struk / Nota Penimbangan Digital Bank Sampah
> - **Elemen yang Ditampilkan:** Modal nota digital berdesain struk resmi yang memuat kop Bank Sampah Pinang Masak Lestari, identitas nasabah KK, rincian berat dan harga komoditas, total rupiah yang diperoleh, dan saldo tabungan akhir nasabah.
> - **Fokus / Aksi Pengguna:** Bukti setor transparan untuk warga nasabah.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-007-nota-digital-setoran-sampah.png`

---

## 5. Manajemen Tabungan & Mutasi Saldo Warga

### 5.1 Memantau Buku Tabungan Sampah Tiap Keluarga
Koordinator dapat memantau saldo tabungan dari seluruh keluarga di RT 15:
- Gunakan kolom pencarian di tabel Setoran Sampah untuk mengetikkan nama warga atau nomor KK tertentu.
- Sistem akan menyaring seluruh riwayat setoran keluarga tersebut sehingga Koordinator dapat mengetahui seberapa aktif keluarga tersebut dalam program pilah sampah lingkungan.

---

### 5.2 Riwayat Mutasi Transaksi Tabungan
Setiap pergerakan saldo tersimpan rapi dengan 2 jenis mutasi:
- **Setoran (Kredit / Bertambah)**: Hasil penimbangan sampah yang disetor.
- **Penarikan / Konversi (Debit / Berkurang)**: Saat warga mencairkan uang tabungannya atau mengalokasikannya untuk pembayaran iuran RT.

---

### 5.3 Prosedur Pencairan Saldo Tunai & Konversi untuk Iuran RT
Warga memiliki hak untuk memanfaatkan saldo tabungan sampahnya:
1. **Pencairan Uang Tunai**:
   - Warga mendatangi Koordinator Bank Sampah untuk mencairkan sebagian atau seluruh tabungannya.
   - Koordinator menyerahkan uang tunai fisik dari kas operasional bank sampah, lalu mencatatkan mutasi penarikan pada sistem agar saldo dompet warga berkurang sesuai nominal yang ditarik.
2. **Konversi Pelunasan Iuran Kas RT**:
   - Jika warga ingin membayar iuran bulanan RT menggunakan saldo bank sampah, Koordinator Bank Sampah berkoordinasi dengan Bendahara RT untuk memotong saldo dompet sampah dan menerbitkan kuitansi pelunasan iuran bulanan bagi keluarga tersebut.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-008]**
> - **Halaman / Tampilan:** Halaman Informasi Saldo & Mutasi Dompet Sampah Keluarga
> - **Elemen yang Ditampilkan:** Rincian saldo buku tabungan sampah keluarga, kartu saldo aktif, dan daftar mutasi debit/kredit setoran warga.
> - **Fokus / Aksi Pengguna:** Pengelolaan buku tabungan nasabah bank sampah.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-008-mutasi-saldo-tabungan-warga.png`

---

## 6. Pemantauan Metrik Dampak Lingkungan Lingkup RT 15

Selain manfaat ekonomi, sistem SIM-LiBas menghitung dampak positif ekologis yang dihasilkan warga RT 15:

### 6.1 Total Tonase Sampah Dialihkan dari TPA
- Pada dasbor utama dan halaman profil bank sampah publik (`/bank-sampah`), sistem menjumlahkan seluruh bobot kilogram sampah anorganik yang disetor.
- Angka ini mencerminkan volume sampah yang berhasil dicegah mencemari drainase parit RT 15 dan tidak membebani kapasitas Tempat Pembuangan Akhir (TPA).

---

### 6.2 Estimasi Reduksi Emisi Jejak Karbon
- Daur ulang kardus dan botol plastik secara ilmiah menghemat energi produksi industri baru.
- Sistem mengonversi total tonase sampah terkelola menjadi perkiraan angka penurunan emisi gas rumah kaca ($CO_2$ ekuivalen) dan pohon yang berhasil diselamatkan dari daur ulang kertas/kardus warga RT 15.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-009]**
> - **Halaman / Tampilan:** Halaman Publik Profil & Dampak Lingkungan Bank Sampah (`/bank-sampah`)
> - **Elemen yang Ditampilkan:** Laman informasi publik yang menyajikan grafik total sampah terpilah (kg), pohon terselamatkan, reduksi emisi karbon, serta tabel katalog harga beli sampah terkini.
> - **Fokus / Aksi Pengguna:** Sosialisasi capaian ekologis Kampung Digital RT 15 kepada masyarakat.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-009-profil-dampak-lingkungan-publik.png`

---

## 7. Ekspor Laporan Transaksi & Tabungan (PDF / Excel)

Untuk keperluan rapat pertanggungjawaban program bank sampah kepada Ketua RT atau pihak Dinas Lingkungan Hidup Kota Jambi:

### 7.1 Ekspor Rekapitulasi Penimbangan ke Lembar Kerja Excel
1. Buka menu **Kampung Digital > Setoran Bank Sampah** (`/admin/waste-deposits`).
2. *(Opsional)* Terapkan filter komoditas tertentu jika ingin laporan spesifik (misal: hanya rekap *Minyak Jelantah*).
3. Klik tombol **"Ekspor Data"** di sudut kanan atas header.
4. Pilih **Ekspor ke Excel (XLSX)**.
5. Lembar kerja spreadsheet akan terunduh lengkap dengan kolom tanggal, nama nasabah, jenis komoditas, bobot, harga satuan, dan nilai rupiah transaksi.

---

### 7.2 Ekspor Laporan Rekap Buku Tabungan Nasabah ke PDF Resmi
1. Klik tombol **"Ekspor Data"**, lalu pilih **Ekspor ke PDF**.
2. Dokumen laporan rapi berukuran A4 akan tercipta lengkap dengan:
   - Kop Resmi Bank Sampah Pinang Masak Lestari RT 15.
   - Tabel Rekapitulasi Penimbangan Seluruh Nasabah Warga.
   - Subtotal Total Berat Sampah (kg) dan Total Rupiah Tabungan.
   - Kolom tanda tangan Koordinator Bank Sampah dan Ketua RT 15.

> 📸 **[SLOT TANGKAPAN LAYAR: SS-BS-010]**
> - **Halaman / Tampilan:** Menu Dropdown Ekspor Data Setoran Bank Sampah (PDF & Excel)
> - **Elemen yang Ditampilkan:** Tombol dropdown "Ekspor Data" dengan pilihan unduhan berkas PDF resmi dan berkas spreadsheet Excel pada header Setoran Bank Sampah.
> - **Fokus / Aksi Pengguna:** Pengunduhan laporan operasional bank sampah.
> - **Rekomendasi Berkas Gambar:** `docs/panduan/assets/screenshots/ss-bs-010-dropdown-ekspor-bank-sampah.png`

---

## 8. Daftar Periksa Slot Tangkapan Layar (Checklist)

Gunakan tabel daftar periksa berikut saat Anda mengambil dan memasukkan foto tangkapan layar ke dalam dokumen panduan Bank Sampah ini:

| No | Kode Slot Tangkapan Layar | Halaman Antarmuka Terkait | Rekomendasi Nama Berkas Gambar | Status Gambar |
|:---:|---|---|---|:---:|
| 1 | `SS-BS-001` | Halaman Masuk Akun Koordinator Bank Sampah | `assets/screenshots/ss-bs-001-login-bank-sampah.png` | [ ] Belum Dipasang |
| 2 | `SS-BS-002` | Dasbor Pengurus Bank Sampah Lestari | `assets/screenshots/ss-bs-002-dashboard-bank-sampah.png` | [ ] Belum Dipasang |
| 3 | `SS-BS-003` | Formulir Tambah Master Jenis Sampah Baru | `assets/screenshots/ss-bs-003-form-tambah-jenis-sampah.png` | [ ] Belum Dipasang |
| 4 | `SS-BS-004` | Tabel Master Jenis Sampah & Daftar Harga | `assets/screenshots/ss-bs-004-tabel-master-harga-sampah.png` | [ ] Belum Dipasang |
| 5 | `SS-BS-005` | Modal Input Penimbangan Sampah Warga | `assets/screenshots/ss-bs-005-modal-catat-penimbangan-sampah.png` | [ ] Belum Dipasang |
| 6 | `SS-BS-006` | Tabel Riwayat Setoran Penimbangan Sampah | `assets/screenshots/ss-bs-006-tabel-riwayat-setoran-sampah.png` | [ ] Belum Dipasang |
| 7 | `SS-BS-007` | Modal Rincian Struk / Nota Penimbangan Digital | `assets/screenshots/ss-bs-007-nota-digital-setoran-sampah.png` | [ ] Belum Dipasang |
| 8 | `SS-BS-008` | Rincian Saldo & Mutasi Tabungan Warga | `assets/screenshots/ss-bs-008-mutasi-saldo-tabungan-warga.png` | [ ] Belum Dipasang |
| 9 | `SS-BS-009` | Laman Publik Profil & Dampak Lingkungan | `assets/screenshots/ss-bs-009-profil-dampak-lingkungan-publik.png` | [ ] Belum Dipasang |
| 10 | `SS-BS-010` | Menu Dropdown Ekspor Rekap (PDF & Excel) | `assets/screenshots/ss-bs-010-dropdown-ekspor-bank-sampah.png` | [ ] Belum Dipasang |

---
*Dokumen ini merupakan bagian dari Buku Panduan Resmi Sistem Informasi Manajemen RT 15 (SIM-LiBas).*
