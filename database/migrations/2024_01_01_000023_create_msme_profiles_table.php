<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Profil Usaha UMKM Warga RT 15
     */
    public function up(): void
    {
        Schema::create('msme_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained('households')->cascadeOnDelete();
            $table->string('nama_usaha');
            $table->text('deskripsi')->nullable();
            $table->text('produk')->nullable();
            $table->string('kategori')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon');
            $table->string('whatsapp')->nullable();
            $table->enum('status_moderasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_moderasi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status_moderasi');
            $table->index('is_active');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msme_profiles');
    }
};
