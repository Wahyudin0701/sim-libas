<?php

namespace App\Filament\Resources\Households\Pages;

use App\Filament\Resources\Households\HouseholdResource;
use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class EditHousehold extends Page
{
    use WithFileUploads;

    protected static string $resource = HouseholdResource::class;

    protected string $view = 'filament.resources.households.pages.edit-household';

    public int|string $record;
    public Household $household;

    // 1. Data Kartu Keluarga
    public ?string $id_kk = null;
    public string $no_kk = '';
    public string $alamat = '';
    public string $rt_rw = 'RT 15 / RW 04';
    public string $status_hunian = 'tetap';
    public ?string $existing_foto_kk = null;
    public ?string $existing_foto_kk_url = null;
    public bool $delete_existing_foto = false;
    public $foto_kk = null;

    // 2. Data Kepala Keluarga
    public ?int $kepala_id = null;
    public string $kepala_nama = '';
    public string $kepala_nik = '';
    public string $kepala_telepon = '';
    public string $kepala_email = '';
    public string $kepala_password = '';
    public string $kepala_jenis_kelamin = 'L';
    public string $kepala_status_perkawinan = 'Kawin';
    public string $kepala_tempat_lahir = 'Jambi';
    public ?string $kepala_tanggal_lahir = null;
    public string $kepala_agama = 'Islam';
    public string $kepala_pekerjaan = '';
    public string $kepala_pendidikan_terakhir = 'SMA / SMK / Sederajat';

    // 3. Data Anggota Keluarga (Dinamis / Repeater)
    public array $anggota_keluarga = [];

    // Tracking anggota yang dihapus
    public array $deleted_resident_ids = [];

    public function mount(int|string $record): void
    {
        $this->household = Household::with([
            'residents' => function ($q) {
                $q->with('user')->orderByRaw("CASE 
                    WHEN is_kepala_keluarga = 1 OR hubungan_keluarga = 'kepala_keluarga' THEN 1 
                    WHEN hubungan_keluarga = 'istri' THEN 2 
                    WHEN hubungan_keluarga = 'anak' THEN 3 
                    ELSE 4 
                END, tanggal_lahir ASC");
            },
            'kepalaKeluarga.user',
            'user',
        ])->findOrFail($record);

        $this->record = $this->household->id;

        // 1. Isi Data KK
        $this->id_kk = $this->household->id_kk;
        $this->no_kk = (string) $this->household->no_kk;
        $this->alamat = (string) $this->household->alamat;
        $this->rt_rw = (string) ($this->household->rt_rw ?? 'RT 15 / RW 04');
        $this->status_hunian = (string) ($this->household->status_hunian ?? 'tetap');
        $this->existing_foto_kk = $this->household->foto_kk;
        $this->existing_foto_kk_url = $this->household->foto_kk_url;

        // 2. Isi Data Kepala Keluarga
        $kepala = $this->household->kepalaKeluarga 
            ?? $this->household->residents->firstWhere('is_kepala_keluarga', true)
            ?? $this->household->residents->firstWhere('hubungan_keluarga', 'kepala_keluarga')
            ?? $this->household->residents->first();

        if ($kepala) {
            $this->kepala_id = $kepala->id;
            $this->kepala_nama = (string) $kepala->nama;
            $this->kepala_nik = (string) ($kepala->nik ?? '');
            $this->kepala_telepon = (string) ($kepala->telepon ?? '');
            $this->kepala_email = (string) ($kepala->email ?? $this->household->user?->email ?? '');
            $this->kepala_password = '';
            $this->kepala_jenis_kelamin = 'L';
            $this->kepala_status_perkawinan = 'Kawin';
            $this->kepala_tempat_lahir = (string) ($kepala->tempat_lahir ?? 'Jambi');
            $this->kepala_tanggal_lahir = $kepala->tanggal_lahir?->format('Y-m-d');
            $this->kepala_agama = (string) ($kepala->agama ?? 'Islam');
            $this->kepala_pekerjaan = (string) ($kepala->pekerjaan ?? '');
            $this->kepala_pendidikan_terakhir = (string) ($kepala->pendidikan_terakhir ?? 'SMA / SMK / Sederajat');
        }

        // 3. Isi Data Anggota Keluarga Lainnya
        $this->anggota_keluarga = [];
        foreach ($this->household->residents as $resident) {
            if ($kepala && $resident->id === $kepala->id) {
                continue;
            }

            $this->anggota_keluarga[] = [
                'id' => $resident->id,
                'id_warga' => $resident->id_warga,
                'nama' => (string) $resident->nama,
                'nik' => (string) ($resident->nik ?? ''),
                'hubungan_keluarga' => (string) ($resident->hubungan_keluarga ?? 'anak'),
                'jenis_kelamin' => (string) ($resident->jenis_kelamin ?? 'L'),
                'tempat_lahir' => (string) ($resident->tempat_lahir ?? 'Jambi'),
                'tanggal_lahir' => $resident->tanggal_lahir?->format('Y-m-d') ?? '',
                'agama' => (string) ($resident->agama ?? 'Islam'),
                'pekerjaan' => (string) ($resident->pekerjaan ?? ''),
                'pendidikan_terakhir' => (string) ($resident->pendidikan_terakhir ?? 'SMA / SMK / Sederajat'),
                'status_perkawinan' => (string) ($resident->status_perkawinan ?? 'Belum Kawin'),
                'telepon' => (string) ($resident->telepon ?? ''),
                'email' => (string) ($resident->email ?? $resident->user?->email ?? ''),
                'password' => '',
            ];
        }
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function addAnggota(): void
    {
        $this->anggota_keluarga[] = [
            'id' => null,
            'id_warga' => null,
            'nama' => '',
            'nik' => '',
            'hubungan_keluarga' => 'istri',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Jambi',
            'tanggal_lahir' => '',
            'agama' => $this->kepala_agama ?: 'Islam',
            'pekerjaan' => '',
            'pendidikan_terakhir' => 'SMA / SMK / Sederajat',
            'status_perkawinan' => 'Kawin',
            'telepon' => '',
            'email' => '',
            'password' => '',
        ];
    }

    public function removeAnggota(int $index): void
    {
        if (isset($this->anggota_keluarga[$index])) {
            $anggota = $this->anggota_keluarga[$index];
            if (!empty($anggota['id'])) {
                $this->deleted_resident_ids[] = $anggota['id'];
            }
            unset($this->anggota_keluarga[$index]);
            $this->anggota_keluarga = array_values($this->anggota_keluarga);
        }
    }

    public function updatedAnggotaKeluarga($value, $key): void
    {
        if (str_ends_with($key, '.hubungan_keluarga')) {
            $parts = explode('.', $key);
            $index = $parts[0] ?? null;
            if ($index !== null && isset($this->anggota_keluarga[$index])) {
                if ($value === 'istri') {
                    $this->anggota_keluarga[$index]['jenis_kelamin'] = 'P';
                    $this->anggota_keluarga[$index]['status_perkawinan'] = 'Kawin';
                } elseif ($value === 'anak') {
                    if (empty($this->anggota_keluarga[$index]['status_perkawinan']) || $this->anggota_keluarga[$index]['status_perkawinan'] === 'Kawin') {
                        $this->anggota_keluarga[$index]['status_perkawinan'] = 'Belum Kawin';
                    }
                }
            }
        }
    }

    public function removeFotoKk(): void
    {
        $this->foto_kk = null;
    }

    public function removeExistingFotoKk(): void
    {
        $this->existing_foto_kk = null;
        $this->existing_foto_kk_url = null;
        $this->delete_existing_foto = true;
    }

    public function updateHousehold(): void
    {
        $this->kepala_jenis_kelamin = 'L';
        $this->kepala_status_perkawinan = 'Kawin';

        foreach ($this->anggota_keluarga as $idx => $anggota) {
            if (($anggota['hubungan_keluarga'] ?? '') === 'istri') {
                $this->anggota_keluarga[$idx]['jenis_kelamin'] = 'P';
                $this->anggota_keluarga[$idx]['status_perkawinan'] = 'Kawin';
            }
        }

        $this->validate([
            'no_kk' => ['required', 'digits:16'],
            'alamat' => ['required', 'string', 'max:255'],
            'rt_rw' => ['nullable', 'string', 'max:50'],
            'status_hunian' => ['required', 'in:tetap,kontrak,kos'],
            'foto_kk' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:10240'],

            'kepala_nama' => ['required', 'string', 'max:100'],
            'kepala_nik' => ['required', 'digits:16'],
            'kepala_telepon' => ['nullable', 'string', 'max:30'],
            'kepala_email' => ['nullable', 'email', 'max:100', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($this->household->user_id)],
            'kepala_password' => ['nullable', 'string', 'min:6'],
            'kepala_jenis_kelamin' => ['required', 'in:L,P'],
            'kepala_status_perkawinan' => ['required', 'string'],
            'kepala_tempat_lahir' => ['nullable', 'string', 'max:50'],
            'kepala_tanggal_lahir' => ['nullable', 'date'],
            'kepala_agama' => ['nullable', 'string', 'max:50'],
            'kepala_pekerjaan' => ['nullable', 'string', 'max:100'],
            'kepala_pendidikan_terakhir' => ['nullable', 'string', 'max:50'],

            'anggota_keluarga.*.nama' => ['required', 'string', 'max:100'],
            'anggota_keluarga.*.nik' => ['required', 'digits:16'],
            'anggota_keluarga.*.hubungan_keluarga' => ['required', 'string'],
            'anggota_keluarga.*.jenis_kelamin' => ['required', 'in:L,P'],
            'anggota_keluarga.*.tempat_lahir' => ['nullable', 'string', 'max:50'],
            'anggota_keluarga.*.tanggal_lahir' => ['nullable', 'date'],
            'anggota_keluarga.*.agama' => ['nullable', 'string', 'max:50'],
            'anggota_keluarga.*.pekerjaan' => ['nullable', 'string', 'max:100'],
            'anggota_keluarga.*.pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'anggota_keluarga.*.status_perkawinan' => ['nullable', 'string', 'max:50'],
            'anggota_keluarga.*.telepon' => ['nullable', 'string', 'max:30'],
            'anggota_keluarga.*.email' => ['nullable', 'email', 'max:100'],
            'anggota_keluarga.*.password' => ['nullable', 'string', 'min:6'],
        ], [
            'no_kk.required' => 'Nomor Kartu Keluarga (KK) wajib diisi.',
            'no_kk.digits' => 'Nomor KK harus 16 digit angka resmi.',
            'alamat.required' => 'Alamat rumah wajib diisi.',
            'status_hunian.required' => 'Status hunian wajib dipilih.',
            'status_hunian.in' => 'Status hunian tidak valid.',
            'foto_kk.mimes' => 'Format Foto KK harus berformat JPG, JPEG, PNG, WEBP, atau PDF.',
            'foto_kk.max' => 'Ukuran file Foto KK maksimal 10 MB.',
            'kepala_nama.required' => 'Nama Kepala Keluarga wajib diisi.',
            'kepala_nik.required' => 'NIK Kepala Keluarga wajib diisi.',
            'kepala_nik.digits' => 'NIK Kepala Keluarga harus 16 digit angka.',
            'kepala_email.unique' => 'Email ini sudah digunakan oleh akun pengguna lain.',
            'kepala_password.min' => 'Kata sandi baru Kepala Keluarga minimal harus 6 karakter.',
            'anggota_keluarga.*.nama.required' => 'Nama anggota keluarga wajib diisi.',
            'anggota_keluarga.*.nik.required' => 'NIK anggota keluarga wajib diisi.',
            'anggota_keluarga.*.nik.digits' => 'NIK anggota keluarga harus 16 digit angka.',
            'anggota_keluarga.*.hubungan_keluarga.required' => 'Hubungan keluarga wajib dipilih.',
            'anggota_keluarga.*.jenis_kelamin.required' => 'Jenis kelamin anggota wajib dipilih.',
            'anggota_keluarga.*.email.email' => 'Format email anggota keluarga tidak valid.',
            'anggota_keluarga.*.password.min' => 'Kata sandi anggota keluarga minimal harus 6 karakter.',
        ]);

        // Cek duplikasi Nomor KK (kecuali KK ini sendiri, termasuk soft-deleted)
        $kkHash = hash('sha256', $this->no_kk);
        if (Household::withTrashed()->where('no_kk_hash', $kkHash)->where('id', '!=', $this->household->id)->exists()) {
            $this->addError('no_kk', 'Nomor KK ini sudah digunakan oleh data KK lain (termasuk pada data arsip/terhapus).');
            return;
        }

        // Cek duplikasi NIK Kepala Keluarga (kecuali diri sendiri, termasuk soft-deleted)
        $kepalaNikHash = hash('sha256', $this->kepala_nik);
        $kepalaQuery = Resident::withTrashed()->where('nik_hash', $kepalaNikHash);
        if ($this->kepala_id) {
            $kepalaQuery->where('id', '!=', $this->kepala_id);
        }
        if ($kepalaQuery->exists()) {
            $this->addError('kepala_nik', 'NIK Kepala Keluarga sudah terdaftar di sistem pada data warga lain (termasuk pada data arsip/terhapus).');
            return;
        }

        // Cek duplikasi NIK di dalam form
        $niks = [$this->kepala_nik];
        foreach ($this->anggota_keluarga as $idx => $anggota) {
            $nik = $anggota['nik'] ?? '';
            if (in_array($nik, $niks)) {
                $this->addError("anggota_keluarga.{$idx}.nik", "NIK duplikat di dalam formulir ini.");
                return;
            }
            $niks[] = $nik;

            if ($nik) {
                $nikHash = hash('sha256', $nik);
                $q = Resident::withTrashed()->where('nik_hash', $nikHash);
                if (!empty($anggota['id'])) {
                    $q->where('id', '!=', $anggota['id']);
                }
                if ($q->exists()) {
                    $this->addError("anggota_keluarga.{$idx}.nik", "NIK {$anggota['nama']} sudah terdaftar pada data warga lain (termasuk pada data arsip/terhapus).");
                    return;
                }
            }
        }

        // Cek duplikasi Email di dalam form & pengguna lain
        $formEmails = [];
        if (!empty($this->kepala_email)) {
            $formEmails[] = strtolower(trim($this->kepala_email));
        }

        foreach ($this->anggota_keluarga as $idx => $anggota) {
            $email = !empty($anggota['email']) ? strtolower(trim($anggota['email'])) : null;
            if ($email) {
                if (in_array($email, $formEmails)) {
                    $this->addError("anggota_keluarga.{$idx}.email", "Email {$email} digunakan lebih dari satu kali di formulir ini.");
                    return;
                }
                $formEmails[] = $email;

                $resId = $anggota['id'] ?? null;
                $existingUserId = null;
                if ($resId) {
                    $existingUserId = Resident::find($resId)?->user_id;
                }
                $userConflictQuery = User::where('email', $email);
                if ($existingUserId) {
                    $userConflictQuery->where('id', '!=', $existingUserId);
                }
                if ($userConflictQuery->exists()) {
                    $this->addError("anggota_keluarga.{$idx}.email", "Email {$email} sudah digunakan oleh akun lain di sistem.");
                    return;
                }
            }
        }

        DB::transaction(function () use ($kkHash) {
            // 1. Upload foto baru jika ada
            $fotoKkPath = $this->household->foto_kk;
            if ($this->foto_kk && !is_string($this->foto_kk)) {
                if ($this->household->foto_kk) {
                    Storage::disk('public')->delete($this->household->foto_kk);
                }
                $fotoKkPath = $this->foto_kk->store('households/foto_kk', 'public');
            } elseif ($this->delete_existing_foto) {
                if ($this->household->foto_kk) {
                    Storage::disk('public')->delete($this->household->foto_kk);
                }
                $fotoKkPath = null;
            }

            // 2. Update data Household
            $this->household->update([
                'no_kk' => $this->no_kk,
                'no_kk_hash' => $kkHash,
                'alamat' => $this->alamat,
                'status_hunian' => $this->status_hunian,
                'foto_kk' => $fotoKkPath,
            ]);

            // 3. Update / Buat User Akun Kepala Keluarga jika ada
            $userEmail = !empty($this->kepala_email) ? $this->kepala_email : ($this->kepala_nik . '@warga.simlibas.com');
            $kepalaUserId = $this->household->user_id;

            $kepalaUser = $this->household->user;
            if (!$kepalaUser && $this->kepala_id) {
                $kepalaUser = Resident::find($this->kepala_id)?->user;
            }

            if ($kepalaUser) {
                $userUpdates = ['name' => $this->kepala_nama];
                if (!empty($this->kepala_telepon)) {
                    $userUpdates['phone'] = $this->kepala_telepon;
                }
                if (!empty($this->kepala_email)) {
                    $userUpdates['email'] = $this->kepala_email;
                }
                $userUpdates['password'] = Hash::make($this->kepala_nik);
                $kepalaUser->update($userUpdates);
                $kepalaUserId = $kepalaUser->id;
            } else {
                $newUser = User::firstOrCreate(
                    ['email' => $userEmail],
                    [
                        'name' => $this->kepala_nama,
                        'phone' => $this->kepala_telepon ?: null,
                        'password' => Hash::make($this->kepala_nik),
                        'status' => 'aktif',
                    ]
                );
                $newUser->update([
                    'name' => $this->kepala_nama,
                    'password' => Hash::make($this->kepala_nik),
                ]);
                if (!$newUser->hasRole('warga')) {
                    $newUser->assignRole('warga');
                }
                $kepalaUserId = $newUser->id;
            }

            $this->household->update([
                'user_id' => $kepalaUserId,
            ]);

            // 4. Update / Buat Kepala Keluarga
            $kepalaData = [
                'nama' => $this->kepala_nama,
                'nik' => $this->kepala_nik,
                'nik_hash' => hash('sha256', $this->kepala_nik),
                'tempat_lahir' => $this->kepala_tempat_lahir ?: 'Jambi',
                'tanggal_lahir' => $this->kepala_tanggal_lahir ?: null,
                'jenis_kelamin' => 'L',
                'agama' => $this->kepala_agama ?: 'Islam',
                'pekerjaan' => $this->kepala_pekerjaan ?: null,
                'pendidikan_terakhir' => $this->kepala_pendidikan_terakhir ?: null,
                'status_perkawinan' => 'Kawin',
                'telepon' => $this->kepala_telepon ?: null,
                'email' => $userEmail,
                'hubungan_keluarga' => 'kepala_keluarga',
                'is_kepala_keluarga' => true,
                'household_id' => $this->household->id,
                'user_id' => $kepalaUserId,
            ];

            if ($this->kepala_id) {
                $kepala = Resident::find($this->kepala_id);
                if ($kepala) {
                    $kepala->update($kepalaData);
                } else {
                    $kepala = Resident::create($kepalaData);
                    $this->kepala_id = $kepala->id;
                }
            } else {
                $kepala = Resident::create($kepalaData);
                $this->kepala_id = $kepala->id;
            }

            // 5. Update / Buat Anggota Keluarga Lainnya
            foreach ($this->anggota_keluarga as $anggota) {
                $anggotaEmail = !empty($anggota['email']) ? $anggota['email'] : ($anggota['nik'] . '@warga.simlibas.com');

                $targetResident = null;
                if (!empty($anggota['id'])) {
                    $targetResident = Resident::with('user')->find($anggota['id']);
                }

                $memberUserId = null;
                if ($targetResident && $targetResident->user) {
                    $userUpdates = ['name' => $anggota['nama']];
                    if (!empty($anggota['email'])) {
                        $userUpdates['email'] = $anggota['email'];
                    }
                    if (!empty($anggota['telepon'])) {
                        $userUpdates['phone'] = $anggota['telepon'];
                    }
                    if (!empty($anggota['nik'])) {
                        $userUpdates['password'] = Hash::make($anggota['nik']);
                    }
                    $targetResident->user->update($userUpdates);
                    $memberUserId = $targetResident->user_id;
                } else {
                    $newUser = User::firstOrCreate(
                        ['email' => $anggotaEmail],
                        [
                            'name' => $anggota['nama'],
                            'phone' => !empty($anggota['telepon']) ? $anggota['telepon'] : null,
                            'password' => Hash::make($anggota['nik']),
                            'status' => 'aktif',
                        ]
                    );
                    $newUser->update([
                        'name' => $anggota['nama'],
                        'password' => Hash::make($anggota['nik']),
                    ]);
                    if (!$newUser->hasRole('warga')) {
                        $newUser->assignRole('warga');
                    }
                    $memberUserId = $newUser->id;
                }

                $anggotaData = [
                    'nama' => $anggota['nama'],
                    'nik' => $anggota['nik'],
                    'nik_hash' => hash('sha256', $anggota['nik']),
                    'hubungan_keluarga' => $anggota['hubungan_keluarga'],
                    'jenis_kelamin' => ($anggota['hubungan_keluarga'] === 'istri') ? 'P' : ($anggota['jenis_kelamin'] ?? 'L'),
                    'tempat_lahir' => $anggota['tempat_lahir'] ?: 'Jambi',
                    'tanggal_lahir' => $anggota['tanggal_lahir'] ?: null,
                    'agama' => $anggota['agama'] ?: 'Islam',
                    'pekerjaan' => $anggota['pekerjaan'] ?: null,
                    'pendidikan_terakhir' => $anggota['pendidikan_terakhir'] ?: null,
                    'status_perkawinan' => ($anggota['hubungan_keluarga'] === 'istri') ? 'Kawin' : ($anggota['status_perkawinan'] ?? 'Belum Kawin'),
                    'telepon' => $anggota['telepon'] ?: null,
                    'email' => $anggotaEmail,
                    'user_id' => $memberUserId,
                    'is_kepala_keluarga' => false,
                    'household_id' => $this->household->id,
                ];

                if ($targetResident) {
                    $targetResident->update($anggotaData);
                } else {
                    Resident::create($anggotaData);
                }
            }

            // 6. Hapus anggota keluarga yang dihapus dari list
            if (!empty($this->deleted_resident_ids)) {
                Resident::whereIn('id', $this->deleted_resident_ids)->delete();
            }
        });

        Notification::make()
            ->title('Kartu Keluarga Berhasil Diperbarui')
            ->body("Data KK {$this->household->id_kk} ({$this->kepala_nama}) berhasil disimpan.")
            ->success()
            ->send();

        $this->redirect(url('/admin/households/' . $this->household->id));
    }

    public function deleteHousehold(): void
    {
        DB::transaction(function () {
            $this->household->residents()->delete();
            $this->household->delete();
        });

        Notification::make()
            ->title('Kartu Keluarga Berhasil Dihapus')
            ->body("Data KK {$this->household->id_kk} telah dipindahkan ke sampah.")
            ->warning()
            ->send();

        $this->redirect(HouseholdResource::getUrl('index'));
    }
}
