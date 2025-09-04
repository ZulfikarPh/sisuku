<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rayon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reset cache roles dan permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Buat semua Roles yang dibutuhkan
        $roleSuperAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $roleAdminTarbiyah = Role::create(['name' => 'admin_tarbiyah', 'guard_name' => 'web']);
        $roleAdminUshuluddin = Role::create(['name' => 'admin_ushuluddin', 'guard_name' => 'web']);
        $roleAdminSyariah = Role::create(['name' => 'admin_syariah', 'guard_name' => 'web']);
        $roleAdminDakwah = Role::create(['name' => 'admin_dakwah', 'guard_name' => 'web']);
        $roleAdminRebi = Role::create(['name' => 'admin_rebi', 'guard_name' => 'web']);
        $roleAnggota = Role::create(['name' => 'anggota', 'guard_name' => 'web']);

        // 3. Ambil semua data Rayon dari database
        $rayons = Rayon::pluck('id', 'name');

        // 4. Buat User Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'rayon_id' => $rayons['Komisariat']
        ])->assignRole($roleSuperAdmin);

        // 5. Buat semua Admin Rayon
        User::create([
            'name' => 'Admin Tarbiyah',
            'email' => 'admin.tarbiyah@example.com',
            'password' => Hash::make('password'),
            'rayon_id' => $rayons['Tarbiyah']
        ])->assignRole($roleAdminTarbiyah);

        User::create([
            'name' => 'Admin Ushuluddin',
            'email' => 'admin.ushuluddin@example.com',
            'password' => Hash::make('password'),
            'rayon_id' => $rayons['Ushuluddin']
        ])->assignRole($roleAdminUshuluddin);

        User::create([
            'name' => 'Admin Syariah',
            'email' => 'admin.syariah@example.com',
            'password' => Hash::make('password'),
            'rayon_id' => $rayons['Syariah']
        ])->assignRole($roleAdminSyariah);

        User::create([
            'name' => 'Admin Dakwah',
            'email' => 'admin.dakwah@example.com',
            'password' => Hash::make('password'),
            'rayon_id' => $rayons['Dakwah']
        ])->assignRole($roleAdminDakwah);

        User::create([
            'name' => 'Admin REBI',
            'email' => 'admin.rebi@example.com',
            'password' => Hash::make('password'),
            'rayon_id' => $rayons['REBI']
        ])->assignRole($roleAdminRebi);
    }
}
