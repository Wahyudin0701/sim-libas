<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity as BaseActivity;

class AuditLog extends BaseActivity
{
    /**
     * Map model class ke nama modul ramah pengguna dalam Bahasa Indonesia.
     */
    public const MODULE_NAMES = [
        'App\Models\User' => 'Akun Pengguna',
        'App\Models\Household' => 'Kartu Keluarga (KK)',
        'App\Models\Resident' => 'Data Warga',
        'App\Models\Letter' => 'Surat Pengantar',
        'App\Models\LetterType' => 'Jenis Surat',
        'App\Models\CashTransaction' => 'Buku Kas RT',
        'App\Models\Payment' => 'Pembayaran Iuran',
        'App\Models\Invoice' => 'Tagihan Iuran',
        'App\Models\FeeType' => 'Jenis Iuran',
        'App\Models\Complaint' => 'Pengaduan Warga',
        'App\Models\Announcement' => 'Pengumuman',
        'App\Models\Event' => 'Agenda Kegiatan',
        'App\Models\GuestReport' => 'Lapor Tamu 1x24 Jam',
        'App\Models\PatrolSchedule' => 'Jadwal Ronda',
        'App\Models\WasteDeposit' => 'Setoran Bank Sampah',
        'App\Models\WasteWallet' => 'Tabungan Sampah',
        'App\Models\MsmeProfile' => 'Katalog UMKM Warga',
        'App\Models\RtSetting' => 'Pengaturan Profil RT',
        'Spatie\Permission\Models\Role' => 'Peran (Role)',
        'Spatie\Permission\Models\Permission' => 'Hak Akses (Permission)',
    ];

    /**
     * Label aksi dalam Bahasa Indonesia.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->event) {
            'created' => 'Data Dibuat',
            'updated' => 'Data Diubah',
            'deleted' => 'Data Dihapus',
            'restored' => 'Data Dipulihkan',
            'login' => 'Masuk Sistem',
            'logout' => 'Keluar Sistem',
            default => ucfirst((string) ($this->event ?? $this->description ?? 'Aktivitas')),
        };
    }

    /**
     * Kelas badge warna Tailwind sesuai jenis aksi/event.
     */
    public function getActionBadgeColorAttribute(): string
    {
        return match ($this->event) {
            'created' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'updated' => 'bg-blue-50 text-blue-700 border-blue-200',
            'deleted' => 'bg-rose-50 text-rose-700 border-rose-200',
            'restored' => 'bg-purple-50 text-purple-700 border-purple-200',
            'login' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'logout' => 'bg-slate-100 text-slate-700 border-slate-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    /**
     * Nama modul Bahasa Indonesia.
     */
    public function getModuleNameAttribute(): string
    {
        if (empty($this->subject_type)) {
            return 'Sistem / Umum';
        }

        return self::MODULE_NAMES[$this->subject_type] ?? class_basename($this->subject_type);
    }

    /**
     * Format tanggal dan jam ramah Indonesia.
     */
    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->created_at)->locale('id')->translatedFormat('d M Y, H:i:s') . ' WIB';
    }

    /**
     * Waktu relatif (e.g. 5 menit yang lalu).
     */
    public function getRelativeTimeAttribute(): string
    {
        return Carbon::parse($this->created_at)->locale('id')->diffForHumans();
    }

    /**
     * Nama pelaku tindakan (Causer).
     */
    public function getCauserNameAttribute(): string
    {
        if ($this->causer) {
            return $this->causer->name ?? 'Pengguna #' . $this->causer_id;
        }

        return 'Sistem Otomatis';
    }

    /**
     * Email pelaku tindakan.
     */
    public function getCauserEmailAttribute(): ?string
    {
        return $this->causer?->email;
    }

    /**
     * Peran utama pelaku tindakan.
     */
    public function getCauserRoleAttribute(): string
    {
        if (!$this->causer) {
            return 'Sistem';
        }

        /** @var \App\Models\User $causer */
        $causer = $this->causer;
        $role = $causer->roles->first();

        if (!$role) {
            return 'Pengguna';
        }

        return match ($role->name) {
            'super_admin' => 'Super Admin',
            'ketua_rt' => 'Ketua RT',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            'koordinator_bank_sampah' => 'Koor. Bank Sampah',
            'koordinator_keamanan' => 'Koor. Keamanan',
            'rw_kelurahan' => 'RW / Kelurahan',
            'warga' => 'Warga',
            default => ucfirst(str_replace('_', ' ', $role->name)),
        };
    }

    /**
     * Dapatkan data atribut baru dan lama secara aman (password disensor).
     */
    public function getSafeProperties(): array
    {
        $properties = $this->properties ? $this->properties->toArray() : [];
        $sensitiveKeys = ['password', 'remember_token', 'token', 'secret'];

        $maskSensitive = function (array $data) use (&$maskSensitive, $sensitiveKeys): array {
            $cleaned = [];
            foreach ($data as $key => $value) {
                if (in_array(strtolower((string) $key), $sensitiveKeys, true)) {
                    $cleaned[$key] = '•••••••• (Dirahasiakan demi keamanan)';
                } elseif (is_array($value)) {
                    $cleaned[$key] = $maskSensitive($value);
                } else {
                    $cleaned[$key] = $value;
                }
            }
            return $cleaned;
        };

        if (isset($properties['attributes']) && is_array($properties['attributes'])) {
            $properties['attributes'] = $maskSensitive($properties['attributes']);
        }

        if (isset($properties['old']) && is_array($properties['old'])) {
            $properties['old'] = $maskSensitive($properties['old']);
        }

        return $properties;
    }

    /**
     * Scope untuk pencarian.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $q) use ($term) {
            $q->where('description', 'like', "%{$term}%")
              ->orWhere('event', 'like', "%{$term}%")
              ->orWhere('subject_type', 'like', "%{$term}%")
              ->orWhere('id', $term)
              ->orWhereHasMorph('causer', [User::class], function (Builder $userQuery) use ($term) {
                  $userQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%");
              });
        });
    }
}
