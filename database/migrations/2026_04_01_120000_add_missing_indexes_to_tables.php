<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('payment_status');
            $table->index('paymongo_link_id');
            $table->index('checked_in_at');
        });

        Schema::table('queue_entries', function (Blueprint $table) {
            $table->index('hold_expires_at');
            $table->index('notified_at');
            $table->index('seated_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['paymongo_link_id']);
            $table->dropIndex(['checked_in_at']);
        });

        Schema::table('queue_entries', function (Blueprint $table) {
            $table->dropIndex(['hold_expires_at']);
            $table->dropIndex(['notified_at']);
            $table->dropIndex(['seated_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};
