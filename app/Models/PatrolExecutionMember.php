<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PatrolExecutionMember untuk presensi individual petugas ronda pada malam pelaksanaan aktual.
 */
class PatrolExecutionMember extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'patrol_execution_members';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'patrol_execution_id',
        'resident_id',
        'status_kehadiran',
        'waktu_absen',
        'is_pengganti',
        'menggantikan_resident_id',
        'keterangan',
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
            'waktu_absen' => 'datetime',
        ];
    }

    /**
     * Relasi ke sesi pelaksanaan ronda (PatrolExecution).
     */
    public function execution(): BelongsTo
    {
        return $this->belongsTo(PatrolExecution::class, 'patrol_execution_id');
    }

    /**
     * Relasi ke warga yang bertugas / hadir.
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    /**
     * Relasi ke warga asli yang digantikan (jika petugas pengganti).
     */
    public function menggantikanResident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'menggantikan_resident_id');
    }
}
