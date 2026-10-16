<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Data Penduduk/Warga RT 15
     */
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained('households')->cascadeOnDelete();
            $table->text('nik_encrypted')->comment('NIK terenkripsi');
            $table->string('nik_hash', 64)->unique()->comment('Hash SHA-256 NIK untuk pencarian cepat');
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama', 50);
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('status_perkawinan', 50)->nullable();
            $table->enum('hubungan_keluarga', [
                'kepala_keluarga',
                'istri',
                'anak',
                'menantu',
                'cucu',
                'orang_tua',
                'mertua',
                'famili_lain',
                'lainnya',
            ]);
            $table->enum('status_kependudukan', [
                'aktif',
                'pindah',
                'meninggal',
                'lahir_baru',
            ])->default('aktif');
            $table->string('telepon', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('foto_path')->nullable();
            $table->boolean('is_kepala_keluarga')->default(false);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Index pencarian dan filter data warga
            $table->index('nama');
            $table->index('status_kependudukan');
            $table->index('hubungan_keluarga');
            $table->index('is_kepala_keluarga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
