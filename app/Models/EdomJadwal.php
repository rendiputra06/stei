<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EdomJadwal extends Model
{
    use HasFactory;

    protected $table = 'edom_jadwal';

    protected $fillable = [
        'tahun_akademik_id',
        'template_id',
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_aktif',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif' => 'boolean',
    ];

    /**
     * Relasi ke tahun akademik
     */
    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    /**
     * Relasi ke template EDOM
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(EdomTemplate::class, 'template_id');
    }

    /**
     * Relasi ke pengisian EDOM
     */
    public function pengisian(): HasMany
    {
        return $this->hasMany(EdomPengisian::class, 'jadwal_id');
    }

    /**
     * Cek apakah jadwal sedang aktif (periode hari ini)
     */
    public function isAktifSaatIni(): bool
    {
        $today = now()->startOfDay();
        return $this->is_aktif &&
            $today->greaterThanOrEqualTo($this->tanggal_mulai) &&
            $today->lessThanOrEqualTo($this->tanggal_selesai);
    }
}
