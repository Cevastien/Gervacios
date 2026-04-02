<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'payload',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'is_read' => 'boolean',
        ];
    }
}
