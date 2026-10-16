<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

/**
 * Model Resident untuk data warga RT 15 Pematang Sulur.
 */
class Resident extends Model
{
    use HasFactory, SoftDeletes;

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
        'household_id',
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
}
