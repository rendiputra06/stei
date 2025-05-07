<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KRS extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'tahun_akademik_id',
        'status',
        'total_sks',
        'semester',
        'keterangan',
        'catatan_dosen',
        'tanggal_submit',
        'tanggal_approval',
        'approved_by',
    ];

    protected $casts = [
        'tanggal_submit' => 'datetime',
        'tanggal_approval' => 'datetime',
        'total_sks' => 'integer',
        'semester' => 'integer',
    ];

    /**
     * Mendapatkan mahasiswa yang terkait dengan KRS
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Mendapatkan tahun akademik yang terkait dengan KRS
     */
    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    /**
     * Mendapatkan user yang menyetujui KRS
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Mendapatkan detail KRS
     */
    public function krsDetail(): HasMany
    {
        return $this->hasMany(KRSDetail::class, 'krs_id');
    }

    /**
     * Mendapatkan daftar status yang tersedia
     */
    public static function getStatusList(): array
    {
        return [
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];
    }

    /**
     * Cek apakah KRS dapat diedit
     */
    public function isDraftStatus(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Cek apakah KRS sudah diajukan
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Cek apakah KRS sudah disetujui
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Cek apakah KRS ditolak
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Hitung total SKS dari detail KRS
     */
    public function hitungTotalSKS(): int
    {
        return $this->krsDetail()
            ->where('status', '=', 'aktif')
            ->sum('sks');
    }

    /**
     * Update total SKS
     */
    public function updateTotalSKS(): void
    {
        $this->total_sks = $this->hitungTotalSKS();
        $this->save();
    }
}
