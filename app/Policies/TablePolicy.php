<?php

namespace App\Policies;

use App\Models\Table;
use App\Models\User;

/**
 * TablePolicy
 *
 * Authorization policy for table management.
 * Admin: full control. Staff: floor ops (status changes, release, mark ready after cleaning).
 */
class TablePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'superadmin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Table $table): bool
    {
        return in_array($user->role, ['admin', 'staff', 'superadmin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Table $table): bool
    {
        return in_array($user->role, ['admin', 'staff', 'superadmin'], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Table $table): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }
}
