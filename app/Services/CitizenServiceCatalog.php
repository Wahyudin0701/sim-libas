<?php

namespace App\Services;

class CitizenServiceCatalog
{
    /**
     * Sumber data tunggal (Single Source of Truth) untuk seluruh katalog Layanan Warga RT 15.
     *
     * @return array<string, array>
     */
    public static function all(): array
    {
        return [
            'surat-pengantar' => [
                'slug' => 'surat-pengantar',
                'nama' => 'Surat Pengantar Online',
                'short_name' => 'Surat Pengantar',
                'kategori' => 'Administrasi & Kependudukan',
                'badge_color' => 'blue',
                'icon_bg' => 'bg-blue-50 text-primary-700',
                'deskripsi' => 'Layanan pengajuan surat domisili, pengantar KTP/KK, SKCK, dan SKTM secara online melalui ponsel.',
                'keunggulan' => 'Surat diterbitkan dengan QR code resmi sehingga warga tidak perlu bolak-balik mengurus surat secara manual.',
                'action_text' => 'Ajukan Surat Sekarang',
                'action_url' => '/masuk',
                'detail_overview' => 'Layanan Surat Pengantar Online SIM-LiBas hadir untuk memodernisasi proses administrasi warga RT 15 Pematang Sulur. Warga tidak perlu lagi datang langsung dan menunggu di kediaman Ketua RT hanya untuk meminta selembar surat pengantar. Seluruh proses permohonan, peninjauan berkas, hingga penandatanganan digital dilakukan secara terpadu melalui sistem dengan jaminan keamanan data pribadi sesuai UU No. 27/2022 (UU PDP).',
                'tujuan' => 'Memberikan kemudahan akses administrasi kependudukan yang cepat, transparan, anti-birokrasi berbelit, dan dapat diakses 24 jam sehari dari ponsel warga.',
                'manfaat' => [
                    [
                        'judul' => 'Pengajuan Kapan Saja & Di Mana Saja',
                        'deskripsi' => 'Tidak terikat jam bertamu. Anda dapat mengajukan permohonan surat bahkan di luar jam kerja dari rumah.',
                    ],
                    [
                        'judul' => 'Keabsahan Terverifikasi QR Code',
                        'deskripsi' => 'Surat pengantar dilengkapi QR token resmi anti-pemalsuan yang dapat dipindai oleh pihak kelurahan atau instansi tujuan.',
                    ],
                    [
                        'judul' => 'Pantau Progres Real-Time',
                        'deskripsi' => 'Ketahui posisi pengajuan surat Anda secara transparan: Menunggu Peninjauan, Disetujui, atau Siap Diunduh.',
                    ],
                    [
                        'judul' => 'Arsip Digital Keluarga',
                        'deskripsi' => 'Seluruh riwayat surat yang pernah diajukan tersimpan rapi dan dapat diunduh ulang dalam format PDF kapan pun diperlukan.',
                    ],
                ],
                'langkah' => [
                    [
                        'nomor' => '01',
                        'judul' => 'Masuk ke Portal Warga',
                        'deskripsi' => 'Buka portal layanan mandiri warga SIM-LiBas dan masuk menggunakan email atau nomor WhatsApp yang telah terdaftar.',
                    ],
                    [
                        'nomor' => '02',
                        'judul' => 'Pilih Jenis Surat & Isi Data',
                        'deskripsi' => 'Pilih jenis surat pengantar yang dibutuhkan (Domisili, KTP/KK, SKCK, atau SKTM) lalu lengkapi alasan dan data keperluan.',
                    ],
                    [
                        'nomor' => '03',
                        'judul' => 'Unggah Dokumen Pendukung',
                        'deskripsi' => 'Lampirkan foto dokumen identitas yang relevan (seperti foto KTP atau Kartu Keluarga) jika disyaratkan.',
                    ],
                    [
                        'nomor' => '04',
                        'judul' => 'Verifikasi Pengurus RT',
                        'deskripsi' => 'Ketua atau Sekretaris RT 15 akan memeriksa kelayakan berkas dan memberikan persetujuan dengan token QR resmi.',
                    ],
                    [
                        'nomor' => '05',
                        'judul' => 'Unduh & Gunakan Surat',
                        'deskripsi' => 'Setelah disetujui, unduh file PDF surat pengantar resmi yang siap dicetak atau dilampirkan langsung ke kelurahan.',
                    ],
                ],
                'catatan_penting' => 'Pengurusan surat pengantar tidak dipungut biaya apapun (Gratis). Pastikan data NIK dan nama keluarga sesuai dengan data kependudukan resmi Anda.',
            ],

            'iuran-kas' => [
                'slug' => 'iuran-kas',
                'nama' => 'Iuran & Kas Transparan',
                'short_name' => 'Iuran & Kas',
                'kategori' => 'Keuangan & Akuntabilitas',
                'badge_color' => 'amber',
                'icon_bg' => 'bg-amber-50 text-gold-700',
                'deskripsi' => 'Layanan untuk melihat tagihan bulanan keluarga, mengonfirmasi pembayaran tunai atau transfer, serta memantau laporan arus kas RT 15.',
                'keunggulan' => 'Informasi iuran dan kas disajikan secara transparan dan akuntabel.',
                'action_text' => 'Lihat Status Tagihan',
                'action_url' => '/masuk',
                'detail_overview' => 'Transparansi keuangan adalah fondasi kepercayaan warga di RT 15 Pematang Sulur. Melalui modul Iuran & Kas Transparan, setiap kepala keluarga memiliki buku kas digital untuk memantau status iuran bulanan (kebersihan lingkungan, keamanan, dan dana sosial kematian/kesejahteraan). Selain memudahkan pembayaran non-tunai, modul ini juga menampilkan rekapitulasi penggunaan kas RT secara berkala sehingga warga mengetahui ke mana setiap rupiah dialokasikan.',
                'tujuan' => 'Mewujudkan tata kelola keuangan kas rukun tetangga yang akuntabel, bebas sengketa tagihan ganda, dan transparan bagi seluruh warga tanpa pengecualian.',
                'manfaat' => [
                    [
                        'judul' => 'Kejelasan Tagihan Tiap Bulan',
                        'deskripsi' => 'Rincian nominal iuran bulanan tercatat jelas tanpa ada kekeliruan riwayat bulan-bulan yang telah terbayar.',
                    ],
                    [
                        'judul' => 'Fleksibilitas Metode Pembayaran',
                        'deskripsi' => 'Dukung pembayaran via transfer bank/QRIS dengan unggah bukti transfer, maupun pembayaran tunai langsung ke bendahara RT.',
                    ],
                    [
                        'judul' => 'Kuitansi Digital Otomatis',
                        'deskripsi' => 'Bukti lunas digital otomatis terbit setelah diverifikasi bendahara, tidak perlu takut kehilangan sobekan kertas kuitansi fisik.',
                    ],
                    [
                        'judul' => 'Laporan Arus Kas Terbuka',
                        'deskripsi' => 'Warga dapat melihat ringkasan pemasukan dan pengeluaran kas RT untuk perawatan lampu jalan, got, hingga santunan.',
                    ],
                ],
                'langkah' => [
                    [
                        'nomor' => '01',
                        'judul' => 'Akses Menu Iuran Keluarga',
                        'deskripsi' => 'Masuk ke Portal Warga dan buka menu "Iuran & Kas" untuk melihat tagihan berjalan keluarga Anda.',
                    ],
                    [
                        'nomor' => '02',
                        'judul' => 'Pilih Tagihan & Cara Bayar',
                        'deskripsi' => 'Pilih bulan tagihan yang ingin dibayarkan, lalu tentukan metode pembayaran (Transfer Rekening RT atau Bayar Tunai).',
                    ],
                    [
                        'nomor' => '03',
                        'judul' => 'Lakukan Pembayaran & Konfirmasi',
                        'deskripsi' => 'Jika transfer, kirim ke rekening resmi kas RT 15 dan unggah foto/screenshot struk bukti transfer pada formulir yang tersedia.',
                    ],
                    [
                        'nomor' => '04',
                        'judul' => 'Verifikasi oleh Bendahara',
                        'deskripsi' => 'Bendahara RT 15 akan mencocokkan mutasi kas masuk dan memperbarui status tagihan Anda menjadi "Lunas".',
                    ],
                    [
                        'nomor' => '05',
                        'judul' => 'Simpan Kuitansi Digital',
                        'deskripsi' => 'Tanda terima pembayaran digital tersimpan di akun Anda dan dapat dijadikan rujukan sah kapan saja.',
                    ],
                ],
                'catatan_penting' => 'Iuran warga dialokasikan sepenuhnya untuk kenyamanan, kebersihan pengangkutan sampah, dan penerangan lingkungan RT 15.',
            ],

            'bank-sampah' => [
                'slug' => 'bank-sampah',
                'nama' => 'Bank Sampah Lestari',
                'short_name' => 'Bank Sampah',
                'kategori' => 'Lingkungan & Ekonomi Sirkular',
                'badge_color' => 'green',
                'icon_bg' => 'bg-green-50 text-green-700',
                'deskripsi' => 'Program pengelolaan sampah anorganik terpilah yang dapat dikonversi menjadi tabungan bernilai rupiah.',
                'keunggulan' => 'Saldo tabungan dapat ditarik tunai atau digunakan untuk memotong iuran bulanan RT.',
                'action_text' => 'Cek Harga Sampah',
                'action_url' => '/bank-sampah',
                'detail_overview' => 'Bank Sampah Pinang Masak Lestari adalah wujud nyata kepedulian lingkungan warga RT 15 yang berbuah nilai ekonomi. Dengan memilah sampah anorganik sejak dari dapur rumah tangga (seperti kardus, botol plastik PET, kaleng, besi, dan minyak jelantah), warga tidak hanya menjaga kebersihan selokan dan lingkungan, tetapi juga mengumpulkan saldo tabungan yang memiliki nilai rupiah nyata.',
                'tujuan' => 'Mengurangi volume sampah ke TPA, mengedukasi warga tentang ekonomi sirkular, dan memberikan manfaat finansial langsung bagi setiap keluarga penyetor.',
                'manfaat' => [
                    [
                        'judul' => 'Ubah Sampah Jadi Uang Tabungan',
                        'deskripsi' => 'Barang bekas yang biasanya dibuang sia-sia dikonversi menjadi saldo tabungan bernilai rupiah kompetitif.',
                    ],
                    [
                        'judul' => 'Potong Iuran Bulanan Otomatis',
                        'deskripsi' => 'Saldo bank sampah dapat disetel untuk memotong iuran kebersihan RT secara otomatis setiap bulannya.',
                    ],
                    [
                        'judul' => 'Lingkungan RT 15 Bersih & Sehat',
                        'deskripsi' => 'Mengurangi risiko banjir akibat selokan mampet serta menekan perkembangbiakan nyamuk demam berdarah.',
                    ],
                    [
                        'judul' => 'Transparansi Timbangan Digital',
                        'deskripsi' => 'Pencatatan berat sampah dan konversi harga tercatat di buku tabungan digital yang dapat dicek di website.',
                    ],
                ],
                'langkah' => [
                    [
                        'nomor' => '01',
                        'judul' => 'Pilah Sampah dari Rumah',
                        'deskripsi' => 'Kumpulkan dan pisahkan sampah anorganik kering (kertas/kardus, plastik, logam, kaca, atau minyak jelantah) di wadah terpisah.',
                    ],
                    [
                        'nomor' => '02',
                        'judul' => 'Kunjungi Posko Penimbangan',
                        'deskripsi' => 'Bawa sampah terpilah ke Posko Bank Sampah RT 15 pada jadwal penimbangan rutin setiap akhir pekan.',
                    ],
                    [
                        'nomor' => '03',
                        'judul' => 'Penimbangan & Verifikasi Harga',
                        'deskripsi' => 'Petugas bank sampah menimbang sampah Anda di hadapan Anda dan menginput jenis serta beratnya ke sistem SIM-LiBas.',
                    ],
                    [
                        'nomor' => '04',
                        'judul' => 'Saldo Masuk Buku Rekening',
                        'deskripsi' => 'Nilai nominal rupiah langsung ditambahkan ke saldo tabungan digital keluarga Anda secara real-time.',
                    ],
                    [
                        'nomor' => '05',
                        'judul' => 'Pencairan atau Potong Iuran',
                        'deskripsi' => 'Warga bebas mencairkan saldo tabungan saat masa penarikan tunai atau menggunakannya sebagai saldo bayar iuran RT.',
                    ],
                ],
                'catatan_penting' => 'Pastikan sampah yang disetor dalam keadaan bersih dan kering agar mendapatkan penimbangan dan taksiran harga terbaik.',
            ],

            'pengumuman-agenda' => [
                'slug' => 'pengumuman-agenda',
                'nama' => 'Pengumuman & Agenda',
                'short_name' => 'Pengumuman & Agenda',
                'kategori' => 'Komunikasi & Komunitas',
                'badge_color' => 'cyan',
                'icon_bg' => 'bg-cyan-50 text-accent-cyan',
                'deskripsi' => 'Pusat informasi kegiatan warga, seperti kerja bakti, pengajian, posyandu, dan agenda arisan.',
                'keunggulan' => 'Informasi kegiatan tersusun dalam satu tempat sehingga mudah ditemukan tanpa harus mencari pesan lama di grup WhatsApp.',
                'action_text' => 'Lihat Pengumuman',
                'action_url' => '/#pengumuman',
                'detail_overview' => 'Sering kali informasi penting rukun tetangga terlewat karena tertimbun ratusan pesan obrolan harian di grup WhatsApp warga. Modul Pengumuman & Agenda SIM-LiBas berfungsi sebagai papan buletin digital resmi RT 15 Pematang Sulur. Mulai dari jadwal imunisasi posyandu balita & lansia, jadwal ronda siskamling, surat edaran kelurahan, hingga agenda kerja bakti lingkungan diarsipkan secara kronologis dan rapi.',
                'tujuan' => 'Memastikan seluruh warga mendapatkan akses informasi resmi yang valid, akurat, tepat waktu, dan mudah ditelusuri kembali kapan saja.',
                'manfaat' => [
                    [
                        'judul' => 'Informasi Terverifikasi & Resmi',
                        'deskripsi' => 'Semua pemberitahuan diterbitkan langsung oleh Ketua RT 15 atau pengurus yang berwenang, bebas dari berita hoaks.',
                    ],
                    [
                        'judul' => 'Jadwal Agenda Jelas & Terstruktur',
                        'deskripsi' => 'Setiap agenda memuat tanggal, waktu mulai, lokasi pertemuan, dan hal-hal yang perlu disiapkan oleh warga.',
                    ],
                    [
                        'judul' => 'Unduh Lampiran Resmi',
                        'deskripsi' => 'Surat edaran, pamflet kegiatan, formulir pendaftaran, dan rincian acara dapat diunduh langsung dalam bentuk file.',
                    ],
                    [
                        'judul' => 'Pencarian Informasi Mudah',
                        'deskripsi' => 'Tidak perlu lagi scroll chat berhari-hari; cukup buka beranda pengumuman untuk melihat seluruh rekap kegiatan.',
                    ],
                ],
                'langkah' => [
                    [
                        'nomor' => '01',
                        'judul' => 'Buka Halaman Pengumuman',
                        'deskripsi' => 'Kunjungi beranda website SIM-LiBas pada bagian "Pengumuman & Agenda Warga" atau masuk lewat menu navigasi.',
                    ],
                    [
                        'nomor' => '02',
                        'judul' => 'Pilih Topik atau Agenda',
                        'deskripsi' => 'Telusuri daftar kartu pengumuman terbaru yang diurutkan dari yang paling aktual.',
                    ],
                    [
                        'nomor' => '03',
                        'judul' => 'Baca Detail Kegiatan',
                        'deskripsi' => 'Klik pada judul pengumuman untuk membaca isi surat pemberitahuan, lokasi kegiatan di RT 15, dan penanggung jawab acara.',
                    ],
                    [
                        'nomor' => '04',
                        'judul' => 'Unduh Dokumen Terkait',
                        'deskripsi' => 'Jika ada surat edaran resmi dari Kelurahan Pematang Sulur atau Kecamatan Telanaipura, unduh berkas lampiran yang tersedia.',
                    ],
                    [
                        'nomor' => '05',
                        'judul' => 'Catat di Kalender Anda',
                        'deskripsi' => 'Simpan tanggal penting kegiatan kemasyarakatan agar tidak terlewat dan dapat berpartisipasi aktif.',
                    ],
                ],
                'catatan_penting' => 'Pengumuman yang bersifat darurat (seperti imbauan keamanan atau pemadaman air/listrik) akan selalu diposisikan di paling atas.',
            ],

            'pengaduan' => [
                'slug' => 'pengaduan',
                'nama' => 'Pengaduan Warga',
                'short_name' => 'Pengaduan Warga',
                'kategori' => 'Ketertiban & Fasilitas Lingkungan',
                'badge_color' => 'red',
                'icon_bg' => 'bg-red-50 text-red-600',
                'deskripsi' => 'Layanan untuk melaporkan permasalahan lingkungan dan keamanan, seperti lampu jalan padam, saluran mampet, atau gangguan keamanan.',
                'keunggulan' => 'Warga dapat memantau status penanganan laporan dan menggunakan opsi pelaporan anonim jika didukung sistem.',
                'action_text' => 'Kirim Laporan',
                'action_url' => '/masuk',
                'detail_overview' => 'Kenyamanan dan ketertiban lingkungan RT 15 adalah tanggung jawab bersama. Layanan Pengaduan Warga SIM-LiBas memberikan saluran pelaporan masalah fasilitas umum dan ketertiban yang solutif dan terarah. Warga dapat mengadukan kerusakan fasilitas umum (lampu PJU mati, dahan pohon rapuh, got mampet) atau masalah ketertiban lingkungan disertai foto bukti lapangan dan titik lokasi.',
                'tujuan' => 'Menjembatani aspirasi warga dengan pengurus RT secara cepat, transparan, terukur, dan menjamin kenyamanan pelapor melalui opsi kerahasiaan identitas.',
                'manfaat' => [
                    [
                        'judul' => 'Respons Cepat Pengurus RT',
                        'deskripsi' => 'Laporan masuk langsung terdata di panel pengurus untuk segera dijadwalkan tindak lanjut atau diteruskan ke instansi terkait.',
                    ],
                    [
                        'judul' => 'Opsi Pelaporan Anonim',
                        'deskripsi' => 'Nama Anda dapat disamarkan dalam laporan demi menjaga kenyamanan bertetangga tanpa mengurangi esensi masalah.',
                    ],
                    [
                        'judul' => 'Lampiran Foto Kondisi Lapangan',
                        'deskripsi' => 'Dukung laporan Anda dengan foto bukti riil agar pengurus RT dan petugas teknis dapat memahami situasi dengan tepat.',
                    ],
                    [
                        'judul' => 'Transparansi Status Tindak Lanjut',
                        'deskripsi' => 'Pantau status penanganan dari "Menunggu", "Sedang Ditindaklanjuti", hingga "Selesai Ditangani" secara langsung.',
                    ],
                ],
                'langkah' => [
                    [
                        'nomor' => '01',
                        'judul' => 'Buka Menu Pengaduan di Portal',
                        'deskripsi' => 'Masuk ke Portal Warga RT 15 dan klik menu "Pengaduan & Aspirasi", lalu pilih "Buat Laporan Baru".',
                    ],
                    [
                        'nomor' => '02',
                        'judul' => 'Uraikan Permasalahan',
                        'deskripsi' => 'Tulis judul pengaduan dan jelaskan kronologi atau letak masalah fasilitas lingkungan secara detail.',
                    ],
                    [
                        'nomor' => '03',
                        'judul' => 'Unggah Foto Bukti & Lokasi',
                        'deskripsi' => 'Sertakan foto dokumentasi kondisi di lapangan (misal foto lampu jalan padam atau parit tersumbat).',
                    ],
                    [
                        'nomor' => '04',
                        'judul' => 'Tentukan Privasi (Opsi Anonim)',
                        'deskripsi' => 'Pilih apakah laporan Anda terbuka untuk pengurus atau ingin dikirimkan secara anonim tanpa mencantumkan nama.',
                    ],
                    [
                        'nomor' => '05',
                        'judul' => 'Pantau Hasil Penanganan',
                        'deskripsi' => 'Pengurus RT 15 akan memperbarui catatan tindakan dan mengonfirmasi penyelesaian kendala kepada Anda.',
                    ],
                ],
                'catatan_penting' => 'Layanan pengaduan ditujukan untuk kepentingan umum lingkungan RT 15. Untuk keadaan darurat keamanan mendesak, segera hubungi kontak Hotline RT 15.',
            ],

            'umkm' => [
                'slug' => 'umkm',
                'nama' => 'Katalog UMKM Warga',
                'short_name' => 'Katalog UMKM',
                'kategori' => 'Pemberdayaan Ekonomi Lokal',
                'badge_color' => 'indigo',
                'icon_bg' => 'bg-indigo-50 text-indigo-700',
                'deskripsi' => 'Katalog digital untuk mempromosikan produk dan jasa usaha warga RT 15, mulai dari kuliner, sembako, jasa, hingga kerajinan.',
                'keunggulan' => 'Membantu warga menemukan dan memesan produk UMKM tetangga secara langsung melalui WhatsApp.',
                'action_text' => 'Lihat Produk UMKM',
                'action_url' => '/katalog-umkm',
                'detail_overview' => 'Kampung Digital Pinang Masak Lestari menjunjung tinggi semangat gotong royong dalam perekonomian: "Belanja di Warung Tetangga Sendiri". Modul Katalog UMKM Warga adalah etalase promosi online gratis untuk seluruh pelaku usaha di lingkungan RT 15. Dari usaha kuliner rumahan, jasa servis, agen sembako, pakaian, hingga produk kerajinan tangan, semuanya dapat ditemukan oleh warga sekitar dengan mudah.',
                'tujuan' => 'Memajukan daya saing usaha mikro warga RT 15, memperluas jangkauan pembeli lokal, dan memfasilitasi transaksi langsung tanpa potongan komisi pihak ketiga.',
                'manfaat' => [
                    [
                        'judul' => 'Pesan Langsung via WhatsApp',
                        'deskripsi' => 'Setiap produk dilengkapi tombol chat langsung ke nomor WhatsApp pemilik usaha tanpa perantara yang rumit.',
                    ],
                    [
                        'judul' => 'Etalase Promosi Gratis untuk Warga',
                        'deskripsi' => 'Warga RT 15 yang memiliki usaha dapat mendaftarkan profil dan katalog fotonya secara cuma-cuma melalui portal.',
                    ],
                    [
                        'judul' => 'Kategori Produk Lengkap & Praktis',
                        'deskripsi' => 'Filter pencarian praktis berdasarkan kategori: Makanan & Minuman, Toko Sembako, Jasa Servis/Keahlian, dan Kerajinan.',
                    ],
                    [
                        'judul' => 'Putar Ekonomi di Lingkungan Sendiri',
                        'deskripsi' => 'Membeli kebutuhan sehari-hari dari tetangga terdekat mempererat silaturahmi sekaligus memajukan ekonomi warga RT 15.',
                    ],
                ],
                'langkah' => [
                    [
                        'nomor' => '01',
                        'judul' => 'Buka Direktori Katalog UMKM',
                        'deskripsi' => 'Kunjungi halaman Katalog UMKM melalui menu utama website atau klik tombol aksi di bawah ini.',
                    ],
                    [
                        'nomor' => '02',
                        'judul' => 'Gunakan Filter Pencarian',
                        'deskripsi' => 'Cari nama produk yang Anda inginkan atau filter menurut kategori bidang usaha.',
                    ],
                    [
                        'nomor' => '03',
                        'judul' => 'Pilih Toko atau Produk',
                        'deskripsi' => 'Lihat rincian produk, rentang harga, alamat rumah pedagang di RT 15, dan galeri foto produk.',
                    ],
                    [
                        'nomor' => '04',
                        'judul' => 'Hubungi Penjual via WhatsApp',
                        'deskripsi' => 'Klik tombol hijau "Hubungi via WA" untuk langsung memulai pemesanan atau bertanya ketersediaan stok.',
                    ],
                    [
                        'nomor' => '05',
                        'judul' => 'Daftarkan Usaha Anda Sendiri',
                        'deskripsi' => 'Bagi warga pemilik usaha, login ke Portal Warga untuk mengunggah profil dan produk dagangan Anda ke katalog.',
                    ],
                ],
                'catatan_penting' => 'Seluruh transaksi dilakukan secara langsung dan kesepakatan antara penjual dan pembeli. Pengurus RT tidak memungut biaya komisi transaksi.',
            ],
        ];
    }

    /**
     * Dapatkan detail satu layanan berdasarkan slug.
     */
    public static function find(string $slug): ?array
    {
        $all = self::all();
        return $all[$slug] ?? null;
    }

    /**
     * Dapatkan daftar layanan lain untuk rekomendasi navigasi (selain layanan yang sedang dibuka).
     */
    public static function others(string $currentSlug, int $limit = 3): array
    {
        $all = self::all();
        unset($all[$currentSlug]);
        return array_slice($all, 0, $limit);
    }
}
