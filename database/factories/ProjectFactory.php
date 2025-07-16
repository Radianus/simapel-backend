<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Koordinat pusat Sumba Barat Daya untuk rentang data dummy yang realistis
        $centerLat = -9.6667;
        $centerLon = 119.2667;

        // Rentang acak untuk Lat/Lon di sekitar Sumba Barat Daya
        // (sekitar +/- 0.5 derajat dari pusat, mencakup sebagian besar area kabupaten)
        $latitude = $this->faker->latitude($min = $centerLat - 0.5, $max = $centerLat + 0.5);
        $longitude = $this->faker->longitude($min = $centerLon - 0.5, $max = $centerLon + 0.5);

        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 year');
        $progressPercentage = $this->faker->randomFloat(2, 0, 100);
        $status = 'On-Track';

        if ($progressPercentage >= 100) {
            $status = 'Selesai';
        } elseif ($endDate < now() && $progressPercentage < 100) {
            $status = 'Terlambat';
        }

        $sectors = [
            'Infrastruktur Jalan',
            'Pertanian dan Irigasi',
            'Kesehatan',
            'Pendidikan',
            'Pariwisata',
            'Pemberdayaan Masyarakat',
            'Sanitasi dan Air Bersih'
        ];

        $agencies = [
            'Dinas PUPR',
            'Dinas Pertanian',
            'Dinas Kesehatan',
            'Dinas Pendidikan',
            'Dinas Pariwisata',
            'Dinas PMD',
            'Bappeda'
        ];

        return [
            'name' => 'Proyek ' . $this->faker->catchPhrase(),
            'responsible_agency' => $this->faker->randomElement($agencies),
            'sector' => $this->faker->randomElement($sectors),
            'budget' => $this->faker->randomFloat(2, 10000000, 5000000000), // Anggaran 10 juta s.d 5 Miliar
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' => $this->faker->paragraph(rand(2, 5)),
            'progress_percentage' => $progressPercentage,
            'status' => $status,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }
}
