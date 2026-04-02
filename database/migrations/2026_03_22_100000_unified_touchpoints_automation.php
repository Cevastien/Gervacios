<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('source', 32)->default('website')->after('booking_ref');
            $table->string('device_type', 32)->nullable()->after('source');
            $table->timestamp('checked_in_at')->nullable()->after('completed_at');
            $table->timestamp('no_show_at')->nullable()->after('checked_in_at');
            $table->timestamp('reminder_24h_sent_at')->nullable()->after('no_show_at');
            $table->timestamp('reminder_2h_sent_at')->nullable()->after('reminder_24h_sent_at');
            $table->timestamp('late_checkin_sms_sent_at')->nullable()->after('reminder_2h_sent_at');
        });

        Schema::table('queue_entries', function (Blueprint $table) {
            $table->string('source', 32)->default('website')->after('id');
            $table->string('device_type', 32)->nullable()->after('source');
            $table->unsignedInteger('queue_display_number')->nullable()->after('device_type');
            $table->timestamp('otp_verified_at')->nullable()->after('seated_at');
            $table->timestamp('hold_expires_at')->nullable()->after('otp_verified_at');
            $table->timestamp('skipped_at')->nullable()->after('hold_expires_at');
            $table->timestamp('absent_at')->nullable()->after('skipped_at');
            $table->unsignedSmallInteger('last_estimated_wait')->nullable()->after('estimated_wait');
            $table->timestamp('wait_alert_sent_at')->nullable()->after('last_estimated_wait');
        });

        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_hash', 64);
            $table->string('template', 64);
            $table->json('context')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index('created_at');
        });

        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('automation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('task', 128);
            $table->string('message')->nullable();
            $table->json('payload')->nullable();
            $table->boolean('success')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['task', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_logs');
        Schema::dropIfExists('blocked_ips');
        Schema::dropIfExists('sms_logs');

        Schema::table('queue_entries', function (Blueprint $table) {
            $table->dropColumn([
                'source',
                'device_type',
                'queue_display_number',
                'otp_verified_at',
                'hold_expires_at',
                'skipped_at',
                'absent_at',
                'last_estimated_wait',
                'wait_alert_sent_at',
            ]);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'source',
                'device_type',
                'checked_in_at',
                'no_show_at',
                'reminder_24h_sent_at',
                'reminder_2h_sent_at',
                'late_checkin_sms_sent_at',
            ]);
        });
    }
};
