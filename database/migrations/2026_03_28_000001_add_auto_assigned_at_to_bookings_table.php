<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bookings', 'auto_assigned_at')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->timestamp('auto_assigned_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'auto_assigned_at')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('auto_assigned_at');
            });
        }
    }
};
