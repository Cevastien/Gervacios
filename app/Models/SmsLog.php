<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'phone_hash',
        'phone',
        'message',
        'status',
        'semaphore_message_id',
        'error_message',
        'template',
        'context',
        'created_at',
    ];

    protected $casts = [
        'context' => 'array',
        'created_at' => 'datetime',
    ];
}
