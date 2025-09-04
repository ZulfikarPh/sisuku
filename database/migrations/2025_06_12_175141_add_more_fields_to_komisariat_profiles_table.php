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
    Schema::table('komisariat_profiles', function (Blueprint $table) {
        // Menambahkan kolom baru setelah kolom 'periode'
        $table->string('jargon')->nullable()->after('periode');
        $table->string('banner_utama')->nullable()->after('jargon');
        $table->longText('sejarah_singkat')->nullable()->after('misi');
        $table->string('alamat_sekretariat')->nullable()->after('sejarah_singkat');
        $table->string('email_resmi')->nullable()->after('alamat_sekretariat');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komisariat_profiles', function (Blueprint $table) {
            //
        });
    }
};
