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
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'catatan_validasi')) {
                $table->text('catatan_validasi')->nullable()->after('catatan');
            }
            if (! Schema::hasColumn('payments', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'catatan_validasi')) {
                $table->dropColumn('catatan_validasi');
            }
            if (Schema::hasColumn('payments', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
