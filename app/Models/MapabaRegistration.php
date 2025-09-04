<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapabaRegistration extends Model
{
    use HasFactory;

    protected $table = 'mapaba_registrations';

    // Izinkan semua kolom diisi secara massal untuk kemudahan
    protected $guarded = [];

    /**
     * Mendefinisikan relasi bahwa pendaftaran ini milik satu Rayon.
     */
    public function rayon(): BelongsTo
    {
        return $this->belongsTo(Rayon::class);
    }

    /**
     * Mendefinisikan relasi bahwa pendaftaran ini milik satu Prodi.
     */
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }
}
