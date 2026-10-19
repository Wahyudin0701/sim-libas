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
        Schema::table('residents', function (Blueprint $table) {
            $table->foreignId('household_id')->nullable()->change();
            $table->string('kategori_warga', 30)->default('memiliki_kk')->after('household_id');
            $table->string('status_tempat_tinggal', 30)->nullable()->after('kategori_warga');
            $table->text('alamat_domisili')->nullable()->after('status_tempat_tinggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['kategori_warga', 'status_tempat_tinggal', 'alamat_domisili']);
            $table->foreignId('household_id')->nullable(false)->change();
        });
    }
};
