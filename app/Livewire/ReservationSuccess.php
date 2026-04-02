<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Component;

class ReservationSuccess extends Component
{
    public ?string $bookingRef = null;

    public ?int $pendingStartedAt = null;

    public bool $timedOut = false;

    public function mount(?string $bookingRef = null): void
    {
        $this->bookingRef = $bookingRef ?? request()->query('ref');

        if ($this->bookingRef) {
            $booking = Booking::query()->where('booking_ref', $this->bookingRef)->first();
            if ($booking && $this->isPaymongoPending($booking)) {
                $this->pendingStartedAt = time();
            }
        }
    }

    public function isPaymongoPending(?Booking $booking): bool
    {
        if (! $booking) {
            return false;
        }

        return $booking->payment_method === 'paymongo'
            && $booking->payment_status === 'pending';
    }

    public function checkPaymentStatus(): void
    {
        if ($this->timedOut || ! $this->bookingRef) {
            return;
        }

        $booking = Booking::query()->where('booking_ref', $this->bookingRef)->first();

        if (! $booking) {
            return;
        }

        if ($booking->payment_status === 'paid') {
            return;
        }

        if ($this->isPaymongoPending($booking)) {
            if ($this->pendingStartedAt !== null && (time() - $this->pendingStartedAt) >= 120) {
                $this->timedOut = true;
            }
        }
    }

    public function render()
    {
        $booking = $this->bookingRef
            ? Booking::query()->where('booking_ref', $this->bookingRef)->first()
            : null;

        $shouldPoll = $booking
            && $this->isPaymongoPending($booking)
            && ! $this->timedOut;

        return view('livewire.reservation-success', [
            'booking' => $booking,
            'bookingRef' => $this->bookingRef,
            'shouldPoll' => $shouldPoll,
        ]);
    }
}
