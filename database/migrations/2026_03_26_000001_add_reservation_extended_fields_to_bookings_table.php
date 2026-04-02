<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Additive fields for reservation form (custom questions, policy, marketing).
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('transaction_number')->nullable();
            $table->string('account_number')->nullable();
            $table->string('city_of_residence')->nullable();
            $table->boolean('policy_acknowledged')->default(false);
            $table->text('special_requests')->nullable();
            $table->boolean('marketing_opt_in')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_number',
                'account_number',
                'city_of_residence',
                'policy_acknowledged',
                'special_requests',
                'marketing_opt_in',
            ]);
        });
    }
};
