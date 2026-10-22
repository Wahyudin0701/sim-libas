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
        // Migrasikan surat berstatus 'selesai' menjadi status terminal 'disetujui'
        DB::table('letters')->where('status', 'selesai')->update([
            'status' => 'disetujui',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu me-revert status selesai
    }
};
