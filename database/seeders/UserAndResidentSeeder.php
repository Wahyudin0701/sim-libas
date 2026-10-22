<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserAndResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Menggabungkan seeding akun resmi pengurus dan data demonstrasi kependudukan.
     * Disediakan untuk menjaga kompatibilitas panggilan seeder yang sudah ada.
     */
    public function run(): void
    {
        $this->call([
            OfficialUserSeeder::class,
            DemoResidentSeeder::class,
        ]);
    }
}
