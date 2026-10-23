<?php

namespace App\Console\Commands;

use App\Models\Household;
use App\Models\MsmeProfile;
use App\Models\Resident;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class InjectUmkmData extends Command
{
    /**
     * Nama dan signature perintah console.
     *
     * @var string
     */
    protected $signature = 'simlibas:inject-umkm
                            {--dry-run : Jalankan simulasi penuh tanpa menulis data ke database}
                            {--force : Lewati konfirmasi interaktif sebelum eksekusi}';

    /**
     * Deskripsi perintah console.
     *
     * @var string
     */
    protected $description = 'Injeksi dan sinkronisasi data UMKM warga RT 15 Pematang Sulur ke database SIM-LiBas';

    /**
     * Daftar 8 Data UMKM sumber (ZaTa Cake digabungkan).
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getSourceData(): array
    {
        return [
            [
                'item_no' => 1,
                'nama_pemilik' => 'Fitri Tresna Lestari',
                'nama_usaha' => 'Pempek Lestari Sulur Berkah',
                'kategori' => 'kuliner',
                'produk' => 'Pempek, Tekwan, Model Gandum',
                'deskripsi' => 'Menyediakan aneka pempek lenjer, kapal selam, kulit, dan olahan kuliner khas dengan cuko kental gula aren asli yang lezat dan higienis.',
                'alamat' => 'Komp. BTN Karya Indah Blok E 7',
                'telepon' => '085273949575',
                'whatsapp' => '085273949575',
                'qris' => 'Ada',
                'social_links' => [
                    'whatsapp' => '085273949575',
                    'qris' => 'ada',
                ],
                'catatan_moderasi' => 'UMKM kuliner binaan warga RT 15 Pematang Sulur. Pembayaran QRIS: Tersedia.',
            ],
            [
                'item_no' => 2,
                'nama_pemilik' => 'Deasy Mudahar',
                'nama_usaha' => 'Dapur Bunda',
                'kategori' => 'kuliner',
                'produk' => 'Aneka Masakan Rumahan, Katering Harian',
                'deskripsi' => 'Menyajikan aneka masakan rumahan lezat, higienis, dan katering harian keluarga dengan bahan-bahan segar pilihan.',
                'alamat' => 'Komp. BTN Karya Indah Blok A/4',
                'telepon' => '081366853339',
                'whatsapp' => '081366853339',
                'qris' => 'Tidak tersedia',
                'social_links' => [
                    'whatsapp' => '081366853339',
                    'qris' => 'tidak_tersedia',
                ],
                'catatan_moderasi' => 'UMKM kuliner warga RT 15 Pematang Sulur (Keluarga Bpk. Edwin Edward Rumajar / Ibu Deasy Mudahar). Pembayaran QRIS: Tidak tersedia.',
            ],
            [
                'item_no' => 3,
                'nama_pemilik' => 'Indah Anggraini',
                'nama_usaha' => 'Little Hood',
                'kategori' => 'kerajinan',
                'produk' => 'Produk Kriya, Kerajinan Tangan Kreatif',
                'deskripsi' => 'Produksi aneka produk kriya, kerajinan tangan artistik, dan cinderamata kreatif ramah lingkungan khas warga RT 15.',
                'alamat' => 'Komp. BTN Karya Indah Blok A/2',
                'telepon' => '082278506560',
                'whatsapp' => '082278506560',
                'qris' => 'Ada',
                'social_links' => [
                    'whatsapp' => '082278506560',
                    'qris' => 'ada',
                ],
                'catatan_moderasi' => 'UMKM kriya/kerajinan warga RT 15 Pematang Sulur. Akun TikTok belum diverifikasi sehingga tidak dipublikasikan. Pembayaran QRIS: Tersedia.',
            ],
            [
                'item_no' => 4,
                'nama_pemilik' => 'Hj. Mariana',
                'nama_usaha' => 'Kedai Rasa',
                'kategori' => 'kuliner',
                'produk' => 'Makanan Kedai, Minuman Segar',
                'deskripsi' => 'Menghadirkan berbagai sajian makanan kedai gurih, kudapan lezat, dan minuman segar untuk santapan santai keluarga.',
                'alamat' => 'Komp. BTN Karya Indah Blok A2',
                'telepon' => '085266259159',
                'whatsapp' => '085266259159',
                'qris' => 'Ada',
                'social_links' => [
                    'whatsapp' => '085266259159',
                    'qris' => 'ada',
                ],
                'catatan_moderasi' => 'UMKM kuliner warga RT 15 Pematang Sulur. Pembayaran QRIS: Tersedia.',
            ],
            [
                'item_no' => 5,
                'nama_pemilik' => 'Yanuar Surya Nanda, SE',
                'nama_usaha' => 'Rumah Rawit MR Nanda',
                'kategori' => 'kuliner',
                'produk' => 'Aneka Masakan Pedas, Olahan Ayam & Bebek Rawit',
                'deskripsi' => 'Kuliner spesial olahan pedas mantap Rumah Rawit MR Nanda dengan racikan cabai rawit pilihan. Tersedia pemesanan langsung maupun online via GrabFood.',
                'alamat' => 'Komp. BTN Karya Indah RT 15 Blok C No. 01',
                'telepon' => '088296163644',
                'whatsapp' => '088296163644',
                'qris' => 'Ada',
                'social_links' => [
                    'whatsapp' => '088296163644',
                    'grabfood' => 'GrabFood',
                    'qris' => 'ada',
                ],
                'catatan_moderasi' => 'UMKM kuliner warga RT 15 Pematang Sulur. Terdaftar di mitra GrabFood. Pembayaran QRIS: Tersedia.',
            ],
            [
                'item_no' => 6,
                'nama_pemilik' => 'Seprima Yuli Handayani',
                'nama_usaha' => 'Dapoer Prima',
                'kategori' => 'kuliner',
                'produk' => 'Produk Frozen Food, Keju Mozzarella',
                'deskripsi' => 'Menyediakan aneka produk makanan beku (frozen food) berkualitas dan higienis, termasuk keju mozzarella leleh istimewa untuk aneka kebutuhan memasak.',
                'alamat' => 'Komp. BTN Karya Indah RT 15 Blok C No. 3, Kel. Pematang Sulur',
                'telepon' => '082180307997',
                'whatsapp' => '082180307997',
                'qris' => 'Tidak tersedia',
                'social_links' => [
                    'whatsapp' => '082180307997',
                    'shopee' => 'seprima_prims',
                    'instagram' => 'dapoer_prima',
                    'facebook' => 'Seprima Prims',
                    'tiktok' => 'dapoer_prima',
                    'qris' => 'tidak_tersedia',
                ],
                'catatan_moderasi' => 'UMKM frozen food warga RT 15 Pematang Sulur. Profil media sosial lengkap: Instagram, TikTok, Facebook, dan Shopee. Pembayaran QRIS: Tidak tersedia.',
            ],
            [
                'item_no' => 7,
                'nama_pemilik' => 'Mutiah',
                'nama_usaha' => 'Jamu Kunyit Asam Semata Wayang MAP',
                'kategori' => 'kuliner',
                'produk' => 'Jamu Kunyit Asam Tradisional, Minuman Herbal Segar',
                'deskripsi' => 'Minuman herbal tradisional jamu kunyit asam asli dan segar tanpa bahan pengawet kimiawi, diolah higienis untuk menjaga kebugaran dan daya tahan tubuh.',
                'alamat' => 'Ruko Green Golf Resident',
                'telepon' => '081288285617',
                'whatsapp' => '081288285617',
                'qris' => 'Ada',
                'social_links' => [
                    'whatsapp' => '081288285617',
                    'qris' => 'ada',
                ],
                'catatan_moderasi' => 'UMKM jamu herbal tradisional warga RT 15 Pematang Sulur. Pembayaran QRIS: Tersedia.',
            ],
            [
                'item_no' => 8,
                'nama_pemilik' => 'Asmaryani Juwita',
                'nama_usaha' => 'ZaTa Cake',
                'kategori' => 'kuliner',
                'produk' => 'Snack Box, Kudapan, Kue Basah & Kering, Katering',
                'deskripsi' => 'Menerima pesanan aneka snack box berkualitas, kudapan kue lezat, kue basah dan kering tradisional/modern, serta sajian katering acara.',
                'alamat' => 'Komplek BTN Karya Indah Blok E10, RT 15, Pematang Sulur, Telanaipura, Jambi',
                'telepon' => '085210000341',
                'whatsapp' => '085210000341',
                'qris' => 'Tersedia',
                'social_links' => [
                    'whatsapp' => '085210000341',
                    'instagram' => 'Jajan_ala_zata',
                    'tiktok' => 'zata cake',
                    'qris' => 'tersedia',
                ],
                'catatan_moderasi' => 'UMKM kue & katering warga RT 15 Pematang Sulur. Gabungan entri ZaTa Cake dengan akun Instagram (@Jajan_ala_zata) dan TikTok (@zata cake). Pembayaran QRIS: Tersedia.',
            ],
        ];
    }

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        $this->newLine();
        $this->info('========================================================================');
        $this->info('  🚀 SIM-LiBas - Injection Data UMKM Warga RT 15 Pematang Sulur');
        $this->info('========================================================================');
        $this->line('Mode Operasi : '.($isDryRun ? '<comment>[DRY-RUN / SIMULASI]</comment> (Tidak ada data yang disimpan permanen)' : '<fg=green>[LIVE PRODUCTION EXECUTION]</fg=green>'));
        $this->newLine();

        $sourceData = $this->getSourceData();

        // 1. Audit dan pencocokan awal
        $this->info('🔍 Melakukan audit struktur data dan pemeriksaan database...');
        $preflightReport = [];
        $duplicateCount = 0;
        $matchedHouseholdCount = 0;
        $newHouseholdCount = 0;

        foreach ($sourceData as $item) {
            $check = $this->analyzeRecord($item);
            $preflightReport[] = $check;

            if ($check['is_existing_msme']) {
                $duplicateCount++;
            }
            if ($check['household_action'] === 'EXISTING') {
                $matchedHouseholdCount++;
            } else {
                $newHouseholdCount++;
            }
        }

        // Tampilkan tabel pra-eksekusi
        $tableRows = [];
        foreach ($preflightReport as $r) {
            $tableRows[] = [
                $r['item_no'],
                $r['nama_usaha'],
                $r['nama_pemilik'],
                $r['kategori'],
                $r['whatsapp'],
                $r['action_label'],
                $r['household_info'],
                $r['qris'],
            ];
        }

        $this->table(
            ['No', 'Nama Usaha', 'Pemilik', 'Kategori', 'WhatsApp', 'Aksi UMKM', 'Target Kartu Keluarga (KK)', 'QRIS'],
            $tableRows
        );

        $this->line('• Total entri UMKM yang diproses : <fg=cyan>'.count($sourceData).'</fg=cyan> (Termasuk penggabungan duplikasi ZaTa Cake)');
        $this->line("• Profil KK yang sudah cocok     : <fg=green>{$matchedHouseholdCount}</fg=green>");
        $this->line("• Profil KK baru yang disiapkan  : <fg=yellow>{$newHouseholdCount}</fg=yellow>");
        $this->line("• Rekaman UMKM terdeteksi kembar : <fg=cyan>{$duplicateCount}</fg=cyan>");
        $this->newLine();

        // Konfirmasi jika Live Mode
        if (! $isDryRun && ! $force) {
            if (! $this->confirm('Apakah Anda yakin ingin mengeksekusi injeksi data UMKM ke database SIM-LiBas sekarang?', true)) {
                $this->warn('⚠️ Eksekusi dibatalkan oleh pengguna.');

                return Command::SUCCESS;
            }
        }

        // 2. Eksekusi Injeksi dalam Transaksi Database
        $this->info($isDryRun ? '⚙️ Menjalankan simulasi injeksi UMKM...' : '💾 Menyimpan data UMKM ke database SIM-LiBas...');

        DB::beginTransaction();

        $insertedMsmes = [];
        $updatedMsmes = [];
        $createdHouseholds = [];

        try {
            foreach ($sourceData as $item) {
                // Selesaikan KK (Household) terlebih dahulu
                $household = $this->resolveHousehold($item);
                if ($household->wasRecentlyCreated) {
                    $createdHouseholds[] = $household;
                }

                // Cek apakah UMKM sudah ada
                $existingMsme = MsmeProfile::withTrashed()
                    ->where(function ($q) use ($item) {
                        $q->whereRaw('LOWER(TRIM(nama_usaha)) = ?', [strtolower(trim($item['nama_usaha']))])
                            ->orWhere('whatsapp', $item['whatsapp'])
                            ->orWhere('telepon', $item['whatsapp']);
                    })
                    ->first();

                if ($existingMsme) {
                    // Update profil yang ada
                    if ($existingMsme->trashed()) {
                        $existingMsme->restore();
                    }

                    $mergedLinks = array_merge(
                        is_array($existingMsme->social_links) ? $existingMsme->social_links : [],
                        $item['social_links']
                    );

                    $existingMsme->update([
                        'household_id' => $household->id,
                        'nama_usaha' => $item['nama_usaha'],
                        'kategori' => $item['kategori'],
                        'produk' => $item['produk'],
                        'deskripsi' => $item['deskripsi'],
                        'alamat' => $item['alamat'],
                        'telepon' => $item['telepon'],
                        'whatsapp' => $item['whatsapp'],
                        'social_links' => $mergedLinks,
                        'status_moderasi' => 'disetujui',
                        'catatan_moderasi' => $item['catatan_moderasi'],
                        'is_active' => true,
                    ]);

                    $updatedMsmes[] = $existingMsme;
                } else {
                    // Buat profil UMKM baru
                    $newMsme = MsmeProfile::create([
                        'household_id' => $household->id,
                        'nama_usaha' => $item['nama_usaha'],
                        'kategori' => $item['kategori'],
                        'produk' => $item['produk'],
                        'deskripsi' => $item['deskripsi'],
                        'alamat' => $item['alamat'],
                        'telepon' => $item['telepon'],
                        'whatsapp' => $item['whatsapp'],
                        'social_links' => $item['social_links'],
                        'status_moderasi' => 'disetujui',
                        'catatan_moderasi' => $item['catatan_moderasi'],
                        'is_active' => true,
                    ]);

                    $insertedMsmes[] = $newMsme;
                }
            }

            if ($isDryRun) {
                DB::rollBack();
                $this->newLine();
                $this->info('========================================================================');
                $this->info('  ✅ SIMULASI SELESAI (DRY-RUN BERHASIL)');
                $this->info('========================================================================');
                $this->line('Semua transaksi berhasil divalidasi dan di-rollback. Tidak ada perubahan yang disimpan ke database.');
            } else {
                DB::commit();
                $this->newLine();
                $this->info('========================================================================');
                $this->info('  🎉 INJEKSI DATA UMKM BERHASIL DI PRODUCTION');
                $this->info('========================================================================');
                $this->line('Seluruh data UMKM warga RT 15 telah tersimpan secara permanen dan terintegrasi.');
            }

            $this->newLine();
            $this->line('📊 <options=bold>Rangkuman Hasil Operasi:</options=bold>');
            $this->line('  • Record UMKM baru dimasukkan : <fg=green>'.count($insertedMsmes).'</fg=green>');
            $this->line('  • Record UMKM yang diperbarui : <fg=yellow>'.count($updatedMsmes).'</fg=yellow>');
            $this->line('  • Profil KK baru terdaftar    : <fg=cyan>'.count($createdHouseholds).'</fg=cyan>');
            $this->newLine();

            return Command::SUCCESS;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->newLine();
            $this->error('❌ Terjadi kesalahan saat memproses data UMKM: '.$e->getMessage());
            $this->line($e->getTraceAsString());

            return Command::FAILURE;
        }
    }

    /**
     * Menganalisis calon data UMKM terhadap database.
     *
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function analyzeRecord(array $item): array
    {
        $existingMsme = MsmeProfile::withTrashed()
            ->where(function ($q) use ($item) {
                $q->whereRaw('LOWER(TRIM(nama_usaha)) = ?', [strtolower(trim($item['nama_usaha']))])
                    ->orWhere('whatsapp', $item['whatsapp'])
                    ->orWhere('telepon', $item['whatsapp']);
            })
            ->first();

        // Cari Resident berdasarkan nama pemilik
        $matchedResident = Resident::with('household')
            ->where('nama', 'like', '%'.trim($item['nama_pemilik']).'%')
            ->first();

        // Jika tidak ditemukan warga, cari Household berdasarkan alamat yang mirip
        $matchedHousehold = null;
        if ($matchedResident && $matchedResident->household) {
            $matchedHousehold = $matchedResident->household;
        } else {
            // Cek alamat
            $matchedHousehold = Household::where('alamat', 'like', '%'.trim($item['alamat']).'%')->first();
        }

        $householdAction = $matchedHousehold ? 'EXISTING' : 'NEW';
        $householdInfo = $matchedHousehold
            ? "{$matchedHousehold->id_kk} (ID: {$matchedHousehold->id})"
            : 'Auto-Generate KK Baru';

        return [
            'item_no' => $item['item_no'],
            'nama_usaha' => $item['nama_usaha'],
            'nama_pemilik' => $item['nama_pemilik'],
            'kategori' => $item['kategori'],
            'whatsapp' => $item['whatsapp'],
            'is_existing_msme' => (bool) $existingMsme,
            'action_label' => $existingMsme ? '<comment>UPDATE</comment>' : '<fg=green>INSERT BARU</fg=green>',
            'household_action' => $householdAction,
            'household_info' => $householdInfo,
            'qris' => $item['qris'],
        ];
    }

    /**
     * Memastikan Kartu Keluarga (Household) dan Kepala Keluarga (Resident) tersedia.
     *
     * @param  array<string, mixed>  $item
     */
    protected function resolveHousehold(array $item): Household
    {
        // 1. Cek apakah pemilik usaha sudah terdaftar di residents
        $cleanName = trim($item['nama_pemilik']);
        $resident = Resident::with('household')->where('nama', 'like', "%{$cleanName}%")->first();
        if ($resident && $resident->household) {
            return $resident->household;
        }

        // 2. Cek apakah ada Household dengan alamat yang sama persis
        $cleanAlamat = trim($item['alamat']);
        $existingHousehold = Household::where('alamat', $cleanAlamat)->first();
        if ($existingHousehold) {
            return $existingHousehold;
        }

        // 3. Jika belum ada, buat Household baru yang rapi sesuai standar RT 15 Pematang Sulur
        $nextIdKk = Household::generateNextIdKk();
        $pseudoNoKk = '157101'.date('ymd').sprintf('%04d', rand(1000, 9999));

        $household = Household::create([
            'id_kk' => $nextIdKk,
            'no_kk' => $pseudoNoKk,
            'alamat' => $item['alamat'],
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'PEMATANG SULUR',
            'kecamatan' => 'TELANAIPURA',
            'status_hunian' => 'tetap',
            'catatan' => "Registrasi Kartu Keluarga Warga RT 15 untuk Usaha: {$item['nama_usaha']} (Pemilik: {$item['nama_pemilik']})",
        ]);

        // Buat Resident sebagai Kepala Keluarga agar relasi household.headOfHousehold.nama tampil di katalog
        $gender = 'P';
        if (preg_match('/(Yanuar|Edwin|Dedy|Bpk|Dody)/i', $item['nama_pemilik'])) {
            $gender = 'L';
        }

        Resident::create([
            'household_id' => $household->id,
            'nama' => $item['nama_pemilik'],
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1985-01-01',
            'jenis_kelamin' => $gender,
            'agama' => 'Islam',
            'hubungan_keluarga' => 'kepala_keluarga',
            'status_kependudukan' => 'aktif',
            'telepon' => $item['whatsapp'],
            'is_kepala_keluarga' => true,
            'status_tempat_tinggal' => 'tetap',
            'alamat_domisili' => $item['alamat'],
        ]);

        return $household;
    }
}
