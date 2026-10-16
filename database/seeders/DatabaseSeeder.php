<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Mengisi data master dan akun awal SIM-LiBas RT 15 Pematang Sulur
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            RtSettingSeeder::class,
            LetterTypeSeeder::class,
            FeeTypeSeeder::class,
            WasteTypeSeeder::class,
            UserAndResidentSeeder::class,
        ]);
    }
}
