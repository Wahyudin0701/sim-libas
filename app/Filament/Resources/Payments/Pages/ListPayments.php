<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Filament\Resources\Payments\PaymentResource;
use App\Models\Payment;
use App\Services\NotificationService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected string $view = 'filament.resources.payments.pages.list-payments';

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'semua'; // 'semua', 'pending', 'valid', 'ditolak'

    public int $perPage = 25;

    // State Modal Validasi / Tolak
    public bool $validateModalOpen = false;
    public ?Payment $selectedPayment = null;
    public string $actionType = 'valid'; // 'valid' atau 'ditolak'
    public string $validationCatatan = '';

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('status') && $this->statusFilter === 'semua') {
            $this->statusFilter = (string) request('status');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setStatus(string $status): void
    {
        $this->statusFilter = in_array($status, ['semua', 'pending', 'valid', 'ditolak']) ? $status : 'semua';
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'semua';
        $this->resetPage();
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getHeading(): string
    {
        return '';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getViewData(): array
    {
        // 1. KPI Stats Validasi (Dioptimasi: 1 query selectRaw alih-alih 5 query round-trip terpisah)
        $stats = Payment::selectRaw("
            COUNT(*) as total_payments,
            COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as count_pending,
            COALESCE(SUM(CASE WHEN status = 'valid' THEN 1 ELSE 0 END), 0) as count_valid,
            COALESCE(SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END), 0) as count_ditolak,
            COALESCE(SUM(CASE WHEN status = 'valid' THEN jumlah ELSE 0 END), 0) as total_nominal_valid
        ")->first();

        $totalPayments = (int) ($stats->total_payments ?? 0);
        $countPending = (int) ($stats->count_pending ?? 0);
        $countValid = (int) ($stats->count_valid ?? 0);
        $countDitolak = (int) ($stats->count_ditolak ?? 0);
        $totalNominalValid = (float) ($stats->total_nominal_valid ?? 0);

        // 2. Query Data Pembayaran
        $query = Payment::with(['invoice.household.kepalaKeluarga', 'invoice.feeType', 'validator'])
            ->latest('tanggal_bayar')
            ->latest('id');

        if ($this->statusFilter !== 'semua') {
            $query->where('status', $this->statusFilter);
        }

        if (filled($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('catatan', 'like', "%{$term}%")
                  ->orWhere('jumlah', 'like', "%{$term}%")
                  ->orWhere('metode', 'like', "%{$term}%")
                  ->orWhereHas('invoice.household', function ($h) use ($term) {
                      $h->where('id_kk', 'like', "%{$term}%")
                        ->orWhere('alamat', 'like', "%{$term}%")
                        ->orWhereHas('kepalaKeluarga', function ($k) use ($term) {
                            $k->where('nama', 'like', "%{$term}%");
                        });
                  })
                  ->orWhereHas('invoice.feeType', function ($f) use ($term) {
                      $f->where('nama', 'like', "%{$term}%");
                  });
            });
        }

        $payments = $query->paginate($this->perPage);

        return [
            'payments' => $payments,
            'totalPayments' => $totalPayments,
            'countPending' => $countPending,
            'countValid' => $countValid,
            'countDitolak' => $countDitolak,
            'totalNominalValid' => $totalNominalValid,
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
        ];
    }

    // =========================================================================
    // MODAL VALIDASI / TOLAK / DETAIL PEMBAYARAN
    // =========================================================================
    public function openApprove(int $id): void
    {
        $this->selectedPayment = Payment::with(['invoice.household.kepalaKeluarga', 'invoice.feeType'])->findOrFail($id);
        $this->actionType = 'valid';
        $this->validationCatatan = 'Bukti pembayaran valid dan dana telah diterima kas RT.';
        $this->validateModalOpen = true;
    }

    public function openReject(int $id): void
    {
        $this->selectedPayment = Payment::with(['invoice.household.kepalaKeluarga', 'invoice.feeType'])->findOrFail($id);
        $this->actionType = 'ditolak';
        $this->validationCatatan = 'Bukti transfer tidak valid atau dana belum masuk rekening RT.';
        $this->validateModalOpen = true;
    }

    public function openDetail(int $id): void
    {
        $this->selectedPayment = Payment::with(['invoice.household.kepalaKeluarga', 'invoice.feeType', 'validator'])->findOrFail($id);
        $this->actionType = 'detail';
        $this->validationCatatan = '';
        $this->validateModalOpen = true;
    }

    public function closeValidateModal(): void
    {
        $this->validateModalOpen = false;
        $this->selectedPayment = null;
        $this->validationCatatan = '';
    }

    public function approvePayment(int $id, ?string $catatan = null): void
    {
        $finalCatatan = trim((string) ($catatan ?? $this->validationCatatan));

        if (empty($finalCatatan)) {
            Notification::make()
                ->title('Catatan Validasi Wajib Diisi')
                ->body('Mohon sertakan catatan validasi sebelum menyetujui pembayaran.')
                ->danger()
                ->send();
            return;
        }

        $payment = Payment::with(['invoice.household.kepalaKeluarga', 'invoice.feeType'])->findOrFail($id);

        // Cegah pembayaran yang sudah disetujui atau ditolak agar tidak divalidasi ulang
        if (in_array($payment->status, ['valid', 'ditolak'])) {
            Notification::make()
                ->title('Pembayaran Sudah Divalidasi')
                ->body('Status pembayaran ini sudah final dan tidak dapat divalidasi ulang.')
                ->warning()
                ->send();
            return;
        }

        $payment->update([
            'status' => 'valid',
            'divalidasi_oleh' => Auth::id() ?? 1,
            'tanggal_validasi' => Carbon::now(),
            'catatan_validasi' => $finalCatatan,
        ]);

        // Update tagihan menjadi lunas
        $payment->invoice?->update(['status' => 'lunas']);

        // Kirim notifikasi sistem ke warga (KK)
        NotificationService::notifyPaymentValidated($payment);

        Notification::make()
            ->title('Pembayaran Berhasil Disetujui')
            ->body("Pembayaran sebesar Rp " . number_format($payment->jumlah, 0, ',', '.') . " telah diverifikasi valid dan tagihan dinyatakan Lunas.")
            ->success()
            ->send();

        $this->selectedPayment = $payment->fresh(['invoice.household.kepalaKeluarga', 'invoice.feeType', 'validator']);
        $this->actionType = 'detail';
    }

    public function rejectPayment(int $id, ?string $catatan = null): void
    {
        $finalCatatan = trim((string) ($catatan ?? $this->validationCatatan));

        if (empty($finalCatatan)) {
            Notification::make()
                ->title('Alasan Penolakan Wajib Diisi')
                ->body('Mohon sertakan alasan penolakan sebelum menolak pembayaran.')
                ->danger()
                ->send();
            return;
        }

        $payment = Payment::with(['invoice.household.kepalaKeluarga', 'invoice.feeType'])->findOrFail($id);

        // Cegah pembayaran yang sudah disetujui atau ditolak agar tidak divalidasi ulang
        if (in_array($payment->status, ['valid', 'ditolak'])) {
            Notification::make()
                ->title('Pembayaran Sudah Divalidasi')
                ->body('Status pembayaran ini sudah final dan tidak dapat divalidasi ulang.')
                ->warning()
                ->send();
            return;
        }

        $payment->update([
            'status' => 'ditolak',
            'divalidasi_oleh' => Auth::id() ?? 1,
            'tanggal_validasi' => Carbon::now(),
            'catatan_validasi' => $finalCatatan,
        ]);

        // Jika sebelumnya lunas, kembalikan ke belum_bayar
        if ($payment->invoice?->status === 'lunas') {
            $payment->invoice?->update(['status' => 'belum_bayar']);
        }

        // Kirim notifikasi sistem ke warga (KK)
        NotificationService::notifyPaymentRejected($payment, $finalCatatan);

        Notification::make()
            ->title('Pembayaran Ditolak')
            ->body("Bukti pembayaran telah ditolak dengan catatan.")
            ->warning()
            ->send();

        $this->selectedPayment = $payment->fresh(['invoice.household.kepalaKeluarga', 'invoice.feeType', 'validator']);
        $this->actionType = 'detail';
    }

    public function processValidation(?int $id = null, ?string $type = null, ?string $catatan = null): void
    {
        $targetId = $id ?? $this->selectedPayment?->id;
        if (! $targetId) {
            return;
        }

        $action = $type ?? $this->actionType;
        $note = $catatan ?? $this->validationCatatan;

        if ($action === 'valid') {
            $this->approvePayment($targetId, $note);
        } else {
            $this->rejectPayment($targetId, $note);
        }

        $this->validateModalOpen = false;
        $this->selectedPayment = null;
    }

    public function deletePayment(int $id): void
    {
        $p = Payment::findOrFail($id);

        // Validasi: Tombol Hapus hanya tersedia untuk status akhir (valid atau ditolak)
        if (! in_array($p->status, ['valid', 'ditolak'])) {
            Notification::make()
                ->title('Penghapusan Ditolak')
                ->body('Pembayaran yang masih menunggu validasi tidak dapat dihapus.')
                ->danger()
                ->send();
            return;
        }

        $inv = $p->invoice;
        $p->delete();

        if ($inv && $inv->status === 'lunas') {
            $hasOtherValid = Payment::where('invoice_id', $inv->id)->where('status', 'valid')->exists();
            if (! $hasOtherValid) {
                $inv->update(['status' => 'belum_bayar']);
            }
        }

        $this->validateModalOpen = false;
        $this->selectedPayment = null;

        Notification::make()
            ->title('Catatan pembayaran berhasil dihapus')
            ->success()
            ->send();
    }

    // =========================================================================
    // EKSPOR LAPORAN VALIDASI PEMBAYARAN (CSV)
    // =========================================================================
    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="validasi-pembayaran-rt15-' . date('Y-m-d') . '.csv"',
        ];

        $status = $this->statusFilter;
        $term = $this->search;

        return response()->streamDownload(function () use ($status, $term) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($file, [
                'NO',
                'TANGGAL BAYAR',
                'ID KK',
                'NAMA KEPALA KELUARGA',
                'JENIS IURAN',
                'PERIODE',
                'JUMLAH BAYAR (RP)',
                'METODE',
                'STATUS VALIDASI',
                'DIVALIDASI OLEH',
            ]);

            $query = Payment::with(['invoice.household.kepalaKeluarga', 'invoice.feeType', 'validator'])
                ->latest('tanggal_bayar');

            if ($status !== 'semua') {
                $query->where('status', $status);
            }

            if (filled($term)) {
                $query->where(function ($q) use ($term) {
                    $q->where('catatan', 'like', "%{$term}%")
                      ->orWhere('jumlah', 'like', "%{$term}%")
                      ->orWhere('metode', 'like', "%{$term}%")
                      ->orWhereHas('invoice.household', function ($h) use ($term) {
                          $h->where('id_kk', 'like', "%{$term}%")
                            ->orWhereHas('kepalaKeluarga', function ($k) use ($term) {
                                $k->where('nama', 'like', "%{$term}%");
                            });
                      })
                      ->orWhereHas('invoice.feeType', function ($f) use ($term) {
                          $f->where('nama', 'like', "%{$term}%");
                      });
                });
            }

            $no = 1;
            foreach ($query->cursor() as $p) {
                fputcsv($file, [
                    $no++,
                    Carbon::parse($p->tanggal_bayar)->translatedFormat('d F Y'),
                    $p->invoice?->household?->id_kk ?? '-',
                    $p->invoice?->household?->kepalaKeluarga?->nama ?? '-',
                    $p->invoice?->feeType?->nama ?? '-',
                    $p->invoice?->periode_label ?? '-',
                    number_format($p->jumlah, 0, ',', '.'),
                    strtoupper($p->metode),
                    strtoupper($p->status),
                    $p->validator?->name ?? '-',
                ]);
            }

            fclose($file);
        }, 'validasi-pembayaran-rt15-' . date('Y-m-d') . '.csv', $headers);
    }
}
