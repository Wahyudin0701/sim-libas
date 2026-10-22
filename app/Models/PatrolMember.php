<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PatrolMember untuk personil/petugas tetap jadwal ronda mingguan RT 15.
 */
class PatrolMember extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'patrol_members';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'patrol_schedule_id',
        'resident_id',
        'peran',
    ];

    /**
     * Relasi ke jadwal ronda master (PatrolSchedule).
     */
    public function patrolSchedule(): BelongsTo
    {
        return $this->belongsTo(PatrolSchedule::class, 'patrol_schedule_id');
    }

    /**
     * Relasi ke data warga (Resident) yang bertugas.
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }
}
