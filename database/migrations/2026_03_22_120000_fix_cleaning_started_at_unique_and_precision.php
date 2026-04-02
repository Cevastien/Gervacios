<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * cleaning_started_at must NOT be unique — multiple tables can enter cleaning at the same second.
     * Some local DBs may have picked up a mistaken UNIQUE index; drop it and widen precision where possible.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            $indexes = DB::select("SELECT name, sql FROM sqlite_master WHERE type='index' AND tbl_name='tables' AND sql IS NOT NULL");
            foreach ($indexes as $idx) {
                $sql = (string) ($idx->sql ?? '');
                if (stripos($sql, 'cleaning_started_at') === false) {
                    continue;
                }
                if (stripos($sql, 'UNIQUE') !== false || preg_match('/\bunique\b/i', $sql)) {
                    $name = (string) $idx->name;
                    DB::statement('DROP INDEX IF EXISTS "'.$name.'"');
                }
            }
        }

        if ($driver === 'mysql') {
            $rows = DB::select('SHOW INDEX FROM `tables`');
            $dropped = [];
            foreach ($rows as $row) {
                if (($row->Column_name ?? '') !== 'cleaning_started_at') {
                    continue;
                }
                if ((int) ($row->Non_unique ?? 1) !== 0) {
                    continue;
                }
                $key = $row->Key_name ?? null;
                if ($key === null || $key === 'PRIMARY' || isset($dropped[$key])) {
                    continue;
                }
                $dropped[$key] = true;
                DB::statement('ALTER TABLE `tables` DROP INDEX `'.$key.'`');
            }

            try {
                DB::statement('ALTER TABLE `tables` MODIFY `cleaning_started_at` DATETIME(6) NULL');
            } catch (\Throwable) {
                // Older MySQL / no fractional support — ignore
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            try {
                DB::statement('ALTER TABLE `tables` MODIFY `cleaning_started_at` DATETIME NULL');
            } catch (\Throwable) {
                //
            }
        }
    }
};
