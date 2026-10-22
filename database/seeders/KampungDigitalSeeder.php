<?php

namespace Database\Seeders;

use App\Models\Household;
use App\Models\MsmeProfile;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use App\Models\WasteWallet;
use App\Models\WasteWalletTransaction;
use Illuminate\Database\Seeder;

class KampungDigitalSeeder extends Seeder
{
    /**
     * Run the database seeds for Kampung Digital (UMKM & Bank Sampah Lestari).
     */
    public function run(): void
    {
        $households = Household::take(4)->get();
        if ($households->isEmpty()) {
            return;
        }

        $pencatat = User::where('email', 'rt@simlibas.com')->first()
            ?? User::first();

        // 1. Seed UMKM Warga RT 15 (6 usaha warga dengan variasi multi-kontak medsos)
        $msmes = [
            [
                'household_id' => $households[0]->id,
                'nama_usaha' => 'Pempek & Tekwan Pinang Masak 15',
                'deskripsi' => 'Menyediakan aneka pempek lenjer, kapal selam, kulit, dan tekwan khas Palembang/Jambi dengan cuko kental gula aren asli.',
                'produk' => 'Pempek Lenjer, Kapal Selam, Tekwan, Model Gandum',
                'kategori' => 'kuliner',
                'alamat' => 'Jl. Aster No. 12 RT 15 Pematang Sulur',
                'telepon' => '081274001511',
                'whatsapp' => '081274001511',
                'social_links' => [
                    'whatsapp' => '081274001511',
                    'instagram' => 'pempekpinang15',
                    'shopee' => 'pempek_pinang_jambi',
                ],
                'status_moderasi' => 'disetujui',
                'catatan_moderasi' => 'Usaha warga aktif, makanan higienis dan terdaftar.',
                'is_active' => true,
            ],
            [
                'household_id' => $households[1]?->id ?? $households[0]->id,
                'nama_usaha' => 'Kerajinan Anyaman Bambu & Rotan Lestari',
                'deskripsi' => 'Kerajinan tangan lokal ramah lingkungan: tudung saji, keranjang belanja, pot bunga, dan perabot bambu artistik.',
                'produk' => 'Tudung Saji, Keranjang Anyaman, Besek Bambu',
                'kategori' => 'kerajinan',
                'alamat' => 'Gang Melati No. 04 RT 15',
                'telepon' => '081366001522',
                'whatsapp' => '081366001522',
                'social_links' => [
                    'whatsapp' => '081366001522',
                    'instagram' => 'anyaman.lestari15',
                    'tiktok' => 'anyaman.lestari15',
                    'shopee' => 'lestari_craft_rt15',
                ],
                'status_moderasi' => 'disetujui',
                'catatan_moderasi' => 'Produk kreatif unggulan RT 15 binaan kelurahan.',
                'is_active' => true,
            ],
            [
                'household_id' => $households[2]?->id ?? $households[0]->id,
                'nama_usaha' => 'Warung Sembako & Frozen Food Berkah',
                'deskripsi' => 'Menyediakan kebutuhan pokok harian warga: beras, telur, minyak, bumbu dapur, gas elpiji 3kg, dan aneka nugget/sosis.',
                'produk' => 'Beras Solok, Telur Ayam, Minyak Goreng, Gas 3Kg, Frozen Food',
                'kategori' => 'sembako',
                'alamat' => 'Jl. Teratai Indah Blok B-3 RT 15',
                'telepon' => '082188001533',
                'whatsapp' => '082188001533',
                'social_links' => [
                    'whatsapp' => '082188001533',
                ],
                'status_moderasi' => 'pending',
                'catatan_moderasi' => 'Menunggu verifikasi kelengkapan izin usaha rumahan.',
                'is_active' => true,
            ],
            [
                'household_id' => $households[3]?->id ?? $households[0]->id,
                'nama_usaha' => 'Jahit & Vermak Pakaian Rapi Kilat',
                'deskripsi' => 'Layanan jahit baju gamis, kemeja, celana, seragam sekolah, dan vermak jeans cepat dengan hasil rapi dan harga terjangkau.',
                'produk' => 'Jahit Busana Muslim, Vermak Jeans, Pasang Resleting, Seragam',
                'kategori' => 'jasa',
                'alamat' => 'Lorong Mawar RT 15 Pematang Sulur',
                'telepon' => '085277001544',
                'whatsapp' => '085277001544',
                'social_links' => [
                    'whatsapp' => '085277001544',
                    'instagram' => 'vermak.kilat15',
                ],
                'status_moderasi' => 'disetujui',
                'catatan_moderasi' => 'Jasa jahit aktif dan terpercaya.',
                'is_active' => true,
            ],
            [
                'household_id' => $households[4]?->id ?? $households[0]->id,
                'nama_usaha' => 'Laundry Kiloan & Cuci Karpet Bersih Wangi',
                'deskripsi' => 'Jasa pencucian pakaian kiloan cuci-setrika uap, selimut, bedcover, dan karpet masjid dengan deterjen ramah lingkungan.',
                'produk' => 'Cuci Komplit Reguler, Cuci Kilat 4 Jam, Cuci Bedcover, Cuci Karpet',
                'kategori' => 'jasa',
                'alamat' => 'Jl. Pinang Masak No. 05 Blok B RT 15',
                'telepon' => '081274001555',
                'whatsapp' => '081274001555',
                'social_links' => [
                    'whatsapp' => '081274001555',
                    'tiktok' => 'laundry_bersihwangi',
                ],
                'status_moderasi' => 'disetujui',
                'catatan_moderasi' => 'Layanan laundry warga aktif dengan fasilitas antar-jemput lingkungan.',
                'is_active' => true,
            ],
            [
                'household_id' => $households[5]?->id ?? $households[0]->id,
                'nama_usaha' => 'Keripik Pisang & Rempeyek Gurih Ibu Sri',
                'deskripsi' => 'Aneka camilan gurih renyah: keripik pisang cokelat lumer, rempeyek kacang tanah, keripik sukun, dan emping melinjo.',
                'produk' => 'Keripik Pisang Aneka Rasa, Rempeyek Kacang, Keripik Sukun Gurih',
                'kategori' => 'kuliner',
                'alamat' => 'Jl. Pinang Masak No. 08 Blok C RT 15',
                'telepon' => '081274001566',
                'whatsapp' => null,
                'social_links' => [
                    'instagram' => 'keripik.ibusri',
                    'shopee' => 'keripik_ibusri_snack',
                ],
                'status_moderasi' => 'disetujui',
                'catatan_moderasi' => 'UMKM camilan warga binaan PKK RT 15.',
                'is_active' => true,
            ],
        ];

        foreach ($msmes as $data) {
            MsmeProfile::updateOrCreate(
                ['nama_usaha' => $data['nama_usaha']],
                $data
            );
        }

        // 2. Seed Setoran Bank Sampah Pinang Masak Lestari
        $wasteTypes = WasteType::where('is_active', true)->get();
        if ($wasteTypes->isNotEmpty()) {
            $kardus = $wasteTypes->where('nama', 'Kardus / Karton Coklat')->first() ?? $wasteTypes->first();
            $botol = $wasteTypes->where('nama', 'Botol Plastik PET Bersih (Mineral Bening)')->first() ?? $wasteTypes->first();
            $besi = $wasteTypes->where('nama', 'Besi Tua / Logam Padat')->first() ?? $wasteTypes->first();
            $jelantah = $wasteTypes->where('nama', 'Minyak Jelantah (Minyak Goreng Bekas)')->first() ?? $wasteTypes->first();

            $deposits = [
                [
                    'household_id' => $households[0]->id,
                    'waste_type_id' => $kardus->id,
                    'berat_kg' => 8.5,
                    'harga_saat_setor' => $kardus->harga_per_kg,
                    'nilai' => 8.5 * $kardus->harga_per_kg,
                    'catatan' => 'Setoran kardus bekas packaging elektronik warga',
                    'dicatat_oleh' => $pencatat->id,
                    'created_at' => now()->subDays(5),
                ],
                [
                    'household_id' => $households[0]->id,
                    'waste_type_id' => $botol->id,
                    'berat_kg' => 4.2,
                    'harga_saat_setor' => $botol->harga_per_kg,
                    'nilai' => 4.2 * $botol->harga_per_kg,
                    'catatan' => 'Botol plastik lemineral dan aqua sudah dicopot label',
                    'dicatat_oleh' => $pencatat->id,
                    'created_at' => now()->subDays(5),
                ],
                [
                    'household_id' => $households[1]?->id ?? $households[0]->id,
                    'waste_type_id' => $jelantah->id,
                    'berat_kg' => 5.0,
                    'harga_saat_setor' => $jelantah->harga_per_kg,
                    'nilai' => 5.0 * $jelantah->harga_per_kg,
                    'catatan' => 'Minyak jelantah dalam jerigen 5 liter bersih',
                    'dicatat_oleh' => $pencatat->id,
                    'created_at' => now()->subDays(3),
                ],
                [
                    'household_id' => $households[2]?->id ?? $households[0]->id,
                    'waste_type_id' => $besi->id,
                    'berat_kg' => 12.0,
                    'harga_saat_setor' => $besi->harga_per_kg,
                    'nilai' => 12.0 * $besi->harga_per_kg,
                    'catatan' => 'Sisa besi pagar renovasi rumah',
                    'dicatat_oleh' => $pencatat->id,
                    'created_at' => now()->subDays(2),
                ],
                [
                    'household_id' => $households[3]?->id ?? $households[0]->id,
                    'waste_type_id' => $kardus->id,
                    'berat_kg' => 15.0,
                    'harga_saat_setor' => $kardus->harga_per_kg,
                    'nilai' => 15.0 * $kardus->harga_per_kg,
                    'catatan' => 'Kardus tebal sisa pindahan rumah',
                    'dicatat_oleh' => $pencatat->id,
                    'created_at' => now()->subDay(),
                ],
            ];

            foreach ($deposits as $dep) {
                $createdDep = WasteDeposit::create($dep);

                // Update saldo WasteWallet keluarga
                $wallet = WasteWallet::firstOrCreate(
                    ['household_id' => $dep['household_id']],
                    ['saldo' => 0]
                );

                $saldoSebelum = $wallet->saldo;
                $saldoSesudah = $saldoSebelum + $dep['nilai'];
                $wallet->saldo = $saldoSesudah;
                $wallet->save();

                WasteWalletTransaction::create([
                    'household_id' => $dep['household_id'],
                    'jenis' => 'setoran',
                    'jumlah' => $dep['nilai'],
                    'saldo_sebelum' => $saldoSebelum,
                    'saldo_sesudah' => $saldoSesudah,
                    'keterangan' => "Setoran sampah {$createdDep->wasteType?->nama} ({$dep['berat_kg']} kg)",
                    'dicatat_oleh' => $pencatat->id,
                    'created_at' => $dep['created_at'],
                ]);
            }
        }
    }
}
