    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        // File: migration_transaksis_table.php
    
        public function up(): void
        {
            Schema::create('transaksis', function (Blueprint $table) {
                $table->id();
                $table->string('kode_transaksi', 50)->unique();
                $table->dateTime('tanggal_transaksi');

                // [PERBAIKAN WAJIB 1]: Tambahkan user_id karena diperlukan oleh controller
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

                // [PERBAIKAN WAJIB 2]: Hapus category_id (Category milik Barang, bukan Transaksi Induk)
                // $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // HAPUS
    
                $table->enum('tipe_transaksi', ['masuk', 'keluar']);
                $table->integer('total_barang');
                $table->string ('lokasi', 255);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('transaksis');
        }
    };
