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
    Schema::create('agendas', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('location');
        $table->dateTime('start_time');
        $table->dateTime('end_time')->nullable();
        $table->string('poster')->nullable();
        // Setiap agenda bisa dimiliki oleh Rayon/Komisariat tertentu
        $table->foreignId('rayon_id')->nullable()->constrained('rayons')->nullOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
