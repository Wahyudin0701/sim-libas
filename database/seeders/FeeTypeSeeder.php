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
            [
                'nama' => 'Iuran Pemeliharaan Taman & Lingkungan Asri',
                'deskripsi' => 'Perawatan taman warga, tanaman peneduh jalan, dan pembersihan drainase berkala.',
                'nominal' => 15000.00,
                'periode' => 'bulanan',
                'is_active' => true,
            ],
            [
                'nama' => 'Iuran Pembangunan Gapura & Pos Kamling RT 15',
                'deskripsi' => 'Dana swadaya warga untuk pengecatan gapura masuk dan peremajaan pos ronda.',
                'nominal' => 50000.00,
                'periode' => 'insidental',
                'is_active' => true,
            ],
            [
                'nama' => 'Iuran Peringatan PHBI & HUT Kemerdekaan RI',
                'deskripsi' => 'Penyelenggaraan peringatan hari besar Islam, Maulid, dan lomba peringatan 17 Agustus.',
                'nominal' => 30000.00,
                'periode' => 'tahunan',
                'is_active' => true,
            ],
        ];

        foreach ($fees as $fee) {
            FeeType::updateOrCreate(['nama' => $fee['nama']], $fee);
        }
    }
}
