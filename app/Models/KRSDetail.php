<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KRSDetail extends Model
{
    use HasFactory;

    protected $table = 'krs_detail';

    protected $fillable = [
        'krs_id',
        'jadwal_id',
        'mata_kuliah_id',
        'sks',
        'kelas',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'sks' => 'integer',
    ];

    /**
     * Mendapatkan KRS yang terkait dengan detail KRS
     */
    public function krs(): BelongsTo
    {
        return $this->belongsTo(KRS::class, 'krs_id');
    }

    /**
     * Mendapatkan jadwal yang terkait dengan detail KRS
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Mendapatkan mata kuliah yang terkait dengan detail KRS
     */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    /**
     * Cek apakah detail KRS aktif
     */
    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Cek apakah detail KRS dibatalkan
     */
    public function isBatal(): bool
    {
        return $this->status === 'batal';
    }

    /**
     * Mendapatkan daftar status yang tersedia
     */
    public static function getStatusList(): array
    {
        return [
            'aktif' => 'Aktif',
            'batal' => 'Batal'
        ];
    }

    /**
     * Mendapatkan nilai untuk detail KRS ini
     */
    public function nilai(): HasOne
    {
        return $this->hasOne(Nilai::class, 'krs_detail_id');
    }

    /**
     * Cek apakah sudah ada nilai
     */
    public function hasNilai(): bool
    {
        return $this->nilai()->exists();
    }

    /**
     * Mendapatkan nilai akhir
     */
    public function getNilaiAkhir()
    {
        return $this->hasNilai() ? $this->nilai->nilai_akhir : null;
    }

    /**
     * Mendapatkan grade
     */
    public function getGrade()
    {
        return $this->hasNilai() ? $this->nilai->grade : null;
    }
}
