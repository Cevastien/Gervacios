<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('customer_email', 255)->nullable()->after('customer_phone');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('table_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('customer_email');
            $table->unsignedBigInteger('table_id')->nullable(false)->change();
        });
    }
};
