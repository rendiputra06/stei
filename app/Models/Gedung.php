<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedung';

    protected $fillable = [
        'kode',
        'nama',
        'lokasi',
        'is_active',
        'deskripsi',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($gedung) {
            if (empty($gedung->kode)) {
                $latestGedung = static::latest()->first();
                $prefix = 'GD-';

                if (!$latestGedung) {
                    $gedung->kode = $prefix . 'A';
                } else {
                    $latestCode = substr($latestGedung->kode, strlen($prefix));
                    $nextLetter = ++$latestCode;

                    // Jika sudah melewati Z, mulai dari AA
                    if (strlen($nextLetter) > 1 && $nextLetter[0] > 'Z') {
                        $nextLetter = 'AA';
                    }

                    $gedung->kode = $prefix . $nextLetter;
                }
            }
        });
    }

    public function ruangan(): HasMany
    {
        return $this->hasMany(Ruangan::class);
    }
}
