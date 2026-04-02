<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'fb_post_id',
        'title',
        'excerpt',
        'content',
        'image_url',
        'fb_url',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
