<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'kode',
        'nama',
        'gedung_id',
        'program_studi_id',
        'lantai',
        'kapasitas',
        'is_active',
        'jenis',  // 'kelas', 'laboratorium', 'kantor', dll
        'deskripsi',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'lantai' => 'integer',
        'kapasitas' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ruangan) {
            if (empty($ruangan->kode)) {
                $gedung = \App\Models\Gedung::find($ruangan->gedung_id);
                if ($gedung) {
                    $latestRuangan = static::where('gedung_id', $ruangan->gedung_id)
                        ->where('lantai', $ruangan->lantai)
                        ->latest('id')
                        ->first();

                    $gedungCode = substr($gedung->kode, 3); // Mengambil kode gedung tanpa prefix 'GD-'
                    $lantai = $ruangan->lantai;

                    if (!$latestRuangan) {
                        $number = '01';
                    } else {
                        $lastNumber = substr($latestRuangan->kode, -2);
                        $number = str_pad((int) $lastNumber + 1, 2, '0', STR_PAD_LEFT);
                    }

                    $ruangan->kode = $gedungCode . $lantai . $number;
                }
            }
        });
    }

    public function gedung(): BelongsTo
    {
        return $this->belongsTo(Gedung::class);
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }
}
