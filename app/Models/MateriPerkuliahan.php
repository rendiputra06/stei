<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MateriPerkuliahan extends Model
{
    use HasFactory;

    protected $table = 'materi_perkuliahan';

    protected $fillable = [
        'jadwal_id',
        'judul',
        'deskripsi',
        'file_path',
        'pertemuan_ke',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'pertemuan_ke' => 'integer',
    ];

    /**
     * Mendapatkan jadwal yang terkait dengan materi perkuliahan
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Cek apakah materi memiliki file
     */
    public function hasFile(): bool
    {
        return !empty($this->file_path);
    }

    /**
     * Mendapatkan nama file
     */
    public function getFileName(): string
    {
        if (!$this->hasFile()) {
            return '';
        }

        $parts = explode('/', $this->file_path);
        return end($parts);
    }

    /**
     * Mendapatkan URL file
     */
    public function getFileUrl(): string
    {
        if (!$this->hasFile()) {
            return '';
        }

        return asset('storage/' . $this->file_path);
    }
}
