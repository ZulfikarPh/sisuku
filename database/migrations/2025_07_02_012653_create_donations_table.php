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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Jika donasi dari anggota terdaftar
            $table->string('donor_name')->nullable(); // Nama donatur jika tidak login/anonim
            $table->string('donor_email')->nullable(); // Email donatur jika tidak login/anonim
            $table->decimal('amount', 12, 2); // Jumlah donasi, misalnya 100000000.00
            $table->text('notes')->nullable(); // Catatan dari donatur
            $table->string('payment_method')->nullable(); // Misal: 'transfer bank', 'cash', 'online gateway'
            $table->string('status')->default('pending'); // Misal: 'pending', 'completed', 'failed', 'refunded'
            $table->timestamp('donated_at')->useCurrent(); // Tanggal dan waktu donasi dilakukan
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
