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
    Schema::create('komisariat_profiles', function (Blueprint $table) {
        $table->id();
        $table->string('nama_ketua')->nullable();
        $table->string('periode')->nullable();
        $table->text('sambutan')->nullable();
        $table->text('visi')->nullable();
        $table->text('misi')->nullable();
        $table->string('logo')->nullable();
        $table->string('link_instagram')->nullable();
        $table->string('link_tiktok')->nullable();
        $table->string('link_youtube')->nullable();
        $table->string('link_facebook')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komisariat_profiles');
    }
};
