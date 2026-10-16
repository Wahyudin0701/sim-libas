<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Announcement untuk pengumuman informasi RT 15 kepada warga.
 */
class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'announcements';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'judul',
        'isi',
        'target', // 'semua', 'tetap', 'kontrak', 'pengurus'
        'lampiran_path',
        'is_pinned',
        'is_published',
        'tanggal_tayang',
        'tanggal_berakhir',
        'dibuat_oleh',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_published' => 'boolean',
            'tanggal_tayang' => 'datetime',
            'tanggal_berakhir' => 'datetime',
        ];
    }

    /**
     * Scope: Pengumuman yang sudah dipublikasikan.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Pengumuman aktif yang sedang dalam periode tayang.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('tanggal_tayang')
                    ->orWhere('tanggal_tayang', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('tanggal_berakhir')
                    ->orWhere('tanggal_berakhir', '>=', now());
            });
    }

    /**
     * Relasi ke pengurus/user yang membuat pengumuman.
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
