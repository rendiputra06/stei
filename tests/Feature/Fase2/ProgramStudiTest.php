<?php

namespace Tests\Feature;

use App\Models\ProgramStudi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProgramStudiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_program_studi()
    {
        $programStudiData = [
            'kode' => 'PST',
            'nama' => 'Program Studi Test',
            'jenjang' => 'S1',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Program Studi Test',
        ];

        $programStudi = ProgramStudi::create($programStudiData);

        $this->assertDatabaseHas('program_studi', $programStudiData);
        $this->assertEquals($programStudiData['kode'], $programStudi->kode);
        $this->assertEquals($programStudiData['nama'], $programStudi->nama);
        $this->assertEquals($programStudiData['jenjang'], $programStudi->jenjang);
        $this->assertEquals($programStudiData['is_active'], $programStudi->is_active);
        $this->assertEquals($programStudiData['deskripsi'], $programStudi->deskripsi);
    }

    /** @test */
    public function it_can_update_a_program_studi()
    {
        $programStudi = ProgramStudi::create([
            'kode' => 'PST',
            'nama' => 'Program Studi Test',
            'jenjang' => 'S1',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Program Studi Test',
        ]);

        $updateData = [
            'nama' => 'Program Studi Updated',
            'jenjang' => 'S2',
            'is_active' => false,
            'deskripsi' => 'Deskripsi Updated',
        ];

        $programStudi->update($updateData);
        $programStudi->refresh();

        $this->assertEquals('PST', $programStudi->kode);
        $this->assertEquals($updateData['nama'], $programStudi->nama);
        $this->assertEquals($updateData['jenjang'], $programStudi->jenjang);
        $this->assertEquals($updateData['is_active'], $programStudi->is_active);
        $this->assertEquals($updateData['deskripsi'], $programStudi->deskripsi);
    }

    /** @test */
    public function it_can_delete_a_program_studi()
    {
        $programStudi = ProgramStudi::create([
            'kode' => 'PST',
            'nama' => 'Program Studi Test',
            'jenjang' => 'S1',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Program Studi Test',
        ]);

        $programStudi->delete();

        $this->assertDatabaseMissing('program_studi', ['id' => $programStudi->id]);
    }
}
