<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model WasteWalletTransaction untuk pencatatan mutasi kredit/debit tabungan bank sampah (tarik tunai, bayar iuran, dll).
 */
class WasteWalletTransaction extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'waste_wallet_transactions';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'jenis', // 'kredit' (bertambah dari setoran), 'debit' (penarikan/potong iuran)
        'jumlah',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
        'dicatat_oleh',
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
            'saldo_sebelum' => 'decimal:2',
            'saldo_sesudah' => 'decimal:2',
        ];
    }

    /**
     * Relasi ke data Kartu Keluarga (Household).
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Relasi ke pengurus yang mencatat transaksi (User).
     */
    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
