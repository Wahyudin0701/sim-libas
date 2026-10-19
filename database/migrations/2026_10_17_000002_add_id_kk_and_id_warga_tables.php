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
        Schema::table('households', function (Blueprint $table) {
            $table->string('id_kk', 20)->nullable()->unique()->after('id')->comment('ID unik KK format KK*** (contoh: KK001)');
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->string('id_warga', 20)->nullable()->unique()->after('household_id')->comment('ID unik warga format W*** (contoh: W001)');
            $table->text('nik_encrypted')->nullable()->change();
            $table->string('nik_hash', 64)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('id_warga');
        });

        Schema::table('households', function (Blueprint $table) {
            $table->dropColumn('id_kk');
        });
    }
};
