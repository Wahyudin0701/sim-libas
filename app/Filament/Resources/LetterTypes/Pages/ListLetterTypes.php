<?php

namespace App\Filament\Resources\LetterTypes\Pages;

use App\Filament\Resources\LetterTypes\LetterTypeResource;
use App\Models\Letter;
use App\Models\LetterType;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListLetterTypes extends ListRecords
{
    protected static string $resource = LetterTypeResource::class;

    protected string $view = 'filament.resources.letter-types.pages.list-letter-types';

    #[Url]
    public string $search = '';

    public int $perPage = 15;

    // --- State Modal Tambah / Ubah Jenis Surat ---
    public bool $letterTypeModalOpen = false;
    public bool $isEditing = false;
    public ?int $editing_id = null;
    public string $nama = '';
    public string $kode = '';
    public string $deskripsi = '';
    public string $format_nomor = '';
    public string $persyaratan_text = '';
    public bool $is_active = true;
    public int $urutan = 0;

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
        $stats = LetterType::selectRaw("
            COUNT(*) as total_jenis,
            COALESCE(SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END), 0) as total_aktif,
            COALESCE(SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END), 0) as total_nonaktif
        ")->first();

        $totalJenis = (int) ($stats->total_jenis ?? 0);
        $totalAktif = (int) ($stats->total_aktif ?? 0);
        $totalNonaktif = (int) ($stats->total_nonaktif ?? 0);

        $query = LetterType::withCount('letters')
            ->orderBy('urutan', 'asc')
            ->orderBy('nama', 'asc');

        if (filled($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('nama', 'like', "%{$term}%")
                  ->orWhere('kode', 'like', "%{$term}%")
                  ->orWhere('deskripsi', 'like', "%{$term}%")
                  ->orWhere('format_nomor', 'like', "%{$term}%");
            });
        }

        $letterTypes = $query->paginate($this->perPage);

        return [
            'letterTypes' => $letterTypes,
            'totalJenis' => $totalJenis,
            'totalAktif' => $totalAktif,
            'totalNonaktif' => $totalNonaktif,
            'search' => $this->search,
        ];
    }

    public function openCreate(): void
    {
        $this->isEditing = false;
        $this->editing_id = null;
        $this->nama = '';
        $this->kode = '';
        $this->deskripsi = '';
        $this->format_nomor = '{NO}/SP/RT.15/{ROMAN_MONTH}/{YEAR}';
        $this->persyaratan_text = "Foto KTP Pemohon\nFoto Kartu Keluarga";
        $this->is_active = true;
        $this->urutan = (LetterType::max('urutan') ?? 0) + 1;

        $this->resetValidation();
        $this->letterTypeModalOpen = true;
        $this->dispatch('open-letter-type-modal');
    }

    public function openEdit(int $id): void
    {
        $type = LetterType::findOrFail($id);

        $this->isEditing = true;
        $this->editing_id = $type->id;
        $this->nama = (string) $type->nama;
        $this->kode = (string) $type->kode;
        $this->deskripsi = (string) ($type->deskripsi ?? '');
        $this->format_nomor = (string) ($type->format_nomor ?? '');
        $this->is_active = (bool) $type->is_active;
        $this->urutan = (int) ($type->urutan ?? 0);

        $persyaratan = is_array($type->persyaratan) ? $type->persyaratan : [];
        $this->persyaratan_text = implode("\n", $persyaratan);

        $this->resetValidation();
        $this->letterTypeModalOpen = true;
        $this->dispatch('open-letter-type-modal');
    }

    public function save(): void
    {
        $this->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kode' => ['required', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'format_nomor' => ['required', 'string', 'max:100'],
            'persyaratan_text' => ['nullable', 'string', 'max:2000'],
            'urutan' => ['required', 'integer', 'min:0'],
        ], [
            'nama.required' => 'Nama jenis surat pengantar wajib diisi.',
            'kode.required' => 'Kode singkat surat (misal: SP-SKCK) wajib diisi.',
            'format_nomor.required' => 'Format penomoran surat resmi wajib diisi.',
        ]);

        // Parsing persyaratan baris per baris
        $persyaratanArray = [];
        if (filled($this->persyaratan_text)) {
            $lines = explode("\n", str_replace("\r", '', $this->persyaratan_text));
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) {
                    $persyaratanArray[] = $trimmed;
                }
            }
        }

        $data = [
            'nama' => $this->nama,
            'kode' => strtoupper(trim($this->kode)),
            'deskripsi' => $this->deskripsi ?: null,
            'format_nomor' => $this->format_nomor,
            'persyaratan' => $persyaratanArray,
            'is_active' => $this->is_active,
            'urutan' => $this->urutan,
        ];

        if ($this->isEditing && $this->editing_id) {
            $type = LetterType::findOrFail($this->editing_id);
            $type->update($data);

            Notification::make()
                ->title('Jenis Surat Diperbarui')
                ->body("Data template {$type->nama} berhasil disimpan.")
                ->success()
                ->send();
        } else {
            LetterType::create($data);

            Notification::make()
                ->title('Jenis Surat Baru Ditambahkan')
                ->body("Template {$this->nama} berhasil dibuat.")
                ->success()
                ->send();
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->letterTypeModalOpen = false;
        $this->isEditing = false;
        $this->editing_id = null;
        $this->dispatch('close-letter-type-modal');
    }

    public function toggleActive(int $id): void
    {
        $type = LetterType::findOrFail($id);
        $type->update(['is_active' => !$type->is_active]);

        $status = $type->is_active ? 'diaktifkan' : 'dinonaktifkan';

        Notification::make()
            ->title("Status Jenis Surat Diperbarui")
            ->body("{$type->nama} telah {$status}.")
            ->success()
            ->send();
    }

    public function deleteType(int $id): void
    {
        $type = LetterType::withCount('letters')->findOrFail($id);

        if ($type->letters_count > 0) {
            Notification::make()
                ->title('Tidak Dapat Dihapus')
                ->body("Jenis surat ini telah digunakan oleh {$type->letters_count} pengajuan surat warga. Anda dapat menonaktifkan statusnya sebagai gantinya.")
                ->danger()
                ->send();
            return;
        }

        $nama = $type->nama;
        $type->delete();

        Notification::make()
            ->title('Jenis Surat Berhasil Dihapus')
            ->body("Template {$nama} telah dihapus dari sistem.")
            ->success()
            ->send();
    }

    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="master-jenis-surat-rt15-' . date('Y-m-d') . '.csv"',
        ];

        $searchTerm = $this->search;

        return response()->streamDownload(function () use ($searchTerm) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, [
                'No',
                'Kode',
                'Nama Jenis Surat',
                'Deskripsi',
                'Format Nomor',
                'Persyaratan Dokumen',
                'Status Aktif',
                'Urutan',
            ]);

            $query = LetterType::orderBy('urutan')->orderBy('nama');

            if (filled($searchTerm)) {
                $term = trim($searchTerm);
                $query->where(function ($q) use ($term) {
                    $q->where('nama', 'like', "%{$term}%")
                      ->orWhere('kode', 'like', "%{$term}%")
                      ->orWhere('deskripsi', 'like', "%{$term}%")
                      ->orWhere('format_nomor', 'like', "%{$term}%");
                });
            }

            $index = 1;
            foreach ($query->cursor() as $row) {
                $persyaratan = is_array($row->persyaratan) ? implode(', ', $row->persyaratan) : '-';
                fputcsv($file, [
                    $index++,
                    $row->kode,
                    $row->nama,
                    $row->deskripsi ?? '-',
                    $row->format_nomor ?? '-',
                    $persyaratan,
                    $row->is_active ? 'Aktif' : 'Nonaktif',
                    $row->urutan,
                ]);
            }
            fclose($file);
        }, 'master-jenis-surat-rt15-' . date('Y-m-d') . '.csv', $headers);
    }
}
