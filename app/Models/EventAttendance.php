<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model EventAttendance untuk konfirmasi kehadiran (RSVP) warga pada agenda kegiatan.
 */
class EventAttendance extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'event_attendances';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'status', // 'hadir', 'tidak_hadir', 'ragu'
    ];

    /**
     * Relasi ke agenda kegiatan (Event).
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relasi ke akun warga (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
