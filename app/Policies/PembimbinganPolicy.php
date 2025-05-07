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
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function view(User $user, Pembimbingan $pembimbingan): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function update(User $user, Pembimbingan $pembimbingan): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function delete(User $user, Pembimbingan $pembimbingan): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }
}
