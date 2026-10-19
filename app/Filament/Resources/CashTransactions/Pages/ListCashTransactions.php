<?php

namespace App\Filament\Resources\CashTransactions\Pages;

use App\Filament\Resources\CashTransactions\CashTransactionResource;
use App\Models\CashTransaction;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListCashTransactions extends ListRecords
{
    use WithFileUploads;

    protected static string $resource = CashTransactionResource::class;

    protected string $view = 'filament.resources.cash-transactions.pages.list-cash-transactions';

    #[Url]
    public string $search = '';

    #[Url]
    public string $jenisFilter = 'semua'; // 'semua', 'masuk', 'keluar'

    #[Url]
    public string $kategoriFilter = '';

    #[Url]
    public string $periodeFilter = 'semua'; // 'semua', 'bulan_ini', 'kustom'

    #[Url]
    public string $startDate = '';

    #[Url]
    public string $endDate = '';

    public int $perPage = 25;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?CashTransaction $selectedTransaction = null;

    // State Modal Form (Tambah / Ubah)
    public bool $formModalOpen = false;
    public bool $isEditing = false;
    public ?int $transactionId = null;
    public string $form_jenis = 'masuk';
    public string $form_kategori = 'Iuran Warga';
    public string $form_jumlah = '';
    public string $form_tanggal = '';
    public string $form_deskripsi = '';
    public $form_bukti = null;
    public ?string $existing_bukti = null;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('jenis') && $this->jenisFilter === 'semua') {
            $this->jenisFilter = (string) request('jenis');
        }
        if (request()->has('kategori') && empty($this->kategoriFilter)) {
            $this->kategoriFilter = (string) request('kategori');
        }
        if (request()->has('periode') && $this->periodeFilter === 'semua') {
            $this->periodeFilter = (string) request('periode');
        }
        if (request()->has('start_date') && empty($this->startDate)) {
            $this->startDate = (string) request('start_date');
        }
        if (request()->has('end_date') && empty($this->endDate)) {
            $this->endDate = (string) request('end_date');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setJenis(string $jenis): void
    {
        $this->jenisFilter = in_array($jenis, ['semua', 'masuk', 'keluar']) ? $jenis : 'semua';
        $this->resetPage();
    }

    public function updatedJenisFilter(): void
    {
        $this->resetPage();
    }

    public function updatedKategoriFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPeriodeFilter(): void
    {
        if ($this->periodeFilter === 'bulan_ini') {
            $this->startDate = now()->startOfMonth()->toDateString();
            $this->endDate = now()->endOfMonth()->toDateString();
        } elseif ($this->periodeFilter === 'semua') {
            $this->startDate = '';
            $this->endDate = '';
        }
        $this->resetPage();
    }

    public function updatedStartDate(): void
    {
        if (filled($this->startDate) || filled($this->endDate)) {
            $this->periodeFilter = 'kustom';
        }
        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        if (filled($this->startDate) || filled($this->endDate)) {
            $this->periodeFilter = 'kustom';
        }
        $this->resetPage();
    }

    public function setPeriode(string $periode): void
    {
        $this->periodeFilter = in_array($periode, ['semua', 'bulan_ini', 'kustom']) ? $periode : 'semua';
        $this->updatedPeriodeFilter();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->jenisFilter = 'semua';
        $this->kategoriFilter = '';
        $this->periodeFilter = 'semua';
        $this->startDate = '';
        $this->endDate = '';
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
        // Closure helper untuk filter tanggal
        $applyDateFilter = function ($q) {
            if ($this->periodeFilter === 'bulan_ini') {
                $q->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);
            } elseif ($this->periodeFilter === 'kustom' || filled($this->startDate) || filled($this->endDate)) {
                if (filled($this->startDate)) {
                    $q->where('tanggal', '>=', $this->startDate);
                }
                if (filled($this->endDate)) {
                    $q->where('tanggal', '<=', $this->endDate);
                }
            }
        };

        // 1. KPI Stats Kas RT (sesuai filter rentang tanggal terpilih)
        $kpiQuery = CashTransaction::query();
        $applyDateFilter($kpiQuery);

        $kpiStats = (clone $kpiQuery)->selectRaw("
            COUNT(*) as total_transaksi,
            SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END) as total_masuk,
            SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) as total_keluar
        ")->first();

        $totalTransaksi = (int) ($kpiStats->total_transaksi ?? 0);
        $totalMasuk = (float) ($kpiStats->total_masuk ?? 0);
        $totalKeluar = (float) ($kpiStats->total_keluar ?? 0);
        $saldoKas = $totalMasuk - $totalKeluar;

        // Saldo kumulatif all-time
        $saldoKumulatif = (float) CashTransaction::selectRaw("
            SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END) - SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) as saldo
        ")->value('saldo') ?? 0;

        // 2. Tab Counters (sesuai filter tanggal & kategori jika ada)
        $tabCountQuery = CashTransaction::query();
        $applyDateFilter($tabCountQuery);
        if (filled($this->kategoriFilter)) {
            $tabCountQuery->where('kategori', $this->kategoriFilter);
        }

        $tabStats = (clone $tabCountQuery)->selectRaw("
            COUNT(*) as count_semua,
            SUM(CASE WHEN jenis = 'masuk' THEN 1 ELSE 0 END) as count_masuk,
            SUM(CASE WHEN jenis = 'keluar' THEN 1 ELSE 0 END) as count_keluar
        ")->first();

        $countSemua = (int) ($tabStats->count_semua ?? 0);
        $countMasuk = (int) ($tabStats->count_masuk ?? 0);
        $countKeluar = (int) ($tabStats->count_keluar ?? 0);

        // 3. Kategori List untuk Filter Dropdown
        $kategoriList = CashTransaction::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        // 4. Query Data Transaksi Kas
        $query = CashTransaction::with(['pencatat'])->latest('tanggal')->latest('id');
        $applyDateFilter($query);

        if ($this->jenisFilter !== 'semua') {
            $query->where('jenis', $this->jenisFilter);
        }

        if (filled($this->kategoriFilter)) {
            $query->where('kategori', $this->kategoriFilter);
        }

        if (filled($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('deskripsi', 'like', "%{$term}%")
                  ->orWhere('kategori', 'like', "%{$term}%")
                  ->orWhere('jumlah', 'like', "%{$term}%")
                  ->orWhereHas('pencatat', function ($u) use ($term) {
                      $u->where('name', 'like', "%{$term}%");
                  });
            });
        }

        $transactions = $query->paginate($this->perPage);

        return [
            'transactions' => $transactions,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldoKas' => $saldoKas,
            'saldoKumulatif' => $saldoKumulatif,
            'totalTransaksi' => $totalTransaksi,
            'countSemua' => $countSemua,
            'countMasuk' => $countMasuk,
            'countKeluar' => $countKeluar,
            'kategoriList' => $kategoriList,
            'search' => $this->search,
            'jenisFilter' => $this->jenisFilter,
            'kategoriFilter' => $this->kategoriFilter,
            'periodeFilter' => $this->periodeFilter,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }

    // =========================================================================
    // MODAL DETAIL TRANSAKSI
    // =========================================================================
    public function openDetail(int $id): void
    {
        $this->selectedTransaction = CashTransaction::with('pencatat')->find($id);
        $this->detailModalOpen = true;
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedTransaction = null;
    }

    // =========================================================================
    // MODAL TAMBAH & EDIT TRANSAKSI KAS
    // =========================================================================
    public function openCreate(): void
    {
        abort_unless(CashTransactionResource::canCreate(), 403);

        $this->resetValidation();
        $this->isEditing = false;
        $this->transactionId = null;
        $this->form_jenis = 'masuk';
        $this->form_kategori = 'Iuran Warga';
        $this->form_jumlah = '';
        $this->form_tanggal = Carbon::now()->format('Y-m-d');
        $this->form_deskripsi = '';
        $this->form_bukti = null;
        $this->existing_bukti = null;
        $this->formModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $this->resetValidation();
        $tx = CashTransaction::findOrFail($id);
        abort_unless(CashTransactionResource::canEdit($tx), 403);

        $this->isEditing = true;
        $this->transactionId = $tx->id;
        $this->form_jenis = $tx->jenis;
        $this->form_kategori = $tx->kategori;
        $this->form_jumlah = (string) (int) $tx->jumlah;
        $this->form_tanggal = Carbon::parse($tx->tanggal)->format('Y-m-d');
        $this->form_deskripsi = $tx->deskripsi ?? '';
        $this->existing_bukti = $tx->bukti_path;
        $this->form_bukti = null;
        $this->formModalOpen = true;
    }

    public function closeFormModal(): void
    {
        $this->formModalOpen = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'form_jenis' => 'required|in:masuk,keluar',
            'form_kategori' => 'required|string|max:100',
            'form_jumlah' => 'required|numeric|min:1',
            'form_tanggal' => 'required|date',
            'form_deskripsi' => 'required|string|max:1000',
            'form_bukti' => 'nullable|image|max:3072',
        ], [
            'form_jenis.required' => 'Pilih jenis transaksi (masuk atau keluar).',
            'form_kategori.required' => 'Kategori transaksi wajib diisi.',
            'form_jumlah.required' => 'Nominal transaksi wajib diisi.',
            'form_jumlah.numeric' => 'Nominal harus berupa angka yang valid.',
            'form_tanggal.required' => 'Tanggal transaksi wajib ditentukan.',
            'form_deskripsi.required' => 'Keterangan/deskripsi transaksi wajib diisi.',
            'form_bukti.image' => 'File bukti harus berupa foto atau gambar (JPG, PNG, WEBP).',
            'form_bukti.max' => 'Ukuran file bukti maksimal 3MB.',
        ]);

        $buktiPath = $this->existing_bukti;
        if ($this->form_bukti) {
            $buktiPath = $this->form_bukti->store('cash-proofs', 'public');
        }

        if ($this->isEditing && $this->transactionId) {
            $tx = CashTransaction::findOrFail($this->transactionId);
            abort_unless(CashTransactionResource::canEdit($tx), 403);

            $tx->update([
                'jenis' => $this->form_jenis,
                'kategori' => $this->form_kategori,
                'jumlah' => $this->form_jumlah,
                'tanggal' => $this->form_tanggal,
                'deskripsi' => $this->form_deskripsi,
                'bukti_path' => $buktiPath,
            ]);

            Notification::make()
                ->title('Transaksi kas berhasil diperbarui')
                ->success()
                ->send();
        } else {
            abort_unless(CashTransactionResource::canCreate(), 403);

            CashTransaction::create([
                'jenis' => $this->form_jenis,
                'kategori' => $this->form_kategori,
                'jumlah' => $this->form_jumlah,
                'tanggal' => $this->form_tanggal,
                'deskripsi' => $this->form_deskripsi,
                'bukti_path' => $buktiPath,
                'dicatat_oleh' => Auth::id() ?? 1,
            ]);

            Notification::make()
                ->title('Transaksi kas baru berhasil dicatat')
                ->success()
                ->send();
        }

        $this->formModalOpen = false;
    }

    public function deleteTransaction(int $id): void
    {
        $tx = CashTransaction::findOrFail($id);
        abort_unless(CashTransactionResource::canDelete($tx), 403);

        if ($tx->bukti_path && Storage::disk('public')->exists($tx->bukti_path)) {
            Storage::disk('public')->delete($tx->bukti_path);
        }
        $tx->delete();

        Notification::make()
            ->title('Catatan transaksi kas berhasil dihapus')
            ->success()
            ->send();
    }

    // =========================================================================
    // EKSPOR LAPORAN BUKU KAS (CSV)
    // =========================================================================
    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="buku-kas-rt15-' . date('Y-m-d') . '.csv"',
        ];

        $jenis = $this->jenisFilter;
        $kategori = $this->kategoriFilter;
        $term = $this->search;

        return response()->streamDownload(function () use ($jenis, $kategori, $term) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            // Header Kolom CSV
            fputcsv($file, [
                'NO',
                'TANGGAL',
                'JENIS TRANSAKSI',
                'KATEGORI',
                'KETERANGAN / DESKRIPSI',
                'PEMASUKAN (RP)',
                'PENGELUARAN (RP)',
                'DICATAT OLEH',
            ]);

            $query = CashTransaction::with('pencatat')->latest('tanggal')->latest('id');

            if ($jenis !== 'semua') {
                $query->where('jenis', $jenis);
            }
            if (filled($kategori)) {
                $query->where('kategori', $kategori);
            }
            if (filled($term)) {
                $query->where(function ($q) use ($term) {
                    $q->where('deskripsi', 'like', "%{$term}%")
                      ->orWhere('kategori', 'like', "%{$term}%")
                      ->orWhere('jumlah', 'like', "%{$term}%")
                      ->orWhereHas('pencatat', function ($u) use ($term) {
                          $u->where('name', 'like', "%{$term}%");
                      });
                });
            }

            $no = 1;
            foreach ($query->cursor() as $tx) {
                fputcsv($file, [
                    $no++,
                    Carbon::parse($tx->tanggal)->translatedFormat('d F Y'),
                    strtoupper($tx->jenis),
                    $tx->kategori,
                    $tx->deskripsi,
                    $tx->jenis === 'masuk' ? number_format($tx->jumlah, 0, ',', '.') : '0',
                    $tx->jenis === 'keluar' ? number_format($tx->jumlah, 0, ',', '.') : '0',
                    $tx->pencatat?->name ?? 'Pengurus RT',
                ]);
            }

            fclose($file);
        }, 'buku-kas-rt15-' . date('Y-m-d') . '.csv', $headers);
    }
}
