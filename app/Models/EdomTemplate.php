<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EdomTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_template',
        'deskripsi',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    /**
     * Relasi ke pertanyaan dalam template
     */
    public function pertanyaan(): HasMany
    {
        return $this->hasMany(EdomPertanyaan::class, 'template_id');
    }

    /**
     * Relasi ke jadwal evaluasi yang menggunakan template ini
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(EdomJadwal::class, 'template_id');
    }
}
