<?php

namespace App\Console\Commands;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MigrateCitizenLoginCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warga:sync-login-credentials {--dry-run : Jalankan simulasi tanpa mengubah database} {--force : Jalankan langsung tanpa konfirmasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi kredensial login akun warga (Username: Nama KTP, Password: NIK di-hash)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        $this->info('====================================================================');
        $this->info('  SIM-LiBas RT 15: Migrasi & Sinkronisasi Kredensial Login Warga   ');
        $this->info('====================================================================');

        if ($isDryRun) {
            $this->warn('MODE DRY-RUN AKTIF: Tidak ada perubahan yang akan disimpan ke database.');
        }

        $officialRoles = [
            'super_admin',
            'admin',
            'Super Admin',
            'Admin',
            'ketua_rt',
            'Ketua RT',
            'sekretaris',
            'Sekretaris',
            'bendahara',
            'Bendahara',
            'rw_kelurahan',
            'RW Kelurahan',
        ];

        $residents = Resident::orderBy('id')->get();
        $totalResidents = $residents->count();

        $this->line("Memproses {$totalResidents} data warga...");

        $updatedCount = 0;
        $createdCount = 0;
        $skippedOfficials = [];
        $skippedWithoutNik = [];

        DB::beginTransaction();

        try {
            foreach ($residents as $resident) {
                $nik = trim((string) $resident->nik);

                // 1. Cek validitas NIK (wajib 16 digit angka)
                if (empty($nik) || strlen($nik) !== 16 || !ctype_digit($nik)) {
                    $skippedWithoutNik[] = [
                        'id_warga' => $resident->id_warga ?? "ID:{$resident->id}",
                        'nama' => $resident->nama,
                        'nik' => $nik ?: '(Kosong)',
                        'alasan' => empty($nik) ? 'NIK belum diisi' : 'Panjang NIK tidak 16 digit angka',
                    ];
                    continue;
                }

                $user = null;

                // 2. Cek apakah resident sudah terhubung ke user
                if ($resident->user_id) {
                    $user = User::find($resident->user_id);
                }

                // 3. Jika belum terhubung, cek via email terdaftar
                if (!$user && !empty($resident->email)) {
                    $user = User::where('email', $resident->email)->first();
                }

                // 4. Jika belum terhubung, cek via email sintetis NIK
                if (!$user) {
                    $syntheticEmail = $nik . '@warga.simlibas.com';
                    $user = User::where('email', $syntheticEmail)->first();
                }

                // 5. Jika akun ditemukan:
                if ($user) {
                    // Proteksi akun pejabat / pengurus RT
                    if ($user->hasAnyRole($officialRoles)) {
                        $skippedOfficials[] = [
                            'email' => $user->email,
                            'name' => $user->name,
                            'resident' => $resident->nama,
                            'role' => implode(', ', $user->getRoleNames()->toArray()),
                        ];
                        // Pastikan relasi resident tetap terpasang jika belum
                        if (!$resident->user_id && !$isDryRun) {
                            $resident->update(['user_id' => $user->id]);
                        }
                        continue;
                    }

                    // Sinkronkan nama dan password hash NIK untuk akun warga
                    if (!$isDryRun) {
                        $user->name = $resident->nama;
                        $user->password = Hash::make($nik);
                        if (!empty($resident->telepon) && empty($user->phone)) {
                            $user->phone = $resident->telepon;
                        }
                        $user->save();

                        if (!$resident->user_id) {
                            $resident->update(['user_id' => $user->id]);
                        }
                    }

                    $updatedCount++;
                } else {
                    // 6. Buat akun User baru untuk warga ini
                    $syntheticEmail = !empty($resident->email) ? $resident->email : $nik . '@warga.simlibas.com';

                    if (!$isDryRun) {
                        $newUser = User::create([
                            'name' => $resident->nama,
                            'email' => $syntheticEmail,
                            'phone' => $resident->telepon ?: null,
                            'password' => Hash::make($nik),
                            'status' => $resident->status_kependudukan === 'aktif' ? 'aktif' : 'nonaktif',
                        ]);

                        $newUser->assignRole('warga');
                        $resident->update(['user_id' => $newUser->id]);
                    }

                    $createdCount++;
                }
            }

            if ($isDryRun) {
                DB::rollBack();
                $this->info("\n[DRY RUN BERHASIL DIJALANKAN - TIDAK ADA PERUBAHAN TERSIMPAN]");
            } else {
                DB::commit();
                $this->info("\n[MIGRASI BERHASIL DILAKUKAN DAN DISIMPAN SECARA PERMANEN]");
            }

            // Tampilkan Ringkasan
            $this->newLine();
            $this->info('Ringkasan Hasil Sinkronisasi:');
            $this->table(
                ['Kategori', 'Jumlah'],
                [
                    ['Total Data Warga Diproses', $totalResidents],
                    ['Akun Warga Diperbarui (Nama & Password NIK Hash)', $updatedCount],
                    ['Akun Warga Baru Dibuat (Role Warga & NIK Hash)', $createdCount],
                    ['Akun Pengurus Diproteksi (Kredensial Aman Tidak Berubah)', count($skippedOfficials)],
                    ['Warga Dilewati Karena NIK Kosong/Belum Valid', count($skippedWithoutNik)],
                ]
            );

            if (!empty($skippedOfficials)) {
                $this->newLine();
                $this->comment('Daftar Akun Pengurus yang Diproteksi:');
                $this->table(['Email', 'Nama Akun', 'Role'], array_map(fn ($o) => [
                    $o['email'], $o['name'], $o['role']
                ], $skippedOfficials));
            }

            if (!empty($skippedWithoutNik)) {
                $this->newLine();
                $this->warn('Daftar Warga yang Membutuhkan Pengisian NIK oleh Pengurus (Belum Memiliki Akses Login):');
                $this->table(['ID Warga', 'Nama Lengkap', 'NIK Saat Ini', 'Alasan'], $skippedWithoutNik);
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Terjadi kesalahan saat migrasi: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
