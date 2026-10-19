<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Household untuk data Kartu Keluarga (KK) RT 15 Pematang Sulur.
 */
class Household extends Model
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
    protected $table = 'households';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kk',
        'no_kk',
        'no_kk_hash',
        'alamat',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'status_hunian', // 'tetap', 'kontrak', 'kos'
        'catatan',
        'foto_kk',
        'user_id',
    ];

    /**
     * Boot model untuk auto-generate id_kk (KK***).
     */
    protected static function booted(): void
    {
        static::creating(function (Household $household) {
            if (empty($household->id_kk)) {
                $household->id_kk = static::generateNextIdKk();
            }
        });
    }

    /**
     * Generate ID KK berikutnya berformat KK*** (misal: KK001, KK002, ...).
     */
    public static function generateNextIdKk(): string
    {
        $latest = static::withTrashed()
            ->where('id_kk', 'LIKE', 'KK%')
            ->orderByRaw('CAST(SUBSTRING(id_kk, 3) AS UNSIGNED) DESC')
            ->value('id_kk');

        $nextNumber = 1;
        if ($latest && preg_match('/^KK(\d+)$/', $latest, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return sprintf('KK%03d', $nextNumber);
    }

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_hunian' => 'string',
        ];
    }

    /**
     * Mutator: Enkripsi no_kk dan simpan hash SHA-256 untuk pencarian.
     */
    public function setNoKkAttribute($value): void
    {
        if (!empty($value)) {
            try {
                // Periksa apakah value sudah berupa ciphertext terenkripsi
                $decrypted = Crypt::decryptString($value);
                $this->attributes['no_kk'] = $value;
                $this->attributes['no_kk_hash'] = hash('sha256', $decrypted);
            } catch (\Exception $e) {
                // Enkripsi teks mentah nomor KK
                $this->attributes['no_kk'] = Crypt::encryptString($value);
                $this->attributes['no_kk_hash'] = hash('sha256', $value);
            }
        } else {
            $this->attributes['no_kk'] = $value;
            $this->attributes['no_kk_hash'] = null;
        }
    }

    /**
     * Accessor: Dekripsi no_kk saat dibaca.
     */
    public function getNoKkAttribute($value): ?string
    {
        if (empty($value)) {
            return $value;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Accessor: Masking nomor KK untuk privasi (contoh: 1571********0001).
     */
    public function getMaskedNoKkAttribute(): ?string
    {
        $kk = $this->no_kk;
        if (!$kk || strlen($kk) < 8) {
            return $kk;
        }

        return substr($kk, 0, 4) . str_repeat('*', max(0, strlen($kk) - 8)) . substr($kk, -4);
    }

    /**
     * Accessor: URL foto KK dari public storage.
     */
    public function getFotoKkUrlAttribute(): ?string
    {
        return $this->foto_kk ? asset('storage/' . $this->foto_kk) : null;
    }

    /**
     * Relasi ke seluruh anggota keluarga (residents).
     */
    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    /**
     * Relasi ke Kepala Keluarga (resident dengan is_kepala_keluarga = true).
     */
    public function kepalaKeluarga(): HasOne
    {
        return $this->hasOne(Resident::class)->where('is_kepala_keluarga', true);
    }

    /**
     * Alias relasi Kepala Keluarga.
     */
    public function headOfHousehold(): HasOne
    {
        return $this->kepalaKeluarga();
    }

    /**
     * Accessor alias nomor_kk.
     */
    public function getNomorKkAttribute(): ?string
    {
        return $this->no_kk;
    }

    /**
     * Accessor alias alamat_lengkap.
     */
    public function getAlamatLengkapAttribute(): ?string
    {
        return $this->alamat;
    }

    /**
     * Relasi ke akun User pengelola KK ini (opsional / kepala keluarga).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tagihan iuran keluarga.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Relasi ke pembayaran iuran melalui tagihan.
     */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Invoice::class);
    }

    /**
     * Relasi ke riwayat setoran Bank Sampah.
     */
    public function wasteDeposits(): HasMany
    {
        return $this->hasMany(WasteDeposit::class);
    }

    /**
     * Relasi ke buku saldo Bank Sampah keluarga.
     */
    public function wasteWallet(): HasOne
    {
        return $this->hasOne(WasteWallet::class);
    }

    /**
     * Relasi ke profil usaha UMKM warga.
     */
    public function msmeProfile(): HasOne
    {
        return $this->hasOne(MsmeProfile::class);
    }

    /**
     * Relasi ke seluruh laporan tamu yang dilaporkan oleh KK ini.
     */
    public function guestReports(): HasMany
    {
        return $this->hasMany(GuestReport::class);
    }
}
