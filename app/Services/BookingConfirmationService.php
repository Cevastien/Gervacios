<?php

namespace App\Services;

use App\Jobs\SendSmsJob;
use App\Models\Booking;
use App\Models\Table;

class BookingConfirmationService
{
    public function __construct(
        private BookingTableService $bookingTableService
    ) {}

    public function confirm(Booking $booking): ?Table
    {
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'active',
            'paid_at' => now(),
        ]);

        $table = $this->bookingTableService->assignTable($booking->fresh());

        SendSmsJob::dispatch(
            $booking->customer_phone,
            'booking_confirmed',
            [
                'name' => $booking->customer_name,
                'venue' => config('app.name', 'Café Gervacios'),
                'booking_ref' => $booking->booking_ref,
                'customer_email' => $booking->customer_email,
                'booked_at' => $booking->booked_at?->toIso8601String(),
                'party_size' => $booking->party_size,
            ]
        );

        return $table;
    }

    public function reject(Booking $booking, string $reason = ''): void
    {
        $booking->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);

        $phone = trim((string) ($booking->customer_phone ?? ''));
        if ($phone === '') {
            return;
        }

        $siteUrl = rtrim((string) config('app.url'), '/');
        if ($siteUrl === '') {
            $siteUrl = url('/');
        }

        $payload = [
            'site_url' => $siteUrl,
        ];
        if ($reason !== '') {
            $payload['reason'] = $reason;
        }

        SendSmsJob::dispatch($phone, 'payment_rejected', $payload);
    }
}
