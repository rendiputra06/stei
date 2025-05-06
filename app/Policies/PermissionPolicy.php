<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('permission_management_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permission_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('permission_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permission_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Permission $permission): bool
    {
        // Prevent deletion of critical permissions
        $criticalPermissions = [
            'user_management_access',
            'role_management_access',
            'permission_management_access',
        ];

        if (in_array($permission->name, $criticalPermissions)) {
            return false;
        }

        return $user->hasPermissionTo('permission_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permission_edit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        // Prevent deletion of critical permissions
        $criticalPermissions = [
            'user_management_access',
            'role_management_access',
            'permission_management_access',
        ];

        if (in_array($permission->name, $criticalPermissions)) {
            return false;
        }

        return $user->hasPermissionTo('permission_delete');
    }
}
