<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('settings', 'qr_image_path')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('qr_image_path')->nullable();
            });
        }

        if (! Schema::hasColumn('settings', 'qr_account_name')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('qr_account_name')->nullable();
            });
        }

        if (! Schema::hasColumn('settings', 'qr_account_number')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('qr_account_number')->nullable();
            });
        }

        if (! Schema::hasColumn('settings', 'qr_payment_label')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('qr_payment_label')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'qr_image_path')) {
                $table->dropColumn('qr_image_path');
            }
            if (Schema::hasColumn('settings', 'qr_account_name')) {
                $table->dropColumn('qr_account_name');
            }
            if (Schema::hasColumn('settings', 'qr_account_number')) {
                $table->dropColumn('qr_account_number');
            }
            if (Schema::hasColumn('settings', 'qr_payment_label')) {
                $table->dropColumn('qr_payment_label');
            }
        });
    }
};
