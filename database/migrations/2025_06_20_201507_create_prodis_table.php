<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // di dalam file ...create_prodis_table.php
public function up(): void
{
    Schema::create('prodis', function (Blueprint $table) {
        $table->id(); // Membuat kolom id (UNSIGNED BIGINT, primary key, auto-increment)
        $table->string('nama_prodi');

        // Membuat kolom foreign key ke tabel rayons
        $table->foreignId('rayon_id')
              ->constrained('rayons') // Terhubung ke tabel 'rayons'
              ->onDelete('cascade'); // Jika rayon dihapus, prodi terkait juga akan terhapus

        $table->timestamps(); // Membuat kolom created_at dan updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};
