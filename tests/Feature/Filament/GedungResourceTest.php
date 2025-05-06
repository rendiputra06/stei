<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\GedungResource;
use App\Models\Gedung;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GedungResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
        ])->assignRole('admin');
    }

    /** @test */
    public function can_render_index_page(): void
    {
        $this->actingAs($this->user)
            ->get(GedungResource::getUrl('index'))
            ->assertSuccessful();
    }

    /** @test */
    public function can_render_create_page(): void
    {
        $this->actingAs($this->user)
            ->get(GedungResource::getUrl('create'))
            ->assertSuccessful();
    }

    /** @test */
    public function can_render_edit_page(): void
    {
        $gedung = Gedung::create([
            'kode' => 'GD-T',
            'nama' => 'Test Gedung',
            'lokasi' => 'Test Lokasi',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->actingAs($this->user)
            ->get(GedungResource::getUrl('edit', ['record' => $gedung]))
            ->assertSuccessful();
    }

    /** @test */
    public function can_create_gedung(): void
    {
        $this->actingAs($this->user);

        Livewire::test(GedungResource\Pages\CreateGedung::class)
            ->fillForm([
                'nama' => 'Gedung Baru',
                'lokasi' => 'Lokasi Baru',
                'is_active' => true,
                'deskripsi' => 'Deskripsi gedung baru',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('gedung', [
            'nama' => 'Gedung Baru',
            'lokasi' => 'Lokasi Baru',
        ]);

        // Verify auto generated code
        $gedung = Gedung::where('nama', 'Gedung Baru')->first();
        $this->assertNotNull($gedung->kode);
        $this->assertStringStartsWith('GD-', $gedung->kode);
    }

    /** @test */
    public function can_update_gedung(): void
    {
        $gedung = Gedung::create([
            'kode' => 'GD-T',
            'nama' => 'Test Gedung',
            'lokasi' => 'Test Lokasi',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->actingAs($this->user);

        Livewire::test(GedungResource\Pages\EditGedung::class, [
            'record' => $gedung->id,
        ])
            ->fillForm([
                'nama' => 'Updated Name',
                'lokasi' => 'Updated Location',
                'is_active' => false,
                'deskripsi' => 'Updated description',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $gedung->refresh();
        $this->assertEquals('Updated Name', $gedung->nama);
        $this->assertEquals('Updated Location', $gedung->lokasi);
        $this->assertEquals(false, $gedung->is_active);
        $this->assertEquals('Updated description', $gedung->deskripsi);
    }

    /** @test */
    public function can_delete_gedung(): void
    {
        $gedung = Gedung::create([
            'kode' => 'GD-T',
            'nama' => 'Test Gedung',
            'lokasi' => 'Test Lokasi',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->actingAs($this->user);

        Livewire::test(GedungResource\Pages\EditGedung::class, [
            'record' => $gedung->id,
        ])
            ->callAction(DeleteAction::class)
            ->assertHasNoActionErrors();

        $this->assertModelMissing($gedung);
    }

    /** @test */
    public function it_auto_generates_code_when_creating_gedung(): void
    {
        $gedung1 = Gedung::create([
            'nama' => 'Gedung Test 1',
            'lokasi' => 'Lokasi Test 1',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test 1',
        ]);

        $gedung2 = Gedung::create([
            'nama' => 'Gedung Test 2',
            'lokasi' => 'Lokasi Test 2',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test 2',
        ]);

        $this->assertStringStartsWith('GD-', $gedung1->kode);
        $this->assertStringStartsWith('GD-', $gedung2->kode);
        $this->assertNotEquals($gedung1->kode, $gedung2->kode);
    }
}
