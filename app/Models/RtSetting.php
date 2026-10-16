<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RtSetting untuk konfigurasi umum RT 15 Pematang Sulur.
 * Bersifat singleton (umumnya hanya ada 1 baris data pengaturan).
 */
class RtSetting extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'rt_settings';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_rt',
        'nomor_rt',
        'nomor_rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'alamat_sekretariat',
        'nama_ketua_rt',
        'nik_ketua_rt',
        'telepon_ketua_rt',
        'email_rt',
        'ttd_ketua_rt_path',
        'cap_rt_path',
        'logo_path',
        'bank_nama',
        'bank_rekening',
        'bank_atas_nama',
        'saldo_kas_awal',
        'kontak_darurat',
        'informasi_tambahan',
    ];

    /**
     * Konfigurasi tipe data atribut (casts).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kontak_darurat' => 'array',
            'informasi_tambahan' => 'array',
            'saldo_kas_awal' => 'decimal:2',
        ];
    }

    /**
     * Helper untuk mengambil data pengaturan RT (singleton).
     */
    public static function instance(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'nama_rt' => 'RT 15 Pematang Sulur',
                'nomor_rt' => '015',
                'nomor_rw' => '004',
                'kelurahan' => 'Pematang Sulur',
                'kecamatan' => 'Telanaipura',
                'kota' => 'Kota Jambi',
                'provinsi' => 'Jambi',
                'kode_pos' => '36129',
            ]
        );
    }
}
