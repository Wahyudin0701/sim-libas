<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model FeeType untuk master jenis iuran RT (misal: kebersihan, keamanan, kas sosial).
 */
class FeeType extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'fee_types';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'deskripsi',
        'nominal',
        'periode', // 'bulanan', 'tahunan', 'sekali', 'kondisional'
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
            'nominal' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: Hanya jenis iuran yang berstatus aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Relasi ke seluruh tagihan yang diterbitkan berdasarkan jenis iuran ini.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'fee_type_id');
    }
}
