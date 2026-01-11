<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // File: migration_detail_transaksis_table.php

    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
    $table->id();
    $table->foreignId('transaksi_id')->constrained()->cascadeOnDelete();
    $table->foreignId('data_barang_id')->constrained()->cascadeOnDelete();
    $table->integer('jumlah');
    $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
