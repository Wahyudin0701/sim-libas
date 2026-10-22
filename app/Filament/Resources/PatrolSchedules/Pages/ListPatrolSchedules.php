<?php

namespace App\Filament\Resources\PatrolSchedules\Pages;

use App\Filament\Resources\PatrolSchedules\PatrolScheduleResource;
use App\Models\PatrolExecution;
use App\Models\PatrolExecutionMember;
use App\Models\PatrolMember;
use App\Models\PatrolSchedule;
use App\Models\Resident;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListPatrolSchedules extends ListRecords
{
    protected static string $resource = PatrolScheduleResource::class;

    protected string $view = 'filament.resources.patrol-schedules.pages.list-patrol-schedules';

    #[Url]
    public string $search = '';

    #[Url]
    public string $reguFilter = '';

    // =========================================================================
    // STATE MODAL EDIT MASTER JADWAL RONDA (Pola Mingguan Tetap)
    // =========================================================================
    public bool $editMasterModalOpen = false;
    public ?int $selectedScheduleId = null;
    public ?PatrolSchedule $selectedSchedule = null;

    public int $form_hari_ke = 1;
    public string $form_nama_hari = 'Senin';
    public string $form_regu = '';
    public string $form_waktu_mulai = '22:00';
    public string $form_waktu_selesai = '04:00';
    public string $form_pos_lokasi = 'Pos Utama RT 15';
    public string $form_catatan = '';
    public bool $form_is_aktif = true;
    public array $form_resident_ids = [];
    public ?int $form_ketua_resident_id = null;

    // =========================================================================
    // STATE MODAL PRESENSI & PELAKSANAAN RONDA AKTUAL (Bertanggal)
    // =========================================================================
    public bool $detailModalOpen = false; // alias for presensi modal
    public bool $presensiModalOpen = false;
    public ?int $presensi_schedule_id = null;
    public ?PatrolSchedule $presensiSchedule = null;
    public ?PatrolExecution $currentExecution = null;

    public string $detail_tanggal = '';
    public string $detail_status = 'berlangsung'; // berlangsung, selesai, dibatalkan
    public string $detail_laporan_kejadian = '';
    public string $detail_alasan_batal = '';
    public array $presensi_members = [];

    // State Tambah Anggota Pengganti / Insidental Presensi
    public bool $showAddMemberForm = false;
    public ?int $new_resident_id = null;
    public string $new_status_kehadiran = 'hadir';
    public bool $new_is_pengganti = false;
    public ?int $new_menggantikan_resident_id = null;
    public string $new_keterangan = '';

    // =========================================================================
    // STATE MODAL RIWAYAT PELAKSANAAN (History)
    // =========================================================================
    public bool $historyModalOpen = false;
    public ?PatrolSchedule $historySchedule = null;

    public function mount(): void
    {
        if (request()->has('search') && empty($this->search)) {
            $this->search = (string) request('search');
        }
        if (request()->has('regu') && empty($this->reguFilter)) {
            $this->reguFilter = (string) request('regu');
        }
    }

    public function updatedReguFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->reguFilter = '';
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

    // =========================================================================
    // 1. KELOLA MASTER JADWAL RONDA (7 Hari: Senin s.d. Minggu)
    // =========================================================================

    public function openEditMaster(int $scheduleId): void
    {
        $this->selectedScheduleId = $scheduleId;
        $this->selectedSchedule = PatrolSchedule::with('members.resident')->findOrFail($scheduleId);

        $this->form_hari_ke = (int) $this->selectedSchedule->hari_ke;
        $this->form_nama_hari = $this->selectedSchedule->nama_hari;
        $this->form_regu = (string) $this->selectedSchedule->regu;
        $this->form_waktu_mulai = Carbon::parse($this->selectedSchedule->waktu_mulai)->format('H:i');
        $this->form_waktu_selesai = Carbon::parse($this->selectedSchedule->waktu_selesai)->format('H:i');
        $this->form_pos_lokasi = (string) ($this->selectedSchedule->pos_lokasi ?: 'Pos Utama RT 15');
        $this->form_catatan = (string) ($this->selectedSchedule->catatan ?: '');
        $this->form_is_aktif = (bool) $this->selectedSchedule->is_aktif;

        $this->form_resident_ids = $this->selectedSchedule->members->pluck('resident_id')->map(fn ($id) => (string) $id)->toArray();
        $ketua = $this->selectedSchedule->members->firstWhere('peran', 'ketua_regu');
        $this->form_ketua_resident_id = $ketua ? $ketua->resident_id : ($this->selectedSchedule->members->first()?->resident_id ?? null);

        $this->resetValidation();
        $this->editMasterModalOpen = true;
    }

    // Alias for backward compatibility
    public function openEdit(int $scheduleId): void
    {
        $this->openEditMaster($scheduleId);
    }

    public function closeEditMaster(): void
    {
        $this->editMasterModalOpen = false;
        $this->selectedScheduleId = null;
        $this->selectedSchedule = null;
    }

    public function saveMasterSchedule(): void
    {
        $this->validate([
            'form_regu' => 'required|string|max:255',
            'form_waktu_mulai' => 'required',
            'form_waktu_selesai' => 'required',
            'form_pos_lokasi' => 'required|string|max:255',
            'form_catatan' => 'nullable|string',
            'form_resident_ids' => 'required|array|min:1',
            'form_ketua_resident_id' => 'required|integer',
        ], [
            'form_regu.required' => 'Nama regu wajib diisi.',
            'form_waktu_mulai.required' => 'Waktu mulai ronda wajib diisi.',
            'form_waktu_selesai.required' => 'Waktu selesai ronda wajib diisi.',
            'form_pos_lokasi.required' => 'Pos / lokasi ronda wajib diisi.',
            'form_resident_ids.required' => 'Minimal pilih 1 warga sebagai personil tetap.',
            'form_resident_ids.min' => 'Minimal pilih 1 warga sebagai personil tetap.',
            'form_ketua_resident_id.required' => 'Pilih salah satu personil sebagai ketua regu.',
        ]);

        $schedule = PatrolSchedule::findOrFail($this->selectedScheduleId);
        $schedule->update([
            'regu' => $this->form_regu,
            'waktu_mulai' => $this->form_waktu_mulai,
            'waktu_selesai' => $this->form_waktu_selesai,
            'pos_lokasi' => $this->form_pos_lokasi,
            'catatan' => $this->form_catatan,
            'is_aktif' => $this->form_is_aktif,
        ]);

        // Sinkronisasi anggota master jadwal tetap
        PatrolMember::where('patrol_schedule_id', $schedule->id)->delete();
        foreach ($this->form_resident_ids as $rId) {
            PatrolMember::create([
                'patrol_schedule_id' => $schedule->id,
                'resident_id' => (int) $rId,
                'peran' => ((int) $rId === (int) $this->form_ketua_resident_id) ? 'ketua_regu' : 'anggota',
            ]);
        }

        $this->closeEditMaster();

        Notification::make()
            ->title('Master Jadwal Diperbarui')
            ->body("Pengaturan jadwal untuk hari {$schedule->nama_hari} berhasil disimpan. Perubahan berlaku untuk jadwal mingguan berikutnya tanpa memengaruhi riwayat terdahulu.")
            ->success()
            ->send();
    }

    // =========================================================================
    // 2. PRESENSI & PELAKSANAAN RONDA AKTUAL (Bertanggal)
    // =========================================================================

    public function openPresensi(int $scheduleId, ?string $tanggal = null): void
    {
        $this->selectedScheduleId = $scheduleId;
        $this->presensi_schedule_id = $scheduleId;
        $this->presensiSchedule = PatrolSchedule::with('members.resident')->findOrFail($scheduleId);
        $this->selectedSchedule = $this->presensiSchedule;

        // Tentukan tanggal aktual pelaksanaan
        if (!empty($tanggal)) {
            $this->detail_tanggal = $tanggal;
        } else {
            // Default: jika hari ini sesuai hari jadwal gunakan today(), bila bukan gunakan tanggal minggu berjalan
            $this->detail_tanggal = $this->presensiSchedule->is_today
                ? today()->toDateString()
                : $this->presensiSchedule->tanggal_minggu_ini;
        }

        // Cari apakah sudah ada sesi eksekusi pada tanggal ini
        $execution = PatrolExecution::with(['members.resident', 'members.menggantikanResident'])
            ->where('patrol_schedule_id', $scheduleId)
            ->whereDate('tanggal', $this->detail_tanggal)
            ->first();

        $this->currentExecution = $execution;

        if ($execution) {
            $this->detail_status = (string) $execution->status;
            $this->detail_laporan_kejadian = (string) ($execution->laporan_kejadian ?: '');
            $this->detail_alasan_batal = (string) ($execution->alasan_batal ?: '');

            $this->presensi_members = $execution->members->map(function ($m) {
                return [
                    'id' => $m->id,
                    'resident_id' => $m->resident_id,
                    'nama' => $m->resident?->nama ?? 'Warga RT 15',
                    'nik' => $m->resident?->nik ?? '-',
                    'status_kehadiran' => $m->status_kehadiran,
                    'waktu_absen' => $m->waktu_absen ? Carbon::parse($m->waktu_absen)->format('H:i') : null,
                    'is_pengganti' => (bool) $m->is_pengganti,
                    'menggantikan_resident_id' => $m->menggantikan_resident_id,
                    'menggantikan_nama' => $m->menggantikanResident?->nama ?? null,
                    'keterangan' => $m->keterangan ?? '',
                ];
            })->toArray();
        } else {
            // Inisialisasi awal dari template personil tetap master jadwal
            $isPast = Carbon::parse($this->detail_tanggal)->isPast() && !Carbon::parse($this->detail_tanggal)->isToday();
            $this->detail_status = $isPast ? 'selesai' : 'berlangsung';
            $this->detail_laporan_kejadian = '';
            $this->detail_alasan_batal = '';

            $this->presensi_members = $this->presensiSchedule->members->map(function ($pm) {
                return [
                    'id' => null,
                    'resident_id' => $pm->resident_id,
                    'nama' => $pm->resident?->nama ?? 'Warga RT 15',
                    'nik' => $pm->resident?->nik ?? '-',
                    'status_kehadiran' => 'hadir',
                    'waktu_absen' => now()->format('H:i'),
                    'is_pengganti' => false,
                    'menggantikan_resident_id' => null,
                    'menggantikan_nama' => null,
                    'keterangan' => '',
                ];
            })->toArray();
        }

        $this->resetNewMemberForm();
        $this->resetValidation();
        $this->presensiModalOpen = true;
        $this->detailModalOpen = true;
    }

    // Alias for backward compatibility
    public function openDetail(int $scheduleId): void
    {
        $this->openPresensi($scheduleId);
    }

    public function closePresensi(): void
    {
        $this->presensiModalOpen = false;
        $this->detailModalOpen = false;
        $this->presensi_schedule_id = null;
        $this->presensiSchedule = null;
        $this->selectedSchedule = null;
        $this->currentExecution = null;
        $this->presensi_members = [];
    }

    // Alias
    public function closeDetail(): void
    {
        $this->closePresensi();
    }

    public function setMemberStatus(int $index, string $status): void
    {
        if (isset($this->presensi_members[$index])) {
            $this->presensi_members[$index]['status_kehadiran'] = $status;
            if ($status === 'hadir' && empty($this->presensi_members[$index]['waktu_absen'])) {
                $this->presensi_members[$index]['waktu_absen'] = now()->format('H:i');
            } elseif ($status !== 'hadir') {
                $this->presensi_members[$index]['waktu_absen'] = null;
            }
        }
    }

    public function markAllHadir(): void
    {
        $nowTime = now()->format('H:i');
        foreach ($this->presensi_members as $index => $m) {
            $this->presensi_members[$index]['status_kehadiran'] = 'hadir';
            $this->presensi_members[$index]['waktu_absen'] = $m['waktu_absen'] ?: $nowTime;
        }

        Notification::make()
            ->title('Semua Ditandai Hadir')
            ->body('Seluruh personil berhasil ditandai hadir tepat waktu.')
            ->success()
            ->send();
    }

    public function toggleAddMemberForm(): void
    {
        $this->showAddMemberForm = !$this->showAddMemberForm;
        if (!$this->showAddMemberForm) {
            $this->resetNewMemberForm();
        }
    }

    public function resetNewMemberForm(): void
    {
        $this->showAddMemberForm = false;
        $this->new_resident_id = null;
        $this->new_status_kehadiran = 'hadir';
        $this->new_is_pengganti = false;
        $this->new_menggantikan_resident_id = null;
        $this->new_keterangan = '';
    }

    public function addMemberToSchedule(): void
    {
        $this->validate([
            'new_resident_id' => 'required|exists:residents,id',
            'new_status_kehadiran' => 'required|in:hadir,izin,sakit,alpa',
            'new_menggantikan_resident_id' => 'nullable|exists:residents,id',
            'new_keterangan' => 'nullable|string|max:255',
        ], [
            'new_resident_id.required' => 'Pilih warga yang akan ditambahkan.',
            'new_resident_id.exists' => 'Data warga tidak valid.',
        ]);

        $alreadyInList = collect($this->presensi_members)->contains('resident_id', $this->new_resident_id);
        if ($alreadyInList) {
            $this->addError('new_resident_id', 'Warga ini sudah ada dalam daftar personil ronda.');
            return;
        }

        $resident = Resident::find($this->new_resident_id);
        $menggantikanResident = $this->new_menggantikan_resident_id ? Resident::find($this->new_menggantikan_resident_id) : null;

        $this->presensi_members[] = [
            'id' => null,
            'resident_id' => $resident->id,
            'nama' => $resident->nama,
            'nik' => $resident->nik,
            'status_kehadiran' => $this->new_status_kehadiran,
            'waktu_absen' => $this->new_status_kehadiran === 'hadir' ? now()->format('H:i') : null,
            'is_pengganti' => (bool) $this->new_is_pengganti,
            'menggantikan_resident_id' => $menggantikanResident?->id,
            'menggantikan_nama' => $menggantikanResident?->nama,
            'keterangan' => $this->new_keterangan,
        ];

        $this->resetNewMemberForm();

        Notification::make()
            ->title('Personil Ditambahkan')
            ->body("{$resident->nama} berhasil ditambahkan ke daftar presensi malam ini.")
            ->success()
            ->send();
    }

    public function removeMemberFromSchedule(int $index): void
    {
        if (isset($this->presensi_members[$index])) {
            $nama = $this->presensi_members[$index]['nama'];
            unset($this->presensi_members[$index]);
            $this->presensi_members = array_values($this->presensi_members);

            Notification::make()
                ->title('Personil Dihapus')
                ->body("{$nama} telah dihapus dari daftar presensi malam ini.")
                ->info()
                ->send();
        }
    }

    public function savePresensiAndReport(): void
    {
        $this->validate([
            'detail_tanggal' => 'required|date',
            'detail_status' => 'required|in:berlangsung,selesai,dibatalkan',
            'detail_alasan_batal' => 'required_if:detail_status,dibatalkan',
            'detail_laporan_kejadian' => 'nullable|string',
            'presensi_members.*.status_kehadiran' => 'required|in:hadir,izin,sakit,alpa',
        ], [
            'detail_tanggal.required' => 'Tanggal pelaksanaan ronda wajib diisi.',
            'detail_status.required' => 'Status pelaksanaan ronda wajib dipilih.',
            'detail_alasan_batal.required_if' => 'Alasan pembatalan wajib diisi jika jadwal dibatalkan.',
        ]);

        $execution = PatrolExecution::updateOrCreate(
            [
                'patrol_schedule_id' => $this->selectedScheduleId,
                'tanggal' => $this->detail_tanggal,
            ],
            [
                'status' => $this->detail_status,
                'laporan_kejadian' => $this->detail_laporan_kejadian,
                'alasan_batal' => $this->detail_status === 'dibatalkan' ? $this->detail_alasan_batal : null,
            ]
        );

        // Bersihkan dan perbarui personil aktual malam ini
        PatrolExecutionMember::where('patrol_execution_id', $execution->id)->delete();
        foreach ($this->presensi_members as $m) {
            $waktuAbsen = null;
            if ($m['status_kehadiran'] === 'hadir') {
                $timeStr = !empty($m['waktu_absen']) ? $m['waktu_absen'] : '22:00';
                $waktuAbsen = Carbon::parse($this->detail_tanggal . ' ' . $timeStr);
            }

            PatrolExecutionMember::create([
                'patrol_execution_id' => $execution->id,
                'resident_id' => $m['resident_id'],
                'status_kehadiran' => $m['status_kehadiran'],
                'waktu_absen' => $waktuAbsen,
                'is_pengganti' => (bool) ($m['is_pengganti'] ?? false),
                'menggantikan_resident_id' => $m['menggantikan_resident_id'] ?? null,
                'keterangan' => $m['keterangan'] ?? null,
            ]);
        }

        $this->closePresensi();

        Notification::make()
            ->title('Pelaksanaan Ronda Tersimpan')
            ->body("Riwayat presensi dan laporan pelaksanaan tanggal " . Carbon::parse($this->detail_tanggal)->translatedFormat('d F Y') . " berhasil disimpan.")
            ->success()
            ->send();
    }

    // =========================================================================
    // 3. RIWAYAT PELAKSANAAN RONDA (History)
    // =========================================================================

    public function openHistory(int $scheduleId): void
    {
        $this->historySchedule = PatrolSchedule::with(['executions.members.resident', 'executions.members.menggantikanResident'])->findOrFail($scheduleId);
        $this->historyModalOpen = true;
    }

    public function closeHistory(): void
    {
        $this->historyModalOpen = false;
        $this->historySchedule = null;
    }

    // =========================================================================
    // 4. EKSPOR CSV MASTER JADWAL 7 HARI MINGGUAN
    // =========================================================================

    public function export(): StreamedResponse
    {
        $query = PatrolSchedule::with('members.resident')->orderBy('hari_ke', 'asc');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(regu) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(catatan) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(pos_lokasi) LIKE ?', ["%{$term}%"])
                  ->orWhereHas('residents', function ($rq) use ($term) {
                      $rq->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                  });
            });
        }

        if (filled($this->reguFilter)) {
            $query->where('regu', $this->reguFilter);
        }

        $records = $query->get();
        $filename = 'Master_Jadwal_Ronda_RT15_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            fputcsv($handle, [
                'NO',
                'HARI',
                'REGU RONDA',
                'JAM RONDA',
                'JUMLAH PERSONIL',
                'KETUA REGU',
                'DAFTAR ANGGOTA BERTUGAS',
                'POS LOKASI',
                'CATATAN / INSTRUKSI',
            ]);

            $no = 1;
            foreach ($records as $item) {
                $ketua = $item->ketua_regu?->nama ?? '-';
                $personil = $item->members->map(fn ($m) => ($m->peran === 'ketua_regu' ? '[Ketua] ' : '') . ($m->resident?->nama ?? 'Warga'))->implode(', ');
                $jam = ($item->waktu_mulai && $item->waktu_selesai) ? "{$item->waktu_mulai} - {$item->waktu_selesai} WIB" : '-';

                fputcsv($handle, [
                    $no++,
                    $item->nama_hari,
                    $item->regu,
                    $jam,
                    $item->members->count() . ' Orang',
                    $ketua,
                    $personil,
                    $item->pos_lokasi,
                    $item->catatan ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // =========================================================================
    // 5. DATA VIEW UTAMA (Master 7 Hari Tetap)
    // =========================================================================

    protected function getViewData(): array
    {
        $query = PatrolSchedule::with([
            'members.resident',
            'todayExecution.members.resident',
            'executions' => fn ($q) => $q->latest('tanggal')->take(5),
        ])->orderBy('hari_ke', 'asc');

        if (filled($this->search)) {
            $term = strtolower(trim($this->search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(regu) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(catatan) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(pos_lokasi) LIKE ?', ["%{$term}%"])
                  ->orWhereHas('residents', function ($rq) use ($term) {
                      $rq->whereRaw('LOWER(nama) LIKE ?', ["%{$term}%"]);
                  });
            });
        }

        if (filled($this->reguFilter)) {
            $query->where('regu', $this->reguFilter);
        }

        $schedules = $query->get();
        $allResidents = Resident::orderBy('nama', 'asc')->get(['id', 'nama', 'nik_encrypted']);
        $allRegus = PatrolSchedule::orderBy('hari_ke', 'asc')->pluck('regu')->unique()->values()->toArray();

        return [
            'schedules' => $schedules,
            'todayHariKe' => (int) now()->dayOfWeekIso, // 1 = Senin, ..., 7 = Minggu
            'allResidents' => $allResidents,
            'allRegus' => $allRegus,
        ];
    }
}
