<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studi';

    protected $fillable = [
        'kode',
        'nama',
        'jenjang',
        'is_active',
        'deskripsi',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function ruangan(): HasMany
    {
        return $this->hasMany(Ruangan::class);
    }
}
