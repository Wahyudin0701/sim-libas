<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Resident untuk data warga RT 15 Pematang Sulur.
 */
class Resident extends Model
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
    protected $table = 'residents';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_warga',
        'household_id',
        'kategori_warga',
        'status_tempat_tinggal',
        'alamat_domisili',
        'nik',
        'nik_encrypted',
        'nik_hash',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin', // 'L', 'P'
        'agama',
        'pendidikan_terakhir',
        'pekerjaan',
        'status_perkawinan',
        'hubungan_keluarga', // 'kepala_keluarga', 'istri', 'anak', dll
        'status_kependudukan', // 'aktif', 'pindah', 'meninggal', 'lahir_baru'
        'telepon',
        'email',
        'foto_path',
        'is_kepala_keluarga',
        'user_id',
    ];

    /**
     * Boot model untuk auto-generate id_warga (W***).
     */
    protected static function booted(): void
    {
        static::creating(function (Resident $resident) {
            if (empty($resident->id_warga)) {
                $resident->id_warga = static::generateNextIdWarga();
            }
        });
    }

    /**
     * Generate ID Warga berikutnya berformat W*** (misal: W001, W002, ...).
     */
    public static function generateNextIdWarga(): string
    {
        $latest = static::withTrashed()
            ->where('id_warga', 'LIKE', 'W%')
            ->orderByRaw('CAST(SUBSTRING(id_warga, 2) AS UNSIGNED) DESC')
            ->value('id_warga');

        $nextNumber = 1;
        if ($latest && preg_match('/^W(\d+)$/', $latest, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return sprintf('W%03d', $nextNumber);
    }

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'is_kepala_keluarga' => 'boolean',
        ];
    }

    /**
     * Mutator: Enkripsi NIK ke kolom nik_encrypted dan simpan SHA-256 di nik_hash.
     */
    public function setNikAttribute($value): void
    {
        if (!empty($value)) {
            try {
                $decrypted = Crypt::decryptString($value);
                $this->attributes['nik_encrypted'] = $value;
                $this->attributes['nik_hash'] = hash('sha256', $decrypted);
            } catch (\Exception $e) {
                $this->attributes['nik_encrypted'] = Crypt::encryptString($value);
                $this->attributes['nik_hash'] = hash('sha256', $value);
            }
        } else {
            $this->attributes['nik_encrypted'] = null;
            $this->attributes['nik_hash'] = null;
        }
    }

    /**
     * Accessor: Dekripsi NIK dari kolom nik_encrypted saat dibaca via $resident->nik.
     */
    public function getNikAttribute(): ?string
    {
        $encrypted = $this->attributes['nik_encrypted'] ?? null;
        if (empty($encrypted)) {
            return null;
        }

        try {
            return Crypt::decryptString($encrypted);
        } catch (\Exception $e) {
            return $encrypted;
        }
    }

    /**
     * Accessor: Masking NIK untuk privasi warga (contoh: 1571********0001).
     */
    public function getMaskedNikAttribute(): ?string
    {
        $nik = $this->nik;
        if (!$nik || strlen($nik) < 8) {
            return $nik;
        }

        return substr($nik, 0, 4) . str_repeat('*', max(0, strlen($nik) - 8)) . substr($nik, -4);
    }

    /**
     * Accessor: Tautan WhatsApp langsung (https://wa.me/628xxx).
     */
    public function getWaUrlAttribute(): ?string
    {
        if (empty($this->telepon)) {
            return null;
        }

        $clean = preg_replace('/[^0-9]/', '', $this->telepon);
        if (empty($clean)) {
            return null;
        }

        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (!str_starts_with($clean, '62')) {
            $clean = '62' . $clean;
        }

        return 'https://wa.me/' . $clean;
    }

    /**
     * Accessor: Alias nama lengkap warga.
     */
    public function getNamaLengkapAttribute(): ?string
    {
        return $this->nama;
    }

    /**
     * Scope: Hanya warga dengan status kependudukan aktif.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status_kependudukan', 'aktif');
    }

    /**
     * Relasi ke Kartu Keluarga (Household).
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Relasi ke akun User yang terhubung dengan warga ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke riwayat mutasi kependudukan.
     */
    public function mutations(): HasMany
    {
        return $this->hasMany(Mutation::class);
    }

    /**
     * Relasi ke seluruh surat pengantar yang diajukan oleh warga ini.
     */
    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }

    /**
     * Relasi ke penugasan jadwal ronda warga (PatrolMember).
     */
    public function patrolMembers(): HasMany
    {
        return $this->hasMany(PatrolMember::class);
    }

    /**
     * Relasi ke jadwal ronda master mingguan yang diikuti warga.
     */
    public function patrolSchedules(): HasManyThrough
    {
        return $this->hasManyThrough(
            PatrolSchedule::class,
            PatrolMember::class,
            'resident_id',
            'id',
            'id',
            'patrol_schedule_id'
        );
    }

    /**
     * Relasi ke riwayat pelaksanaan ronda aktual yang pernah diikuti warga.
     */
    public function patrolExecutions(): HasManyThrough
    {
        return $this->hasManyThrough(
            PatrolExecution::class,
            PatrolExecutionMember::class,
            'resident_id',
            'id',
            'id',
            'patrol_execution_id'
        );
    }
}
