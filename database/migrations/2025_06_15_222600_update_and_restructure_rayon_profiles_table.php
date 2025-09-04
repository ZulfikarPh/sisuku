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
        Schema::table('rayon_profiles', function (Blueprint $table) {
            // 1. Mengubah nama kolom 'logo' menjadi 'logo_path'
            $table->renameColumn('logo', 'logo_path');

            // 2. Menambahkan kolom baru
            $table->string('banner_path')->nullable()->after('logo_path');
            $table->text('vision')->nullable()->after('history');
            $table->text('mission')->nullable()->after('vision');
            $table->text('address')->nullable()->after('mission');
            $table->string('email')->nullable()->after('address');
            $table->string('phone_number', 25)->nullable()->after('email');
            $table->json('social_media')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rayon_profiles', function (Blueprint $table) {
            // Urutan dibalik untuk membatalkan perubahan di method up()
            $table->dropColumn([
                'banner_path',
                'vision',
                'mission',
                'address',
                'email',
                'phone_number',
                'social_media',
            ]);

            $table->renameColumn('logo_path', 'logo');
        });
    }
};
