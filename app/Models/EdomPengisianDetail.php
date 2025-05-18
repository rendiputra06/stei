<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EdomPengisianDetail extends Model
{
    use HasFactory;

    protected $table = 'edom_pengisian_detail';

    protected $fillable = [
        'pengisian_id',
        'pertanyaan_id',
        'nilai',
        'jawaban_text',
    ];

    /**
     * Relasi ke pengisian EDOM
     */
    public function pengisian(): BelongsTo
    {
        return $this->belongsTo(EdomPengisian::class, 'pengisian_id');
    }

    /**
     * Relasi ke pertanyaan
     */
    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(EdomPertanyaan::class, 'pertanyaan_id');
    }
}
