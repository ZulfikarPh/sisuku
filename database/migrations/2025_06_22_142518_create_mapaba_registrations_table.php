<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapaba_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nim')->unique();
            $table->string('email')->unique();
            $table->string('no_hp');
            $table->foreignId('rayon_id')->constrained('rayons');
            $table->foreignId('prodi_id')->constrained('prodis');

            $table->string('ttl')->nullable();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->nullable();
            $table->text('alamat')->nullable();
            $table->year('tahun_mapaba')->nullable(); // Tetap di DB, tapi akan diisi otomatis
            // $table->text('minat_dan_bakat')->nullable(); // HAPUS BARIS INI JIKA MIGRASI BELUM DIJALANKAN

            $table->string('bukti_follow_path')->nullable();
            $table->string('foto_ktm_path')->nullable();
            $table->string('bukti_pembayaran_path')->nullable();

            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapaba_registrations');
    }
};
