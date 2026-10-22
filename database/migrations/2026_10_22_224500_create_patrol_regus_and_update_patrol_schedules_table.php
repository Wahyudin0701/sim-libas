<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat Tabel Master Regu Ronda (Pola Mingguan Rutin)
        Schema::create('patrol_regus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_regu'); // Contoh: Regu A - Garuda (Senin Malam)
            $table->unsignedTinyInteger('hari_ke'); // 1 = Senin, 2 = Selasa, ..., 7 = Minggu
            $table->time('waktu_mulai')->default('22:00');
            $table->time('waktu_selesai')->default('04:00');
            $table->string('pos_lokasi')->default('Pos Utama RT 15');
            $table->boolean('is_aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['hari_ke', 'is_aktif']);
        });

        // 2. Buat Tabel Master Anggota Tetap Regu Ronda
        Schema::create('patrol_regu_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patrol_regu_id')->constrained('patrol_regus')->cascadeOnDelete();
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
            $table->string('peran')->default('anggota'); // ketua_regu, anggota
            $table->timestamps();

            $table->unique(['patrol_regu_id', 'resident_id']);
        });

        // 3. Tambahkan kolom pendukung pada tabel patrol_schedules
        Schema::table('patrol_schedules', function (Blueprint $table) {
            $table->foreignId('patrol_regu_id')->nullable()->after('id')->constrained('patrol_regus')->nullOnDelete();
            $table->string('status')->default('terjadwal')->after('waktu_selesai'); // terjadwal, berlangsung, selesai, dibatalkan
            $table->text('laporan_kejadian')->nullable()->after('catatan');
            $table->string('alasan_batal')->nullable()->after('laporan_kejadian');
            $table->boolean('is_generated')->default(false)->after('alasan_batal');

            $table->index('status');
        });

        // 4. Tambahkan kolom presensi & pengganti pada tabel patrol_members
        Schema::table('patrol_members', function (Blueprint $table) {
            $table->string('status_kehadiran')->default('belum_absen')->after('resident_id'); // belum_absen, hadir, izin, sakit, alpa
            $table->timestamp('waktu_absen')->nullable()->after('status_kehadiran');
            $table->foreignId('menggantikan_resident_id')->nullable()->after('is_pengganti')->constrained('residents')->nullOnDelete();
            $table->string('keterangan')->nullable()->after('menggantikan_resident_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patrol_members', function (Blueprint $table) {
            $table->dropForeign(['menggantikan_resident_id']);
            $table->dropColumn([
                'status_kehadiran',
                'waktu_absen',
                'menggantikan_resident_id',
                'keterangan',
            ]);
        });

        Schema::table('patrol_schedules', function (Blueprint $table) {
            $table->dropForeign(['patrol_regu_id']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'patrol_regu_id',
                'status',
                'laporan_kejadian',
                'alasan_batal',
                'is_generated',
            ]);
        });

        Schema::dropIfExists('patrol_regu_members');
        Schema::dropIfExists('patrol_regus');
    }
};
