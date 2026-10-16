<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Kartu Keluarga (KK) warga RT 15
     */
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 500)->unique()->comment('Nomor KK terenkripsi');
            $table->string('no_kk_hash', 64)->unique()->comment('Hash SHA-256 nomor KK untuk pencarian cepat');
            $table->text('alamat');
            $table->string('rt_rw', 20)->default('015/000');
            $table->string('kelurahan')->default('Pematang Sulur');
            $table->string('kecamatan')->default('Telanaipura');
            $table->enum('status_hunian', ['tetap', 'kontrak', 'kos'])->default('tetap');
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Index tambahan untuk pencarian & filtering
            $table->index('status_hunian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
