<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rayon extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug'];

    // Relasi: Satu Rayon memiliki banyak User (admin)
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'rayon_id');
    }

    // Relasi: Satu Rayon memiliki banyak Anggota
    public function anggotas(): HasMany
    {
        return $this->hasMany(Anggota::class, 'rayon_id');
    }
    // app/Models/Rayon.php
    public function profile()
    {
        return $this->hasOne(RayonProfile::class);
    }
}
