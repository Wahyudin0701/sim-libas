<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Permohonan dan Penerbitan Surat Layanan Warga
     */
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->nullable()->unique();
            $table->foreignId('letter_type_id')->constrained('letter_types')->cascadeOnDelete();
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete()->comment('Warga pemohon');
            $table->text('keperluan');
            $table->json('data_tambahan')->nullable()->comment('Data isian form spesifik surat');
            $table->enum('status', [
                'diajukan',
                'diperiksa',
                'disetujui',
                'ditolak',
                'selesai',
            ])->default('diajukan');
            $table->text('catatan_pengurus')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('qr_token', 100)->nullable()->unique()->comment('Token unik untuk validasi QR Code surat');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('tanggal_disetujui')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index(['resident_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
