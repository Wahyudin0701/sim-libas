<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model WasteDeposit untuk pencatatan penimbangan dan setoran sampah warga.
 */
class WasteDeposit extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'waste_deposits';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'waste_type_id',
        'berat_kg',
        'harga_saat_setor',
        'nilai',
        'catatan',
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
            'berat_kg' => 'decimal:3',
            'harga_saat_setor' => 'decimal:2',
            'nilai' => 'decimal:2',
        ];
    }

    /**
     * Relasi ke keluarga penyetor (Household).
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Relasi ke master jenis sampah (WasteType).
     */
    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class);
    }

    /**
     * Relasi ke petugas pengurus bank sampah yang mencatat setoran (User).
     */
    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
