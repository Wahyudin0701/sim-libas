<?php

namespace App\Filament\Resources\Letters\Pages;

use App\Filament\Resources\Letters\LetterResource;
use App\Models\Letter;
use App\Models\LetterType;
use App\Models\Resident;
use App\Services\NotificationService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListLetters extends ListRecords
{
    protected static string $resource = LetterResource::class;

    protected string $view = 'filament.resources.letters.pages.list-letters';

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'semua';

    #[Url]
    public string $typeFilter = '';

    public int $perPage = 15;

    // --- State Modal Detail Surat ---
    public bool $detailLetterModalOpen = false;
    public ?Letter $detailLetter = null;

    // --- State Modal Setujui Surat ---
    public bool $approveLetterModalOpen = false;
    public ?int $approve_letter_id = null;
    public ?Letter $approveLetter = null;
    public string $approve_nomor_surat = '';
    public string $approve_catatan = '';

    // --- State Modal Tolak Surat ---
    public bool $rejectLetterModalOpen = false;
    public ?int $reject_letter_id = null;
    public ?Letter $rejectLetter = null;
    public string $reject_alasan = '';

    // --- State Modal Form Tambah / Ubah Surat ---
    public bool $letterModalOpen = false;
    public bool $isEditingLetter = false;
    public ?int $editing_letter_id = null;
    public ?int $form_resident_id = null;
    public string $form_resident_query = '';
    public ?int $form_letter_type_id = null;
    public string $form_keperluan = '';
    public string $form_status = 'diajukan';
    public string $form_nomor_surat = '';
    public string $form_catatan_pengurus = '';

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }

        if (request()->has('status') && !empty(request('status'))) {
            $this->statusFilter = (string) request('status');
        }

        if (request()->has('type') && empty($this->typeFilter)) {
            $this->typeFilter = (string) request('type');
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

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function setStatus(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'semua';
        $this->typeFilter = '';
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

    /**
     * Data utama untuk tampilan daftar surat pengantar.
     */
    protected function getViewData(): array
    {
        $stats = Letter::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'diajukan' THEN 1 ELSE 0 END) as diajukan,
            SUM(CASE WHEN status = 'diperiksa' THEN 1 ELSE 0 END) as diperiksa,
            SUM(CASE WHEN status = 'disetujui' THEN 1 ELSE 0 END) as disetujui,
            SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
        ")->first();

        $totalSemua = (int) ($stats->total ?? 0);
        $totalDiajukan = (int) ($stats->diajukan ?? 0);
        $totalDiperiksa = (int) ($stats->diperiksa ?? 0);
        $totalDisetujui = (int) ($stats->disetujui ?? 0);
        $totalDitolak = (int) ($stats->ditolak ?? 0);

        $query = Letter::with(['letterType', 'resident.household', 'approver', 'verifier'])
            ->latest('created_at');

        // Filter Status
        if (filled($this->statusFilter) && $this->statusFilter !== 'semua') {
            $query->where('status', $this->statusFilter);
        }

        // Filter Jenis Surat
        if (filled($this->typeFilter)) {
            $query->where('letter_type_id', $this->typeFilter);
        }

        // Pencarian Real-Time
        if (filled($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat', 'like', "%{$term}%")
                  ->orWhere('keperluan', 'like', "%{$term}%")
                  ->orWhere('catatan_pengurus', 'like', "%{$term}%")
                  ->orWhereHas('letterType', function ($lt) use ($term) {
                      $lt->where('nama', 'like', "%{$term}%")
                         ->orWhere('kode', 'like', "%{$term}%");
                  })
                  ->orWhereHas('resident', function ($r) use ($term) {
                      $r->where('nama', 'like', "%{$term}%")
                        ->orWhere('id_warga', 'like', "%{$term}%")
                        ->orWhere('telepon', 'like', "%{$term}%")
                        ->orWhere('nik_hash', hash('sha256', $term));
                  });
            });
        }

        $letters = $query->paginate($this->perPage);
        $residents = Resident::select('id', 'nama', 'id_warga', 'nik_encrypted')->orderBy('nama')->get();
        $letterTypes = LetterType::where('is_active', true)->orderBy('urutan')->get();

        $residentOptions = $residents->map(function ($r) {
            return [
                'id' => $r->id,
                'nama' => $r->nama,
                'id_warga' => $r->id_warga ?? '',
                'masked_nik' => $r->masked_nik ?? '-',
            ];
        })->values()->toArray();

        return [
            'letters' => $letters,
            'totalSemua' => $totalSemua,
            'totalDiajukan' => $totalDiajukan,
            'totalDiperiksa' => $totalDiperiksa,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
            'residents' => $residents,
            'residentOptions' => $residentOptions,
            'letterTypes' => $letterTypes,
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'typeFilter' => $this->typeFilter,
        ];
    }

    // =========================================================================
    // MODAL DETAIL SURAT
    // =========================================================================

    public function openDetailLetter(int $id): void
    {
        $letter = Letter::with(['letterType', 'resident.household', 'approver', 'verifier', 'attachments'])->findOrFail($id);

        // Jika status masih 'diajukan', otomatis beralih menjadi 'diperiksa' saat detail dibuka
        if ($letter->status === 'diajukan') {
            $letter->update([
                'status' => 'diperiksa',
                'diverifikasi_oleh' => auth()->id(),
            ]);
            $letter->refresh();
        }

        $this->detailLetter = $letter;
        $this->detailLetterModalOpen = true;
        $this->dispatch('open-detail-letter-modal');
    }

    public function closeDetailLetter(): void
    {
        $this->detailLetterModalOpen = false;
        $this->detailLetter = null;
        $this->dispatch('close-detail-letter-modal');
    }

    // =========================================================================
    // MODAL SETUJUI SURAT
    // =========================================================================

    public function generateProposedNumber(Letter $letter): string
    {
        $format = $letter->letterType?->format_nomor ?: '{NO}/SP/RT.15/{ROMAN_MONTH}/{YEAR}';
        $year = date('Y');

        $count = Letter::whereYear('created_at', $year)
            ->whereNotNull('nomor_surat')
            ->count() + 1;

        $noPad = str_pad($count, 3, '0', STR_PAD_LEFT);
        $bulanRomawi = match ((int) date('n')) {
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        };

        return str_replace(
            ['{NO}', '{ROMAN_MONTH}', '{YEAR}'],
            [$noPad, $bulanRomawi, $year],
            $format
        );
    }

    public function openApproveLetter(int $id): void
    {
        $this->approveLetter = Letter::with(['letterType', 'resident'])->findOrFail($id);
        $this->approve_letter_id = $id;
        $this->approve_nomor_surat = $this->approveLetter->nomor_surat ?: $this->generateProposedNumber($this->approveLetter);
        $this->approve_catatan = (string) ($this->approveLetter->catatan_pengurus ?? '');
        $this->approveLetterModalOpen = true;
        $this->dispatch('open-approve-letter-modal');
    }

    public function confirmApproveLetter(): void
    {
        $this->validate([
            'approve_nomor_surat' => ['required', 'string', 'max:100'],
            'approve_catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'approve_nomor_surat.required' => 'Nomor resmi surat pengantar wajib diisi.',
        ]);

        if (!$this->approve_letter_id) {
            return;
        }

        $letter = Letter::findOrFail($this->approve_letter_id);
        $letter->update([
            'status' => 'disetujui',
            'nomor_surat' => $this->approve_nomor_surat,
            'catatan_pengurus' => $this->approve_catatan ?: $letter->catatan_pengurus,
            'disetujui_oleh' => auth()->id(),
            'tanggal_disetujui' => now(),
        ]);

        // Kirim notifikasi sistem ke warga pemohon
        NotificationService::notifyLetterApproved($letter);

        Notification::make()
            ->title('Surat Pengantar Berhasil Disetujui')
            ->body("Nomor Surat Resmi: {$letter->nomor_surat}")
            ->success()
            ->send();

        $this->closeApproveLetter();

        // Refresh detail letter jika sedang terbuka
        if ($this->detailLetterModalOpen && $this->detailLetter?->id === $letter->id) {
            $this->detailLetter = $letter->fresh(['letterType', 'resident.household', 'approver']);
        }
    }

    public function closeApproveLetter(): void
    {
        $this->approveLetterModalOpen = false;
        $this->approve_letter_id = null;
        $this->approveLetter = null;
        $this->approve_nomor_surat = '';
        $this->approve_catatan = '';
        $this->dispatch('close-approve-letter-modal');
    }

    // =========================================================================
    // MODAL TOLAK SURAT
    // =========================================================================

    public function openRejectLetter(int $id): void
    {
        $this->rejectLetter = Letter::with(['letterType', 'resident'])->findOrFail($id);
        $this->reject_letter_id = $id;
        $this->reject_alasan = '';
        $this->rejectLetterModalOpen = true;
        $this->dispatch('open-reject-letter-modal');
    }

    public function confirmRejectLetter(): void
    {
        $this->validate([
            'reject_alasan' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'reject_alasan.required' => 'Alasan penolakan surat wajib diisi agar pemohon mengetahuinya.',
            'reject_alasan.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        if (!$this->reject_letter_id) {
            return;
        }

        $letter = Letter::findOrFail($this->reject_letter_id);
        $letter->update([
            'status' => 'ditolak',
            'alasan_tolak' => $this->reject_alasan,
            'disetujui_oleh' => auth()->id(),
        ]);

        // Kirim notifikasi sistem ke warga pemohon
        NotificationService::notifyLetterRejected($letter, $this->reject_alasan);

        Notification::make()
            ->title('Permohonan Surat Ditolak')
            ->body('Catatan penolakan telah disimpan.')
            ->warning()
            ->send();

        $this->closeRejectLetter();

        // Refresh detail letter jika sedang terbuka
        if ($this->detailLetterModalOpen && $this->detailLetter?->id === $letter->id) {
            $this->detailLetter = $letter->fresh(['letterType', 'resident.household', 'approver']);
        }
    }

    public function closeRejectLetter(): void
    {
        $this->rejectLetterModalOpen = false;
        $this->reject_letter_id = null;
        $this->rejectLetter = null;
        $this->reject_alasan = '';
        $this->dispatch('close-reject-letter-modal');
    }

    // =========================================================================
    // MODAL FORM TAMBAH / UBAH SURAT
    // =========================================================================

    public function updatedFormStatus(string $value): void
    {
        if ($value === 'diajukan' || $value === 'diperiksa') {
            $this->form_nomor_surat = '';
            $this->form_catatan_pengurus = '';
        } elseif ($value === 'ditolak') {
            $this->form_nomor_surat = '';
        }
    }

    public function openCreateLetter(): void
    {
        $this->isEditingLetter = false;
        $this->editing_letter_id = null;
        $this->form_resident_id = null;
        $this->form_resident_query = '';
        $this->form_letter_type_id = null;
        $this->form_keperluan = '';
        $this->form_status = 'diajukan';
        $this->form_nomor_surat = '';
        $this->form_catatan_pengurus = '';

        $this->resetValidation();
        $this->letterModalOpen = true;
        $this->dispatch('open-letter-form-modal');
    }

    public function openEditLetter(int $id): void
    {
        $letter = Letter::with('resident')->findOrFail($id);

        $this->isEditingLetter = true;
        $this->editing_letter_id = $letter->id;
        $this->form_resident_id = $letter->resident_id;
        $this->form_resident_query = $letter->resident ? ($letter->resident->nama . ($letter->resident->id_warga ? " [{$letter->resident->id_warga}]" : '')) : '';
        $this->form_letter_type_id = $letter->letter_type_id;
        $this->form_keperluan = (string) $letter->keperluan;
        $this->form_status = (string) $letter->status;
        $this->form_nomor_surat = (string) ($letter->nomor_surat ?? '');
        $this->form_catatan_pengurus = (string) ($letter->catatan_pengurus ?? '');

        $this->resetValidation();
        $this->letterModalOpen = true;
        $this->dispatch('open-letter-form-modal');
    }

    public function saveLetter(): void
    {
        // 1. Verifikasi dan Resolusi Warga Pemohon dari Autocomplete / Input Bebas
        $queryText = trim((string) $this->form_resident_query);

        if (filled($queryText)) {
            $currentResident = $this->form_resident_id ? Resident::find($this->form_resident_id) : null;
            $isMatchingCurrent = false;

            if ($currentResident) {
                $expectedDisplay = $currentResident->nama . ($currentResident->id_warga ? " [{$currentResident->id_warga}]" : '');
                if (
                    strcasecmp($currentResident->nama, $queryText) === 0 ||
                    ($currentResident->id_warga && strcasecmp($currentResident->id_warga, $queryText) === 0) ||
                    strcasecmp($expectedDisplay, $queryText) === 0
                ) {
                    $isMatchingCurrent = true;
                }
            }

            if (!$isMatchingCurrent) {
                // Pengurus mengetik nama atau ID secara manual, cari pencocokan pasti di database
                $cleanQuery = trim(preg_replace('/\[.*?\]/', '', $queryText));
                $matchedResident = Resident::where(function ($q) use ($queryText, $cleanQuery) {
                    $q->where('id_warga', $queryText)
                      ->orWhere('id_warga', $cleanQuery)
                      ->orWhere('nama', $queryText)
                      ->orWhere('nama', $cleanQuery);
                })->first();

                if ($matchedResident) {
                    $this->form_resident_id = $matchedResident->id;
                    $this->form_resident_query = $matchedResident->nama . ($matchedResident->id_warga ? " [{$matchedResident->id_warga}]" : '');
                } else {
                    $this->addError('form_resident_id', 'Warga tidak ditemukan. Silakan pilih warga yang terdaftar.');
                    return;
                }
            }
        } elseif (!$this->form_resident_id) {
            $this->addError('form_resident_id', 'Warga tidak ditemukan. Silakan pilih warga yang terdaftar.');
            return;
        }

        // Pastikan warga benar-benar terdaftar di database
        if (!$this->form_resident_id || !Resident::where('id', $this->form_resident_id)->exists()) {
            $this->addError('form_resident_id', 'Warga tidak ditemukan. Silakan pilih warga yang terdaftar.');
            return;
        }

        $rules = [
            'form_resident_id' => ['required', 'exists:residents,id'],
            'form_letter_type_id' => ['required', 'exists:letter_types,id'],
            'form_keperluan' => ['required', 'string', 'min:5', 'max:1000'],
            'form_status' => ['required', 'in:diajukan,diperiksa,disetujui,ditolak'],
            'form_nomor_surat' => ['nullable', 'string', 'max:100'],
            'form_catatan_pengurus' => ['nullable', 'string', 'max:1000'],
        ];

        if ($this->form_status === 'disetujui') {
            $rules['form_nomor_surat'] = ['required', 'string', 'min:3', 'max:100'];
        } elseif ($this->form_status === 'ditolak') {
            $rules['form_catatan_pengurus'] = ['required', 'string', 'min:3', 'max:1000'];
        }

        $this->validate($rules, [
            'form_resident_id.required' => 'Warga tidak ditemukan. Silakan pilih warga yang terdaftar.',
            'form_resident_id.exists' => 'Warga tidak ditemukan. Silakan pilih warga yang terdaftar.',
            'form_letter_type_id.required' => 'Jenis surat pengantar wajib dipilih.',
            'form_keperluan.required' => 'Keperluan permohonan surat wajib diisi.',
            'form_keperluan.min' => 'Keperluan permohonan minimal 5 karakter.',
            'form_nomor_surat.required' => 'Nomor resmi surat wajib diisi ketika permohonan disetujui.',
            'form_nomor_surat.min' => 'Nomor resmi surat minimal 3 karakter.',
            'form_catatan_pengurus.required' => 'Catatan / alasan penolakan wajib diisi ketika permohonan ditolak.',
            'form_catatan_pengurus.min' => 'Catatan / alasan penolakan minimal 3 karakter.',
        ]);

        // Aturan data berdasarkan status: diajukan & diperiksa tidak menggunakan nomor surat & catatan pengurus
        if ($this->form_status === 'diajukan' || $this->form_status === 'diperiksa') {
            $this->form_nomor_surat = '';
            $this->form_catatan_pengurus = '';
        } elseif ($this->form_status === 'ditolak') {
            $this->form_nomor_surat = '';
        }

        if ($this->isEditingLetter && $this->editing_letter_id) {
            $letter = Letter::findOrFail($this->editing_letter_id);
            $updateData = [
                'resident_id' => $this->form_resident_id,
                'letter_type_id' => $this->form_letter_type_id,
                'keperluan' => $this->form_keperluan,
                'status' => $this->form_status,
                'nomor_surat' => $this->form_nomor_surat ?: null,
                'catatan_pengurus' => $this->form_catatan_pengurus ?: null,
            ];

            if ($this->form_status === 'diperiksa' && empty($letter->diverifikasi_oleh)) {
                $updateData['diverifikasi_oleh'] = auth()->id();
            }

            if ($this->form_status === 'disetujui' && empty($letter->tanggal_disetujui)) {
                $updateData['disetujui_oleh'] = auth()->id();
                $updateData['tanggal_disetujui'] = now();
            }

            if ($this->form_status === 'ditolak') {
                $updateData['alasan_tolak'] = $this->form_catatan_pengurus ?: $letter->alasan_tolak;
                $updateData['disetujui_oleh'] = auth()->id();
            }

            $oldStatus = $letter->status;
            $letter->update($updateData);

            if ($this->form_status === 'disetujui' && $oldStatus !== 'disetujui') {
                NotificationService::notifyLetterApproved($letter);
            } elseif ($this->form_status === 'ditolak' && $oldStatus !== 'ditolak') {
                NotificationService::notifyLetterRejected($letter, $this->form_catatan_pengurus);
            }

            Notification::make()
                ->title('Surat Pengantar Berhasil Diperbarui')
                ->success()
                ->send();
        } else {
            $createData = [
                'resident_id' => $this->form_resident_id,
                'letter_type_id' => $this->form_letter_type_id,
                'keperluan' => $this->form_keperluan,
                'status' => $this->form_status,
                'nomor_surat' => $this->form_nomor_surat ?: null,
                'catatan_pengurus' => $this->form_catatan_pengurus ?: null,
                'qr_token' => (string) Str::uuid(),
            ];

            if ($this->form_status === 'diperiksa') {
                $createData['diverifikasi_oleh'] = auth()->id();
            }

            if ($this->form_status === 'disetujui') {
                $createData['disetujui_oleh'] = auth()->id();
                $createData['tanggal_disetujui'] = now();
            }

            if ($this->form_status === 'ditolak') {
                $createData['alasan_tolak'] = $this->form_catatan_pengurus ?: null;
                $createData['disetujui_oleh'] = auth()->id();
            }

            Letter::create($createData);

            Notification::make()
                ->title('Surat Pengantar Berhasil Dibuat')
                ->success()
                ->send();
        }

        $this->closeLetterModal();
    }

    public function closeLetterModal(): void
    {
        $this->letterModalOpen = false;
        $this->isEditingLetter = false;
        $this->editing_letter_id = null;
        $this->dispatch('close-letter-form-modal');
    }

    // =========================================================================
    // HAPUS SURAT
    // =========================================================================

    public function deleteLetter(int $id): void
    {
        $letter = Letter::findOrFail($id);
        $nomor = $letter->nomor_surat ?: 'Surat Pengantar';
        $letter->delete();

        Notification::make()
            ->title('Surat Berhasil Dihapus')
            ->body("Data {$nomor} telah dihapus dari sistem.")
            ->success()
            ->send();
    }

    // =========================================================================
    // EKSPOR DATA CSV
    // =========================================================================

    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-surat-pengantar-rt15-' . date('Y-m-d') . '.csv"',
        ];

        $searchTerm = $this->search;
        $status = $this->statusFilter;
        $typeId = $this->typeFilter;

        return response()->streamDownload(function () use ($searchTerm, $status, $typeId) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, [
                'No',
                'Nomor Surat',
                'Jenis Surat',
                'Kode Surat',
                'Nama Pemohon',
                'NIK Pemohon',
                'No. Telepon / WA',
                'Alamat Pemohon',
                'Keperluan',
                'Status',
                'Catatan Pengurus',
                'Alasan Tolak',
                'Tanggal Pengajuan',
                'Disetujui Oleh',
                'Tanggal Disetujui',
            ]);

            $query = Letter::with(['letterType', 'resident.household', 'approver'])->latest('created_at');

            if (filled($status) && $status !== 'semua') {
                $query->where('status', $status);
            }

            if (filled($typeId)) {
                $query->where('letter_type_id', $typeId);
            }

            if (filled($searchTerm)) {
                $term = trim($searchTerm);
                $query->where(function ($q) use ($term) {
                    $q->where('nomor_surat', 'like', "%{$term}%")
                      ->orWhere('keperluan', 'like', "%{$term}%")
                      ->orWhere('catatan_pengurus', 'like', "%{$term}%")
                      ->orWhereHas('letterType', function ($lt) use ($term) {
                          $lt->where('nama', 'like', "%{$term}%")
                             ->orWhere('kode', 'like', "%{$term}%");
                      })
                      ->orWhereHas('resident', function ($r) use ($term) {
                          $r->where('nama', 'like', "%{$term}%")
                            ->orWhere('id_warga', 'like', "%{$term}%")
                            ->orWhere('telepon', 'like', "%{$term}%")
                            ->orWhere('nik_hash', hash('sha256', $term));
                      });
                });
            }

            $index = 1;
            foreach ($query->cursor() as $row) {
                fputcsv($file, [
                    $index++,
                    $row->nomor_surat ?? '-',
                    $row->letterType?->nama ?? '-',
                    $row->letterType?->kode ?? '-',
                    $row->resident?->nama ?? '-',
                    $row->resident?->masked_nik ?? '-',
                    $row->resident?->telepon ?? '-',
                    $row->resident?->household?->alamat ?? $row->resident?->alamat_domisili ?? '-',
                    $row->keperluan,
                    ucfirst($row->status),
                    $row->catatan_pengurus ?? '-',
                    $row->alasan_tolak ?? '-',
                    $row->created_at?->format('d/m/Y H:i') ?? '-',
                    $row->approver?->name ?? '-',
                    $row->tanggal_disetujui?->format('d/m/Y H:i') ?? '-',
                ]);
            }
            fclose($file);
        }, 'data-surat-pengantar-rt15-' . date('Y-m-d') . '.csv', $headers);
    }
}
