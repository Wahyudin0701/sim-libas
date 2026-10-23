<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Complaint;
use App\Models\Event;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\Letter;
use App\Models\LetterType;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\RtSetting;
use App\Models\User;
use App\Models\WasteWallet;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CitizenPortalController extends Controller
{
    /**
     * Dapatkan data Kartu Keluarga milik user yang sedang login
     */
    protected function getHousehold()
    {
        $user = Auth::user();
        $resident = $user->resident;

        if ($resident && $resident->household) {
            return $resident->household;
        }

        return Household::where('user_id', $user->id)->first();
    }

    /**
     * Dashboard Utama Warga (FR-DSH-02)
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        $household = $this->getHousehold();
        if ($household) {
            $household->loadMissing([
                'residents' => function ($query) {
                    $query->orderBy('is_kepala_keluarga', 'desc')->orderBy('id_warga');
                },
                'kepalaKeluarga',
            ]);
        }
        $resident = $user->resident ?? $household?->kepalaKeluarga;

        // Tagihan belum bayar
        $invoices = $household ? Invoice::where('household_id', $household->id)
            ->where('status', 'belum_bayar')
            ->with('feeType')
            ->get() : collect();

        $totalTagihan = $invoices->sum('jumlah');

        // Status surat terakhir
        $latestLetter = $resident ? Letter::where('resident_id', $resident->id)
            ->with('letterType')
            ->latest('created_at')
            ->first() : null;

        // Saldo bank sampah
        $wallet = $household ? WasteWallet::where('household_id', $household->id)->first() : null;
        $saldoBankSampah = $wallet?->saldo ?? 0.00;

        // Pengumuman & Agenda Aktif (Hanya yang sedang dalam periode tayang aktif)
        $pengumuman = Announcement::active()
            ->where('target', '!=', 'pengurus')
            ->orderByDesc('is_pinned')
            ->latest('created_at')
            ->take(3)
            ->get();
        $agenda = Event::where('tanggal_mulai', '>=', now())->orderBy('tanggal_mulai')->take(2)->get();

        return view('warga.dashboard', compact(
            'user',
            'household',
            'resident',
            'invoices',
            'totalTagihan',
            'latestLetter',
            'saldoBankSampah',
            'pengumuman',
            'agenda'
        ));
    }

    /**
     * Halaman Khusus Detail Kartu Keluarga Warga
     */
    public function kartuKeluarga(): View
    {
        $user = Auth::user();
        $household = $this->getHousehold();

        if ($household) {
            $household->loadMissing([
                'residents' => function ($query) {
                    $query->orderBy('is_kepala_keluarga', 'desc')->orderBy('id_warga');
                },
                'kepalaKeluarga',
            ]);
        }

        return view('warga.kartu-keluarga', compact('user', 'household'));
    }

    /**
     * Halaman Khusus Profil Akun Warga
     */
    public function profile(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $resident = $user->resident;
        $household = $this->getHousehold();

        return view('warga.profile', compact('user', 'resident', 'household'));
    }

    /**
     * Perbarui Alamat Email Akun Warga
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'current_password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email baru wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar pada akun lain.',
            'current_password.required' => 'Kata sandi saat ini wajib diisi untuk verifikasi keamanan.',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.'])
                ->with('error_modal', 'modal-email');
        }

        $user->email = $validated['email'];
        $user->save();

        if ($user->resident) {
            $user->resident->update(['email' => $validated['email']]);
        }

        return back()->with('success', 'Alamat email akun berhasil diperbarui menjadi '.$validated['email'].'.');
    }

    /**
     * Perbarui Kata Sandi Akun Warga
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.'])
                ->with('error_modal', 'modal-password');
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Kata sandi akun Anda berhasil diperbarui. Silakan gunakan kata sandi baru untuk login berikutnya.');
    }

    /**
     * Perbarui Nomor Telepon / WhatsApp Akun Warga
     */
    public function updatePhone(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\-\+\s\(\)]+$/'],
        ], [
            'phone.required' => 'Nomor telepon / WhatsApp baru wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan angka dan karakter pemisah standar.',
        ]);

        $cleanPhone = preg_replace('/\s+/', '', $validated['phone']);
        $user->phone = $cleanPhone;
        $user->save();

        if ($user->resident) {
            $user->resident->update(['telepon' => $cleanPhone]);
        }

        return back()->with('success', 'Nomor telepon / WhatsApp berhasil diperbarui menjadi '.$cleanPhone.'.');
    }

    /**
     * Halaman Layanan Surat Pengantar Warga
     */
    public function letters(): View
    {
        $user = Auth::user();
        $household = $this->getHousehold();
        $resident = $user->resident ?? $household?->kepalaKeluarga;

        $residentIds = $household ? $household->residents->pluck('id') : ($resident ? [$resident->id] : []);

        $letters = Letter::whereIn('resident_id', $residentIds)
            ->with(['letterType', 'resident'])
            ->latest()
            ->paginate(10);

        $letterTypes = LetterType::where('is_active', true)->orderBy('urutan')->get();

        $isFamilyHeadOrSpouse = false;
        if ($user->resident) {
            $isFamilyHeadOrSpouse = $user->resident->is_kepala_keluarga || in_array($user->resident->hubungan_keluarga, ['kepala_keluarga', 'istri']);
        } elseif ($household && $household->user_id === $user->id) {
            $isFamilyHeadOrSpouse = true;
        }

        return view('warga.surat', compact('letters', 'letterTypes', 'household', 'resident', 'isFamilyHeadOrSpouse'));
    }

    /**
     * Simpan Pengajuan Surat Pengantar Baru
     */
    public function storeLetter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'letter_type_id' => ['required', 'exists:letter_types,id'],
            'resident_id' => ['required', 'exists:residents,id'],
            'keperluan' => ['required', 'string', 'min:3', 'max:1000'],
        ], [
            'letter_type_id.required' => 'Pilih jenis surat pengantar yang ingin diajukan.',
            'letter_type_id.exists' => 'Jenis surat pengantar yang dipilih tidak valid.',
            'resident_id.required' => 'Pilih nama warga pemohon.',
            'resident_id.exists' => 'Data warga pemohon tidak ditemukan di sistem.',
            'keperluan.required' => 'Keperluan permohonan surat wajib diisi.',
            'keperluan.min' => 'Keperluan permohonan surat minimal harus diisi 3 karakter.',
            'keperluan.max' => 'Keperluan permohonan surat maksimal 1000 karakter.',
        ]);

        $user = Auth::user();
        $household = $this->getHousehold();
        $resident = $user->resident;

        $allowedResidentIds = $household ? $household->residents->pluck('id')->toArray() : [];
        if ($resident) {
            $allowedResidentIds[] = $resident->id;
        }

        if (! in_array((int) $validated['resident_id'], $allowedResidentIds)) {
            return back()->withErrors(['resident_id' => 'Warga yang dipilih tidak berada dalam Kartu Keluarga Anda.']);
        }

        $targetResident = Resident::find($validated['resident_id']);
        if (! $targetResident || $targetResident->status_kependudukan !== 'aktif') {
            return back()->withErrors(['resident_id' => 'Permohonan surat tidak dapat diajukan untuk warga berstatus kependudukan '.($targetResident ? $targetResident->status_kependudukan : 'tidak aktif').'.']);
        }

        // Pembeda peran status keluarga:
        // Jika user adalah anggota keluarga mandiri (bukan kepala keluarga & bukan istri), batasi pengajuan hanya untuk dirinya sendiri
        $isFamilyHeadOrSpouse = false;
        if ($resident) {
            $isFamilyHeadOrSpouse = $resident->is_kepala_keluarga || in_array($resident->hubungan_keluarga, ['kepala_keluarga', 'istri']);
        } elseif ($household && $household->user_id === $user->id) {
            $isFamilyHeadOrSpouse = true;
        }

        if (! $isFamilyHeadOrSpouse && $resident && (int) $validated['resident_id'] !== $resident->id) {
            return back()->withErrors([
                'resident_id' => 'Sebagai anggota keluarga, Anda hanya dapat mengajukan permohonan surat untuk diri Anda sendiri. Permohonan untuk anggota keluarga lain dilakukan oleh Kepala Keluarga atau Istri.',
            ]);
        }

        $letter = Letter::create([
            'letter_type_id' => $validated['letter_type_id'],
            'resident_id' => $validated['resident_id'],
            'keperluan' => $validated['keperluan'],
            'status' => 'diajukan',
            'qr_token' => bin2hex(random_bytes(16)),
        ]);

        // Kirim notifikasi ke pengurus terkait (Sekretaris, Ketua RT, Super Admin)
        NotificationService::notifyNewLetter($letter);

        return redirect()->route('warga.surat')->with('success', 'Permohonan surat pengantar berhasil dikirim! Menunggu persetujuan pengurus RT.');
    }

    /**
     * Cetak Lembar Resmi Surat Pengantar RT 15 via Stream PDF untuk Pratinjau Native Browser
     */
    public function printLetter(Request $request, Letter $letter): Response|View
    {
        $user = Auth::user();

        // Otorisasi: hanya Admin atau warga bersangkutan yang berhak mencetak
        $adminPanel = Filament::getPanel('admin') ?? Filament::getDefaultPanel();
        $isAdmin = $user->canAccessPanel($adminPanel);
        $isOwner = false;

        if (! $isAdmin) {
            $household = $this->getHousehold();
            $allowedResidentIds = $household ? $household->residents->pluck('id')->toArray() : [];
            if ($user->resident) {
                $allowedResidentIds[] = $user->resident->id;
            }
            $isOwner = in_array((int) $letter->resident_id, $allowedResidentIds);
        }

        if (! $isAdmin && ! $isOwner) {
            abort(403, 'Anda tidak memiliki hak untuk mencetak surat pengantar ini.');
        }

        $letter->load(['letterType', 'resident.household', 'approver']);
        $setting = RtSetting::first();

        // Generate QR Code SVG mengarah ke verifikasi publik
        $verifyUrl = route('public.verify-letter', $letter->qr_token ?? $letter->id);
        $qrCodeSvg = QrCode::size(100)->generate($verifyUrl);
        $qrCodeBase64 = base64_encode($qrCodeSvg);

        $logoPath = public_path('images/logo-icon.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)) : null;

        if ($request->query('format') === 'html') {
            return view('letters.pdf', compact('letter', 'setting', 'qrCodeSvg', 'qrCodeBase64', 'verifyUrl', 'logoBase64'));
        }

        $cleanNomor = str_replace(['/', '\\'], '-', $letter->nomor_surat ?? 'DRAFT');
        $filename = "Surat-Pengantar-{$cleanNomor}.pdf";

        $pdf = Pdf::loadView('letters.pdf', compact(
            'letter',
            'setting',
            'qrCodeSvg',
            'qrCodeBase64',
            'verifyUrl',
            'logoBase64'
        ))->setPaper('a4', 'portrait');

        if ($request->boolean('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    /**
     * Halaman Tagihan Iuran & Pembayaran Warga
     */
    public function invoices(): View
    {
        $household = $this->getHousehold();

        $invoices = $household ? Invoice::where('household_id', $household->id)
            ->with(['feeType', 'payments'])
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->get() : collect();

        return view('warga.iuran', compact('invoices', 'household'));
    }

    /**
     * Simpan Bukti Pembayaran Iuran
     */
    public function storePayment(Request $request, Invoice $invoice): RedirectResponse
    {
        $user = Auth::user();
        $household = $this->getHousehold();

        // Otorisasi: Invoice harus milik Kartu Keluarga warga yang sedang login
        if (! $household || $invoice->household_id !== $household->id) {
            abort(403, 'Anda tidak memiliki hak untuk membayar tagihan iuran keluarga lain.');
        }

        // Validasi: Cegah pembayaran tagihan yang sudah lunas
        if ($invoice->status === 'lunas') {
            return back()->withErrors(['pembayaran' => 'Tagihan iuran ini sudah berstatus Lunas.']);
        }

        // Validasi: Cegah pembayaran ganda jika sedang menunggu validasi bendahara
        $hasPending = $invoice->payments()->where('status', 'pending')->exists();
        if ($hasPending) {
            return back()->withErrors(['pembayaran' => 'Bukti pembayaran sebelumnya untuk tagihan ini sedang menunggu validasi oleh Bendahara RT.']);
        }

        $request->validate([
            'jumlah' => ['required', 'numeric', 'min:1000'],
            'metode' => ['required', 'in:tunai,transfer'],
            'bukti' => ['nullable', 'image', 'max:2048'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'jumlah.required' => 'Nominal pembayaran wajib diisi.',
            'jumlah.numeric' => 'Nominal pembayaran harus berupa angka.',
            'jumlah.min' => 'Nominal pembayaran minimal Rp 1.000.',
            'metode.required' => 'Pilih metode pembayaran (transfer atau tunai).',
            'metode.in' => 'Pilihan metode pembayaran tidak valid.',
            'bukti.image' => 'File bukti bayar harus berupa gambar (JPG, PNG, atau JPEG).',
            'bukti.max' => 'Ukuran file bukti bayar maksimal 2MB.',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti-bayar', 'public');
        }

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'jumlah' => $request->jumlah,
            'metode' => $request->metode,
            'bukti_path' => $buktiPath,
            'catatan' => $request->catatan,
            'tanggal_bayar' => now(),
            'status' => 'pending',
        ]);

        // Kirim notifikasi ke pengurus terkait (Bendahara, Ketua RT, Super Admin)
        NotificationService::notifyNewPayment($payment);

        return redirect()->route('warga.iuran')->with('success', 'Konfirmasi pembayaran berhasil dikirim! Menunggu validasi bendahara.');
    }

    /**
     * Halaman Pengaduan & Aspirasi Warga
     */
    public function complaints(): View
    {
        $complaints = Complaint::where('user_id', Auth::id())->latest()->paginate(10);

        return view('warga.pengaduan', compact('complaints'));
    }

    /**
     * Simpan Pengaduan Warga Baru
     */
    public function storeComplaint(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:infrastruktur,kebersihan,keamanan,kebisingan,lainnya'],
            'deskripsi' => ['required', 'string', 'min:5'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'is_anonim' => ['nullable', 'boolean'],
        ], [
            'judul.required' => 'Judul laporan pengaduan wajib diisi.',
            'judul.max' => 'Judul laporan pengaduan maksimal 255 karakter.',
            'kategori.required' => 'Pilih kategori masalah pengaduan.',
            'kategori.in' => 'Kategori pengaduan tidak valid.',
            'deskripsi.required' => 'Deskripsi keluhan wajib diisi.',
            'deskripsi.min' => 'Deskripsi keluhan minimal harus diisi 5 karakter.',
        ]);

        $complaint = Complaint::create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'] ?? null,
            'is_anonim' => $request->boolean('is_anonim'),
            'status' => 'diterima',
        ]);

        // Kirim notifikasi ke pengurus terkait (Target spesifik sesuai kategori masalah)
        NotificationService::notifyNewComplaint($complaint);

        return redirect()->route('warga.pengaduan')->with('success', 'Laporan pengaduan berhasil dikirim ke pengurus RT 15.');
    }

    /**
     * Dapatkan notifikasi untuk warga:
     * - Jika request JSON (API polling/Fetch): Kembalikan response JSON
     * - Jika request Web Biasa: Tampilkan Halaman Pusat Notifikasi Warga
     */
    public function getNotifications(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Mode JSON API (untuk sinkronisasi indikator lonceng atau panggilan AJAX)
        if ($request->expectsJson() || $request->ajax()) {
            $notifications = Notification::where('user_id', $user->id)
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($notif) {
                    return [
                        'id' => $notif->id,
                        'judul' => $notif->judul,
                        'isi' => $notif->isi,
                        'url' => $notif->url ?? '#',
                        'is_read' => (bool) $notif->is_read,
                        'time_ago' => $notif->created_at->diffForHumans(),
                    ];
                });

            $unreadCount = Notification::where('user_id', $user->id)->unread()->count();

            return response()->json([
                'unread_count' => $unreadCount,
                'notifications' => $notifications,
            ]);
        }

        // 2. Mode Halaman Web: Tampilan Penuh Pusat Notifikasi Warga
        $tab = $request->query('tab', 'all');
        $query = Notification::where('user_id', $user->id)->latest();

        if ($tab === 'unread') {
            $query->unread();
        } elseif ($tab === 'letter') {
            $query->where(function ($q) {
                $q->where('url', 'like', '%surat%')
                    ->orWhere('judul', 'like', '%Surat%');
            });
        }

        $notifications = $query->paginate(15)->withQueryString();
        $unreadCount = Notification::where('user_id', $user->id)->unread()->count();
        $hasReadNotifications = Notification::where('user_id', $user->id)->where('is_read', true)->exists();

        return view('warga.notifications', compact('notifications', 'unreadCount', 'tab', 'hasReadNotifications'));
    }

    /**
     * Buka notifikasi warga, tandai telah dibaca, dan redirect ke halaman tujuan
     */
    public function openNotification(Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Akses notifikasi ditolak.');
        }

        if (! $notification->is_read) {
            $notification->markAsRead();
        }

        $targetUrl = $notification->url ?: route('warga.dashboard');

        return redirect()->to($targetUrl);
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function markNotificationAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Akses notifikasi ditolak.');
        }

        $notification->markAsRead();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi berhasil ditandai telah dibaca.');
    }

    /**
     * Tandai seluruh notifikasi warga sebagai sudah dibaca
     */
    public function markAllNotificationsAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Seluruh notifikasi berhasil ditandai telah dibaca.');
    }

    /**
     * Hapus seluruh notifikasi warga yang sudah dibaca (pembersihan riwayat)
     */
    public function deleteAllReadNotifications(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', true)
            ->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Riwayat notifikasi yang telah dibaca berhasil dibersihkan.');
    }
}
