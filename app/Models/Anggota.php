<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Anggota extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama', 'nia', 'rayon_id', 'user_id', 'kategori_anggota_id', 'ttl',
        'jenis_kelamin', 'no_hp', 'email', 'alamat', 'foto',
        'prodi_id', 'tahun_mapaba', 'minat_dan_bakat',
    ];
/**
     * The "booted" method of the model.
     * Di sinilah kita mendaftarkan event listener kita.
     */
    protected static function booted(): void
    {
        // Event ini berjalan SETELAH seorang anggota baru BERHASIL dibuat.
        static::created(function (Anggota $anggota) {
            // Cek apakah anggota ini belum memiliki user_id (untuk menghindari duplikasi)
            if (is_null($anggota->user_id)) {
                // 1. Buat User baru dari data Anggota
                $user = User::create([
                    'name' => $anggota->nama,
                    'email' => $anggota->email,
                    'password' => bcrypt('password123'), // Password default = NIA
                    'rayon_id' => $anggota->rayon_id,
                ]);

                // 2. Berikan role default untuk anggota
                $user->assignRole('anggota');

                // 3. Hubungkan User baru ke Anggota ini
                // Menggunakan updateQuietly agar tidak memicu event loop tak terbatas
                $anggota->updateQuietly(['user_id' => $user->id]);
            }
        });
    }
    // Relasi: Satu Anggota dimiliki oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu Anggota dimiliki oleh satu Rayon
    public function rayon(): BelongsTo
    {
        return $this->belongsTo(Rayon::class);
    }

    // Relasi: Satu Anggota dimiliki oleh satu Kategori
    public function kategoriAnggota(): BelongsTo
    {
        return $this->belongsTo(KategoriAnggota::class);
    }
     public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }
}
