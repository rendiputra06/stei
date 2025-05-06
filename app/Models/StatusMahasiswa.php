<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusMahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'status_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        'tahun_akademik_id',
        'status', // tidak_aktif, aktif, cuti, lulus, drop_out
        'semester',
        'ip_semester',
        'ipk',
        'sks_semester',
        'sks_total',
        'keterangan',
    ];

    protected $casts = [
        'ip_semester' => 'float',
        'ipk' => 'float',
        'sks_semester' => 'integer',
        'sks_total' => 'integer',
    ];

    /**
     * Relasi ke mahasiswa
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Relasi ke tahun akademik
     */
    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    /**
     * Mendapatkan list status yang tersedia
     */
    public static function getStatusList(): array
    {
        return [
            'tidak_aktif' => 'Tidak Aktif',
            'aktif' => 'Aktif',
            'cuti' => 'Cuti',
            'lulus' => 'Lulus',
            'drop_out' => 'Drop Out'
        ];
    }

    /**
     * Menghitung semester berdasarkan tahun masuk mahasiswa dan tahun akademik
     */
    public static function hitungSemester(Mahasiswa $mahasiswa, TahunAkademik $tahunAkademik): int
    {
        // Menghitung selisih tahun
        $selisihTahun = $tahunAkademik->tahun - $mahasiswa->tahun_masuk;

        // Menghitung semester dasar dari selisih tahun (2 semester per tahun)
        $semester = $selisihTahun * 2;

        // Jika semester pada tahun akademik adalah Ganjil, tambahkan 1
        if ($tahunAkademik->semester === 'Ganjil') {
            $semester += 1;
        }
        // Jika semester adalah Pendek, gunakan semester terakhir + 0.5 (dibulatkan ke atas)
        elseif ($tahunAkademik->semester === 'Pendek') {
            $semester += 0.5;
            $semester = ceil($semester);
        }

        // Pastikan semester minimal 1
        return max(1, $semester);
    }
}
