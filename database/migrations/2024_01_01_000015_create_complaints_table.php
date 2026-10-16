<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Pengaduan / Aspirasi Warga
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('kategori', [
                'infrastruktur', 'kebersihan', 'keamanan', 'kebisingan', 'lainnya'
            ])->default('lainnya');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('foto_path')->nullable();
            $table->string('lokasi')->nullable();
            $table->boolean('is_anonim')->default(false);
            $table->enum('status', ['diterima', 'diproses', 'selesai', 'ditolak'])->default('diterima');
            $table->text('catatan_penanganan')->nullable();
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
