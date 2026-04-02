<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Align existing waiting/notified rows with decoupled rules: senior/pregnant are never ♿-restricted;
     * PWD follows queue_pwd_requires_accessible_table (default off).
     */
    public function up(): void
    {
        DB::table('queue_entries')
            ->whereIn('priority_type', ['senior', 'pregnant'])
            ->whereIn('status', ['waiting', 'notified'])
            ->update(['needs_accessible' => false]);

        if (Setting::get('queue_pwd_requires_accessible_table', '0') !== '1') {
            DB::table('queue_entries')
                ->where('priority_type', 'pwd')
                ->whereIn('status', ['waiting', 'notified'])
                ->update(['needs_accessible' => false]);
        }
    }

    public function down(): void
    {
        // Prior semantics tied priority score to needs_accessible; no reliable restore.
    }
};
