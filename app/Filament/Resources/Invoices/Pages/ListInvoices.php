<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\FeeType;
use App\Models\Household;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected string $view = 'filament.resources.invoices.pages.list-invoices';

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'semua'; // 'semua', 'belum_bayar', 'sebagian', 'lunas'

    #[Url]
    public string $feeTypeFilter = '';

    public int $perPage = 25;

    // State Modal Detail Tagihan
    public bool $detailModalOpen = false;
    public ?Invoice $selectedInvoice = null;

    // State Modal Bayar Langsung / Catat Pembayaran Manual
    public bool $payModalOpen = false;
    public ?Invoice $payInvoice = null;
    public string $pay_metode = 'tunai';
    public string $pay_jumlah = '';
    public string $pay_tanggal = '';
    public string $pay_catatan = '';

    // State Modal Buat Tagihan Baru
    public bool $createModalOpen = false;
    public string $create_mode = 'single'; // 'single' atau 'all_households'
    public ?int $create_household_id = null;
    public ?int $create_fee_type_id = null;
    public int $create_bulan = 10;
    public int $create_tahun = 2026;
    public string $create_jatuh_tempo = '';

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('status') && $this->statusFilter === 'semua') {
            $this->statusFilter = (string) request('status');
        }
        if (request()->has('fee_type') && empty($this->feeTypeFilter)) {
            $this->feeTypeFilter = (string) request('fee_type');
        }

        $this->create_bulan = (int) Carbon::now()->format('n');
        $this->create_tahun = (int) Carbon::now()->format('Y');
        $this->create_jatuh_tempo = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setStatus(string $status): void
    {
        $this->statusFilter = in_array($status, ['semua', 'belum_bayar', 'sebagian', 'lunas']) ? $status : 'semua';
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedFeeTypeFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'semua';
        $this->feeTypeFilter = '';
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
        // 1. KPI Stats Tagihan (Dioptimasi: 1 query selectRaw alih-alih 6 query round-trip terpisah)
        $stats = Invoice::selectRaw("
            COUNT(*) as total_tagihan,
            COALESCE(SUM(jumlah), 0) as total_nominal,
            COALESCE(SUM(CASE WHEN status = 'belum_bayar' THEN 1 ELSE 0 END), 0) as count_belum_bayar,
            COALESCE(SUM(CASE WHEN status = 'sebagian' THEN 1 ELSE 0 END), 0) as count_sebagian,
            COALESCE(SUM(CASE WHEN status = 'lunas' THEN 1 ELSE 0 END), 0) as count_lunas,
            COALESCE(SUM(CASE WHEN status IN ('belum_bayar', 'sebagian') THEN jumlah ELSE 0 END), 0) as nominal_belum_lunas
        ")->first();

        $totalTagihan = (int) ($stats->total_tagihan ?? 0);
        $totalNominal = (float) ($stats->total_nominal ?? 0);
        $countBelumBayar = (int) ($stats->count_belum_bayar ?? 0);
        $countSebagian = (int) ($stats->count_sebagian ?? 0);
        $countLunas = (int) ($stats->count_lunas ?? 0);
        $nominalBelumLunas = (float) ($stats->nominal_belum_lunas ?? 0);

        // 2. Daftar Jenis Iuran & KK
        $feeTypes = FeeType::where('is_active', true)->get(['id', 'nama', 'nominal']);
        $households = Household::with('kepalaKeluarga:id,household_id,nama')->orderBy('id_kk')->get(['id', 'id_kk', 'alamat']);

        // 3. Query Data Tagihan
        $query = Invoice::with(['household.kepalaKeluarga', 'feeType', 'payments'])
            ->latest('periode_tahun')
            ->latest('periode_bulan')
            ->latest('id');

        if ($this->statusFilter !== 'semua') {
            $query->where('status', $this->statusFilter);
        }

        if (filled($this->feeTypeFilter)) {
            $query->where('fee_type_id', $this->feeTypeFilter);
        }

        if (filled($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->whereHas('household', function ($h) use ($term) {
                    $h->where('id_kk', 'like', "%{$term}%")
                      ->orWhere('alamat', 'like', "%{$term}%")
                      ->orWhereHas('kepalaKeluarga', function ($k) use ($term) {
                          $k->where('nama', 'like', "%{$term}%")
                            ->orWhere('telepon', 'like', "%{$term}%");
                      });
                })
                ->orWhereHas('feeType', function ($f) use ($term) {
                    $f->where('nama', 'like', "%{$term}%");
                })
                ->orWhere('jumlah', 'like', "%{$term}%")
                ->orWhere('periode_tahun', 'like', "%{$term}%");
            });
        }

        $invoices = $query->paginate($this->perPage);

        return [
            'invoices' => $invoices,
            'totalTagihan' => $totalTagihan,
            'totalNominal' => $totalNominal,
            'countBelumBayar' => $countBelumBayar,
            'countSebagian' => $countSebagian,
            'countLunas' => $countLunas,
            'nominalBelumLunas' => $nominalBelumLunas,
            'feeTypes' => $feeTypes,
            'households' => $households,
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'feeTypeFilter' => $this->feeTypeFilter,
        ];
    }

    // =========================================================================
    // MODAL DETAIL TAGIHAN & RIWAYAT PEMBAYARAN
    // =========================================================================
    public function openDetail(int $id): void
    {
        $this->selectedInvoice = Invoice::with(['household.kepalaKeluarga', 'feeType', 'payments.validator'])->find($id);
        $this->detailModalOpen = true;
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedInvoice = null;
    }

    // =========================================================================
    // MODAL CATAT PEMBAYARAN CEPAT (TERIMA TUNAI / TRANSFER)
    // =========================================================================
    public function openPay(int $id): void
    {
        $this->resetValidation();
        $this->payInvoice = Invoice::with(['household.kepalaKeluarga', 'feeType'])->findOrFail($id);
        $this->pay_metode = 'tunai';
        $this->pay_jumlah = (string) (int) $this->payInvoice->jumlah;
        $this->pay_tanggal = Carbon::now()->format('Y-m-d');
        $this->pay_catatan = 'Diterima tunai oleh pengurus RT 15';
        $this->payModalOpen = true;
    }

    public function closePay(): void
    {
        $this->payModalOpen = false;
        $this->payInvoice = null;
        $this->resetValidation();
    }

    public function confirmPayment(): void
    {
        $this->validate([
            'pay_metode' => 'required|in:tunai,transfer',
            'pay_jumlah' => 'required|numeric|min:1',
            'pay_tanggal' => 'required|date',
            'pay_catatan' => 'nullable|string|max:255',
        ]);

        if (! $this->payInvoice) {
            return;
        }

        Payment::create([
            'invoice_id' => $this->payInvoice->id,
            'jumlah' => $this->pay_jumlah,
            'metode' => $this->pay_metode,
            'catatan' => $this->pay_catatan,
            'tanggal_bayar' => $this->pay_tanggal,
            'divalidasi_oleh' => Auth::id() ?? 1,
            'tanggal_validasi' => Carbon::now(),
            'status' => 'valid',
        ]);

        $this->payInvoice->update(['status' => 'lunas']);

        Notification::make()
            ->title('Pembayaran tagihan berhasil dicatat & divalidasi')
            ->success()
            ->send();

        $this->payModalOpen = false;
        $this->payInvoice = null;
    }

    // =========================================================================
    // MODAL BUAT TAGIHAN BARU (INDIVIDU / MASSAL SEMUA KK)
    // =========================================================================
    public function openCreate(): void
    {
        $this->resetValidation();
        $firstFee = FeeType::where('is_active', true)->first();
        $this->create_mode = 'single';
        $this->create_household_id = Household::first()?->id;
        $this->create_fee_type_id = $firstFee?->id;
        $this->create_bulan = (int) Carbon::now()->format('n');
        $this->create_tahun = (int) Carbon::now()->format('Y');
        $this->create_jatuh_tempo = Carbon::now()->addDays(15)->format('Y-m-d');
        $this->createModalOpen = true;
    }

    public function closeCreate(): void
    {
        $this->createModalOpen = false;
        $this->resetValidation();
    }

    public function saveInvoice(): void
    {
        $this->validate([
            'create_mode' => 'required|in:single,all_households',
            'create_fee_type_id' => 'required|exists:fee_types,id',
            'create_bulan' => 'required|integer|between:1,12',
            'create_tahun' => 'required|integer|min:2020|max:2035',
            'create_jatuh_tempo' => 'nullable|date',
        ]);

        $feeType = FeeType::findOrFail($this->create_fee_type_id);

        if ($this->create_mode === 'all_households') {
            $allHouseholds = Household::all();
            $countCreated = 0;

            foreach ($allHouseholds as $hh) {
                $exists = Invoice::where('household_id', $hh->id)
                    ->where('fee_type_id', $feeType->id)
                    ->where('periode_bulan', $this->create_bulan)
                    ->where('periode_tahun', $this->create_tahun)
                    ->exists();

                if (! $exists) {
                    Invoice::create([
                        'household_id' => $hh->id,
                        'fee_type_id' => $feeType->id,
                        'periode_bulan' => $this->create_bulan,
                        'periode_tahun' => $this->create_tahun,
                        'jumlah' => $feeType->nominal,
                        'status' => 'belum_bayar',
                        'tanggal_jatuh_tempo' => $this->create_jatuh_tempo ?: null,
                    ]);
                    $countCreated++;
                }
            }

            Notification::make()
                ->title("Berhasil menerbitkan {$countCreated} tagihan iuran massal")
                ->success()
                ->send();
        } else {
            $this->validate([
                'create_household_id' => 'required|exists:households,id',
            ]);

            $exists = Invoice::where('household_id', $this->create_household_id)
                ->where('fee_type_id', $feeType->id)
                ->where('periode_bulan', $this->create_bulan)
                ->where('periode_tahun', $this->create_tahun)
                ->exists();

            if ($exists) {
                Notification::make()
                    ->title('Tagihan untuk KK dan periode tersebut sudah ada.')
                    ->danger()
                    ->send();
                return;
            }

            Invoice::create([
                'household_id' => $this->create_household_id,
                'fee_type_id' => $feeType->id,
                'periode_bulan' => $this->create_bulan,
                'periode_tahun' => $this->create_tahun,
                'jumlah' => $feeType->nominal,
                'status' => 'belum_bayar',
                'tanggal_jatuh_tempo' => $this->create_jatuh_tempo ?: null,
            ]);

            Notification::make()
                ->title('Tagihan iuran baru berhasil diterbitkan')
                ->success()
                ->send();
        }

        $this->createModalOpen = false;
    }

    public function deleteInvoice(int $id): void
    {
        $inv = Invoice::findOrFail($id);
        $inv->delete();

        Notification::make()
            ->title('Tagihan iuran berhasil dihapus')
            ->success()
            ->send();
    }

    // =========================================================================
    // EKSPOR LAPORAN TAGIHAN (CSV)
    // =========================================================================
    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="tagihan-iuran-rt15-' . date('Y-m-d') . '.csv"',
        ];

        $status = $this->statusFilter;
        $feeTypeId = $this->feeTypeFilter;
        $term = $this->search;

        return response()->streamDownload(function () use ($status, $feeTypeId, $term) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($file, [
                'NO',
                'ID KK',
                'KEPALA KELUARGA',
                'ALAMAT RUMAH',
                'JENIS IURAN',
                'PERIODE',
                'NOMINAL TAGIHAN (RP)',
                'JATUH TEMPO',
                'STATUS TAGIHAN',
            ]);

            $query = Invoice::with(['household.kepalaKeluarga', 'feeType'])
                ->latest('periode_tahun')
                ->latest('periode_bulan');

            if ($status !== 'semua') {
                $query->where('status', $status);
            }
            if (filled($feeTypeId)) {
                $query->where('fee_type_id', $feeTypeId);
            }
            if (filled($term)) {
                $query->where(function ($q) use ($term) {
                    $q->whereHas('household', function ($h) use ($term) {
                        $h->where('id_kk', 'like', "%{$term}%")
                          ->orWhere('alamat', 'like', "%{$term}%")
                          ->orWhereHas('kepalaKeluarga', function ($k) use ($term) {
                              $k->where('nama', 'like', "%{$term}%");
                          });
                    })
                    ->orWhereHas('feeType', function ($f) use ($term) {
                        $f->where('nama', 'like', "%{$term}%");
                    });
                });
            }

            $no = 1;
            foreach ($query->cursor() as $inv) {
                fputcsv($file, [
                    $no++,
                    $inv->household?->id_kk ?? '-',
                    $inv->household?->kepalaKeluarga?->nama ?? '-',
                    $inv->household?->alamat ?? '-',
                    $inv->feeType?->nama ?? '-',
                    $inv->periode_label,
                    number_format($inv->jumlah, 0, ',', '.'),
                    $inv->tanggal_jatuh_tempo ? Carbon::parse($inv->tanggal_jatuh_tempo)->translatedFormat('d F Y') : '-',
                    strtoupper($inv->status),
                ]);
            }

            fclose($file);
        }, 'tagihan-iuran-rt15-' . date('Y-m-d') . '.csv', $headers);
    }
}
