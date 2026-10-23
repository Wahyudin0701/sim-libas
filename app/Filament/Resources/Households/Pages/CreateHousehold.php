<?php

namespace App\Filament\Resources\Households\Pages;

use App\Filament\Resources\Households\HouseholdResource;
use App\Models\Household;
use App\Models\Resident;
use App\Models\RtSetting;
use App\Models\User;
use App\Models\WasteWallet;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class CreateHousehold extends Page
{
    use WithFileUploads;

    protected static string $resource = HouseholdResource::class;

    protected string $view = 'filament.resources.households.pages.create-household';

    // 1. Data Kartu Keluarga
    public string $no_kk = '';
    public string $alamat = '';
    public string $rt_rw = 'RT 15 / RW 04';
    public string $status_hunian = 'tetap';
    public $foto_kk = null;

    // 2. Data Kepala Keluarga (Wajib)
    public string $kepala_nama = '';
    public string $kepala_nik = '';
    public string $kepala_telepon = '';
    public string $kepala_email = '';
    public string $kepala_jenis_kelamin = 'L'; // Otomatis Laki-laki
    public string $kepala_status_perkawinan = 'Kawin'; // Otomatis Kawin
    public string $kepala_tempat_lahir = 'Jambi';
    public ?string $kepala_tanggal_lahir = null;
    public string $kepala_agama = 'Islam';
    public string $kepala_pekerjaan = '';
    public string $kepala_pendidikan_terakhir = 'SMA / SMK / Sederajat';

    // 3. Data Anggota Keluarga (Dinamis / Repeater)
    public array $anggota_keluarga = [];

    public function mount(): void
    {
        $this->rt_rw = 'RT 15 / RW 04';
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
        ];
    }

    public function removeAnggota(int $index): void
    {
        unset($this->anggota_keluarga[$index]);
        $this->anggota_keluarga = array_values($this->anggota_keluarga);
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

    public function saveHousehold(): void
    {
        // Kepala keluarga otomatis berjenis kelamin Laki-laki (L) dan status perkawinan Kawin
        $this->kepala_jenis_kelamin = 'L';
        $this->kepala_status_perkawinan = 'Kawin';

        // Otomatis set jenis kelamin = P & status perkawinan = Kawin jika hubungan keluarga adalah istri
        foreach ($this->anggota_keluarga as $idx => $anggota) {
            if (($anggota['hubungan_keluarga'] ?? '') === 'istri') {
                $this->anggota_keluarga[$idx]['jenis_kelamin'] = 'P';
                $this->anggota_keluarga[$idx]['status_perkawinan'] = 'Kawin';
            }
        }

        $this->validate([
            // Validasi KK
            'no_kk' => ['required', 'digits:16'],
            'alamat' => ['required', 'string', 'max:255'],
            'rt_rw' => ['nullable', 'string', 'max:50'],
            'status_hunian' => ['required', 'in:tetap,kontrak,kos'],
            'foto_kk' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:10240'],

            // Validasi Kepala Keluarga
            'kepala_nama' => ['required', 'string', 'max:100'],
            'kepala_nik' => ['required', 'digits:16'],
            'kepala_telepon' => ['nullable', 'string', 'max:30'],
            'kepala_email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'kepala_jenis_kelamin' => ['required', 'in:L,P'],
            'kepala_status_perkawinan' => ['required', 'string'],
            'kepala_tempat_lahir' => ['nullable', 'string', 'max:50'],
            'kepala_tanggal_lahir' => ['nullable', 'date'],
            'kepala_agama' => ['nullable', 'string', 'max:50'],
            'kepala_pekerjaan' => ['nullable', 'string', 'max:100'],
            'kepala_pendidikan_terakhir' => ['nullable', 'string', 'max:50'],

            // Validasi Anggota Keluarga jika ada
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
            'kepala_email.unique' => 'Email Kepala Keluarga sudah digunakan oleh akun pengguna lain.',
            'anggota_keluarga.*.nama.required' => 'Nama anggota keluarga wajib diisi.',
            'anggota_keluarga.*.nik.required' => 'NIK anggota keluarga wajib diisi.',
            'anggota_keluarga.*.nik.digits' => 'NIK anggota keluarga harus 16 digit angka.',
            'anggota_keluarga.*.hubungan_keluarga.required' => 'Hubungan keluarga wajib dipilih.',
            'anggota_keluarga.*.jenis_kelamin.required' => 'Jenis kelamin anggota wajib dipilih.',
        ]);

        // Cek duplikasi Nomor KK via Hash SHA-256 (termasuk soft-deleted)
        $kkHash = hash('sha256', $this->no_kk);
        if (Household::withTrashed()->where('no_kk_hash', $kkHash)->exists()) {
            $this->addError('no_kk', 'Nomor KK ini sudah terdaftar di sistem (termasuk pada data arsip/terhapus).');
            return;
        }

        // Cek duplikasi NIK Kepala Keluarga (termasuk soft-deleted)
        $kepalaNikHash = hash('sha256', $this->kepala_nik);
        if (Resident::withTrashed()->where('nik_hash', $kepalaNikHash)->exists()) {
            $this->addError('kepala_nik', 'NIK Kepala Keluarga sudah terdaftar di sistem (termasuk pada data arsip/terhapus).');
            return;
        }

        // Cek duplikasi NIK Anggota Keluarga
        $niks = [$this->kepala_nik];
        foreach ($this->anggota_keluarga as $idx => $anggota) {
            $nik = $anggota['nik'] ?? '';
            if (in_array($nik, $niks)) {
                $this->addError("anggota_keluarga.{$idx}.nik", "NIK duplikat di dalam form ini.");
                return;
            }
            $niks[] = $nik;

            if ($nik) {
                $nikHash = hash('sha256', $nik);
                if (Resident::withTrashed()->where('nik_hash', $nikHash)->exists()) {
                    $this->addError("anggota_keluarga.{$idx}.nik", "NIK {$anggota['nama']} sudah terdaftar di sistem (termasuk pada data arsip/terhapus).");
                    return;
                }
            }
        }

        // Simpan file Foto Dokumen KK bila diunggah
        $fotoKkPath = null;
        if ($this->foto_kk && !is_string($this->foto_kk)) {
            $fotoKkPath = $this->foto_kk->store('households/foto_kk', 'public');
        }

        $anggotaCount = 0;
        $savedKepalaNama = $this->kepala_nama;

        DB::transaction(function () use (&$anggotaCount, $fotoKkPath) {
            $rtSetting = RtSetting::first();
            $kelurahan = $rtSetting?->kelurahan ?? 'Pematang Sulur';
            $kecamatan = $rtSetting?->kecamatan ?? 'Telanaipura';
            $defaultRtRw = $rtSetting?->nama_rt ?? 'RT 15 / RW 04';

            // 1. Buat / Hubungkan Akun User untuk Kepala Keluarga (Role: Warga)
            $userEmail = !empty($this->kepala_email) ? $this->kepala_email : $this->kepala_nik . '@warga.simlibas.com';
            $user = User::firstOrCreate(
                ['email' => $userEmail],
                [
                    'name' => $this->kepala_nama,
                    'phone' => $this->kepala_telepon ?: null,
                    'password' => Hash::make($this->kepala_nik),
                    'status' => 'aktif',
                ]
            );
            $user->update([
                'name' => $this->kepala_nama,
                'password' => Hash::make($this->kepala_nik),
            ]);
            if (!$user->hasRole('warga')) {
                $user->assignRole('warga');
            }

            // 2. Simpan Kartu Keluarga (Household)
            $household = Household::create([
                'user_id' => $user->id,
                'no_kk' => $this->no_kk,
                'alamat' => $this->alamat,
                'rt_rw' => $this->rt_rw ?: $defaultRtRw,
                'kelurahan' => $kelurahan,
                'kecamatan' => $kecamatan,
                'status_hunian' => $this->status_hunian ?: 'tetap',
                'foto_kk' => $fotoKkPath,
            ]);

            // 3. Simpan Kepala Keluarga (Resident)
            Resident::create([
                'household_id' => $household->id,
                'user_id' => $user->id,
                'nik' => $this->kepala_nik,
                'nama' => $this->kepala_nama,
                'tempat_lahir' => $this->kepala_tempat_lahir ?: 'Jambi',
                'tanggal_lahir' => $this->kepala_tanggal_lahir ?: '1980-01-01',
                'jenis_kelamin' => 'L',
                'agama' => $this->kepala_agama ?: 'Islam',
                'pekerjaan' => $this->kepala_pekerjaan ?: null,
                'pendidikan_terakhir' => $this->kepala_pendidikan_terakhir ?: 'SMA / SMK / Sederajat',
                'status_perkawinan' => 'Kawin',
                'hubungan_keluarga' => 'kepala_keluarga',
                'status_kependudukan' => 'aktif',
                'telepon' => $this->kepala_telepon ?: null,
                'email' => $userEmail,
                'is_kepala_keluarga' => true,
                'kategori_warga' => 'memiliki_kk',
            ]);

            // 4. Inisialisasi Dompet Bank Sampah Keluarga
            WasteWallet::firstOrCreate(
                ['household_id' => $household->id],
                ['saldo' => 0.00]
            );

            // 5. Simpan Anggota Keluarga jika ada
            foreach ($this->anggota_keluarga as $anggota) {
                if (!empty($anggota['nama']) && !empty($anggota['nik'])) {
                    $anggotaEmail = $anggota['nik'] . '@warga.simlibas.com';
                    $anggotaUser = User::firstOrCreate(
                        ['email' => $anggotaEmail],
                        [
                            'name' => $anggota['nama'],
                            'phone' => !empty($anggota['telepon']) ? $anggota['telepon'] : null,
                            'password' => Hash::make($anggota['nik']),
                            'status' => 'aktif',
                        ]
                    );
                    $anggotaUser->update([
                        'name' => $anggota['nama'],
                        'password' => Hash::make($anggota['nik']),
                    ]);
                    if (!$anggotaUser->hasRole('warga')) {
                        $anggotaUser->assignRole('warga');
                    }

                    Resident::create([
                        'household_id' => $household->id,
                        'user_id' => $anggotaUser->id,
                        'nik' => $anggota['nik'],
                        'nama' => $anggota['nama'],
                        'tempat_lahir' => !empty($anggota['tempat_lahir']) ? $anggota['tempat_lahir'] : ($this->kepala_tempat_lahir ?: 'Jambi'),
                        'tanggal_lahir' => !empty($anggota['tanggal_lahir']) ? $anggota['tanggal_lahir'] : '2000-01-01',
                        'jenis_kelamin' => $anggota['jenis_kelamin'] ?? 'P',
                        'agama' => !empty($anggota['agama']) ? $anggota['agama'] : ($this->kepala_agama ?: 'Islam'),
                        'pekerjaan' => $anggota['pekerjaan'] ?? null,
                        'pendidikan_terakhir' => $anggota['pendidikan_terakhir'] ?? 'SMA / SMK / Sederajat',
                        'status_perkawinan' => $anggota['status_perkawinan'] ?? (($anggota['hubungan_keluarga'] ?? '') === 'istri' ? 'Kawin' : 'Belum Kawin'),
                        'hubungan_keluarga' => $anggota['hubungan_keluarga'] ?? 'anak',
                        'status_kependudukan' => 'aktif',
                        'telepon' => !empty($anggota['telepon']) ? $anggota['telepon'] : null,
                        'email' => $anggotaEmail,
                        'is_kepala_keluarga' => false,
                        'kategori_warga' => 'memiliki_kk',
                    ]);
                    $anggotaCount++;
                }
            }
        });

        $totalJiwa = 1 + $anggotaCount;
        Notification::make()
            ->title('Kartu Keluarga Berhasil Ditambahkan')
            ->body("Data KK berhasil disimpan bersama akun Portal Warga untuk {$savedKepalaNama} ({$totalJiwa} total jiwa).")
            ->success()
            ->send();

        $this->redirect(HouseholdResource::getUrl('index'));
    }
}
