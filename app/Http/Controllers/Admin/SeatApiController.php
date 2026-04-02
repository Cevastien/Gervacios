<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SeatApiController extends Controller
{
    public function index(): JsonResponse
    {
        $seats = Seat::query()
            ->with('table:id,label,capacity,furniture_type')
            ->orderBy('table_id')
            ->orderBy('seat_index')
            ->get();

        return response()->json([
            'seats' => $seats->map(fn (Seat $s) => [
                'id' => $s->id,
                'table_id' => $s->table_id,
                'table_label' => $s->table->label,
                'table_capacity' => (int) $s->table->capacity,
                'furniture_type' => $s->table->furniture_type ?? 'standard',
                'seat_index' => $s->seat_index,
                'status' => $s->status,
                'pos_x' => (float) $s->pos_x,
                'pos_y' => (float) $s->pos_y,
            ]),
        ]);
    }

    /**
     * Drop a new seat on the map at the given % position (creates a 1-seat table).
     * Capacity is guest/party size for bookings (may be &gt; 1 with a single marker until more dots are added).
     */
    public function place(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pos_x' => ['required', 'numeric', 'min:0', 'max:100'],
            'pos_y' => ['required', 'numeric', 'min:0', 'max:100'],
            'label' => ['nullable', 'string', 'max:50'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'furniture_type' => ['nullable', 'string', 'max:32'],
            'status' => ['nullable', 'string', 'in:free,reserved,occupied'],
        ]);

        $result = DB::transaction(function () use ($validated) {
            $label = isset($validated['label']) && trim((string) $validated['label']) !== ''
                ? trim((string) $validated['label'])
                : $this->nextDefaultTableLabel();

            $furniture = trim((string) ($validated['furniture_type'] ?? 'standard'));
            if ($furniture === '') {
                $furniture = 'standard';
            }

            $capacity = (int) ($validated['capacity'] ?? 1);

            $table = Table::query()->create([
                'venue_id' => 1,
                'label' => $label,
                'capacity' => $capacity,
                'status' => 'available',
                'shape' => 'rect',
                'furniture_type' => $furniture,
            ]);

            $seatStatus = isset($validated['status']) && $validated['status'] !== ''
                ? $validated['status']
                : 'free';

            $seat = Seat::query()->create([
                'table_id' => $table->id,
                'seat_index' => 1,
                'status' => $seatStatus,
                'pos_x' => round((float) $validated['pos_x'], 4),
                'pos_y' => round((float) $validated['pos_y'], 4),
            ]);

            return [
                'table_id' => $table->id,
                'table_label' => $table->label,
                'seat_id' => $seat->id,
                'pos_x' => (float) $seat->pos_x,
                'pos_y' => (float) $seat->pos_y,
            ];
        });

        return response()->json([
            'ok' => true,
            'seat' => $result,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'seat_id' => ['required', 'integer', 'exists:seats,id'],
            'status' => ['nullable', 'string', 'in:free,reserved,occupied'],
            'label' => ['nullable', 'string', 'max:50'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'furniture_type' => ['nullable', 'string', 'max:32'],
        ]);

        $hasStatus = isset($validated['status']) && $validated['status'] !== null && $validated['status'] !== '';
        $hasLabel = $request->filled('label');
        $hasCapacity = $request->filled('capacity');
        $hasFurniture = $request->filled('furniture_type');

        if (! $hasStatus && ! $hasLabel && ! $hasCapacity && ! $hasFurniture) {
            throw ValidationException::withMessages([
                'seat_id' => ['Choose a status and/or table details to save.'],
            ]);
        }

        $seat = Seat::query()->findOrFail($validated['seat_id']);
        $tableId = $seat->table_id;
        $table = Table::query()->findOrFail($tableId);

        DB::transaction(function () use ($table, $tableId, $validated, $hasStatus, $hasLabel, $hasCapacity, $hasFurniture) {
            if ($hasStatus) {
                Seat::query()
                    ->where('table_id', $tableId)
                    ->update(['status' => $validated['status']]);
            }

            $meta = [];
            if ($hasLabel) {
                $meta['label'] = trim((string) $validated['label']);
            }
            if ($hasCapacity) {
                $seatCount = Seat::query()->where('table_id', $tableId)->count();
                $cap = (int) $validated['capacity'];
                if ($cap < $seatCount) {
                    throw ValidationException::withMessages([
                        'capacity' => ["Capacity must be at least the number of seats on the map ({$seatCount})."],
                    ]);
                }
                $meta['capacity'] = $cap;
            }
            if ($hasFurniture) {
                $ft = trim((string) $validated['furniture_type']);
                $meta['furniture_type'] = $ft === '' ? 'standard' : $ft;
            }

            if ($meta !== []) {
                $table->update($meta);
            }
        });

        $table->refresh();
        $seats = Seat::query()
            ->where('table_id', $tableId)
            ->orderBy('seat_index')
            ->get(['id', 'status', 'seat_index']);

        return response()->json([
            'ok' => true,
            'seats' => $seats->map(fn (Seat $s) => [
                'id' => $s->id,
                'status' => $s->status,
                'seat_index' => $s->seat_index,
            ])->values()->all(),
            'table' => [
                'id' => $table->id,
                'label' => $table->label,
                'capacity' => (int) $table->capacity,
                'furniture_type' => $table->furniture_type ?? 'standard',
            ],
        ]);
    }

    /**
     * Remove one seat dot, or the whole table (all dots). Blocked if the table has bookings.
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'seat_id' => ['required', 'integer', 'exists:seats,id'],
            'scope' => ['required', 'string', 'in:seat,table'],
        ]);

        $seat = Seat::query()->findOrFail($validated['seat_id']);
        $table = Table::query()->findOrFail($seat->table_id);

        if ($table->bookings()->exists()) {
            throw ValidationException::withMessages([
                'seat_id' => ['This table has bookings and cannot be removed.'],
            ]);
        }

        $scope = $validated['scope'];
        $tableId = $table->id;

        DB::transaction(function () use ($seat, $table, $scope) {
            if ($scope === 'table') {
                $table->delete();

                return;
            }

            if ($table->seats()->count() <= 1) {
                $table->delete();

                return;
            }

            $tid = $table->id;
            $seat->delete();

            $remaining = Seat::query()->where('table_id', $tid)->orderBy('seat_index')->get();
            $i = 1;
            foreach ($remaining as $s) {
                $s->update(['seat_index' => $i]);
                $i++;
            }

            $table->update(['capacity' => $remaining->count()]);
        });

        $tableStillExists = Table::query()->where('id', $tableId)->exists();

        return response()->json([
            'ok' => true,
            'removed_table_id' => $tableStillExists ? null : $tableId,
        ]);
    }

    /**
     * Assign selected seats to a new table (group). Seat positions (pos_x/pos_y) are unchanged.
     */
    public function group(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'seat_ids' => ['required', 'array', 'min:1'],
            'seat_ids.*' => ['integer', 'distinct', 'exists:seats,id'],
            'label' => ['nullable', 'string', 'max:50'],
        ]);

        $seatIds = array_values(array_unique($validated['seat_ids']));

        $result = DB::transaction(function () use ($seatIds, $validated) {
            $seats = Seat::query()
                ->whereIn('id', $seatIds)
                ->lockForUpdate()
                ->get();

            if ($seats->count() !== count($seatIds)) {
                throw ValidationException::withMessages([
                    'seat_ids' => ['Some seats were not found.'],
                ]);
            }

            $oldTableIds = $seats->pluck('table_id')->unique()->values()->all();

            $label = isset($validated['label']) && $validated['label'] !== ''
                ? trim($validated['label'])
                : $this->nextDefaultTableLabel();

            // Guest capacity: sum each merged table's capacity once (not "number of dots").
            // Floor at dot count so capacity is never below physical markers on the map.
            $sumMergedGuestCap = (int) Table::query()
                ->whereIn('id', $oldTableIds)
                ->sum('capacity');
            $mergedCapacity = max(count($seatIds), $sumMergedGuestCap);

            $table = Table::query()->create([
                'venue_id' => 1,
                'label' => $label,
                'capacity' => $mergedCapacity,
                'status' => 'available',
                'shape' => 'rect',
                'furniture_type' => 'standard',
            ]);

            $index = 1;
            foreach ($seatIds as $sid) {
                $seat = $seats->firstWhere('id', $sid);
                $seat->update([
                    'table_id' => $table->id,
                    'seat_index' => $index,
                ]);
                $index++;
            }

            foreach ($oldTableIds as $oldId) {
                if ((int) $oldId === (int) $table->id) {
                    continue;
                }
                $old = Table::query()->find($oldId);
                if (! $old) {
                    continue;
                }
                if ($old->seats()->count() === 0 && $old->bookings()->count() === 0) {
                    $old->delete();
                }
            }

            return [
                'table_id' => $table->id,
                'table_label' => $table->label,
            ];
        });

        return response()->json([
            'ok' => true,
            'table' => $result,
        ]);
    }

    private function nextDefaultTableLabel(): string
    {
        $max = 0;
        foreach (Table::query()->pluck('label') as $l) {
            if (preg_match('/^T(\d+)$/i', (string) $l, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return 'T'.($max + 1);
    }
}
