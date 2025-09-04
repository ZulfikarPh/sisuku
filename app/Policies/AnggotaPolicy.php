<?php

namespace App\Policies;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnggotaPolicy
{
    use HandlesAuthorization;

    /**
     * Berikan "kartu VIP" untuk Super Admin.
     * Jika user adalah super_admin, ia boleh melakukan apa saja.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return null;
    }

    /**
     * Aturan untuk siapa yang boleh melihat menu "Anggotas".
     */
    public function viewAny(User $user): bool
    {
        // Izinkan jika user memiliki salah satu dari role admin ini.
        return $user->hasAnyRole(['super_admin', 'admin_tarbiyah', 'admin_ushuluddin', 'admin_syariah', 'admin_dakwah', 'admin_rebi']);
    }

    /**
     * Aturan untuk melihat, mengedit, dan menghapus DATA TERTENTU.
     * Aturan ini hanya berlaku untuk Admin Rayon.
     */
    public function view(User $user, Anggota $anggota): bool
    {
        // Izinkan jika ID rayon user SAMA DENGAN ID rayon anggota.
        return $user->rayon_id === $anggota->rayon_id;
    }

    public function create(User $user): bool
    {
        // Semua admin boleh melihat tombol "New Anggota".
        return $user->hasAnyRole(['super_admin', 'admin_tarbiyah', 'admin_ushuluddin', 'admin_syariah', 'admin_dakwah', 'admin_rebi']);
    }

    public function update(User $user, Anggota $anggota): bool
    {
        return $user->rayon_id === $anggota->rayon_id;
    }

    public function delete(User $user, Anggota $anggota): bool
    {
        return $user->rayon_id === $anggota->rayon_id;
    }
}
