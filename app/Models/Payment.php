<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Payment untuk pencatatan pembayaran tagihan iuran RT.
 */
class Payment extends Model
{
    use HasFactory, LogsActivity;

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
    protected $table = 'payments';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invoice_id',
        'jumlah',
        'metode', // 'tunai', 'transfer', 'qris', 'bank_sampah'
        'bukti_path',
        'catatan',
        'tanggal_bayar',
        'divalidasi_oleh',
        'tanggal_validasi',
        'status', // 'menunggu_konfirmasi', 'diterima', 'ditolak'
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_bayar' => 'date',
            'tanggal_validasi' => 'datetime',
        ];
    }

    /**
     * Relasi ke tagihan (Invoice) yang dibayar.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relasi ke pengurus/bendahara yang memvalidasi pembayaran.
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }
}
