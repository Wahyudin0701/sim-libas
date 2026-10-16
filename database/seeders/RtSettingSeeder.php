<?php

namespace Database\Seeders;

use App\Models\RtSetting;
use Illuminate\Database\Seeder;

class RtSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Pengaturan Identitas RT 15 Pematang Sulur, Kota Jambi
     */
    public function run(): void
    {
        RtSetting::updateOrCreate(
            ['id' => 1],
            [
                'nama_rt' => 'Rukun Tetangga 15',
                'alamat' => 'Kampung Digital Pinang Masak Lestari, RT 15',
                'kelurahan' => 'Pematang Sulur',
                'kecamatan' => 'Telanaipura',
                'kota' => 'Kota Jambi',
                'provinsi' => 'Jambi',
                'kode_pos' => '36129',
                'telepon' => '0812-7400-1515',
                'email' => 'rt15pematangsulur@gmail.com',
                'nama_ketua_rt' => 'Bpk. H. Ahmad Sudirman, S.E.',
                'format_nomor_surat' => '{NO}/RT.15/PMS/{BULAN_ROMAWI}/{TAHUN}',
            ]
        );
    }
}
