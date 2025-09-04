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
    Schema::create('rayon_profiles', function (Blueprint $table) {
        $table->id();
        // Setiap profil rayon terhubung ke satu rayon
        $table->foreignId('rayon_id')->unique()->constrained('rayons')->cascadeOnDelete();
        $table->string('jargon')->nullable();
        $table->text('description')->nullable();
        $table->longText('history')->nullable();
        $table->string('logo')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rayon_profiles');
    }
};
