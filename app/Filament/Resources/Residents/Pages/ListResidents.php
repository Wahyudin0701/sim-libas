<?php

namespace App\Filament\Resources\Residents\Pages;

use App\Filament\Resources\Residents\ResidentResource;
use App\Filament\Resources\Residents\Traits\HandlesEditResidentModal;
use App\Models\Resident;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListResidents extends ListRecords
{
    use HandlesEditResidentModal;

    protected static string $resource = ResidentResource::class;

    protected string $view = 'filament.resources.residents.pages.list-residents';

    #[Url]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = '';

    #[Url(as: 'gender')]
    public string $genderFilter = '';

    public int $perPage = 25;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('status') && empty($this->statusFilter)) {
            $this->statusFilter = (string) request('status');
        }
        if (request()->has('gender') && empty($this->genderFilter)) {
            $this->genderFilter = (string) request('gender');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedGenderFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->genderFilter = '';
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
        $stats = Resident::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as laki,
            SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as perempuan,
            SUM(CASE WHEN is_kepala_keluarga = 1 THEN 1 ELSE 0 END) as kepala
        ")->first();

        $totalWarga = (int) ($stats->total ?? 0);
        $totalLaki = (int) ($stats->laki ?? 0);
        $totalPerempuan = (int) ($stats->perempuan ?? 0);
        $totalKepala = (int) ($stats->kepala ?? 0);

        $query = Resident::with(['household'])
            ->latest('created_at');

        if (filled($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('nama', 'like', "%{$term}%")
                  ->orWhere('id_warga', 'like', "%{$term}%")
                  ->orWhere('telepon', 'like', "%{$term}%")
                  ->orWhere('pekerjaan', 'like', "%{$term}%")
                  ->orWhere('nik_hash', hash('sha256', $term))
                  ->orWhereHas('household', function ($h) use ($term) {
                      $h->where('id_kk', 'like', "%{$term}%")
                        ->orWhere('alamat', 'like', "%{$term}%");
                  });
            });
        }

        if (filled($this->statusFilter) && $this->statusFilter !== 'semua') {
            $query->where('status_kependudukan', $this->statusFilter);
        }

        if (filled($this->genderFilter) && $this->genderFilter !== 'semua') {
            $query->where('jenis_kelamin', $this->genderFilter);
        }

        $residents = $query->paginate($this->perPage);

        return [
            'residents' => $residents,
            'totalWarga' => $totalWarga,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
            'totalKepala' => $totalKepala,
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'genderFilter' => $this->genderFilter,
        ];
    }

    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-warga-rt15-' . date('Y-m-d') . '.csv"',
        ];

        $searchTerm = $this->search;
        $statusFilter = $this->statusFilter;
        $genderFilter = $this->genderFilter;

        return response()->streamDownload(function () use ($searchTerm, $statusFilter, $genderFilter) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, [
                'No',
                'ID Warga',
                'ID KK',
                'Nama Lengkap',
                'NIK',
                'L/P',
                'Hubungan Keluarga',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Pekerjaan',
                'No. Telepon / WA',
                'Alamat Rumah',
                'Status Kependudukan',
            ]);

            $query = Resident::with('household')->latest('created_at');

            if (filled($searchTerm)) {
                $term = trim($searchTerm);
                $query->where(function ($q) use ($term) {
                    $q->where('nama', 'like', "%{$term}%")
                      ->orWhere('id_warga', 'like', "%{$term}%")
                      ->orWhere('telepon', 'like', "%{$term}%")
                      ->orWhere('pekerjaan', 'like', "%{$term}%")
                      ->orWhere('nik_hash', hash('sha256', $term))
                      ->orWhereHas('household', function ($h) use ($term) {
                          $h->where('id_kk', 'like', "%{$term}%")
                            ->orWhere('alamat', 'like', "%{$term}%");
                      });
                });
            }

            if (filled($statusFilter) && $statusFilter !== 'semua') {
                $query->where('status_kependudukan', $statusFilter);
            }

            if (filled($genderFilter) && $genderFilter !== 'semua') {
                $query->where('jenis_kelamin', $genderFilter);
            }

            $index = 1;
            foreach ($query->cursor() as $row) {
                fputcsv($file, [
                    $index++,
                    $row->id_warga ?? '-',
                    $row->household?->id_kk ?? '-',
                    $row->nama,
                    $row->masked_nik ?? '-',
                    $row->jenis_kelamin,
                    ucwords(str_replace('_', ' ', $row->hubungan_keluarga ?? '-')),
                    $row->tempat_lahir ?? '-',
                    $row->tanggal_lahir?->format('d/m/Y') ?? '-',
                    $row->pekerjaan ?? '-',
                    $row->telepon ?? '-',
                    $row->household?->alamat ?? '-',
                    ucfirst($row->status_kependudukan ?? 'aktif'),
                ]);
            }
            fclose($file);
        }, 'data-warga-rt15-' . date('Y-m-d') . '.csv', $headers);
    }
}
