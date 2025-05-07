<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;

    public function definition(): array
    {
        $tahunMasuk = $this->faker->numberBetween(2018, 2023);

        return [
            'nim' => $this->faker->unique()->numerify('##########'),
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'no_telepon' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'tanggal_lahir' => $this->faker->date(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tahun_masuk' => $tahunMasuk,
            'status' => $this->faker->randomElement(['aktif', 'cuti', 'lulus', 'drop_out']),
            'is_active' => true,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Mahasiswa $mahasiswa) {
            // Set program studi jika belum diset
            if (empty($mahasiswa->program_studi_id)) {
                // Cari apakah ada program studi
                $programStudi = ProgramStudi::first();

                // Jika tidak ada, buat program studi
                if (!$programStudi) {
                    // Gunakan factory jika ada
                    if (class_exists('\Database\Factories\ProgramStudiFactory')) {
                        $programStudi = ProgramStudi::factory()->create();
                    } else {
                        // Buat manual jika tidak ada factory
                        $programStudi = ProgramStudi::create([
                            'nama' => 'Program Studi Default',
                            'kode' => 'PSD',
                            'jenjang' => 'S1',
                            'fakultas_id' => 1,
                        ]);
                    }
                }

                $mahasiswa->program_studi_id = $programStudi->id;
            }
        });
    }
}
