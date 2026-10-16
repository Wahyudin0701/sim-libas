<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Event untuk kalender kegiatan/agenda warga RT 15.
 */
class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'judul',
        'deskripsi',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'kategori', // 'gotong_royong', 'rapat_rt', 'posyandu', 'keagamaan', '17_agustus', 'lainnya'
        'is_recurring',
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
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'is_recurring' => 'boolean',
        ];
    }

    /**
     * Relasi ke pengurus/user pembuat agenda.
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Relasi ke daftar RSVP kehadiran agenda.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    /**
     * Relasi ke peserta yang hadir (User) melalui tabel attendances.
     */
    public function attendees(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            EventAttendance::class,
            'event_id', // Foreign key di tabel event_attendances
            'id',       // Foreign key di tabel users
            'id',       // Local key di tabel events
            'user_id'   // Local key di tabel event_attendances
        );
    }
}
