<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Invoice untuk tagihan iuran keluarga/KK.
 */
class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'fee_type_id',
        'periode_bulan',
        'periode_tahun',
        'jumlah',
        'status', // 'belum_lunas', 'menunggu_verifikasi', 'lunas', 'dibatalkan'
        'tanggal_jatuh_tempo',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'periode_bulan' => 'integer',
            'periode_tahun' => 'integer',
            'jumlah' => 'decimal:2',
            'tanggal_jatuh_tempo' => 'date',
        ];
    }

    /**
     * Accessor: Menampilkan label periode yang mudah dibaca (misal: 'Oktober 2026').
     */
    public function getPeriodeLabelAttribute(): string
    {
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $namaBulan = $bulanIndo[(int) $this->periode_bulan] ?? (string) $this->periode_bulan;

        return "{$namaBulan} {$this->periode_tahun}";
    }

    /**
     * Scope: Tagihan yang belum lunas (belum_lunas atau menunggu_verifikasi).
     */
    public function scopeBelumLunas(Builder $query): Builder
    {
        return $query->whereIn('status', ['belum_lunas', 'menunggu_verifikasi']);
    }

    /**
     * Relasi ke data Kartu Keluarga (Household).
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Relasi ke master jenis iuran (FeeType).
     */
    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class);
    }

    /**
     * Relasi ke riwayat pembayaran tagihan ini.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
