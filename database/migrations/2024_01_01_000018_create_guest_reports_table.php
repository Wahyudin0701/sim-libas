<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Lapor Tamu Menginap / Penghuni Baru
     */
    public function up(): void
    {
        Schema::create('guest_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained('households')->cascadeOnDelete();
            $table->string('nama_tamu');
            $table->string('nik_tamu')->nullable();
            $table->string('asal');
            $table->text('tujuan')->nullable();
            $table->date('tanggal_datang');
            $table->date('tanggal_pulang')->nullable();
            $table->string('foto_path')->nullable();
            $table->foreignId('dilaporkan_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index('tanggal_datang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_reports');
    }
};
