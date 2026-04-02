<?php

namespace Database\Seeders;

use App\Models\Seat;
use App\Models\Table;
use Illuminate\Database\Seeder;

/**
 * Positions seats as % of the floor plan (12×9 grid, same as tables floor_col/row).
 * Centers each cluster on the table cell; offsets spread chairs within ~5% of center.
 * Schematic only: does not trace a specific uploaded PNG — use admin “Add seats…” or edit pos_x/pos_y for real alignment.
 */
class SeatSeeder extends Seeder
{
    private const GRID_COLS = 12;

    private const GRID_ROWS = 9;

    private const MARGIN_X = 4.0;

    private const MARGIN_Y = 6.0;

    public function run(): void
    {
        $tables = Table::query()->orderBy('id')->get();

        foreach ($tables as $table) {
            [$cx, $cy] = $this->tableCenterPercent($table);
            $offsets = $this->seatOffsets((int) $table->capacity);

            foreach ($offsets as $i => [$dx, $dy]) {
                $seat = Seat::query()->firstOrNew([
                    'table_id' => $table->id,
                    'seat_index' => $i + 1,
                ]);
                $seat->pos_x = round($cx + $dx, 4);
                $seat->pos_y = round($cy + $dy, 4);
                if (! $seat->exists) {
                    $seat->status = 'free';
                }
                $seat->save();
            }
        }
    }

    /**
     * @return array{0: float, 1: float}
     */
    private function tableCenterPercent(Table $table): array
    {
        $col = $table->floor_col ?? 6;
        $row = $table->floor_row ?? 5;
        $spanC = max(1, (int) $table->floor_col_span);
        $spanR = max(1, (int) $table->floor_row_span);

        $usableW = 100 - (2 * self::MARGIN_X);
        $usableH = 100 - (2 * self::MARGIN_Y);

        $cx = self::MARGIN_X + (($col - 1) + $spanC / 2) / self::GRID_COLS * $usableW;
        $cy = self::MARGIN_Y + (($row - 1) + $spanR / 2) / self::GRID_ROWS * $usableH;

        return [$cx, $cy];
    }

    /**
     * Offsets in percentage points from table center (chair spread).
     *
     * @return list<array{0: float, 1: float}>
     */
    private function seatOffsets(int $capacity): array
    {
        return match ($capacity) {
            1 => [[0.0, 0.0]],
            2 => [[-2.0, 0.0], [2.0, 0.0]],
            3 => [[-2.2, 0.0], [0.0, 0.0], [2.2, 0.0]],
            4 => [
                [-2.0, -1.4],
                [2.0, -1.4],
                [-2.0, 1.4],
                [2.0, 1.4],
            ],
            5 => [
                [-2.4, -1.5],
                [0.0, -1.5],
                [2.4, -1.5],
                [-1.2, 1.6],
                [1.2, 1.6],
            ],
            6 => [
                [-2.6, -1.6],
                [0.0, -1.6],
                [2.6, -1.6],
                [-2.6, 1.6],
                [0.0, 1.6],
                [2.6, 1.6],
            ],
            default => $this->seatOffsetsGrid($capacity),
        };
    }

    /**
     * @return list<array{0: float, 1: float}>
     */
    private function seatOffsetsGrid(int $capacity): array
    {
        $cols = (int) ceil(sqrt($capacity));
        $rows = (int) ceil($capacity / $cols);
        $stepX = 2.4;
        $stepY = 2.0;
        $out = [];
        $n = 0;
        for ($r = 0; $r < $rows && $n < $capacity; $r++) {
            for ($c = 0; $c < $cols && $n < $capacity; $c++, $n++) {
                $ox = ($c - ($cols - 1) / 2) * $stepX;
                $oy = ($r - ($rows - 1) / 2) * $stepY;
                $out[] = [$ox, $oy];
            }
        }

        return $out;
    }
}
