<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    /**
     * Pastikan pengguna memiliki izin akses ke panel pengurus.
     */
    protected function authorizeAdmin(): User
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->canAccessPanel()) {
            abort(403, 'Akses notifikasi pengurus ditolak.');
        }

        return $user;
    }

    /**
     * Dapatkan daftar notifikasi in-app untuk pengurus yang sedang login.
     */
    public function index(): JsonResponse
    {
        $user = $this->authorizeAdmin();

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get()
            ->map(function (Notification $notif) {
                $category = 'general';
                $urlOrTitle = strtolower(($notif->url ?? '') . ' ' . $notif->judul);

                if (str_contains($urlOrTitle, 'surat') || str_contains($urlOrTitle, 'letters')) {
                    $category = 'letter';
                } elseif (str_contains($urlOrTitle, 'pengaduan') || str_contains($urlOrTitle, 'complaint')) {
                    $category = 'complaint';
                } elseif (str_contains($urlOrTitle, 'pembayaran') || str_contains($urlOrTitle, 'iuran') || str_contains($urlOrTitle, 'payment')) {
                    $category = 'payment';
                } elseif (str_contains($urlOrTitle, 'tamu') || str_contains($urlOrTitle, 'guest')) {
                    $category = 'guest';
                }

                return [
                    'id' => $notif->id,
                    'judul' => $notif->judul,
                    'isi' => $notif->isi,
                    'url' => $notif->url ?? route('filament.admin.pages.dashboard'),
                    'is_read' => (bool) $notif->is_read,
                    'category' => $category,
                    'time_ago' => $notif->created_at->diffForHumans(),
                ];
            });

        $unreadCount = Notification::where('user_id', $user->id)->unread()->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Tandai notifikasi pengurus tertentu sebagai telah dibaca.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $user = $this->authorizeAdmin();

        if ($notification->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak mengakses notifikasi ini.');
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => Notification::where('user_id', $user->id)->unread()->count(),
        ]);
    }

    /**
     * Tandai seluruh notifikasi pengurus yang belum dibaca sebagai telah dibaca.
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = $this->authorizeAdmin();

        Notification::where('user_id', $user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }
}
