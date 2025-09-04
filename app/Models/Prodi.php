<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_prodi',
        'rayon_id',
    ];

    /**
     * Mendefinisikan bahwa satu Prodi "dimiliki oleh" satu Rayon.
     */
    public function rayon(): BelongsTo
    {
        return $this->belongsTo(Rayon::class);
    }

    /**
     * Mendefinisikan bahwa satu Prodi "memiliki banyak" Anggota.
     */
    public function anggotas(): HasMany
    {
        return $this->hasMany(Anggota::class);
    }
}
