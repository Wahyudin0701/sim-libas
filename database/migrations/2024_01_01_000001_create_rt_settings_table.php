<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel konfigurasi dan profil RT 15 Pematang Sulur
     */
    public function up(): void
    {
        Schema::create('rt_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rt')->default('RT 15');
            $table->text('alamat');
            $table->string('kelurahan')->default('Pematang Sulur');
            $table->string('kecamatan')->default('Telanaipura');
            $table->string('kota')->default('Kota Jambi');
            $table->string('provinsi')->default('Jambi');
            $table->string('kode_pos', 10)->nullable()->default('36129');
            $table->string('telepon', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('nama_ketua_rt');
            $table->string('tanda_tangan_path')->nullable();
            $table->string('stempel_path')->nullable();
            $table->string('format_nomor_surat')->nullable()->comment('Format penomoran surat, misal: {nomor}/{kode}/RT15-PS/{bulan}/{tahun}');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rt_settings');
    }
};
