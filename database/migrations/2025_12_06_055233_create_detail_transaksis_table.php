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
            // [PERBAIKAN 3]: Hapus $table->id() agar bisa menggunakan primary key komposit
            // $table->id(); // HAPUS

            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $table->foreignId('data_barang_id')->constrained('data_barangs')->onDelete('cascade');
            $table->integer('jumlah');
            $table->timestamps();

            // [PERBAIKAN 4]: Set primary key gabungan (Komposit)
            $table->primary(['transaksi_id', 'data_barang_id']);
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
