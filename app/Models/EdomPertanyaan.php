<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EdomPertanyaan extends Model
{
    use HasFactory;

    protected $table = 'edom_pertanyaan';

    protected $fillable = [
        'template_id',
        'pertanyaan',
        'jenis',
        'urutan',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    /**
     * Relasi ke template EDOM
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(EdomTemplate::class, 'template_id');
    }

    /**
     * Relasi ke detail pengisian
     */
    public function pengisianDetail(): HasMany
    {
        return $this->hasMany(EdomPengisianDetail::class, 'pertanyaan_id');
    }
}
