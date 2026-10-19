<?php

namespace App\Filament\Resources\WasteTypes\Pages;

use App\Filament\Resources\WasteTypes\WasteTypeResource;
use App\Models\WasteType;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListWasteTypes extends ListRecords
{
    protected static string $resource = WasteTypeResource::class;

    protected string $view = 'filament.resources.waste-types.pages.list-waste-types';

    #[Url]
    public string $search = '';

    #[Url]
    public string $kategoriFilter = 'semua'; // 'semua', 'anorganik', 'organik', 'b3', 'lainnya'

    public int $perPage = 25;

    // State Modal Form (Tambah / Ubah Komoditas Sampah)
    public bool $formModalOpen = false;
    public bool $isEditing = false;
    public ?int $wasteTypeId = null;
    public string $form_nama = '';
    public string $form_kategori = 'anorganik';
    public string $form_harga_per_kg = '0';
    public string $form_satuan = 'kg';
    public bool $form_is_active = true;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('kategori') && $this->kategoriFilter === 'semua') {
            $this->kategoriFilter = (string) request('kategori');
        }
    }

    public function setKategori(string $kategori): void
    {
        $this->kategoriFilter = $kategori;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedKategoriFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->kategoriFilter = 'semua';
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

    public function toggleActive(int $id): void
    {
        $item = WasteType::findOrFail($id);
        $item->is_active = ! $item->is_active;
        $item->save();

        Notification::make()
            ->title($item->is_active ? 'Penerimaan Komoditas Dibuka' : 'Penerimaan Komoditas Ditutup')
            ->body("Status komoditas {$item->nama} kini " . ($item->is_active ? 'Menerima' : 'Ditutup') . ".")
            ->success()
            ->send();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->wasteTypeId = null;
        $this->formModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $item = WasteType::findOrFail($id);
        $this->wasteTypeId = $item->id;
        $this->isEditing = true;
        $this->form_nama = $item->nama;
        $this->form_kategori = $item->kategori;
        $this->form_harga_per_kg = (string) $item->harga_per_kg;
        $this->form_satuan = $item->satuan ?? 'kg';
        $this->form_is_active = (bool) $item->is_active;

        $this->formModalOpen = true;
    }

    public function closeForm(): void
    {
        $this->formModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->form_nama = '';
        $this->form_kategori = 'anorganik';
        $this->form_harga_per_kg = '0';
        $this->form_satuan = 'kg';
        $this->form_is_active = true;
        $this->wasteTypeId = null;
        $this->isEditing = false;
    }

    public function saveWasteType(): void
    {
        $this->validate([
            'form_nama' => 'required|string|max:255',
            'form_kategori' => 'required|in:organik,anorganik,b3,lainnya',
            'form_harga_per_kg' => 'required|numeric|min:0',
            'form_satuan' => 'required|string|max:50',
        ], [
            'form_nama.required' => 'Nama komoditas sampah wajib diisi.',
            'form_harga_per_kg.required' => 'Patokan harga beli per satuan wajib diisi.',
            'form_satuan.required' => 'Satuan timbangan wajib ditentukan.',
        ]);

        $harga = (float) str_replace(',', '.', $this->form_harga_per_kg);

        $payload = [
            'nama' => $this->form_nama,
            'kategori' => $this->form_kategori,
            'harga_per_kg' => $harga,
            'satuan' => strtolower(trim($this->form_satuan)),
            'is_active' => $this->form_is_active,
        ];

        if ($this->isEditing && $this->wasteTypeId) {
            $item = WasteType::findOrFail($this->wasteTypeId);
            $item->update($payload);

            Notification::make()
                ->title('Komoditas Sampah Diperbarui')
                ->body("Harga dan pengaturan {$item->nama} telah diperbarui.")
                ->success()
                ->send();
        } else {
            $item = WasteType::create($payload);

            Notification::make()
                ->title('Komoditas Baru Ditambahkan')
                ->body("Jenis sampah {$item->nama} telah didaftarkan ke master harga.")
                ->success()
                ->send();
        }

        $this->closeForm();
    }

    public function deleteWasteType(int $id): void
    {
        $item = WasteType::withCount('deposits')->findOrFail($id);

        if ($item->deposits_count > 0) {
            Notification::make()
                ->title('Tidak Dapat Dihapus')
                ->body("Komoditas {$item->nama} sudah memiliki {$item->deposits_count} riwayat transaksi setoran. Silakan nonaktifkan saja statusnya.")
                ->danger()
                ->send();

            return;
        }

        $item->delete();

        Notification::make()
            ->title('Komoditas Sampah Dihapus')
            ->body('Data komoditas telah berhasil dihapus.')
            ->success()
            ->send();
    }

    public function export(): StreamedResponse
    {
        $fileName = 'master-harga-sampah-rt15-' . now()->format('Y-m-d-His') . '.csv';

        $query = WasteType::withCount('deposits')->withSum('deposits', 'berat_kg')->latest();

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(satuan) LIKE ?', ["%{$term}%"]);
            });
        }

        if (in_array($this->kategoriFilter, ['organik', 'anorganik', 'b3', 'lainnya'])) {
            $query->where('kategori', $this->kategoriFilter);
        }

        $records = $query->get();

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'ID',
                'Nama Komoditas Sampah',
                'Kategori',
                'Patokan Harga Beli (Rp)',
                'Satuan',
                'Total Frekuensi Setoran',
                'Total Berat Terkumpul (Kg)',
                'Status',
            ]);

            foreach ($records as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->nama,
                    strtoupper($item->kategori),
                    number_format($item->harga_per_kg, 0, ',', '.'),
                    $item->satuan,
                    $item->deposits_count ?? 0,
                    number_format($item->deposits_sum_berat_kg ?? 0, 2, ',', '.'),
                    $item->is_active ? 'MENERIMA' : 'DITUTUP',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    protected function getViewData(): array
    {
        $stats = WasteType::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN kategori = 'anorganik' THEN 1 ELSE 0 END) as anorganik,
            SUM(CASE WHEN kategori = 'organik' THEN 1 ELSE 0 END) as organik,
            SUM(CASE WHEN kategori = 'b3' THEN 1 ELSE 0 END) as b3,
            SUM(CASE WHEN kategori = 'lainnya' THEN 1 ELSE 0 END) as lainnya,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active
        ")->first();

        $kpiTotal = (int) ($stats->total ?? 0);
        $kpiAnorganik = (int) ($stats->anorganik ?? 0);
        $kpiOrganik = (int) ($stats->organik ?? 0);
        $countB3 = (int) ($stats->b3 ?? 0);
        $countLainnya = (int) ($stats->lainnya ?? 0);
        $kpiActive = (int) ($stats->active ?? 0);

        $query = WasteType::withCount('deposits')->withSum('deposits', 'berat_kg')->orderBy('kategori')->orderBy('nama');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(satuan) LIKE ?', ["%{$term}%"]);
            });
        }

        if (in_array($this->kategoriFilter, ['organik', 'anorganik', 'b3', 'lainnya'])) {
            $query->where('kategori', $this->kategoriFilter);
        }

        $wasteTypes = $query->paginate($this->perPage);

        return [
            'wasteTypes' => $wasteTypes,
            'kpiTotal' => $kpiTotal,
            'kpiAnorganik' => $kpiAnorganik,
            'kpiOrganik' => $kpiOrganik,
            'kpiActive' => $kpiActive,
            'countSemua' => $kpiTotal,
            'countAnorganik' => $kpiAnorganik,
            'countOrganik' => $kpiOrganik,
            'countB3' => $countB3,
            'countLainnya' => $countLainnya,
        ];
    }
}
