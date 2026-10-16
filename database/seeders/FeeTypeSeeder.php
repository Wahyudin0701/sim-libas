<?php

namespace Database\Seeders;

use App\Models\FeeType;
use Illuminate\Database\Seeder;

class FeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Jenis Iuran Warga RT 15 Pematang Sulur
     */
    public function run(): void
    {
        $fees = [
            [
                'nama' => 'Iuran Wajib RT & Kebersihan',
                'deskripsi' => 'Pengangkutan sampah rutin 3x seminggu dan operasional kebersihan lingkungan RT 15.',
                'nominal' => 25000.00,
                'periode' => 'bulanan',
                'is_active' => true,
            ],
            [
                'nama' => 'Iuran Keamanan & Pos Ronda',
                'deskripsi' => 'Operasional pos kamling, insentif jaga malam, dan pemeliharaan portal RT.',
                'nominal' => 20000.00,
                'periode' => 'bulanan',
                'is_active' => true,
            ],
            [
                'nama' => 'Iuran Kas Sosial & Kematian (Kematian/Sakit)',
                'deskripsi' => 'Dana santunan musibah kemalangan kematian, warga rawat inap, dan aksi tanggap sosial.',
                'nominal' => 10000.00,
                'periode' => 'bulanan',
                'is_active' => true,
            ],
        ];

        foreach ($fees as $fee) {
            FeeType::updateOrCreate(['nama' => $fee['nama']], $fee);
        }
    }
}
