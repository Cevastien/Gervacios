<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\QueueEntry;
use App\Models\Seat;
use App\Models\Table;
use Illuminate\Support\Collection;

class SeatingLayoutController extends Controller
{
    /**
     * Guest name + party size for floor map badges (queue hold, booking, or walk-in).
     *
     * @param  Collection<int, Table>  $tables
     * @return Collection<int, array{guest: string, party: string, arrival_at: ?string}>
     */
    public static function floorTableGuestInfoForTables(Collection $tables): Collection
    {
        if ($tables->isEmpty()) {
            return collect();
        }

        $reservedEntries = QueueEntry::query()
            ->where('status', 'notified')
            ->whereNotNull('reserved_table_id')
            ->get()
            ->keyBy('reserved_table_id');

        $bookingsByTable = Booking::query()
            ->whereNotNull('table_id')
            ->whereIn('status', ['active', 'pending'])
            ->whereNull('no_show_at')
            ->orderByDesc('id')
            ->get()
            ->unique('table_id')
            ->keyBy('table_id');

        $tz = (string) config('app.timezone');

        $bookingIds = $tables->pluck('booking_id')->filter();
        $bookings = Booking::whereIn('id', $bookingIds)
            ->get()->keyBy('id');

        return $tables->mapWithKeys(function (Table $t) use ($reservedEntries, $bookingsByTable, $bookings, $tz) {
            $guest = '—';
            $party = (string) max(1, (int) $t->capacity);
            $arrival_at = null;

            if ($t->status === 'reserved') {
                $e = $reservedEntries->get($t->id);
                if ($e) {
                    $guest = trim((string) $e->customer_name) !== '' ? (string) $e->customer_name : '—';
                    $party = (string) max(1, (int) $e->party_size);
                } elseif ($t->booking_id !== null) {
                    $booking = $bookings->get($t->booking_id);
                    if ($booking) {
                        $guest = trim((string) $booking->customer_name) !== '' ? (string) $booking->customer_name : '—';
                        $party = (string) max(1, (int) $booking->party_size);
                        $arrival_at = $booking->booked_at !== null
                            ? $booking->booked_at->timezone($tz)->format('M d, g:i A')
                            : null;
                    } else {
                        $guest = 'Walk-in';
                    }
                } else {
                    $guest = 'Walk-in';
                }
            } elseif ($t->status === 'occupied') {
                $b = $bookingsByTable->get($t->id);
                if ($b) {
                    $guest = trim((string) $b->customer_name) !== '' ? (string) $b->customer_name : '—';
                    $party = (string) max(1, (int) $b->party_size);
                } else {
                    $guest = 'Walk-in';
                    $party = (string) max(1, (int) ($t->occupied_party ?? $t->capacity));
                }
            }

            return [
                $t->id => [
                    'guest' => $guest,
                    'party' => $party,
                    'arrival_at' => $arrival_at,
                ],
            ];
        });
    }

    /**
     * @return array{tableGroups: \Illuminate\Support\Collection, allSeats: \Illuminate\Support\Collection, floorTableGuestInfo: Collection<int, array{guest: string, party: string, arrival_at: ?string}>}
     */
    public static function layoutData(): array
    {
        $allSeats = Seat::query()
            ->with('table')
            ->orderBy('table_id')
            ->orderBy('seat_index')
            ->get();

        $tableGroups = Table::query()
            ->with(['seats' => fn ($q) => $q->orderBy('seat_index')])
            ->orderBy('id')
            ->get()
            ->map(function (Table $table) {
                if ($table->seats->isEmpty()) {
                    return null;
                }

                $anchorX = round((float) $table->seats->avg('pos_x'), 2);
                $anchorY = round((float) $table->seats->avg('pos_y'), 2);

                $seatList = $table->seats;
                $minX = (float) $seatList->min('pos_x');
                $maxX = (float) $seatList->max('pos_x');
                $minY = (float) $seatList->min('pos_y');
                $maxY = (float) $seatList->max('pos_y');
                $pad = 3.5;
                $left = max(0.0, $minX - $pad);
                $top = max(0.0, $minY - $pad);
                $w = max(12.0, min(100.0, $maxX - $minX + 2 * $pad));
                $h = max(10.0, min(100.0, $maxY - $minY + 2 * $pad));

                return (object) [
                    'table' => $table,
                    'anchor_x' => $anchorX,
                    'anchor_y' => $anchorY,
                    'seats' => $table->seats,
                    'bounds' => (object) [
                        'left' => round($left, 4),
                        'top' => round($top, 4),
                        'w' => round($w, 4),
                        'h' => round($h, 4),
                    ],
                ];
            })
            ->filter()
            ->values();

        $tablesForBadges = $tableGroups->pluck('table')->unique('id')->values();

        return [
            'tableGroups' => $tableGroups,
            'allSeats' => $allSeats,
            'floorTableGuestInfo' => self::floorTableGuestInfoForTables($tablesForBadges),
        ];
    }

    public function __invoke()
    {
        return view('admin.seating-layout', self::layoutData());
    }
}
