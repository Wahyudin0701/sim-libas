<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model PatrolSchedule untuk master jadwal ronda mingguan (7 hari tetap: Senin s.d. Minggu).
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
        'hari_ke',
        'regu',
        'waktu_mulai',
        'waktu_selesai',
        'pos_lokasi',
        'catatan',
        'is_aktif',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hari_ke' => 'integer',
            'is_aktif' => 'boolean',
        ];
    }

    /**
     * Accessor nama hari dalam bahasa Indonesia.
     */
    public function getNamaHariAttribute(): string
    {
        return match ((int) $this->hari_ke) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
            default => 'Hari ' . $this->hari_ke,
        };
    }

    /**
     * Indikator apakah jadwal ini berlaku hari ini.
     */
    public function getIsTodayAttribute(): bool
    {
        return (int) $this->hari_ke === (int) now()->dayOfWeekIso;
    }

    /**
     * Tanggal aktual pada minggu berjalan untuk hari jadwal ini.
     */
    public function getTanggalMingguIniAttribute(): string
    {
        return now()->startOfWeek()->addDays((int) $this->hari_ke - 1)->toDateString();
    }

    /**
     * Ketua regu yang bertugas.
     */
    public function getKetuaReguAttribute(): ?Resident
    {
        $member = $this->members->firstWhere('peran', 'ketua_regu') ?? $this->members->first();
        return $member?->resident;
    }

    /**
     * Relasi ke anggota personil tetap jadwal ronda (PatrolMember).
     */
    public function members(): HasMany
    {
        return $this->hasMany(PatrolMember::class, 'patrol_schedule_id');
    }

    /**
     * Relasi ke data warga (Resident) yang bertugas ronda melalui patrol_members.
     */
    public function residents(): HasManyThrough
    {
        return $this->hasManyThrough(
            Resident::class,
            PatrolMember::class,
            'patrol_schedule_id',
            'id',
            'id',
            'resident_id'
        );
    }

    /**
     * Relasi ke riwayat pelaksanaan ronda aktual (PatrolExecution).
     */
    public function executions(): HasMany
    {
        return $this->hasMany(PatrolExecution::class, 'patrol_schedule_id')->orderBy('tanggal', 'desc');
    }

    /**
     * Riwayat pelaksanaan ronda untuk tanggal hari ini.
     */
    public function todayExecution(): HasOne
    {
        return $this->hasOne(PatrolExecution::class, 'patrol_schedule_id')->whereDate('tanggal', today());
    }
}
