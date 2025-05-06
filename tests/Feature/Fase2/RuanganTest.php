<?php

namespace Tests\Feature;

use App\Models\Gedung;
use App\Models\ProgramStudi;
use App\Models\Ruangan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RuanganTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_ruangan()
    {
        $gedung = Gedung::create([
            'kode' => 'GDT',
            'nama' => 'Gedung Test',
            'lokasi' => 'Lokasi Test',
            'jumlah_lantai' => 3,
            'is_active' => true,
            'deskripsi' => 'Deskripsi Gedung Test',
        ]);

        $programStudi = ProgramStudi::create([
            'kode' => 'PST',
            'nama' => 'Program Studi Test',
            'jenjang' => 'S1',
            'fakultas' => 'Fakultas Test',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Program Studi Test',
        ]);

        $ruanganData = [
            'kode' => 'RGT',
            'nama' => 'Ruangan Test',
            'gedung_id' => $gedung->id,
            'program_studi_id' => $programStudi->id,
            'lantai' => 2,
            'kapasitas' => 30,
            'is_active' => true,
            'jenis' => 'kelas',
            'deskripsi' => 'Deskripsi Ruangan Test',
        ];

        $ruangan = Ruangan::create($ruanganData);

        $this->assertDatabaseHas('ruangan', $ruanganData);
        $this->assertEquals($ruanganData['kode'], $ruangan->kode);
        $this->assertEquals($ruanganData['nama'], $ruangan->nama);
        $this->assertEquals($ruanganData['gedung_id'], $ruangan->gedung_id);
        $this->assertEquals($ruanganData['program_studi_id'], $ruangan->program_studi_id);
        $this->assertEquals($ruanganData['lantai'], $ruangan->lantai);
        $this->assertEquals($ruanganData['kapasitas'], $ruangan->kapasitas);
        $this->assertEquals($ruanganData['is_active'], $ruangan->is_active);
        $this->assertEquals($ruanganData['jenis'], $ruangan->jenis);
        $this->assertEquals($ruanganData['deskripsi'], $ruangan->deskripsi);
    }

    /** @test */
    public function it_can_update_a_ruangan()
    {
        $gedung = Gedung::create([
            'kode' => 'GDT',
            'nama' => 'Gedung Test',
            'lokasi' => 'Lokasi Test',
            'jumlah_lantai' => 3,
            'is_active' => true,
            'deskripsi' => 'Deskripsi Gedung Test',
        ]);

        $programStudi = ProgramStudi::create([
            'kode' => 'PST',
            'nama' => 'Program Studi Test',
            'jenjang' => 'S1',
            'fakultas' => 'Fakultas Test',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Program Studi Test',
        ]);

        $programStudi2 = ProgramStudi::create([
            'kode' => 'PS2',
            'nama' => 'Program Studi Test 2',
            'jenjang' => 'S2',
            'fakultas' => 'Fakultas Test',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Program Studi Test 2',
        ]);

        $ruangan = Ruangan::create([
            'kode' => 'RGT',
            'nama' => 'Ruangan Test',
            'gedung_id' => $gedung->id,
            'program_studi_id' => $programStudi->id,
            'lantai' => 2,
            'kapasitas' => 30,
            'is_active' => true,
            'jenis' => 'kelas',
            'deskripsi' => 'Deskripsi Ruangan Test',
        ]);

        $updateData = [
            'nama' => 'Ruangan Updated',
            'program_studi_id' => $programStudi2->id,
            'lantai' => 3,
            'kapasitas' => 40,
            'is_active' => false,
            'jenis' => 'laboratorium',
            'deskripsi' => 'Deskripsi Updated',
        ];

        $ruangan->update($updateData);
        $ruangan->refresh();

        $this->assertEquals('RGT', $ruangan->kode);
        $this->assertEquals($updateData['nama'], $ruangan->nama);
        $this->assertEquals($gedung->id, $ruangan->gedung_id);
        $this->assertEquals($updateData['program_studi_id'], $ruangan->program_studi_id);
        $this->assertEquals($updateData['lantai'], $ruangan->lantai);
        $this->assertEquals($updateData['kapasitas'], $ruangan->kapasitas);
        $this->assertEquals($updateData['is_active'], $ruangan->is_active);
        $this->assertEquals($updateData['jenis'], $ruangan->jenis);
        $this->assertEquals($updateData['deskripsi'], $ruangan->deskripsi);
    }

    /** @test */
    public function it_can_delete_a_ruangan()
    {
        $gedung = Gedung::create([
            'kode' => 'GDT',
            'nama' => 'Gedung Test',
            'lokasi' => 'Lokasi Test',
            'jumlah_lantai' => 3,
            'is_active' => true,
            'deskripsi' => 'Deskripsi Gedung Test',
        ]);

        $programStudi = ProgramStudi::create([
            'kode' => 'PST',
            'nama' => 'Program Studi Test',
            'jenjang' => 'S1',
            'fakultas' => 'Fakultas Test',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Program Studi Test',
        ]);

        $ruangan = Ruangan::create([
            'kode' => 'RGT',
            'nama' => 'Ruangan Test',
            'gedung_id' => $gedung->id,
            'program_studi_id' => $programStudi->id,
            'lantai' => 2,
            'kapasitas' => 30,
            'is_active' => true,
            'jenis' => 'kelas',
            'deskripsi' => 'Deskripsi Ruangan Test',
        ]);

        $ruangan->delete();

        $this->assertDatabaseMissing('ruangan', ['id' => $ruangan->id]);
        $this->assertDatabaseHas('gedung', ['id' => $gedung->id]);
        $this->assertDatabaseHas('program_studi', ['id' => $programStudi->id]);
    }

    /** @test */
    public function it_belongs_to_gedung()
    {
        $gedung = Gedung::create([
            'kode' => 'GDT',
            'nama' => 'Gedung Test',
            'lokasi' => 'Lokasi Test',
            'jumlah_lantai' => 3,
            'is_active' => true,
            'deskripsi' => 'Deskripsi Gedung Test',
        ]);

        $ruangan = Ruangan::create([
            'kode' => 'RGT',
            'nama' => 'Ruangan Test',
            'gedung_id' => $gedung->id,
            'program_studi_id' => null,
            'lantai' => 2,
            'kapasitas' => 30,
            'is_active' => true,
            'jenis' => 'kelas',
            'deskripsi' => 'Deskripsi Ruangan Test',
        ]);

        $this->assertInstanceOf(Gedung::class, $ruangan->gedung);
        $this->assertEquals($gedung->id, $ruangan->gedung->id);
        $this->assertEquals($gedung->nama, $ruangan->gedung->nama);
    }

    /** @test */
    public function it_belongs_to_program_studi()
    {
        $programStudi = ProgramStudi::create([
            'kode' => 'PST',
            'nama' => 'Program Studi Test',
            'jenjang' => 'S1',
            'fakultas' => 'Fakultas Test',
            'is_active' => true,
            'deskripsi' => 'Deskripsi Program Studi Test',
        ]);

        $gedung = Gedung::create([
            'kode' => 'GDT',
            'nama' => 'Gedung Test',
            'lokasi' => 'Lokasi Test',
            'jumlah_lantai' => 3,
            'is_active' => true,
            'deskripsi' => 'Deskripsi Gedung Test',
        ]);

        $ruangan = Ruangan::create([
            'kode' => 'RGT',
            'nama' => 'Ruangan Test',
            'gedung_id' => $gedung->id,
            'program_studi_id' => $programStudi->id,
            'lantai' => 2,
            'kapasitas' => 30,
            'is_active' => true,
            'jenis' => 'kelas',
            'deskripsi' => 'Deskripsi Ruangan Test',
        ]);

        $this->assertInstanceOf(ProgramStudi::class, $ruangan->programStudi);
        $this->assertEquals($programStudi->id, $ruangan->programStudi->id);
        $this->assertEquals($programStudi->nama, $ruangan->programStudi->nama);
    }
}
