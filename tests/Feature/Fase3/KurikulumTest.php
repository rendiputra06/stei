<?php

namespace Tests\Feature;

use App\Models\Kurikulum;
use App\Models\ProgramStudi;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Filament\Resources\KurikulumResource\Pages\CreateKurikulum;
use App\Filament\Resources\KurikulumResource\Pages\EditKurikulum;
use App\Filament\Resources\KurikulumResource\Pages\ListKurikulums;

class KurikulumTest extends TestCase
{
    use RefreshDatabase;

    protected $programStudi;

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
    }

    public function test_can_view_kurikulum_page()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        Livewire::test(ListKurikulums::class)
            ->assertSuccessful();
    }

    public function test_can_create_kurikulum()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        $kurikulumData = [
            'kode' => 'KUR-TEST',
            'nama' => 'Kurikulum Test',
            'program_studi_id' => $this->programStudi->id,
            'tahun_mulai' => 2025,
            'status' => 'aktif',
            'is_active' => true,
            'deskripsi' => 'Deskripsi kurikulum test',
        ];

        Livewire::test(CreateKurikulum::class)
            ->set('data.kode', $kurikulumData['kode'])
            ->set('data.nama', $kurikulumData['nama'])
            ->set('data.program_studi_id', $kurikulumData['program_studi_id'])
            ->set('data.tahun_mulai', $kurikulumData['tahun_mulai'])
            ->set('data.status', $kurikulumData['status'])
            ->set('data.is_active', $kurikulumData['is_active'])
            ->set('data.deskripsi', $kurikulumData['deskripsi'])
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('kurikulum', [
            'kode' => 'KUR-TEST',
            'nama' => 'Kurikulum Test',
        ]);
    }

    public function test_can_edit_kurikulum()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        $kurikulum = Kurikulum::create([
            'kode' => 'KUR-EDIT',
            'nama' => 'Kurikulum Edit',
            'program_studi_id' => $this->programStudi->id,
            'tahun_mulai' => 2023,
            'status' => 'aktif',
            'is_active' => true,
            'deskripsi' => 'Deskripsi kurikulum edit',
        ]);

        $updatedData = [
            'kode' => 'KUR-EDIT',
            'nama' => 'Kurikulum Updated',
            'program_studi_id' => $this->programStudi->id,
            'tahun_mulai' => 2023,
            'status' => 'tidak aktif',
            'is_active' => true,
            'deskripsi' => 'Deskripsi kurikulum updated',
        ];

        Livewire::test(EditKurikulum::class, [
            'record' => $kurikulum->id,
        ])
            ->set('data.kode', $updatedData['kode'])
            ->set('data.nama', $updatedData['nama'])
            ->set('data.program_studi_id', $updatedData['program_studi_id'])
            ->set('data.tahun_mulai', $updatedData['tahun_mulai'])
            ->set('data.status', $updatedData['status'])
            ->set('data.is_active', $updatedData['is_active'])
            ->set('data.deskripsi', $updatedData['deskripsi'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('kurikulum', [
            'id' => $kurikulum->id,
            'nama' => 'Kurikulum Updated',
            'status' => 'tidak aktif',
        ]);
    }

    public function test_can_delete_kurikulum()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        $kurikulum = Kurikulum::create([
            'kode' => 'KUR-DEL',
            'nama' => 'Kurikulum Delete',
            'program_studi_id' => $this->programStudi->id,
            'tahun_mulai' => 2022,
            'status' => 'tidak aktif',
            'is_active' => true,
            'deskripsi' => 'Deskripsi kurikulum delete',
        ]);

        Livewire::test(ListKurikulums::class)
            ->callTableAction('delete', $kurikulum)
            ->assertSuccessful();

        $this->assertDatabaseMissing('kurikulum', [
            'id' => $kurikulum->id,
        ]);
    }

    public function test_validation_errors_on_create_kurikulum()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        // Data dengan satu field kosong
        Livewire::test(CreateKurikulum::class)
            ->set('data.nama', 'Test Kurikulum')
            ->set('data.program_studi_id', $this->programStudi->id)
            ->set('data.tahun_mulai', 2023)
            ->set('data.status', 'aktif')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.kode' => 'required']);

        // Kode kurikulum harus unik
        Kurikulum::create([
            'kode' => 'KUR-DUP',
            'nama' => 'Kurikulum Duplikat',
            'program_studi_id' => $this->programStudi->id,
            'tahun_mulai' => 2023,
            'status' => 'aktif',
            'is_active' => true,
        ]);

        Livewire::test(CreateKurikulum::class)
            ->set('data.kode', 'KUR-DUP')
            ->set('data.nama', 'Kurikulum Duplikat 2')
            ->set('data.program_studi_id', $this->programStudi->id)
            ->set('data.tahun_mulai', 2023)
            ->set('data.status', 'aktif')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.kode' => 'unique']);
    }
}
