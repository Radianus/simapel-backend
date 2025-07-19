<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Panggil seeder untuk peran dan izin terlebih dahulu
        $this->call(RolesAndPermissionsSeeder::class);

        // Panggil seeder ProjectSeeder
        $this->call(ProjectSeeder::class);

        // Jika Anda ingin juga membuat user dummy:
        \App\Models\User::factory()->create([
            'name' => 'Admin Simapel',
            'email' => 'admin@simapel.com',
            'password' => bcrypt('password'), // passwordnya 'password'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Staf Dinas',
            'email' => 'staf@simapel.com',
            'password' => bcrypt('password'),
        ]);

        $this->call(DefaultSettingsSeeder::class);
    }
}
