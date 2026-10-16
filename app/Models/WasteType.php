<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model WasteType untuk master katalog jenis sampah dan patokan harga per kg Bank Sampah Pinang Masak Lestari.
 */
class WasteType extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'waste_types';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'kategori', // 'plastik', 'kertas', 'logam', 'kaca', 'minyak_jelantah', 'elektronik', 'lainnya'
        'harga_per_kg',
        'satuan', // 'kg', 'liter', 'pcs'
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
            'harga_per_kg' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: Hanya jenis sampah yang aktif diterima Bank Sampah.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Relasi ke seluruh catatan setoran sampah jenis ini.
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(WasteDeposit::class, 'waste_type_id');
    }
}
