<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Saldo / Dompet Bank Sampah Warga (KK)
     */
    public function up(): void
    {
        Schema::create('waste_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->unique()->constrained('households')->cascadeOnDelete();
            $table->decimal('saldo', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_wallets');
    }
};
