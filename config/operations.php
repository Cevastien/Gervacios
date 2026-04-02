<?php

return [

    // Prefer OCCUPANCY_*; KIOSK_* kept as fallback for older .env files
    'occupancy_duration_minutes' => (int) env('OCCUPANCY_DURATION_MINUTES', env('KIOSK_OCCUPANCY_DURATION_MINUTES', 90)),

    /**
     * Used when no tables exist in the floor plan yet — guest reservation max party size.
     * Once tables are configured, the largest single table capacity is used instead.
     */
    'reservation_max_party_fallback' => (int) env('RESERVATION_MAX_PARTY_FALLBACK', env('KIOSK_RESERVATION_MAX_PARTY_FALLBACK', 4)),

    /**
     * Reservation table-hold policy window (hours before the booked slot).
     *
     * - Paid active/pending bookings with a reserved table: keep the hold whether the slot is
     *   more than this many hours away or within this window (the active hold window).
     * - Automation does not release tables based on this window alone; it only frees tables
     *   when the booking is cancelled or payment failed.
     */
    'reservation_hold_window_hours' => (int) env('RESERVATION_HOLD_WINDOW_HOURS', env('KIOSK_RESERVATION_HOLD_WINDOW_HOURS', 5)),

];
