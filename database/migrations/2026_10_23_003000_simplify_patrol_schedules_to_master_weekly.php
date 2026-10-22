<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Penyederhanaan Jadwal Ronda:
     * - Master Jadwal Mingguan tetap 7 baris (Senin s.d. Minggu) di tabel patrol_schedules.
     * - Riwayat Pelaksanaan Aktual bertanggal dipisahkan ke tabel patrol_executions & patrol_execution_members.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Buat Tabel Riwayat Pelaksanaan Ronda Aktual (PatrolExecution)
        if (!Schema::hasTable('patrol_executions')) {
            Schema::create('patrol_executions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('patrol_schedule_id')->nullable();
                $table->date('tanggal');
                $table->string('status')->default('berlangsung'); // berlangsung, selesai, dibatalkan
                $table->text('laporan_kejadian')->nullable();
                $table->string('alasan_batal')->nullable();
                $table->timestamps();

                $table->index(['tanggal', 'status']);
            });
        }

        // 2. Buat Tabel Detail Presensi Kehadiran Aktual (PatrolExecutionMember)
        if (!Schema::hasTable('patrol_execution_members')) {
            Schema::create('patrol_execution_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patrol_execution_id')->constrained('patrol_executions')->cascadeOnDelete();
                $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
                $table->string('status_kehadiran')->default('hadir'); // hadir, izin, sakit, alpa
                $table->timestamp('waktu_absen')->nullable();
                $table->boolean('is_pengganti')->default(false);
                $table->foreignId('menggantikan_resident_id')->nullable()->constrained('residents')->nullOnDelete();
                $table->string('keterangan')->nullable();
                $table->timestamps();
            });
        }

        // 3. Migrasi Riwayat Pelaksanaan yang sudah tersimpan sebelumnya
        if (Schema::hasTable('patrol_schedules') && Schema::hasColumn('patrol_schedules', 'tanggal')) {
            $completedSchedules = DB::table('patrol_schedules')
                ->whereIn('status', ['selesai', 'berlangsung'])
                ->orWhereNotNull('laporan_kejadian')
                ->get();

            foreach ($completedSchedules as $sched) {
                $execId = DB::table('patrol_executions')->insertGetId([
                    'patrol_schedule_id' => null,
                    'tanggal' => $sched->tanggal,
                    'status' => $sched->status ?? 'selesai',
                    'laporan_kejadian' => $sched->laporan_kejadian,
                    'alasan_batal' => $sched->alasan_batal,
                    'created_at' => $sched->created_at ?? now(),
                    'updated_at' => $sched->updated_at ?? now(),
                ]);

                if (Schema::hasTable('patrol_members')) {
                    $members = DB::table('patrol_members')->where('patrol_schedule_id', $sched->id)->get();
                    foreach ($members as $m) {
                        DB::table('patrol_execution_members')->insert([
                            'patrol_execution_id' => $execId,
                            'resident_id' => $m->resident_id,
                            'status_kehadiran' => $m->status_kehadiran ?? 'hadir',
                            'waktu_absen' => $m->waktu_absen,
                            'is_pengganti' => $m->is_pengganti ?? false,
                            'menggantikan_resident_id' => $m->menggantikan_resident_id ?? null,
                            'keterangan' => $m->keterangan ?? null,
                            'created_at' => $m->created_at ?? now(),
                            'updated_at' => $m->updated_at ?? now(),
                        ]);
                    }
                }
            }
        }

        // 4. Bersihkan data jadwal lama dari patrol_members dan patrol_schedules
        if (Schema::hasTable('patrol_members')) {
            DB::table('patrol_members')->delete();
        }
        if (Schema::hasTable('patrol_schedules')) {
            DB::table('patrol_schedules')->delete();
        }

        // 5. Sesuaikan struktur tabel patrol_schedules & patrol_members menjadi Master Mingguan 7 Hari
        if (DB::getDriverName() === 'sqlite') {
            Schema::dropIfExists('patrol_members');
            Schema::dropIfExists('patrol_schedules');

            Schema::create('patrol_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedTinyInteger('hari_ke')->unique();
                $table->string('regu');
                $table->time('waktu_mulai');
                $table->time('waktu_selesai');
                $table->string('pos_lokasi')->default('Pos Utama RT 15');
                $table->boolean('is_aktif')->default(true);
                $table->text('catatan')->nullable();
                $table->timestamps();
            });

            Schema::create('patrol_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patrol_schedule_id')->constrained('patrol_schedules')->cascadeOnDelete();
                $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
                $table->string('peran')->default('anggota');
                $table->timestamps();
            });
        } else {
            Schema::table('patrol_schedules', function (Blueprint $table) {
                if (Schema::hasColumn('patrol_schedules', 'patrol_regu_id')) {
                    try {
                        $table->dropForeign(['patrol_regu_id']);
                    } catch (\Throwable $e) {
                        // ignore if foreign key doesn't exist
                    }
                }

                $dropCols = [];
                foreach (['patrol_regu_id', 'tanggal', 'status', 'laporan_kejadian', 'alasan_batal', 'is_generated'] as $col) {
                    if (Schema::hasColumn('patrol_schedules', $col)) {
                        $dropCols[] = $col;
                    }
                }
                if (!empty($dropCols)) {
                    $table->dropColumn($dropCols);
                }

                if (!Schema::hasColumn('patrol_schedules', 'hari_ke')) {
                    $table->unsignedTinyInteger('hari_ke')->after('id')->unique();
                }
                if (!Schema::hasColumn('patrol_schedules', 'pos_lokasi')) {
                    $table->string('pos_lokasi')->after('waktu_selesai')->default('Pos Utama RT 15');
                }
                if (!Schema::hasColumn('patrol_schedules', 'is_aktif')) {
                    $table->boolean('is_aktif')->after('pos_lokasi')->default(true);
                }
            });

            Schema::table('patrol_members', function (Blueprint $table) {
                if (Schema::hasColumn('patrol_members', 'menggantikan_resident_id')) {
                    try {
                        $table->dropForeign(['menggantikan_resident_id']);
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }

                $dropCols = [];
                foreach (['status_kehadiran', 'waktu_absen', 'is_pengganti', 'menggantikan_resident_id', 'keterangan'] as $col) {
                    if (Schema::hasColumn('patrol_members', $col)) {
                        $dropCols[] = $col;
                    }
                }
                if (!empty($dropCols)) {
                    $table->dropColumn($dropCols);
                }

                if (!Schema::hasColumn('patrol_members', 'peran')) {
                    $table->string('peran')->after('resident_id')->default('anggota');
                }
            });
        }

        // 7. Isi Master Jadwal Mingguan (Tepat 7 Record: Senin s.d. Minggu)
        $masterData = [
            [
                'hari_ke' => 1,
                'regu' => 'Regu A - Garuda (Senin Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'catatan' => 'Patroli keliling setiap 1 jam sekali dan pencatatan portal masuk tamu.',
                'is_aktif' => true,
            ],
            [
                'hari_ke' => 2,
                'regu' => 'Regu B - Rajawali (Selasa Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'catatan' => 'Kondisi lingkungan aman kondusif, cuaca cerah.',
                'is_aktif' => true,
            ],
            [
                'hari_ke' => 3,
                'regu' => 'Regu C - Elang (Rabu Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'catatan' => 'Kunci portal dipegang ketua regu, senter baterai terisi penuh.',
                'is_aktif' => true,
            ],
            [
                'hari_ke' => 4,
                'regu' => 'Regu D - Harimau (Kamis Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'catatan' => 'Pemeriksaan rutin pintu gerbang blok perumahan dan pos ronda.',
                'is_aktif' => true,
            ],
            [
                'hari_ke' => 5,
                'regu' => 'Regu E - Banteng (Jumat Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'catatan' => 'Antisipasi peningkatan arus keluar masuk kendaraan tamu akhir pekan.',
                'is_aktif' => true,
            ],
            [
                'hari_ke' => 6,
                'regu' => 'Regu F - Singa (Sabtu Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'catatan' => 'Jaga malam akhir pekan dan monitoring berkala posko keamanan.',
                'is_aktif' => true,
            ],
            [
                'hari_ke' => 7,
                'regu' => 'Regu G - Merpati (Minggu Malam)',
                'waktu_mulai' => '22:00:00',
                'waktu_selesai' => '04:00:00',
                'pos_lokasi' => 'Pos Utama RT 15',
                'catatan' => 'Ronda awal pekan dan pemantauan lampu penerangan jalan.',
                'is_aktif' => true,
            ],
        ];

        $hasRegus = Schema::hasTable('patrol_regus') && Schema::hasTable('patrol_regu_members');
        $residentIds = DB::table('residents')->pluck('id')->toArray();

        foreach ($masterData as $idx => $d) {
            $now = now();
            $schedId = DB::table('patrol_schedules')->insertGetId([
                'hari_ke' => $d['hari_ke'],
                'regu' => $d['regu'],
                'waktu_mulai' => $d['waktu_mulai'],
                'waktu_selesai' => $d['waktu_selesai'],
                'pos_lokasi' => $d['pos_lokasi'],
                'catatan' => $d['catatan'],
                'is_aktif' => $d['is_aktif'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Hubungkan patrol_executions yang relevan dengan hari ini
            $dayOfWeekNumber = $d['hari_ke'] == 7 ? 1 : $d['hari_ke'] + 1; // MySQL DAYOFWEEK (1=Minggu, 2=Senin)
            if (DB::getDriverName() === 'sqlite') {
                $sqliteDay = (string) ($d['hari_ke'] == 7 ? 0 : $d['hari_ke']); // SQLite %w (0=Minggu, 1=Senin)
                DB::table('patrol_executions')
                    ->whereNull('patrol_schedule_id')
                    ->whereRaw("strftime('%w', tanggal) = ?", [$sqliteDay])
                    ->update(['patrol_schedule_id' => $schedId]);
            } else {
                DB::table('patrol_executions')
                    ->whereNull('patrol_schedule_id')
                    ->whereRaw("DAYOFWEEK(tanggal) = ?", [$dayOfWeekNumber])
                    ->update(['patrol_schedule_id' => $schedId]);
            }

            // Salin anggota dari patrol_regu_members bila ada, atau petakan dari warga yang tersedia
            $copied = false;
            if ($hasRegus) {
                $regu = DB::table('patrol_regus')->where('hari_ke', $d['hari_ke'])->first();
                if ($regu) {
                    $reguMembers = DB::table('patrol_regu_members')->where('patrol_regu_id', $regu->id)->get();
                    if ($reguMembers->isNotEmpty()) {
                        foreach ($reguMembers as $rm) {
                            DB::table('patrol_members')->insert([
                                'patrol_schedule_id' => $schedId,
                                'resident_id' => $rm->resident_id,
                                'peran' => $rm->peran ?? 'anggota',
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);
                        }
                        $copied = true;
                    }
                }
            }

            if (!$copied && !empty($residentIds)) {
                $count = count($residentIds);
                $slice = array_slice($residentIds, ($idx * 2) % max(1, $count - 2), 3);
                foreach ($slice as $mIdx => $rId) {
                    DB::table('patrol_members')->insert([
                        'patrol_schedule_id' => $schedId,
                        'resident_id' => $rId,
                        'peran' => $mIdx === 0 ? 'ketua_regu' : 'anggota',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        // 8. Tambahkan foreign key pada patrol_executions
        Schema::table('patrol_executions', function (Blueprint $table) {
            $table->foreign('patrol_schedule_id')->references('id')->on('patrol_schedules')->nullOnDelete();
        });

        // 9. Hapus tabel intermediate patrol_regus yang sudah tidak diperlukan
        Schema::dropIfExists('patrol_regu_members');
        Schema::dropIfExists('patrol_regus');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrol_execution_members');
        Schema::dropIfExists('patrol_executions');
    }
};
