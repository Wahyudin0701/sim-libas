<?php

namespace Database\Seeders;

use App\Models\FeeType;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\MsmeProfile;
use App\Models\Resident;
use App\Models\User;
use App\Models\WasteWallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi data kependudukan demonstrasi (KK, Warga, UMKM Warga, Dompet Sampah, dan Tagihan Iuran)
     * untuk keperluan pengujian dan demonstrasi fungsionalitas sistem.
     *
     * PERINGATAN: Seeder ini HANYA untuk lingkungan non-production (local/testing).
     */
    public function run(): void
    {
        $defaultPassword = Hash::make(config('seeder.default_password', 'password'));

        // Ambil referensi akun pengurus (dibentuk oleh OfficialUserSeeder)
        $rtCom = User::where('email', 'rt@simlibas.com')->first();
        $sekretarisCom = User::where('email', 'sekretaris@simlibas.com')->first();
        $bendaharaCom = User::where('email', 'bendahara@simlibas.com')->first();
        $banksampahCom = User::where('email', 'banksampah@simlibas.com')->first();
        $keamananCom = User::where('email', 'keamanan@simlibas.com')->first();

        // -------------------------------------------------------------
        // KK 1: Keluarga Bpk. Dedi Kurniawan (Warga Tetap & Pelaku UMKM)
        // -------------------------------------------------------------
        $wargaDedi = User::firstOrCreate(
            ['email' => 'warga.dedi@simlibas.com'],
            [
                'name' => 'Bpk. Dedi Kurniawan',
                'phone' => '0813-6677-8899',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $wargaDedi->syncRoles(['warga']);

        $kkDedi = $this->getOrCreateHousehold('1571011203080001', [
            'alamat' => 'Jl. Pinang Masak No. 15 Blok A, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $wargaDedi->id,
            'catatan' => 'Keluarga tetap warga RT 15 sejak 2015',
        ]);

        $this->getOrCreateResident('1571011504780001', [
            'household_id' => $kkDedi->id,
            'user_id' => $wargaDedi->id,
            'nama' => 'Dedi Kurniawan',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1978-04-15',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'Wiraswasta',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'kepala_keluarga',
            'status_kependudukan' => 'aktif',
            'telepon' => '0813-6677-8899',
            'email' => 'warga.dedi@simlibas.com',
            'is_kepala_keluarga' => true,
        ]);

        $userSiti = User::firstOrCreate(
            ['email' => '1571015206800002@warga.simlibas.com'],
            [
                'name' => 'Siti Aminah',
                'phone' => '0813-6677-8898',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $userSiti->syncRoles(['warga']);

        $this->getOrCreateResident('1571015206800002', [
            'household_id' => $kkDedi->id,
            'user_id' => $userSiti->id,
            'nama' => 'Siti Aminah',
            'tempat_lahir' => 'Palembang',
            'tanggal_lahir' => '1980-06-12',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'SMA',
            'pekerjaan' => 'Pedagang / Wirausaha',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'istri',
            'status_kependudukan' => 'aktif',
            'telepon' => '0813-6677-8898',
            'email' => '1571015206800002@warga.simlibas.com',
            'is_kepala_keluarga' => false,
        ]);

        $userRizky = User::firstOrCreate(
            ['email' => '1571011005050003@warga.simlibas.com'],
            [
                'name' => 'Rizky Pratama Kurniawan',
                'phone' => '0813-6677-8897',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $userRizky->syncRoles(['warga']);

        $this->getOrCreateResident('1571011005050003', [
            'household_id' => $kkDedi->id,
            'user_id' => $userRizky->id,
            'nama' => 'Rizky Pratama Kurniawan',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '2005-05-10',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'SMA',
            'pekerjaan' => 'Mahasiswa UNJA',
            'status_perkawinan' => 'Belum Kawin',
            'hubungan_keluarga' => 'anak',
            'status_kependudukan' => 'aktif',
            'telepon' => '0813-6677-8897',
            'email' => '1571011005050003@warga.simlibas.com',
            'is_kepala_keluarga' => false,
        ]);

        WasteWallet::firstOrCreate(
            ['household_id' => $kkDedi->id],
            ['saldo' => 87500.00]
        );

        // UMKM Keluarga Dedi: Pempek & Tekwan Pinang Masak 15
        MsmeProfile::firstOrCreate(
            ['nama_usaha' => 'Pempek & Tekwan Pinang Masak 15'],
            [
                'household_id' => $kkDedi->id,
                'nama_usaha' => 'Pempek & Tekwan Pinang Masak 15',
                'deskripsi' => 'Menyediakan aneka pempek ikan tenggiri asli, tekwan, model, dan lenggang khas Jambi-Palembang dengan cuko kental mantap.',
                'produk' => 'Pempek Kapal Selam, Pempek Lenjer, Tekwan Komplit, Cuko Asli Batanghari',
                'kategori' => 'kuliner',
                'alamat' => 'Jl. Pinang Masak No. 15 Blok A, RT 15 Pematang Sulur',
                'telepon' => '0813-6677-8898',
                'whatsapp' => '6281366778898',
                'status_moderasi' => 'disetujui',
                'catatan_moderasi' => 'Usaha warga aktif, makanan higienis dan terdaftar.',
                'is_active' => true,
            ]
        );

        // -------------------------------------------------------------
        // KK 2: Keluarga Bpk. Agus Setiawan (Karyawan Swasta)
        // -------------------------------------------------------------
        $wargaAgus = User::firstOrCreate(
            ['email' => 'warga.agus@simlibas.com'],
            [
                'name' => 'Bpk. Agus Setiawan',
                'phone' => '0813-7788-9900',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $wargaAgus->syncRoles(['warga']);

        $kkAgus = $this->getOrCreateHousehold('1571012005120002', [
            'alamat' => 'Jl. Pinang Masak No. 22 Blok B, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $wargaAgus->id,
            'catatan' => 'Warga tetap RT 15',
        ]);

        $this->getOrCreateResident('1571012208850001', [
            'household_id' => $kkAgus->id,
            'user_id' => $wargaAgus->id,
            'nama' => 'Agus Setiawan, S.T.',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1985-08-22',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'Karyawan Swasta',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'kepala_keluarga',
            'status_kependudukan' => 'aktif',
            'telepon' => '0813-7788-9900',
            'email' => 'warga.agus@simlibas.com',
            'is_kepala_keluarga' => true,
        ]);

        $userDewi = User::firstOrCreate(
            ['email' => '1571015509870002@warga.simlibas.com'],
            [
                'name' => 'Dewi Lestari',
                'phone' => '0813-7788-9901',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $userDewi->syncRoles(['warga']);

        $this->getOrCreateResident('1571015509870002', [
            'household_id' => $kkAgus->id,
            'user_id' => $userDewi->id,
            'nama' => 'Dewi Lestari',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1987-09-15',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'D3',
            'pekerjaan' => 'Ibu Rumah Tangga',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'istri',
            'status_kependudukan' => 'aktif',
            'telepon' => '0813-7788-9901',
            'email' => '1571015509870002@warga.simlibas.com',
            'is_kepala_keluarga' => false,
        ]);

        WasteWallet::firstOrCreate(
            ['household_id' => $kkAgus->id],
            ['saldo' => 45000.00]
        );

        // -------------------------------------------------------------
        // KK 3: Keluarga Bpk. Hendra Saputra (Koordinator Keamanan)
        // -------------------------------------------------------------
        $kkHendra = $this->getOrCreateHousehold('1571011406150003', [
            'alamat' => 'Jl. Pinang Masak No. 08 Blok C, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $keamananCom?->id ?? $wargaAgus->id,
            'catatan' => 'Koordinator Keamanan & Ronda',
        ]);

        $this->getOrCreateResident('1571011406820001', [
            'household_id' => $kkHendra->id,
            'user_id' => $keamananCom?->id ?? $wargaAgus->id,
            'nama' => 'Bpk. Hendra Saputra',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1982-06-14',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'SMA',
            'pekerjaan' => 'Wiraswasta',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'kepala_keluarga',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1505',
            'email' => 'keamanan@simlibas.com',
            'is_kepala_keluarga' => true,
        ]);

        $userRina = User::firstOrCreate(
            ['email' => '1571015011860002@warga.simlibas.com'],
            [
                'name' => 'Rina Marlina',
                'phone' => '0812-7400-1506',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $userRina->syncRoles(['warga']);

        $this->getOrCreateResident('1571015011860002', [
            'household_id' => $kkHendra->id,
            'user_id' => $userRina->id,
            'nama' => 'Rina Marlina',
            'tempat_lahir' => 'Bukittinggi',
            'tanggal_lahir' => '1986-11-10',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'SMA',
            'pekerjaan' => 'Pedagang',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'istri',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1506',
            'email' => '1571015011860002@warga.simlibas.com',
            'is_kepala_keluarga' => false,
        ]);

        WasteWallet::firstOrCreate(
            ['household_id' => $kkHendra->id],
            ['saldo' => 30000.00]
        );

        // -------------------------------------------------------------
        // KK 4: Keluarga Bpk. Joko Wahyudi (Koordinator Bank Sampah)
        // -------------------------------------------------------------
        $kkJoko = $this->getOrCreateHousehold('1571011809180004', [
            'alamat' => 'Jl. Pinang Masak No. 11 Blok A, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $banksampahCom?->id ?? $wargaAgus->id,
            'catatan' => 'Koordinator Bank Sampah Lestari',
        ]);

        $this->getOrCreateResident('1571011809800001', [
            'household_id' => $kkJoko->id,
            'user_id' => $banksampahCom?->id ?? $wargaAgus->id,
            'nama' => 'Bpk. Joko Wahyudi',
            'tempat_lahir' => 'Solo',
            'tanggal_lahir' => '1980-09-18',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'D3',
            'pekerjaan' => 'Karyawan Swasta',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'kepala_keluarga',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1504',
            'email' => 'banksampah@simlibas.com',
            'is_kepala_keluarga' => true,
        ]);

        $userSri = User::firstOrCreate(
            ['email' => '1571014502840002@warga.simlibas.com'],
            [
                'name' => 'Sri Wahyuni',
                'phone' => '0812-7400-1507',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $userSri->syncRoles(['warga']);

        $this->getOrCreateResident('1571014502840002', [
            'household_id' => $kkJoko->id,
            'user_id' => $userSri->id,
            'nama' => 'Sri Wahyuni',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1984-02-05',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'Guru',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'istri',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1507',
            'email' => '1571014502840002@warga.simlibas.com',
            'is_kepala_keluarga' => false,
        ]);

        WasteWallet::firstOrCreate(
            ['household_id' => $kkJoko->id],
            ['saldo' => 52000.00]
        );

        // -------------------------------------------------------------
        // KK 5: Keluarga Bpk. Bambang Irawan (Sekretaris RT 15)
        // -------------------------------------------------------------
        $kkBambang = $this->getOrCreateHousehold('1571011004100005', [
            'alamat' => 'Jl. Pinang Masak No. 05 Blok B, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $sekretarisCom?->id ?? $wargaAgus->id,
            'catatan' => 'Sekretaris RT 15 Pematang Sulur',
        ]);

        $this->getOrCreateResident('1571011004830001', [
            'household_id' => $kkBambang->id,
            'user_id' => $sekretarisCom?->id ?? $wargaAgus->id,
            'nama' => 'Bpk. Bambang Irawan, S.Kom.',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1983-04-10',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'Karyawan Swasta',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'kepala_keluarga',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1502',
            'email' => 'sekretaris@simlibas.com',
            'is_kepala_keluarga' => true,
        ]);

        $userLinda = User::firstOrCreate(
            ['email' => '1571015005850002@warga.simlibas.com'],
            [
                'name' => 'Linda Marlina, S.Pd.',
                'phone' => '0812-7400-1508',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $userLinda->syncRoles(['warga']);

        $this->getOrCreateResident('1571015005850002', [
            'household_id' => $kkBambang->id,
            'user_id' => $userLinda->id,
            'nama' => 'Linda Marlina, S.Pd.',
            'tempat_lahir' => 'Palembang',
            'tanggal_lahir' => '1985-05-15',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'Guru',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'istri',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1508',
            'email' => '1571015005850002@warga.simlibas.com',
            'is_kepala_keluarga' => false,
        ]);

        WasteWallet::firstOrCreate(
            ['household_id' => $kkBambang->id],
            ['saldo' => 38000.00]
        );

        // -------------------------------------------------------------
        // KK 6: Keluarga Bpk. H. Ahmad Sudirman (Ketua RT 15)
        // -------------------------------------------------------------
        $kkSudirman = $this->getOrCreateHousehold('1571010101750006', [
            'alamat' => 'Jl. Pinang Masak No. 01 Blok A (Kediaman Ketua RT), RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $rtCom?->id ?? $wargaAgus->id,
            'catatan' => 'Kediaman Ketua RT 15',
        ]);

        $this->getOrCreateResident('1571010101720001', [
            'household_id' => $kkSudirman->id,
            'user_id' => $rtCom?->id ?? $wargaAgus->id,
            'nama' => 'Bpk. H. Ahmad Sudirman, S.E.',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1972-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'Pensiunan / Wiraswasta',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'kepala_keluarga',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1501',
            'email' => 'rt@simlibas.com',
            'is_kepala_keluarga' => true,
        ]);

        $userFatimah = User::firstOrCreate(
            ['email' => '1571014503750002@warga.simlibas.com'],
            [
                'name' => 'Hj. Fatimah Zahra',
                'phone' => '0812-7400-1509',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $userFatimah->syncRoles(['warga']);

        $this->getOrCreateResident('1571014503750002', [
            'household_id' => $kkSudirman->id,
            'user_id' => $userFatimah->id,
            'nama' => 'Hj. Fatimah Zahra',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1975-03-12',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'Ibu Rumah Tangga',
            'status_perkawinan' => 'Kawin',
            'hubungan_keluarga' => 'istri',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1509',
            'email' => '1571014503750002@warga.simlibas.com',
            'is_kepala_keluarga' => false,
        ]);

        WasteWallet::firstOrCreate(
            ['household_id' => $kkSudirman->id],
            ['saldo' => 64000.00]
        );

        // -------------------------------------------------------------
        // KK 7: Keluarga Ibu Hj. Nurhayati (Bendahara RT 15)
        // -------------------------------------------------------------
        $kkNurhayati = $this->getOrCreateHousehold('1571010508800007', [
            'alamat' => 'Jl. Pinang Masak No. 07 Blok A, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $bendaharaCom?->id ?? $wargaAgus->id,
            'catatan' => 'Bendahara RT 15',
        ]);

        $this->getOrCreateResident('1571014808790001', [
            'household_id' => $kkNurhayati->id,
            'user_id' => $bendaharaCom?->id ?? $wargaAgus->id,
            'nama' => 'Ibu Hj. Nurhayati, S.E.',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '1979-08-18',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'PNS / Wiraswasta',
            'status_perkawinan' => 'Cerai Mati',
            'hubungan_keluarga' => 'kepala_keluarga',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1503',
            'email' => 'bendahara@simlibas.com',
            'is_kepala_keluarga' => true,
        ]);

        $userFikri = User::firstOrCreate(
            ['email' => '1571011204040003@warga.simlibas.com'],
            [
                'name' => 'Muhammad Fikri',
                'phone' => '0812-7400-1510',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $userFikri->syncRoles(['warga']);

        $this->getOrCreateResident('1571011204040003', [
            'household_id' => $kkNurhayati->id,
            'user_id' => $userFikri->id,
            'nama' => 'Muhammad Fikri',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '2004-04-12',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'SMA',
            'pekerjaan' => 'Mahasiswa',
            'status_perkawinan' => 'Belum Kawin',
            'hubungan_keluarga' => 'anak',
            'status_kependudukan' => 'aktif',
            'telepon' => '0812-7400-1510',
            'email' => '1571011204040003@warga.simlibas.com',
            'is_kepala_keluarga' => false,
        ]);

        WasteWallet::firstOrCreate(
            ['household_id' => $kkNurhayati->id],
            ['saldo' => 45000.00]
        );

        // Buat tagihan iuran bulan berjalan untuk seluruh 7 KK
        $feeTypes = FeeType::all();
        $bulanSekarang = (int) date('n');
        $tahunSekarang = (int) date('Y');

        foreach ([$kkDedi, $kkAgus, $kkHendra, $kkJoko, $kkBambang, $kkSudirman, $kkNurhayati] as $kk) {
            foreach ($feeTypes as $fee) {
                Invoice::firstOrCreate(
                    [
                        'household_id' => $kk->id,
                        'fee_type_id' => $fee->id,
                        'periode_bulan' => $bulanSekarang,
                        'periode_tahun' => $tahunSekarang,
                    ],
                    [
                        'jumlah' => $fee->nominal,
                        'status' => 'belum_bayar',
                        'tanggal_jatuh_tempo' => date('Y-m-10', strtotime('+1 month')),
                    ]
                );
            }
        }
    }

    /**
     * Dapatkan instance Household berdasarkan hash nomor KK atau buat baru jika belum ada.
     */
    protected function getOrCreateHousehold(string $noKk, array $attributes): Household
    {
        $hash = hash('sha256', $noKk);
        $household = Household::where('no_kk_hash', $hash)->first();

        if ($household) {
            return $household;
        }

        return Household::create(array_merge(['no_kk' => $noKk], $attributes));
    }

    /**
     * Dapatkan instance Resident berdasarkan hash NIK atau buat baru jika belum ada.
     */
    protected function getOrCreateResident(string $nik, array $attributes): Resident
    {
        $hash = hash('sha256', $nik);
        $resident = Resident::where('nik_hash', $hash)->first();

        if ($resident) {
            return $resident;
        }

        return Resident::create(array_merge(['nik' => $nik], $attributes));
    }
}
