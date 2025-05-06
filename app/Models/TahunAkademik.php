<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahunAkademik extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tahun_akademik';

    protected $fillable = [
        'kode',
        'tahun',
        'semester',
        'nama',
        'aktif',
        'tanggal_mulai',
        'tanggal_selesai',
        'krs_mulai',
        'krs_selesai',
        'nilai_mulai',
        'nilai_selesai',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'krs_mulai' => 'datetime',
        'krs_selesai' => 'datetime',
        'nilai_mulai' => 'datetime',
        'nilai_selesai' => 'datetime',
    ];

    /**
     * Mendapatkan tahun akademik yang aktif
     */
    public static function getAktif()
    {
        return self::where('aktif', true)->first();
    }
}
