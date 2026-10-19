<?php

namespace Database\Seeders;

use App\Models\FeeType;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\Resident;
use App\Models\User;
use App\Models\WasteWallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAndResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi akun pengurus RT 15, data KK, warga, serta user login untuk seluruh warga.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // =====================================================================
        // 1. Akun Pengurus Utama (Domain @simlibas.com sesuai permintaan)
        // =====================================================================
        
        // Super Admin Sistem
        $adminCom = User::firstOrCreate(
            ['email' => 'admin@simlibas.com'],
            [
                'name' => 'Administrator SIM-LiBas',
                'phone' => '0811-0000-0001',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $adminCom->syncRoles(['super_admin']);

        // Ketua RT 15
        $rtCom = User::firstOrCreate(
            ['email' => 'rt@simlibas.com'],
            [
                'name' => 'Bpk. H. Ahmad Sudirman, S.E.',
                'phone' => '0812-7400-1501',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $rtCom->syncRoles(['ketua_rt']);

        // Sekretaris RT 15
        $sekretarisCom = User::firstOrCreate(
            ['email' => 'sekretaris@simlibas.com'],
            [
                'name' => 'Bpk. Bambang Irawan, S.Kom.',
                'phone' => '0812-7400-1502',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $sekretarisCom->syncRoles(['sekretaris']);

        // Bendahara RT 15
        $bendaharaCom = User::firstOrCreate(
            ['email' => 'bendahara@simlibas.com'],
            [
                'name' => 'Ibu Hj. Nurhayati, S.E.',
                'phone' => '0812-7400-1503',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $bendaharaCom->syncRoles(['bendahara']);

        // Koordinator Bank Sampah Pinang Masak Lestari
        $banksampahCom = User::firstOrCreate(
            ['email' => 'banksampah@simlibas.com'],
            [
                'name' => 'Bpk. Joko Wahyudi',
                'phone' => '0812-7400-1504',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $banksampahCom->syncRoles(['koordinator_bank_sampah', 'warga']);

        // Koordinator Keamanan & Ronda RT 15
        $keamananCom = User::firstOrCreate(
            ['email' => 'keamanan@simlibas.com'],
            [
                'name' => 'Bpk. Hendra Saputra',
                'phone' => '0812-7400-1505',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $keamananCom->syncRoles(['koordinator_keamanan', 'warga']);

        // Perwakilan RW 04 / Kelurahan (Supervisor & Pemantau Read-Only)
        $rwCom = User::firstOrCreate(
            ['email' => 'rw@simlibas.com'],
            [
                'name' => 'Bpk. H. Suryadi, S.Sos.',
                'phone' => '0812-7400-1599',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $rwCom->syncRoles(['rw_kelurahan']);

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

        $kkDedi = Household::create([
            'no_kk' => '1571011203080001',
            'alamat' => 'Jl. Pinang Masak No. 15 Blok A, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $wargaDedi->id,
            'catatan' => 'Keluarga tetap warga RT 15 sejak 2015',
        ]);

        Resident::create([
            'household_id' => $kkDedi->id,
            'user_id' => $wargaDedi->id,
            'nik' => '1571011504780001',
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

        Resident::create([
            'household_id' => $kkDedi->id,
            'user_id' => $userSiti->id,
            'nik' => '1571015206800002',
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

        Resident::create([
            'household_id' => $kkDedi->id,
            'user_id' => $userRizky->id,
            'nik' => '1571011005050003',
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

        WasteWallet::create([
            'household_id' => $kkDedi->id,
            'saldo' => 87500.00,
        ]);

        // UMKM Keluarga Dedi: Pempek & Tekwan Pinang Masak 15
        \App\Models\MsmeProfile::firstOrCreate(
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

        $kkAgus = Household::create([
            'no_kk' => '1571012005120002',
            'alamat' => 'Jl. Pinang Masak No. 22 Blok B, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $wargaAgus->id,
            'catatan' => 'Warga tetap RT 15',
        ]);

        Resident::create([
            'household_id' => $kkAgus->id,
            'user_id' => $wargaAgus->id,
            'nik' => '1571012208850001',
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

        Resident::create([
            'household_id' => $kkAgus->id,
            'user_id' => $userDewi->id,
            'nik' => '1571015509870002',
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

        WasteWallet::create([
            'household_id' => $kkAgus->id,
            'saldo' => 45000.00,
        ]);

        // -------------------------------------------------------------
        // KK 3: Keluarga Bpk. Hendra Saputra (Koordinator Keamanan)
        // -------------------------------------------------------------
        $kkHendra = Household::create([
            'no_kk' => '1571011406150003',
            'alamat' => 'Jl. Pinang Masak No. 08 Blok C, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $keamananCom->id,
            'catatan' => 'Koordinator Keamanan & Ronda',
        ]);

        Resident::create([
            'household_id' => $kkHendra->id,
            'user_id' => $keamananCom->id,
            'nik' => '1571011406820001',
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

        Resident::create([
            'household_id' => $kkHendra->id,
            'user_id' => $userRina->id,
            'nik' => '1571015011860002',
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

        WasteWallet::create([
            'household_id' => $kkHendra->id,
            'saldo' => 30000.00,
        ]);

        // -------------------------------------------------------------
        // KK 4: Keluarga Bpk. Joko Wahyudi (Koordinator Bank Sampah)
        // -------------------------------------------------------------
        $kkJoko = Household::create([
            'no_kk' => '1571011809180004',
            'alamat' => 'Jl. Pinang Masak No. 11 Blok A, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $banksampahCom->id,
            'catatan' => 'Koordinator Bank Sampah Lestari',
        ]);

        Resident::create([
            'household_id' => $kkJoko->id,
            'user_id' => $banksampahCom->id,
            'nik' => '1571011809800001',
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

        Resident::create([
            'household_id' => $kkJoko->id,
            'user_id' => $userSri->id,
            'nik' => '1571014502840002',
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

        WasteWallet::create([
            'household_id' => $kkJoko->id,
            'saldo' => 52000.00,
        ]);

        // -------------------------------------------------------------
        // KK 5: Keluarga Bpk. Bambang Irawan (Sekretaris RT 15)
        // -------------------------------------------------------------
        $kkBambang = Household::create([
            'no_kk' => '1571011004100005',
            'alamat' => 'Jl. Pinang Masak No. 05 Blok B, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $sekretarisCom->id,
            'catatan' => 'Sekretaris RT 15 Pematang Sulur',
        ]);

        Resident::create([
            'household_id' => $kkBambang->id,
            'user_id' => $sekretarisCom->id,
            'nik' => '1571011004830001',
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

        Resident::create([
            'household_id' => $kkBambang->id,
            'user_id' => $userLinda->id,
            'nik' => '1571015005850002',
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

        WasteWallet::create([
            'household_id' => $kkBambang->id,
            'saldo' => 38000.00,
        ]);

        // -------------------------------------------------------------
        // KK 6: Keluarga Bpk. H. Ahmad Sudirman (Ketua RT 15)
        // -------------------------------------------------------------
        $kkSudirman = Household::create([
            'no_kk' => '1571010101750006',
            'alamat' => 'Jl. Pinang Masak No. 01 Blok A (Kediaman Ketua RT), RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $rtCom->id,
            'catatan' => 'Kediaman Ketua RT 15',
        ]);

        Resident::create([
            'household_id' => $kkSudirman->id,
            'user_id' => $rtCom->id,
            'nik' => '1571010101720001',
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

        Resident::create([
            'household_id' => $kkSudirman->id,
            'user_id' => $userFatimah->id,
            'nik' => '1571014503750002',
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

        WasteWallet::create([
            'household_id' => $kkSudirman->id,
            'saldo' => 64000.00,
        ]);

        // -------------------------------------------------------------
        // KK 7: Keluarga Ibu Hj. Nurhayati (Bendahara RT 15)
        // -------------------------------------------------------------
        $kkNurhayati = Household::create([
            'no_kk' => '1571010508800007',
            'alamat' => 'Jl. Pinang Masak No. 07 Blok A, RT 15',
            'rt_rw' => 'RT 15 / RW 04',
            'kelurahan' => 'Pematang Sulur',
            'kecamatan' => 'Telanaipura',
            'status_hunian' => 'tetap',
            'user_id' => $bendaharaCom->id,
            'catatan' => 'Bendahara RT 15',
        ]);

        Resident::create([
            'household_id' => $kkNurhayati->id,
            'user_id' => $bendaharaCom->id,
            'nik' => '1571014808790001',
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

        Resident::create([
            'household_id' => $kkNurhayati->id,
            'user_id' => $userFikri->id,
            'nik' => '1571011204040003',
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

        WasteWallet::create([
            'household_id' => $kkNurhayati->id,
            'saldo' => 45000.00,
        ]);

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
}
