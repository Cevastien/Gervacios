<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->timestamp('cleaning_started_at')->nullable()->after('occupied_at');
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE tables MODIFY COLUMN status ENUM('available', 'occupied', 'cleaning', 'reserved', 'unavailable') NOT NULL DEFAULT 'available'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        DB::table('tables')->where('status', 'cleaning')->update([
            'status' => 'available',
            'cleaning_started_at' => null,
        ]);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE tables MODIFY COLUMN status ENUM('available', 'occupied', 'reserved', 'unavailable') NOT NULL DEFAULT 'available'");
        }

        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn('cleaning_started_at');
        });
    }
};
