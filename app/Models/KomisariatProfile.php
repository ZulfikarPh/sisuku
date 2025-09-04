<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class KomisariatProfile extends Model
{
    use HasFactory;

    protected $table = 'komisariat_profiles';

    // Menonaktifkan perlindungan mass assignment, sesuai preferensi Anda
    protected $guarded = [];

    /**
     * Memberitahu Laravel untuk selalu menyertakan atribut virtual ini.
     * Kita hanya butuh logo_url dan banner_url untuk ditampilkan.
     */
    protected $appends = ['logo_url', 'banner_url'];

    /**
     * [DIPERBAIKI] Accessor ini sekarang membaca dari kolom 'logo'
     * sesuai dengan struktur database Anda.
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
                isset($attributes['logo']) && $attributes['logo']
                    ? Storage::url($attributes['logo'])
                    : null
        );
    }

    /**
     * [DIPERBAIKI] Accessor ini sekarang membaca dari kolom 'banner_utama'
     * sesuai dengan struktur database Anda.
     */
    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
                isset($attributes['banner_utama']) && $attributes['banner_utama']
                    ? Storage::url($attributes['banner_utama'])
                    : null
        );
    }
}
