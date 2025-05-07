<?php

namespace App\Policies;

use App\Models\KRS;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Carbon\Carbon;

class KRSPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Hanya mahasiswa yang bisa melihat daftar KRS
        return $user->hasRole('mahasiswa');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KRS $krs): bool
    {
        // Mahasiswa hanya bisa melihat KRS miliknya sendiri
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return false;
        }

        return $krs->mahasiswa_id === $mahasiswa->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Mahasiswa hanya bisa membuat KRS jika:
        // 1. Dia memiliki role mahasiswa
        // 2. Ada tahun akademik aktif
        // 3. Saat ini masih dalam periode pengisian KRS

        if (!$user->hasRole('mahasiswa')) {
            return false;
        }

        $tahunAkademik = TahunAkademik::getAktif();

        if (!$tahunAkademik) {
            return false;
        }

        $now = Carbon::now();

        return $now->between($tahunAkademik->krs_mulai, $tahunAkademik->krs_selesai);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KRS $krs): bool
    {
        // Mahasiswa hanya bisa mengupdate KRS jika:
        // 1. KRS miliknya sendiri
        // 2. KRS masih dalam status draft
        // 3. Saat ini masih dalam periode pengisian KRS

        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa || $krs->mahasiswa_id !== $mahasiswa->id) {
            return false;
        }

        if (!$krs->isDraftStatus()) {
            return false;
        }

        $tahunAkademik = $krs->tahunAkademik;
        $now = Carbon::now();

        return $now->between($tahunAkademik->krs_mulai, $tahunAkademik->krs_selesai);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KRS $krs): bool
    {
        // Mahasiswa hanya bisa menghapus KRS jika:
        // 1. KRS miliknya sendiri
        // 2. KRS masih dalam status draft

        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa || $krs->mahasiswa_id !== $mahasiswa->id) {
            return false;
        }

        return $krs->isDraftStatus();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KRS $krs): bool
    {
        return false; // Tidak diijinkan untuk merestore KRS yang dihapus
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KRS $krs): bool
    {
        return false; // Tidak diijinkan untuk menghapus permanen KRS
    }

    /**
     * Determine whether the user can submit the KRS.
     */
    public function submit(User $user, KRS $krs): bool
    {
        // Mahasiswa hanya bisa submit KRS jika:
        // 1. KRS miliknya sendiri
        // 2. KRS masih dalam status draft
        // 3. Saat ini masih dalam periode pengisian KRS

        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa || $krs->mahasiswa_id !== $mahasiswa->id) {
            return false;
        }

        if (!$krs->isDraftStatus()) {
            return false;
        }

        $tahunAkademik = $krs->tahunAkademik;
        $now = Carbon::now();

        return $now->between($tahunAkademik->krs_mulai, $tahunAkademik->krs_selesai);
    }
}
