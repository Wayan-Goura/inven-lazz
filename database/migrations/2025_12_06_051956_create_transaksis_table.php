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
                $table->date('tanggal_transaksi');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->enum('tipe_transaksi', ['masuk', 'keluar']);
                $table->string ('lokasi', 255);
                $table->json('pending_perubahan')->nullable();
                $table->boolean('is_disetujui')->default(false);
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
