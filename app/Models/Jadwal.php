<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'tahun_akademik_id',
        'mata_kuliah_id',
        'dosen_id',
        'ruangan_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kelas',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
    ];

    /**
     * Mendapatkan tahun akademik yang terkait dengan jadwal
     */
    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    /**
     * Mendapatkan mata kuliah yang terkait dengan jadwal
     */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    /**
     * Mendapatkan dosen yang terkait dengan jadwal
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Mendapatkan ruangan yang terkait dengan jadwal
     */
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    /**
     * Cek apakah jadwal bentrok dengan jadwal lain untuk dosen yang sama
     */
    public function isBentrokDosen($id = null)
    {
        $query = static::where('dosen_id', $this->dosen_id)
            ->where('hari', $this->hari)
            ->where('tahun_akademik_id', $this->tahun_akademik_id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhere(function ($query) {
                        $query->where('jam_mulai', '<=', $this->jam_mulai)
                            ->where('jam_selesai', '>=', $this->jam_selesai);
                    });
            });

        if ($id) {
            $query->where('id', '!=', $id);
        }

        return $query->exists();
    }

    /**
     * Cek apakah jadwal bentrok dengan jadwal lain untuk ruangan yang sama
     */
    public function isBentrokRuangan($id = null)
    {
        $query = static::where('ruangan_id', $this->ruangan_id)
            ->where('hari', $this->hari)
            ->where('tahun_akademik_id', $this->tahun_akademik_id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhere(function ($query) {
                        $query->where('jam_mulai', '<=', $this->jam_mulai)
                            ->where('jam_selesai', '>=', $this->jam_selesai);
                    });
            });

        if ($id) {
            $query->where('id', '!=', $id);
        }

        return $query->exists();
    }

    /**
     * Mendapatkan presensi untuk jadwal ini
     */
    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    /**
     * Mendapatkan materi perkuliahan untuk jadwal ini
     */
    public function materiPerkuliahan(): HasMany
    {
        return $this->hasMany(MateriPerkuliahan::class);
    }

    /**
     * Mendapatkan jumlah pertemuan presensi
     */
    public function jumlahPertemuan(): int
    {
        return $this->presensi()->count();
    }

    /**
     * Mendapatkan jumlah mahasiswa di kelas ini
     */
    public function jumlahMahasiswa(): int
    {
        return KRSDetail::where('jadwal_id', $this->id)
            ->where('status', 'aktif')
            ->count();
    }
}
