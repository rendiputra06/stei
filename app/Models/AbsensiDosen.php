<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiDosen extends Model
{
    use HasFactory;

    protected $table = 'absensi_dosen';

    protected $fillable = [
        'dosen_id',
        'jadwal_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime',
        'jam_keluar' => 'datetime',
    ];

    /**
     * Mendapatkan dosen yang terkait dengan absensi
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Mendapatkan jadwal yang terkait dengan absensi
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
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
            'alpha' => 'Tanpa Keterangan',
        ];
    }

    /**
     * Menghasilkan token QR code
     */
    public static function generateQRToken(Jadwal $jadwal): string
    {
        $timestamp = now()->timestamp;
        $uniqueData = "{$jadwal->id}|{$jadwal->dosen_id}|{$timestamp}";
        return base64_encode($uniqueData);
    }

    /**
     * Mendekode token QR code
     */
    public static function decodeQRToken(string $token): array
    {
        $decodedData = base64_decode($token);
        list($jadwalId, $dosenId, $timestamp) = explode('|', $decodedData);

        return [
            'jadwal_id' => $jadwalId,
            'dosen_id' => $dosenId,
            'timestamp' => $timestamp,
        ];
    }

    /**
     * Memvalidasi token QR code
     */
    public static function validateQRToken(string $token): bool
    {
        try {
            $decoded = self::decodeQRToken($token);
            $timestamp = $decoded['timestamp'];

            // Token valid selama 5 menit
            $validUntil = $timestamp + (5 * 60);

            return time() <= $validUntil;
        } catch (\Exception $e) {
            return false;
        }
    }
}
