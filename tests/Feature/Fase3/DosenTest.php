<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Filament\Resources\DosenResource\Pages\CreateDosen;
use App\Filament\Resources\DosenResource\Pages\EditDosen;
use App\Filament\Resources\DosenResource\Pages\ListDosens;

class DosenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Jalankan seeder role & permission
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_can_view_dosen_page()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        Livewire::test(ListDosens::class)
            ->assertSuccessful();
    }

    public function test_can_create_dosen()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        $dosenData = [
            'nip' => '199001012020121001',
            'nama' => 'Dr. Ahmad Fauzi, M.Kom',
            'email' => 'ahmad.fauzi@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Contoh No. 123, Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'is_active' => true,
        ];

        Livewire::test(CreateDosen::class)
            ->set('data.nip', $dosenData['nip'])
            ->set('data.nama', $dosenData['nama'])
            ->set('data.email', $dosenData['email'])
            ->set('data.no_hp', $dosenData['no_hp'])
            ->set('data.alamat', $dosenData['alamat'])
            ->set('data.tanggal_lahir', $dosenData['tanggal_lahir'])
            ->set('data.jenis_kelamin', $dosenData['jenis_kelamin'])
            ->set('data.is_active', $dosenData['is_active'])
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('dosen', [
            'nip' => '199001012020121001',
            'nama' => 'Dr. Ahmad Fauzi, M.Kom',
        ]);

        // Periksa apakah user juga dibuat
        $this->assertDatabaseHas('users', [
            'email' => 'ahmad.fauzi@example.com',
        ]);
    }

    public function test_can_edit_dosen()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        // Buat user terkait dosen
        $dosenUser = User::create([
            'name' => 'Dosen Edit',
            'email' => 'dosen.edit@example.com',
            'password' => bcrypt('password'),
        ]);
        $dosenUser->assignRole('dosen');

        $dosen = Dosen::create([
            'nip' => '199001012020121002',
            'nama' => 'Dosen Edit',
            'email' => 'dosen.edit@example.com',
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Contoh No. 124, Jakarta',
            'tanggal_lahir' => '1990-01-02',
            'jenis_kelamin' => 'Laki-laki',
            'user_id' => $dosenUser->id,
            'is_active' => true,
        ]);

        $updatedData = [
            'nip' => '199001012020121002',
            'nama' => 'Dosen Updated',
            'email' => 'dosen.updated@example.com',
            'no_hp' => '081234567892',
            'alamat' => 'Jl. Contoh No. 125, Jakarta',
            'tanggal_lahir' => '1990-01-02',
            'jenis_kelamin' => 'Laki-laki',
            'is_active' => true,
        ];

        Livewire::test(EditDosen::class, [
            'record' => $dosen->id,
        ])
            ->set('data.nip', $updatedData['nip'])
            ->set('data.nama', $updatedData['nama'])
            ->set('data.email', $updatedData['email'])
            ->set('data.no_hp', $updatedData['no_hp'])
            ->set('data.alamat', $updatedData['alamat'])
            ->set('data.tanggal_lahir', $updatedData['tanggal_lahir'])
            ->set('data.jenis_kelamin', $updatedData['jenis_kelamin'])
            ->set('data.is_active', $updatedData['is_active'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('dosen', [
            'id' => $dosen->id,
            'nama' => 'Dosen Updated',
            'email' => 'dosen.updated@example.com',
        ]);

        // Periksa apakah user juga diupdate
        $this->assertDatabaseHas('users', [
            'id' => $dosenUser->id,
            'email' => 'dosen.updated@example.com',
        ]);
    }

    public function test_can_delete_dosen()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        // Buat user terkait dosen
        $dosenUser = User::create([
            'name' => 'Dosen Delete',
            'email' => 'dosen.delete@example.com',
            'password' => bcrypt('password'),
        ]);
        $dosenUser->assignRole('dosen');

        $dosen = Dosen::create([
            'nip' => '199001012020121003',
            'nama' => 'Dosen Delete',
            'email' => 'dosen.delete@example.com',
            'no_hp' => '081234567893',
            'alamat' => 'Jl. Contoh No. 126, Jakarta',
            'tanggal_lahir' => '1990-01-03',
            'jenis_kelamin' => 'Laki-laki',
            'user_id' => $dosenUser->id,
            'is_active' => true,
        ]);

        Livewire::test(ListDosens::class)
            ->callTableAction('delete', $dosen)
            ->assertSuccessful();

        $this->assertDatabaseMissing('dosen', [
            'id' => $dosen->id,
        ]);

        // Periksa apakah user juga dihapus
        $this->assertDatabaseMissing('users', [
            'id' => $dosenUser->id,
        ]);
    }

    public function test_validation_errors_on_create_dosen()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);

        // Data dengan satu field kosong
        Livewire::test(CreateDosen::class)
            ->set('data.nama', 'Test Dosen')
            ->set('data.email', 'test.dosen@example.com')
            ->set('data.no_hp', '081234567890')
            ->set('data.alamat', 'Jl. Contoh No. 123, Jakarta')
            ->set('data.tanggal_lahir', '1990-01-01')
            ->set('data.jenis_kelamin', 'Laki-laki')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.nip' => 'required']);

        // NIP harus unik
        Dosen::create([
            'nip' => '199001012020121004',
            'nama' => 'Dosen Duplikat',
            'email' => 'dosen.dup@example.com',
            'no_hp' => '081234567894',
            'alamat' => 'Jl. Contoh No. 127, Jakarta',
            'tanggal_lahir' => '1990-01-04',
            'jenis_kelamin' => 'Laki-laki',
            'is_active' => true,
        ]);

        Livewire::test(CreateDosen::class)
            ->set('data.nip', '199001012020121004')
            ->set('data.nama', 'Dosen Duplikat 2')
            ->set('data.email', 'dosen.dup2@example.com')
            ->set('data.no_hp', '081234567895')
            ->set('data.alamat', 'Jl. Contoh No. 128, Jakarta')
            ->set('data.tanggal_lahir', '1990-01-05')
            ->set('data.jenis_kelamin', 'Laki-laki')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.nip' => 'unique']);

        // Email harus unik
        Livewire::test(CreateDosen::class)
            ->set('data.nip', '199001012020121005')
            ->set('data.nama', 'Dosen Duplikat 3')
            ->set('data.email', 'dosen.dup@example.com') // Email yang sudah digunakan
            ->set('data.no_hp', '081234567896')
            ->set('data.alamat', 'Jl. Contoh No. 129, Jakarta')
            ->set('data.tanggal_lahir', '1990-01-06')
            ->set('data.jenis_kelamin', 'Laki-laki')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasErrors(['data.email' => 'unique']);
    }
}
