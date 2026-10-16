<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model LetterAttachment untuk berkas lampiran pendukung surat pengantar.
 */
class LetterAttachment extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'letter_attachments';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'letter_id',
        'nama_file',
        'file_path',
        'mime_type',
        'ukuran',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ukuran' => 'integer',
        ];
    }

    /**
     * Relasi ke surat pengantar (Letter).
     */
    public function letter(): BelongsTo
    {
        return $this->belongsTo(Letter::class);
    }
}
