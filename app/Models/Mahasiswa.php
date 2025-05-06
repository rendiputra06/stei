<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'no_telepon',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'program_studi_id',
        'tahun_masuk',
        'status',  // aktif, cuti, lulus, drop out
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_lahir' => 'date',
        'tahun_masuk' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mahasiswa) {
            // Buat user jika belum ada user_id
            if (empty($mahasiswa->user_id)) {
                $user = User::create([
                    'name' => $mahasiswa->nama,
                    'email' => $mahasiswa->email,
                    'password' => bcrypt('password'), // Default password
                ]);

                // Assign role mahasiswa
                $user->assignRole('mahasiswa');

                $mahasiswa->user_id = $user->id;
            }
        });

        static::updating(function ($mahasiswa) {
            // Update user terkait jika mahasiswa diupdate
            if ($mahasiswa->user_id && $mahasiswa->isDirty(['nama', 'email'])) {
                $user = User::find($mahasiswa->user_id);

                if ($user) {
                    $user->name = $mahasiswa->nama;
                    $user->email = $mahasiswa->email;
                    $user->save();
                }
            }
        });

        static::deleting(function ($mahasiswa) {
            // Hapus user terkait jika mahasiswa dihapus
            if ($mahasiswa->user) {
                $mahasiswa->user->delete();
            }
        });
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
