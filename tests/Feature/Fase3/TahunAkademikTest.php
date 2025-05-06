<?php

namespace Tests\Feature;

use App\Filament\Resources\TahunAkademikResource\Pages\CreateTahunAkademik;
use App\Filament\Resources\TahunAkademikResource\Pages\EditTahunAkademik;
use App\Filament\Resources\TahunAkademikResource\Pages\ListTahunAkademiks;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TahunAkademikTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_tahun_akademik_list(): void
    {
        $this->actingAs(User::factory()->create());

        $tahunAkademiks = TahunAkademik::factory()->count(3)->create();

        Livewire::test(ListTahunAkademiks::class)
            ->assertCanSeeTableRecords($tahunAkademiks);
    }

    public function test_can_create_tahun_akademik(): void
    {
        $this->actingAs(User::factory()->create());

        $data = [
            'kode' => '20251',
            'tahun' => 2025,
            'semester' => 'Ganjil',
            'nama' => 'Semester Ganjil 2025/2026',
            'aktif' => true,
            'tanggal_mulai' => '2025-09-01',
            'tanggal_selesai' => '2026-01-31',
            'krs_mulai' => '2025-08-15 08:00:00',
            'krs_selesai' => '2025-08-28 23:59:59',
            'nilai_mulai' => '2026-01-10 08:00:00',
            'nilai_selesai' => '2026-01-25 23:59:59',
        ];

        Livewire::test(CreateTahunAkademik::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tahun_akademik', [
            'kode' => '20251',
            'nama' => 'Semester Ganjil 2025/2026',
            'aktif' => true,
        ]);
    }

    public function test_can_update_tahun_akademik(): void
    {
        $this->actingAs(User::factory()->create());

        $tahunAkademik = TahunAkademik::factory()->create([
            'kode' => '20251',
            'nama' => 'Semester Ganjil 2025/2026',
            'aktif' => false,
        ]);

        $data = [
            'kode' => '20251',
            'tahun' => 2025,
            'semester' => 'Ganjil',
            'nama' => 'Semester Ganjil 2025/2026 (Updated)',
            'aktif' => true,
            'tanggal_mulai' => '2025-09-01',
            'tanggal_selesai' => '2026-01-31',
            'krs_mulai' => '2025-08-15 08:00:00',
            'krs_selesai' => '2025-08-28 23:59:59',
            'nilai_mulai' => '2026-01-10 08:00:00',
            'nilai_selesai' => '2026-01-25 23:59:59',
        ];

        Livewire::test(EditTahunAkademik::class, [
            'record' => $tahunAkademik->id,
        ])
            ->fillForm($data)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tahun_akademik', [
            'id' => $tahunAkademik->id,
            'nama' => 'Semester Ganjil 2025/2026 (Updated)',
            'aktif' => true,
        ]);
    }

    public function test_only_one_tahun_akademik_can_be_active(): void
    {
        $this->actingAs(User::factory()->create());

        // Buat beberapa tahun akademik dengan satu aktif
        $tahunAkademik1 = TahunAkademik::factory()->create(['aktif' => true]);
        $tahunAkademik2 = TahunAkademik::factory()->create(['aktif' => false]);
        $tahunAkademik3 = TahunAkademik::factory()->create(['aktif' => false]);

        // Pastikan hanya satu tahun akademik yang aktif
        $this->assertEquals(1, TahunAkademik::where('aktif', true)->count());

        // Aktifkan tahun akademik lain
        $data = [
            'kode' => $tahunAkademik2->kode,
            'tahun' => $tahunAkademik2->tahun,
            'semester' => $tahunAkademik2->semester,
            'nama' => $tahunAkademik2->nama,
            'aktif' => true,
            'tanggal_mulai' => $tahunAkademik2->tanggal_mulai,
            'tanggal_selesai' => $tahunAkademik2->tanggal_selesai,
            'krs_mulai' => $tahunAkademik2->krs_mulai,
            'krs_selesai' => $tahunAkademik2->krs_selesai,
            'nilai_mulai' => $tahunAkademik2->nilai_mulai,
            'nilai_selesai' => $tahunAkademik2->nilai_selesai,
        ];

        Livewire::test(EditTahunAkademik::class, [
            'record' => $tahunAkademik2->id,
        ])
            ->fillForm($data)
            ->call('save')
            ->assertHasNoFormErrors();

        // Pastikan masih hanya satu tahun akademik yang aktif
        $this->assertEquals(1, TahunAkademik::where('aktif', true)->count());

        // Pastikan tahun akademik yang sekarang aktif adalah yang kedua
        $this->assertDatabaseHas('tahun_akademik', [
            'id' => $tahunAkademik2->id,
            'aktif' => true,
        ]);

        // Pastikan tahun akademik pertama sekarang tidak aktif
        $this->assertDatabaseHas('tahun_akademik', [
            'id' => $tahunAkademik1->id,
            'aktif' => false,
        ]);
    }

    public function test_can_delete_tahun_akademik(): void
    {
        $this->actingAs(User::factory()->create());

        $tahunAkademik = TahunAkademik::factory()->create();

        Livewire::test(ListTahunAkademiks::class)
            ->callTableAction('delete', $tahunAkademik);

        // Pastikan data masih ada di database (soft deleted)
        $this->assertSoftDeleted($tahunAkademik);
    }
}
