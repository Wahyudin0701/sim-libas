<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Model PatrolExecution untuk riwayat pelaksanaan dan presensi ronda malam pada tanggal aktual.
 */
class PatrolExecution extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'patrol_executions';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'patrol_schedule_id',
        'tanggal',
        'status',
        'laporan_kejadian',
        'alasan_batal',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /**
     * Relasi ke master jadwal ronda mingguan (PatrolSchedule).
     */
    public function patrolSchedule(): BelongsTo
    {
        return $this->belongsTo(PatrolSchedule::class, 'patrol_schedule_id');
    }

    /**
     * Relasi ke personil presensi aktual pelaksanaan ronda (PatrolExecutionMember).
     */
    public function members(): HasMany
    {
        return $this->hasMany(PatrolExecutionMember::class, 'patrol_execution_id');
    }

    /**
     * Relasi ke data warga (Resident) yang hadir/bertugas melalui members.
     */
    public function residents(): HasManyThrough
    {
        return $this->hasManyThrough(
            Resident::class,
            PatrolExecutionMember::class,
            'patrol_execution_id',
            'id',
            'id',
            'resident_id'
        );
    }
}
