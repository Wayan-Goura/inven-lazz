<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string ('merek');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('k_barang')->unique();
            $table->string('lokasi_gudang')->nullable();
            $table->integer('jml_stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_barangs');
    }
};
