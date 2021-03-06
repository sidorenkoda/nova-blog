<?php

namespace OptimistDigital\NovaBlog\Models;

use OptimistDigital\NovaBlog\NovaBlog;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $casts = [
        'published_at' => 'datetime',
        'data' => 'object'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(NovaBlog::getPostsTableName());
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            if ($post->is_pinned) {
                Post::where('is_pinned', true)->each(function ($pinnedPost) {
                    $pinnedPost->is_pinned = false;
                    $pinnedPost->save();
                });
            }
            return true;
        });
    }
}
