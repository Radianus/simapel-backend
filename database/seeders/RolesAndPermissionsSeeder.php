<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Import model User

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Definisikan Izin (Permissions)
        // Izin untuk manajemen proyek
        Permission::findOrCreate('view projects');
        Permission::findOrCreate('create project');
        Permission::findOrCreate('edit project');
        Permission::findOrCreate('delete project');

        // Izin untuk manajemen user (admin super)
        Permission::findOrCreate('manage users');
        Permission::findOrCreate('manage roles');

        // 2. Definisikan Peran (Roles) dan Berikan Izin
        // Peran Super Admin (dapat melakukan segalanya)
        $superAdminRole = Role::findOrCreate('super_admin');
        $superAdminRole->givePermissionTo(Permission::all()); // Memberikan semua izin yang ada

        // Peran Admin Dinas (dapat mengelola proyek)
        $adminDinasRole = Role::findOrCreate('admin_dinas');
        $adminDinasRole->givePermissionTo(['view projects']);

        // Peran Petugas Lapangan (hanya bisa melihat dan update progres proyek, untuk ke depan)
        $petugasLapanganRole = Role::findOrCreate('petugas_lapangan');
        $petugasLapanganRole->givePermissionTo(['view projects', 'edit project']); // Bisa edit progres

        // Peran View Only (hanya bisa melihat)
        $viewOnlyRole = Role::findOrCreate('view_only');
        $viewOnlyRole->givePermissionTo(['view projects']);


        // 3. Berikan Peran kepada User Dummy yang Sudah Ada
        // Temukan user yang sudah ada dari DatabaseSeeder
        $adminSimapel = User::where('email', 'admin@simapel.com')->first();
        if ($adminSimapel) {
            $adminSimapel->assignRole('super_admin');
            $this->command->info('User admin@simapel.com diberi peran super_admin');
        }

        $stafDinas = User::where('email', 'staf@simapel.com')->first();
        if ($stafDinas) {
            $stafDinas->assignRole('view_only');
            $this->command->info('User staf@simapel.com diberi peran admin_dinas');
        }

        // Contoh: Jika ingin membuat user baru khusus dengan peran
        // User::factory()->create([
        //     'name' => 'Viewonly User',
        //     'email' => 'viewer@simapel.com',
        //     'password' => bcrypt('password'),
        // ])->assignRole('view_only');
    }
}
