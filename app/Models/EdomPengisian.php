<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EdomPengisian extends Model
{
    use HasFactory;

    protected $table = 'edom_pengisian';

    protected $fillable = [
        'jadwal_id',
        'mahasiswa_id',
        'jadwal_kuliah_id',
        'dosen_id',
        'mata_kuliah_id',
        'tanggal_pengisian',
        'status',
    ];

    protected $casts = [
        'tanggal_pengisian' => 'date',
    ];

    /**
     * Relasi ke jadwal EDOM
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(EdomJadwal::class, 'jadwal_id');
    }

    /**
     * Relasi ke mahasiswa
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke jadwal kuliah
     */
    public function jadwalKuliah(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_kuliah_id');
    }

    /**
     * Relasi ke dosen
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Relasi ke mata kuliah
     */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Relasi ke detail pengisian
     */
    public function detail(): HasMany
    {
        return $this->hasMany(EdomPengisianDetail::class, 'pengisian_id');
    }

    /**
     * Alias untuk relasi detail pengisian (untuk kompatibilitas)
     */
    public function pengisianDetail(): HasMany
    {
        return $this->detail();
    }

    /**
     * Cek apakah pengisian sudah disubmit
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
}
