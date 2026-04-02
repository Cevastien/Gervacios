<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

/**
 * TableSeeder
 *
 * Creates 10 tables for the venue with floor-plan slots (12×9 grid, see migration).
 * T1 and T2 are accessible (left wall / near door in layout).
 */
class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['label' => 'T1', 'capacity' => 4, 'is_accessible' => true, 'accessible_features' => 'ground-floor,wide-aisle', 'position_x' => 1, 'position_y' => 3, 'shape' => 'circle', 'floor_col' => 1, 'floor_row' => 3, 'floor_col_span' => 1, 'floor_row_span' => 1],
            ['label' => 'T2', 'capacity' => 6, 'is_accessible' => true, 'accessible_features' => 'ground-floor,wide-aisle', 'position_x' => 1, 'position_y' => 4, 'shape' => 'circle', 'floor_col' => 1, 'floor_row' => 4, 'floor_col_span' => 1, 'floor_row_span' => 1],
            ['label' => 'T3', 'capacity' => 2, 'is_accessible' => false, 'accessible_features' => null, 'position_x' => 1, 'position_y' => 5, 'shape' => 'circle', 'floor_col' => 1, 'floor_row' => 5, 'floor_col_span' => 1, 'floor_row_span' => 1],
            ['label' => 'T4', 'capacity' => 4, 'is_accessible' => false, 'accessible_features' => null, 'position_x' => 1, 'position_y' => 6, 'shape' => 'circle', 'floor_col' => 1, 'floor_row' => 6, 'floor_col_span' => 1, 'floor_row_span' => 1],
            ['label' => 'T5', 'capacity' => 4, 'is_accessible' => false, 'accessible_features' => null, 'position_x' => 3, 'position_y' => 4, 'shape' => 'rect', 'floor_col' => 3, 'floor_row' => 4, 'floor_col_span' => 1, 'floor_row_span' => 1],
            ['label' => 'T6', 'capacity' => 6, 'is_accessible' => false, 'accessible_features' => null, 'position_x' => 5, 'position_y' => 4, 'shape' => 'rect', 'floor_col' => 5, 'floor_row' => 4, 'floor_col_span' => 1, 'floor_row_span' => 1],
            ['label' => 'T7', 'capacity' => 6, 'is_accessible' => false, 'accessible_features' => null, 'position_x' => 2, 'position_y' => 1, 'shape' => 'rect', 'floor_col' => 2, 'floor_row' => 1, 'floor_col_span' => 3, 'floor_row_span' => 2],
            ['label' => 'T8', 'capacity' => 2, 'is_accessible' => false, 'accessible_features' => null, 'position_x' => 3, 'position_y' => 5, 'shape' => 'rect', 'floor_col' => 3, 'floor_row' => 5, 'floor_col_span' => 1, 'floor_row_span' => 1],
            ['label' => 'T9', 'capacity' => 4, 'is_accessible' => false, 'accessible_features' => null, 'position_x' => 5, 'position_y' => 5, 'shape' => 'rect', 'floor_col' => 5, 'floor_row' => 5, 'floor_col_span' => 1, 'floor_row_span' => 1],
            ['label' => 'T10', 'capacity' => 6, 'is_accessible' => false, 'accessible_features' => null, 'position_x' => 4, 'position_y' => 6, 'shape' => 'rect', 'floor_col' => 4, 'floor_row' => 6, 'floor_col_span' => 2, 'floor_row_span' => 1],
        ];

        foreach ($tables as $tableData) {
            Table::create(array_merge([
                'venue_id' => 1,
                'status' => 'available',
            ], $tableData));
        }
    }
}
