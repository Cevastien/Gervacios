<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    protected $fillable = [
        'table_id',
        'seat_index',
        'status',
        'pos_x',
        'pos_y',
    ];

    protected function casts(): array
    {
        return [
            'pos_x' => 'float',
            'pos_y' => 'float',
        ];
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
