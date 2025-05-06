<?php

namespace Tests\Feature;

use App\Filament\Resources\StatusMahasiswaResource\Pages\CreateStatusMahasiswa;
use App\Filament\Resources\StatusMahasiswaResource\Pages\EditStatusMahasiswa;
use App\Filament\Resources\StatusMahasiswaResource\Pages\ListStatusMahasiswas;
use App\Livewire\StatusMahasiswaTable;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\StatusMahasiswa;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class StatusMahasiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $programStudi;

    public function setUp(): void
    {
        parent::setUp();

        // Buat program studi untuk testing
        $this->programStudi = ProgramStudi::create([
            'kode' => 'TI',
            'nama' => 'Teknik Informatika',
            'jenjang' => 'S1',
            'is_active' => true,
        ]);
    }

    public function test_status_mahasiswa_page_can_be_rendered(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('status-mahasiswa'));

        $response->assertStatus(200);
    }

    public function test_status_mahasiswa_table_displays_data(): void
    {
        $this->actingAs(User::factory()->create());

        $tahunAkademik = TahunAkademik::factory()->create(['aktif' => true]);
        $mahasiswa = Mahasiswa::factory()->create([
            'program_studi_id' => $this->programStudi->id
        ]);
        $statusMahasiswa = StatusMahasiswa::factory()->create([
            'mahasiswa_id' => $mahasiswa->id,
            'tahun_akademik_id' => $tahunAkademik->id,
            'status' => 'aktif',
            'semester' => 3,
            'ip_semester' => 3.5,
            'ipk' => 3.6,
        ]);

        Livewire::test(StatusMahasiswaTable::class)
            ->assertCanSeeTableRecords([$statusMahasiswa])
            ->filterTable('tahun_akademik_id', $tahunAkademik->id)
            ->assertCanSeeTableRecords([$statusMahasiswa]);
    }

    public function test_status_mahasiswa_table_filters_by_tahun_akademik(): void
    {
        $this->actingAs(User::factory()->create());

        $tahunAkademik1 = TahunAkademik::factory()->create(['aktif' => true]);
        $tahunAkademik2 = TahunAkademik::factory()->create(['aktif' => false]);

        $mahasiswa = Mahasiswa::factory()->create([
            'program_studi_id' => $this->programStudi->id
        ]);

        $statusMahasiswa1 = StatusMahasiswa::factory()->create([
            'mahasiswa_id' => $mahasiswa->id,
            'tahun_akademik_id' => $tahunAkademik1->id,
        ]);

        $statusMahasiswa2 = StatusMahasiswa::factory()->create([
            'mahasiswa_id' => $mahasiswa->id,
            'tahun_akademik_id' => $tahunAkademik2->id,
        ]);

        Livewire::test(StatusMahasiswaTable::class)
            ->filterTable('tahun_akademik_id', $tahunAkademik1->id)
            ->assertCanSeeTableRecords([$statusMahasiswa1])
            ->assertCanNotSeeTableRecords([$statusMahasiswa2]);
    }

    public function test_status_mahasiswa_table_filters_by_status(): void
    {
        $this->actingAs(User::factory()->create());

        $tahunAkademik = TahunAkademik::factory()->create(['aktif' => true]);

        $mahasiswa1 = Mahasiswa::factory()->create([
            'program_studi_id' => $this->programStudi->id
        ]);
        $mahasiswa2 = Mahasiswa::factory()->create([
            'program_studi_id' => $this->programStudi->id
        ]);

        $statusMahasiswaAktif = StatusMahasiswa::factory()->create([
            'mahasiswa_id' => $mahasiswa1->id,
            'tahun_akademik_id' => $tahunAkademik->id,
            'status' => 'aktif',
        ]);

        $statusMahasiswaTidakAktif = StatusMahasiswa::factory()->create([
            'mahasiswa_id' => $mahasiswa2->id,
            'tahun_akademik_id' => $tahunAkademik->id,
            'status' => 'tidak_aktif',
        ]);

        Livewire::test(StatusMahasiswaTable::class)
            ->filterTable('status', 'aktif')
            ->assertCanSeeTableRecords([$statusMahasiswaAktif])
            ->assertCanNotSeeTableRecords([$statusMahasiswaTidakAktif]);
    }

    public function test_can_create_status_mahasiswa(): void
    {
        $this->actingAs(User::factory()->create());

        $mahasiswa = Mahasiswa::factory()->create([
            'program_studi_id' => $this->programStudi->id
        ]);
        $tahunAkademik = TahunAkademik::factory()->create();

        $semester = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);

        $data = [
            'mahasiswa_id' => $mahasiswa->id,
            'tahun_akademik_id' => $tahunAkademik->id,
            'status' => 'tidak_aktif',
            'semester' => $semester,
            'ip_semester' => 3.5,
            'ipk' => 3.6,
            'sks_semester' => 21,
            'sks_total' => 63,
            'keterangan' => 'Mahasiswa belum melakukan registrasi',
        ];

        Livewire::test(CreateStatusMahasiswa::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('status_mahasiswa', [
            'mahasiswa_id' => $mahasiswa->id,
            'tahun_akademik_id' => $tahunAkademik->id,
            'status' => 'tidak_aktif',
            'semester' => $semester,
        ]);
    }

    public function test_semester_is_calculated_correctly(): void
    {
        $this->actingAs(User::factory()->create());

        // Buat mahasiswa dengan tahun masuk 2022
        $mahasiswa = Mahasiswa::factory()->create([
            'program_studi_id' => $this->programStudi->id,
            'tahun_masuk' => 2022,
        ]);

        // Buat tahun akademik 2023 semester Ganjil
        $tahunAkademik = TahunAkademik::factory()->create([
            'tahun' => 2023,
            'semester' => 'Ganjil',
        ]);

        // Semester seharusnya 3 (2023 - 2022 = 1 tahun * 2 + 1 = 3)
        $semester = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik);
        $this->assertEquals(3, $semester);

        // Buat tahun akademik 2023 semester Genap
        $tahunAkademik2 = TahunAkademik::factory()->create([
            'tahun' => 2023,
            'semester' => 'Genap',
        ]);

        // Semester seharusnya 4 (2023 - 2022 = 1 tahun * 2 + 0 = 2, semester genap = 4)
        $semester2 = StatusMahasiswa::hitungSemester($mahasiswa, $tahunAkademik2);
        $this->assertEquals(4, $semester2);
    }
}
