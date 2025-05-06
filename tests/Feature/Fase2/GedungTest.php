<?php

namespace Tests\Feature;

use App\Models\Gedung;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GedungTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_gedung()
    {
        $gedungData = [
            'kode' => 'GDT',
            'nama' => 'Gedung Test',
            'lokasi' => 'Lokasi Test',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Gedung Test',
        ];

        $gedung = Gedung::create($gedungData);

        $this->assertDatabaseHas('gedung', $gedungData);
        $this->assertEquals($gedungData['kode'], $gedung->kode);
        $this->assertEquals($gedungData['nama'], $gedung->nama);
        $this->assertEquals($gedungData['lokasi'], $gedung->lokasi);
        $this->assertEquals($gedungData['is_active'], $gedung->is_active);
        $this->assertEquals($gedungData['deskripsi'], $gedung->deskripsi);
    }

    /** @test */
    public function it_can_update_a_gedung()
    {
        $gedung = Gedung::create([
            'kode' => 'GDT',
            'nama' => 'Gedung Test',
            'lokasi' => 'Lokasi Test',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Gedung Test',
        ]);

        $updateData = [
            'nama' => 'Gedung Updated',
            'lokasi' => 'Lokasi Updated',
            'is_active' => false,
            'deskripsi' => 'Deskripsi Updated',
        ];

        $gedung->update($updateData);
        $gedung->refresh();

        $this->assertEquals('GDT', $gedung->kode);
        $this->assertEquals($updateData['nama'], $gedung->nama);
        $this->assertEquals($updateData['lokasi'], $gedung->lokasi);
        $this->assertEquals($updateData['is_active'], $gedung->is_active);
        $this->assertEquals($updateData['deskripsi'], $gedung->deskripsi);
    }

    /** @test */
    public function it_can_delete_a_gedung()
    {
        $gedung = Gedung::create([
            'kode' => 'GDT',
            'nama' => 'Gedung Test',
            'lokasi' => 'Lokasi Test',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Gedung Test',
        ]);

        $gedung->delete();

        $this->assertDatabaseMissing('gedung', ['id' => $gedung->id]);
    }
}
