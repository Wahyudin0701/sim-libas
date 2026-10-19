<?php

namespace Database\Seeders;

use App\Models\Letter;
use App\Models\LetterType;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengisi sampel data permohonan surat pengantar warga RT 15 untuk berbagai status.
     */
    public function run(): void
    {
        $admin = User::first();
        $ketuaRt = User::where('email', 'rt@simlibas.com')->first() ?? $admin;

        $dedi = Resident::where('nama', 'like', '%Dedi%')->first();
        $siti = Resident::where('nama', 'like', '%Siti%')->first();
        $rizky = Resident::where('nama', 'like', '%Rizky%')->first();
        $agus = Resident::where('nama', 'like', '%Agus%')->first();

        $spKtp = LetterType::where('kode', 'SP-KTP')->first();
        $skd = LetterType::where('kode', 'SKD')->first();
        $spSkck = LetterType::where('kode', 'SP-SKCK')->first();
        $sku = LetterType::where('kode', 'SKU')->first();
        $sktm = LetterType::where('kode', 'SKTM')->first();

        $samples = [
            [
                'resident' => $rizky,
                'type' => $spSkck,
                'keperluan' => 'Persyaratan berkas pendaftaran program Magang Bersertifikat BUMN Batch 2',
                'status' => 'disetujui',
                'nomor_surat' => '001/SP-SKCK/RT.15/X/2026',
                'catatan_pengurus' => 'Berkas persyaratan KTP dan KK sudah lengkap dan sesuai.',
                'alasan_tolak' => null,
                'disetujui_oleh' => $ketuaRt?->id,
                'tanggal_disetujui' => now()->subDays(2),
                'created_at' => now()->subDays(3),
            ],
            [
                'resident' => $dedi,
                'type' => $sku,
                'keperluan' => 'Pengajuan pinjaman modal usaha KUR Mikro BRI untuk pengembangan Toko Kelontong RT 15',
                'status' => 'diajukan',
                'nomor_surat' => null,
                'catatan_pengurus' => null,
                'alasan_tolak' => null,
                'disetujui_oleh' => null,
                'tanggal_disetujui' => null,
                'created_at' => now()->subHours(5),
            ],
            [
                'resident' => $siti,
                'type' => $spKtp,
                'keperluan' => 'Permohonan penerbitan KTP-el baru dikarenakan KTP lama fisik retak/rusak',
                'status' => 'diperiksa',
                'nomor_surat' => null,
                'catatan_pengurus' => 'Sedang diverifikasi dokumen foto Kartu Keluarga oleh Sekretaris RT.',
                'alasan_tolak' => null,
                'disetujui_oleh' => null,
                'tanggal_disetujui' => null,
                'created_at' => now()->subDay(),
            ],
            [
                'resident' => $agus,
                'type' => $skd,
                'keperluan' => 'Keterangan domisili tinggal untuk pembukaan rekening tabungan Bank Mandiri Cabang Jambi',
                'status' => 'selesai',
                'nomor_surat' => '002/SKD/RT.15/X/2026',
                'catatan_pengurus' => 'Surat fisik asli telah diambil oleh pemohon di kediaman Ketua RT.',
                'alasan_tolak' => null,
                'disetujui_oleh' => $ketuaRt?->id,
                'tanggal_disetujui' => now()->subDays(4),
                'created_at' => now()->subDays(5),
            ],
            [
                'resident' => Resident::where('nama', 'like', '%Dewi%')->first(),
                'type' => $sktm,
                'keperluan' => 'Permohonan bantuan keringanan biaya pendidikan anak di sekolah negeri',
                'status' => 'diajukan',
                'nomor_surat' => null,
                'catatan_pengurus' => null,
                'alasan_tolak' => null,
                'disetujui_oleh' => null,
                'tanggal_disetujui' => null,
                'created_at' => now()->subHours(8),
            ],
            [
                'resident' => Resident::where('nama', 'like', '%Rina%')->first(),
                'type' => LetterType::where('kode', 'SP-NIKAH')->first(),
                'keperluan' => 'Pengantar pendaftaran pernikahan ke KUA Telanaipura',
                'status' => 'ditolak',
                'nomor_surat' => null,
                'catatan_pengurus' => 'Foto KTP dan Akta Kelahiran pemohon terpotong dan tidak terbaca jelas. Mohon unggah ulang.',
                'alasan_tolak' => 'Dokumen persyaratan foto KTP dan Akta Kelahiran buram/tidak terbaca.',
                'disetujui_oleh' => $ketuaRt?->id,
                'tanggal_disetujui' => now()->subDays(1),
                'created_at' => now()->subDays(2),
            ],
            [
                'resident' => Resident::where('nama', 'like', '%Joko%')->first(),
                'type' => LetterType::where('kode', 'SK-LAIN')->first(),
                'keperluan' => 'Surat keterangan pengantar perizinan kegiatan bazar lingkungan di area fasum RT 15',
                'status' => 'disetujui',
                'nomor_surat' => '003/SK/RT.15/X/2026',
                'catatan_pengurus' => 'Surat telah ditandatangani secara digital oleh Ketua RT 15.',
                'alasan_tolak' => null,
                'disetujui_oleh' => $ketuaRt?->id,
                'tanggal_disetujui' => now()->subHours(12),
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($samples as $item) {
            if ($item['resident'] && $item['type']) {
                Letter::updateOrCreate(
                    [
                        'resident_id' => $item['resident']->id,
                        'letter_type_id' => $item['type']->id,
                        'keperluan' => $item['keperluan'],
                    ],
                    [
                        'nomor_surat' => $item['nomor_surat'],
                        'status' => $item['status'],
                        'catatan_pengurus' => $item['catatan_pengurus'],
                        'alasan_tolak' => $item['alasan_tolak'],
                        'qr_token' => (string) Str::uuid(),
                        'disetujui_oleh' => $item['disetujui_oleh'],
                        'tanggal_disetujui' => $item['tanggal_disetujui'],
                        'created_at' => $item['created_at'],
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
