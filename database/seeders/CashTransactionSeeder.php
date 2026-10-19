<?php

namespace Database\Seeders;

use App\Models\CashTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CashTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi data pembukuan kas umum RT 15 Pematang Sulur
     */
    public function run(): void
    {
        $bendahara = User::where('email', 'bendahara@simlibas.id')->first()
            ?? User::where('email', 'ketua.rt@simlibas.id')->first()
            ?? User::first();

        if (! $bendahara) {
            return;
        }

        $transactions = [
            [
                'jenis' => 'masuk',
                'kategori' => 'Saldo Awal',
                'deskripsi' => 'Saldo kas awal pergantian periode kepengurusan RT 15 tahun 2026',
                'jumlah' => 5000000.00,
                'tanggal' => Carbon::now()->subMonths(2)->startOfMonth()->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
            [
                'jenis' => 'masuk',
                'kategori' => 'Iuran Warga',
                'deskripsi' => 'Penerimaan iuran warga rutin kebersihan & keamanan bulan Agustus 2026 (Tahap 1)',
                'jumlah' => 1850000.00,
                'tanggal' => Carbon::now()->subMonths(2)->addDays(10)->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
            [
                'jenis' => 'keluar',
                'kategori' => 'Pemeliharaan Fasilitas',
                'deskripsi' => 'Pembelian 6 unit bohlam LED 40W dan kabel pengganti lampu jalan Gang Kenanga RT 15',
                'jumlah' => 450000.00,
                'tanggal' => Carbon::now()->subMonths(2)->addDays(15)->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
            [
                'jenis' => 'keluar',
                'kategori' => 'Kegiatan Warga',
                'deskripsi' => 'Konsumsi snack dan air mineral kerja bakti bersih saluran air RT 15',
                'jumlah' => 350000.00,
                'tanggal' => Carbon::now()->subMonths(2)->addDays(20)->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
            [
                'jenis' => 'masuk',
                'kategori' => 'Iuran Warga',
                'deskripsi' => 'Penerimaan iuran warga rutin bulan September 2026',
                'jumlah' => 2450000.00,
                'tanggal' => Carbon::now()->subMonths(1)->addDays(5)->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
            [
                'jenis' => 'masuk',
                'kategori' => 'Donasi & Sumbangan',
                'deskripsi' => 'Sumbangan swadaya donatur warga untuk pengecatan pos ronda & gapura RT 15',
                'jumlah' => 1500000.00,
                'tanggal' => Carbon::now()->subMonths(1)->addDays(12)->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
            [
                'jenis' => 'keluar',
                'kategori' => 'Kesehatan Lingkungan',
                'deskripsi' => 'Biaya bahan obat dan solar pengasapan (fogging) nyamuk DBD di seluruh blok rumah warga RT 15',
                'jumlah' => 600000.00,
                'tanggal' => Carbon::now()->subMonths(1)->addDays(18)->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
            [
                'jenis' => 'keluar',
                'kategori' => 'Sosial & Kematian',
                'deskripsi' => 'Santunan duka cita musibah kemalangan warga Blok C RT 15',
                'jumlah' => 500000.00,
                'tanggal' => Carbon::now()->subMonths(1)->addDays(25)->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
            [
                'jenis' => 'masuk',
                'kategori' => 'Iuran Warga',
                'deskripsi' => 'Penerimaan iuran warga rutin kebersihan & keamanan bulan Oktober 2026 (Tahap 1)',
                'jumlah' => 2100000.00,
                'tanggal' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
            [
                'jenis' => 'keluar',
                'kategori' => 'Operasional RT',
                'deskripsi' => 'Pembelian buku kwitansi, tinta stempel, kertas surat, dan stopmap administrasi RT',
                'jumlah' => 175000.00,
                'tanggal' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'dicatat_oleh' => $bendahara->id,
            ],
        ];

        foreach ($transactions as $data) {
            CashTransaction::firstOrCreate(
                [
                    'deskripsi' => $data['deskripsi'],
                    'tanggal' => $data['tanggal'],
                ],
                $data
            );
        }
    }
}
