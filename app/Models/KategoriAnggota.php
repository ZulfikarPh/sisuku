<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriAnggota extends Model
{
    use HasFactory;

    // Nonaktifkan penjaga mass assignment agar semua kolom bisa diisi
    protected $guarded = [];

    // Atau jika Anda lebih suka menggunakan fillable (lebih aman)
    // protected $fillable = ['nama_kategori'];

    // Matikan timestamps jika tabel Anda tidak memiliki created_at/updated_at
    public $timestamps = false;

    // Relasi: Satu Kategori memiliki banyak Anggota
    public function anggotas(): HasMany
    {
        return $this->hasMany(Anggota::class, 'kategori_anggota_id');
    }
}
