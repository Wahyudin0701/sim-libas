<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model LetterType untuk mendefinisikan master jenis surat pengantar RT.
 */
class LetterType extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'letter_types';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'template_path',
        'format_nomor',
        'persyaratan',
        'is_active',
        'urutan',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'persyaratan' => 'array',
            'is_active' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    /**
     * Scope: Hanya jenis surat yang aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Relasi ke pengajuan surat dari jenis ini.
     */
    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class, 'letter_type_id');
    }
}
