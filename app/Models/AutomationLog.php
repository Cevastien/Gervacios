<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'task',
        'message',
        'payload',
        'success',
        'created_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'success' => 'boolean',
        'created_at' => 'datetime',
    ];

    public static function record(string $task, ?string $message = null, ?array $payload = null, bool $success = true): void
    {
        static::create([
            'task'       => $task,
            'message'    => $message,
            'payload'    => $payload,
            'success'    => $success,
            'created_at' => now(),
        ]);
    }
}
