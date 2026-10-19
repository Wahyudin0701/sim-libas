<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('guest_reports', function (Blueprint $table) {
            $table->string('status', 20)->default('menginap')->after('tanggal_pulang');
        });

        // Update catatan tamu yang tanggal pulangnya sudah lewat
        DB::table('guest_reports')
            ->whereNotNull('tanggal_pulang')
            ->where('tanggal_pulang', '<', now()->toDateString())
            ->update(['status' => 'pulang']);

        // Sinkronisasi tamu Ahmad Fauzi Rahman yang telah dicatat kepulangannya oleh pengurus hari ini
        DB::table('guest_reports')
            ->where('nama_tamu', 'Ahmad Fauzi Rahman')
            ->update(['status' => 'pulang']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guest_reports', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
