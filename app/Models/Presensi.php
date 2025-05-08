<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'jadwal_id',
        'pertemuan_ke',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'pertemuan_ke' => 'integer',
    ];

    /**
     * Mendapatkan jadwal yang terkait dengan presensi
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Mendapatkan detail presensi
     */
    public function presensiDetail(): HasMany
    {
        return $this->hasMany(PresensiDetail::class);
    }

    /**
     * Mendapatkan daftar mahasiswa yang hadir
     */
    public function mahasiswaHadir()
    {
        return $this->presensiDetail()->where('status', 'hadir')->count();
    }

    /**
     * Mendapatkan daftar mahasiswa yang tidak hadir
     */
    public function mahasiswaTidakHadir()
    {
        return $this->presensiDetail()->whereIn('status', ['izin', 'sakit', 'alpa'])->count();
    }

    /**
     * Mendapatkan total mahasiswa
     */
    public function totalMahasiswa()
    {
        return $this->presensiDetail()->count();
    }

    /**
     * Mendapatkan persentase kehadiran
     */
    public function persentaseKehadiran()
    {
        $total = $this->totalMahasiswa();
        if ($total === 0) {
            return 0;
        }

        return ($this->mahasiswaHadir() / $total) * 100;
    }
}
