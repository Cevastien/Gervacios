<?php

namespace App\Livewire\Admin;

use App\Models\QueueEntry;
use App\Models\Table;
use Livewire\Component;

/**
 * Dashboard
 *
 * Main admin dashboard for front-of-house staff.
 * Displays live table grid and waitlist panel.
 * Uses wire:poll.3000ms for real-time updates.
 *
 * Includes summary bar with key metrics:
 * - Tables free, occupied
 * - Parties waiting
 * - Priority waiting count (highlighted)
 */
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'tables' => Table::with('bookings')->get(),
            'queue' => QueueEntry::waiting()->sorted()->get(),
            'priorityWaiting' => QueueEntry::waiting()->where('priority_score', 100)->count(),
            'tablesFree' => Table::where('status', 'available')->count(),
            'tablesOccupied' => Table::where('status', 'occupied')->count(),
        ]);
    }
}
