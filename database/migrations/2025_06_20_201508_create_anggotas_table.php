<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Di dalam file ..._create_anggotas_table.php

public function up(): void
{
    Schema::create('anggotas', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('nia')->unique();
        $table->foreignId('rayon_id')->constrained('rayons');
        $table->foreignId('user_id')->nullable()->constrained('users');
        $table->foreignId('kategori_anggota_id')->constrained('kategori_anggotas');

        $table->string('ttl');
        $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
        $table->string('no_hp')->nullable();
        $table->string('email')->unique();
        $table->text('alamat');
        $table->string('foto')->nullable();

        // Kolom program_studi yang lama sudah kita hapus

        $table->year('tahun_mapaba');
        $table->text('minat_dan_bakat')->nullable();

        // [DIPERBAIKI] Kolom prodi_id ditambahkan di sini TANPA ->after()
        $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('set null');

        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
