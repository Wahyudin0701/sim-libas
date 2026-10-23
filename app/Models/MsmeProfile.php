<?php

namespace App\Models;

use App\Services\ImageOptimizerService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Model MsmeProfile untuk profil usaha UMKM warga RT 15 Pematang Sulur.
 */
class MsmeProfile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'msme_profiles';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'nama_usaha',
        'deskripsi',
        'produk',
        'kategori', // 'kuliner', 'kerajinan', 'jasa', 'sembako', 'fashion', 'pertanian', 'lainnya'
        'foto_path',
        'alamat',
        'telepon',
        'whatsapp',
        'social_links', // json: ['whatsapp' => '...', 'instagram' => '...', 'tiktok' => '...', 'shopee' => '...']
        'status_moderasi', // 'pending', 'disetujui', 'ditolak'
        'catatan_moderasi',
        'is_active',
    ];

    /**
     * Konfigurasi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'social_links' => 'array',
        ];
    }

    /**
     * Booted event listener model untuk siklus hidup data & storage file.
     */
    protected static function booted(): void
    {
        // Otomatis sinkronisasi whatsapp column dan social_links['whatsapp']
        static::saving(function (MsmeProfile $msme) {
            $links = is_array($msme->social_links) ? $msme->social_links : [];

            // Jika whatsapp diisi tapi di social_links belum ada
            if (filled($msme->whatsapp) && empty($links['whatsapp'])) {
                $links['whatsapp'] = $msme->whatsapp;
            } elseif (! empty($links['whatsapp']) && empty($msme->whatsapp)) {
                $msme->whatsapp = $links['whatsapp'];
            }

            // Bersihkan platform kosong agar json tetap ringkas
            $cleaned = [];
            foreach ($links as $k => $v) {
                if (filled($v)) {
                    $cleaned[$k] = trim((string) $v);
                }
            }
            $msme->social_links = ! empty($cleaned) ? $cleaned : null;
        });

        // Hapus file lama saat foto_path diubah atau diperbarui
        static::updating(function (MsmeProfile $msme) {
            if ($msme->isDirty('foto_path')) {
                $oldPath = $msme->getOriginal('foto_path');
                if (filled($oldPath) && $oldPath !== $msme->foto_path) {
                    app(ImageOptimizerService::class)->deleteOldPhoto($oldPath);
                }
            }
        });

        // Hapus file foto dari disk saat data UMKM dihapus secara permanen atau soft delete
        static::deleted(function (MsmeProfile $msme) {
            if ($msme->isForceDeleting() && filled($msme->foto_path)) {
                app(ImageOptimizerService::class)->deleteOldPhoto($msme->foto_path);
            }
        });
    }

    /**
     * Scope: Hanya UMKM yang telah disetujui (approved) dan aktif.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereIn('status_moderasi', ['approved', 'disetujui'])
            ->where('is_active', true);
    }

    /**
     * Relasi ke data Kartu Keluarga pemilik usaha (Household).
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Cek apakah UMKM memiliki foto valid.
     */
    public function hasPhoto(): bool
    {
        return filled($this->foto_path) && Storage::disk('public')->exists($this->foto_path);
    }

    /**
     * URL foto yang dapat diakses publik.
     */
    public function getPhotoUrl(): ?string
    {
        if ($this->hasPhoto()) {
            return asset('storage/'.$this->foto_path);
        }

        return null;
    }

    /**
     * Cek apakah ada minimal 1 kontak / media sosial terisi.
     */
    public function hasSocialLinks(): bool
    {
        return ! empty($this->getAvailableSocialPlatforms());
    }

    /**
     * Ambil daftar nama platform yang aktif dan memiliki nilai.
     *
     * @return list<string> ['whatsapp', 'instagram', 'tiktok', 'shopee']
     */
    public function getAvailableSocialPlatforms(): array
    {
        $links = is_array($this->social_links) ? $this->social_links : [];
        $available = [];

        // WhatsApp bisa dari social_links atau kolom whatsapp / telepon
        $wa = $links['whatsapp'] ?? $this->whatsapp ?? $this->telepon ?? null;
        if (filled($wa)) {
            $available[] = 'whatsapp';
        }

        foreach (['instagram', 'tiktok', 'shopee', 'facebook', 'grabfood'] as $platform) {
            if (! empty($links[$platform]) && filled(trim($links[$platform]))) {
                $available[] = $platform;
            }
        }

        return array_values(array_unique($available));
    }

    /**
     * Mendapatkan URL tujuan yang dinormalisasi dan aman untuk platform tertentu.
     */
    public function getSocialUrl(string $platform): ?string
    {
        $platform = strtolower(trim($platform));
        $links = is_array($this->social_links) ? $this->social_links : [];

        switch ($platform) {
            case 'whatsapp':
                $rawNumber = $links['whatsapp'] ?? $this->whatsapp ?? $this->telepon ?? '';
                $clean = preg_replace('/[^0-9]/', '', (string) $rawNumber);
                if (empty($clean)) {
                    return null;
                }
                if (str_starts_with($clean, '0')) {
                    $clean = '62'.substr($clean, 1);
                } elseif (! str_starts_with($clean, '62') && strlen($clean) >= 9) {
                    $clean = '62'.$clean;
                }
                $pesan = urlencode("Halo {$this->nama_usaha}, saya warga RT 15 melihat profil usaha Anda di Portal SIM-LiBas. Apakah produk masih tersedia?");

                return "https://wa.me/{$clean}?text={$pesan}";

            case 'instagram':
                $raw = $links['instagram'] ?? '';
                if (empty($raw)) {
                    return null;
                }
                $handle = trim((string) $raw);
                if (preg_match('~instagram\.com/([^/?#]+)~i', $handle, $matches)) {
                    $handle = $matches[1];
                }
                $handle = ltrim($handle, '@');

                return filled($handle) ? "https://instagram.com/{$handle}" : null;

            case 'tiktok':
                $raw = $links['tiktok'] ?? '';
                if (empty($raw)) {
                    return null;
                }
                $handle = trim((string) $raw);
                if (preg_match('~tiktok\.com/@?([^/?#]+)~i', $handle, $matches)) {
                    $handle = $matches[1];
                }
                $handle = ltrim($handle, '@');

                return filled($handle) ? "https://www.tiktok.com/@{$handle}" : null;

            case 'shopee':
                $raw = $links['shopee'] ?? '';
                if (empty($raw)) {
                    return null;
                }
                $val = trim((string) $raw);
                if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
                    return $val;
                }
                $shop = ltrim($val, '@');

                return filled($shop) ? "https://shopee.co.id/{$shop}" : null;

            case 'facebook':
                $raw = $links['facebook'] ?? '';
                if (empty($raw)) {
                    return null;
                }
                $val = trim((string) $raw);
                if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
                    return $val;
                }
                $handle = ltrim($val, '@');

                return 'https://www.facebook.com/'.rawurlencode($handle);

            case 'grabfood':
                $raw = $links['grabfood'] ?? $links['marketplace'] ?? '';
                if (empty($raw)) {
                    return null;
                }
                $val = trim((string) $raw);
                if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
                    return $val;
                }
                if (strtolower($val) === 'grabfood') {
                    return 'https://food.grab.com/id/id/';
                }

                return 'https://food.grab.com/id/id/search?search='.rawurlencode($val);

            default:
                return null;
        }
    }

    /**
     * Mendapatkan label tampilan singkat (misal @tokoberkah atau 0812...).
     */
    public function getSocialLabel(string $platform): ?string
    {
        $platform = strtolower(trim($platform));
        $links = is_array($this->social_links) ? $this->social_links : [];

        switch ($platform) {
            case 'whatsapp':
                return $links['whatsapp'] ?? $this->whatsapp ?? $this->telepon ?? null;

            case 'instagram':
                $raw = $links['instagram'] ?? '';
                if (preg_match('~instagram\.com/([^/?#]+)~i', (string) $raw, $matches)) {
                    $raw = $matches[1];
                }
                $handle = ltrim(trim((string) $raw), '@');

                return filled($handle) ? "@{$handle}" : null;

            case 'tiktok':
                $raw = $links['tiktok'] ?? '';
                if (preg_match('~tiktok\.com/@?([^/?#]+)~i', (string) $raw, $matches)) {
                    $raw = $matches[1];
                }
                $handle = ltrim(trim((string) $raw), '@');

                return filled($handle) ? "@{$handle}" : null;

            case 'shopee':
                $raw = $links['shopee'] ?? '';
                if (preg_match('~shopee\.co\.id/([^/?#]+)~i', (string) $raw, $matches)) {
                    return $matches[1];
                }

                return filled($raw) ? trim((string) $raw) : null;

            case 'facebook':
                $raw = $links['facebook'] ?? '';
                if (preg_match('~facebook\.com/([^/?#]+)~i', (string) $raw, $matches)) {
                    return $matches[1];
                }

                return filled($raw) ? trim((string) $raw) : null;

            case 'grabfood':
                $raw = $links['grabfood'] ?? $links['marketplace'] ?? '';
                if (empty($raw)) {
                    return 'GrabFood';
                }
                if (preg_match('~food\.grab\.com/[^/]+/[^/]+/restaurant/([^/?#]+)~i', (string) $raw, $matches)) {
                    $slug = str_replace(['-', '_'], ' ', $matches[1]);
                    return ucwords($slug);
                }
                $val = trim((string) $raw);
                if (! str_starts_with($val, 'http://') && ! str_starts_with($val, 'https://') && strtolower($val) !== 'grabfood') {
                    return $val;
                }

                return 'GrabFood';

            default:
                return null;
        }
    }

    /**
     * Accessor & Mutator untuk kemudahan akses atribut Facebook.
     */
    public function getFacebookAttribute(): ?string
    {
        return $this->social_links['facebook'] ?? null;
    }

    public function setFacebookAttribute(?string $value): void
    {
        $links = is_array($this->social_links) ? $this->social_links : [];
        if (filled($value)) {
            $links['facebook'] = trim((string) $value);
        } else {
            unset($links['facebook']);
        }
        $this->social_links = ! empty($links) ? $links : null;
    }

    /**
     * Accessor & Mutator untuk kemudahan akses atribut GrabFood.
     */
    public function getGrabfoodAttribute(): ?string
    {
        return $this->social_links['grabfood'] ?? null;
    }

    public function setGrabfoodAttribute(?string $value): void
    {
        $links = is_array($this->social_links) ? $this->social_links : [];
        if (filled($value)) {
            $links['grabfood'] = trim((string) $value);
        } else {
            unset($links['grabfood']);
        }
        $this->social_links = ! empty($links) ? $links : null;
    }
}
