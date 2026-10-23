<?php

namespace App\Filament\Pages;

use App\Models\Notification;
use App\Models\User;
use BackedEnum;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use UnitEnum;

class Notifications extends Page
{
    use WithPagination;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationLabel = 'Pusat Notifikasi';

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi Warga';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Pusat Notifikasi';

    protected static ?string $slug = 'notifikasi';

    protected string $view = 'filament.pages.notifications';

    protected Width|string|null $maxContentWidth = Width::Full;

    #[Url]
    public string $tab = 'all';

    public int $perPage = 15;

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->canAccessPanel();
    }

    public static function getNavigationBadge(): ?string
    {
        $userId = Auth::id();
        if (! $userId) {
            return null;
        }

        $count = Notification::where('user_id', $userId)->unread()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function updatedTab(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['all', 'unread', 'letter'], true) ? $tab : 'all';
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->tab = 'all';
        $this->resetPage();
    }

    public function getUnreadCountProperty(): int
    {
        $userId = Auth::id();

        return $userId ? Notification::where('user_id', $userId)->unread()->count() : 0;
    }

    public function getHasReadNotificationsProperty(): bool
    {
        $userId = Auth::id();

        return $userId ? Notification::where('user_id', $userId)->where('is_read', true)->exists() : false;
    }

    /**
     * Dapatkan daftar notifikasi berdasarkan filter tab (semua, belum dibaca, surat pengantar).
     */
    public function getNotificationsProperty(): LengthAwarePaginator
    {
        $userId = Auth::id();

        $query = Notification::where('user_id', $userId)->latest();

        if ($this->tab === 'unread') {
            $query->unread();
        } elseif ($this->tab === 'letter') {
            $query->where(function ($q) {
                $q->where('url', 'like', '%letters%')
                    ->orWhere('url', 'like', '%surat%')
                    ->orWhere('judul', 'like', '%Surat%');
            });
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Buka notifikasi, tandai telah dibaca, dan arahkan ke URL tujuan.
     */
    public function openNotification(int $id)
    {
        $notification = Notification::where('user_id', Auth::id())->find($id);

        if (! $notification) {
            return;
        }

        if (! $notification->is_read) {
            $notification->markAsRead();
        }

        $targetUrl = $notification->url ?: route('filament.admin.pages.dashboard');

        return redirect()->to($targetUrl);
    }

    /**
     * Tandai notifikasi tertentu sebagai telah dibaca.
     */
    public function markAsRead(int $id): void
    {
        $notification = Notification::where('user_id', Auth::id())->find($id);

        if ($notification) {
            $notification->markAsRead();

            FilamentNotification::make()
                ->title('Notifikasi ditandai dibaca')
                ->success()
                ->send();
        }
    }

    /**
     * Tandai notifikasi tertentu sebagai belum dibaca.
     */
    public function markAsUnread(int $id): void
    {
        $notification = Notification::where('user_id', Auth::id())->find($id);

        if ($notification) {
            $notification->update([
                'is_read' => false,
                'read_at' => null,
            ]);

            FilamentNotification::make()
                ->title('Notifikasi ditandai belum dibaca')
                ->info()
                ->send();
        }
    }

    /**
     * Tandai seluruh notifikasi pengurus sebagai telah dibaca.
     */
    public function markAllAsRead(): void
    {
        $userId = Auth::id();
        $updated = Notification::where('user_id', $userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if ($updated > 0) {
            FilamentNotification::make()
                ->title("{$updated} notifikasi berhasil ditandai telah dibaca")
                ->success()
                ->send();
        } else {
            FilamentNotification::make()
                ->title('Semua notifikasi sudah dalam status dibaca')
                ->info()
                ->send();
        }
    }

    /**
     * Hapus satu notifikasi.
     */
    public function deleteNotification(int $id): void
    {
        $notification = Notification::where('user_id', Auth::id())->find($id);

        if ($notification) {
            $notification->delete();

            FilamentNotification::make()
                ->title('Notifikasi berhasil dihapus')
                ->success()
                ->send();
        }
    }

    /**
     * Hapus seluruh notifikasi yang sudah dibaca untuk membersihkan riwayat.
     */
    public function deleteAllRead(): void
    {
        $userId = Auth::id();
        $deleted = Notification::where('user_id', $userId)
            ->where('is_read', true)
            ->delete();

        if ($deleted > 0) {
            FilamentNotification::make()
                ->title("{$deleted} riwayat notifikasi lama berhasil dibersihkan")
                ->success()
                ->send();
        } else {
            FilamentNotification::make()
                ->title('Tidak ada notifikasi terbaca yang dapat dibersihkan')
                ->info()
                ->send();
        }
    }

    /**
     * Klasifikasi kategori dan visual styling notifikasi.
     */
    public function getCategoryMeta(Notification $notif): array
    {
        $target = strtolower(($notif->url ?? '').' '.$notif->judul);

        if (str_contains($target, 'surat') || str_contains($target, 'letters')) {
            return [
                'name' => 'Surat Pengantar',
                'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                'icon_bg' => 'bg-blue-100 text-[#0B3A85]',
                'category' => 'letter',
            ];
        }

        if (str_contains($target, 'pengaduan') || str_contains($target, 'complaint')) {
            return [
                'name' => 'Pengaduan Warga',
                'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                'icon_bg' => 'bg-amber-100 text-amber-700',
                'category' => 'complaint',
            ];
        }

        if (str_contains($target, 'pembayaran') || str_contains($target, 'iuran') || str_contains($target, 'payment') || str_contains($target, 'invoice')) {
            return [
                'name' => 'Iuran & Keuangan',
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'icon_bg' => 'bg-emerald-100 text-emerald-700',
                'category' => 'payment',
            ];
        }

        if (str_contains($target, 'tamu') || str_contains($target, 'guest') || str_contains($target, 'patrol') || str_contains($target, 'keamanan')) {
            return [
                'name' => 'Keamanan & Tamu',
                'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'icon_bg' => 'bg-indigo-100 text-indigo-700',
                'category' => 'guest',
            ];
        }

        return [
            'name' => 'Aktivitas Sistem',
            'badge' => 'bg-slate-50 text-slate-700 border-slate-200',
            'icon_bg' => 'bg-slate-100 text-slate-600',
            'category' => 'general',
        ];
    }
}
