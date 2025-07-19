<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting; // Import model Setting

class DefaultSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'app_name_display',
                'value' => 'SIMAPEL Sumba Barat Daya',
                'description' => 'Nama aplikasi yang ditampilkan di antarmuka.',
                'type' => 'text'
            ],
            [
                'key' => 'app_slogan',
                'value' => 'Monitoring Pembangunan Inovatif',
                'description' => 'Slogan aplikasi.',
                'type' => 'text'
            ],
            [
                'key' => 'contact_email_admin',
                'value' => 'admin@simapel.com',
                'description' => 'Alamat email untuk notifikasi dan kontak admin.',
                'type' => 'email'
            ],
            [
                'key' => 'default_map_latitude',
                'value' => '-9.6667',
                'description' => 'Latitude default untuk peta (pusat Sumba Barat Daya).',
                'type' => 'text' // Bisa juga 'number' tapi 'text' lebih fleksibel untuk desimal
            ],
            [
                'key' => 'default_map_longitude',
                'value' => '119.2667',
                'description' => 'Longitude default untuk peta (pusat Sumba Barat Daya).',
                'type' => 'text'
            ],
            [
                'key' => 'app_logo_url',
                'value' => 'https://via.placeholder.com/100x100?text=SIMAPEL',
                'description' => 'URL logo aplikasi (misal: untuk header).',
                'type' => 'text' // Atau 'file' jika kita implement upload logo nanti
            ],
            [
                'key' => 'show_public_dashboard',
                'value' => '0', // 0 = false, 1 = true
                'description' => 'Tampilkan dashboard publik untuk masyarakat umum.',
                'type' => 'checkbox'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description'],
                    'type' => $setting['type'],
                ]
            );
        }
    }
}
