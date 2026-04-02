<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\QueueEntry;
use App\Models\Table;
use Livewire\Component;

/**
 * SummaryBar
 *
 * Displays key metrics at the top of the admin dashboard.
 * Updates every 5 seconds via wire:poll on the view.
 * Always shows priority waiting count when > 0.
 */
class SummaryBar extends Component
{
    public function render()
    {
        $today = today();

        $bookingsTodayBase = fn () => Booking::query()
            ->whereDate('booked_at', $today)
            ->whereIn('status', ['active', 'pending']);

        $bookingsBySource = [];
        $queueBySource = [];
        foreach (['website', 'staff'] as $src) {
            $bookingsBySource[$src] = $bookingsTodayBase()
                ->where('source', $src)
                ->count();
            $queueBySource[$src] = QueueEntry::whereDate('created_at', $today)->where('source', $src)->count();
        }

        $bookingsTodayTotal = $bookingsTodayBase()->count();
        // All walk-ins today (includes legacy kiosk/mobile sources not shown in the channel grid)
        $walkInsTodayTotal = QueueEntry::whereDate('created_at', $today)->count();

        $bookingDaily = Booking::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->whereIn('status', ['active', 'pending'])
            ->groupByRaw('DATE(created_at)')
            ->get()
            ->keyBy('date');

        $queueDaily = QueueEntry::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupByRaw('DATE(created_at)')
            ->get()
            ->keyBy('date');

        $bookingsLast7 = [];
        $queueLast7 = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = $today->copy()->subDays($i);
            $dateKey = $d->format('Y-m-d');
            $bookingsLast7[] = [
                'label' => $d->format('M j'),
                'count' => (int) ($bookingDaily->get($dateKey)?->total ?? 0),
            ];
            $queueLast7[] = [
                'label' => $d->format('M j'),
                'count' => (int) ($queueDaily->get($dateKey)?->total ?? 0),
            ];
        }

        $maxBookings7 = max(array_merge([1], array_column($bookingsLast7, 'count')));
        $maxQueue7 = max(array_merge([1], array_column($queueLast7, 'count')));

        return view('livewire.admin.summary-bar', [
            'tablesFree' => Table::where('status', 'available')->count(),
            'tablesOccupied' => Table::where('status', 'occupied')->count(),
            'partiesWaiting' => QueueEntry::waiting()->count(),
            'priorityWaiting' => QueueEntry::waiting()->where('priority_score', 100)->count(),
            'bookingsBySource' => $bookingsBySource,
            'queueBySource' => $queueBySource,
            'bookingsTodayTotal' => $bookingsTodayTotal,
            'walkInsTodayTotal' => $walkInsTodayTotal,
            'bookingsLast7' => $bookingsLast7,
            'queueLast7' => $queueLast7,
            'maxBookings7' => $maxBookings7,
            'maxQueue7' => $maxQueue7,
            'lastUpdated' => now()->format('g:i:s A'),
        ]);
    }
}
