<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Unified automation (waitlist, reservations, SMS)
    |--------------------------------------------------------------------------
    | Thresholds can be overridden per-environment via settings table keys
    | automation_* (see App\Services\AutomationSettings).
    */

    'queue_hold_minutes' => (int) env('AUTOMATION_QUEUE_HOLD_MINUTES', 1),

    'no_show_minutes_after_booking' => (int) env('AUTOMATION_NO_SHOW_MINUTES', 30),

    'table_cleaning_minutes' => (int) env('TABLE_CLEANING_MINUTES', 10),

    'peak_hours_start' => env('PEAK_HOURS_START', '17:00'),

    'peak_hours_end' => env('PEAK_HOURS_END', '22:00'),

    /*
    | Learn busy hours from waitlist joins (queue_entries.joined_at) instead of a fixed window.
    | Same weekday is preferred; falls back to all weekdays if not enough samples.
    */
    'peak_hours_learn_from_queue' => filter_var(env('PEAK_HOURS_LEARN_FROM_QUEUE', true), FILTER_VALIDATE_BOOL),

    'peak_queue_lookback_days' => (int) env('PEAK_QUEUE_LOOKBACK_DAYS', 28),

    'peak_queue_min_samples' => (int) env('PEAK_QUEUE_MIN_SAMPLES', 30),

    /** Hour counts as "peak" if joins >= this fraction of the busiest hour in the window. */
    'peak_queue_busy_ratio_of_max' => (float) env('PEAK_QUEUE_BUSY_RATIO_OF_MAX', 0.35),

    /* Shorter = admin chart / learned window updates sooner (still one DB scan per TTL per weekday). */
    'peak_queue_cache_ttl_seconds' => (int) env('PEAK_QUEUE_CACHE_TTL_SECONDS', 90),

    'table_occupied_alert_minutes' => (int) env('AUTOMATION_TABLE_OCCUPIED_MINUTES', 90),

    'reminder_hours_before_1' => (int) env('AUTOMATION_REMINDER_HOURS_1', 24),

    'reminder_hours_before_2' => (int) env('AUTOMATION_REMINDER_HOURS_2', 2),

    'late_checkin_minutes_after_slot' => (int) env('AUTOMATION_LATE_CHECKIN_MINUTES', 15),

    'wait_increase_alert_minutes' => (int) env('AUTOMATION_WAIT_INCREASE_MINUTES', 10),

    'device_detection_enabled' => env('DEVICE_DETECTION_ENABLED', true),

    'master_automation_enabled' => env('MASTER_AUTOMATION_ENABLED', true),

];
