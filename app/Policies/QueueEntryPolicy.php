<?php

namespace App\Policies;

use App\Models\QueueEntry;
use App\Models\User;

/**
 * QueueEntryPolicy
 *
 * Authorization policy for queue entry management.
 * Both admin and staff can update (seat) queue entries.
 * Only admin can delete (cancel) queue entries.
 */
class QueueEntryPolicy
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
    public function view(User $user, QueueEntry $queueEntry): bool
    {
        return in_array($user->role, ['admin', 'staff', 'superadmin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Kiosk customers can create (handled separately)
    }

    /**
     * Determine whether the user can update the model.
     * Both admin and staff can seat customers.
     */
    public function update(User $user, QueueEntry $queueEntry): bool
    {
        return in_array($user->role, ['admin', 'staff', 'superadmin']);
    }

    /**
     * Determine whether the user can delete the model.
     * Only admin can cancel queue entries.
     */
    public function delete(User $user, QueueEntry $queueEntry): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }
}
