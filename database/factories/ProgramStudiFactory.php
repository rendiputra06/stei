<?php

namespace Database\Factories;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramStudi>
 */
class ProgramStudiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = $this->faker->randomElement([
            'Teknik Informatika',
            'Sistem Informasi',
            'Ilmu Komputer',
            'Manajemen Informatika',
            'Teknik Komputer',
            'Desain Komunikasi Visual',
        ]);

        $kode = strtoupper(substr(str_replace(' ', '', $nama), 0, 2)) . $this->faker->unique()->numberBetween(10, 99);

        return [
            'kode' => $kode,
            'nama' => $nama,
            'jenjang' => $this->faker->randomElement(['D3', 'D4', 'S1', 'S2']),
            'is_active' => true,
        ];
    }
}
