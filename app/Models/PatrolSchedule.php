<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Model PatrolSchedule untuk jadwal ronda malam / poskamling warga RT 15.
 */
class PatrolSchedule extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'patrol_schedules';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tanggal',
        'regu',
        'waktu_mulai',
        'waktu_selesai',
        'catatan',
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
     * Relasi ke anggota daftar ronda (PatrolMember).
     */
    public function members(): HasMany
    {
        return $this->hasMany(PatrolMember::class);
    }

    /**
     * Relasi ke data warga (Resident) yang bertugas ronda melalui patrol_members.
     */
    public function residents(): HasManyThrough
    {
        return $this->hasManyThrough(
            Resident::class,
            PatrolMember::class,
            'patrol_schedule_id', // Foreign key di tabel patrol_members
            'id',                 // Foreign key di tabel residents
            'id',                 // Local key di tabel patrol_schedules
            'resident_id'         // Local key di tabel patrol_members
        );
    }
}
