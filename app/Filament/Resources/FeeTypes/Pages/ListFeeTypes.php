<?php

namespace App\Filament\Resources\FeeTypes\Pages;

use App\Filament\Resources\FeeTypes\FeeTypeResource;
use App\Models\FeeType;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListFeeTypes extends ListRecords
{
    protected static string $resource = FeeTypeResource::class;

    protected string $view = 'filament.resources.fee-types.pages.list-fee-types';

    #[Url]
    public string $search = '';

    public int $perPage = 25;

    // State Modal Form (Tambah / Ubah)
    public bool $feeTypeModalOpen = false;
    public bool $isEditing = false;
    public ?int $feeTypeId = null;
    public string $nama = '';
    public string $deskripsi = '';
    public string $nominal = '';
    public string $periode = 'bulanan';
    public bool $is_active = true;

    // State Modal Detail
    public bool $detailModalOpen = false;
    public ?FeeType $selectedFeeType = null;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
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
        $stats = FeeType::selectRaw("
            COUNT(*) as total_fee_types,
            COALESCE(SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END), 0) as count_active
        ")->first();

        $totalFeeTypes = (int) ($stats->total_fee_types ?? 0);
        $countActive = (int) ($stats->count_active ?? 0);

        $query = FeeType::withCount('invoices')->latest('id');

        if (filled($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('nama', 'like', "%{$term}%")
                  ->orWhere('deskripsi', 'like', "%{$term}%")
                  ->orWhere('nominal', 'like', "%{$term}%")
                  ->orWhere('periode', 'like', "%{$term}%");
            });
        }

        $feeTypes = $query->paginate($this->perPage);

        return [
            'feeTypes' => $feeTypes,
            'totalFeeTypes' => $totalFeeTypes,
            'countActive' => $countActive,
            'search' => $this->search,
        ];
    }

    // =========================================================================
    // MODAL TAMBAH & EDIT MASTER JENIS IURAN
    // =========================================================================
    public function openCreate(): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->feeTypeId = null;
        $this->nama = '';
        $this->deskripsi = '';
        $this->nominal = '25000';
        $this->periode = 'bulanan';
        $this->is_active = true;
        $this->feeTypeModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $this->resetValidation();
        $ft = FeeType::findOrFail($id);
        $this->isEditing = true;
        $this->feeTypeId = $ft->id;
        $this->nama = $ft->nama;
        $this->deskripsi = $ft->deskripsi ?? '';
        $this->nominal = (string) (int) $ft->nominal;
        $this->periode = $ft->periode;
        $this->is_active = (bool) $ft->is_active;
        $this->feeTypeModalOpen = true;
    }

    public function openDetail(int $id): void
    {
        $this->selectedFeeType = FeeType::withCount('invoices')->findOrFail($id);
        $this->detailModalOpen = true;
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedFeeType = null;
    }

    public function closeFormModal(): void
    {
        $this->feeTypeModalOpen = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'nama' => 'required|string|max:150',
            'deskripsi' => 'nullable|string|max:500',
            'nominal' => 'required|numeric|min:0',
            'periode' => 'required|in:bulanan,tahunan,sekali,kondisional',
            'is_active' => 'boolean',
        ], [
            'nama.required' => 'Nama jenis iuran wajib diisi.',
            'nominal.required' => 'Besaran nominal iuran wajib diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
            'periode.required' => 'Periode iuran wajib ditentukan.',
        ]);

        if ($this->isEditing && $this->feeTypeId) {
            $ft = FeeType::findOrFail($this->feeTypeId);
            $ft->update([
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'nominal' => $this->nominal,
                'periode' => $this->periode,
                'is_active' => $this->is_active,
            ]);

            Notification::make()
                ->title('Master jenis iuran berhasil diperbarui')
                ->success()
                ->send();
        } else {
            FeeType::create([
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'nominal' => $this->nominal,
                'periode' => $this->periode,
                'is_active' => $this->is_active,
            ]);

            Notification::make()
                ->title('Jenis iuran baru berhasil ditambahkan')
                ->success()
                ->send();
        }

        $this->feeTypeModalOpen = false;
    }

    public function toggleActive(int $id): void
    {
        $ft = FeeType::findOrFail($id);
        $ft->update(['is_active' => ! $ft->is_active]);

        $statusText = $ft->is_active ? 'diaktifkan' : 'dinonaktifkan';
        Notification::make()
            ->title("Jenis iuran '{$ft->nama}' berhasil {$statusText}")
            ->success()
            ->send();
    }

    public function deleteFeeType(int $id): void
    {
        $ft = FeeType::findOrFail($id);
        if ($ft->invoices()->exists()) {
            Notification::make()
                ->title('Jenis iuran ini sudah memiliki tagihan aktif dan tidak dapat dihapus.')
                ->danger()
                ->send();
            return;
        }

        $ft->delete();

        Notification::make()
            ->title('Jenis iuran berhasil dihapus')
            ->success()
            ->send();
    }

    // =========================================================================
    // EKSPOR CSV MASTER JENIS IURAN
    // =========================================================================
    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="master-jenis-iuran-rt15-' . date('Y-m-d') . '.csv"',
        ];

        $term = $this->search;

        return response()->streamDownload(function () use ($term) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($file, [
                'NO',
                'NAMA JENIS IURAN',
                'DESKRIPSI',
                'NOMINAL (RP)',
                'PERIODE',
                'STATUS AKTIF',
            ]);

            $query = FeeType::latest('id');

            if (filled($term)) {
                $query->where(function ($q) use ($term) {
                    $q->where('nama', 'like', "%{$term}%")
                      ->orWhere('deskripsi', 'like', "%{$term}%")
                      ->orWhere('nominal', 'like', "%{$term}%")
                      ->orWhere('periode', 'like', "%{$term}%");
                });
            }

            $no = 1;
            foreach ($query->cursor() as $ft) {
                fputcsv($file, [
                    $no++,
                    $ft->nama,
                    $ft->deskripsi ?? '-',
                    number_format($ft->nominal, 0, ',', '.'),
                    strtoupper($ft->periode),
                    $ft->is_active ? 'AKTIF' : 'NONAKTIF',
                ]);
            }

            fclose($file);
        }, 'master-jenis-iuran-rt15-' . date('Y-m-d') . '.csv', $headers);
    }
}
