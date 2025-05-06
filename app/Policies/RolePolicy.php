<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('role_management_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('role_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('role_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        // Protect super_admin role from being edited by non-super admins
        if ($role->name === 'super_admin' && !$user->hasRole('super_admin')) {
            return false;
        }

        return $user->hasPermissionTo('role_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        // Prevent deletion of critical roles
        if (in_array($role->name, ['super_admin', 'admin', 'dosen', 'mahasiswa'])) {
            return false;
        }

        return $user->hasPermissionTo('role_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('role_edit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        // Prevent deletion of critical roles
        if (in_array($role->name, ['super_admin', 'admin', 'dosen', 'mahasiswa'])) {
            return false;
        }

        return $user->hasPermissionTo('role_delete');
    }
}
