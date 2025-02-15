<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'content',
        'source',
        'category',
        'author',
        'url',
        'news_service',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
