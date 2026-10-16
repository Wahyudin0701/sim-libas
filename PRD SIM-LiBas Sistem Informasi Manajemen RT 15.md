# PRD SIM-LiBas: Sistem Informasi Manajemen RT 15

**Kampung Digital Pinang Masak Lestari, RT 15 Pematang Sulur, Kota Jambi** Versi 1.1 | Oktober 2026 | Status: Draft untuk ditinjau pengurus RT | Stack: Laravel (disetujui)

## 1. Ringkasan Produk

**SIM-LiBas** adalah portal web terpadu untuk pengelolaan informasi, pelayanan, dan kegiatan warga RT 15 Pematang Sulur. Satu portal menggabungkan layanan administrasi (surat pengantar, data warga), keuangan RT (iuran dan kas), komunikasi (pengumuman, pengaduan), keamanan lingkungan (ronda, lapor tamu), serta program Kampung Digital (Bank Sampah Pinang Masak Lestari dan UMKM warga).

**Tagline:** *Satu Portal, Banyak Layanan, Manfaat Nyata untuk Warga.*

**Moto kampung:** *Dari Warga, Oleh Warga, Untuk Kota Jambi yang Lebih Cerdas.*

### Visi Produk

Menjadi model Kampung Digital terbaik di Kota Jambi: pelayanan RT yang cepat, transparan, partisipatif, dan berkelanjutan, tanpa meninggalkan nilai kekeluargaan.

### Prinsip Produk

1. **Warga di tengah.** Teknologi hanya sarana, bukan tujuan akhir.
2. **Sederhana untuk semua usia.** Bapak/ibu lanjut usia harus bisa memakainya tanpa dibantu.
3. **Transparan.** Keuangan dan status layanan bisa dilihat warga.
4. **Aman.** Data pribadi dilindungi sesuai UU PDP.
5. **Inklusif.** Jalur non-digital tetap tersedia lewat pengurus.
6. **Berkelanjutan.** Mendukung ekonomi sirkular lewat bank sampah.

## 2. Filosofi SIM-LiBas dan Penerapannya pada Produk

Filosofi logo diterjemahkan menjadi keputusan produk dan desain yang konkret.

| Elemen Logo | Makna | Penerapan di Produk |
| --- | --- | --- |
| **Rumah & atap biru** | RT sebagai ruang bersama; kepercayaan, perlindungan, tata kelola bertanggung jawab | Modul inti administrasi dan data warga; keamanan dan audit log; warna biru sebagai warna utama |
| **Simbol Wi-Fi** | Keterhubungan warga, pengurus, informasi, dan layanan | Notifikasi, pengumuman, pengaduan dua arah, akses nyaman dari ponsel |
| **Tiga figur manusia (biru, emas, hijau)** | Kebersamaan, gotong royong, partisipasi aktif, inklusivitas | Fitur partisipasi (kegiatan, agenda, pengaduan), tiga kelompok peran utama, bahasa dan UI ramah semua usia |
| **Daun hijau** | Kelestarian, ekonomi sirkular, identitas Pinang Masak Lestari | Modul Bank Sampah: setoran, saldo, poin, dan laporan dampak lingkungan |
| **Simpul dan lingkaran emas** | Integrasi dan inovasi; ikon dokumen, warga, dan grafik pada orbit | Satu akun untuk semua layanan; dashboard terintegrasi (surat, warga, laporan, UMKM) |
| **Pita biru, sian, hijau** | Sinergi teknologi, masyarakat, lingkungan | Pola visual gelombang pada header dan hero; keseimbangan kemajuan, kesejahteraan, dan kelestarian dalam metrik dashboard |

### Filosofi Warna

- **Biru tua dan biru cerah:** kepercayaan, profesionalisme, keamanan informasi, kemajuan teknologi.
- **Hijau:** kelestarian, pertumbuhan, produktivitas, keberlanjutan.
- **Emas:** prestasi, optimisme, nilai ekonomi, semangat unggul.
- **Putih:** keterbukaan, kejelasan informasi, integritas, pelayanan yang bersih.

## 3. Latar Belakang dan Masalah

Pelayanan RT 15 saat ini masih banyak berjalan manual. Masalah utama yang ingin diselesaikan:

1. Pengurusan surat pengantar memakan waktu dan penomoran dicatat manual.
2. Data warga tersebar di buku dan catatan pribadi.
3. Pencatatan iuran dan kas kurang transparan bagi warga.
4. Informasi di grup pesan sering tenggelam dan tidak merata.
5. Pengaduan warga tidak punya status dan riwayat penanganan.
6. Pelaporan tamu dan pendatang belum tertib.
7. Potensi bank sampah dan UMKM warga belum terkelola dan terdata dalam satu sistem.

## 4. Tujuan dan Indikator Keberhasilan

| Tujuan | Indikator (KPI) | Target (usulan) |
| --- | --- | --- |
| Mempercepat layanan surat | Waktu dari pengajuan sampai surat terbit | Di bawah 1 hari kerja (target jangka panjang di bawah 1 jam jika pengurus aktif) |
| Data warga terpusat | Persentase KK terdaftar dan terverifikasi | 80% dalam 3 bulan, 95% dalam 6 bulan |
| Transparansi keuangan | Persentase iuran tercatat di sistem; laporan kas terbit tiap bulan | 100% tercatat; laporan tiap bulan |
| Partisipasi warga | Warga aktif bulanan (MAU) | 60% KK |
| Respons pengaduan | Pengaduan ditanggapi dalam 2 hari kerja | 90% |
| Dampak lingkungan | Jumlah KK penyetor bank sampah dan total kg sampah | Dilaporkan tiap bulan |
| Adopsi pengurus | Surat yang diproses lewat sistem | 90% |

## 5. Pengguna dan Peran

| Peran | Deskripsi | Hak Akses Utama |
| --- | --- | --- |
| **Super Admin** | Pengelola teknis sistem | Konfigurasi, backup, manajemen pengguna, audit log |
| **Ketua RT** | Pemimpin RT | Persetujuan surat, melihat seluruh data dan laporan, kelola pengurus |
| **Sekretaris** | Administrasi | Kelola data warga, surat, pengumuman, agenda |
| **Bendahara** | Keuangan | Kelola iuran, kas, laporan keuangan |
| **Koordinator Bank Sampah** | Pengelola bank sampah | Catat setoran, kelola harga sampah, saldo, penarikan |
| **Koordinator Keamanan** | Ronda dan keamanan | Jadwal ronda, laporan tamu, kejadian |
| **Warga (Kepala Keluarga / anggota)** | Pengguna layanan | Data keluarga sendiri, ajukan surat, lihat tagihan dan kas, pengaduan, bank sampah sendiri, profil UMKM sendiri |
| **RW / Kelurahan (opsional)** | Pemantau | Lihat ringkasan dan laporan, tanpa data pribadi detail |
| **Publik / Tamu** | Pengunjung | Halaman profil kampung, pengumuman publik, katalog UMKM, verifikasi surat via QR |

Aturan utama: warga hanya dapat melihat data keluarganya sendiri. Akses pengurus dibatasi sesuai tugas (least privilege).

## 6. Ruang Lingkup dan Fase Rilis

### Fase 1: MVP (inti layanan RT)

Autentikasi dan peran, data warga dan KK, surat pengantar online, iuran dan kas, pengumuman dan agenda, dashboard dasar.

### Fase 2: Partisipasi dan keamanan lingkungan

Pengaduan warga, jadwal ronda, lapor tamu dan pendatang, notifikasi WhatsApp.

### Fase 3: Ekosistem Kampung Digital

Bank Sampah Pinang Masak Lestari, katalog UMKM warga, dashboard dampak (sosial, ekonomi, lingkungan), pelaporan untuk RW/kelurahan.

### Di Luar Lingkup

- Penerbitan dokumen kependudukan resmi (KTP, KK, akta). Sistem hanya menerbitkan surat pengantar RT.
- Tanda tangan elektronik tersertifikasi (memakai tanda tangan terpindai dan QR verifikasi).
- Aplikasi seluler native (web responsif dan opsi PWA).
- Integrasi langsung dengan Dukcapil atau sistem pemerintah.
- Pembayaran online penuh (Fase 1 hanya pencatatan; payment gateway dipertimbangkan kemudian).

## 7. Kebutuhan Fungsional

Prioritas: **P0** wajib MVP, **P1** penting, **P2** sebaiknya ada.

### 7.1 Autentikasi dan Manajemen Pengguna

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-AUTH-01 | Warga mendaftar dengan nomor HP atau email, lalu akun diverifikasi pengurus | P0 |
| FR-AUTH-02 | Login dengan kata sandi; OTP lewat WhatsApp/email untuk pemulihan | P0 |
| FR-AUTH-03 | Akses berbasis peran (RBAC) dan kepemilikan data | P0 |
| FR-AUTH-04 | Pengurus bisa membuatkan akun untuk warga yang tidak memakai gawai | P1 |
| FR-AUTH-05 | Riwayat login dan penguncian akun setelah percobaan gagal berulang | P1 |

### 7.2 Data Warga dan Kartu Keluarga

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-WRG-01 | CRUD data KK dan anggota (NIK, nama, hubungan keluarga, status hunian: tetap, kontrak, kos) | P0 |
| FR-WRG-02 | Warga dapat memperbarui data keluarga sendiri; perubahan sensitif butuh persetujuan pengurus | P0 |
| FR-WRG-03 | Pencatatan mutasi: lahir, pindah, datang, meninggal, dengan riwayat | P0 |
| FR-WRG-04 | Pencarian dan filter data (nama, alamat, status) | P0 |
| FR-WRG-05 | Impor data awal dari Excel/CSV dengan validasi | P1 |
| FR-WRG-06 | Ekspor data terbatas (masking NIK) untuk laporan | P1 |

### 7.3 Surat Pengantar Online

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-SRT-01 | Warga memilih jenis surat (domisili, pengantar KTP/KK, SKCK, keterangan tidak mampu, usaha, nikah, lainnya) dan mengisi form | P0 |
| FR-SRT-02 | Unggah lampiran pendukung (foto/scan) | P0 |
| FR-SRT-03 | Pengurus memverifikasi, menyetujui, atau menolak disertai alasan | P0 |
| FR-SRT-04 | Nomor surat otomatis sesuai format RT dan tidak bisa ganda | P0 |
| FR-SRT-05 | Surat dihasilkan sebagai PDF dari template dengan TTD terpindai dan kode QR verifikasi | P0 |
| FR-SRT-06 | Halaman publik verifikasi keaslian surat lewat QR | P1 |
| FR-SRT-07 | Pelacakan status: Diajukan, Diperiksa, Disetujui, Ditolak, Selesai | P0 |
| FR-SRT-08 | Notifikasi ke warga saat status berubah | P1 |
| FR-SRT-09 | Template surat dapat dikelola pengurus tanpa programmer | P1 |
| FR-SRT-10 | Arsip dan buku agenda surat dapat diekspor | P1 |

### 7.4 Iuran dan Kas RT

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-KAS-01 | Kelola jenis iuran (kebersihan, keamanan, sosial, dll.) dan nominal | P0 |
| FR-KAS-02 | Tagihan periodik otomatis per KK | P0 |
| FR-KAS-03 | Pencatatan pembayaran (tunai/transfer) dengan bukti | P0 |
| FR-KAS-04 | Pencatatan pemasukan dan pengeluaran kas dengan kategori dan bukti | P0 |
| FR-KAS-05 | Warga melihat tagihan, riwayat bayar, dan laporan kas ringkas | P0 |
| FR-KAS-06 | Laporan bulanan dan tahunan yang dapat diunduh | P1 |
| FR-KAS-07 | Pengingat tagihan otomatis | P1 |
| FR-KAS-08 | Pembayaran QRIS/transfer terintegrasi | P2 |

### 7.5 Pengumuman dan Agenda

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-INF-01 | Pengurus membuat pengumuman (judul, isi, lampiran, target: semua/kelompok) | P0 |
| FR-INF-02 | Kalender agenda kegiatan (kerja bakti, arisan, rapat, pengajian, 17 Agustus) | P0 |
| FR-INF-03 | Notifikasi dalam aplikasi; WhatsApp/email sebagai tambahan | P1 |
| FR-INF-04 | Konfirmasi hadir (RSVP) pada kegiatan | P2 |
| FR-INF-05 | Dokumentasi kegiatan (galeri foto) | P2 |

### 7.6 Pengaduan Warga

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-ADU-01 | Warga mengirim laporan dengan kategori, deskripsi, foto, dan lokasi | P1 |
| FR-ADU-02 | Status penanganan: Diterima, Diproses, Selesai, dengan catatan pengurus | P1 |
| FR-ADU-03 | Pengaduan dapat dibuat anonim untuk pengurus tertentu dan tetap tercatat | P2 |
| FR-ADU-04 | Statistik pengaduan per kategori dan waktu penyelesaian | P2 |

### 7.7 Keamanan Lingkungan

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-AMN-01 | Penyusunan jadwal ronda dan penukaran jadwal antar warga | P1 |
| FR-AMN-02 | Pengingat jadwal ronda otomatis | P1 |
| FR-AMN-03 | Warga melaporkan tamu menginap atau penghuni baru | P1 |
| FR-AMN-04 | Pencatatan kejadian/laporan keamanan | P2 |
| FR-AMN-05 | Tombol kontak darurat (Ketua RT, Bhabinkamtibmas, puskesmas) | P2 |

### 7.8 Bank Sampah Pinang Masak Lestari

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-BSP-01 | Master jenis sampah dan harga per kg yang dapat diperbarui | P1 |
| FR-BSP-02 | Pencatatan setoran per warga (jenis, berat, nilai) oleh petugas | P1 |
| FR-BSP-03 | Saldo dan riwayat setoran terlihat oleh warga | P1 |
| FR-BSP-04 | Pencairan saldo atau penukaran untuk iuran/kebutuhan lain | P2 |
| FR-BSP-05 | Jadwal penjemputan atau jadwal setor | P2 |
| FR-BSP-06 | Laporan dampak: total kg, jumlah penyetor, ekuivalen manfaat ekonomi | P1 |
| FR-BSP-07 | Papan peringkat atau lencana partisipasi (opsional, tanpa menampilkan nilai uang warga lain) | P2 |

### 7.9 UMKM Warga

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-UMK-01 | Warga mendaftarkan profil usaha (nama, produk, foto, kontak) | P2 |
| FR-UMK-02 | Katalog publik UMKM RT 15 dengan tombol hubungi lewat WhatsApp | P2 |
| FR-UMK-03 | Moderasi profil oleh pengurus | P2 |
| FR-UMK-04 | Surat keterangan usaha terhubung dengan modul surat | P2 |

### 7.10 Dashboard dan Laporan

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-DSH-01 | Dashboard pengurus: jumlah KK/warga, surat berjalan, tagihan belum lunas, saldo kas, pengaduan terbuka | P0 |
| FR-DSH-02 | Dashboard warga: tagihan saya, status surat saya, pengumuman terbaru, saldo bank sampah | P0 |
| FR-DSH-03 | Dashboard dampak Kampung Digital: sosial, ekonomi, lingkungan | P2 |
| FR-DSH-04 | Laporan terjadwal untuk RW/kelurahan | P2 |

### 7.11 Halaman Publik

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| FR-PUB-01 | Beranda: profil Kampung Digital Pinang Masak Lestari, filosofi logo, tagline | P1 |
| FR-PUB-02 | Pengumuman publik dan agenda | P1 |
| FR-PUB-03 | Verifikasi surat via QR | P1 |
| FR-PUB-04 | Katalog UMKM dan statistik bank sampah publik (teragregasi) | P2 |

## 8. Alur Pengguna Utama

### 8.1 Pengajuan Surat Pengantar

1. Warga login dan memilih *Ajukan Surat*.
2. Memilih jenis surat; data diri terisi otomatis dari profil keluarga.
3. Melengkapi keperluan dan lampiran, lalu mengirim.
4. Sekretaris memeriksa kelengkapan; jika kurang, meminta perbaikan dengan catatan.
5. Ketua RT menyetujui; sistem memberi nomor surat dan membuat PDF dengan QR.
6. Warga mendapat notifikasi, mengunduh PDF, atau mengambil cetakan.
7. Instansi tujuan dapat memindai QR untuk verifikasi.

### 8.2 Iuran Bulanan

1. Sistem membuat tagihan per KK tiap awal periode.
2. Warga melihat tagihan dan membayar (tunai ke bendahara atau transfer + unggah bukti).
3. Bendahara memvalidasi; status berubah Lunas.
4. Laporan kas bulanan terbit otomatis dan dapat dilihat warga.

### 8.3 Setoran Bank Sampah

1. Warga datang ke titik bank sampah dan menyerahkan sampah terpilah.
2. Petugas menimbang dan mencatat jenis serta berat.
3. Saldo warga bertambah otomatis dan tampil di akunnya.
4. Laporan dampak bulanan diperbarui.

## 9. Kebutuhan Non-Fungsional

| Kategori | Kebutuhan |
| --- | --- |
| **Kinerja** | Halaman utama dimuat di bawah 3 detik pada jaringan seluler 4G; pembuatan PDF surat di bawah 5 detik |
| **Ketersediaan** | Target 99% per bulan; pemeliharaan terjadwal diumumkan |
| **Keamanan** | HTTPS, hashing kata sandi (Argon2/bcrypt), enkripsi data sensitif (NIK), proteksi CSRF/XSS/SQL injection, pembatasan percobaan login, audit log |
| **Privasi** | Sesuai UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi: persetujuan, minimisasi data, hak akses/koreksi/hapus, kebijakan privasi |
| **Kegunaan** | Mobile-first; ukuran huruf dasar minimal 16 px; target sentuh minimal 44 px; bahasa Indonesia sederhana |
| **Aksesibilitas** | Kontras sesuai WCAG 2.1 AA; navigasi keyboard; label formulir jelas; opsi ukuran huruf besar |
| **Kompatibilitas** | Peramban modern (Chrome, Safari, Edge, Firefox) dan ponsel Android entry-level |
| **Keandalan data** | Backup harian, uji pemulihan berkala, retensi sesuai kebijakan |
| **Skalabilitas** | Dirancang multi-RT di masa depan (struktur data mendukung banyak RT/RW) |
| **Pemeliharaan** | Kode terdokumentasi, migrasi basis data berversi, log terpusat |

## 10. Model Data Tingkat Tinggi

| Entitas | Atribut Kunci | Relasi |
| --- | --- | --- |
| users | id, nama, telepon/email, password\_hash, status, role | punya banyak roles; terhubung ke warga |
| roles / permissions | nama, izin | RBAC |
| households (KK) | no\_kk, alamat, status\_hunian, rt\_id | punya banyak residents |
| residents | NIK (terenkripsi), nama, tgl\_lahir, hubungan, status | milik household |
| mutations | resident\_id, jenis, tanggal, catatan | milik resident |
| letter\_types | nama, template, format\_nomor | punya banyak letters |
| letters | nomor, jenis, pemohon, status, pdf\_path, qr\_token, approved\_by | milik letter\_type, resident |
| fee\_types | nama, nominal, periode | punya banyak invoices |
| invoices | household\_id, fee\_type\_id, periode, jumlah, status | punya banyak payments |
| payments | invoice\_id, jumlah, metode, bukti, divalidasi\_oleh | milik invoice |
| cash\_transactions | jenis (masuk/keluar), kategori, jumlah, bukti, tanggal | laporan kas |
| announcements / events | judul, isi, target, tanggal | notifikasi |
| complaints | kategori, deskripsi, foto, status, penanggung\_jawab | riwayat tanggapan |
| patrol\_schedules | tanggal, regu, anggota | milik resident |
| guest\_reports | nama tamu, tujuan, tgl\_datang, tgl\_pulang, pelapor | milik household |
| waste\_types | nama, harga\_per\_kg | punya banyak waste\_deposits |
| waste\_deposits | household\_id, waste\_type\_id, berat\_kg, nilai, petugas | menambah saldo |
| waste\_wallet\_tx | household\_id, jenis, jumlah, saldo\_akhir | saldo bank sampah |
| msme\_profiles | nama\_usaha, produk, foto, kontak, status\_moderasi | milik household |
| audit\_logs | user\_id, aksi, objek, waktu, IP | seluruh modul |
| notifications | user\_id, kanal, isi, status\_baca | seluruh modul |

## 11. Arsitektur dan Teknologi (Keputusan)

| Lapisan | Keputusan | Catatan |
| --- | --- | --- |
| Backend | Laravel (rilis stabil terbaru), PHP 8.3 atau lebih baru | Monolit modular: satu codebase, satu modul per domain (Warga, Surat, Keuangan, Bank Sampah, dst.) |
| Panel pengurus | Filament | Resource CRUD, tabel, filter, form, dan widget dashboard; tema disesuaikan dengan palet SIM-LiBas |
| Antarmuka warga dan publik | Blade + Livewire + Alpine.js + Tailwind CSS | Mobile-first dan dirender di server agar ringan di ponsel entry-level; mengikuti design tokens Lampiran A |
| Basis data | MySQL 8 atau PostgreSQL 16 (dipilih saat Fase 0) | Relasional; NIK disimpan di kolom terenkripsi plus kolom hash untuk pencarian |
| Autentikasi dan peran | Laravel auth + spatie/laravel-permission | RBAC sesuai bagian 5; akses ke data keluarga sendiri diatur lewat policy |
| Audit log | spatie/laravel-activitylog | Mencatat akses dan perubahan data sensitif |
| Penyimpanan berkas | Disk privat Laravel (lokal terenkripsi atau S3-kompatibel) | Berkas tidak publik; diakses lewat URL bertanda tangan sementara |
| PDF dan QR | barryvdh/laravel-dompdf + simple-qrcode | Template surat berbasis HTML; QR berisi token acak, bukan NIK |
| Impor dan ekspor | Laravel Excel | Impor data awal warga, ekspor laporan |
| Antrian dan penjadwalan | Laravel Queue (driver database, Redis bila perlu) + Scheduler | Tagihan periodik, pengingat, notifikasi, pembuatan PDF |
| Notifikasi | Dalam aplikasi + email; WhatsApp lewat provider resmi pada Fase 2 | WhatsApp sebagai pelengkap, hindari gateway tidak resmi |
| Grafik | Chart.js atau ApexCharts (widget Filament) | Dashboard pengurus, warga, dan dampak |
| Akses ponsel | PWA | Dapat dipasang di layar utama tanpa aplikasi native |
| Pengujian | Pest + Laravel Pint | Tes fitur untuk alur surat, iuran, dan hak akses |
| Hosting | VPS di Indonesia (awal 2 vCPU, 4 GB RAM), Nginx + PHP-FPM | Backup harian dengan spatie/laravel-backup ke lokasi terpisah |
| CI/CD | GitHub Actions | Tes otomatis dan deploy |
| Pemantauan | Sentry + uptime monitor | Deteksi galat dan downtime lebih awal |

**Keputusan:** Laravel disetujui sebagai kerangka utama pada Oktober 2026. Pendekatan monolit modular dipilih karena skala RT kecil, sebagian besar kebutuhan berupa aplikasi administrasi, dan sistem harus mudah dirawat serta dialihkan saat pengurus berganti. Microservices, Kubernetes, dan aplikasi native tidak dipakai pada tahap ini. Pilihan basis data dan penyedia WhatsApp dikonfirmasi pada Fase 0.

## 12. Keamanan dan Privasi Data

- NIK dan KK dikategorikan data pribadi: dienkripsi saat disimpan, dimasking saat ditampilkan (contoh 3304\*\*\*\*\*\*\*\*0001).
- Persetujuan eksplisit saat pendaftaran dan kebijakan privasi yang jelas dalam bahasa sederhana.
- Audit log untuk setiap akses data sensitif oleh pengurus.
- QR verifikasi hanya menampilkan data minimal (nomor, jenis, tanggal, status sah), tanpa NIK.
- Pengurus yang berganti: cabut akses dan serah terima melalui fitur manajemen peran.
- Prosedur insiden: deteksi, pembatasan, pemberitahuan kepada warga terdampak, perbaikan.
- Warga dapat meminta koreksi atau penghapusan data sesuai ketentuan yang berlaku.

## 13. Roadmap Rilis (Estimasi)

| Rilis | Cakupan | Perkiraan Durasi |
| --- | --- | --- |
| Fase 0 | Pengumpulan kebutuhan, desain UI, data awal | 2 minggu |
| Fase 1 (MVP) | Autentikasi, data warga, surat, iuran dan kas, pengumuman, dashboard | 6 sampai 8 minggu |
| Fase 2 | Pengaduan, ronda, lapor tamu, notifikasi WhatsApp | 3 sampai 4 minggu |
| Fase 3 | Bank Sampah, UMKM, dashboard dampak, laporan RW/kelurahan | 4 sampai 6 minggu |
| Stabilisasi | Pelatihan, perbaikan, pendampingan | Berkelanjutan |

Durasi adalah perkiraan awal dan perlu dikonfirmasi setelah estimasi tim.

## 14. Risiko dan Asumsi

| Risiko / Asumsi | Dampak | Mitigasi |
| --- | --- | --- |
| Warga lanjut usia kesulitan memakai sistem | Adopsi rendah | UI sangat sederhana, pelatihan, pendamping (relawan digital), jalur manual tetap |
| Data awal tidak lengkap | Peluncuran tertunda | Template Excel, pendataan bertahap per blok |
| Pengurus berganti | Pengelolaan terputus | Dokumentasi, manajemen peran, pelatihan berkala |
| Kebocoran data pribadi | Kerugian warga dan reputasi | Enkripsi, RBAC, audit log, backup, kebijakan privasi |
| Ketergantungan satu pengembang | Sistem sulit dipelihara | Kode di repositori, dokumentasi, teknologi umum |
| Biaya operasional (hosting, WhatsApp) | Anggaran RT terbatas | Pilih paket hemat, notifikasi WhatsApp dibatasi ke yang penting |
| Asumsi: mayoritas warga memiliki ponsel dan koneksi data |  | Verifikasi lewat survei singkat saat Fase 0 |

## 15. Pertanyaan Terbuka

1. Jumlah KK dan warga RT 15 saat ini (untuk perencanaan kapasitas dan target adopsi)?
2. Format nomor surat dan template surat resmi RT 15?
3. Apakah pembayaran iuran perlu terhubung ke QRIS pada tahap awal?
4. Siapa pemilik data dan pengelola sistem setelah serah terima?
5. Bagaimana mekanisme harga dan pencairan Bank Sampah yang berlaku?
6. Apakah katalog UMKM akan dibuka untuk publik atau terbatas warga?
7. Siapa penanggung jawab biaya hosting dan domain?

## Lampiran A. Design Tokens Berdasarkan Logo SIM-LiBas

Nilai heksadesimal di bawah adalah perkiraan dari tampilan logo. Untuk presisi, ambil sampel warna langsung dari berkas logo asli (misalnya dengan eyedropper) lalu perbarui token.

### Palet Warna

| Token | Hex | Asal dan Fungsi |
| --- | --- | --- |
| `--color-primary-900` | `#0B2F6B` | Biru tua huruf SIM dan atap; teks judul, header gelap |
| `--color-primary-700` | `#0B3A85` | Biru atap; warna utama tombol, sidebar, tautan |
| `--color-primary-500` | `#1C7ED6` | Biru cerah; hover, ikon aktif, grafik |
| `--color-accent-cyan` | `#19A7E8` | Sian pita; informasi, konektivitas, highlight sekunder |
| `--color-green-700` | `#1B7A3A` | Hijau daun tua; status sukses, modul Bank Sampah |
| `--color-green-500` | `#6DB33F` | Hijau daun cerah; aksen pertumbuhan, grafik |
| `--color-gold-500` | `#F2A900` | Emas; penanda penting, badge, CTA sekunder |
| `--color-gold-700` | `#C98A00` | Emas tua; border, ikon emas pada latar terang |
| `--color-bg` | `#F5F8FC` | Latar halaman (putih kebiruan lembut) |
| `--color-surface` | `#FFFFFF` | Kartu, formulir, modal |
| `--color-border` | `#DDE5F0` | Garis tipis dan pemisah |
| `--color-text` | `#0F1F3D` | Teks utama |
| `--color-text-muted` | `#5B6B82` | Teks pendukung |
| `--color-danger` | `#D64545` | Galat, tolak, hapus |

### Aturan Penggunaan Warna

- Komposisi: sekitar 60% putih/latar terang, 25% biru, 10% hijau, 5% emas.
- Teks putih hanya di atas biru tua (`primary-700/900`) dan hijau tua (`green-700`). **Teks di atas emas harus gelap** (`#0B2F6B`) karena putih di atas emas tidak memenuhi kontras.
- Gradien khas: `linear-gradient(135deg, #0B3A85 0%, #1C7ED6 55%, #19A7E8 100%)` untuk hero dan header; gradien hijau `#1B7A3A` ke `#6DB33F` untuk modul lingkungan.
- Pola gelombang (pita biru, sian, hijau) dipakai sebagai pemisah bagian dan latar hero, dengan kepadatan rendah agar tidak mengganggu keterbacaan.

### Tipografi, Bentuk, dan Komponen

- Font: **Poppins** atau **Plus Jakarta Sans** untuk judul (bulat dan ramah seperti huruf pada logo), **Inter** untuk isi teks. Cadangan: system-ui, sans-serif.
- Ukuran dasar 16 px, tinggi baris 1.6, judul tebal (700).
- Sudut membulat: kartu 16 px, tombol dan input 12 px, chip penuh (pill).
- Bayangan lembut berwarna biru (`0 4px 16px rgba(11,58,133,0.08)`).
- Ikon bergaya garis bulat (rounded stroke), selaras dengan ikon dokumen, grup warga, dan grafik pada orbit emas logo.
- Tiga figur logo (biru, emas, hijau) dapat dipakai sebagai ilustrasi kosong (empty state) dan avatar default.

### Pemetaan Warna ke Modul

| Modul | Warna Dominan |
| --- | --- |
| Surat Pengantar, Data Warga | Biru |
| Iuran dan Kas | Biru dengan aksen emas |
| Pengumuman dan Agenda | Sian |
| Pengaduan | Sian dengan status berwarna |
| Keamanan dan Ronda | Biru tua |
| Bank Sampah | Hijau |
| UMKM | Emas dan hijau |
| Dashboard Dampak | Kombinasi biru, hijau, emas |

## Lampiran B. Prompt Instruksi untuk Membangun Antarmuka SIM-LiBas

Salin seluruh blok di bawah ini ke alat AI pembuat antarmuka (misalnya Claude, Claude Code, atau Cursor; v0 dan Lovable menghasilkan React sehingga hasilnya perlu diterjemahkan ke Blade dan Livewire). Ganti bagian dalam tanda \[ \] sesuai kebutuhan. Disarankan membangun per tahap: mulai dari sistem desain dan halaman login, lalu halaman lain satu per satu.

### B.1 Master Prompt

```
PERAN
Kamu adalah desainer UI/UX dan frontend engineer senior. Bangun antarmuka web untuk SIM-LiBas (Sistem Informasi Manajemen RT 15), bagian dari Kampung Digital Pinang Masak Lestari, RT 15 Pematang Sulur, Kota Jambi. Seluruh teks antarmuka berbahasa Indonesia yang sederhana dan ramah.

TAGLINE
- Satu Portal, Banyak Layanan, Manfaat Nyata untuk Warga.
- Dari Warga, Oleh Warga, Untuk Kota Jambi yang Lebih Cerdas.

IDENTITAS VISUAL (berdasarkan logo)
Logo: rumah berlogo atap biru dengan simbol Wi-Fi, tiga figur manusia (biru, emas, hijau), daun hijau besar, lingkaran orbit emas dengan ikon dokumen, grup warga, dan grafik, serta pita gelombang biru-sian-hijau. Tulisan SIM berwarna biru tua dan LiBas berwarna hijau, titik emas pada huruf i.

PALET WARNA (wajib memakai CSS variables / Tailwind theme extend)
--color-primary-900: #0B2F6B
--color-primary-700: #0B3A85
--color-primary-500: #1C7ED6
--color-accent-cyan: #19A7E8
--color-green-700: #1B7A3A
--color-green-500: #6DB33F
--color-gold-500: #F2A900
--color-gold-700: #C98A00
--color-bg: #F5F8FC
--color-surface: #FFFFFF
--color-border: #DDE5F0
--color-text: #0F1F3D
--color-text-muted: #5B6B82
--color-danger: #D64545

Aturan warna:
- Komposisi kira-kira 60% putih/latar terang, 25% biru, 10% hijau, 5% emas.
- Biru = tindakan utama, navigasi, kepercayaan. Hijau = sukses dan modul lingkungan. Emas = penanda penting, badge, sorotan. Sian = informasi dan konektivitas.
- Teks putih hanya di atas biru tua atau hijau tua. Di atas emas pakai teks biru tua #0B2F6B.
- Hero/header memakai gradien 135deg #0B3A85 ke #1C7ED6 ke #19A7E8 dengan pola gelombang halus bertema pita logo.
- Pastikan kontras minimal WCAG AA.

TIPOGRAFI DAN BENTUK
- Judul: Poppins atau Plus Jakarta Sans (tebal). Isi: Inter. Ukuran dasar 16px, line-height 1.6.
- Radius: kartu 16px, tombol dan input 12px, chip pill.
- Bayangan lembut: 0 4px 16px rgba(11,58,133,0.08).
- Ikon bergaya rounded-stroke (lucide-react).
- Empty state dan avatar default memakai ilustrasi tiga figur (biru, emas, hijau).

PRINSIP UX
- Mobile-first. Mayoritas pengguna memakai ponsel Android dan banyak yang berusia lanjut.
- Tombol besar (tinggi minimal 48px), teks jelas, satu tujuan utama per layar.
- Navigasi bawah (bottom nav) pada mobile untuk warga: Beranda, Layanan, Iuran, Info, Akun. Sidebar kiri biru tua pada desktop untuk pengurus.
- Opsi ukuran huruf besar di pengaturan akun.
- Status selalu memakai teks + warna + ikon, jangan warna saja.
- Formulir pendek, bertahap (wizard), dengan pesan galat yang ramah dan spesifik.
- Loading skeleton, empty state informatif, dan konfirmasi sebelum aksi destruktif.
- Data sensitif (NIK) dimasking secara default.
- Dukungan mode terang sebagai default; mode gelap opsional dengan token yang sama.

STACK
Laravel (rilis stabil terbaru, PHP 8.3+). Aplikasi Pengurus dibangun sebagai Filament panel (resource, table, form, widget) dengan tema kustom sesuai palet di atas. Antarmuka Warga dan halaman publik memakai Blade + Livewire + Alpine.js + Tailwind CSS, ikon Heroicons atau Lucide, grafik Chart.js atau ApexCharts. Konfigurasikan palet sebagai Tailwind theme extend dan CSS variables, lalu hubungkan ke tema Filament. Data awal memakai seeder dan factory yang realistis berbahasa Indonesia (nama warga, alamat RT 15 Pematang Sulur, nominal rupiah format Rp 25.000).

PERAN PENGGUNA
Warga, Ketua RT, Sekretaris, Bendahara, Koordinator Bank Sampah, Koordinator Keamanan, Super Admin. Tampilkan menu dan dashboard sesuai peran.

HALAMAN YANG DIBANGUN

1) Sistem desain
- Token warna, tipografi, tombol (primary biru, secondary outline, aksen emas, success hijau, danger), input, select, datepicker, upload berkas, kartu, badge status, tabel, modal, toast, stepper, tabs, empty state, skeleton, breadcrumb.

2) Halaman publik
- Beranda: hero gradien biru dengan logo, tagline, tombol Masuk dan Daftar, ikon layanan (Surat, Iuran, Info, Bank Sampah, UMKM), statistik kampung (jumlah KK, kg sampah, UMKM), pengumuman terbaru, bagian Filosofi Logo singkat dengan tiga figur, footer.
- Verifikasi surat via QR: kartu hasil berstatus SAH berwarna hijau atau TIDAK DITEMUKAN merah, menampilkan nomor, jenis, tanggal, tanpa NIK.
- Katalog UMKM (kartu produk, tombol WhatsApp).

3) Autentikasi
- Login, daftar (wizard 3 langkah), lupa kata sandi dengan OTP, status menunggu verifikasi pengurus.

4) Aplikasi Warga
- Beranda: sapaan, kartu ringkas (tagihan bulan ini, status surat terakhir, saldo bank sampah), tombol layanan cepat, pengumuman, agenda terdekat.
- Surat: daftar jenis surat, wizard pengajuan (pilih jenis, data, lampiran, tinjau), pelacakan status dengan timeline, unduh PDF.
- Iuran: daftar tagihan, detail, unggah bukti bayar, riwayat, laporan kas RT ringkas (grafik pemasukan vs pengeluaran).
- Info: pengumuman dan kalender agenda, RSVP.
- Pengaduan: formulir (kategori, foto, lokasi), daftar dan status.
- Bank Sampah: saldo, riwayat setoran, grafik kg per bulan, harga sampah terkini.
- Keluarga saya: data KK dan anggota, ajukan perubahan data.
- UMKM saya, jadwal ronda saya, lapor tamu.
- Akun: profil, ukuran huruf, keamanan, kebijakan privasi.

5) Aplikasi Pengurus (desktop-first, tetap responsif)
- Dashboard: kartu KPI (KK, warga, surat berjalan, tagihan belum lunas, saldo kas, pengaduan terbuka), grafik tren, daftar tugas hari ini.
- Data Warga: tabel dengan pencarian, filter, impor Excel, detail KK, mutasi.
- Surat: antrian persetujuan (kanban atau tabel), pratinjau surat, setujui/tolak dengan alasan, kelola template, buku agenda surat.
- Keuangan: tagihan, validasi pembayaran, buku kas, kategori, laporan bulanan.
- Pengumuman dan agenda: editor, target penerima, jadwal tayang.
- Pengaduan: papan status dan penugasan.
- Keamanan: jadwal ronda (kalender), laporan tamu.
- Bank Sampah: input setoran (cepat, ramah layar sentuh), harga sampah, saldo warga, laporan.
- UMKM: moderasi profil.
- Pengaturan: pengguna dan peran, audit log, profil RT, template.

6) Dashboard Dampak Kampung Digital
- Tiga kolom tema: Sosial (biru), Ekonomi (emas), Lingkungan (hijau), dengan KPI dan grafik; pita gelombang sebagai pemisah.

KUALITAS
- Blade component dan komponen Livewire yang reusable, struktur folder mengikuti konvensi Laravel, aksesibel (label, fokus terlihat, aria), responsif 360px sampai 1440px.
- Jangan memakai gambar eksternal; gunakan SVG inline dan ilustrasi sederhana bergaya logo.
- Berikan hasil per tahap, mulai dari sistem desain + halaman Beranda publik + Login + Beranda Warga, lalu tanyakan sebelum lanjut.
```

### B.2 Prompt Lanjutan per Layar (Contoh)

Gunakan setelah master prompt diterima dan sistem desain sudah jadi.

**Wizard Pengajuan Surat**

```
Buat halaman Ajukan Surat untuk Warga sesuai sistem desain SIM-LiBas. Stepper 4 langkah: (1) Pilih jenis surat dengan kartu ikon besar, (2) Konfirmasi data diri terisi otomatis dan isi keperluan, (3) Unggah lampiran dengan pratinjau, (4) Tinjau dan kirim. Tombol utama biru tua di bawah (sticky pada mobile), tombol kembali outline. Tampilkan estimasi waktu proses dan checklist dokumen yang dibutuhkan. Setelah kirim, tampilkan layar sukses hijau dengan nomor pengajuan dan tombol Lacak Status.
```

**Dashboard Bendahara**

```
Buat dashboard Bendahara SIM-LiBas: kartu KPI (saldo kas, tagihan bulan ini, terkumpul, belum lunas) dengan aksen emas untuk saldo, grafik batang pemasukan vs pengeluaran 6 bulan (biru dan hijau), tabel pembayaran menunggu validasi dengan tombol Validasi/Tolak, serta tombol Catat Pengeluaran. Bahasa Indonesia, nominal format Rp.
```

**Input Setoran Bank Sampah**

```
Buat layar input setoran Bank Sampah untuk petugas di tablet atau ponsel: cari warga (nama atau no. KK), pilih jenis sampah dari chip hijau, input berat dengan keypad besar, tampilkan nilai otomatis (berat x harga), daftar item setoran, total, dan tombol Simpan Setoran hijau. Setelah tersimpan, tampilkan saldo terbaru warga dan animasi ringan daun tumbuh.
```

**Beranda Publik**

```
Buat beranda publik SIM-LiBas: hero gradien biru-sian dengan pola gelombang, logo di kiri atas, judul besar Satu Portal, Banyak Layanan, Manfaat Nyata untuk Warga, dua tombol (Masuk biru tua putih, Daftar emas teks biru tua). Di bawahnya grid 6 layanan dengan ikon, statistik kampung, pengumuman terbaru, bagian Filosofi Logo dengan tiga figur biru-emas-hijau, dan footer berisi alamat RT 15 Pematang Sulur, Kota Jambi.
```

### B.3 Daftar Periksa Hasil Antarmuka

- [ ] Warna sesuai token; tidak ada warna di luar palet kecuali status.
- [ ] Teks di atas emas berwarna biru tua.
- [ ] Semua teks dalam bahasa Indonesia sederhana.
- [ ] Tombol dan target sentuh minimal 48 px di mobile.
- [ ] Ukuran huruf dasar 16 px dan ada opsi huruf besar.
- [ ] NIK dimasking secara default.
- [ ] Status memakai teks, warna, dan ikon.
- [ ] Responsif pada 360 px, 768 px, dan 1440 px.
- [ ] Logo, tagline, dan filosofi tampil konsisten.
- [ ] Ada empty state, loading skeleton, dan pesan galat ramah.

---

**SIM-LiBas: Satu Portal, Banyak Layanan, Manfaat Nyata untuk Warga.** *Kampung Digital Pinang Masak Lestari: Dari Warga, Oleh Warga, Untuk Kota Jambi yang Lebih Cerdas.*
