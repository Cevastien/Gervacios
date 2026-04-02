<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * One-time: turn off device_detection_enabled so DB-backed admin setting matches
     * device detection redirect disabled (guests use main site).
     */
    public function up(): void
    {
        Setting::set('device_detection_enabled', '0');
    }

    public function down(): void
    {
        Setting::set('device_detection_enabled', '1');
    }
};
