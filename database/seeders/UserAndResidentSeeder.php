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

class UserAndResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Buat Akun Pengurus RT 15 dan Sampel Data Warga / KK
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // 1. Super Admin Sistem
        $admin = User::firstOrCreate(
            ['email' => 'admin@simlibas.id'],
            [
                'name' => 'Administrator SIM-LiBas',
                'phone' => '0811-0000-0001',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $admin->syncRoles(['super_admin']);

        // 2. Ketua RT 15
        $ketuaRtUser = User::firstOrCreate(
            ['email' => 'ketua.rt@simlibas.id'],
            [
                'name' => 'Bpk. H. Ahmad Sudirman, S.E.',
                'phone' => '0812-7400-1501',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $ketuaRtUser->syncRoles(['ketua_rt']);

        // 3. Sekretaris RT 15
        $sekretarisUser = User::firstOrCreate(
            ['email' => 'sekretaris@simlibas.id'],
            [
                'name' => 'Bpk. Bambang Irawan, S.Kom.',
                'phone' => '0812-7400-1502',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $sekretarisUser->syncRoles(['sekretaris']);

        // 4. Bendahara RT 15
        $bendaharaUser = User::firstOrCreate(
            ['email' => 'bendahara@simlibas.id'],
            [
                'name' => 'Ibu Hj. Nurhayati, S.E.',
                'phone' => '0812-7400-1503',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $bendaharaUser->syncRoles(['bendahara']);

        // 5. Koordinator Bank Sampah Pinang Masak Lestari
        $koorBankSampahUser = User::firstOrCreate(
            ['email' => 'banksampah@simlibas.id'],
            [
                'name' => 'Bpk. Joko Wahyudi',
                'phone' => '0812-7400-1504',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $koorBankSampahUser->syncRoles(['koordinator_bank_sampah']);

        // 6. Koordinator Keamanan & Ronda RT 15
        $koorKeamananUser = User::firstOrCreate(
            ['email' => 'keamanan@simlibas.id'],
            [
                'name' => 'Bpk. Hendra Saputra',
                'phone' => '0812-7400-1505',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $koorKeamananUser->syncRoles(['koordinator_keamanan']);

        // 7. Sampel Warga 1: Bpk. Dedi Kurniawan (Warga + Pelaku UMKM)
        $wargaDedi = User::firstOrCreate(
            ['email' => 'warga.dedi@simlibas.id'],
            [
                'name' => 'Bpk. Dedi Kurniawan',
                'phone' => '0813-6677-8899',
                'password' => $defaultPassword,
                'status' => 'aktif',
            ]
        );
        $wargaDedi->syncRoles(['warga']);

        // Buat KK Dedi
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

        // Anggota KK Dedi:
        // 1) Dedi (Kepala Keluarga)
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
            'email' => 'warga.dedi@simlibas.id',
            'is_kepala_keluarga' => true,
        ]);

        // 2) Siti Aminah (Istri - Pelaku UMKM)
        Resident::create([
            'household_id' => $kkDedi->id,
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
            'is_kepala_keluarga' => false,
        ]);

        // 3) Rizky Pratama (Anak ke-1)
        Resident::create([
            'household_id' => $kkDedi->id,
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
            'is_kepala_keluarga' => false,
        ]);

        // Dompet Bank Sampah Keluarga Dedi (sudah aktif menabung sampah)
        WasteWallet::create([
            'household_id' => $kkDedi->id,
            'saldo' => 87500.00,
        ]);

        // UMKM Keluarga Dedi: Pempek Pinang Masak 15
        MsmeProfile::create([
            'household_id' => $kkDedi->id,
            'nama_usaha' => 'Pempek & Tekwan Pinang Masak 15',
            'deskripsi' => 'Menyediakan aneka pempek ikan tenggiri asli, tekwan, model, dan lenggang khas Jambi-Palembang dengan cuko kental mantap.',
            'produk' => 'Pempek Kapal Selam, Pempek Lenjer, Tekwan Komplit, Cuko Asli Batanghari',
            'kategori' => 'kuliner',
            'alamat' => 'Jl. Pinang Masak No. 15 Blok A, RT 15 Pematang Sulur',
            'telepon' => '0813-6677-8898',
            'whatsapp' => '6281366778898',
            'status_moderasi' => 'disetujui',
            'is_active' => true,
        ]);

        // 8. Sampel Warga 2: Bpk. Agus Setiawan (Karyawan Swasta)
        $wargaAgus = User::firstOrCreate(
            ['email' => 'warga.agus@simlibas.id'],
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
            'catatan' => 'Warga tetap',
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
            'email' => 'warga.agus@simlibas.id',
            'is_kepala_keluarga' => true,
        ]);

        WasteWallet::create([
            'household_id' => $kkAgus->id,
            'saldo' => 45000.00,
        ]);

        // Buat tagihan iuran bulan berjalan untuk KK Dedi dan KK Agus
        $feeTypes = FeeType::all();
        $bulanSekarang = (int) date('n');
        $tahunSekarang = (int) date('Y');

        foreach ([$kkDedi, $kkAgus] as $kk) {
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
