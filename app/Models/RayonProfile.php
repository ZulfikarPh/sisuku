<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RayonProfile extends Model
{
    use HasFactory;

    /**
     * Mass-assignment protection.
     * Masukkan semua nama kolom baru ke sini.
     */
    protected $fillable = [
        'rayon_id',
        'jargon',
        'logo_path',
        'banner_path',
        'description',
        'vision',
        'mission',
        'history',
        'address',
        'email',
        'phone_number',
        'social_media',
    ];

    /**
     * Memberitahu Laravel untuk memperlakukan kolom 'social_media' sebagai array/object.
     * Ini sangat membantu saat menyimpan dan mengambil data.
     */
    protected $casts = [
        'social_media' => 'array',
    ];

    /**
     * Accessor untuk mendapatkan URL logo secara penuh.
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
                isset($attributes['logo_path']) && $attributes['logo_path']
                    ? Storage::url($attributes['logo_path'])
                    : null,
        );
    }

    /**
     * Accessor untuk mendapatkan URL banner secara penuh.
     */
    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
                isset($attributes['banner_path']) && $attributes['banner_path']
                    ? Storage::url($attributes['banner_path'])
                    : null,
        );
    }
    protected $appends = ['logo_url', 'banner_url'];
    public function rayon(): BelongsTo
    {
        return $this->belongsTo(Rayon::class);
    }
}
