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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // Kolom Relasi
            $table->foreignId('article_category_id')->constrained('article_categories')->cascadeOnDelete();
            $table->foreignId('rayon_id')->nullable()->constrained('rayons')->nullOnDelete();

            // Kolom Konten
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('penulis'); // Nama penulis sebagai teks biasa
            $table->longText('content');
            $table->string('thumbnail')->nullable();

            // Kolom Status & Jadwal
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
