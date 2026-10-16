<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Master Jenis Surat Layanan Warga
     */
    public function up(): void
    {
        Schema::create('letter_types', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode', 20)->unique()->comment('Kode surat unik, misal: SKD, SKCK, SKU');
            $table->text('deskripsi')->nullable();
            $table->string('template_path')->nullable();
            $table->string('format_nomor')->nullable()->comment('Format penomoran spesifik jenis surat');
            $table->json('persyaratan')->nullable()->comment('Daftar syarat dokumen dalam format JSON');
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index('is_active');
            $table->index('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_types');
    }
};
