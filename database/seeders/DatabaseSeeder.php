<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleAndPermissionSeeder::class,
            TahunAkademikSeeder::class,
            ProgramStudiSeeder::class,
            GedungSeeder::class,
            RuanganSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
            KurikulumSeeder::class,
            MataKuliahSeeder::class,
            StatusMahasiswaSeeder::class,
            JadwalSeeder::class, // Menambahkan seeder jadwal
        ]);
    }
}
