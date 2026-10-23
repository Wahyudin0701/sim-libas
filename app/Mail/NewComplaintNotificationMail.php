<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Route;

class NewComplaintNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Complaint $complaint;
    public string $detailUrl;
    public string $pelaporName;
    public string $kategoriLabel;
    public string $waktuKirim;

    /**
     * Create a new message instance.
     */
    public function __construct(Complaint $complaint, ?string $detailUrl = null)
    {
        $this->complaint = $complaint->loadMissing(['pelapor', 'user']);

        // Tentukan nama pelapor dengan menghormati status anonim
        $this->pelaporName = $this->complaint->is_anonim
            ? 'Warga RT 15 (Laporan Anonim)'
            : ($this->complaint->pelapor?->name ?? $this->complaint->user?->name ?? 'Warga RT 15');

        // Label kategori
        $kategoriMap = [
            'infrastruktur' => 'Infrastruktur & Fasilitas',
            'kebersihan' => 'Kebersihan & Lingkungan',
            'keamanan' => 'Keamanan & Ketertiban',
            'kebisingan' => 'Kebisingan & Gangguan',
            'lainnya' => 'Aspirasi / Lainnya',
        ];
        $this->kategoriLabel = $kategoriMap[strtolower((string) $this->complaint->kategori)]
            ?? ucfirst((string) $this->complaint->kategori);

        // Format waktu kirim (WIB)
        $waktu = $this->complaint->created_at ?? now();
        $this->waktuKirim = $waktu->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB';

        // Tautan detail pengaduan di sistem
        $this->detailUrl = $detailUrl ?: self::resolveDetailUrl($this->complaint);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $kategoriShort = ucfirst((string) $this->complaint->kategori);

        return new Envelope(
            subject: "[SIM-LiBas RT 15] Pengaduan Warga Baru [{$kategoriShort}]: {$this->complaint->judul}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-complaint',
            with: [
                'complaint' => $this->complaint,
                'pelaporName' => $this->pelaporName,
                'kategoriLabel' => $this->kategoriLabel,
                'waktuKirim' => $this->waktuKirim,
                'detailUrl' => $this->detailUrl,
            ],
        );
    }

    /**
     * Menghasilkan tautan detail pengaduan pada panel pengurus SIM-LiBas.
     */
    public static function resolveDetailUrl(Complaint $complaint): string
    {
        try {
            if (Route::has('filament.admin.resources.complaints.edit') && $complaint->getKey()) {
                return route('filament.admin.resources.complaints.edit', ['record' => $complaint->getKey()]);
            }

            if (Route::has('filament.admin.resources.complaints.index')) {
                return route('filament.admin.resources.complaints.index');
            }
        } catch (\Throwable) {
            // Abaikan jika terjadi exception route resolution
        }

        return url('/admin/complaints/' . ($complaint->getKey() ?: '') . '/edit');
    }
}
