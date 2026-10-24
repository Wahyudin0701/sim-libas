<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('rt_settings')) {
            DB::table('rt_settings')->update([
                'alamat' => 'Jl. Yulius Usman, RT 15',
                'email' => 'RT15.pematangsulur@gmail.com',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('rt_settings')) {
            DB::table('rt_settings')->update([
                'alamat' => 'Jl. Pinang Masak, RT 15',
                'email' => 'rt15pematangsulur@gmail.com',
            ]);
        }
    }
};
