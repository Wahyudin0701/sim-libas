<?php

namespace App\Filament\Resources\WasteDeposits\Pages;

use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use App\Models\Household;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use App\Models\WasteWallet;
use App\Models\WasteWalletTransaction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListWasteDeposits extends ListRecords
{
    protected static string $resource = WasteDepositResource::class;

    protected string $view = 'filament.resources.waste-deposits.pages.list-waste-deposits';

    #[Url]
    public string $search = '';

    #[Url]
    public string $wasteTypeFilter = '';

    public int $perPage = 25;

    // State Modal Detail Setoran
    public bool $detailModalOpen = false;
    public ?WasteDeposit $selectedDeposit = null;

    // State Modal Form (Catat Penimbangan Baru / Ubah)
    public bool $formModalOpen = false;
    public bool $isEditing = false;
    public ?int $depositId = null;
    public ?int $form_household_id = null;
    public ?int $form_waste_type_id = null;
    public string $form_berat_kg = '1';
    public string $form_harga_saat_setor = '0';
    public string $form_nilai = '0';
    public string $form_catatan = '';

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('type') && empty($this->wasteTypeFilter)) {
            $this->wasteTypeFilter = (string) request('type');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedWasteTypeFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->wasteTypeFilter = '';
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

    private function formatCleanNumber(float|int|string|null $val): string
    {
        if ($val === null || $val === '') {
            return '0';
        }
        $floatVal = (float) str_replace(',', '.', (string) $val);
        return $floatVal == (int) $floatVal ? (string) (int) $floatVal : (string) $floatVal;
    }

    public function updatedFormWasteTypeId(): void
    {
        if ($this->form_waste_type_id) {
            $type = WasteType::find($this->form_waste_type_id);
            if ($type) {
                $this->form_harga_saat_setor = $this->formatCleanNumber($type->harga_per_kg);
                $this->recalculateNilai();
            }
        }
    }

    public function updatedFormBeratKg(): void
    {
        $this->recalculateNilai();
    }

    public function updatedFormHargaSaatSetor(): void
    {
        $this->recalculateNilai();
    }

    private function recalculateNilai(): void
    {
        $berat = (float) str_replace(',', '.', $this->form_berat_kg);
        $harga = (float) str_replace(',', '.', $this->form_harga_saat_setor);
        $this->form_nilai = (string) round($berat * $harga, 2);
    }

    public function openDetail(int $id): void
    {
        $this->selectedDeposit = WasteDeposit::with(['household.headOfHousehold', 'wasteType', 'pencatat'])->find($id);
        if ($this->selectedDeposit) {
            $this->detailModalOpen = true;
        }
    }

    public function closeDetail(): void
    {
        $this->detailModalOpen = false;
        $this->selectedDeposit = null;
    }

    public function openCreate(): void
    {
        abort_unless(WasteDepositResource::canCreate(), 403);

        $this->resetForm();
        $this->isEditing = false;
        $this->depositId = null;

        // Auto select first active waste type
        $firstType = WasteType::where('is_active', true)->first();
        if ($firstType) {
            $this->form_waste_type_id = $firstType->id;
            $this->form_harga_saat_setor = $this->formatCleanNumber($firstType->harga_per_kg);
            $this->recalculateNilai();
        }

        $this->formModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $dep = WasteDeposit::findOrFail($id);
        abort_unless(WasteDepositResource::canEdit($dep), 403);

        $this->depositId = $dep->id;
        $this->isEditing = true;
        $this->form_household_id = $dep->household_id;
        $this->form_waste_type_id = $dep->waste_type_id;
        $this->form_berat_kg = $this->formatCleanNumber($dep->berat_kg);
        $this->form_harga_saat_setor = $this->formatCleanNumber($dep->harga_saat_setor);
        $this->form_nilai = (string) $dep->nilai;
        $this->form_catatan = $dep->catatan ?? '';

        $this->formModalOpen = true;
    }

    public function closeForm(): void
    {
        $this->formModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->form_household_id = null;
        $this->form_waste_type_id = null;
        $this->form_berat_kg = '1';
        $this->form_harga_saat_setor = '0';
        $this->form_nilai = '0';
        $this->form_catatan = '';
        $this->depositId = null;
        $this->isEditing = false;
    }

    public function saveDeposit(): void
    {
        $this->validate([
            'form_household_id' => 'required|exists:households,id',
            'form_waste_type_id' => 'required|exists:waste_types,id',
            'form_berat_kg' => 'required|numeric|min:0.01',
            'form_harga_saat_setor' => 'required|numeric|min:0',
            'form_catatan' => 'nullable|string|max:500',
        ], [
            'form_household_id.required' => 'Keluarga penyetor sampah wajib dipilih.',
            'form_waste_type_id.required' => 'Jenis komoditas sampah wajib dipilih.',
            'form_berat_kg.required' => 'Berat timbangan sampah wajib diisi.',
            'form_berat_kg.min' => 'Berat timbangan minimal 0.01 kg.',
        ]);

        $berat = (float) str_replace(',', '.', $this->form_berat_kg);
        $harga = (float) str_replace(',', '.', $this->form_harga_saat_setor);
        $nilai = round($berat * $harga, 2);

        DB::transaction(function () use ($berat, $harga, $nilai) {
            $currentUserId = auth()->id() ?? 1;

            if ($this->isEditing && $this->depositId) {
                $dep = WasteDeposit::findOrFail($this->depositId);
                abort_unless(WasteDepositResource::canEdit($dep), 403);
                $oldNilai = (float) $dep->nilai;
                $diff = $nilai - $oldNilai;

                $dep->update([
                    'household_id' => $this->form_household_id,
                    'waste_type_id' => $this->form_waste_type_id,
                    'berat_kg' => $berat,
                    'harga_saat_setor' => $harga,
                    'nilai' => $nilai,
                    'catatan' => $this->form_catatan,
                ]);

                // Sinkronkan saldo buku tabungan jika ada selisih nilai
                if ($diff != 0) {
                    $wallet = WasteWallet::firstOrCreate(
                        ['household_id' => $this->form_household_id],
                        ['saldo' => 0]
                    );
                    $saldoSebelum = (float) $wallet->saldo;
                    $wallet->saldo = max(0, $saldoSebelum + $diff);
                    $wallet->save();

                    WasteWalletTransaction::create([
                        'household_id' => $this->form_household_id,
                        'jenis' => 'setoran',
                        'jumlah' => abs($diff),
                        'saldo_sebelum' => $saldoSebelum,
                        'saldo_sesudah' => $wallet->saldo,
                        'keterangan' => 'Penyesuaian koreksi nilai setoran sampah #' . $dep->id,
                        'dicatat_oleh' => $currentUserId,
                    ]);
                }

                Notification::make()
                    ->title('Data Setoran Sampah Diperbarui')
                    ->body("Penimbangan untuk KK telah berhasil disesuaikan (Rp " . number_format($nilai, 0, ',', '.') . ").")
                    ->success()
                    ->send();
            } else {
                abort_unless(WasteDepositResource::canCreate(), 403);

                $dep = WasteDeposit::create([
                    'household_id' => $this->form_household_id,
                    'waste_type_id' => $this->form_waste_type_id,
                    'berat_kg' => $berat,
                    'harga_saat_setor' => $harga,
                    'nilai' => $nilai,
                    'catatan' => $this->form_catatan,
                    'dicatat_oleh' => $currentUserId,
                ]);

                // Tambahkan saldo ke Buku Tabungan Bank Sampah Keluarga
                $wallet = WasteWallet::firstOrCreate(
                    ['household_id' => $this->form_household_id],
                    ['saldo' => 0]
                );
                $saldoSebelum = (float) $wallet->saldo;
                $wallet->saldo = $saldoSebelum + $nilai;
                $wallet->save();

                $wasteType = WasteType::find($this->form_waste_type_id);
                $namaKomoditas = $wasteType ? $wasteType->nama : 'Sampah';

                WasteWalletTransaction::create([
                    'household_id' => $this->form_household_id,
                    'jenis' => 'setoran',
                    'jumlah' => $nilai,
                    'saldo_sebelum' => $saldoSebelum,
                    'saldo_sesudah' => $wallet->saldo,
                    'keterangan' => "Setoran {$namaKomoditas} ({$berat} kg)",
                    'dicatat_oleh' => $currentUserId,
                ]);

                Notification::make()
                    ->title('Penimbangan Sampah Berhasil Dicatat')
                    ->body("Nilai tabungan Rp " . number_format($nilai, 0, ',', '.') . " telah ditambahkan ke buku tabungan warga.")
                    ->success()
                    ->send();
            }
        });

        $this->closeForm();
    }

    public function deleteDeposit(int $id): void
    {
        $dep = WasteDeposit::findOrFail($id);
        abort_unless(WasteDepositResource::canDelete($dep), 403);

        DB::transaction(function () use ($dep) {
            $wallet = WasteWallet::where('household_id', $dep->household_id)->first();
            if ($wallet && (float) $wallet->saldo >= (float) $dep->nilai) {
                $saldoSebelum = (float) $wallet->saldo;
                $wallet->saldo = max(0, $saldoSebelum - (float) $dep->nilai);
                $wallet->save();

                WasteWalletTransaction::create([
                    'household_id' => $dep->household_id,
                    'jenis' => 'pencairan',
                    'jumlah' => (float) $dep->nilai,
                    'saldo_sebelum' => $saldoSebelum,
                    'saldo_sesudah' => $wallet->saldo,
                    'keterangan' => 'Pembatalan/penghapusan data setoran penimbangan #' . $dep->id,
                    'dicatat_oleh' => auth()->id() ?? 1,
                ]);
            }

            $dep->delete();
        });

        Notification::make()
            ->title('Catatan Penimbangan Dihapus')
            ->body('Data setoran bank sampah telah dihapus dan tabungan disinkronkan.')
            ->success()
            ->send();
    }

    public function export(): StreamedResponse
    {
        $fileName = 'setoran-bank-sampah-rt15-' . now()->format('Y-m-d-His') . '.csv';

        $query = WasteDeposit::with(['household.headOfHousehold', 'wasteType', 'pencatat'])->latest();

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereHas('household.headOfHousehold', function ($q2) use ($term) {
                    $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                })
                ->orWhereHas('household', function ($q2) use ($term) {
                    $q2->whereRaw('LOWER(no_kk) LIKE ?', ["%{$term}%"]);
                })
                ->orWhereHas('wasteType', function ($q2) use ($term) {
                    $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                })
                ->orWhereRaw('LOWER(catatan) LIKE ?', ["%{$term}%"]);
            });
        }

        if (filled($this->wasteTypeFilter)) {
            $query->where('waste_type_id', $this->wasteTypeFilter);
        }

        $records = $query->get();

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'ID',
                'Tanggal Setor',
                'Kepala Keluarga',
                'Nomor KK',
                'Jenis Komoditas Sampah',
                'Kategori',
                'Berat (Kg/Satuan)',
                'Harga per Kg (Rp)',
                'Nilai Tabungan (Rp)',
                'Petugas Pencatat',
                'Catatan',
            ]);

            foreach ($records as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->created_at->format('Y-m-d H:i'),
                    $item->household && $item->household->headOfHousehold ? $item->household->headOfHousehold->nama : 'Warga RT 15',
                    $item->household ? "'{$item->household->no_kk}'" : '-',
                    $item->wasteType ? $item->wasteType->nama : '-',
                    $item->wasteType ? strtoupper($item->wasteType->kategori) : '-',
                    number_format($item->berat_kg, 2, ',', '.'),
                    number_format($item->harga_saat_setor, 0, ',', '.'),
                    number_format($item->nilai, 0, ',', '.'),
                    $item->pencatat ? $item->pencatat->name : 'Pengurus',
                    $item->catatan ?? '-',
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
        $stats = WasteDeposit::selectRaw("
            COUNT(*) as total_transaksi,
            SUM(berat_kg) as total_berat,
            SUM(nilai) as total_nilai
        ")->first();

        $kpiTotalTransaksi = (int) ($stats->total_transaksi ?? 0);
        $kpiTotalBerat = (float) ($stats->total_berat ?? 0);
        $kpiTotalNilai = (float) ($stats->total_nilai ?? 0);
        $kpiAvgBerat = $kpiTotalTransaksi > 0 ? ($kpiTotalBerat / $kpiTotalTransaksi) : 0;

        $query = WasteDeposit::with(['household.headOfHousehold', 'wasteType', 'pencatat'])->latest();

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereHas('household.headOfHousehold', function ($q2) use ($term) {
                    $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                })
                ->orWhereHas('household', function ($q2) use ($term) {
                    $q2->whereRaw('LOWER(no_kk) LIKE ?', ["%{$term}%"]);
                })
                ->orWhereHas('wasteType', function ($q2) use ($term) {
                    $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                })
                ->orWhereRaw('LOWER(catatan) LIKE ?', ["%{$term}%"]);
            });
        }

        if (filled($this->wasteTypeFilter)) {
            $query->where('waste_type_id', $this->wasteTypeFilter);
        }

        $deposits = $query->paginate($this->perPage);

        $householdList = Household::with('headOfHousehold')->orderBy('id')->get();
        $wasteTypeList = WasteType::orderBy('nama')->get();

        return [
            'deposits' => $deposits,
            'householdList' => $householdList,
            'wasteTypeList' => $wasteTypeList,
            'kpiTotalBerat' => $kpiTotalBerat,
            'kpiTotalNilai' => $kpiTotalNilai,
            'kpiTotalTransaksi' => $kpiTotalTransaksi,
            'kpiAvgBerat' => $kpiAvgBerat,
        ];
    }
}
