<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('phone_hash');
            $table->text('message')->nullable()->after('phone');
            $table->string('status', 32)->default('unknown')->after('message');
            $table->string('semaphore_message_id', 64)->nullable()->after('status');
            $table->text('error_message')->nullable()->after('semaphore_message_id');
        });
    }

    public function down(): void
    {
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'message',
                'status',
                'semaphore_message_id',
                'error_message',
            ]);
        });
    }
};
