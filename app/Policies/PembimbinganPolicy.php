<?php

namespace App\Policies;

use App\Models\Pembimbingan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PembimbinganPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'dosen']);
    }

    public function view(User $user, Pembimbingan $pembimbingan): bool
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            return true;
        }

        // Dosen hanya bisa melihat pembimbingan mereka sendiri
        if ($user->hasRole('dosen') && $user->dosen) {
            return $pembimbingan->dosen_id === $user->dosen->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin', 'dosen']);
    }

    public function update(User $user, Pembimbingan $pembimbingan): bool
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            return true;
        }

        // Dosen hanya bisa update pembimbingan mereka sendiri
        if ($user->hasRole('dosen') && $user->dosen) {
            return $pembimbingan->dosen_id === $user->dosen->id;
        }

        return false;
    }

    public function delete(User $user, Pembimbingan $pembimbingan): bool
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            return true;
        }

        // Dosen hanya bisa menghapus pembimbingan mereka sendiri
        if ($user->hasRole('dosen') && $user->dosen) {
            return $pembimbingan->dosen_id === $user->dosen->id;
        }

        return false;
    }
}
