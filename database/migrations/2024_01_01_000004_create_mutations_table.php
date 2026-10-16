<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Pencatatan Mutasi Kependudukan (Lahir, Datang, Pindah, Meninggal)
     */
    public function up(): void
    {
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
            $table->enum('jenis', ['lahir', 'datang', 'pindah', 'meninggal']);
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->string('dokumen_path')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('users');
            $table->timestamps();

            $table->index(['jenis', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutations');
    }
};
