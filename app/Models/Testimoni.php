<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimonis';

    protected $fillable = [
        'name',
        'title',
        'quote',
        'photo',
        'is_visible',
        'order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    // Relasi: Satu testimoni dimiliki oleh satu Rayon/Komisariat
    public function rayon(): BelongsTo
    {
        return $this->belongsTo(Rayon::class);
    }
    /**
     * [INI BAGIAN PENTING]
     * Pastikan array ini terisi dengan nama accessor yang ingin ditampilkan.
     */
    protected $appends = ['photo_url'];


    /**
     * Accessor untuk membuat atribut virtual `photo_url`.
     */
    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
                isset($attributes['photo']) && $attributes['photo']
                    ? Storage::url($attributes['photo'])
                    : null,
        );
    }
}
