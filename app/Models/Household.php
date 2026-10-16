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

/**
 * Model Household untuk data Kartu Keluarga (KK) RT 15 Pematang Sulur.
 */
class Household extends Model
{
    use HasFactory, SoftDeletes;

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
        'no_kk',
        'no_kk_hash',
        'alamat',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'status_hunian', // 'tetap', 'kontrak', 'kos'
        'catatan',
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
