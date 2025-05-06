<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Filament\Resources\MahasiswaResource\Pages\CreateMahasiswa;
use App\Filament\Resources\MahasiswaResource\Pages\EditMahasiswa;
use App\Filament\Resources\MahasiswaResource\Pages\ListMahasiswas;

class MahasiswaTest extends TestCase
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

    public function test_can_view_mahasiswa_page()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        Livewire::test(ListMahasiswas::class)
            ->assertSuccessful();
    }

    public function test_can_create_mahasiswa()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        $mahasiswaData = [
            'nim' => '2023001',
            'nama' => 'Ananda Putra Test',
            'email' => 'ananda.test@example.com',
            'no_telepon' => '081234567891',
            'alamat' => 'Jl. Mahasiswa No. 1, Jakarta',
            'tanggal_lahir' => '2000-05-15',
            'jenis_kelamin' => 'L',
            'program_studi_id' => $this->programStudi->id,
            'tahun_masuk' => 2023,
            'status' => 'aktif',
            'is_active' => true,
        ];

        Livewire::test(CreateMahasiswa::class)
            ->set('data.nim', $mahasiswaData['nim'])
            ->set('data.nama', $mahasiswaData['nama'])
            ->set('data.email', $mahasiswaData['email'])
            ->set('data.no_telepon', $mahasiswaData['no_telepon'])
            ->set('data.alamat', $mahasiswaData['alamat'])
            ->set('data.tanggal_lahir', $mahasiswaData['tanggal_lahir'])
            ->set('data.jenis_kelamin', $mahasiswaData['jenis_kelamin'])
            ->set('data.program_studi_id', $mahasiswaData['program_studi_id'])
            ->set('data.tahun_masuk', $mahasiswaData['tahun_masuk'])
            ->set('data.status', $mahasiswaData['status'])
            ->set('data.is_active', $mahasiswaData['is_active'])
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('mahasiswa', [
            'nim' => '2023001',
            'nama' => 'Ananda Putra Test',
        ]);

        // Pastikan user juga dibuat otomatis
        $this->assertDatabaseHas('users', [
            'name' => 'Ananda Putra Test',
            'email' => 'ananda.test@example.com',
        ]);

        // Pastikan user mendapat role mahasiswa
        $user = User::where('email', 'ananda.test@example.com')->first();
        $this->assertTrue($user->hasRole('mahasiswa'));
    }

    public function test_can_edit_mahasiswa()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        // Buat user terlebih dahulu untuk mahasiswa
        $mahasiswaUser = User::create([
            'name' => 'Mahasiswa Update',
            'email' => 'mahasiswa.update@example.com',
            'password' => bcrypt('password'),
        ]);
        $mahasiswaUser->assignRole('mahasiswa');

        // Buat mahasiswa
        $mahasiswa = Mahasiswa::create([
            'nim' => '2023002',
            'nama' => 'Mahasiswa Update',
            'email' => 'mahasiswa.update@example.com',
            'no_telepon' => '081234567892',
            'alamat' => 'Jl. Mahasiswa No. 2, Jakarta',
            'tanggal_lahir' => '2001-07-22',
            'jenis_kelamin' => 'P',
            'program_studi_id' => $this->programStudi->id,
            'tahun_masuk' => 2023,
            'status' => 'aktif',
            'is_active' => true,
            'user_id' => $mahasiswaUser->id,
        ]);

        $updatedData = [
            'nim' => '2023002',
            'nama' => 'Mahasiswa Updated',
            'email' => 'mahasiswa.update@example.com',
            'no_telepon' => '081234567899',
            'alamat' => 'Jl. Mahasiswa No. 22, Jakarta',
            'tanggal_lahir' => '2001-07-22',
            'jenis_kelamin' => 'P',
            'program_studi_id' => $this->programStudi->id,
            'tahun_masuk' => 2023,
            'status' => 'cuti',
            'is_active' => true,
        ];

        Livewire::test(EditMahasiswa::class, [
            'record' => $mahasiswa->id,
        ])
            ->set('data.nim', $updatedData['nim'])
            ->set('data.nama', $updatedData['nama'])
            ->set('data.email', $updatedData['email'])
            ->set('data.no_telepon', $updatedData['no_telepon'])
            ->set('data.alamat', $updatedData['alamat'])
            ->set('data.tanggal_lahir', $updatedData['tanggal_lahir'])
            ->set('data.jenis_kelamin', $updatedData['jenis_kelamin'])
            ->set('data.program_studi_id', $updatedData['program_studi_id'])
            ->set('data.tahun_masuk', $updatedData['tahun_masuk'])
            ->set('data.status', $updatedData['status'])
            ->set('data.is_active', $updatedData['is_active'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('mahasiswa', [
            'id' => $mahasiswa->id,
            'nama' => 'Mahasiswa Updated',
            'status' => 'cuti',
        ]);

        // Pastikan user juga terupdate
        $this->assertDatabaseHas('users', [
            'id' => $mahasiswaUser->id,
            'name' => 'Mahasiswa Updated',
        ]);
    }

    public function test_can_delete_mahasiswa()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        // Buat user terlebih dahulu untuk mahasiswa
        $mahasiswaUser = User::create([
            'name' => 'Mahasiswa Delete',
            'email' => 'mahasiswa.delete@example.com',
            'password' => bcrypt('password'),
        ]);
        $mahasiswaUser->assignRole('mahasiswa');

        // Buat mahasiswa
        $mahasiswa = Mahasiswa::create([
            'nim' => '2022001',
            'nama' => 'Mahasiswa Delete',
            'email' => 'mahasiswa.delete@example.com',
            'no_telepon' => '081234567893',
            'alamat' => 'Jl. Mahasiswa No. 3, Jakarta',
            'tanggal_lahir' => '2000-02-12',
            'jenis_kelamin' => 'L',
            'program_studi_id' => $this->programStudi->id,
            'tahun_masuk' => 2022,
            'status' => 'aktif',
            'is_active' => true,
            'user_id' => $mahasiswaUser->id,
        ]);

        // Simpan user_id untuk mengecek apakah user juga dihapus
        $userId = $mahasiswa->user_id;

        Livewire::test(ListMahasiswas::class)
            ->callTableAction('delete', $mahasiswa)
            ->assertSuccessful();

        $this->assertDatabaseMissing('mahasiswa', [
            'id' => $mahasiswa->id,
        ]);

        // Pastikan user terkait juga dihapus
        $this->assertDatabaseMissing('users', [
            'id' => $userId,
        ]);
    }

    public function test_can_bulk_change_status_mahasiswa()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        // Buat beberapa mahasiswa
        $mahasiswa1 = Mahasiswa::create([
            'nim' => '2021001',
            'nama' => 'Mahasiswa Bulk 1',
            'email' => 'mahasiswa.bulk1@example.com',
            'no_telepon' => '081234567894',
            'jenis_kelamin' => 'L',
            'program_studi_id' => $this->programStudi->id,
            'tahun_masuk' => 2021,
            'status' => 'aktif',
            'is_active' => true,
        ]);

        $mahasiswa2 = Mahasiswa::create([
            'nim' => '2021002',
            'nama' => 'Mahasiswa Bulk 2',
            'email' => 'mahasiswa.bulk2@example.com',
            'no_telepon' => '081234567895',
            'jenis_kelamin' => 'P',
            'program_studi_id' => $this->programStudi->id,
            'tahun_masuk' => 2021,
            'status' => 'aktif',
            'is_active' => true,
        ]);

        // Test bulk action untuk mengubah status
        Livewire::test(ListMahasiswas::class)
            ->callTableBulkAction('ubahStatus', [
                $mahasiswa1->id,
                $mahasiswa2->id
            ], [
                'status' => 'lulus',
            ])
            ->assertSuccessful();

        // Verifikasi perubahan status di database
        $this->assertDatabaseHas('mahasiswa', [
            'id' => $mahasiswa1->id,
            'status' => 'lulus',
        ]);

        $this->assertDatabaseHas('mahasiswa', [
            'id' => $mahasiswa2->id,
            'status' => 'lulus',
        ]);
    }

    public function test_validation_errors_on_create_mahasiswa()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        // Data dengan satu field kosong
        Livewire::test(CreateMahasiswa::class)
            ->set('data.nama', 'Mahasiswa Test')
            ->set('data.email', 'mahasiswa.test@example.com')
            ->set('data.jenis_kelamin', 'L')
            ->set('data.program_studi_id', $this->programStudi->id)
            ->set('data.tahun_masuk', 2023)
            ->set('data.status', 'aktif')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.nim' => 'required']);

        // NIM dan Email harus unik
        Mahasiswa::create([
            'nim' => '2023003',
            'nama' => 'Mahasiswa Duplicate',
            'email' => 'mahasiswa.duplicate@example.com',
            'jenis_kelamin' => 'P',
            'program_studi_id' => $this->programStudi->id,
            'tahun_masuk' => 2023,
            'status' => 'aktif',
            'is_active' => true,
        ]);

        Livewire::test(CreateMahasiswa::class)
            ->set('data.nim', '2023003')
            ->set('data.nama', 'Mahasiswa Test')
            ->set('data.email', 'mahasiswa.test@example.com')
            ->set('data.jenis_kelamin', 'L')
            ->set('data.program_studi_id', $this->programStudi->id)
            ->set('data.tahun_masuk', 2023)
            ->set('data.status', 'aktif')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.nim' => 'unique']);

        Livewire::test(CreateMahasiswa::class)
            ->set('data.nim', '2023004')
            ->set('data.nama', 'Mahasiswa Test')
            ->set('data.email', 'mahasiswa.duplicate@example.com')
            ->set('data.jenis_kelamin', 'L')
            ->set('data.program_studi_id', $this->programStudi->id)
            ->set('data.tahun_masuk', 2023)
            ->set('data.status', 'aktif')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.email' => 'unique']);
    }
}
