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
        Schema::table('anggotas', function (Blueprint $table) {
            // Membuat kolom-kolom ini menjadi nullable di tabel 'anggotas'
            // Pastikan tipe data sama dengan yang sudah ada di tabel Anda
            $table->string('ttl', 255)->nullable()->change(); // Sesuaikan panjang jika perlu
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->nullable()->change();
            $table->year('tahun_mapaba')->nullable()->change();
            $table->text('minat_dan_bakat')->nullable()->change();
            $table->text('alamat')->nullable()->change();
            // 'alamat' sudah text NULLABLE, tidak perlu diubah lagi
            // 'kampus_asal' sudah varchar, tapi kita belum lihat nullable statusnya, buat nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            // Mengembalikan ke NOT NULL (jika diperlukan)
            // CATATAN: Ini bisa gagal jika ada nilai NULL di DB Anda saat rollback
            // $table->string('ttl', 255)->nullable(false)->change();
            // $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->nullable(false)->change();
            // $table->year('tahun_mapaba')->nullable(false)->change();
            // $table->text('minat_dan_bakat')->nullable(false)->change();
            // $table->string('kampus_asal', 255)->nullable(false)->change();
        });
    }
};
