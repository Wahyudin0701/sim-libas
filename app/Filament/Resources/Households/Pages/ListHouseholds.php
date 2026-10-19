<?php

namespace App\Filament\Resources\Households\Pages;

use App\Filament\Resources\Households\HouseholdResource;
use App\Models\Household;
use App\Models\Resident;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListHouseholds extends ListRecords
{

    protected static string $resource = HouseholdResource::class;

    protected string $view = 'filament.resources.households.pages.list-households';

    #[Url]
    public string $search = '';

    #[Url(as: 'anggota')]
    public string $anggotaFilter = '';

    public int $perPage = 25;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('anggota') && empty($this->anggotaFilter)) {
            $this->anggotaFilter = (string) request('anggota');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedAnggotaFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->anggotaFilter = '';
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
        $totalKk = Household::count();
        $totalJiwa = Resident::where('status_kependudukan', 'aktif')->count();

        $query = Household::with(['kepalaKeluarga'])
            ->withCount('residents')
            ->latest('created_at');

        if (filled($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('alamat', 'like', "%{$term}%")
                  ->orWhere('id_kk', 'like', "%{$term}%")
                  ->orWhere('no_kk_hash', hash('sha256', $term))
                  ->orWhereHas('kepalaKeluarga', function ($k) use ($term) {
                      $k->where('nama', 'like', "%{$term}%")
                        ->orWhere('telepon', 'like', "%{$term}%")
                        ->orWhere('nik_hash', hash('sha256', $term));
                  });
            });
        }

        if (filled($this->anggotaFilter)) {
            if ($this->anggotaFilter === '6+') {
                $query->has('residents', '>=', 6);
            } elseif ($this->anggotaFilter === '0') {
                $query->doesntHave('residents');
            } else {
                $query->has('residents', '=', (int) $this->anggotaFilter);
            }
        }

        $households = $query->paginate($this->perPage);

        return [
            'households' => $households,
            'totalKk' => $totalKk,
            'totalJiwa' => $totalJiwa,
            'search' => $this->search,
            'anggotaFilter' => $this->anggotaFilter,
        ];
    }

    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-kartu-keluarga-rt15-' . date('Y-m-d') . '.csv"',
        ];

        $searchTerm = $this->search;
        $anggotaFilter = $this->anggotaFilter;

        return response()->streamDownload(function () use ($searchTerm, $anggotaFilter) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, ['No', 'ID KK', 'Nomor KK', 'Kepala Keluarga', 'NIK Kepala', 'No. Telepon / WA', 'Alamat', 'Foto KK', 'Jumlah Anggota']);

            $query = Household::with(['kepalaKeluarga'])->withCount('residents')->latest('created_at');

            if (filled($searchTerm)) {
                $term = trim($searchTerm);
                $query->where(function ($q) use ($term) {
                    $q->where('alamat', 'like', "%{$term}%")
                      ->orWhere('id_kk', 'like', "%{$term}%")
                      ->orWhere('no_kk_hash', hash('sha256', $term))
                      ->orWhereHas('kepalaKeluarga', function ($k) use ($term) {
                          $k->where('nama', 'like', "%{$term}%")
                            ->orWhere('telepon', 'like', "%{$term}%")
                            ->orWhere('nik_hash', hash('sha256', $term));
                      });
                });
            }

            if (filled($anggotaFilter)) {
                if ($anggotaFilter === '6+') {
                    $query->has('residents', '>=', 6);
                } elseif ($anggotaFilter === '0') {
                    $query->doesntHave('residents');
                } else {
                    $query->has('residents', '=', (int) $anggotaFilter);
                }
            }

            $index = 1;
            foreach ($query->cursor() as $row) {
                $kepala = $row->kepalaKeluarga;
                fputcsv($file, [
                    $index++,
                    $row->id_kk ?? '-',
                    $row->no_kk ?? '-',
                    $kepala?->nama ?? '-',
                    $kepala?->nik ?? '-',
                    $kepala?->telepon ?? '-',
                    $row->alamat ?? '-',
                    $row->foto_kk ? 'Ada' : 'Belum Ada',
                    $row->residents_count . ' Jiwa',
                ]);
            }
            fclose($file);
        }, 'data-kartu-keluarga-rt15-' . date('Y-m-d') . '.csv', $headers);
    }
}
