<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'krs_detail_id',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_kehadiran',
        'nilai_akhir',
        'grade',
        'keterangan',
    ];

    protected $casts = [
        'nilai_tugas' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_kehadiran' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    /**
     * Mendapatkan detail KRS yang terkait dengan nilai
     */
    public function krsDetail(): BelongsTo
    {
        return $this->belongsTo(KRSDetail::class, 'krs_detail_id');
    }

    /**
     * Menghitung nilai akhir berdasarkan bobot
     * Bobot default: Tugas 20%, UTS 30%, UAS 40%, Kehadiran 10%
     */
    public function hitungNilaiAkhir(
        float $bobotTugas = 0.2,
        float $bobotUTS = 0.3,
        float $bobotUAS = 0.4,
        float $bobotKehadiran = 0.1
    ): float {
        $nilaiAkhir = 0;

        if ($this->nilai_tugas !== null) {
            $nilaiAkhir += $this->nilai_tugas * $bobotTugas;
        }

        if ($this->nilai_uts !== null) {
            $nilaiAkhir += $this->nilai_uts * $bobotUTS;
        }

        if ($this->nilai_uas !== null) {
            $nilaiAkhir += $this->nilai_uas * $bobotUAS;
        }

        if ($this->nilai_kehadiran !== null) {
            $nilaiAkhir += $this->nilai_kehadiran * $bobotKehadiran;
        }

        return round($nilaiAkhir, 2);
    }

    /**
     * Menentukan grade berdasarkan nilai akhir
     */
    public function tentukanGrade(float $nilaiAkhir = null): string
    {
        $nilai = $nilaiAkhir ?? $this->nilai_akhir;

        if ($nilai === null) {
            return '';
        }

        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'A-';
        if ($nilai >= 75) return 'B+';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 65) return 'B-';
        if ($nilai >= 60) return 'C+';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 40) return 'D';
        return 'E';
    }

    /**
     * Update nilai akhir dan grade berdasarkan komponen nilai
     */
    public function updateNilaiAkhirDanGrade(): void
    {
        $this->nilai_akhir = $this->hitungNilaiAkhir();
        $this->grade = $this->tentukanGrade();
        $this->save();
    }
}
