<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil waktu acak dengan interval 30 menit
        $jam_mulai = fake()->dateTimeBetween('07:00', '17:00')->format('H:i:00');
        $jam_mulai_time = strtotime($jam_mulai);

        // Durasi kuliah acak antara 1-3 jam (30-90 menit)
        $durasi = fake()->randomElement([60, 90, 120, 150, 180]);
        $jam_selesai = date('H:i:00', $jam_mulai_time + ($durasi * 60));

        return [
            'tahun_akademik_id' => TahunAkademik::inRandomOrder()->first()->id ?? TahunAkademik::factory(),
            'mata_kuliah_id' => MataKuliah::inRandomOrder()->first()->id ?? MataKuliah::factory(),
            'dosen_id' => Dosen::inRandomOrder()->first()->id ?? Dosen::factory(),
            'ruangan_id' => Ruangan::inRandomOrder()->first()->id ?? Ruangan::factory(),
            'hari' => fake()->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']),
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'kelas' => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
            'is_active' => fake()->boolean(80), // 80% akan aktif
        ];
    }
}
