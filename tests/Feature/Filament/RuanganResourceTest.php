<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\RuanganResource;
use App\Models\Gedung;
use App\Models\ProgramStudi;
use App\Models\Ruangan;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RuanganResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Gedung $gedung;
    private ProgramStudi $programStudi;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
        ])->assignRole('admin');

        $this->gedung = Gedung::create([
            'kode' => 'GD-T',
            'nama' => 'Test Gedung',
            'lokasi' => 'Test Lokasi',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->programStudi = ProgramStudi::create([
            'kode' => 'TST',
            'nama' => 'Test Program Studi',
            'jenjang' => 'S1',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);
    }

    /** @test */
    public function can_render_index_page(): void
    {
        $this->actingAs($this->user)
            ->get(RuanganResource::getUrl('index'))
            ->assertSuccessful();
    }

    /** @test */
    public function can_render_create_page(): void
    {
        $this->actingAs($this->user)
            ->get(RuanganResource::getUrl('create'))
            ->assertSuccessful();
    }

    /** @test */
    public function can_render_edit_page(): void
    {
        $ruangan = Ruangan::create([
            'kode' => 'T101',
            'nama' => 'Test Ruangan',
            'gedung_id' => $this->gedung->id,
            'program_studi_id' => $this->programStudi->id,
            'lantai' => 1,
            'kapasitas' => 30,
            'jenis' => 'kelas',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->actingAs($this->user)
            ->get(RuanganResource::getUrl('edit', ['record' => $ruangan]))
            ->assertSuccessful();
    }

    /** @test */
    public function can_create_ruangan(): void
    {
        $this->actingAs($this->user);

        Livewire::test(RuanganResource\Pages\CreateRuangan::class)
            ->fillForm([
                'nama' => 'Ruangan Baru',
                'gedung_id' => $this->gedung->id,
                'program_studi_id' => $this->programStudi->id,
                'lantai' => 2,
                'kapasitas' => 40,
                'jenis' => 'laboratorium',
                'is_active' => true,
                'deskripsi' => 'Deskripsi ruangan baru',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('ruangan', [
            'nama' => 'Ruangan Baru',
            'gedung_id' => $this->gedung->id,
            'program_studi_id' => $this->programStudi->id,
            'lantai' => 2,
            'kapasitas' => 40,
            'jenis' => 'laboratorium',
        ]);

        // Verify auto generated code
        $ruangan = Ruangan::where('nama', 'Ruangan Baru')->first();
        $this->assertNotNull($ruangan->kode);
        // Code format: gedungCode + lantai + sequence number
        $expectedCodePrefix = substr($this->gedung->kode, 3) . '2'; // T2 (from GD-T + lantai 2)
        $this->assertStringStartsWith($expectedCodePrefix, $ruangan->kode);
    }

    /** @test */
    public function can_update_ruangan(): void
    {
        $ruangan = Ruangan::create([
            'kode' => 'T101',
            'nama' => 'Test Ruangan',
            'gedung_id' => $this->gedung->id,
            'program_studi_id' => $this->programStudi->id,
            'lantai' => 1,
            'kapasitas' => 30,
            'jenis' => 'kelas',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->actingAs($this->user);

        Livewire::test(RuanganResource\Pages\EditRuangan::class, [
            'record' => $ruangan->id,
        ])
            ->fillForm([
                'nama' => 'Updated Name',
                'lantai' => 3,
                'kapasitas' => 45,
                'jenis' => 'kantor',
                'is_active' => false,
                'deskripsi' => 'Updated description',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $ruangan->refresh();
        $this->assertEquals('Updated Name', $ruangan->nama);
        $this->assertEquals(3, $ruangan->lantai);
        $this->assertEquals(45, $ruangan->kapasitas);
        $this->assertEquals('kantor', $ruangan->jenis);
        $this->assertEquals(false, $ruangan->is_active);
        $this->assertEquals('Updated description', $ruangan->deskripsi);
    }

    /** @test */
    public function can_delete_ruangan(): void
    {
        $ruangan = Ruangan::create([
            'kode' => 'T101',
            'nama' => 'Test Ruangan',
            'gedung_id' => $this->gedung->id,
            'program_studi_id' => $this->programStudi->id,
            'lantai' => 1,
            'kapasitas' => 30,
            'jenis' => 'kelas',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->actingAs($this->user);

        Livewire::test(RuanganResource\Pages\EditRuangan::class, [
            'record' => $ruangan->id,
        ])
            ->callAction(DeleteAction::class)
            ->assertHasNoActionErrors();

        $this->assertModelMissing($ruangan);
    }

    /** @test */
    public function it_auto_generates_code_when_creating_ruangan(): void
    {
        $ruangan1 = Ruangan::create([
            'nama' => 'Ruangan Test 1',
            'gedung_id' => $this->gedung->id,
            'program_studi_id' => $this->programStudi->id,
            'lantai' => 1,
            'kapasitas' => 30,
            'jenis' => 'kelas',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test 1',
        ]);

        $ruangan2 = Ruangan::create([
            'nama' => 'Ruangan Test 2',
            'gedung_id' => $this->gedung->id,
            'program_studi_id' => $this->programStudi->id,
            'lantai' => 1,
            'kapasitas' => 30,
            'jenis' => 'kelas',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test 2',
        ]);

        $expectedCodePrefix = substr($this->gedung->kode, 3) . '1'; // T1 (from GD-T + lantai 1)
        $this->assertStringStartsWith($expectedCodePrefix, $ruangan1->kode);
        $this->assertStringStartsWith($expectedCodePrefix, $ruangan2->kode);
        $this->assertNotEquals($ruangan1->kode, $ruangan2->kode);
    }
}
