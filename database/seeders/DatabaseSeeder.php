<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan eksekusi seeder SIM-LiBas RT 15:
     * 1. Role & Hak Akses Pengguna (RoleSeeder)
     * 2. Data Referensi & Kategori Master (CategorySeeder, PatrolScheduleMasterSeeder)
     * 3. Akun Awal Pengurus Resmi (OfficialUserSeeder) - Membaca kredensial aman dari .env
     * 4. Data Pengembangan / Demo (Hanya aktif di lingkungan non-production: local/testing)
     */
    public function run(): void
    {
        // -----------------------------------------------------------------
        // 1. Roles & Permissions (Hak Akses Spatie)
        // -----------------------------------------------------------------
        $this->call([
            RoleSeeder::class,
        ]);

        // -----------------------------------------------------------------
        // 2. Master Data & Referensi Kategori Sistem
        //    (Profil RT, 8 Jenis Surat, 6 Jenis Iuran, 9 Komoditas Sampah, 7 Hari Ronda)
        // -----------------------------------------------------------------
        $this->call([
            CategorySeeder::class,
            PatrolScheduleMasterSeeder::class,
        ]);

        // -----------------------------------------------------------------
        // 3. Akun Awal Pengurus Resmi (Super Admin, RT, Sekretaris, Bendahara,
        //    Bank Sampah, Keamanan, RW/Kelurahan)
        //    Kredensial dibaca aman melalui konfigurasi .env
        // -----------------------------------------------------------------
        $this->call([
            OfficialUserSeeder::class,
        ]);

        // -----------------------------------------------------------------
        // 4. Data Transaksional & Demonstrasi Kependudukan (Non-Production Only)
        //    Di lingkungan production, langkah ini otomatis dilewati agar
        //    data pribadi palsu / dummy warga tidak mengotori server produksi.
        // -----------------------------------------------------------------
        if (! app()->environment('production')) {
            $this->call([
                DemoResidentSeeder::class,
                LetterSeeder::class,
                CashTransactionSeeder::class,
                PaymentSeeder::class,
                CommunicationAndSecuritySeeder::class,
                KampungDigitalSeeder::class,
            ]);
        }
    }
}
