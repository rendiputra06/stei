<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresensiDetail extends Model
{
    use HasFactory;

    protected $table = 'presensi_detail';

    protected $fillable = [
        'presensi_id',
        'mahasiswa_id',
        'status',
        'keterangan',
    ];

    /**
     * Mendapatkan presensi yang terkait dengan detail presensi
     */
    public function presensi(): BelongsTo
    {
        return $this->belongsTo(Presensi::class);
    }

    /**
     * Mendapatkan mahasiswa yang terkait dengan detail presensi
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Cek apakah mahasiswa hadir
     */
    public function isHadir(): bool
    {
        return $this->status === 'hadir';
    }

    /**
     * Cek apakah mahasiswa izin
     */
    public function isIzin(): bool
    {
        return $this->status === 'izin';
    }

    /**
     * Cek apakah mahasiswa sakit
     */
    public function isSakit(): bool
    {
        return $this->status === 'sakit';
    }

    /**
     * Cek apakah mahasiswa alpa (tanpa keterangan)
     */
    public function isAlpa(): bool
    {
        return $this->status === 'alpa';
    }

    /**
     * Mendapatkan daftar status yang tersedia
     */
    public static function getStatusList(): array
    {
        return [
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpa' => 'Tanpa Keterangan',
        ];
    }
}
