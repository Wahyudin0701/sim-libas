<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\GuestReport;
use App\Models\Household;
use App\Models\Letter;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Kirim notifikasi ke User ID tertentu.
     */
    public static function sendToUser(int $userId, string $judul, string $isi, ?string $url = null, string $kanal = 'web'): ?Notification
    {
        $user = User::find($userId);
        if (!$user) {
            return null;
        }

        return Notification::create([
            'user_id' => $user->id,
            'judul' => $judul,
            'isi' => $isi,
            'kanal' => $kanal,
            'url' => $url,
            'is_read' => false,
        ]);
    }

    /**
     * Kirim notifikasi ke Resident (warga pemohon).
     * Jika resident memiliki user_id, kirim langsung.
     * Jika resident berada di suatu Kartu Keluarga, kirimkan juga ke akun pengelola KK (jika berbeda).
     */
    public static function sendToResident(?Resident $resident, string $judul, string $isi, ?string $url = null, string $kanal = 'web'): array
    {
        if (!$resident) {
            return [];
        }

        $userIds = [];
        if ($resident->user_id) {
            $userIds[] = $resident->user_id;
        }

        if ($resident->household) {
            if ($resident->household->user_id) {
                $userIds[] = $resident->household->user_id;
            }
            $kepala = $resident->household->kepalaKeluarga;
            if ($kepala && $kepala->user_id) {
                $userIds[] = $kepala->user_id;
            }
        }

        $userIds = array_unique(array_filter($userIds));
        $created = [];

        foreach ($userIds as $userId) {
            $notification = self::sendToUser((int) $userId, $judul, $isi, $url, $kanal);
            if ($notification) {
                $created[] = $notification;
            }
        }

        return $created;
    }

    /**
     * Kirim notifikasi ke seluruh akun yang terikat pada suatu Kartu Keluarga (Household).
     */
    public static function sendToHousehold(?Household $household, string $judul, string $isi, ?string $url = null, string $kanal = 'web'): array
    {
        if (!$household) {
            return [];
        }

        $userIds = [];
        if ($household->user_id) {
            $userIds[] = $household->user_id;
        }

        $residentUserIds = $household->residents()
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();

        $userIds = array_unique(array_filter(array_merge($userIds, $residentUserIds)));
        $created = [];

        foreach ($userIds as $userId) {
            $notification = self::sendToUser((int) $userId, $judul, $isi, $url, $kanal);
            if ($notification) {
                $created[] = $notification;
            }
        }

        return $created;
    }

    /**
     * Kirim notifikasi ke seluruh pengurus yang memiliki role tertentu.
     * Hanya dikirim ke pengguna berstatus aktif dan mencegah duplikasi notifikasi.
     *
     * @param list<string> $roles
     * @return list<Notification>
     */
    public static function sendToRoles(array $roles, string $judul, string $isi, ?string $url = null, string $kanal = 'web'): array
    {
        $users = User::role($roles)
            ->where('status', 'aktif')
            ->get();

        $created = [];
        foreach ($users as $user) {
            $notification = self::sendToUser((int) $user->id, $judul, $isi, $url, $kanal);
            if ($notification) {
                $created[] = $notification;
            }
        }

        return $created;
    }

    /**
     * Notifikasi Aktivitas: Pengajuan Surat Baru dari Warga.
     * Ditujukan kepada:
     * - Sekretaris RT (pemeriksa administrasi surat)
     * - Ketua RT (penandatangan & persetujuan)
     * - Super Admin (supervisi sistem)
     */
    public static function notifyNewLetter(Letter $letter): array
    {
        $letter->loadMissing(['letterType', 'resident']);

        $namaWarga = $letter->resident->nama_lengkap ?? 'Warga RT 15';
        $jenisSurat = $letter->letterType->nama ?? $letter->letterType->nama_surat ?? 'Surat Pengantar';
        $judul = "Pengajuan Surat: {$jenisSurat}";
        $isi = "Warga {$namaWarga} telah mengajukan {$jenisSurat}. Keperluan: \"{$letter->keperluan}\".";
        $url = route('filament.admin.resources.letters.index');

        return self::sendToRoles(['sekretaris', 'ketua_rt', 'super_admin'], $judul, $isi, $url);
    }

    /**
     * Notifikasi Aktivitas: Laporan Pengaduan / Keluhan Warga Masuk.
     * Ditujukan tepat sasaran sesuai bidang:
     * - Kategori 'keamanan' & 'kebisingan': Koordinator Keamanan, Ketua RT, Sekretaris, Super Admin
     * - Kategori 'kebersihan': Koordinator Bank Sampah, Ketua RT, Sekretaris, Super Admin
     * - Kategori lainnya: Ketua RT, Sekretaris, Super Admin
     * - Ditambah: Penanggung Jawab khusus jika langsung ditugaskan
     */
    public static function notifyNewComplaint(Complaint $complaint): array
    {
        $complaint->loadMissing('user');

        $kategori = strtolower($complaint->kategori ?? 'lainnya');
        $targetRoles = ['ketua_rt', 'sekretaris', 'super_admin'];

        if (in_array($kategori, ['keamanan', 'kebisingan'])) {
            $targetRoles[] = 'koordinator_keamanan';
        } elseif ($kategori === 'kebersihan') {
            $targetRoles[] = 'koordinator_bank_sampah';
        }

        $pelapor = $complaint->is_anonim ? 'Warga RT 15 (Anonim)' : ($complaint->user->name ?? 'Warga RT 15');
        $kategoriLabel = ucfirst($kategori);
        $judul = "Pengaduan Baru [{$kategoriLabel}]: {$complaint->judul}";
        $cuplikan = Str::limit($complaint->deskripsi, 80);
        $isi = "Laporan masuk dari {$pelapor}: \"{$cuplikan}\".";
        if (!empty($complaint->lokasi)) {
            $isi .= " Lokasi: {$complaint->lokasi}.";
        }
        $url = route('filament.admin.resources.complaints.index');

        $notifications = self::sendToRoles(array_unique($targetRoles), $judul, $isi, $url);

        // Jika terdapat penanggung jawab spesifik yang sudah ditugaskan
        if ($complaint->penanggung_jawab_id) {
            $alreadySent = collect($notifications)->contains('user_id', $complaint->penanggung_jawab_id);
            if (!$alreadySent) {
                $customNotif = self::sendToUser((int) $complaint->penanggung_jawab_id, $judul, $isi, $url);
                if ($customNotif) {
                    $notifications[] = $customNotif;
                }
            }
        }

        return $notifications;
    }

    /**
     * Notifikasi Aktivitas: Konfirmasi Pembayaran Iuran Warga Masuk.
     * Ditujukan kepada:
     * - Bendahara RT (verifikator keuangan & buku kas)
     * - Ketua RT (monitoring kas RT)
     * - Super Admin (supervisi sistem)
     */
    public static function notifyNewPayment(Payment $payment): array
    {
        $payment->loadMissing(['invoice.feeType', 'invoice.household.kepalaKeluarga']);

        $namaIuran = $payment->invoice?->feeType?->nama ?? $payment->invoice?->feeType?->nama_iuran ?? 'Iuran RT';
        $namaKeluarga = $payment->invoice?->household?->nama_kepala_keluarga
            ?? $payment->invoice?->household?->kepalaKeluarga?->nama_lengkap
            ?? 'Warga RT 15';
        $nominal = number_format($payment->jumlah, 0, ',', '.');
        $metode = ucfirst($payment->metode ?? 'transfer');

        $judul = "Konfirmasi Pembayaran: {$namaIuran}";
        $isi = "KK {$namaKeluarga} telah mengirim konfirmasi pembayaran {$namaIuran} sebesar Rp {$nominal} via {$metode}. Mohon divalidasi.";
        $url = route('filament.admin.resources.payments.index');

        return self::sendToRoles(['bendahara', 'ketua_rt', 'super_admin'], $judul, $isi, $url);
    }

    /**
     * Notifikasi Aktivitas: Pelaporan Tamu Menginap 1x24 Jam / Pendatang Baru.
     * Ditujukan kepada: Koordinator Keamanan, Ketua RT, Sekretaris, dan Super Admin.
     */
    public static function notifyNewGuestReport(GuestReport $guestReport): array
    {
        $guestReport->loadMissing(['household.headOfHousehold', 'pelapor']);

        $tuanRumah = $guestReport->household?->nama_kepala_keluarga
            ?? $guestReport->pelapor?->name
            ?? 'Warga RT 15';
        $durasi = $guestReport->durasi_hari ? "{$guestReport->durasi_hari} hari" : 'beberapa hari';

        $judul = "Laporan Tamu Baru: {$guestReport->nama_tamu}";
        $isi = "Pelaporan tamu menginap di kediaman {$tuanRumah} selama {$durasi}. Keperluan: \"{$guestReport->keperluan}\".";
        $url = route('filament.admin.resources.guest-reports.index');

        return self::sendToRoles(['koordinator_keamanan', 'ketua_rt', 'sekretaris', 'super_admin'], $judul, $isi, $url);
    }

    /**
     * Notifikasi ke Warga: Surat Pengantar Disetujui oleh Pengurus RT.
     */
    public static function notifyLetterApproved(Letter $letter): array
    {
        $letter->loadMissing(['letterType', 'resident.household']);

        $jenisSurat = $letter->letterType->nama ?? $letter->letterType->nama_surat ?? 'Surat Pengantar';
        $namaWarga = $letter->resident->nama ?? $letter->resident->nama_lengkap ?? 'Warga';
        $nomorSurat = $letter->nomor_surat ?? '-';

        $judul = "Surat Pengantar Disetujui ({$jenisSurat})";
        $isi = "Permohonan {$jenisSurat} atas nama {$namaWarga} telah disetujui dengan Nomor: {$nomorSurat}. Anda dapat mengunduh atau mencetak dokumen resmi sekarang.";
        $url = route('warga.surat');

        return self::sendToResident($letter->resident, $judul, $isi, $url);
    }

    /**
     * Notifikasi ke Warga: Surat Pengantar Ditolak oleh Pengurus RT.
     */
    public static function notifyLetterRejected(Letter $letter, ?string $alasan = null): array
    {
        $letter->loadMissing(['letterType', 'resident.household']);

        $jenisSurat = $letter->letterType->nama ?? $letter->letterType->nama_surat ?? 'Surat Pengantar';
        $namaWarga = $letter->resident->nama ?? $letter->resident->nama_lengkap ?? 'Warga';
        $alasanTolak = $alasan ?: ($letter->alasan_tolak ?: 'Persyaratan belum lengkap.');

        $judul = "Permohonan Surat Ditolak ({$jenisSurat})";
        $isi = "Permohonan {$jenisSurat} atas nama {$namaWarga} ditolak oleh pengurus RT. Alasan: \"{$alasanTolak}\".";
        $url = route('warga.surat');

        return self::sendToResident($letter->resident, $judul, $isi, $url);
    }

    /**
     * Notifikasi ke Warga: Pembayaran Iuran Berhasil Divalidasi (Lunas).
     */
    public static function notifyPaymentValidated(Payment $payment): array
    {
        $payment->loadMissing(['invoice.household', 'invoice.feeType']);

        $namaIuran = $payment->invoice?->feeType?->nama ?? $payment->invoice?->feeType?->nama_iuran ?? 'Iuran RT';
        $nominal = number_format($payment->jumlah, 0, ',', '.');

        $judul = "Pembayaran Iuran Berhasil Divalidasi";
        $isi = "Pembayaran {$namaIuran} sebesar Rp {$nominal} telah diverifikasi oleh Bendahara RT dan status tagihan dinyatakan LUNAS. Terima kasih.";
        $url = route('warga.iuran');

        return self::sendToHousehold($payment->invoice?->household, $judul, $isi, $url);
    }

    /**
     * Notifikasi ke Warga: Pembayaran Iuran Ditolak.
     */
    public static function notifyPaymentRejected(Payment $payment, ?string $alasan = null): array
    {
        $payment->loadMissing(['invoice.household', 'invoice.feeType']);

        $namaIuran = $payment->invoice?->feeType?->nama ?? $payment->invoice?->feeType?->nama_iuran ?? 'Iuran RT';
        $catatan = $alasan ?: ($payment->catatan ?: 'Bukti pembayaran tidak sesuai.');

        $judul = "Konfirmasi Pembayaran Iuran Ditolak";
        $isi = "Bukti pembayaran {$namaIuran} ditolak oleh Bendahara RT. Catatan: \"{$catatan}\". Silakan unggah bukti yang valid.";
        $url = route('warga.iuran');

        return self::sendToHousehold($payment->invoice?->household, $judul, $isi, $url);
    }

    /**
     * Notifikasi ke Warga: Status Pengaduan Diperbarui.
     */
    public static function notifyComplaintUpdated(Complaint $complaint): ?Notification
    {
        if (!$complaint->user_id) {
            return null;
        }

        $statusLabel = match ($complaint->status) {
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Telah Diselesaikan',
            'ditolak' => 'Ditolak',
            default => ucfirst($complaint->status),
        };

        $judul = "Status Pengaduan: {$statusLabel}";
        $isi = "Laporan Anda \"{$complaint->judul}\" kini berstatus {$statusLabel}.";
        if (!empty($complaint->catatan_penanganan)) {
            $isi .= " Catatan pengurus: \"{$complaint->catatan_penanganan}\".";
        }
        $url = route('warga.pengaduan');

        return self::sendToUser((int) $complaint->user_id, $judul, $isi, $url);
    }
}
