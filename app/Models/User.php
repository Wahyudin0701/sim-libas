<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * Model User untuk SIM-LiBas.
 * Mendukung autentikasi, otorisasi berbasis peran (RBAC Spatie), dan pencatatan audit log.
 */
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Konfigurasi pencatatan log aktivitas Spatie.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Relasi ke data warga (Resident).
     */
    public function resident(): HasOne
    {
        return $this->hasOne(Resident::class);
    }

    /**
     * Relasi ke kartu keluarga (Household) melalui resident.
     */
    public function household(): HasOneThrough
    {
        return $this->hasOneThrough(
            Household::class,
            Resident::class,
            'user_id',       // Foreign key di tabel residents
            'id',            // Foreign key di tabel households
            'id',            // Local key di tabel users
            'household_id'   // Local key di tabel residents
        );
    }

    /**
     * Relasi ke pengaduan yang diajukan oleh user.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'user_id');
    }

    /**
     * Relasi ke pengaduan yang ditugaskan ke user ini.
     */
    public function assignedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'penanggung_jawab_id');
    }

    /**
     * Relasi ke kehadiran acara (RSVP).
     */
    public function eventAttendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class, 'user_id');
    }

    /**
     * Relasi ke notifikasi sistem (SIM-LiBas In-App Notifications).
     */
    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id')->latest();
    }

    /**
     * Dapatkan jumlah notifikasi yang belum dibaca.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->appNotifications()->where('is_read', false)->count();
    }

    /**
     * Helper: Cek apakah user adalah Admin / Super Admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin', 'Super Admin', 'Admin']);
    }

    /**
     * Helper: Cek apakah user adalah Ketua RT.
     */
    public function isKetuaRT(): bool
    {
        return $this->hasRole(['ketua_rt', 'Ketua RT']);
    }

    /**
     * Helper: Cek apakah user adalah Sekretaris.
     */
    public function isSekretaris(): bool
    {
        return $this->hasRole(['sekretaris', 'Sekretaris']);
    }

    /**
     * Helper: Cek apakah user adalah Bendahara.
     */
    public function isBendahara(): bool
    {
        return $this->hasRole(['bendahara', 'Bendahara']);
    }

    /**
     * Helper: Cek apakah user adalah Koordinator Bank Sampah.
     */
    public function isKoordinatorBankSampah(): bool
    {
        return $this->hasRole(['koordinator_bank_sampah', 'Koordinator Bank Sampah']);
    }

    /**
     * Helper: Cek apakah user adalah Koordinator Keamanan.
     */
    public function isKoordinatorKeamanan(): bool
    {
        return $this->hasRole(['koordinator_keamanan', 'Koordinator Keamanan']);
    }

    /**
     * Helper: Cek apakah user adalah Warga biasa.
     */
    public function isWarga(): bool
    {
        return $this->hasRole(['warga', 'Warga']);
    }

    /**
     * Otorisasi akses ke panel administrasi Filament.
     * Hanya pengurus RT dan admin yang berstatus aktif yang diizinkan masuk.
     */
    public function canAccessPanel(?Panel $panel = null): bool
    {
        if ($this->status !== 'aktif') {
            return false;
        }

        return $this->hasAnyRole([
            'super_admin',
            'admin',
            'ketua_rt',
            'sekretaris',
            'bendahara',
            'koordinator_bank_sampah',
            'koordinator_keamanan',
            'rw_kelurahan',
        ]);
    }

    /**
     * URL Avatar untuk Filament.
     * Mengembalikan URL foto jika diunggah, atau null agar fallback ke SimLibasAvatarProvider.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::disk('public')->url($this->avatar_url) : null;
    }
}
