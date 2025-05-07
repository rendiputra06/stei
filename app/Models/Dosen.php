<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'no_telepon',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'program_studi_id',
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_lahir' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dosen) {
            // Buat user jika belum ada user_id
            if (empty($dosen->user_id)) {
                $user = User::create([
                    'name' => $dosen->nama,
                    'email' => $dosen->email,
                    'password' => bcrypt('password'), // Default password
                ]);

                // Assign role dosen
                $user->assignRole('dosen');

                $dosen->user_id = $user->id;
            }
        });

        static::updating(function ($dosen) {
            // Update user terkait jika dosen diupdate
            if ($dosen->user_id && $dosen->isDirty(['nama', 'email'])) {
                $user = User::find($dosen->user_id);

                if ($user) {
                    $user->name = $dosen->nama;
                    $user->email = $dosen->email;
                    $user->save();
                }
            }
        });

        static::deleting(function ($dosen) {
            // Hapus user terkait jika dosen dihapus
            if ($dosen->user) {
                $dosen->user->delete();
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

    public function pembimbingan(): HasMany
    {
        return $this->hasMany(Pembimbingan::class);
    }

    public function mahasiswaBimbingan()
    {
        return $this->belongsToMany(Mahasiswa::class, 'pembimbingan')
            ->withTimestamps();
    }
}
