<?php

namespace Database\Factories;

use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TahunAkademik>
 */
class TahunAkademikFactory extends Factory
{
    protected $model = TahunAkademik::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tahun = $this->faker->numberBetween(2018, 2023);
        $semester = $this->faker->randomElement(['Ganjil', 'Genap', 'Pendek']);
        $semesterKode = $semester === 'Ganjil' ? '1' : ($semester === 'Genap' ? '2' : '3');
        $kode = $tahun . $semesterKode;

        $tanggalMulai = $this->faker->dateTimeBetween("$tahun-01-01", "$tahun-12-31");
        $tanggalSelesai = $this->faker->dateTimeBetween($tanggalMulai, "$tahun-12-31");

        $krsMulai = (clone $tanggalMulai)->modify('+2 days');
        $krsSelesai = (clone $krsMulai)->modify('+14 days');

        $nilaiMulai = (clone $tanggalSelesai)->modify('-21 days');
        $nilaiSelesai = (clone $tanggalSelesai)->modify('-7 days');

        return [
            'kode' => $kode,
            'tahun' => $tahun,
            'semester' => $semester,
            'nama' => "Semester $semester " . $tahun . "/" . ($tahun + 1),
            'aktif' => false,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'krs_mulai' => $krsMulai,
            'krs_selesai' => $krsSelesai,
            'nilai_mulai' => $nilaiMulai,
            'nilai_selesai' => $nilaiSelesai,
        ];
    }

    /**
     * Menandai tahun akademik sebagai aktif.
     */
    public function aktif()
    {
        return $this->state(function (array $attributes) {
            return [
                'aktif' => true,
            ];
        });
    }
}
