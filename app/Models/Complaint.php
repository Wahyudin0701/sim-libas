<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Complaint untuk layanan pengaduan dan aspirasi warga RT 15.
 */
class Complaint extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'complaints';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'kategori', // 'kebersihan', 'keamanan', 'ketertiban', 'fasilitas', 'sosial', 'lainnya'
        'judul',
        'deskripsi',
        'foto_path',
        'lokasi',
        'is_anonim',
        'status', // 'menunggu', 'diproses', 'selesai', 'ditolak'
        'catatan_penanganan',
        'penanggung_jawab_id',
        'tanggal_selesai',
        'email_sent_at',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_anonim' => 'boolean',
            'tanggal_selesai' => 'datetime',
            'email_sent_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke warga pelapor (User).
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias relasi ke warga pelapor (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke pengurus yang menangani pengaduan (User).
     */
    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }
}
