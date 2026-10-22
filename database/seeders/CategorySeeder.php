<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi seluruh kategori dan data referensi awal master sistem:
     * 1. Pengaturan Identitas RT 15 Pematang Sulur
     * 2. Master Jenis Surat Pengantar RT
     * 3. Master Jenis Iuran dan Retribusi Warga
     * 4. Master Jenis & Harga Sampah Bank Sampah Lestari
     */
    public function run(): void
    {
        $this->call([
            RtSettingSeeder::class,
            LetterTypeSeeder::class,
            FeeTypeSeeder::class,
            WasteTypeSeeder::class,
        ]);
    }
}
