<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model MsmeProfile untuk profil usaha UMKM warga RT 15 Pematang Sulur.
 */
class MsmeProfile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'msme_profiles';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'nama_usaha',
        'deskripsi',
        'produk',
        'kategori', // 'kuliner', 'kerajinan', 'jasa', 'sembako', 'fashion', 'pertanian', 'lainnya'
        'foto_path',
        'alamat',
        'telepon',
        'whatsapp',
        'status_moderasi', // 'menunggu', 'approved', 'rejected'
        'catatan_moderasi',
        'is_active',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: Hanya UMKM yang telah disetujui (approved) dan aktif.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereIn('status_moderasi', ['approved', 'disetujui'])
            ->where('is_active', true);
    }

    /**
     * Relasi ke data Kartu Keluarga pemilik usaha (Household).
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }
}
