<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ProgramStudiResource;
use App\Models\ProgramStudi;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProgramStudiResourceTest extends TestCase
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
            ->get(ProgramStudiResource::getUrl('index'))
            ->assertSuccessful();
    }

    /** @test */
    public function can_render_create_page(): void
    {
        $this->actingAs($this->user)
            ->get(ProgramStudiResource::getUrl('create'))
            ->assertSuccessful();
    }

    /** @test */
    public function can_render_edit_page(): void
    {
        $programStudi = ProgramStudi::create([
            'kode' => 'TST',
            'nama' => 'Test Program Studi',
            'jenjang' => 'S1',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->actingAs($this->user)
            ->get(ProgramStudiResource::getUrl('edit', ['record' => $programStudi]))
            ->assertSuccessful();
    }

    /** @test */
    public function can_create_program_studi(): void
    {
        $this->actingAs($this->user);

        Livewire::test(ProgramStudiResource\Pages\CreateProgramStudi::class)
            ->fillForm([
                'kode' => 'NEW',
                'nama' => 'Program Studi Baru',
                'jenjang' => 'S1',
                'is_active' => true,
                'deskripsi' => 'Deskripsi program studi baru',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('program_studi', [
            'kode' => 'NEW',
            'nama' => 'Program Studi Baru',
            'jenjang' => 'S1',
        ]);
    }

    /** @test */
    public function can_update_program_studi(): void
    {
        $programStudi = ProgramStudi::create([
            'kode' => 'TST',
            'nama' => 'Test Program Studi',
            'jenjang' => 'S1',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->actingAs($this->user);

        Livewire::test(ProgramStudiResource\Pages\EditProgramStudi::class, [
            'record' => $programStudi->id,
        ])
            ->fillForm([
                'nama' => 'Updated Name',
                'jenjang' => 'S2',
                'is_active' => false,
                'deskripsi' => 'Updated description',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $programStudi->refresh();
        $this->assertEquals('Updated Name', $programStudi->nama);
        $this->assertEquals('S2', $programStudi->jenjang);
        $this->assertEquals(false, $programStudi->is_active);
        $this->assertEquals('Updated description', $programStudi->deskripsi);
    }

    /** @test */
    public function can_delete_program_studi(): void
    {
        $programStudi = ProgramStudi::create([
            'kode' => 'TST',
            'nama' => 'Test Program Studi',
            'jenjang' => 'S1',
            'is_active' => true,
            'deskripsi' => 'Deskripsi test',
        ]);

        $this->actingAs($this->user);

        Livewire::test(ProgramStudiResource\Pages\EditProgramStudi::class, [
            'record' => $programStudi->id,
        ])
            ->callAction(DeleteAction::class)
            ->assertHasNoActionErrors();

        $this->assertModelMissing($programStudi);
    }
}
