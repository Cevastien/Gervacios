<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\StaffNotification;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

class BookingTableService
{
    public function assignTable(Booking $booking): ?Table
    {
        return DB::transaction(function () use ($booking) {
            $booking->refresh();

            if ($booking->table_id !== null) {
                return $booking->table;
            }

            $partySize = (int) $booking->party_size;
            if ($partySize < 1) {
                return null;
            }

            $query = Table::query()->available($partySize);

            if ($this->bookingNeedsAccessibleTable($booking)) {
                $query->accessible();
            }

            /** @var \Illuminate\Database\Eloquent\Collection<int, Table> $tables */
            $tables = $query->orderBy('capacity')->orderBy('id')->get();

            if ($tables->isEmpty()) {
                $this->notifyNoTableAvailable($booking);

                return null;
            }

            $best = $tables->first();

            $table = Table::query()->whereKey($best->id)->lockForUpdate()->firstOrFail();

            if (! $table->reserveForBooking($booking)) {
                $this->notifyNoTableAvailable($booking);

                return null;
            }

            $booking->table_id = $table->id;
            $booking->auto_assigned_at = now();
            $booking->save();

            $booking->refresh();
            $tableLabel = $table->fresh()->label;
            $tz = (string) config('app.timezone');
            $bookedAtFormatted = $booking->booked_at !== null
                ? $booking->booked_at->timezone($tz)->format('M j, Y g:i A')
                : '—';
            $bookedAtPayload = $booking->booked_at !== null
                ? $booking->booked_at->toIso8601String()
                : null;

            StaffNotification::create([
                'type' => 'table_assigned',
                'title' => 'Table '.$tableLabel.' needs preparation',
                'message' => 'Guest '.$booking->customer_name.', party of '.$partySize.', arriving at '.$bookedAtFormatted.'. Please prepare '.$tableLabel.'.',
                'payload' => [
                    'booking_ref' => $booking->booking_ref,
                    'table_label' => $tableLabel,
                    'party_size' => $partySize,
                    'booked_at' => $bookedAtPayload,
                ],
                'is_read' => false,
            ]);

            return $table->fresh();
        });
    }

    private function bookingNeedsAccessibleTable(Booking $booking): bool
    {
        $text = (string) ($booking->special_requests ?? '');

        return stripos($text, 'wheelchair') !== false
            || stripos($text, 'accessible') !== false;
    }

    private function notifyNoTableAvailable(Booking $booking): void
    {
        if (
            StaffNotification::query()
                ->where('type', 'no_table_available')
                ->where('payload->booking_ref', $booking->booking_ref)
                ->exists()
        ) {
            return;
        }

        $partySize = (int) $booking->party_size;

        StaffNotification::create([
            'type' => 'no_table_available',
            'title' => 'No table available for confirmed booking',
            'message' => sprintf(
                'Booking %s for %s (%d guests) has no table assigned. Please assign manually.',
                $booking->booking_ref,
                $booking->customer_name,
                $partySize
            ),
            'payload' => [
                'booking_ref' => $booking->booking_ref,
                'customer_name' => $booking->customer_name,
                'party_size' => $partySize,
                'booked_at' => $booking->booked_at?->toIso8601String(),
            ],
            'is_read' => false,
        ]);
    }
}
