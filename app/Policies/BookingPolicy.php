<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

/**
 * BookingPolicy
 *
 * Authorization policy for reservation / booking management.
 */
class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'superadmin']);
    }

    public function view(User $user, Booking $booking): bool
    {
        return in_array($user->role, ['admin', 'staff', 'superadmin']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Booking $booking): bool
    {
        return in_array($user->role, ['admin', 'staff', 'superadmin']);
    }

    public function delete(User $user, Booking $booking): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }
}
