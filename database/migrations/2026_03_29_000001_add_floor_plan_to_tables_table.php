<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Floor plan uses a 12×9 CSS grid (columns 1–12, rows 1–9, top = row 1).
     * Labels T1…T10 are positioned to match the venue sketch (bar top, door bottom-left, kitchen/VIP right).
     */
    public function up(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->unsignedTinyInteger('floor_col')->nullable()->after('shape');
            $table->unsignedTinyInteger('floor_row')->nullable()->after('floor_col');
            $table->unsignedTinyInteger('floor_col_span')->default(1)->after('floor_row');
            $table->unsignedTinyInteger('floor_row_span')->default(1)->after('floor_col_span');
        });

        $plan = [
            'T1' => ['floor_col' => 1, 'floor_row' => 3, 'floor_col_span' => 1, 'floor_row_span' => 1, 'shape' => 'circle'],
            'T2' => ['floor_col' => 1, 'floor_row' => 4, 'floor_col_span' => 1, 'floor_row_span' => 1, 'shape' => 'circle'],
            'T3' => ['floor_col' => 1, 'floor_row' => 5, 'floor_col_span' => 1, 'floor_row_span' => 1, 'shape' => 'circle'],
            'T4' => ['floor_col' => 1, 'floor_row' => 6, 'floor_col_span' => 1, 'floor_row_span' => 1, 'shape' => 'circle'],
            'T5' => ['floor_col' => 3, 'floor_row' => 4, 'floor_col_span' => 1, 'floor_row_span' => 1, 'shape' => 'rect'],
            'T6' => ['floor_col' => 5, 'floor_row' => 4, 'floor_col_span' => 1, 'floor_row_span' => 1, 'shape' => 'rect'],
            'T7' => ['floor_col' => 2, 'floor_row' => 1, 'floor_col_span' => 3, 'floor_row_span' => 2, 'shape' => 'rect'],
            'T8' => ['floor_col' => 3, 'floor_row' => 5, 'floor_col_span' => 1, 'floor_row_span' => 1, 'shape' => 'rect'],
            'T9' => ['floor_col' => 5, 'floor_row' => 5, 'floor_col_span' => 1, 'floor_row_span' => 1, 'shape' => 'rect'],
            'T10' => ['floor_col' => 4, 'floor_row' => 6, 'floor_col_span' => 2, 'floor_row_span' => 1, 'shape' => 'rect'],
        ];

        foreach ($plan as $label => $coords) {
            DB::table('tables')->where('label', $label)->update($coords);
        }
    }

    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn(['floor_col', 'floor_row', 'floor_col_span', 'floor_row_span']);
        });
    }
};
