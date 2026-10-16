<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Jenis-Jenis Surat Pengantar RT sesuai PRD SIM-LiBas
     */
    public function run(): void
    {
        $types = [
            [
                'nama' => 'Surat Pengantar Pembuatan KTP / KK',
                'kode' => 'SP-KTP',
                'deskripsi' => 'Pengantar permohonan penerbitan atau perubahan Kartu Tanda Penduduk / Kartu Keluarga ke Kelurahan.',
                'format_nomor' => '{NO}/SP-KTP/RT.15/{ROMAN_MONTH}/{YEAR}',
                'persyaratan' => ['Foto KTP Lama (jika ada)', 'Foto Kartu Keluarga', 'Pas Foto 3x4'],
                'is_active' => true,
                'urutan' => 1,
            ],
            [
                'nama' => 'Surat Keterangan Domisili Warga',
                'kode' => 'SKD',
                'deskripsi' => 'Keterangan bahwa yang bersangkutan berdomisili sah tinggal di lingkungan RT 15 Pematang Sulur.',
                'format_nomor' => '{NO}/SKD/RT.15/{ROMAN_MONTH}/{YEAR}',
                'persyaratan' => ['Foto Kartu Keluarga', 'Foto KTP Pemohon'],
                'is_active' => true,
                'urutan' => 2,
            ],
            [
                'nama' => 'Surat Pengantar SKCK',
                'kode' => 'SP-SKCK',
                'deskripsi' => 'Pengantar untuk pengurusan Surat Keterangan Catatan Kepolisian di Polsek / Polres.',
                'format_nomor' => '{NO}/SP-SKCK/RT.15/{ROMAN_MONTH}/{YEAR}',
                'persyaratan' => ['Foto KTP Pemohon', 'Foto Kartu Keluarga', 'Pas Foto Berlatar Merah'],
                'is_active' => true,
                'urutan' => 3,
            ],
            [
                'nama' => 'Surat Keterangan Usaha (SKU)',
                'kode' => 'SKU',
                'deskripsi' => 'Keterangan legalitas domisili usaha UMKM warga di wilayah RT 15.',
                'format_nomor' => '{NO}/SKU/RT.15/{ROMAN_MONTH}/{YEAR}',
                'persyaratan' => ['Foto KTP Pemilik Usaha', 'Foto KK', 'Foto Tempat & Kegiatan Usaha'],
                'is_active' => true,
                'urutan' => 4,
            ],
            [
                'nama' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'kode' => 'SKTM',
                'deskripsi' => 'Keterangan permohonan beasiswa, keringanan biaya pendidikan/kesehatan untuk keluarga prasejahtera.',
                'format_nomor' => '{NO}/SKTM/RT.15/{ROMAN_MONTH}/{YEAR}',
                'persyaratan' => ['Foto KTP', 'Foto KK', 'Foto Rumah/Kondisi Tempat Tinggal'],
                'is_active' => true,
                'urutan' => 5,
            ],
            [
                'nama' => 'Surat Pengantar Nikah (N1-N4)',
                'kode' => 'SP-NIKAH',
                'deskripsi' => 'Pengantar pengurusan pendaftaran pernikahan ke KUA atau Pencatatan Sipil.',
                'format_nomor' => '{NO}/SP-NIKAH/RT.15/{ROMAN_MONTH}/{YEAR}',
                'persyaratan' => ['Foto KTP Calon Pengantin', 'Foto KK', 'Foto Akta Lahir & Ijazah'],
                'is_active' => true,
                'urutan' => 6,
            ],
            [
                'nama' => 'Surat Keterangan Kematian',
                'kode' => 'SK-MATI',
                'deskripsi' => 'Keterangan warga yang meninggal dunia untuk pengurusan akta kematian dan keperluan dinas.',
                'format_nomor' => '{NO}/SK-MATI/RT.15/{ROMAN_MONTH}/{YEAR}',
                'persyaratan' => ['KTP Almarhum/Almarhumah', 'KK', 'Surat Keterangan Medis/Puskesmas (bila ada)'],
                'is_active' => true,
                'urutan' => 7,
            ],
            [
                'nama' => 'Surat Keterangan Serbaguna / Lainnya',
                'kode' => 'SK-LAIN',
                'deskripsi' => 'Keterangan pengantar umum untuk keperluan administrasi lainnya.',
                'format_nomor' => '{NO}/SK/RT.15/{ROMAN_MONTH}/{YEAR}',
                'persyaratan' => ['Foto KTP', 'Foto KK'],
                'is_active' => true,
                'urutan' => 8,
            ],
        ];

        foreach ($types as $type) {
            LetterType::updateOrCreate(['kode' => $type['kode']], $type);
        }
    }
}
