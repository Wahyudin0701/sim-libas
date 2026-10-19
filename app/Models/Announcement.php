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
     * Scope: Pengumuman yang sudah berakhir (lewat periode tayang).
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('tanggal_berakhir')
            ->where('tanggal_berakhir', '<', now());
    }

    /**
     * Scope: Pengumuman yang terjadwal tayang di masa depan.
     */
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('tanggal_tayang')
            ->where('tanggal_tayang', '>', now());
    }

    /**
     * Hitung status dinamis pengumuman secara real-time berdasarkan waktu sekarang.
     * Mengembalikan: 'draft', 'terjadwal', 'tayang', atau 'berakhir'.
     */
    public function getStatusAttribute(): string
    {
        if (! $this->is_published) {
            return 'draft';
        }

        $now = now();

        if ($this->tanggal_tayang && $this->tanggal_tayang->isAfter($now)) {
            return 'terjadwal';
        }

        if ($this->tanggal_berakhir && $this->tanggal_berakhir->isBefore($now)) {
            return 'berakhir';
        }

        return 'tayang';
    }

    /**
     * Label teks status yang mudah dibaca untuk antarmuka pengguna.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'terjadwal' => 'Terjadwal',
            'tayang' => 'Sedang Tayang',
            'berakhir' => 'Sudah Berakhir',
            default => 'Draft',
        };
    }

    /**
     * Relasi ke pengurus/user yang membuat pengumuman.
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
