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
    Schema::create('testimonis', function (Blueprint $table) {
        $table->id();
        // Setiap testimoni bisa dihubungkan ke Rayon atau Komisariat
        $table->string('name'); // Nama pemberi testimoni
        $table->string('title'); // Jabatan/status, cth: "Ketua PCNU Kudus", "Alumni 2015"
        $table->text('quote'); // Isi kutipan/testimoni
        $table->string('photo')->nullable(); // Foto pemberi testimoni
        $table->boolean('is_visible')->default(false); // Untuk kontrol tampil/sembunyi di frontend
        $table->integer('order')->default(0); // Untuk mengatur urutan tampil
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonis');
    }
};
