<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import ini

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    public function canAccessPanel(Panel $panel): bool {
        return $this->hasAnyRole(['super_admin', 'admin_tarbiyah', 'admin_ushuluddin', 'admin_syariah', 'admin_dakwah', 'admin_rebi']);
    }

    protected $fillable = ['name', 'email', 'password', 'rayon_id'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function rayon(): BelongsTo {
        return $this->belongsTo(Rayon::class);
    }

    public function anggota(): HasOne {
        return $this->hasOne(Anggota::class, 'user_id');
    }
     public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
