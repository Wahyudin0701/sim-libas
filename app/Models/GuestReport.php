<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model GuestReport untuk pelaporan tamu menginap 1x24 jam atau pendatang di RT 15.
 */
class GuestReport extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'guest_reports';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'nama_tamu',
        'nik_tamu',
        'asal',
        'tujuan',
        'tanggal_datang',
        'tanggal_pulang',
        'foto_path',
        'dilaporkan_oleh',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_datang' => 'date',
            'tanggal_pulang' => 'date',
        ];
    }

    /**
     * Relasi ke keluarga tuan rumah (Household).
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Relasi ke user pelapor.
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }
}
