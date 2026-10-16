<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PatrolMember untuk anggota warga yang ditugaskan pada suatu jadwal ronda.
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
        'is_pengganti',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_pengganti' => 'boolean',
        ];
    }

    /**
     * Relasi ke jadwal ronda (PatrolSchedule).
     */
    public function patrolSchedule(): BelongsTo
    {
        return $this->belongsTo(PatrolSchedule::class);
    }

    /**
     * Relasi ke data warga (Resident).
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }
}
