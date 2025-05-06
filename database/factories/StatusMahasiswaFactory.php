<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\StatusMahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StatusMahasiswa>
 */
class StatusMahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['tidak_aktif', 'aktif', 'cuti', 'lulus', 'drop_out']);
        $semester = $this->faker->numberBetween(1, 8);
        $ipSemester = $this->faker->randomFloat(2, 1, 4);
        $ipk = $this->faker->randomFloat(2, 1, 4);
        $sksSemester = $this->faker->numberBetween(18, 24);
        $sksTotal = $semester * $this->faker->numberBetween(18, 24);

        $keteranganCuti = [
            'Cuti karena sakit',
            'Cuti karena keperluan keluarga',
            'Cuti karena masalah keuangan',
            'Cuti karena alasan pribadi',
        ];

        $keteranganDropOut = [
            'Drop out karena nilai rendah',
            'Drop out karena tidak aktif selama 2 semester berturut-turut',
            'Drop out karena melanggar peraturan akademik',
            'Drop out karena alasan lainnya',
        ];

        $keteranganAktif = [
            'Mahasiswa aktif mengikuti perkuliahan',
            'Mahasiswa aktif melakukan penelitian',
            'Mahasiswa aktif mengikuti program magang',
            'Mahasiswa aktif mengikuti kegiatan PKL',
        ];

        $keteranganLulus = [
            'Lulus dengan predikat cumlaude',
            'Lulus dengan predikat sangat memuaskan',
            'Lulus dengan predikat memuaskan',
            'Lulus standar',
        ];

        $keteranganTidakAktif = [
            'Mahasiswa belum melakukan registrasi',
            'Mahasiswa belum melakukan pembayaran',
            'Mahasiswa belum mengisi KRS',
            'Status mahasiswa belum dikonfirmasi',
        ];

        if ($status === 'cuti') {
            $keterangan = $this->faker->randomElement($keteranganCuti);
        } elseif ($status === 'drop_out') {
            $keterangan = $this->faker->randomElement($keteranganDropOut);
        } elseif ($status === 'lulus') {
            $keterangan = $this->faker->randomElement($keteranganLulus);
        } elseif ($status === 'tidak_aktif') {
            $keterangan = $this->faker->randomElement($keteranganTidakAktif);
        } else {
            $keterangan = $this->faker->randomElement($keteranganAktif);
        }

        return [
            'mahasiswa_id' => Mahasiswa::factory(),
            'tahun_akademik_id' => TahunAkademik::factory(),
            'status' => $status,
            'semester' => $semester,
            'ip_semester' => $ipSemester,
            'ipk' => $ipk,
            'sks_semester' => $sksSemester,
            'sks_total' => $sksTotal,
            'keterangan' => $keterangan,
        ];
    }

    /**
     * Status tidak aktif
     */
    public function tidakAktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'tidak_aktif',
            'keterangan' => $this->faker->randomElement([
                'Mahasiswa belum melakukan registrasi',
                'Mahasiswa belum melakukan pembayaran',
                'Mahasiswa belum mengisi KRS',
                'Status mahasiswa belum dikonfirmasi',
            ]),
        ]);
    }

    /**
     * Status aktif
     */
    public function aktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'aktif',
            'keterangan' => $this->faker->randomElement([
                'Mahasiswa aktif mengikuti perkuliahan',
                'Mahasiswa aktif melakukan penelitian',
                'Mahasiswa aktif mengikuti program magang',
                'Mahasiswa aktif mengikuti kegiatan PKL',
            ]),
        ]);
    }

    /**
     * Status cuti
     */
    public function cuti(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'cuti',
            'keterangan' => $this->faker->randomElement([
                'Cuti karena sakit',
                'Cuti karena keperluan keluarga',
                'Cuti karena masalah keuangan',
                'Cuti karena alasan pribadi',
            ]),
        ]);
    }

    /**
     * Status lulus
     */
    public function lulus(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'lulus',
            'semester' => 8,
            'keterangan' => $this->faker->randomElement([
                'Lulus dengan predikat cumlaude',
                'Lulus dengan predikat sangat memuaskan',
                'Lulus dengan predikat memuaskan',
                'Lulus standar',
            ]),
        ]);
    }

    /**
     * Status drop out
     */
    public function dropOut(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'drop_out',
            'keterangan' => $this->faker->randomElement([
                'Drop out karena nilai rendah',
                'Drop out karena tidak aktif selama 2 semester berturut-turut',
                'Drop out karena melanggar peraturan akademik',
                'Drop out karena alasan lainnya',
            ]),
        ]);
    }
}
