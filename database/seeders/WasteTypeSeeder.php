<?php

namespace Database\Seeders;

use App\Models\WasteType;
use Illuminate\Database\Seeder;

class WasteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Master Jenis & Harga Sampah Bank Sampah Pinang Masak Lestari RT 15
     */
    public function run(): void
    {
        $types = [
            [
                'nama' => 'Kardus / Karton Coklat',
                'kategori' => 'anorganik',
                'harga_per_kg' => 2000.00,
                'satuan' => 'kg',
                'is_active' => true,
            ],
            [
                'nama' => 'Kertas Putih HVS / Arsip / Buku',
                'kategori' => 'anorganik',
                'harga_per_kg' => 2200.00,
                'satuan' => 'kg',
                'is_active' => true,
            ],
            [
                'nama' => 'Koran Bekas & Majalah',
                'kategori' => 'anorganik',
                'harga_per_kg' => 1500.00,
                'satuan' => 'kg',
                'is_active' => true,
            ],
            [
                'nama' => 'Botol Plastik PET Bersih (Mineral Bening)',
                'kategori' => 'anorganik',
                'harga_per_kg' => 3500.00,
                'satuan' => 'kg',
                'is_active' => true,
            ],
            [
                'nama' => 'Gelas Plastik PP Bersih (Air Mineral Cup)',
                'kategori' => 'anorganik',
                'harga_per_kg' => 3000.00,
                'satuan' => 'kg',
                'is_active' => true,
            ],
            [
                'nama' => 'Plastik Campur / Kresek Keras (HDPE/LDPE)',
                'kategori' => 'anorganik',
                'harga_per_kg' => 1200.00,
                'satuan' => 'kg',
                'is_active' => true,
            ],
            [
                'nama' => 'Besi Tua / Logam Padat',
                'kategori' => 'anorganik',
                'harga_per_kg' => 4500.00,
                'satuan' => 'kg',
                'is_active' => true,
            ],
            [
                'nama' => 'Kaleng Alumunium Minuman',
                'kategori' => 'anorganik',
                'harga_per_kg' => 12000.00,
                'satuan' => 'kg',
                'is_active' => true,
            ],
            [
                'nama' => 'Minyak Jelantah (Minyak Goreng Bekas)',
                'kategori' => 'organik',
                'harga_per_kg' => 6000.00,
                'satuan' => 'liter',
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            WasteType::updateOrCreate(['nama' => $type['nama']], $type);
        }
    }
}
