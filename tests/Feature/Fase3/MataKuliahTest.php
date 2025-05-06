<?php

namespace Tests\Feature;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Filament\Resources\MataKuliahResource\Pages\CreateMataKuliah;
use App\Filament\Resources\MataKuliahResource\Pages\EditMataKuliah;
use App\Filament\Resources\MataKuliahResource\Pages\ListMataKuliahs;

class MataKuliahTest extends TestCase
{
    use RefreshDatabase;

    protected $programStudi;
    protected $kurikulum;

    protected function setUp(): void
    {
        parent::setUp();

        // Jalankan seeder role & permission
        $this->seed(RoleAndPermissionSeeder::class);

        // Buat program studi untuk testing
        $this->programStudi = ProgramStudi::create([
            'kode' => 'EI',
            'nama' => 'Ekonomi Islam',
            'jenjang' => 'S1',
            'is_active' => true,
            'deskripsi' => 'Program Studi Ekonomi Islam',
        ]);

        // Buat kurikulum untuk testing
        $this->kurikulum = Kurikulum::create([
            'kode' => 'KUR-TEST',
            'nama' => 'Kurikulum Test',
            'program_studi_id' => $this->programStudi->id,
            'tahun_mulai' => 2024,
            'status' => 'aktif',
            'is_active' => true,
            'deskripsi' => 'Kurikulum untuk testing',
        ]);
    }

    public function test_can_view_mata_kuliah_page()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        Livewire::test(ListMataKuliahs::class)
            ->assertSuccessful();
    }

    public function test_can_create_mata_kuliah()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        $mataKuliahData = [
            'kode' => 'MK-TEST',
            'nama' => 'Mata Kuliah Test',
            'program_studi_id' => $this->programStudi->id,
            'kurikulum_id' => $this->kurikulum->id,
            'sks' => 3,
            'semester' => 1,
            'jenis' => 'wajib',
            'is_active' => true,
            'deskripsi' => 'Deskripsi mata kuliah test',
        ];

        Livewire::test(CreateMataKuliah::class)
            ->set('data.kode', $mataKuliahData['kode'])
            ->set('data.nama', $mataKuliahData['nama'])
            ->set('data.program_studi_id', $mataKuliahData['program_studi_id'])
            ->set('data.kurikulum_id', $mataKuliahData['kurikulum_id'])
            ->set('data.sks', $mataKuliahData['sks'])
            ->set('data.semester', $mataKuliahData['semester'])
            ->set('data.jenis', $mataKuliahData['jenis'])
            ->set('data.is_active', $mataKuliahData['is_active'])
            ->set('data.deskripsi', $mataKuliahData['deskripsi'])
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('mata_kuliah', [
            'kode' => 'MK-TEST',
            'nama' => 'Mata Kuliah Test',
        ]);
    }

    public function test_can_edit_mata_kuliah()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        $mataKuliah = MataKuliah::create([
            'kode' => 'MK-EDIT',
            'nama' => 'Mata Kuliah Edit',
            'program_studi_id' => $this->programStudi->id,
            'kurikulum_id' => $this->kurikulum->id,
            'sks' => 2,
            'semester' => 2,
            'jenis' => 'wajib',
            'is_active' => true,
            'deskripsi' => 'Deskripsi mata kuliah edit',
        ]);

        $updatedData = [
            'kode' => 'MK-EDIT',
            'nama' => 'Mata Kuliah Updated',
            'program_studi_id' => $this->programStudi->id,
            'kurikulum_id' => $this->kurikulum->id,
            'sks' => 4,
            'semester' => 3,
            'jenis' => 'pilihan',
            'is_active' => true,
            'deskripsi' => 'Deskripsi mata kuliah updated',
        ];

        Livewire::test(EditMataKuliah::class, [
            'record' => $mataKuliah->id,
        ])
            ->set('data.kode', $updatedData['kode'])
            ->set('data.nama', $updatedData['nama'])
            ->set('data.program_studi_id', $updatedData['program_studi_id'])
            ->set('data.kurikulum_id', $updatedData['kurikulum_id'])
            ->set('data.sks', $updatedData['sks'])
            ->set('data.semester', $updatedData['semester'])
            ->set('data.jenis', $updatedData['jenis'])
            ->set('data.is_active', $updatedData['is_active'])
            ->set('data.deskripsi', $updatedData['deskripsi'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('mata_kuliah', [
            'id' => $mataKuliah->id,
            'nama' => 'Mata Kuliah Updated',
            'sks' => 4,
            'semester' => 3,
            'jenis' => 'pilihan',
        ]);
    }

    public function test_can_delete_mata_kuliah()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        $mataKuliah = MataKuliah::create([
            'kode' => 'MK-DEL',
            'nama' => 'Mata Kuliah Delete',
            'program_studi_id' => $this->programStudi->id,
            'kurikulum_id' => $this->kurikulum->id,
            'sks' => 2,
            'semester' => 1,
            'jenis' => 'wajib',
            'is_active' => true,
            'deskripsi' => 'Deskripsi mata kuliah delete',
        ]);

        Livewire::test(ListMataKuliahs::class)
            ->callTableAction('delete', $mataKuliah)
            ->assertSuccessful();

        $this->assertDatabaseMissing('mata_kuliah', [
            'id' => $mataKuliah->id,
        ]);
    }

    public function test_validation_errors_on_create_mata_kuliah()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        // Data dengan satu field kosong
        Livewire::test(CreateMataKuliah::class)
            ->set('data.nama', 'Test Mata Kuliah')
            ->set('data.program_studi_id', $this->programStudi->id)
            ->set('data.kurikulum_id', $this->kurikulum->id)
            ->set('data.sks', 3)
            ->set('data.semester', 1)
            ->set('data.jenis', 'wajib')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.kode' => 'required']);

        // Kode mata kuliah harus unik
        MataKuliah::create([
            'kode' => 'MK-DUP',
            'nama' => 'Mata Kuliah Duplikat',
            'program_studi_id' => $this->programStudi->id,
            'kurikulum_id' => $this->kurikulum->id,
            'sks' => 3,
            'semester' => 1,
            'jenis' => 'wajib',
            'is_active' => true,
        ]);

        Livewire::test(CreateMataKuliah::class)
            ->set('data.kode', 'MK-DUP')
            ->set('data.nama', 'Mata Kuliah Duplikat 2')
            ->set('data.program_studi_id', $this->programStudi->id)
            ->set('data.kurikulum_id', $this->kurikulum->id)
            ->set('data.sks', 3)
            ->set('data.semester', 1)
            ->set('data.jenis', 'wajib')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.kode' => 'unique']);
    }
}
