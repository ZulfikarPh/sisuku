<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- Pastikan ini ada
use Illuminate\Support\Facades\Storage;          // <-- Pastikan ini ada

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'article_category_id',
        'rayon_id',
        'penulis',
        'title',
        'slug',
        'content',
        'thumbnail',
        'status',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * [BARU] Memberitahu Laravel untuk selalu menyertakan atribut virtual ini
     * saat model diubah menjadi array atau JSON.
     */
    protected $appends = ['thumbnail_url'];


    /**
     * Event untuk membuat slug otomatis dari judul.
     */
    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    /**
     * [BARU] Accessor untuk membuat atribut virtual `thumbnail_url`.
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
                isset($attributes['thumbnail']) && $attributes['thumbnail']
                    ? Storage::url($attributes['thumbnail'])
                    : null,
        );
    }

    /**
     * Relasi ke Rayon.
     */
    public function rayon(): BelongsTo
    {
        return $this->belongsTo(Rayon::class);
    }

    /**
     * Relasi ke Kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    /**
     * Relasi ke Tag.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
