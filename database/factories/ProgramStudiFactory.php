<?php

namespace Database\Factories;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramStudi>
 */
class ProgramStudiFactory extends Factory
{
    protected $model = ProgramStudi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenjang = $this->faker->randomElement(['D3', 'S1', 'S2', 'S3']);
        $namaProdi = $this->faker->randomElement([
            'Teknik Informatika',
            'Teknik Elektro',
            'Sistem Informasi',
            'Manajemen Informatika',
            'Ilmu Komputer',
        ]);

        $kode = strtoupper(substr(str_replace(' ', '', $namaProdi), 0, 3));

        return [
            'nama' => $namaProdi . ' ' . $jenjang,
            'kode' => $kode,
            'jenjang' => $jenjang,
        ];
    }
}
