<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Letter untuk data permohonan surat pengantar warga RT 15.
 */
class Letter extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'letters';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nomor_surat',
        'letter_type_id',
        'resident_id',
        'keperluan',
        'data_tambahan',
        'status', // 'diajukan', 'diperiksa', 'disetujui', 'ditolak'
        'catatan_pengurus',
        'alasan_tolak',
        'pdf_path',
        'qr_token',
        'diverifikasi_oleh',
        'disetujui_oleh',
        'tanggal_disetujui',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data_tambahan' => 'array',
            'tanggal_disetujui' => 'datetime',
        ];
    }

    /**
     * Boot logic model untuk mengenerate qr_token unik otomatis.
     */
    protected static function booted(): void
    {
        static::creating(function (Letter $letter) {
            if (empty($letter->qr_token)) {
                $letter->qr_token = (string) Str::uuid();
            }
        });
    }

    /**
     * Konfigurasi Spatie Activitylog untuk audit trail surat.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Relasi ke jenis surat (LetterType).
     */
    public function letterType(): BelongsTo
    {
        return $this->belongsTo(LetterType::class, 'letter_type_id');
    }

    /**
     * Relasi ke warga pemohon (Resident).
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Relasi ke berkas lampiran pendukung surat.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(LetterAttachment::class);
    }

    /**
     * Relasi ke pengurus yang memverifikasi surat.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    /**
     * Relasi ke Ketua RT / pengurus yang menyetujui surat.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
