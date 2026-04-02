<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('paymongo_link_id')->nullable()->after('status');
            $table->string('paymongo_payment_id')->nullable()->after('paymongo_link_id');
            $table->string('payment_status')->default('unpaid')->after('paymongo_payment_id');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->unsignedInteger('deposit_amount')->default(0)->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'paymongo_link_id',
                'paymongo_payment_id',
                'payment_status',
                'paid_at',
                'deposit_amount',
            ]);
        });
    }
};
